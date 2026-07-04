<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $products = Product::with([
                'categories',
                'photos'
            ])
            ->when($search, function ($query) use ($search) {

                $query->where('product_code', 'like', "%{$search}%")
                    ->orWhere('product_name', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");

            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'inventory',
            compact(
                'products',
                'search'
            )
        );
    }
}

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\Product;

// class InventoryController extends Controller
// {
//     public function index(Request $request)
//     {
//         $search = $request->search;

//         $products = Product::with('categories')

//             ->when($search, function ($query) use ($search) {

//                 $query->where('product_code', 'like', "%{$search}%")
//                     ->orWhere('product_name', 'like', "%{$search}%")
//                     ->orWhere('barcode', 'like', "%{$search}%");

//             })

//             ->latest()
//             ->paginate(10)
//             ->withQueryString();

//         return view(
//             'inventory',
//             compact(
//                 'products',
//                 'search'
//             )
//         );
//     }
// }