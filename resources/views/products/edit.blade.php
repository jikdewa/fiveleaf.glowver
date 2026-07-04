@extends('layouts.app')

@section('content')

<div class="mt-5 ">

    <form
        action="{{ route('products.update',$product->id) }}"
        method="POST"
        enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-5">

            {{-- LEFT --}}
            <div class="xl:col-span-9 space-y-5">

                {{-- PRODUCT IMAGE --}}
                <div class="bg-white rounded-3xl shadow-sm p-6">

                    <h2 class="font-bold text-2xl mb-5">
                        Product Image
                    </h2>

                    <div class="flex flex-wrap gap-4 items-center">

                        <div id="imagePreview" class="flex flex-wrap gap-4">

                            @foreach($product->photos as $photo)

                                <div
                                    class="existing-photo w-24 h-24 rounded-xl overflow-hidden border relative">

                                    <img
                                        src="{{ asset('storage/products/'.$photo->photo) }}"
                                        onclick="openImageModal(this.src)"
                                        class="w-full h-full object-cover cursor-pointer hover:opacity-80 transition">
                                    <button
                                        type="button"
                                        onclick="deleteExistingPhoto({{ $photo->id }},this)"
                                        class="absolute top-1 right-1 w-5 h-5 rounded-full bg-red-500 text-white text-xs">

                                        ×

                                    </button>

                                </div>

                            @endforeach

                        </div>

                        <label
                            class="w-24 h-24 bg-gray-100 rounded-2xl flex flex-col items-center justify-center cursor-pointer hover:bg-gray-200 transition">

                            <i class="fa-solid fa-plus text-3xl mb-1"></i>

                            <span class="text-sm">
                                Add Photo
                            </span>

                            <input
                                type="file"
                                id="images"
                                name="images[]"
                                multiple
                                class="hidden" accept=".jpg,.jpeg,.png,.webp">
                        </label>

                    </div>

                </div>

                {{-- PRODUCT INFO --}}
                <div class="bg-white rounded-3xl shadow-sm p-6">

                    <h2 class="font-bold text-2xl mb-5">
                        Product Information
                    </h2>

                    <div class="space-y-4">

                        <div>

                            <label class="block mb-2">
                                Product Name
                            </label>

                            <input type="text" name="product_name" value="{{ old('product_name',$product->product_name) }}" class="w-full border rounded-xl px-4 py-3" required>

                        </div>

                        <div>

                            <label class="block mb-2">
                                Product Description
                            </label>

                            <textarea
                                name="description"
                                rows="5"
                                class="w-full border rounded-xl px-4 py-3">{{ old('description', $product->description) }}</textarea>

                        </div>

                    </div>

                </div>

                {{-- PRODUCT PRICE --}}
                <div class="bg-white rounded-3xl shadow-sm p-6">

                    <h2 class="font-bold text-2xl mb-5">
                        Product Price
                    </h2>

                    <div class="grid grid-cols-2 gap-5">

                        <div>

                            <label class="block mb-2">
                                Cost Price
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                name="cost_price"
                                value="{{ old('cost_price', $product->cost_price) }}"
                                class="w-full border rounded-xl px-4 py-3">

                        </div>

                        <div>

                            <label class="block mb-2">
                                Selling Price
                            </label>

                            <input type="number" step="0.01" name="selling_price" value="{{ old('selling_price',$product->selling_price) }}" class="w-full border rounded-xl px-4 py-3">

                        </div>

                    </div>

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="xl:col-span-3 space-y-5">

                {{-- CATEGORY --}}
                <div class="bg-white rounded-3xl shadow-sm p-6">

                    <h2 class="font-bold text-2xl mb-5">
                        Product Category
                    </h2>

                    <div class="relative">

                        <div class="flex gap-2">

                            <input
                                type="text"
                                id="categorySearch"
                                placeholder="Search / New Category"
                                class="flex-1 border rounded-xl px-4 py-2">

                            <button
                                type="button"
                                id="addCategoryBtn"
                                class="bg-gray-200 px-5 rounded-xl">

                                Add

                            </button>

                        </div>

                        {{-- Dropdown Suggestion --}}
                        <div
                            id="categorySuggestions"
                            class="hidden absolute top-full left-0 right-0 mt-2 bg-white border rounded-xl shadow-lg z-50 max-h-48 overflow-y-auto">
                        </div>

                    </div>

                    {{-- Selected Categories --}}
                    <div id="selectedCategories" class="mt-4 flex flex-wrap gap-2 max-h-40 overflow-y-auto pr-2">

                        @foreach($product->categories as $category)

                            <div
                                class="bg-gray-200 rounded-full px-4 py-2 flex items-center gap-2">

                                {{ $category->category_name }}

                                <span
                                    onclick="removeCategory(
                                        '{{ $category->category_name }}',
                                        this
                                    )"
                                    class="cursor-pointer text-red-500">

                                    ×

                                </span>

                                <input
                                    type="hidden"
                                    name="categories[]"
                                    value="{{ $category->category_name }}">

                            </div>

                        @endforeach

                    </div>

                </div>

                {{-- STOCK --}}
                <div class="bg-white rounded-3xl shadow-sm p-6">

                    <h2 class="font-bold text-2xl mb-5">
                        Product Stock
                    </h2>

                    <div class="space-y-4">

                        <div>

                            <label class="block mb-2">
                                Stock
                            </label>

                            <input type="number" name="stock" value="{{ old('stock',$product->stock) }}" class="w-full border rounded-xl px-4 py-3">

                        </div>

                        <div>

                            <label class="block mb-2">
                                Minimum Stock
                            </label>

                            <input
                                type="number"
                                name="minimum_stock"
                                value="{{ old('minimum_stock',$product->minimum_stock) }}"
                                class="w-full border rounded-xl px-4 py-3">

                        </div>

                    </div>

                </div>
                <div id="deletedPhotos"></div>

                {{-- SAVE --}}
                <button
                    type="submit"
                    class="w-full bg-green-300 hover:bg-green-400 transition rounded-2xl py-4 text-3xl font-semibold">

                    Save

                </button>

            </div>

        </div>

    </form>

    <!-- Image Preview Modal -->
    <div
        id="imageModal"
        class="fixed inset-0 bg-black/80 hidden z-[9999] flex items-center justify-center p-5">

        <button
            type="button"
            onclick="closeImageModal()"
            class="absolute top-5 right-8 text-white text-5xl font-bold">

            ×

        </button>

        <img
            id="modalImage"
            src=""
            class="max-w-full max-h-full object-contain rounded-xl">

    </div>

