@extends('layouts.app')

@section('content')

<div class="p-6 bg-[#F4F4F4] min-h-screen">

    <div class="grid grid-cols-12 gap-6">

        {{-- LEFT --}}
        <div class="col-span-5">

            <div class="bg-white rounded-3xl p-6">

                {{-- MAIN PHOTO --}}
                <div class="relative">

                    <button
                        id="prevBtn"
                        class="absolute left-2 top-1/2 -translate-y-1/2 z-10
                        w-10 h-10 rounded-full bg-gray-300">

                        ❮

                    </button>

                    <img
                        id="mainImage"
                        src="{{ asset('storage/products/'.$product->photos->first()?->photo) }}"
                        class="w-full h-[500px] object-contain rounded-2xl">

                    <button
                        id="nextBtn"
                        class="absolute right-2 top-1/2 -translate-y-1/2 z-10
                        w-10 h-10 rounded-full bg-gray-300">

                        ❯

                    </button>

                </div>

                {{-- THUMBNAILS --}}
                <div class="relative mt-6">

                    <div
                        id="thumbnailWrapper"
                        class="flex gap-3 overflow-x-auto scrollbar-hide">

                        @foreach($product->photos as $index => $photo)

                            <img
                                src="{{ asset('storage/products/'.$photo->photo) }}"
                                data-index="{{ $index }}"
                                class="thumbnail
                                w-20 h-20
                                rounded-xl
                                object-cover
                                cursor-pointer
                                flex-shrink-0
                                {{ $loop->first ? 'border-2 border-black' : '' }}">

                        @endforeach

                    </div>

                </div>

            </div>

            {{-- BUTTONS --}}
            <div class="mt-5 space-y-4">

                <a
                    href="{{ route('products.edit',$product->id) }}"
                    class="block text-center py-5 rounded-full
                    bg-yellow-300 text-4xl font-light">

                    Edit

                </a>

                <form
                    action="{{ route('products.destroy',$product->id) }}"
                    method="POST">

                    @csrf
                    @method('DELETE')

                    <button
                        class="w-full py-5 rounded-full
                        bg-red-400 text-white text-4xl font-light">

                        Delete

                    </button>

                </form>

            </div>

        </div>

        {{-- RIGHT --}}
        <div class="col-span-7 space-y-6">

            {{-- PRODUCT INFO --}}
            <div class="bg-white rounded-3xl p-6">

                <h1
                    class="text-4xl font-bold truncate">

                    {{ $product->product_name }}

                </h1>

                <div class="mt-4 text-xl">

                    <div>
                        Cost Price :
                        Rp.
                        {{ number_format($product->cost_price,0,',','.') }}
                    </div>

                    <div>
                        Selling Price :
                        Rp.
                        {{ number_format($product->selling_price,0,',','.') }}
                    </div>

                </div>

            </div>

            {{-- DESCRIPTION --}}
            <div class="bg-white rounded-3xl p-6">

                <h2 class="font-bold text-3xl mb-4">
                    Product Description
                </h2>

                <p
                    id="descriptionText"
                    class="text-gray-700 leading-8 overflow-hidden">

                    {{ $product->description }}

                </p>

                <button
                    id="toggleDescription"
                    class="text-gray-500 mt-3 float-right">

                    Show More

                </button>

            </div>

            {{-- BARCODE --}}
            <div class="bg-white rounded-3xl p-6">

                <h2 class="font-bold text-3xl mb-4">
                    Barcode
                </h2>

                <div
                    class="bg-gray-100 rounded-xl p-6 flex justify-center">

                    <img
                        src="{{ asset('storage/barcodes/'.$product->barcode_photo) }}"
                        class="max-h-32">

                </div>

            </div>

            <div class="grid grid-cols-2 gap-6">

                {{-- CATEGORY --}}
                <div class="bg-white rounded-3xl p-6">

                    <h2 class="font-bold text-3xl mb-5">

                        Product Category

                    </h2>

                    <div class="flex flex-wrap gap-3">

                        @foreach($product->categories as $category)

                            <div
                                class="px-5 py-2 rounded-full bg-gray-200">

                                {{ $category->category_name }}

                            </div>

                        @endforeach

                    </div>

                </div>

                {{-- STOCK --}}
                <div class="bg-white rounded-3xl p-6">

                    <h2 class="font-bold text-3xl mb-5">

                        Product Stock

                    </h2>

                    <div class="space-y-5">

                        <div>

                            <label class="block mb-2">
                                Stock
                            </label>

                            <input
                                value="{{ $product->stock }} pcs"
                                readonly
                                class="w-full border rounded-xl px-4 py-3">

                        </div>

                        <div>

                            <label class="block mb-2">
                                Minimum Stock
                            </label>

                            <input
                                value="{{ $product->minimum_stock }} pcs"
                                readonly
                                class="w-full border rounded-xl px-4 py-3">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

const photos = @json(
    $product->photos->map(
        fn($photo) =>
            asset('storage/products/'.$photo->photo)
    )
);

let currentIndex = 0;

const mainImage =
    document.getElementById('mainImage');

const thumbnails =
    document.querySelectorAll('.thumbnail');

function showImage(index)
{
    currentIndex = index;

    mainImage.src = photos[index];

    thumbnails.forEach(
        thumb =>
        thumb.classList.remove(
            'border-2',
            'border-black'
        )
    );

    thumbnails[index]
        .classList.add(
            'border-2',
            'border-black'
        );

    thumbnails[index]
        .scrollIntoView({
            behavior:'smooth',
            inline:'center'
        });
}

thumbnails.forEach((thumb,index)=>{

    thumb.addEventListener(
        'click',
        ()=>showImage(index)
    );

});

document
.getElementById('nextBtn')
.addEventListener('click',()=>{

    currentIndex++;

    if(currentIndex >= photos.length)
        currentIndex = 0;

    showImage(currentIndex);

});

document
.getElementById('prevBtn')
.addEventListener('click',()=>{

    currentIndex--;

    if(currentIndex < 0)
        currentIndex = photos.length - 1;

    showImage(currentIndex);

});


// DESCRIPTION

const desc =
    document.getElementById(
        'descriptionText'
    );

const toggle =
    document.getElementById(
        'toggleDescription'
    );

if(desc.scrollHeight > 120)
{
    desc.style.maxHeight = '120px';
}
else
{
    toggle.style.display = 'none';
}

toggle.addEventListener(
'click',
function(){

    if(desc.style.maxHeight === '120px')
    {
        desc.style.maxHeight = 'none';
        toggle.innerText = 'Show Less';
    }
    else
    {
        desc.style.maxHeight = '120px';
        toggle.innerText = 'Show More';
    }

});

</script>

@endsection