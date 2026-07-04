@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Laporan Keuangan Penjualan</h1>
        <p class="text-sm text-gray-600">Pantau performa pendapatan dan transaksi tokomu.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Penjualan</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
            </div>
            <div class="p-3 rounded-full bg-green-100 text-green-600"><i class="fa fa-wallet text-2xl"></i></div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Transaksi</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $totalTransactions }} Transaksi</h3>
            </div>
            <div class="p-3 rounded-full bg-blue-100 text-blue-600"><i class="fa fa-shopping-bag text-2xl"></i></div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Diskon</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalDiscount, 0, ',', '.') }}</h3>
            </div>
            <div class="p-3 rounded-full bg-red-100 text-red-600"><i class="fa fa-tags text-2xl"></i></div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden p-6">
        
        <form method="GET" action="{{ route('transactions.sales') }}" class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-6">
            
            <div class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">Dari</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="mt-1 block w-40 h-9 rounded-full border-gray-200 bg-[#f4f4f5] text-xs font-semibold text-gray-700 px-4 focus:outline-none focus:border-gray-300 focus:ring-0">
                </div>
                
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">Sampai</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="mt-1 block w-40 h-9 rounded-full border-gray-200 bg-[#f4f4f5] text-xs font-semibold text-gray-700 px-4 focus:outline-none focus:border-gray-300 focus:ring-0">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">Metode</label>
                    <select name="payment_method" onchange="this.form.submit()" class="mt-1 block w-32 h-9 rounded-full border-gray-200 bg-[#f4f4f5] text-xs font-bold text-gray-600 px-4 focus:outline-none focus:border-gray-300 focus:ring-0 appearance-none cursor-pointer">
                        <option value="">Semua</option>
                        <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>CASH</option>
                        <option value="qris" {{ request('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">Kasir</label>
                    <select name="user_id" onchange="this.form.submit()" class="mt-1 block w-40 h-9 rounded-full border-gray-200 bg-[#f4f4f5] text-xs font-bold text-gray-600 px-4 focus:outline-none focus:border-gray-300 focus:ring-0 appearance-none cursor-pointer">
                        <option value="">Semua Kasir</option>
                        @foreach($cashiers as $cashier)
                            <option value="{{ $cashier->id }}" {{ request('user_id') == $cashier->id ? 'selected' : '' }}>
                                {{ $cashier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex items-center gap-2">
                    <button type="submit" class="h-9 bg-[#5e5a52] hover:bg-[#4d4a43] text-white px-4 rounded-full text-xs font-bold shadow-sm transition flex items-center gap-1.5 whitespace-nowrap">
                        <i class="fa fa-filter text-[10px]"></i> Apply
                    </button>
                    
                    @if(request()->anyFilled(['start_date', 'end_date', 'payment_method', 'user_id', 'search']))
                        <a href="{{ route('transactions.sales') }}" class="h-9 px-4 inline-flex items-center justify-center text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 rounded-full transition shadow-sm whitespace-nowrap">
                            <i class="fa fa-sync text-[10px] mr-1.5"></i> Reset
                        </a>
                    @endif
                </div>
            </div>

            <div class="flex items-end gap-3 w-full lg:w-auto">
                <div class="w-full lg:w-64">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">Cari Invoice</label>
                    <div class="relative mt-1">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari No. Invoice..." 
                               class="w-full h-9 rounded-full border border-gray-200 pl-4 pr-10 text-xs bg-white text-gray-700 placeholder-gray-400 focus:outline-none focus:border-gray-300 focus:ring-0">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fa fa-search text-xs"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <a href="{{ route('transactions.create') }}" class="h-9 px-4 inline-flex items-center justify-center text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-full transition shadow-sm whitespace-nowrap gap-1.5">
                        <i class="fa fa-plus text-[10px]"></i> Tambah Transaksi
                    </a>
                </div>
            </div>
        </form>

        <div class="overflow-hidden border border-gray-200 rounded-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#e4e4e6] text-black text-xs font-extrabold tracking-wider border-b border-gray-200">
                            <th class="px-6 py-3.5 text-center w-12">No</th>
                            <th class="px-6 py-3.5">Waktu</th>
                            <th class="px-6 py-3.5">No. Invoice</th>
                            <th class="px-6 py-3.5">Kasir</th>
                            <th class="px-6 py-3.5">Metode</th>
                            <th class="px-4 py-3.5 text-right">Sub Total</th>
                            <th class="px-4 py-3.5 text-right">Diskon</th>
                            <th class="px-4 py-3.5 text-right">Grand Total</th>
                            <th class="px-4 py-3.5 text-center">Stok Status</th>
                            <th class="px-6 py-3.5 text-center w-16">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                        @forelse($transactions as $index => $trx)
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-6 py-4 text-center text-gray-600 font-medium">
                                    {{ $transactions->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                    {{ \Carbon\Carbon::parse($trx->transaction_date)->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 font-mono text-indigo-600 font-semibold">
                                    {{ $trx->invoice_number }}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $trx->user->name ?? 'System' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded text-xs font-semibold uppercase {{ $trx->payment_method === 'cash' ? 'bg-amber-100 text-amber-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $trx->payment_method }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right text-gray-600 font-medium">
                                    Rp {{ number_format($trx->subtotal, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-red-500 font-medium">
                                    -Rp {{ number_format($trx->discount, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right font-bold text-gray-900">
                                    Rp {{ number_format($trx->grand_total, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-block bg-[#eefbf3] text-[#429e69] px-2.5 py-1 rounded-md text-xs font-bold min-w-[38px]">
                                        100
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button" onclick="openDetailModal({{ $trx->id }})" class="w-8 h-8 inline-flex items-center justify-center bg-[#f0eff2] hover:bg-gray-200 text-gray-600 rounded-lg transition">
                                        <i class="fa fa-ellipsis-v text-xs"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-12 text-center text-gray-400">
                                    Tidak ada data transaksi yang sesuai dengan kriteria filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-center items-center gap-6 mt-8 select-none">
            <div class="flex items-center gap-2">
                {{-- Tombol Previous --}}
                @if($transactions->onFirstPage())
                    <div class="w-8 h-8 rounded-full bg-[#dbdad7] flex items-center justify-center text-gray-400 cursor-not-allowed text-xs font-bold">
                        &lt;
                    </div>
                @else
                    <a href="{{ $transactions->appends(request()->query())->previousPageUrl() }}" 
                       class="w-8 h-8 rounded-full bg-[#dbdad7] hover:bg-gray-300 flex items-center justify-center text-gray-700 text-xs font-bold transition">
                        &lt;
                    </a>
                @endif

                {{-- Angka Indikator Halaman Aktif --}}
                <div class="bg-[#e4e3e0] text-gray-800 text-xs font-bold rounded-full h-8 px-5 flex items-center justify-center min-w-[56px]">
                    {{ $transactions->currentPage() }}
                </div>

                {{-- Tombol Next --}}
                @if($transactions->hasMorePages())
                    <a href="{{ $transactions->appends(request()->query())->nextPageUrl() }}" 
                       class="w-8 h-8 rounded-full bg-[#dbdad7] hover:bg-gray-300 flex items-center justify-center text-gray-700 text-xs font-bold transition">
                        &gt;
                    </a>
                @else
                    <div class="w-8 h-8 rounded-full bg-[#dbdad7] flex items-center justify-center text-gray-400 cursor-not-allowed text-xs font-bold">
                        &gt;
                    </div>
                @endif
            </div>

            <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-2 text-xs font-semibold text-gray-500">
                @foreach(request()->except('page') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach

                <span>Go to :</span>

                <input type="number" 
                       name="page" 
                       min="1" 
                       max="{{ $transactions->lastPage() }}"
                       value="{{ $transactions->currentPage() }}"
                       class="w-12 h-8 rounded-full border border-gray-300 text-center bg-white text-gray-800 focus:outline-none focus:border-gray-400 font-bold p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">

                <button type="submit" 
                        {{ $transactions->lastPage() <= 1 ? 'disabled' : '' }}
                        class="bg-[#5e5a52] hover:bg-[#4d4a43] disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed text-white px-4 h-8 rounded-xl font-bold text-xs shadow-sm transition">
                    Go
                </button>
            </form>
        </div>

    </div>
</div>

<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full overflow-hidden border border-gray-100">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fa fa-file-invoice text-indigo-600"></i> Rincian Transaksi
            </h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 gap-4 text-xs mb-6 bg-indigo-50 p-4 rounded-lg text-gray-700">
                <div>
                    <p class="mb-1"><span class="font-semibold text-gray-500">No. Invoice:</span> <span id="modalInvoice" class="font-mono font-bold text-indigo-700"></span></p>
                    <p><span class="font-semibold text-gray-500">Tanggal:</span> <span id="modalDate"></span></p>
                </div>
                <div>
                    <p class="mb-1"><span class="font-semibold text-gray-500">Kasir:</span> <span id="modalCashier"></span></p>
                    <p><span class="font-semibold text-gray-500">Metode Bayar:</span> <span id="modalMethod" class="uppercase font-semibold"></span></p>
                </div>
            </div>

            <div class="border border-gray-100 rounded-lg overflow-hidden mb-4">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 font-semibold border-b">
                            <th class="p-3">Nama Produk</th>
                            <th class="p-3 text-center">Qty</th>
                            <th class="p-3 text-right">Harga Satuan</th>
                            <th class="p-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="modalItems" class="divide-y divide-gray-100 text-gray-700"></tbody>
                </table>
            </div>

            <div class="w-1/2 ml-auto text-xs space-y-2 pt-2 border-t border-dashed border-gray-200">
                <div class="flex justify-between text-gray-500"><span>Subtotal:</span> <span id="modalSubtotal"></span></div>
                <div class="flex justify-between text-red-500"><span>Diskon:</span> <span id="modalDiscount"></span></div>
                <div class="flex justify-between font-bold text-sm text-gray-900 pt-1 border-t"><span>Grand Total:</span> <span id="modalGrandTotal"></span></div>
                <div class="flex justify-between text-gray-600"><span>Dibayar:</span> <span id="modalPaid"></span></div>
                <div class="flex justify-between text-gray-600"><span>Kembalian:</span> <span id="modalChange"></span></div>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 flex justify-end">
            <button type="button" onclick="closeModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-xs font-semibold transition">Tutup</button>
        </div>
    </div>
</div>

<script>
const modal = document.getElementById('detailModal');

async function openDetailModal(transactionId) {
    try {
        const response = await fetch(`{{ url('/dashboard/transactions') }}/${transactionId}/detail`);
        
        if (!response.ok) throw new Error(`Server merespon dengan status ${response.status}`);

        const result = await response.json();
        if (!result.success) {
            alert('Gagal memuat rincian transaksi.');
            return;
        }

        document.getElementById('modalInvoice').innerText = result.invoice;
        document.getElementById('modalDate').innerText = result.date;
        document.getElementById('modalCashier').innerText = result.cashier;
        document.getElementById('modalMethod').innerText = result.payment_method;

        let htmlItems = '';
        result.details.forEach(item => {
            htmlItems += `
                <tr>
                    <td class="p-3 font-medium">${item.product_name}</td>
                    <td class="p-3 text-center">${item.qty}</td>
                    <td class="p-3 text-right">Rp ${Number(item.price).toLocaleString('id-ID')}</td>
                    <td class="p-3 text-right font-semibold">Rp ${Number(item.subtotal).toLocaleString('id-ID')}</td>
                </tr>
            `;
        });
        document.getElementById('modalItems').innerHTML = htmlItems;

        document.getElementById('modalSubtotal').innerText = 'Rp ' + Number(result.subtotal).toLocaleString('id-ID');
        document.getElementById('modalDiscount').innerText = '-Rp ' + Number(result.discount).toLocaleString('id-ID');
        document.getElementById('modalGrandTotal').innerText = 'Rp ' + Number(result.grand_total).toLocaleString('id-ID');
        document.getElementById('modalPaid').innerText = 'Rp ' + Number(result.paid_amount).toLocaleString('id-ID');
        document.getElementById('modalChange').innerText = 'Rp ' + Number(result.change_amount).toLocaleString('id-ID');

        modal.classList.remove('hidden');
    } catch (error) {
        console.error('Detail Error:', error);
        alert('Terjadi kendala saat menghubungi server.');
    }
}

function closeModal() {
    modal.classList.add('hidden');
}

window.onclick = function(event) {
    if (event.target == modal) closeModal();
}
</script>
@endsection