</div>

<script>
/*
|--------------------------------------------------------------------------
| IMAGE MODAL
|--------------------------------------------------------------------------
*/

    function openImageModal(src)
    {
        document
            .getElementById('modalImage')
            .src = src;

        document
            .getElementById('imageModal')
            .classList
            .remove('hidden');

        document.body.style.overflow = 'hidden';
    }

    function closeImageModal()
    {
        document
            .getElementById('imageModal')
            .classList
            .add('hidden');

        document.body.style.overflow = 'auto';
    }

    document
    .getElementById('imageModal')
    .addEventListener(
    'click',
    function(e){

        if(e.target === this)
        {
            closeImageModal();
        }

    });

    document.addEventListener(
    'keydown',
    function(e){

        if(e.key === 'Escape')
        {
            closeImageModal();
        }

    });

/*
|--------------------------------------------------------------------------
| Javascript Hapus Thumbnail Lama
|--------------------------------------------------------------------------
*/
function deleteExistingPhoto(id, button)
{
    document
        .getElementById('deletedPhotos')
        .innerHTML += `
            <input
                type="hidden"
                name="deleted_photos[]"
                value="${id}">
        `;

    button
        .closest('.existing-photo')
        .remove();
}


let selectedCategories = [

@foreach($product->categories as $category)

    @json($category->category_name),

@endforeach

];

/*
|--------------------------------------------------------------------------
| IMAGE PREVIEW
|--------------------------------------------------------------------------
*/

let dataTransfer = new DataTransfer();

const imageInput =
    document.getElementById('images');

const imagePreview =
    document.getElementById('imagePreview');

imageInput.addEventListener(
    'change',
    function (e) {

        [...e.target.files].forEach(file => {

            dataTransfer.items.add(file);

        });

        imageInput.files = dataTransfer.files;

        renderImages();
    }
);

