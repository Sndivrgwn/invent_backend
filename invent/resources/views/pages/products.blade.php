@include('template.head')

<div class="flex flex-col h-screen bg-gradient-to-b from-blue-100 to-white md:flex-row">
    <!-- Sidebar -->
    <div class="w-full md:w-auto relative">
        @include('template.sidebar')
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto px-4 md:px-6">
        {{-- header --}}

        {{-- navbar --}}
        <div>
            @include('template.navbar')
        </div>

        <div class="navbar my-6">
            <div class="flex-1">
                <h1 class="text-2xl font-semibold py-4">Products</h1>
            </div>
            <div class="flex-none">
                @can('isAdmin')
                {{-- new product --}}
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
                        <!-- close button -->
                        <form method="POST" id="itemForm">
                            <button id="cancel" type="button" onclick="closeModal()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                            <h1 class="font-semibold text-2xl mb-4">New Product</h1>
                            {{-- image upload --}}
                            <div class="mb-4 text-gray-600">
                                <h1 class="font-medium">IMAGE</h1>
                                <div class="flex items-center gap-4">
                                    <div class="avatar">
                                        <div class="w-24 rounded-lg bg-gray-200">
                                            <img id="imagePreview" src="{{ asset('storage/items/default.png') }}" alt="Preview" class="w-full h-full object-cover" />
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
                                        {{-- <label class="select">
                                            <select id="product" class="w-[90vw]">
                                                <option value="">Insert Product</option>
                                                <option value="Router">Router</option>
                                                <option value="Access Point">Access Point</option>
                                            </select>
                                        </label> --}}
                                    </div>
                                </div>
                                <!-- rack -->
                                <div class="w-[50%]">
                                    <h1 class="font-medium">RACK</h1>
                                    <label class="select">
                                        <select id="rack">
                                            <option>Insert Rack</option>
                                            @foreach ($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->name . ' | '.
                                                $location->description }}</option>
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
                                <!-- status -->
                                <div class="w-[50%]">
                                    <h1 class="font-medium">CATEGORY</h1>
                                    <div>
                                        <label class="select">
                                            <select id="category_select" name="category_id" class="input w-full">
                                                <option value="">Pilih Kategori</option>
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

                            <!-- deskripsi -->
                            <div class="mb-4">
                                <h1 class="font-medium text-gray-600">DESCRIPTION</h1>
                                <textarea id="description" class="textarea text-gray-600" placeholder="Description" style="width: 100%;"></textarea>
                            </div>

                            <!-- button -->
                            <div class="w-full flex justify-end items-end gap-4">
                                <button id="cancelButton" type="button" onclick="closeModal()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Cancel</button>
                                <button class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Submit</button>
                            </div>
                        </form>

                        @push('scripts')
                        <script>

                            document.getElementById("imageUpload").addEventListener("change", function () {
                                const file = this.files[0];
                                if (file) {
                                    const reader = new FileReader();

                                    reader.onload = function (e) {
                                        document.getElementById("imagePreview").src = e.target.result;
                                    };

                                    reader.readAsDataURL(file);
                                } else {
                                    document.getElementById("imagePreview").src = "{{ asset('image/default.png') }}";
                                }
                            });

                            document.getElementById("itemForm").addEventListener("submit", function (e) {
                                e.preventDefault();

                                const formData = new FormData();

                                const image = document.getElementById("imageUpload").files[0];
                                const product = document.getElementById("product").value;
                                const brand = document.getElementById("brand").value;
                                const type = document.getElementById("type").value;
                                const location = document.getElementById("rack").value;
                                const condition = document.getElementById("condition").value;
                                const status = 'READY';
                                const serialNumber = document.getElementById("serialNumber").value;
                                const description = document.getElementById("description").value;
                                const categoryId = document.getElementById("category_select").value;

                                if (image) {
                                    formData.append("image", image);
                                }

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
                                .then((res) => res.json())
                                .then((data) => {
                                    if (data.errors) {
                                        showToast("Please fill in all fields correctly", "error");
                                        console.log("Payload error:", data.errors);
                                    } else {
                                        showToast("Item created successfully", "success");
                                        document.getElementById("itemForm").reset();
                                        closeModal();
                                        window.location.reload();
                                    }
                                });
                            });


                            function closeModal() {
                                document.getElementById('newProduct').close();
                            }

                        </script>
                        @endpush

                    </div>
                </dialog>
            </div>
        </div>

        <div class="list bg-base-100 rounded-box shadow-md">

            <div class="p-4 pb-2 flex">
                <!-- search -->
                <div class="relative w-full mr-4">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                        <span class="sr-only">Search icon</span>
                    </div>
                    <form method="GET" action="{{ route('products') }}" class="relative w-full md:block">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                            <span class="sr-only">Search icon</span>
                        </div>
                        <input type="text" name="search-navbar" value="{{ request('search-navbar') }}" class="block w-full p-2 ps-10 text-sm border border-gray-400 rounded-lg" placeholder="Search...">

                    </form>

                </div>

                <!-- filter -->
                <button class="btn flex justify-center items-center bg-transparent" onclick="filterProduct.showModal()">All Categories <i class="fa fa-filter" style="display: flex; justify-content: center; align-items: center;"></i></button>
                <dialog id="filterProduct" class="modal">
                    <div class="modal-box">
                        <!-- close button -->
                        <form method="dialog">
                            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                        </form>

                        <form id="filterForm">
                            <!-- product (brand) filter -->
                            <div class="mb-4">
                                <h1 class="text-lg font-semibold mb-2">Brand</h1>
                                <div class="flex flex-wrap gap-1">
                                    <input class="btn btn-square" type="reset" value="×" onclick="resetFilter('brand')" />
                                    @foreach($items->pluck('brand')->unique() as $brand)
                                    <input class="btn" type="radio" name="brand" value="{{ $brand }}" aria-label="{{ $brand }}" />
                                    @endforeach
                                </div>
                            </div>

                            <!-- category filter -->
                            <div class="mb-4">
                                <h1 class="text-lg font-semibold mb-2">Category</h1>
                                <div class="flex flex-wrap gap-1">
                                    <input class="btn btn-square" type="reset" value="×" onclick="resetFilter('category')" />
                                    @foreach($items->pluck('category.name')->unique() as $category)
                                    <input class="btn" type="radio" name="category" value="{{ $category }}" aria-label="{{ $category }}" />
                                    @endforeach
                                </div>
                            </div>

                            <!-- type filter -->
                            <div class="mb-4">
                                <h1 class="text-lg font-semibold mb-2">Type</h1>
                                <div class="flex flex-wrap gap-1">
                                    <input class="btn btn-square" type="reset" value="×" onclick="resetFilter('type')" />
                                    @foreach($items->pluck('type')->unique() as $type)
                                    <input class="btn" type="radio" name="type" value="{{ $type }}" aria-label="{{ $type }}" />
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-4">
                                <h1 class="text-lg font-semibold mb-2">Location</h1>
                                <div class="flex flex-wrap gap-1">
                                    <input class="btn btn-square" type="reset" value="×" onclick="resetFilter('type')" />
                                    @foreach($locations->pluck('description')->unique() as $type)
                                    <input class="btn" type="radio" name="location" value="{{ $type }}" aria-label="{{ $type }}" />
                                    @endforeach
                                </div>
                            </div>
                            <!-- condition filter -->
                            <div class="mb-4">
                                <h1 class="text-lg font-semibold mb-2">Condition</h1>
                                <div class="flex flex-wrap gap-1">
                                    <input class="btn btn-square" type="reset" value="×" onclick="resetFilter('condition')" />
                                    <input class="btn" type="radio" name="condition" value="GOOD" aria-label="GOOD" />
                                    <input class="btn" type="radio" name="condition" value="NOT GOOD" aria-label="NOT GOOD" />
                                </div>
                            </div>


                            <!-- status filter -->
                            <div class="mb-4">
                                <h1 class="text-lg font-semibold mb-2">Status</h1>
                                <div class="flex flex-wrap gap-1">
                                    <input class="btn btn-square" type="reset" value="×" onclick="resetFilter('status')" />
                                    <input class="btn" type="radio" name="status" value="READY" aria-label="READY" />
                                    <input class="btn" type="radio" name="status" value="NOT READY" aria-label="NOT READY" />
                                </div>
                            </div>

                            <!-- Apply Button -->
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
                        @foreach ($items as $item)
                        <tr>
                            <td class="flex justify-center">
                                <img src="{{ asset('storage/' . $item->image) }}" alt="Gambar Produk" />
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
                            <td class="text-center flex">
                                <div class="flex justify-center items-center">
                @can('isAdmin')

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
                                        category_id: {{ $item->category_id }}
                                    })"></i>
                @endcan
                                    <i class="fa-regular fa-eye fa-lg cursor-pointer" onclick="openPreviewModal({{$item->id}})"></i>
                                </div>
                            </td>

                            {{-- tampilan delete --}}
                            <dialog id="confirmDeleteDialog" class="modal">
                                <div class="modal-box">
                                    <form method="dialog">
                                        <!-- Close Button -->
                                        <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeDeleteDialog()">✕</button>
                                        <!-- Konten -->
                                        <h1 class="text-xl font-bold text-center mb-4">Delete Item?</h1>
                                        <p class="text-center text-gray-600">Are you sure you want to delete this item? This action cannot be undone.</p>
                                        <!-- Tombol -->
                                        <div class="flex justify-end gap-3 mt-6">
                                            <button type="button" onclick="closeDeleteDialog()" class="bg-gray-300 text-gray-800 rounded-lg px-4 py-2 hover:bg-gray-400">Cancel</button>
                                            <button type="button" onclick="confirmDelete()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-red-600">Yes, Delete</button>
                                        </div>
                                    </form>
                                </div>
                            </dialog>


                            {{-- tampilan edit --}}
                            <dialog id="editProduct" class="modal">
                                <div class="modal-box">
                                    <form id="editForm">
                                        <button id="cancel" type="button" onclick="closeEditModal()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                        <h1 class="font-semibold text-2xl mb-4">Edit Product</h1>

                                        <input type="hidden" id="edit_id">
                                        <input type="hidden" id="edit_category">

                                        <div class="flex gap-5 justify-between text-gray-600">
                                            <div class="w-[50%]">
                                                <h1 class="font-medium">PRODUCT</h1>
                                                <input type="text" id="edit_product" value="ss" class="input w-full" placeholder="Insert Product">
                                            </div>
                                            <div class="w-[50%]">
                                                <h1 class="font-medium">RACK</h1>
                                                <input type="text" id="edit_rack" class="input w-full" placeholder="Insert Rack">
                                            </div>
                                        </div>

                                        <div class="flex gap-5 justify-between text-gray-600 mt-3">
                                            <div class="w-[50%]">
                                                <h1 class="font-medium">BRAND</h1>
                                                <input type="text" id="edit_brand" class="input w-full" placeholder="Insert Brand">
                                            </div>
                                            <div class="w-[50%]">
                                                <h1 class="font-medium">CONDITION</h1>
                                                <input type="text" id="edit_condition" class="input w-full" placeholder="Insert Condition">
                                            </div>
                                        </div>

                                        <div class="flex gap-5 justify-between text-gray-600 mt-3">
                                            <div class="w-[50%]">
                                                <h1 class="font-medium">TYPE</h1>
                                                <input type="text" id="edit_type" class="input w-full" placeholder="Insert Type">
                                            </div>
                                            <div class="w-[50%]">
                                                <h1 class="font-medium">STATUS</h1>
                                                <input type="text" id="edit_status" class="input w-full" placeholder="Insert Status">
                                            </div>
                                        </div>

                                        <div class="w-full mt-3">
                                            <h1 class="font-medium text-gray-600">SERIAL NUMBER</h1>
                                            <input type="text" id="edit_serial" class="input w-full" placeholder="Serial Number">
                                        </div>

                                        <div class="w-full mt-3">
                                            <h1 class="font-medium text-gray-600">DESCRIPTION</h1>
                                            <textarea id="edit_description" class="textarea w-full text-gray-600" placeholder="Description"></textarea>
                                        </div>

                                        <div class="w-full flex justify-end items-end gap-4 mt-4">
                                            <button type="button" onclick="closeEditModal()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Cancel</button>
                                            <button type="submit" class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Edit</button>
                                        </div>
                                    </form>
                                </div>
                            </dialog>
                            {{-- tampilan edit --}}

                            {{-- tampilan preview --}}
                            <dialog id="viewProduct" class="modal">
                                <div class="modal-box">
                                    <form method="dialog" id="viewForm">
                                        <!-- Gambar atas -->
                                        <div class="w-full mb-4">
                                            <img src="{{ asset('image/cyrene.jpg') }}" id="view_image" alt="Preview" class="w-full h-[180px] object-cover rounded-lg">
                                        </div>

                                        <!-- Tombol close -->
                                        <button type="button" onclick="document.getElementById('viewProduct').close()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>

                                        <h1 class="font-semibold text-2xl mb-4">Product Details</h1>

                                        <div class="flex gap-5 justify-between text-gray-600">
                                            <div class="w-[50%]">
                                                <h1 class="font-medium">PRODUCT</h1>
                                                <p id="view_product">Access Point</p>
                                            </div>
                                            <div class="w-[50%]">
                                                <h1 class="font-medium">RACK</h1>
                                                <p id="view_rack">Rack 1</p>
                                            </div>
                                        </div>

                                        <div class="flex gap-5 justify-between text-gray-600 mt-3">
                                            <div class="w-[50%]">
                                                <h1 class="font-medium">BRAND</h1>
                                                <p id="view_brand">TP-Link</p>
                                            </div>
                                            <div class="w-[50%]">
                                                <h1 class="font-medium">CONDITION</h1>
                                                <p id="view_condition">Good</p>
                                            </div>
                                        </div>

                                        <div class="flex gap-5 justify-between text-gray-600 mt-3">
                                            <div class="w-[50%]">
                                                <h1 class="font-medium">TYPE</h1>
                                                <p id="view_type">TL-WR840N</p>
                                            </div>
                                            <div class="w-[50%]">
                                                <h1 class="font-medium">STATUS</h1>
                                                <p id="view_status">Ready</p>
                                            </div>
                                        </div>

                                        <div class="w-full mt-3">
                                            <h1 class="font-medium text-gray-600">SERIAL NUMBER</h1>
                                            <p id="view_serial">A1B2C3D4E5F6G7H</p>
                                        </div>

                                        <div class="w-full mt-3">
                                            <h1 class="font-medium text-gray-600">DESCRIPTION</h1>
                                            <p id="view_description" class="text-gray-600">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus vel enim eget lacus fermentum suscipit ut non ex.</p>
                                        </div>

                                        <div class="w-full flex justify-end items-end gap-4 mt-4">
                                            <button type="button" onclick="document.getElementById('viewProduct').close()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Close</button>
                                        </div>
                                    </form>
                                </div>
                            </dialog>
                            {{-- tampilan preview --}}

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @push('scripts')
            <script>
                let deleteTargetId = null;

                function openPreviewModal(id){
                    document.getElementById('viewProduct').showModal();

                    fetch(`/api/items/${id}`)
                    .then(res => {
                        if (!res.ok) throw new Error("Produk tidak ditemukan");
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

                        const imageUrl = item.image
                            ? `/storage/${item.image}`
                            : '/image/default.png';
                        document.getElementById("view_image").src = imageUrl;

                        document.getElementById("viewProduct").showModal();
                    })
                    .catch(error => {
                        console.error("Gagal mengambil data produk:", error);
                        showToast("Gagal mengambil data produk", "error");
                    });
                }

                function openEditModal(item) {
                    // Simpan ID ke hidden input (atau variabel global)
                    document.getElementById("edit_id").value = item.id;

                    // Isi semua input dengan data
                    document.getElementById("edit_product").value = item.name || "";
                    document.getElementById("edit_brand").value = item.brand || "";
                    document.getElementById("edit_type").value = item.type || "";
                    document.getElementById("edit_condition").value = item.condition || "";
                    document.getElementById("edit_status").value = item.status || "";
                    document.getElementById("edit_serial").value = item.code || "";
                    document.getElementById("edit_description").value = item.description || "";
                    document.getElementById("edit_rack").value = item.location_id || "";
                    document.getElementById("edit_category").value = item.category_id || "";

                    // Tampilkan modal
                    document.getElementById("editProduct").showModal();
                }

                async function deleteItem(id) {
                    deleteTargetId = id;
                    document.getElementById("confirmDeleteDialog").showModal();
                }

                async function confirmDelete() {
                    if (!deleteTargetId) return;

                    const res = await fetch(`/api/items/${deleteTargetId}`, {
                        method: 'DELETE'
                        , headers: {
                            'Accept': 'application/json'
                            , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    if (res.ok) {
                        showToast("Item deleted successfully", "success");
                        window.location.reload();
                    } else {
                        const data = await res.json();
                        showToast("Item not deleted", "error");
                        console.log(data.message || res.statusText);
                    }

                    deleteTargetId = null;
                    closeDeleteDialog();
                }

                function closeDeleteDialog() {
                    document.getElementById("confirmDeleteDialog").close();
                    deleteTargetId = null;
                }

            </script>
            @endpush


        </div>
        <div class="flex justify-end mb-4 mt-4">
            <div class="join">
                {{-- Previous Page Link --}}
                @if ($items->onFirstPage())
                <button class="join-item btn btn-disabled">«</button>
                @else
                <a href="{{ $items->previousPageUrl() }}" class="join-item btn">«</a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($items->getUrlRange(1, $items->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="join-item btn {{ $items->currentPage() == $page ? 'btn-primary' : '' }}">
                    {{ $page }}
                </a>
                @endforeach

                {{-- Next Page Link --}}
                @if ($items->hasMorePages())
                <a href="{{ $items->nextPageUrl() }}" class="join-item btn">»</a>
                @else
                <button class="join-item btn btn-disabled">»</button>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
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
                            <img class="size-12 rounded-sm" src="/image/${item.image}" />
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
                            <i class="fa fa-trash fa-lg cursor-pointer" onclick="deleteItem(${item.id})"></i>
                            <i class="fa fa-pen-to-square fa-lg"></i>
                            <i class="fa-regular fa-eye fa-lg"></i>
                        </td>
                    </tr>
                `;
                });
            })
            .catch(error => {
                console.error("Error:", error);
            });
    }

</script>
<script>
    document.getElementById('search-navbar').addEventListener('input', function() {
        const keyword = this.value;

        fetch(`/items/search?q=${encodeURIComponent(keyword)}`)
            .then(res => res.json())
            .then(items => {
                const tbody = document.querySelector('table tbody');
                tbody.innerHTML = ''; // Kosongkan dulu

                if (items.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="8" class="text-center">No items found</td></tr>`;
                    return;
                }

                items.forEach(item => {
                    tbody.innerHTML += `
                        <tr>
                            <td class="flex justify-center">
                                <img class="size-12 rounded-sm" src="/image/${item.image}" />
                            </td>
                            <td class="text-center">${item.name}</td>
                            <td class="text-center">${item.location?.description || '-'}</td>
                            <td class="text-center">${item.code}</td>
                            <td class="text-center">${item.type}</td>
                            <td class="text-center">${item.condition}</td>
                            <td class="text-center">
                                <div class="badge badge-soft p-4 ${item.status === 'READY' ? 'badge-success' : 'badge-error'}">
                                    ${item.status}
                                </div>
                            </td>
                            <td class="text-center">
                                <i class="fa fa-trash fa-lg cursor-pointer" onclick="deleteItem(${item.id})"></i>
                                <i class="fa fa-pen-to-square fa-lg"></i>
                                <i class="fa-regular fa-eye fa-lg"></i>
                            </td>
                        </tr>`;
                });
            })
            .catch(err => console.error(err));
    });

    // edit product
    function closeEditModal() {
        document.getElementById('editProduct').close();
    }

document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("editForm");
  if (!form) {
    console.warn("Form edit tidak ditemukan!");
    return;
  }

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    console.log("Form submitted tanpa refresh");

    const payload = {
      product: document.getElementById("edit_product").value,
      rack: document.getElementById("edit_rack").value,
      brand: document.getElementById("edit_brand").value,
      condition: document.getElementById("edit_condition").value,
      type: document.getElementById("edit_type").value,
      status: document.getElementById("edit_status").value,
      serial: document.getElementById("edit_serial").value,
      description: document.getElementById("edit_description").value,
    };

    console.log("Edit payload:", payload);
    alert("Simulasi update berhasil. Kirim ke API sesuai kebutuhan.");

    form.reset();
    closeEditModal();
  });
});



</script>

@stack('scripts')
@include('template.footer')
