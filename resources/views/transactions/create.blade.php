@extends('layouts.app')

@section('content')

<div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-6">

    <div class="xl:col-span-8">

        <div class="bg-white rounded-3xl p-6 shadow-sm">

            <h2 class="text-2xl font-bold mb-6 flex items-center justify-between">
                <span>New Transaction</span>
                <button type="button" onclick="openScannerModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs px-4 py-2 rounded-xl font-bold flex items-center gap-1.5 shadow-sm transition">
                    <i class="fa-solid fa-barcode"></i> Scan Barcode
                </button>
            </h2>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

                <div class="lg:col-span-8">

                    <div class="mb-4 relative">

                        <label class="block mb-2 text-sm font-medium">
                            Product
                        </label>

                        <div class="flex gap-2">
                            <input
                                type="text"
                                id="product_search"
                                autocomplete="off"
                                placeholder="Search Product by name or code..."
                                class="w-full border border-gray-200 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-violet-500">
                            
                            <button type="button" onclick="openScannerModal()" class="bg-gray-100 hover:bg-gray-200 border border-gray-200 text-gray-700 px-4 rounded-2xl transition flex items-center justify-center" title="Scan Barcode">
                                <i class="fa-solid fa-camera text-lg"></i>
                            </button>
                        </div>
                        
                        <input
                            type="hidden"
                            id="product_id">
                        <div
                            id="search_result"
                            class="hidden absolute left-0 right-14 bg-white border border-gray-200 rounded-2xl mt-2 shadow-lg z-50">
                        </div>

                    </div>

                    <div class="grid grid-cols-2 gap-4">

                        <div>
                            <label class="block mb-2 text-sm font-medium">
                                Qty
                            </label>

                            <input
                                type="number"
                                id="qty"
                                value="1"
                                min="1"
                                class="w-full border border-gray-200 rounded-2xl px-4 py-3">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium">
                                Discount (Rp)
                            </label>

                            <input
                                type="number"
                                id="discount"
                                value="0"
                                class="w-full border border-gray-200 rounded-2xl px-4 py-3">
                        </div>

                    </div>

                    <button
                        id="add_to_cart"
                        type="button"
                        class="mt-5 bg-violet-600 hover:bg-violet-700 text-white px-6 py-3 rounded-2xl font-medium">
                        Add To List
                    </button>

                </div>

                <div class="lg:col-span-4">

                    <div class="bg-gray-100 rounded-3xl h-64 flex items-center justify-center overflow-hidden">

                        <img
                            id="product_preview"
                            src=""
                            class="hidden w-full h-full object-cover">

                        <div id="preview_placeholder">

                            <i class="fa-solid fa-image text-5xl text-gray-400"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm mt-6">

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead>

                        <tr class="border-b text-gray-500 text-sm">

                            <th class="text-left py-3">Product</th>
                            <th class="text-left py-3">Price</th>
                            <th class="text-left py-3">Qty</th>
                            <th class="text-left py-3">Discount</th>
                            <th class="text-left py-3">Subtotal</th>
                            <th class="text-left py-3">Action</th>

                        </tr>

                    </thead>

                    <tbody id="cart_table" class="text-sm divide-y divide-gray-100">

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="xl:col-span-4">

        <div class="bg-white rounded-3xl p-6 shadow-sm sticky top-5">

            <h2 class="text-2xl font-bold mb-6">
                Payment
            </h2>

            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Total Price
                </label>

                <input
                    type="text"
                    id="total_price"
                    readonly
                    value="Rp 0"
                    class="w-full bg-gray-100 rounded-2xl px-4 py-3 font-bold text-lg">

            </div>

            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Payment Method
                </label>

                <select
                    id="payment_method"
                    class="w-full border border-gray-200 rounded-2xl px-4 py-3">

                    <option value="cash">Cash</option>
                    <option value="transfer">Transfer</option>
                    <option value="qris">QRIS</option>
                    <option value="debit">Debit</option>

                </select>

            </div>

            <div id="cash_wrapper" class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Cash
                </label>

                <input
                    type="number"
                    id="cash"
                    min="0"
                    step="1"
                    class="w-full border border-gray-200 rounded-2xl px-4 py-3">

            </div>

            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Change
                </label>

                <input
                    type="text"
                    id="change"
                    readonly
                    value="Rp 0"
                    class="w-full bg-gray-100 rounded-2xl px-4 py-3">

            </div>

            <button
                type="button" id="save_transaction"
                class="w-full bg-green-600 hover:bg-green-700 text-white py-4 rounded-2xl font-bold">
                Save Transaction
            </button>

        </div>

    </div>