function renderImages()
{
    document
        .querySelectorAll('.new-photo')
        .forEach(el => el.remove());

    [...dataTransfer.files].forEach((file,index)=>{

        const reader = new FileReader();

        reader.onload = function(e){

            const div =
                document.createElement('div');

            div.className =
                'new-photo w-24 h-24 rounded-xl overflow-hidden border relative';

            div.innerHTML = `
                <img
                    src="${e.target.result}"
                    onclick="openImageModal(this.src)"
                    class="w-full h-full object-cover cursor-pointer">

                <span
                    class="absolute bottom-0 left-0 right-0 text-center bg-black/50 text-white text-xs">

                    Foto ${index + 1}

                </span>

                <button
                    type="button"
                    onclick="removeImage(${index})"
                    class="absolute top-1 right-1 w-5 h-5 rounded-full bg-red-500 text-white text-xs">

                    ×

                </button>
            `;

            imagePreview.appendChild(div);

        };

        reader.readAsDataURL(file);

    });
}

function removeImage(index)
{
    const newTransfer = new DataTransfer();

    [...dataTransfer.files].forEach((file, i) => {

        if(i !== index)
        {
            newTransfer.items.add(file);
        }

    });

    dataTransfer = newTransfer;

    imageInput.files = dataTransfer.files;

    renderImages();
}

/*
|--------------------------------------------------------------------------
| CATEGORY SEARCH
|--------------------------------------------------------------------------
*/

const searchInput =
    document.getElementById(
        'categorySearch'
    );

searchInput.addEventListener(
    'keyup',
    async function(){

        let keyword = this.value;

        if(keyword.length < 1)
        {
            document
                .getElementById(
                    'categorySuggestions'
                )
                .innerHTML = '';

            return;
        }

        let response =
            await fetch(
                `/dashboard/categories/search?search=${keyword}`
            );

        let categories =
            await response.json();

        let html = '';

        categories.forEach(category => {

            html += `
                <div
                    onclick="selectCategory(
                        ${category.id},
                        '${category.category_name}'
                    )"
                    class="cursor-pointer p-2 rounded hover:bg-gray-100">

                    ${category.category_name}

                </div>
            `;

        });

        const suggestionBox =
            document.getElementById(
                'categorySuggestions'
            );

        suggestionBox.innerHTML = html;

        if(categories.length > 0)
        {
            suggestionBox.classList.remove('hidden');
        }
        else
        {
            suggestionBox.classList.add('hidden');
        }

    }
);

function selectCategory(id,name)
{
    if(selectedCategories.includes(name))
        return;

    selectedCategories.push(name);

    document
        .getElementById(
            'selectedCategories'
        )
        .innerHTML += `

        <div
            class="bg-gray-200 rounded-full px-4 py-2 flex items-center gap-2">

            ${name}

            <span
                onclick="removeCategory('${name}',this)"
                class="cursor-pointer text-red-500">

                ×

            </span>

            <input
                type="hidden"
                name="categories[]"
                value="${name}">

        </div>

    `;

    document
        .getElementById(
            'categorySearch'
        )
        .value = '';

    document
        .getElementById(
            'categorySuggestions'
        )
        .classList.add('hidden');
}

function removeCategory(id,el)
{
    selectedCategories =
        selectedCategories.filter(
            item => item !== id
        );

    el.parentElement.remove();
}

/*
|--------------------------------------------------------------------------
| ADD CATEGORY
|--------------------------------------------------------------------------
*/

document
.getElementById(
    'addCategoryBtn'
)
.addEventListener(
'click',
function(){

    let name =
        document
        .getElementById(
            'categorySearch'
        )
        .value.trim();

    if(!name)
        return;

    selectCategory(
        null,
        name
    );

});

// dwda
document.addEventListener('click', function(e){

    const searchBox =
        document.getElementById('categorySearch');

    const suggestionBox =
        document.getElementById('categorySuggestions');

    if(
        !searchBox.contains(e.target)
        &&
        !suggestionBox.contains(e.target)
    ){
        suggestionBox.classList.add('hidden');
    }

});

</script>

@endsection