@include('template.head')

<div class="flex flex-col h-screen bg-gradient-to-b from-blue-100 to-white md:flex-row">
    <!-- Sidebar -->
    <div class="w-full md:w-auto relative">
        @include('template.sidebar')
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto px-4 md:px-6">
        {{-- navbar --}}
        <div>
            @include('template.navbar')
        </div>

        <div class="navbar my-6">
            <div class="flex-1">
                <h1 class="text-2xl font-semibold py-4">Products</h1>
            </div>
            <div class="flex-none">
                @can('adminFunction')
                <button class="bg-[#2563EB] text-white rounded-lg py-2 px-4 mx-5 hover:bg-blue-400 cursor-pointer flex justify-center items-center" onclick="newProduct.showModal()">
                    <div class="gap-2 flex">
                        <i class="fa fa-plus" style="display: flex; justify-content: center; align-items: center;"></i>
                        <span>New Product</span>
                    </div>
                </button>
                @endcan

                {{-- modal new product --}}
                <dialog id="newProduct" class="modal">
                    <div class="modal-box">
                        <form method="POST" id="itemForm">
                            <button id="cancel" type="button" onclick="closeModal()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                            <h1 class="font-semibold text-2xl mb-4">New Product</h1>
                            
                            {{-- image upload --}}
                            <div class="mb-4 text-gray-600">
                                <h1 class="font-medium">IMAGE</h1>
                                <div class="flex items-center gap-4">
                                    <div class="avatar">
                                        <div class="w-24 rounded-lg bg-gray-200">
                                            <img id="imagePreview" src="{{ asset('image/default.png') }}" alt="Preview" class="w-full h-full object-cover" />
                                        </div>
                                    </div>
                                    <input type="file" id="imageUpload" name="image" class="file-input file-input-bordered w-full max-w-xs" accept="image/*" />
                                </div>
                            </div>

                            <div class="flex gap-5 justify-between text-gray-600">
                                <!-- Product -->
                                <div class="w-[50%]">
                                    <h1 class="font-medium">PRODUCT</h1>
                                    <div class="mb-2">
                                        <label class="input flex text-gray-600" style="width: 100%;">
                                            <input class="w-full" type="text" id="product" placeholder="product" />
                                        </label>
                                    </div>
                                </div>
                                <!-- rack -->
                                <div class="w-[50%]">
                                    <h1 class="font-medium">RACK</h1>
                                    <label class="select">
                                        <select id="rack">
                                            <option>Insert Rack</option>
                                            @foreach ($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->name . ' | '. $location->description }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </div>
                            </div>

                            <div class="flex gap-5 justify-between text-gray-600">
                                <!-- Brand -->
                                <div class="w-[50%]">
                                    <h1 class="font-medium">BRAND</h1>
                                    <div class="mb-2">
                                        <label class="input flex text-gray-600" style="width: 100%;">
                                            <input class="w-full" type="text" id="brand" placeholder="brand" />
                                        </label>
                                    </div>
                                </div>
                                <!-- condition -->
                                <div class="w-[50%]">
                                    <h1 class="font-medium">CONDITION</h1>
                                    <div>
                                        <label class="select">
                                            <select id="condition">
                                                <option>Insert Condition</option>
                                                <option value="GOOD">Good</option>
                                                <option value="NOT GOOD">Not Good</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-5 justify-between text-gray-600">
                                <!-- Type -->
                                <div class="w-[50%]">
                                    <h1 class="font-medium">TYPE</h1>
                                    <div class="mb-2">
                                        <label class="input flex text-gray-600" style="width: 100%;">
                                            <input class="w-full" type="text" id="type" placeholder="type" />
                                        </label>
                                    </div>
                                </div>
                                <!-- category -->
                                <div class="w-[50%]">
                                    <h1 class="font-medium">CATEGORY</h1>
                                    <div>
                                        <label class="select">
                                            <select id="category_select" name="category_id" class="input w-full">
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category) 
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- SN -->
                            <div class="flex w-full mb-2">
                                <div class="w-full">
                                    <h1 class="font-medium text-gray-600">SERIAL NUMBER</h1>
                                    <label class="input flex text-gray-600" style="width: 100%;">
                                        <input class="w-full" type="text" id="serialNumber" placeholder="Serial Number" />
                                    </label>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <h1 class="font-medium text-gray-600">DESCRIPTION</h1>
                                <textarea id="description" class="textarea text-gray-600" placeholder="Description" style="width: 100%;"></textarea>
                            </div>

                            <!-- buttons -->
                            <div class="w-full flex justify-end items-end gap-4">
                                <button id="cancelButton" type="button" onclick="closeModal()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Cancel</button>
                                <button class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Submit</button>
                            </div>
                        </form>
                    </div>
                </dialog>
            </div>
        </div>

        <div class="list bg-base-100 rounded-box shadow-md">
            <div class="p-4 pb-2 flex">
                <!-- search -->
                <div class="relative w-full mr-4">
                    <form method="GET" action="{{ route('products') }}" class="relative w-full md:block">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input type="text" name="search-navbar" value="{{ request('search-navbar') }}" class="block w-full p-2 ps-10 text-sm border border-gray-400 rounded-lg" placeholder="Search...">
                    </form>
                </div>

                <!-- filter -->
                <button class="btn flex justify-center items-center bg-transparent" onclick="filterProduct.showModal()">All Categories <i class="fa fa-filter" style="display: flex; justify-content: center; align-items: center;"></i></button>
                <dialog id="filterProduct" class="modal">
    <div class="modal-box">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>

        <form id="filterForm">
            <div class="mb-4">
                <h1 class="text-lg font-semibold mb-2">Brand</h1>
                <select name="brand" class="select select-bordered w-full max-w-xs">
                    <option value="" selected>All Brands</option> {{-- Opsi default untuk reset/tidak memilih filter --}}
                    @foreach($items->pluck('brand')->unique() as $brand)
                        <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <h1 class="text-lg font-semibold mb-2">Category</h1>
                <select name="category" class="select select-bordered w-full max-w-xs">
                    <option value="" selected>All Categories</option> {{-- Opsi default untuk reset/tidak memilih filter --}}
                    @foreach($items->pluck('category.name')->unique() as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <h1 class="text-lg font-semibold mb-2">Type</h1>
                <select name="type" class="select select-bordered w-full max-w-xs">
                    <option value="" selected>All Types</option> {{-- Opsi default untuk reset/tidak memilih filter --}}
                    @foreach($items->pluck('type')->unique() as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <h1 class="text-lg font-semibold mb-2">Location</h1>
                <select name="location" class="select select-bordered w-full max-w-xs">
                    <option value="" selected>All Locations</option> {{-- Opsi default untuk reset/tidak memilih filter --}}
                    @foreach($locations->pluck('description')->unique() as $location)
                        <option value="{{ $location }}" {{ request('location') == $location ? 'selected' : '' }}>{{ $location }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-4">
                <h1 class="text-lg font-semibold mb-2">Condition</h1>
                <div class="flex flex-wrap gap-1">
                    <input class="btn btn-square" type="reset" value="×" onclick="resetFilter('condition')" />
                    <input class="btn" type="radio" name="condition" value="GOOD" aria-label="GOOD" {{ request('condition') == 'GOOD' ? 'checked' : '' }} />
                    <input class="btn" type="radio" name="condition" value="NOT GOOD" aria-label="NOT GOOD" {{ request('condition') == 'NOT GOOD' ? 'checked' : '' }} />
                </div>
            </div>

            <div class="mb-4">
                <h1 class="text-lg font-semibold mb-2">Status</h1>
                <div class="flex flex-wrap gap-1">
                    <input class="btn btn-square" type="reset" value="×" onclick="resetFilter('status')" />
                    <input class="btn" type="radio" name="status" value="READY" aria-label="READY" {{ request('status') == 'READY' ? 'checked' : '' }} />
                    <input class="btn" type="radio" name="status" value="NOT READY" aria-label="NOT READY" {{ request('status') == 'NOT READY' ? 'checked' : '' }} />
                </div>
            </div>

            <button type="button" class="btn btn-primary mt-4" onclick="applyFilter()">Apply</button>
        </form>
    </div>
</dialog>
            </div>
            
            <!-- table -->
            <div id="itemTableContainer" class="overflow-x-auto px-2">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="text-center font-semibold">PHOTO</th>
                            <th class="text-center font-semibold">PRODUCT</th>
                            <th class="text-center font-semibold">RACK</th>
                            <th class="text-center font-semibold">SERIAL NUMBER</th>
                            <th class="text-center font-semibold">TYPE</th>
                            <th class="text-center font-semibold">CONDITIONAL</th>
                            <th class="text-center font-semibold">STATUS</th>
                            <th class="text-center font-semibold">ACTION</th>
                        </tr>
                    </thead>
                    <tbody id="itemTableBody">
                        @forelse ($items as $item)
                        <tr>
                            <td class="flex justify-center">
                                <img src="{{ $item->image === 'items/default.png' || $item->image === 'default.png' ? asset('image/default.png') : asset('storage/' . $item->image) }}" 
                                    alt="Product Image" class="w-12 h-12 object-cover" />
                            </td>
                            <td class="text-center">{{ $item->name }}</td>
                            <td class="text-center">{{ $item->location->name }}</td>
                            <td class="text-center">{{ $item->code }}</td>
                            <td class="text-center">{{ $item->type }}</td>
                            <td class="text-center">{{ $item->condition }}</td>
                            <td class="text-center">
                                <div class="badge badge-soft p-4 {{ $item->status === 'READY' ? 'badge-success' : 'badge-error' }}">
                                    {{ $item->status }}
                                </div>
                            </td>
                            <td class="">
                                <div class="flex justify-center items-center">
                                    @can('adminFunction')
                                    <i class="fa fa-trash fa-lg cursor-pointer !leading-none" onclick="deleteItem({{ $item->id }})"></i>
                                    <i class="fa fa-pen-to-square fa-lg cursor-pointer !leading-none" onclick="openEditModal({
                                        id: {{ $item->id }},
                                        name: '{{ $item->name }}',
                                        brand: '{{ $item->brand }}',
                                        type: '{{ $item->type }}',
                                        condition: '{{ $item->condition }}',
                                        status: '{{ $item->status }}',
                                        code: '{{ $item->code }}',
                                        description: `{{ $item->description }}`,
                                        location_id: {{ $item->location_id }},
                                        category_id: {{ $item->category_id }},
                                        image: '{{ $item->image }}'
                                    })"></i>
                                    @endcan
                                    <i class="fa-regular mb-2 fa-eye fa-lg cursor-pointer" onclick="openPreviewModal({{$item->id}})"></i>
                                </div>
                            </td>
                        </tr>
                        @empty
                                <tr>
                                    <td colspan="8" class="text-center text-gray-500">No Product found</td>
                                </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Delete Confirmation Dialog --}}
            <dialog id="confirmDeleteDialog" class="modal">
                <div class="modal-box">
                    <form method="dialog">
                        <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeDeleteDialog()">✕</button>
                        <h1 class="text-xl font-bold text-center mb-4">Delete Item?</h1>
                        <p class="text-center text-gray-600">Are you sure you want to delete this item? This action cannot be undone.</p>
                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" onclick="closeDeleteDialog()" class="bg-gray-300 text-gray-800 rounded-lg px-4 py-2 hover:bg-gray-400">Cancel</button>
                            <button type="button" onclick="confirmDelete()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-red-600">Yes, Delete</button>
                        </div>
                    </form>
                </div>
            </dialog>

            {{-- Edit Product Dialog --}}
            <dialog id="editProduct" class="modal">
                <div class="modal-box">
                    <form method="dialog">
                        <button type="button" onclick="closeEditModal()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                    </form>
                    <h1 class="font-semibold text-2xl mb-4">Edit Product</h1>
                    
                    <div class="mb-4 text-gray-600">
                        <h1 class="font-medium">IMAGE</h1>
                        <div class="flex items-center gap-4">
                            <div class="avatar">
                                <div class="w-24 rounded-lg bg-gray-200">
                                    <img id="edit_imagePreview" src="{{ asset('image/default.png') }}" alt="Preview" class="w-full h-full object-cover" />
                                </div>
                            </div>
                            <input type="file" id="edit_imageUpload" name="image" class="file-input file-input-bordered w-full max-w-xs" accept="image/*" />
                        </div>
                    </div>

                    <div class="flex gap-5 justify-between text-gray-600">
                        <div class="w-[50%]">
                            <h1 class="font-medium">PRODUCT</h1>
                            <div class="mb-2">
                                <label class="input flex text-gray-600" style="width: 100%;">
                                    <input class="w-full" type="text" id="edit_product" placeholder="product" />
                                </label>
                            </div>
                        </div>
                        <div class="w-[50%]">
                            <h1 class="font-medium">RACK</h1>
                            <label class="select">
                                <select id="edit_rack">
                                    <option>Insert Rack</option>
                                    @foreach ($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name . ' | '. $location->description }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex gap-5 justify-between text-gray-600">
                        <div class="w-[50%]">
                            <h1 class="font-medium">BRAND</h1>
                            <div class="mb-2">
                                <label class="input flex text-gray-600" style="width: 100%;">
                                    <input class="w-full" type="text" id="edit_brand" placeholder="brand" />
                                </label>
                            </div>
                        </div>
                        <div class="w-[50%]">
                            <h1 class="font-medium">CONDITION</h1>
                            <div>
                                <label class="select">
                                    <select id="edit_condition">
                                        <option>Insert Condition</option>
                                        <option value="GOOD">Good</option>
                                        <option value="NOT GOOD">Not Good</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-5 justify-between text-gray-600">
                        <div class="w-[50%]">
                            <h1 class="font-medium">TYPE</h1>
                            <div class="mb-2">
                                <label class="input flex text-gray-600" style="width: 100%;">
                                    <input class="w-full" type="text" id="edit_type" placeholder="type" />
                                </label>
                            </div>
                        </div>
                        <div class="w-[50%]">
                            <h1 class="font-medium">STATUS</h1>
                            <div>
                                <label class="select">
                                    <select id="edit_status">
                                        <option value="READY">READY</option>
                                        <option value="NOT READY">NOT READY</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="w-[50%]">
                        <h1 class="font-medium">CATEGORY</h1>
                        <div>
                            <label class="select">
                                <select id="edit_category" class="input w-full">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category) 
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                    </div>

                    <div class="flex w-full mb-2">
                        <div class="w-full">
                            <h1 class="font-medium text-gray-600">SERIAL NUMBER</h1>
                            <label class="input flex text-gray-600" style="width: 100%;">
                                <input class="w-full" type="text" id="edit_serialNumber" placeholder="Serial Number" />
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h1 class="font-medium text-gray-600">DESCRIPTION</h1>
                        <textarea id="edit_description" class="textarea text-gray-600" placeholder="Description" style="width: 100%;"></textarea>
                    </div>

                    <div class="w-full flex justify-end items-end gap-4">
                        <button type="button" onclick="closeEditModal()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Cancel</button>
                        <button type="button" onclick="submitEditForm()" class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Update</button>
                    </div>
                </div>
            </dialog>

            {{-- Preview Product Dialog --}}
            <dialog id="viewProduct" class="modal">
                <div class="modal-box">
                    <form method="dialog" id="viewForm">
                        <div class="w-full mb-4">
                            <img src="{{ asset('image/default.png') }}" id="view_image" alt="Preview" class="w-full h-[180px] object-cover rounded-lg">
                        </div>

                        <button type="button" onclick="document.getElementById('viewProduct').close()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                        <h1 class="font-semibold text-2xl mb-4">Product Details</h1>

                        <div class="flex gap-5 justify-between text-gray-600">
                            <div class="w-[50%]">
                                <h1 class="font-medium">PRODUCT</h1>
                                <p id="view_product">-</p>
                            </div>
                            <div class="w-[50%]">
                                <h1 class="font-medium">RACK</h1>
                                <p id="view_rack">-</p>
                            </div>
                        </div>

                        <div class="flex gap-5 justify-between text-gray-600 mt-3">
                            <div class="w-[50%]">
                                <h1 class="font-medium">BRAND</h1>
                                <p id="view_brand">-</p>
                            </div>
                            <div class="w-[50%]">
                                <h1 class="font-medium">CONDITION</h1>
                                <p id="view_condition">-</p>
                            </div>
                        </div>

                        <div class="flex gap-5 justify-between text-gray-600 mt-3">
                            <div class="w-[50%]">
                                <h1 class="font-medium">TYPE</h1>
                                <p id="view_type">-</p>
                            </div>
                            <div class="w-[50%]">
                                <h1 class="font-medium">STATUS</h1>
                                <p id="view_status">-</p>
                            </div>
                        </div>

                        <div class="flex gap-5 justify-between text-gray-600 mt-3">
                            <div class="w-[50%]">
                                <h1 class="font-medium">CATEGORY</h1>
                                <p id="view_category">-</p>
                            </div>
                            <div class="w-[50%]">
                                <h1 class="font-medium">SERIAL NUMBER</h1>
                                <p id="view_serial">-</p>
                            </div>
                        </div>

                        <div class="w-full mt-3">
                            <h1 class="font-medium text-gray-600">DESCRIPTION</h1>
                            <p id="view_description" class="text-gray-600">-</p>
                        </div>

                        <div class="w-full flex justify-end items-end gap-4 mt-4">
                            <button type="button" onclick="document.getElementById('viewProduct').close()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Close</button>
                        </div>
                    </form>
                </div>
            </dialog>
        </div>

        <div class="flex justify-end mb-4 mt-4">
            <div class="join">
                @if ($items->onFirstPage())
                <button class="join-item btn btn-disabled">«</button>
                @else
                <a href="{{ $items->previousPageUrl() }}" class="join-item btn">«</a>
                @endif

                @foreach ($items->getUrlRange(1, $items->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="join-item btn {{ $items->currentPage() == $page ? 'btn-primary' : '' }}">
                    {{ $page }}
                </a>
                @endforeach

                @if ($items->hasMorePages())
                <a href="{{ $items->nextPageUrl() }}" class="join-item btn">»</a>
                @else
                <button class="join-item btn btn-disabled">»</button>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let deleteTargetId = null;
    let currentEditId = null;

    // Image preview for new product
    document.getElementById("imageUpload").addEventListener("change", function() {
        const file = this.files[0];
        const preview = document.getElementById("imagePreview");
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = "{{ asset('image/default.png') }}";
        }
    });

    // Image preview for edit product
    document.getElementById("edit_imageUpload").addEventListener("change", function() {
        const file = this.files[0];
        const preview = document.getElementById("edit_imagePreview");
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            // When no file is selected, check if current image is default
            if (preview.src.includes('image/default.png')) {
                preview.src = "{{ asset('image/default.png') }}";
            }
        }
    });

    // New product form submission
    document.getElementById("itemForm").addEventListener("submit", function(e) {
        e.preventDefault();

        const formData = new FormData();
        const imageInput = document.getElementById("imageUpload");
        const preview = document.getElementById("imagePreview");
        
        // Only include image if a new one was selected and it's not the default
        if (imageInput.files.length > 0 && !preview.src.includes('image/default.png')) {
            formData.append("image", imageInput.files[0]);
        } else {
            // Explicitly set image to null if no image was selected
            formData.append("image", "");
        }

        const product = document.getElementById("product").value;
        const brand = document.getElementById("brand").value;
        const type = document.getElementById("type").value;
        const location = document.getElementById("rack").value;
        const condition = document.getElementById("condition").value;
        const status = 'READY';
        const serialNumber = document.getElementById("serialNumber").value;
        const description = document.getElementById("description").value;
        const categoryId = document.getElementById("category_select").value;

        formData.append("name", `${product} ${type}`);
        formData.append("brand", brand);
        formData.append("type", type);
        formData.append("location_id", location);
        formData.append("condition", condition);
        formData.append("status", status);
        formData.append("code", serialNumber);
        formData.append("description", description);
        formData.append("category_id", categoryId);

        fetch("/api/items", {
            method: "POST",
            body: formData,
        })
        .then(res => res.json())
        .then(data => {
            if (data.errors) {
                showToast("Please fill in all fields correctly", "error");
            } else {
                showToast("Item created successfully", "success");
                document.getElementById("itemForm").reset();
                document.getElementById("imagePreview").src = "{{ asset('image/default.png') }}";
                document.getElementById("newProduct").close();
                window.location.reload();
            }
        });
    });

    function closeModal() {
        document.getElementById('newProduct').close();
    }

   function openPreviewModal(id) {
    fetch(`/api/items/${id}`)
    .then(res => {
        if (!res.ok) throw new Error("Product not found");
        return res.json();
    })
    .then(response => {
        const item = response.data;

        document.getElementById("view_product").textContent = item.name;
        document.getElementById("view_rack").textContent = item.location?.name ?? "-";
        document.getElementById("view_brand").textContent = item.brand;
        document.getElementById("view_condition").textContent = item.condition;
        document.getElementById("view_type").textContent = item.type;
        document.getElementById("view_status").textContent = item.status;
        document.getElementById("view_serial").textContent = item.code;
        document.getElementById("view_description").textContent = item.description ?? "-";
        document.getElementById("view_category").textContent = item.category?.name ?? "-";

        // Updated image URL logic
        const imageUrl = (item.image === 'items/default.png' || item.image === 'default.png' || !item.image)
            ? '/image/default.png'
            : `/storage/${item.image}`;
        document.getElementById("view_image").src = imageUrl;

        document.getElementById("viewProduct").showModal();
    })
    .catch(error => {
        console.error("Failed to fetch product:", error);
        showToast("Failed to load product data", "error");
    });
}

    // Edit modal functions
    function openEditModal(item) {
    currentEditId = item.id;
    
    // Set form values
    document.getElementById("edit_product").value = item.name || "";
    document.getElementById("edit_brand").value = item.brand || "";
    document.getElementById("edit_type").value = item.type || "";
    document.getElementById("edit_condition").value = item.condition || "";
    document.getElementById("edit_status").value = item.status || "";
    document.getElementById("edit_serialNumber").value = item.code || "";
    document.getElementById("edit_description").value = item.description || "";
    document.getElementById("edit_rack").value = item.location_id || "";
    document.getElementById("edit_category").value = item.category_id || "";
    
    // Updated image preview logic
    const imageUrl = (item.image === 'items/default.png' || item.image === 'default.png' || !item.image)
        ? '/image/default.png'
        : `/storage/${item.image}`;
    document.getElementById("edit_imagePreview").src = imageUrl;
    
    document.getElementById("editProduct").showModal();
}

    function closeEditModal() {
        document.getElementById("editProduct").close();
        currentEditId = null;
    }

    function submitEditForm() {
    if (!currentEditId) return;

    const formData = new FormData();
    const imageInput = document.getElementById("edit_imageUpload");
    
    // Always append the image if a file is selected
    if (imageInput.files.length > 0) {
        formData.append("image", imageInput.files[0]);
    }

    // Append other form data
    formData.append("name", document.getElementById("edit_product").value);
    formData.append("brand", document.getElementById("edit_brand").value);
    formData.append("type", document.getElementById("edit_type").value);
    formData.append("location_id", document.getElementById("edit_rack").value);
    formData.append("condition", document.getElementById("edit_condition").value);
    formData.append("status", document.getElementById("edit_status").value);
    formData.append("code", document.getElementById("edit_serialNumber").value);
    formData.append("description", document.getElementById("edit_description").value);
    formData.append("category_id", document.getElementById("edit_category").value);
    formData.append("_method", "PUT");

    fetch(`/api/items/${currentEditId}`, {
        method: "POST",
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            // Don't set Content-Type header - let the browser set it with the boundary
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        showToast("Item updated successfully", "success");
        closeEditModal();
        window.location.reload();
    })
    .catch(error => {
        console.error("Error updating item:", error);
        showToast(error.errors ? Object.values(error.errors).join(', ') : "Failed to update item", "error");
    });
}

    // Delete functions
    function deleteItem(id) {
        deleteTargetId = id;
        document.getElementById("confirmDeleteDialog").showModal();
    }

    function confirmDelete() {
        if (!deleteTargetId) return;

        fetch(`/api/items/${deleteTargetId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => {
            if (res.ok) {
                showToast("Item deleted successfully", "success");
                window.location.reload();
            } else {
                throw new Error("Delete failed");
            }
        })
        .catch(error => {
            console.error(error);
            showToast("Item not deleted", "error");
        });

        closeDeleteDialog();
    }

    function closeDeleteDialog() {
        document.getElementById("confirmDeleteDialog").close();
        deleteTargetId = null;
    }

    function applyFilter() {
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams();

        for (const [key, value] of formData.entries()) {
            params.append(key, value);
        }

        fetch(`/items/filter?${params.toString()}`)
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById("itemTableBody");
                tbody.innerHTML = ""; // Kosongkan isi lama

                if (data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="8" class="text-center text-gray-500">No items found</td></tr>`;
                    return;
                }

                data.forEach(item => {
                    tbody.innerHTML += `
                    <tr>
                        <td class="flex justify-center">
        <img src="${item.image === 'items/default.png' || item.image === 'default.png' ? '/image/default.png' : '/storage/' + item.image}" 
             alt="Product Image" class="w-12 h-12 object-cover" />
    </td>
                        <td class="text-center">${item.name}</td>
                        <td class="text-center">${item.location.description}</td>
                        <td class="text-center">${item.code}</td>
                        <td class="text-center">${item.type}</td>
                        <td class="text-center">${item.condition}</td>
                        <td class="text-center">
                            <div class="badge badge-soft p-4 ${item.status === 'READY' ? 'badge-success' : 'badge-error'}">
                                ${item.status}
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="flex justify-center items-center">
                                    @can('adminFunction')
                                    <i class="fa fa-trash fa-lg cursor-pointer !leading-none" onclick="deleteItem(${item.id})"></i>
                                    <i class="fa fa-pen-to-square fa-lg cursor-pointer !leading-none" onclick="openEditModal({
                                        id:${item.id},
                                        name: '${item.name}',
                                        brand: '${item.brand}',
                                        type: '${item.type}',
                                        condition: '${item.condition}',
                                        status: '${item.status}',
                                        code: '${item.code}',
                                        description: '${item.description}',
                                        location_id: ${item.location_id},
                                        category_id: ${item.category_id},
                                        image: '${item.image}'
                                    })"></i>
                                    @endcan
                                    <i class="fa-regular mb-2 fa-eye fa-lg cursor-pointer" onclick="openPreviewModal(${item.id})"></i>
                                </div>
                        </td>
                    </tr>
                `;
                });
            })
            .catch(error => {
                console.error("Error:", error);
            });
    }

    function resetFilter(type) {
        const inputs = document.querySelectorAll(`input[name="${type}"]`);
        inputs.forEach(input => input.checked = false);
    }

    // Toast notification
    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `toast toast-top toast-end`;
        toast.innerHTML = `
            <div class="alert ${type === 'success' ? 'alert-success' : 'alert-error'}">
                <span>${message}</span>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
</script>
@endpush

@stack('scripts')
@include('template.footer')