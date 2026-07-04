<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorPNG;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductPhoto;


class ProductController extends Controller
{   
    public function searchCategory(Request $request)
    {
        $search = $request->search;

        $categories = Category::query()

            ->when($search, function ($query) use ($search) {

                $query->where(
                    'category_name',
                    'like',
                    "%{$search}%"
                );

            })

            ->limit(10)
            ->get();

        return response()->json($categories);
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required'
        ]);

        $category = Category::create([
            'category_name' => $request->category_name
        ]);

        return response()->json($category);
    }
    public function index(Request $request)
    {
        $products = Product::with('categories')
            ->when($request->search, function ($query) use ($request) {
                $query->where('product_name', 'like', '%' . $request->search . '%')
                      ->orWhere('product_code', 'like', '%' . $request->search . '%');
            })
            ->paginate(10);

        return view('products.inventory', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('products.create', compact('categories'));
    }
    private function generateBarcodeNumber(): string{
        do {

            $barcode = str_pad(
                rand(0, 99999999),
                8,
                '0',
                STR_PAD_LEFT
            );

        } while (
            Product::where('barcode', $barcode)->exists()
        );

        return $barcode;
    }

    // Generate product code based on product name
    private function generateProductCode(string $productName): string
    {
        $cleanName = preg_replace(
            '/[^A-Za-z]/',
            '',
            strtoupper($productName)
        );

        if(strlen($cleanName) < 3)
        {
            $cleanName .= 'XXX';
        }

        $firstTwo =
            substr($cleanName, 0, 2);

        $lastOne =
            substr($cleanName, -1);

        $randomNumber =
            str_pad(
                rand(0, 9999),
                4,
                '0',
                STR_PAD_LEFT
            );

        $randomLetters =
            strtoupper(
                Str::random(2)
            );

        return sprintf(
            'PRX-%s%s%s%s',
            $firstTwo,
            $randomNumber,
            $lastOne,
            $randomLetters
        );
    }
    // 
    

    // generate barcode image based on product code
    private function generateBarcodeImage(string $barcode): string{
        $fileName = 'barcode_' . time() . '_' . Str::random(5) . '.svg';
        $path = "barcodes/{$fileName}";

        $dns = new \Milon\Barcode\DNS1D();

        $svg = $dns->getBarcodeSVG(
            $barcode,
            'C128',
            2,
            80
        );

        Storage::disk('public')->put($path, $svg);

        return $fileName;
    }
    // 


    // add product to database
    public function store(Request $request){
        $request->validate([

            'product_name' => 'required',

            'cost_price' => 'required|numeric',

            'selling_price' => 'required|numeric',

            'stock' => 'required|integer',

            'minimum_stock' => 'required|integer',

        ]);

        DB::transaction(function () use ($request) {

            $productCode = $this->generateProductCode(
                $request->product_name
            );

            $barcode = $this->generateBarcodeNumber();

            $barcodePhoto = $this->generateBarcodeImage($barcode);

            $product = Product::create([

                'product_code' => $productCode,

                'barcode' => $barcode,

                'barcode_photo' => $barcodePhoto,

                'is_active' => true,

                'product_name' => $request->product_name,

                'description' => $request->description,

                'cost_price' => $request->cost_price,

                'selling_price' => $request->selling_price,

                'stock' => $request->stock,

                'minimum_stock' => $request->minimum_stock,

            ]);

            /*
            |--------------------------------------------------------------------------
            | CATEGORY
            |--------------------------------------------------------------------------
            */

            $categoryIds = [];

            foreach ($request->categories ?? [] as $name) {

                $category = Category::firstOrCreate([
                    'category_name' => trim($name)
                ]);

                $categoryIds[] = $category->id;
            }

            $product->categories()
                ->sync($categoryIds);

            /*
            |--------------------------------------------------------------------------
            | PHOTO
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('images')) {

                foreach ($request->file('images') as $image) {

                    $fileName =
                        time()
                        . '_'
                        . Str::random(10)
                        . '.'
                        . $image->getClientOriginalExtension();

                    $image->storeAs(
                        'products',
                        $fileName,
                        'public'
                    );

                    ProductPhoto::create([

                        'product_id' => $product->id,

                        'photo' => $fileName

                    ]);
                }
            }
        });

        return redirect()
            ->route('inventory')
            ->with(
                'success',
                'Product berhasil ditambahkan'
            );
    }
    // 


    public function show(Product $product){
        $product->load([
            'photos',
            'categories'
        ]);

        return view(
            'products.detail',
            compact('product')
        );
    }

    public function edit(Product $product){
        $product->load([
            'photos',
            'categories'
        ]);

        $categories = Category::all();

        return view(
            'products.edit',
            compact(
                'product',
                'categories'
            )
        );
    }

    public function update(Request $request, Product $product){
        DB::transaction(function () use ($request, $product) {

            $product->update([
                'product_name' => $request->product_name,
                'description' => $request->description,
                'cost_price' => $request->cost_price,
                'selling_price' => $request->selling_price,
                'stock' => $request->stock,
                'minimum_stock' => $request->minimum_stock,
            ]);

            /*
            |--------------------------------------------------------------------------
            | CATEGORY
            |--------------------------------------------------------------------------
            */

            $categoryIds = [];

            foreach ($request->categories ?? [] as $name) {

                $category = Category::firstOrCreate([
                    'category_name' => trim($name)
                ]);

                $categoryIds[] = $category->id;
            }

            $product->categories()->sync($categoryIds);

            /*
            |--------------------------------------------------------------------------
            | DELETE PHOTO
            |--------------------------------------------------------------------------
            */

            if ($request->deleted_photos) {

                foreach ($request->deleted_photos as $photoId) {

                    $photo = ProductPhoto::find($photoId);

                    if (!$photo) {
                        continue;
                    }

                    $photoPath = 'products/' . $photo->photo;

                    if (
                        Storage::disk('public')
                            ->exists($photoPath)
                    ) {
                        Storage::disk('public')
                            ->delete($photoPath);
                    }

                    $photo->delete();
                }
            }

            /*
            |--------------------------------------------------------------------------
            | ADD NEW PHOTO
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('images')) {

                foreach ($request->file('images') as $image) {

                    $fileName =
                        time()
                        . '_'
                        . Str::random(10)
                        . '.'
                        . $image->getClientOriginalExtension();

                    $image->storeAs(
                        'products',
                        $fileName,
                        'public'
                    );

                    ProductPhoto::create([
                        'product_id' => $product->id,
                        'photo' => $fileName
                    ]);
                }
            }

        });

        return redirect()
            ->route('inventory')
            ->with(
                'success',
                'Product berhasil diupdate'
            );
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('inventory')
            ->with('success', 'Product deleted successfully.');
    }
}