@include('template.head')

<!-- Toast Wrapper -->
@if(session('success') || session('error'))
    <div class="toast toast-top toast-end z-50">
        @if(session('success'))
            <div class="alert alert-success">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <span>{{ session('error') }}</span>
            </div>
        @endif
    </div>
@endif


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
                <h1 class="text-2xl font-semibold py-4">Produk</h1>
            </div>
            <div class="flex-none">
                @can('adminFunction')
<div class="flex gap-3 items-center">

    <!-- Produk Baru -->
    <button class="bg-blue-600 text-white rounded-lg px-4 py-2 hover:bg-blue-500 flex items-center gap-2" onclick="newProduct.showModal()">
        <i class="fa fa-plus"></i>
        <span>Produk Baru</span>
    </button>

    <!-- Download Template -->
    <a href="{{ route('products.template') }}" class="bg-white border border-blue-600 text-blue-600 rounded-lg px-4 py-2 hover:bg-blue-50 flex items-center gap-2">
        <i class="fa fa-download"></i>
        <span>Template</span>
    </a>

    <!-- Import Produk -->
    <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="relative">
        @csrf

        <!-- Hidden File Input -->
        <input id="importFile" type="file" name="file" accept=".csv" class="hidden" onchange="this.form.submit()" required />

        <!-- Custom Import Button -->
        <button type="button" onclick="document.getElementById('importFile').click()" class="bg-green-600 text-white rounded-lg px-4 py-2 hover:bg-green-500 flex items-center gap-2">
            <i class="fa fa-upload"></i>
            <span>Import Produk</span>
        </button>
    </form>