</div>

<div id="scannerModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900 bg-opacity-60 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-xl max-w-lg w-full overflow-hidden border border-gray-100">
        
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-md font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-barcode text-indigo-600"></i> Scanner Barcode POS
            </h3>
            <button type="button" onclick="closeScannerModal()" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
        </div>

        <div class="p-6 space-y-4">
            <div id="interactive-reader" class="w-full overflow-hidden rounded-2xl bg-black border border-gray-200" style="min-height: 250px;"></div>
            
            <div id="scanner_result_form" class="hidden bg-gray-50 border border-gray-200 rounded-2xl p-4 space-y-3">
                <div class="text-xs text-gray-700 space-y-1">
                    <p><span class="font-bold text-gray-400 uppercase">ID Produk:</span> <span id="scan_res_id" class="font-mono font-bold text-indigo-600"></span></p>
                    <p><span class="font-bold text-gray-400 uppercase">Nama:</span> <span id="scan_res_name" class="font-semibold text-gray-900 text-sm"></span></p>
                    <p><span class="font-bold text-gray-400 uppercase">Harga Satuan:</span> <span id="scan_res_price" class="font-bold text-gray-800"></span></p>
                    <p><span class="font-bold text-gray-400 uppercase">Sisa Stok:</span> <span id="scan_res_stock" class="font-bold text-green-600"></span></p>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2 border-t border-gray-200">
                    <div>
                        <label class="block mb-1 text-xs font-bold text-gray-500">Jumlah Belanja (Qty)</label>
                        <input type="number" id="scan_qty" value="1" min="1" class="w-full text-xs border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-bold text-gray-500">Potongan Diskon (Rp)</label>
                        <input type="number" id="scan_discount" value="0" min="0" class="w-full text-xs border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                </div>

                <button type="button" id="submit_scan_item" class="w-full mt-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs py-2.5 rounded-xl font-bold shadow-sm transition">
                    Masukkan ke Daftar Belanja
                </button>
            </div>
            
            <div id="scanner_status_msg" class="text-center text-xs text-gray-400 py-2">
                Menginisialisasi modul kamera... silakan tunggu dan arahkan kode batang ke kamera.
            </div>
        </div>

    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    let selectedProduct = null;
    let scannedProductData = null; 
    window.totalPrice = 0;
    let html5QrcodeScanner = null; 
    let isProcessingScan = false; 

    const searchInput = document.getElementById('product_search');
    const resultBox = document.getElementById('search_result');
    const preview = document.getElementById('product_preview');
    const placeholder = document.getElementById('preview_placeholder');
    const paymentMethod = document.getElementById('payment_method');
    const cashInput = document.getElementById('cash');
    const changeInput = document.getElementById('change');
    const saveButton = document.getElementById('save_transaction');
    const addButton = document.getElementById('add_to_cart');

    // Header khusus agar Ngrok melewatkan halaman konfirmasi peringatan browser
    const fetchHeaders = {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'ngrok-skip-browser-warning': 'true',
        'Accept': 'application/json'
    };

    // ==========================================
    // MODULE: BARCODE SCANNER ENGINE INTERACTION
    // ==========================================
    function openScannerModal() {
        document.getElementById('scannerModal').classList.remove('hidden');
        document.getElementById('scanner_result_form').classList.add('hidden');
        document.getElementById('scanner_status_msg').innerText = "Kamera aktif. Silakan arahkan barcode produk tepat di depan lensa kamera.";
        scannedProductData = null;
        isProcessingScan = false;

        if (!html5QrcodeScanner) {
            html5QrcodeScanner = new Html5Qrcode("interactive-reader");
        }

        const config = { fps: 15, qrbox: { width: 280, height: 160 } };
        
        html5QrcodeScanner.start(
            { facingMode: "environment" }, 
            config, 
            onScanSuccess, 
            onScanFailure
        ).catch(err => {
            console.error("Gagal mengakses modul hardware kamera: ", err);
            document.getElementById('scanner_status_msg').innerHTML = `<span class="text-red-500 font-bold">Kamera Gagal Dimuat!</span><br>Pastikan izin kamera diizinkan atau gunakan pencarian manual.`;
        });
    }

    async function onScanSuccess(decodedText, decodedResult) {
        if (isProcessingScan) return; 
        isProcessingScan = true; 
        
        document.getElementById('scanner_status_msg').innerText = "Barcode terdeteksi! Mencari data komoditas produk...";
        
        try {
            const response = await fetch(`{{ route('transactions.search-product') }}?keyword=${encodeURIComponent(decodedText)}`, {
                headers: fetchHeaders
            });
            const products = await response.json();

            if (products && products.length > 0) {
                const matchProduct = products[0];
                
                const detailRes = await fetch(`{{ url('/dashboard/transactions/product') }}/${matchProduct.id}`, {
                    headers: fetchHeaders
                });
                const fullProduct = await detailRes.json();

                scannedProductData = fullProduct;

                document.getElementById('scan_res_id').innerText = fullProduct.id;
                document.getElementById('scan_res_name').innerText = fullProduct.product_name;
                document.getElementById('scan_res_price').innerText = 'Rp ' + Number(fullProduct.price).toLocaleString('id-ID');
                document.getElementById('scan_res_stock').innerText = fullProduct.stock + ' Unit';
                
                document.getElementById('scan_qty').value = 1;
                document.getElementById('scan_discount').value = 0;

                document.getElementById('scanner_result_form').classList.remove('hidden');
                document.getElementById('scanner_status_msg').innerText = "Produk ditemukan! Masukkan jumlah kuantitas dan klik tambah.";
            } else {
                document.getElementById('scanner_status_msg').innerHTML = `<span class="text-amber-600 font-bold">Barcode Tidak Dikenal: "${decodedText}"</span><br>Sistem tidak mendeteksi produk tersebut.`;
                document.getElementById('scanner_result_form').classList.add('hidden');
                setTimeout(() => { isProcessingScan = false; }, 2000);
            }
        } catch (error) {
            console.error("Scanner API Route Error:", error);
            document.getElementById('scanner_status_msg').innerText = "Gagal memproses pencarian API internal toko.";
            setTimeout(() => { isProcessingScan = false; }, 2000);
        }
    }

    function onScanFailure(error) {}

    function closeScannerModal() {
        document.getElementById('scannerModal').classList.add('hidden');
        if (html5QrcodeScanner && html5QrcodeScanner.isScanning) {
            html5QrcodeScanner.stop().catch(err => console.error("Gagal mematikan kamera:", err));
        }
    }

    document.getElementById('submit_scan_item').addEventListener('click', async function () {

        if (!scannedProductData) return;

        const button = this;

        button.disabled = true;
        button.innerHTML = `
            <i class="fa-solid fa-spinner fa-spin"></i>
            Menambahkan...
        `;

        try {

            let qty = document.getElementById('scan_qty').value;
            let discount = document.getElementById('scan_discount').value;

            if (parseInt(qty) > scannedProductData.stock) {

                alert(`Stok tidak mencukupi! Sisa stok saat ini hanya ${scannedProductData.stock} unit.`);

                return;
            }

            const success = await sendProductToCartSession(
                scannedProductData.id,
                qty,
                discount
            );

            if (success) {

                await loadTransactionItems();

                document.getElementById('scanner_status_msg').innerText =
                    "Produk berhasil ditambahkan! Silakan scan produk berikutnya.";

                document.getElementById('scanner_result_form').classList.add('hidden');

                scannedProductData = null;

                isProcessingScan = false;
            }

        } finally {

            button.disabled = false;

            button.innerHTML = 'Masukkan ke Daftar Belanja';
        }
    });

    // ==========================================
    // 1. SEARCH PRODUCT (MANUAL INPUT AUTOCOMPLETE)
    // ==========================================
    searchInput.addEventListener('keyup', async function() {
        let keyword = this.value;

        if (keyword.length < 2) {
            resultBox.classList.add('hidden');
            return;
        }

        const response = await fetch(`{{ route('transactions.search-product') }}?keyword=${encodeURIComponent(keyword)}`, {
            headers: fetchHeaders
        });
        const products = await response.json();

        let html = '';
        products.forEach(product => {
            html += `
                <div class="p-3 hover:bg-gray-100 cursor-pointer product-item text-xs font-medium text-gray-700" data-id="${product.id}">
                    ${product.product_name} <span class="text-gray-400 font-mono">(${product.product_code})</span>
                </div>
            `;
        });

        resultBox.innerHTML = html;
        resultBox.classList.remove('hidden');

        document.querySelectorAll('.product-item').forEach(item => {
            item.addEventListener('click', async function() {
                const id = this.dataset.id;
                const response = await fetch(`{{ url('/dashboard/transactions/product') }}/${id}`, {
                    headers: fetchHeaders
                });
                const product = await response.json();

                selectedProduct = product;
                document.getElementById('product_id').value = product.id;
                searchInput.value = product.product_name;
                resultBox.classList.add('hidden');

                if (product.photo) {
                    preview.src = product.photo;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                } else {
                    preview.classList.add('hidden');
                    placeholder.classList.remove('hidden');
                }
            });
        });
    });

    document.addEventListener('click', function(e) {
        if (e.target !== searchInput) resultBox.classList.add('hidden');
    });

    // ==========================================
    // 2. ADD ITEM TO CART (MANUAL BUTTON)
    // ==========================================
    addButton.addEventListener('click', async () => {
        if (!selectedProduct) {
            alert('Pilih produk terlebih dahulu');
            return;
        }

        let qty = document.getElementById('qty').value;
        let discount = document.getElementById('discount').value;

        await sendProductToCartSession(selectedProduct.id, qty, discount);
        
        document.getElementById('qty').value = 1;
        document.getElementById('discount').value = 0;
        searchInput.value = '';
        selectedProduct = null;
        preview.classList.add('hidden');
        placeholder.classList.remove('hidden');
    });

    async function sendProductToCartSession(productId, qty, discount) {
        try {
            const response = await fetch("{{ route('transactions.items.add') }}", {
                method: 'POST',
                headers: {
                    ...fetchHeaders,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    qty: qty,
                    discount: discount
                })
            });

            const result = await response.json();

            if (result.success) {
                loadTransactionItems();
                return true;
            } else {
                alert(result.message || 'Gagal menambahkan produk');
                return false;
            }
        } catch (err) {
            alert('Gagal menyambungkan transaksi ke session server.');
            return false;
        }
    }

    // ==========================================
    // 3. LOAD ITEMS FROM SESSION & RENDER
    // ==========================================
    async function loadTransactionItems() {

        document.getElementById('cart_table').innerHTML = '<tr><td colspan="6" class="text-center py-4">Memperbarui tabel...</td></tr>';

        const response = await fetch("{{ route('transactions.items') }}", {
            headers: fetchHeaders
        });
        const items = await response.json();

        let html = '';
        let total = 0;

        items.forEach((item, index) => {
            total += Number(item.subtotal);

            html += `
            <tr class="border-b hover:bg-gray-50/50 transition">
                <td class="py-4 font-medium text-gray-800">${item.product_name}</td>
                <td class="text-gray-600">Rp ${Number(item.price).toLocaleString('id-ID')}</td>
                <td class="font-bold text-gray-700">${item.qty}</td>
                <td class="text-red-500"><span class="text-xs font-mono text-gray-400">Rp ${Number(item.discount).toLocaleString('id-ID')}</span></td>
                <td class="font-bold text-gray-900">Rp ${Number(item.subtotal).toLocaleString('id-ID')}</td>
                <td>
                    <button type="button" onclick="removeItem(${index})" class="w-8 h-8 rounded-full bg-red-50 hover:bg-red-100 text-red-500 transition flex items-center justify-center">
                        <i class="fa fa-trash text-xs"></i>
                    </button>
                </td>
            </tr>
            `;
        });

        if(items.length === 0) {
            html = `<tr><td colspan="6" class="py-8 text-center text-gray-400 text-xs">Belum ada item di dalam keranjang belanja. Gunakan scanner atau ketik nama barang.</td></tr>`;
        }

        document.getElementById('cart_table').innerHTML = html;
        document.getElementById('total_price').value = 'Rp ' + total.toLocaleString('id-ID');
        window.totalPrice = total;

        handlePaymentMethod();
    }

    // ==========================================
    // 4. REMOVE ITEM FROM CART
    // ==========================================
    async function removeItem(index) {
        if (!confirm('Hapus item ini dari daftar?')) return;

        await fetch(`{{ url('/dashboard/transactions/items') }}/${index}`, {
            method: 'DELETE',
            headers: fetchHeaders
        });

        loadTransactionItems();
    }

    // ==========================================
    // 5. PAYMENT METHOD & CHANGE CALCULATION
    // ==========================================
    paymentMethod.addEventListener('change', handlePaymentMethod);
    cashInput.addEventListener('input', calculateChange);

    function handlePaymentMethod() {
        const total = Number(window.totalPrice || 0);

        if (paymentMethod.value === 'cash') {
            cashInput.disabled = false;
            cashInput.required = true;
            if (Number(cashInput.value) === total || cashInput.value == total) {
                cashInput.value = '';
            }
        } else {
            cashInput.disabled = true;
            cashInput.required = false;
            cashInput.value = total;
        }

        calculateChange();
    }

    function calculateChange() {
        const total = Number(window.totalPrice || 0);

        if (paymentMethod.value !== 'cash') {
            changeInput.value = 'Rp 0';
            return;
        }

        const cash = Number(cashInput.value || 0);
        const change = cash - total;

        changeInput.value = 'Rp ' + Math.max(change, 0).toLocaleString('id-ID');
    }

    // ==========================================
    // 6. SAVE TRANSACTION TO DATABASE
    // ==========================================
    saveButton.addEventListener('click', saveTransaction);

    async function saveTransaction() {
        const total = Number(window.totalPrice || 0);

        if (total <= 0) {
            alert('Belum ada produk di dalam daftar belanja.');
            return;
        }

        const payment_method = paymentMethod.value;
        const paid_amount = Number(cashInput.value || 0);
        const change_amount = Math.max(paid_amount - total, 0);

        if (payment_method === 'cash' && paid_amount < total) {
            alert('Uang pembayaran tunai masih kurang!');
            return;
        }

        try {
            saveButton.disabled = true;
            saveButton.innerText = 'Saving...';

            const response = await fetch("{{ route('transactions.store') }}", {
                method: 'POST',
                headers: {
                    ...fetchHeaders,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    payment_method,
                    paid_amount,
                    change_amount
                })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                alert('Transaksi Berhasil!\nNomor Invoice: ' + result.invoice);
                location.reload(); 
            } else {
                alert('Gagal menyimpan: ' + (result.message || 'Terjadi kesalahan internal.'));
            }
        } catch (error) {
            console.error(error);
            alert('Server Error atau kendala koneksi internet.');
        } finally {
            saveButton.disabled = false;
            saveButton.innerText = 'Save Transaction';
        }
    }

    loadTransactionItems();
</script>
@endsection