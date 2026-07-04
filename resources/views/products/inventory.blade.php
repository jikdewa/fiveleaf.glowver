@extends('layouts.app')

@section('content')
    <h1 class="text-xl md:text-5xl font-bold mt-5">
        Dashboard
    </h1>

    <!-- ALERT -->
    <div class="mt-5 bg-red-100 text-red-600 rounded-xl p-4">

        <i class="fa-solid fa-circle-exclamation"></i>

        Ada produk yang hampir habis, segera restok.

        <a href="#" class="underline">
            View More...
        </a>

    </div>

    <div class="flex gap-4 mt-6 ">

        {{-- MAIN CONTENT --}}
        <div class="flex-1 bg-white rounded-[24px] p-6 shadow-lg">

            {{-- TOP BAR --}}
            <div class="flex justify-between items-center mb-5">

                {{-- FILTER CHIPS --}}
                <div class="flex gap-3 flex-wrap">

                    <button class="bg-[#E6E6E6] px-4 py-2 rounded-full text-sm flex items-center gap-2">
                        Price
                        <span>✕</span>
                    </button>

                    <button class="bg-[#E6E6E6] px-4 py-2 rounded-full text-sm flex items-center gap-2">
                        Category
                        <span>✕</span>
                    </button>

                    <button class="bg-[#E6E6E6] px-4 py-2 rounded-full text-sm flex items-center gap-2">
                        Stock < 10
                        <span>✕</span>
                    </button>

                </div>

                {{-- SEARCH + ADD --}}
                <div class="flex gap-4">

                    <form
                        action="{{ route('inventory') }}"
                        method="GET">

                        <div class="relative">

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search"
                                class="w-[300px] h-[50px] border border-gray-300 rounded-xl pl-4 pr-12 bg-white">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5 absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/>

                            </svg>

                        </div>

                    </form>

                    <a
                        href="{{ route('products.create') }}"
                        class="bg-[#6E6B60] text-white px-8 rounded-xl h-[50px] inline-flex items-center justify-center">

                        Add +

                    </a>

                </div>

            </div>

            {{-- TABLE --}}
            <div class="">

                <table class="w-full border-collapse">

                    <!-- <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Product Code</th>
                        <th>Barcode</th>
                        <th>Barcode Photo</th>
                        <th>Product Name</th>
                        <th>Description</th>
                        <th>Categories</th>
                        <th>Cost Price</th>
                        <th>Selling Price</th>
                        <th>Stock</th>
                        <th>Minimum Stock</th>
                        <th>Photos</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                    </thead>
                    <tbody>


                        @foreach($products as $product)

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $product->id }}</td>

                            <td>{{ $product->product_code }}</td>

                            <td>{{ $product->barcode }}</td>

                            <td>
                                <img
                                    src="{{ asset('storage/products/' . $product->barcode_photo) }}"
                                    class="w-20 h-20 object-cover rounded mb-2">
                            </td>

                            <td>{{ $product->product_name }}</td>

                            <td>{{ $product->description }}</td>

                            <td>
                                {{ $product->categories->pluck('category_name')->join(', ') }}
                            </td>

                            <td>{{ $product->cost_price }}</td>

                            <td>{{ $product->selling_price }}</td>

                            <td>{{ $product->stock }}</td>

                            <td>{{ $product->minimum_stock }}</td>

                            <td>

                                @foreach($product->photos as $photo)

                                    <img
                                        src="{{ asset('storage/products/' . $photo->photo) }}"
                                        class="w-20 h-20 object-cover rounded mb-2">

                                @endforeach

                            </td>

                            <td>

                                {{ $product->is_active ? 'Active' : 'Inactive' }}

                            </td>

                            <td>

                                {{ $product->created_at }}

                            </td>

                        </tr>

                        @endforeach

                    </tbody> -->

                    <thead>

                        <tr class="bg-[#E9E9E9] text-sm">

                            <th class="border border-gray-300 p-3 text-left">No</th>
                            <th class="border border-gray-300 p-3 text-left">Product Code</th>
                            <th class="border border-gray-300 p-3 text-left">Product Name</th>
                            <th class="border border-gray-300 p-3 text-left">Description</th>
                            <th class="border border-gray-300 p-3 text-left">Category</th>
                            <th class="border border-gray-300 p-3 text-left">Price</th>
                            <th class="border border-gray-300 p-3 text-left">Stock</th>
                            <th class="border border-gray-300 p-3 text-left">Created Time</th>
                            <th class="border border-gray-300 p-3 text-center">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($products as $product)

                        <tr x-data="{ open:false }" class="hover:bg-gray-50 cursor-pointer relative" @click="open = !open">

                            <td class="border border-gray-300 p-3">
                                {{ $loop->iteration + ($products->currentPage()-1) * $products->perPage() }}
                            </td>

                            <td class="border border-gray-300 p-3">
                                {{ $product->product_code }}
                            </td>

                            <td class="border border-gray-300 p-3">
                                {{ $product->product_name }}
                            </td>

                            <td class="border border-gray-300 p-3">
                                {{ $product->description }}
                            </td>

                            <td class="border border-gray-300 p-3">
                                {{ $product->categories->pluck('category_name')->join(', ') }}
                            </td>

                            <td class="border border-gray-300 p-3">
                                Rp {{ number_format($product->selling_price,0,',','.') }}
                            </td>

                            <td class="border border-gray-300 p-3">

                                @if($product->stock <= $product->minimum_stock)

                                    <span class="bg-red-100 text-red-800 text-xs px-3 py-1 rounded-lg">
                                        {{ $product->stock }}
                                    </span>

                                @else

                                    <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-lg">
                                        {{ $product->stock }}
                                    </span>

                                @endif

                            </td>

                            <td class="border border-gray-300 p-3">
                                {{ $product->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="border border-gray-300 p-3 text-center relative">

                                <button
                                    @click.stop="open = !open"
                                    class="px-3 py-1 rounded-lg bg-gray-200">

                                    ⋮

                                </button>

                                <div
                                    x-show="open"
                                    @click.outside="open = false"
                                    x-transition
                                    class="absolute right-4 top-12 w-40 bg-white shadow-lg rounded-xl border z-50">

                                    <a
                                        href="{{ route('products.show', $product->id) }}"
                                        class="block px-4 py-3 hover:bg-gray-100 text-center">

                                        👁 View More

                                    </a>

                                    <a
                                        href="{{ route('products.edit', $product->id) }}"
                                        class="block px-4 py-3 hover:bg-gray-100 text-center">

                                        ✏ Edit

                                    </a>

                                    <form
                                        action="{{ route('products.destroy', $product->id) }}"
                                        method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            onclick="return confirm('Delete this product?')"
                                            class="w-full text-center px-4 py-3 hover:bg-red-100 text-red-600 ">

                                            🗑 Delete

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="8" class="text-center p-10">
                                No Product Found
                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- CUSTOM PAGINATION --}}
            <div class="flex justify-center items-center gap-5 mt-10">

                {{-- PREVIOUS --}}
                @if($products->onFirstPage())

                    <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-white">
                        &lt;
                    </div>

                @else

                    <a
                        href="{{ $products->previousPageUrl() }}"
                        class="w-10 h-10 rounded-full bg-[#6E6B60] flex items-center justify-center text-white">
                        &lt;
                    </a>

                @endif

                {{-- PAGE NUMBERS --}}
                <div class="bg-[#E3E3E3] rounded-full h-[42px] px-8 flex items-center gap-6">

                    @php
                        $current = $products->currentPage();
                        $last = $products->lastPage();
                    @endphp

                    @for($i = 1; $i <= $last; $i++)

                        @if(
                            $i == 1 ||
                            $i == $last ||
                            abs($i - $current) <= 2
                        )

                            <a
                                href="{{ $products->url($i) }}"
                                class="
                                    text-lg
                                    {{ $current == $i
                                        ? 'font-bold text-black'
                                        : 'text-gray-600'
                                    }}
                                ">
                                {{ $i }}
                            </a>

                        @elseif(
                            $i == 2 && $current > 4 ||
                            $i == $last - 1 && $current < $last - 3
                        )

                            <span>...</span>

                        @endif

                    @endfor

                </div>

                {{-- NEXT --}}
                @if($products->hasMorePages())

                    <a
                        href="{{ $products->nextPageUrl() }}"
                        class="w-10 h-10 rounded-full bg-[#6E6B60] flex items-center justify-center text-white">
                        &gt;
                    </a>

                @else

                    <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-white">
                        &gt;
                    </div>

                @endif

                {{-- GO TO PAGE --}}
                <form
                    action="{{ route('inventory') }}"
                    method="GET"
                    class="flex items-center gap-2 ml-4">

                    <span class="text-gray-600">
                        Go to :
                    </span>

                    <input
                        type="number"
                        name="page"
                        min="1"
                        max="{{ $products->lastPage() }}"
                        class="w-[50px] h-[40px] rounded-full border border-gray-400 text-center bg-white">

                    <button
                        class="bg-[#6E6B60] text-white px-5 h-[40px] rounded-xl">

                        Go

                    </button>

                </form>

            </div>

        </div>

        {{-- RIGHT SIDEBAR --}}
        <div class="w-[260px] bg-white rounded-[24px] p-6 shadow-lg">

            <h2 class="text-[38px] font-bold mb-3">
                Filters
            </h2>

            <hr>

            <div class="mt-5 space-y-5">

                <div>

                    <label class="block font-semibold mb-2">
                        Category
                    </label>

                    <select class="w-full border rounded-xl p-3">

                        <option>All Categories</option>

                    </select>

                </div>

                <div>

                    <label class="block font-semibold mb-2">
                        Stock
                    </label>

                    <select class="w-full border rounded-xl p-3">

                        <option>All Stock</option>
                        <option>Stock < 10</option>

                    </select>

                </div>

                <button
                    class="w-full bg-[#6E6B60] text-white py-3 rounded-xl">

                    Apply Filters

                </button>

            </div>

        </div>

    </div>
@endsection