</div>
@endcan


                {{-- modal new product --}}
                <dialog id="newProduct" class="modal">
                    <div class="modal-box">
                        <form method="POST" id="itemForm">
                            <button id="cancel" type="button" onclick="closeModal()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                            <h1 class="font-semibold text-2xl mb-4">Produk baru</h1>

                            {{-- image upload --}}
                            <div class="mb-4 text-gray-600">
                                <h1 class="font-medium">GAMBAR</h1>
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
                                    <h1 class="font-medium">PRODUK</h1>
                                    <div class="mb-2">
                                        <label class="input flex text-gray-600" style="width: 100%;">
                                            <input class="w-full" type="text" id="product" placeholder="product" />
                                        </label>
                                    </div>
                                </div>
                                <!-- rack -->
                                <div class="w-[50%]">
                                    <h1 class="font-medium">RAK</h1>
                                    <label class="select">
                                        <select id="rack">
                                            <option>Pilih rak</option>
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
                                    <h1 class="font-medium">MEREK</h1>
                                    <div class="mb-2">
                                        <label class="input flex text-gray-600" style="width: 100%;">
                                            <input class="w-full" type="text" id="brand" placeholder="brand" />
                                        </label>
                                    </div>
                                </div>
                                <!-- condition -->
                                <div class="w-[50%]">
                                    <h1 class="font-medium">KONDISI</h1>
                                    <div>
                                        <label class="select">
                                            <select id="condition">
                                                <option>Pilih Kondisi</option>
                                                <option value="GOOD">GOOD</option>
                                                <option value="NOT GOOD">NOT GOOD</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-5 justify-between text-gray-600">
                                <!-- Type -->
                                <div class="w-[50%]">
                                    <h1 class="font-medium">TIPE</h1>
                                    <div class="mb-2">
                                        <label class="input flex text-gray-600" style="width: 100%;">
                                            <input class="w-full" type="text" id="type" placeholder="type" />
                                        </label>
                                    </div>
                                </div>
                                <!-- category -->
                                <div class="w-[50%]">
                                    <h1 class="font-medium">KATEGORI</h1>
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
                                    <h1 class="font-medium text-gray-600">NOMOR SERIAL</h1>
                                    <label class="input flex text-gray-600" style="width: 100%;">
                                        <input class="w-full" type="text" id="serialNumber" placeholder="Serial Number" />
                                    </label>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <h1 class="font-medium text-gray-600">DESKRIPSI</h1>
                                <textarea id="description" class="textarea text-gray-600" placeholder="Description" style="width: 100%;"></textarea>
                            </div>

                            <!-- buttons -->
                            <div class="w-full flex justify-end items-end gap-4">
                                <button id="cancelButton" type="button" onclick="closeModal()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Batal</button>
                                <button class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Kirim</button>
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
                        <input type="text" name="search-navbar" value="{{ request('search-navbar') }}" class="block w-full p-2 ps-10 text-sm border border-gray-400 rounded-lg" placeholder="Cari...">
                    </form>
                </div>

                <!-- filter -->
                <button class="btn flex justify-center items-center bg-transparent" onclick="filterProduct.showModal()">Kategori <i class="fa fa-filter" style="display: flex; justify-content: center; align-items: center;"></i></button>
                <dialog id="filterProduct" class="modal">
                    <div class="modal-box">
                        <form method="dialog">
                            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                        </form>

                        <form id="filterForm">
                            <div class="mb-4">
                                <h1 class="text-lg font-semibold mb-2">Merek</h1>
                                <select name="brand" class="select select-bordered w-full max-w-xs">
                                    <option value="" selected>semua Merek</option> {{-- Opsi default untuk reset/tidak memilih filter --}}
                                    @foreach($items->pluck('brand')->unique() as $brand)
                                    <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <h1 class="text-lg font-semibold mb-2">Kategori</h1>
                                <select name="category" class="select select-bordered w-full max-w-xs">
                                    <option value="" selected>semua Kategori</option> {{-- Opsi default untuk reset/tidak memilih filter --}}
                                    @foreach($items->pluck('category.name')->unique() as $category)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <h1 class="text-lg font-semibold mb-2">Tipe</h1>
                                <select name="type" class="select select-bordered w-full max-w-xs">
                                    <option value="" selected>Semua Tipe</option> {{-- Opsi default untuk reset/tidak memilih filter --}}
                                    @foreach($items->pluck('type')->unique() as $type)
                                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <h1 class="text-lg font-semibold mb-2">Lokasi</h1>
                                <select name="location" class="select select-bordered w-full max-w-xs">
                                    <option value="" selected>semua Lokasi</option> {{-- Opsi default untuk reset/tidak memilih filter --}}
                                    @foreach($locations->pluck('description')->unique() as $location)
                                    <option value="{{ $location }}" {{ request('location') == $location ? 'selected' : '' }}>{{ $location }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <h1 class="text-lg font-semibold mb-2">Kondisi</h1>
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

                            <button type="button" class="btn btn-primary mt-4" onclick="applyFilter()">Terapkan</button>
                        </form>
                    </div>
                </dialog>
            </div>

            <!-- table -->
            <div id="itemTableContainer" class="overflow-x-auto px-2">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <thead>
                                <tr>
                                    <th class="text-center font-semibold">FOTO</th>
                                    <th class="text-center font-semibold">
                                        <a href="{{ route('products', ['sortBy' => 'name', 'sortDir' => ($sortBy === 'name' && $sortDir === 'asc') ? 'desc' : 'asc', 'search-navbar' => request('search-navbar')]) }}">
                                            PRODUK
                                            @if($sortBy === 'name') {{ $sortDir === 'asc' ? '↑' : '↓' }} @endif
                                        </a>
                                    </th>
                                    <th class="text-center font-semibold">
                                        RAK {{-- atau tambahkan logic sorting location.name jika mau --}}
                                    </th>
                                    <th class="text-center font-semibold">
                                        <a href="{{ route('products', ['sortBy' => 'code', 'sortDir' => ($sortBy === 'code' && $sortDir === 'asc') ? 'desc' : 'asc', 'search-navbar' => request('search-navbar')]) }}">
                                            NOMOR SERIAL
                                            @if($sortBy === 'code') {{ $sortDir === 'asc' ? '↑' : '↓' }} @endif
                                        </a>
                                    </th>
                                    <th class="text-center font-semibold">
                                        <a href="{{ route('products', ['sortBy' => 'type', 'sortDir' => ($sortBy === 'type' && $sortDir === 'asc') ? 'desc' : 'asc', 'search-navbar' => request('search-navbar')]) }}">
                                            TIPE
                                            @if($sortBy === 'type') {{ $sortDir === 'asc' ? '↑' : '↓' }} @endif
                                        </a>
                                    </th>
                                    <th class="text-center font-semibold">
                                        <a href="{{ route('products', ['sortBy' => 'condition', 'sortDir' => ($sortBy === 'condition' && $sortDir === 'asc') ? 'desc' : 'asc', 'search-navbar' => request('search-navbar')]) }}">
                                            KONDISI
                                            @if($sortBy === 'condition') {{ $sortDir === 'asc' ? '↑' : '↓' }} @endif
                                        </a>
                                    </th>
                                    <th class="text-center font-semibold">
                                        <a href="{{ route('products', ['sortBy' => 'status', 'sortDir' => ($sortBy === 'status' && $sortDir === 'asc') ? 'desc' : 'asc', 'search-navbar' => request('search-navbar')]) }}">
                                            STATUS
                                            @if($sortBy === 'status') {{ $sortDir === 'asc' ? '↑' : '↓' }} @endif
                                        </a>
                                    </th>
                                    <th class="text-center font-semibold">TINDAKAN</th>
                                </tr>
                            </thead>

                        </tr>
                    </thead>
                    <tbody id="itemTableBody">
                        @forelse ($items as $item)
                        <tr>
                            <td class="flex justify-center">
                                <img src="{{ $item->image === 'items/default.png' || $item->image === 'default.png' ? asset('image/default.png') : asset('storage/' . $item->image) }}" alt="Product Image" class="w-12 h-12 object-cover" />
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
                            <td colspan="8" class="text-center text-gray-500">Tidak ada Produk</td>
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
                        <h1 class="text-xl font-bold text-center mb-4">Hapus item?</h1>
                        <p class="text-center text-gray-600">Apakah Anda yakin ingin menghapus item ini? Tindakan ini tidak bisa dibatalkan.</p>
                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" onclick="closeDeleteDialog()" class="bg-gray-300 text-gray-800 rounded-lg px-4 py-2 hover:bg-gray-400">Batal</button>
                            <button type="button" onclick="confirmDelete()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-red-600">Ya, Hapus</button>
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
                    <h1 class="font-semibold text-2xl mb-4">Edit Produk</h1>

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
                            <h1 class="font-medium">PRODUK</h1>
                            <div class="mb-2">
                                <label class="input flex text-gray-600" style="width: 100%;">
                                    <input class="w-full" type="text" id="edit_product" placeholder="product" />
                                </label>
                            </div>
                        </div>
                        <div class="w-[50%]">
                            <h1 class="font-medium">RAK</h1>
                            <label class="select">
                                <select id="edit_rack">
                                    <option>Pilih Rak</option>
                                    @foreach ($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name . ' | '. $location->description }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                    </div>

                    <div class="flex gap-5 justify-between text-gray-600">
                        <div class="w-[50%]">
                            <h1 class="font-medium">MEREK</h1>
                            <div class="mb-2">
                                <label class="input flex text-gray-600" style="width: 100%;">
                                    <input class="w-full" type="text" id="edit_brand" placeholder="brand" />
                                </label>
                            </div>
                        </div>
                        <div class="w-[50%]">
                            <h1 class="font-medium">KONDISI</h1>
                            <div>
                                <label class="select">
                                    <select id="edit_condition">
                                        <option>Pilih Kondisi</option>
                                        <option value="GOOD">GOOD</option>
                                        <option value="NOT GOOD">NOT GOOD</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-5 justify-between text-gray-600">
                        <div class="w-[50%]">
                            <h1 class="font-medium">Tipe</h1>
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
                                        <option value="READY">SIAP DIGUNAKAN</option>
                                        <option value="NOT READY">BELUM SIAP </option>
                                    </select>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="w-[50%]">
                        <h1 class="font-medium">Kategori</h1>
                        <div>
                            <label class="select">
                                <select id="edit_category" class="input w-full">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                    </div>

                    <div class="flex w-full mb-2">
                        <div class="w-full">
                            <h1 class="font-medium text-gray-600">NOMOR SERIAL</h1>
                            <label class="input flex text-gray-600" style="width: 100%;">
                                <input class="w-full" type="text" id="edit_serialNumber" placeholder="Serial Number" />
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h1 class="font-medium text-gray-600">DESKRIPSI</h1>
                        <textarea id="edit_description" class="textarea text-gray-600" placeholder="Description" style="width: 100%;"></textarea>
                    </div>

                    <div class="w-full flex justify-end items-end gap-4">
                        <button type="button" onclick="closeEditModal()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Batal</button>
                        <button type="button" onclick="submitEditForm()" class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Perbarui</button>
                    </div>
                </div>
            </dialog>

            {{-- Preview Product Dialog --}}
            <dialog id="viewProduct" class="modal">
                <div class="modal-box">
                    <form method="dialog" id="viewForm">
                        <div class="w-full mb-4">
                            <img src="{{ asset('image/default.png') }}" id="view_image" alt="Preview" class="w-full h-[180px] object-cover rounded-lg cursor-pointer">
                        </div>
                        <dialog id="imageZoomModal" class="modal">
                            <div class="modal-box w-11/12 max-w-2xl p-0 flex justify-center items-center bg-transparent shadow-none">
                                {{-- Image yang akan diperbesar --}}
                                <img id="modalZoomImage" src="" alt="Zoomed Image" class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-xl" />
                            </div>
                        </dialog>

                        <button type="button" onclick="document.getElementById('viewProduct').close()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                        <h1 class="font-semibold text-2xl mb-4">Rincian Produk</h1>

                        <div class="flex gap-5 justify-between text-gray-600">
                            <div class="w-[50%]">
                                <h1 class="font-medium">PRODUK</h1>
                                <p id="view_product">-</p>
                            </div>
                            <div class="w-[50%]">
                                <h1 class="font-medium">RAK</h1>
                                <p id="view_rack">-</p>
                            </div>
                        </div>

                        <div class="flex gap-5 justify-between text-gray-600 mt-3">
                            <div class="w-[50%]">
                                <h1 class="font-medium">MEREK</h1>
                                <p id="view_brand">-</p>
                            </div>
                            <div class="w-[50%]">
                                <h1 class="font-medium">KONDISI</h1>
                                <p id="view_condition">-</p>
                            </div>
                        </div>

                        <div class="flex gap-5 justify-between text-gray-600 mt-3">
                            <div class="w-[50%]">
                                <h1 class="font-medium">TIPE</h1>
                                <p id="view_type">-</p>
                            </div>
                            <div class="w-[50%]">
                                <h1 class="font-medium">STATUS</h1>
                                <p id="view_status">-</p>
                            </div>
                        </div>

                        <div class="flex gap-5 justify-between text-gray-600 mt-3">
                            <div class="w-[50%]">
                                <h1 class="font-medium">KATEGORI</h1>
                                <p id="view_category">-</p>
                            </div>
                            <div class="w-[50%]">
                                <h1 class="font-medium">NOMOR SERIAL</h1>
                                <p id="view_serial">-</p>
                            </div>
                        </div>

                        <div class="w-full mt-3">
                            <h1 class="font-medium text-gray-600">DESKRIPSI</h1>
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
                method: "POST"
                , body: formData
            , })
            .then(res => res.json())
            .then(data => {
                if (data.errors) {
                    showToast("Harap isi semua bidang dengan benar", "error");
                } else {
                    showToast("Item berhasil dibuat", "success");
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
            document.getElementById("view_rack").textContent = item.location?.name ?? "-";  // Fixed
            document.getElementById("view_brand").textContent = item.brand;
            document.getElementById("view_condition").textContent = item.condition;
            document.getElementById("view_type").textContent = item.type;
            document.getElementById("view_status").textContent = item.status;
            document.getElementById("view_serial").textContent = item.code;
            document.getElementById("view_description").textContent = item.description ?? "-";
            document.getElementById("view_category").textContent = item.category?.name ?? "-";  // Fixed

            // Image URL logic
            const imageUrl = (item.image === 'items/default.png' || item.image === 'default.png' || !item.image) ?
                '/image/default.png' :
                `/storage/${item.image}`;
            document.getElementById("view_image").src = imageUrl;

            document.getElementById("viewProduct").showModal();
        })
        .catch(error => {
            console.error("Failed to fetch product:", error);
            showToast("Gagal memuat data produk", "error");
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
        const imageUrl = (item.image === 'items/default.png' || item.image === 'default.png' || !item.image) ?
            '/image/default.png' :
            `/storage/${item.image}`;
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
                method: "POST"
                , body: formData
                , headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    // Don't set Content-Type header - let the browser set it with the boundary
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw err;
                    });
                }
                return response.json();
            })
            .then(data => {
                showToast("Item berhasil diperbarui", "success");
                closeEditModal();
                window.location.reload();
            })
            .catch(error => {
                console.error("Error updating item:", error);
                showToast(error.errors ? Object.values(error.errors).join(', ') : "Gagal memperbarui item", "error");
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
                method: 'DELETE'
                , headers: {
                    'Accept': 'application/json'
                    , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => {
                if (res.ok) {
                    showToast("Item berhasil dihapus", "success");
                    window.location.reload();
                } else {
                    throw new Error("Hapus gagal");
                }
            })
            .catch(error => {
                console.error(error);
                showToast("Item tidak dihapus", "error");
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

<script>
    // image preview zoom
    document.addEventListener('DOMContentLoaded', function() {
        const imagePreview = document.getElementById('view_image');
        const imageZoomModal = document.getElementById('imageZoomModal');
        const modalZoomImage = document.getElementById('modalZoomImage');

        // Fungsi untuk menampilkan gambar diperbesar saat preview diklik
        imagePreview.addEventListener('click', function() {
            const currentImageSrc = imagePreview.src;
            if (currentImageSrc && currentImageSrc !== "{{ asset('image/default.png') }}") {
                modalZoomImage.src = currentImageSrc;
                imageZoomModal.showModal();
            }
        });

        // Close diluar dialog
        imageZoomModal.addEventListener('click', (event) => {
            if (event.target === imageZoomModal) {
                imageZoomModal.close();
            }
        });
    });

</script>

@stack('scripts')
@include('template.footer')
