<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stores;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\StockMovement;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function create()
    {
        return view('transactions.create');
    }

    public function searchProduct(Request $request)
    {
        // Fleksibel: mengambil dari parameter 'keyword' atau 'barcode'
        $keyword = $request->keyword ?? $request->barcode;

        $products = Product::query()
            ->where('is_active', true)
            ->where(function ($query) use ($keyword) {
                $query->where('product_name', 'like', "%{$keyword}%")
                    ->orWhere('product_code', 'like', "%{$keyword}%")
                    ->orWhere('barcode', 'like', "%{$keyword}%");
            })
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    public function getProduct(Product $product)
    {
        $product->load('photos');

        return response()->json([
            'id' => $product->id,
            'product_name' => $product->product_name,
            'product_code' => $product->product_code,
            'price' => $product->selling_price,
            'stock' => $product->stock,
            'photo' => $product->photos->first()
                ? asset('storage/products/' . $product->photos->first()->photo)
                : null
        ]);
    }

    public function addTransactionItem(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        $qty = (int) $request->qty;
        $discount = (float) $request->discount;

        $transactionItems = session()->get('transaction_items', []);

        $existingKey = null;
        foreach ($transactionItems as $key => $item) {
            if ($item['product_id'] == $product->id) {
                $existingKey = $key;
                break;
            }
        }

        if ($existingKey !== null) {
            $newQty = $transactionItems[$existingKey]['qty'] + $qty;
            $newDiscount = $transactionItems[$existingKey]['discount'] + $discount;
            
            if ($newQty > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => "Stok tidak mencukupi. Stok sisa: {$product->stock}, total di keranjang akan menjadi: {$newQty}"
                ], 422);
            }

            $subtotal = ($product->selling_price * $newQty) - $newDiscount;

            $transactionItems[$existingKey]['qty'] = $newQty;
            $transactionItems[$existingKey]['discount'] = $newDiscount;
            $transactionItems[$existingKey]['subtotal'] = $subtotal;
        } else {
            if ($qty > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => "Stock tidak mencukupi. Sisa: {$product->stock}"
                ], 422);
            }

            $subtotal = ($product->selling_price * $qty) - $discount;

            $transactionItems[] = [
                'product_id'   => $product->id,
                'product_name' => $product->product_name,
                'price'        => $product->selling_price,
                'qty'          => $qty,
                'discount'     => $discount,
                'subtotal'     => $subtotal,
            ];
        }

        session()->put('transaction_items', $transactionItems);

        return response()->json([
            'success' => true
        ]);
    }

    public function getTransactionItem()
    {
        return response()->json(
            session()->get('transaction_items', [])
        );
    }

    public function removeTransactionItem($index)
    {
        $transactionItems = session()->get('transaction_items', []);

        unset($transactionItems[$index]);

        session()->put(
            'transaction_items',
            array_values($transactionItems)
        );

        return response()->json([
            'success' => true
        ]);
    }

    public function clearTransactionItems()
    {
        session()->forget('transaction_items');

        return response()->json([
            'success' => true
        ]);
    }

    public function store(Request $request)
    {
        $transactionItems = session()->get('transaction_items', []);

        if (count($transactionItems) < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada item transaksi'
            ], 422);
        }

        DB::beginTransaction();

        try {
            $grandtotal = collect($transactionItems)->sum('subtotal');
            $totalDiscount = collect($transactionItems)->sum('discount');
            $subtotal = $grandtotal + $totalDiscount;

            $invoiceNumber = 'INV-' . now()->format('YmdHis');
            $store = Stores::first();

            if (!$store) {
                return response()->json([
                    'success' => false,
                    'message' => 'Store belum tersedia'
                ], 422);
            }

            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => Auth::id(),
                'store_id' => $store->id,
                'transaction_date' => now(),
                'subtotal' => $subtotal,
                'discount' => $totalDiscount,
                'tax' => 0,
                'grand_total' => $grandtotal,
                'payment_method' => $request->payment_method,
                'paid_amount' => $request->paid_amount,
                'change_amount' => $request->change_amount,
                'status' => 'paid',
            ]);

            foreach ($transactionItems as $item) {
                $product = Product::findOrFail($item['product_id']);
                $stockBefore = $product->stock;
                $stockAfter = $stockBefore - $item['qty'];

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['qty'],
                    'cost_price' => $product->cost_price,
                    'selling_price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                $product->update([
                    'stock' => $stockAfter
                ]);

                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'sale',
                    'quantity' => $item['qty'],
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'notes' => 'Sales ' . $invoiceNumber
                ]);
            }

            session()->forget('transaction_items');
            DB::commit();

            return response()->json([
                'success' => true,
                'invoice' => $invoiceNumber
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function salesReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());
        $paymentMethod = $request->get('payment_method');
        $userId = $request->get('user_id');
        $search = $request->get('search');

        $baseQuery = Transaction::whereBetween('transaction_date', [
            $startDate . ' 00:00:00', 
            $endDate . ' 23:59:59'
        ]);

        if (!empty($paymentMethod)) {
            $baseQuery->where('payment_method', $paymentMethod);
        }

        if (!empty($userId)) {
            $baseQuery->where('user_id', $userId);
        }

        if (!empty($search)) {
            $baseQuery->where('invoice_number', 'like', "%{$search}%");
        }

        $summaryQuery = clone $baseQuery;
        $totalSales = $summaryQuery->sum('grand_total');
        $totalTransactions = $summaryQuery->count();
        $totalDiscount = $summaryQuery->sum('discount');

        $cashiers = User::orderBy('name', 'asc')->get(); 

        $transactions = $baseQuery->with(['user'])
            ->orderBy('transaction_date', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('transactions.sales', compact(
            'transactions', 
            'totalSales', 
            'totalTransactions', 
            'totalDiscount', 
            'startDate', 
            'endDate',
            'cashiers'
        ));
    }

    public function showDetail($id)
    {
        try {
            $transaction = Transaction::find($id);

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan.'
                ], 404);
            }

            $transaction->load(['details.product', 'user']);

            return response()->json([
                'success' => true,
                'invoice'       => $transaction->invoice_number,
                'cashier'       => $transaction->user->name ?? 'System',
                'date'          => \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y, H:i'),
                'payment_method'=> $transaction->payment_method,
                'subtotal'      => (float) $transaction->subtotal,
                'discount'      => (float) $transaction->discount,
                'grand_total'   => (float) $transaction->grand_total,
                'paid_amount'   => (float) $transaction->paid_amount,
                'change_amount' => (float) $transaction->change_amount,
                'details'       => collect($transaction->details)->map(function ($detail) {
                    return [
                        'product_name' => optional($detail->product)->product_name ?? 'Produk Tidak Diketahui',
                        'qty'          => (int) $detail->quantity,
                        'price'        => (float) $detail->selling_price,
                        'subtotal'     => (float) $detail->subtotal
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi error di server: ' . $e->getMessage()
            ], 500);
        }
    }
}