@include('template.head')

<div class="flex h-screen bg-gradient-to-b from-blue-100 to-white">
    <!-- Sidebar -->
    <div>
        @include('template.sidebar')
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto px-6">
        <!-- Navbar -->
        <div>
            @include('template.navbar')
        </div>

        <!-- Page Header -->
        <div class="navbar my-6">
            <div class="flex-1">
                <h1 class="text-2xl font-semibold py-4">Inventory Management</h1>
            </div>
            <div class="flex-none">
                <button class="bg-[#2563EB] text-white rounded-lg py-2 px-4 mx-5 hover:bg-blue-400 cursor-pointer flex justify-center items-center" onclick="document.getElementById('newInventory').showModal()">
                    <div class="gap-2 flex">
                        <i class="fa fa-plus flex justify-center items-center"></i>
                        <span>New Inventory</span>
                    </div>
                </button>
            </div>
        </div>

        <!-- New Inventory Modal -->
        <dialog id="newInventory" class="modal">
            <div class="modal-box">
                <form method="POST" id="itemForm" enctype="multipart/form-data">
                    <button id="cancel" type="button" onclick="closeModal()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                    <h1 class="font-semibold text-2xl mb-4">New Inventory</h1>

                    <!-- Image Upload -->
                    <div class="mb-4">
                        <h1 class="font-medium">Image</h1>
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
                        <!-- Rack -->
                        <div class="w-[50%]">
                            <h1 class="font-medium">Name</h1>
                            <div class="mb-2">
                                <input type="text" name="name" id="locationName" class="input input-bordered w-full max-w-xs" placeholder="Enter location name" required>
                            </div>
                        </div>
                        <!-- Location -->
                        <div class="w-[50%]">
                            <h1 class="font-medium">Description</h1>
                            <input type="text" name="description" id="locationDescription" class="input input-bordered w-full max-w-xs" placeholder="Enter location description">
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="w-full flex justify-end items-end gap-4 mt-4">
                        <button id="cancelButton" type="button" onclick="closeModal()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Cancel</button>
                        <button type="submit" class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Submit</button>
                    </div>
                </form>
            </div>
        </dialog>

        <script>
            function closeModal() {
                document.getElementById('newInventory').close();
            }

            // Image preview functionality
            document.getElementById('imageUpload').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        document.getElementById('imagePreview').src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });

            document.getElementById('itemForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch('/locations', {
                        method: 'POST'
                        , headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                        , body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        alert(data.message);
                        closeModal();
                        window.location.reload();
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Failed to create location');
                    });
            });

        </script>


        <!-- Inventory List -->
        <div class="list bg-base-100 rounded-box shadow-md">
            <div class="p-4 pb-2 flex">
                <!-- Racks Grid -->
                <div class="racks grid grid-cols-2 flex justify-center mx-auto w-3/4 gap-5 py-5">
                    @foreach ($AllLocation as $location)
                    <div onclick="openLocationDetail({{ $location->id }})" class="rack1 card border border-[#64748B] cursor-pointer hover:shadow-lg transition-shadow duration-200 rounded-2xl">
                        <div class="flex place-content-between p-5">
                            <div>
                                <p class="text-[#64748B]">{{ $location->name }}</p>
                                <p class="text-[#000000] text-3xl font-bold">{{ $totalItemAtLocation[$location->id] ?? 0 }}</p>

                            </div>
                            <i class="fa fa-server bg-[rgba(37,99,235,0.25)] rounded-3xl text-blue-500" style="width: 40px; height:40px;"></i>
                        </div>
                        <div class="rack-category flex gap-3 text-[#2563EB] justify-center items-center py-4">
                            @foreach ($categoryPerLocation[$location->id] ?? [] as $category)
                            <p class="bg-[#2563EB40] rounded-sm px-1">{{ $category }}</p>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- View Product Modal -->
        <dialog id="viewProduct" class="modal">
            <div class="modal-box max-w-xl">
                <form method="dialog" id="viewForm">
                    <!-- Product Image -->
                    <div class="w-full mb-4">
                        <img alt="Preview" class="w-full h-[180px] object-cover rounded-lg" src="{{ $location->image === 'default.png' ? asset('image/default.png') : asset('storage/' . $location->image) }}" />


                    </div>

                    <!-- Close Button -->
                    <button type="button" onclick="document.getElementById('viewProduct').close()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>

                    <h1 class="font-semibold text-2xl mb-2">Location Details</h1>
                    <h2 class="font-semibold text-xl text-blue-600 mb-4" id="modalLocationName">-</h2>

                    <!-- Location Description -->
                    <div class="w-full mt-3">
                        <h1 class="font-medium text-gray-600">DESCRIPTION</h1>
                        <p class="text-gray-600" id="modalLocationDescription">-</p>
                    </div>

                    <!-- Items -->
                    <div class="w-full mt-4">
                        <h1 class="font-medium text-gray-600 mb-2">ITEMS (Preview)</h1>
                        <ul id="modalItemList" class="list-disc pl-5 space-y-1 text-gray-700 text-sm max-h-40 overflow-y-auto">
                            <!-- Show only 5 items -->
                        </ul>

                        <button id="viewAllBtn" class="text-sm text-blue-600 mt-2 hover:underline hidden" onclick="openAllItemsModal()">
                            Lihat Semua Item →
                        </button>
                    </div>


                    <!-- Unique Categories -->
                    <div class="w-full mt-4">
                        <h1 class="font-medium text-gray-600 mb-2">CATEGORIES</h1>
                        <div id="modalCategoryList" class="flex flex-wrap gap-2">
                            <!-- Category badges -->
                        </div>
                    </div>

                    <div class="w-full flex justify-end items-end gap-4 mt-6">
                        <button type="button" class="btn btn-primary text-white rounded-lg px-4 py-2 cursor-pointer" onclick="event.stopPropagation(); prepareEditModal();">edit</button>
                        <button type="button" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-red-800 cursor-pointer" onclick="deleteItem({{ $location->id }})">delete</button>
                        <button type="button" onclick="document.getElementById('viewProduct').close()" class="btn btn-secondary text-white rounded-lg px-4 py-2 cursor-pointer">Close</button>
                    </div>
                </form>
            </div>
        </dialog>

        <dialog id="allItemsModal" class="modal">
            <div class="modal-box max-w-2xl h-[80vh] overflow-y-auto">
                <form method="dialog">
                    <h2 class="font-semibold text-2xl mb-4">Semua Item di Lokasi Ini</h2>

                    <ul id="allItemList" class="space-y-2 text-sm text-gray-700">
                        <!-- Diisi via JavaScript -->
                    </ul>

                    <div class="text-center mt-6">
                        <button type="button" onclick="document.getElementById('allItemsModal').close()" class="btn">Tutup</button>
                    </div>
                </form>
            </div>
        </dialog>

        <dialog id="confirmDeleteDialog" class="modal">
            <div class="modal-box">
                <form method="dialog">
                    <!-- Close Button -->
                    <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeDeleteDialog()">✕</button>
                    <!-- Konten -->
                    <h1 class="text-xl font-bold text-center mb-4">Delete Item?</h1>
                    <p class="text-center text-gray-600">Are you sure you want to delete this item? The product will also deleted. Check before you submit.</p>
                    <!-- Tombol -->
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" onclick="closeDeleteDialog()" class="bg-gray-300 text-gray-800 rounded-lg px-4 py-2 hover:bg-gray-400 cursor-pointer">Cancel</button>
                        <button type="button" onclick="confirmDelete()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-red-600 cursor-pointer">Yes, Delete</button>
                    </div>
                </form>
            </div>
        </dialog>

        <dialog id="editProduct" class="modal">
            <div class="modal-box">
                <form method="POST" id="editForm" enctype="multipart/form-data">
                    <button id="cancel" type="button" onclick="closeEditModal()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                    <h1 class="font-semibold text-2xl mb-4">Edit Location</h1>
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="id" id="edit_location_id">

                    <!-- Image Upload -->
                    <div class="mb-4">
                        <h1 class="font-medium">Image</h1>
                        <div class="flex items-center gap-4">
                            <div class="avatar">
                                <div class="w-24 rounded-lg bg-gray-200">
                                    <img id="editImagePreview" src="" alt="Preview" class="w-full h-full object-cover" />
                                </div>
                            </div>
                            <input type="file" id="editImageUpload" name="image" class="file-input file-input-bordered w-full max-w-xs" accept="image/*" />
                        </div>
                    </div>

                    <div class="flex gap-5 justify-between text-gray-600">
                        <div class="w-[50%]">
                            <h1 class="font-medium">NAME</h1>
                            <input type="text" name="name" id="edit_name" class="input w-full" placeholder="Location name" required>
                        </div>
                        <div class="w-[50%]">
                            <h1 class="font-medium">DESCRIPTION</h1>
                            <input type="text" name="description" id="edit_description" class="input w-full" placeholder="Location description">
                        </div>
                    </div>

                    <div class="w-full flex justify-end items-end gap-4 mt-4">
                        <button type="button" onclick="closeEditModal()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Cancel</button>
                        <button type="submit" class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Save</button>
                    </div>
                </form>
            </div>
        </dialog>

        <!-- JavaScript -->
        <script>
            let currentItems = []; // global variable
            let currentLocationId = null;
            let deleteTargetId = null;

            async function deleteItem(id) {
                deleteTargetId = id;
                document.getElementById("confirmDeleteDialog").showModal();
            }

            async function confirmDelete() {
                if (!deleteTargetId) return;

                const res = await fetch(`/api/location/${deleteTargetId}`, {
                    method: 'DELETE'
                    , headers: {
                        'Accept': 'application/json'
                        , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (res.ok) {
                    alert('Item deleted');
                    window.location.reload();
                } else {
                    const data = await res.json();
                    alert('Error bray cek console');
                    console.log(data.message || res.statusText);
                }

                deleteTargetId = null;
                closeDeleteDialog();
            }

            function closeDeleteDialog() {
                document.getElementById("confirmDeleteDialog").close();
                deleteTargetId = null;
            }

            function closeEditModal() {
                document.getElementById('editProduct').close();
            }

            document.getElementById("editForm").addEventListener("submit", function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const locationId = formData.get('id');

                // Use the correct endpoint (note: using /api/locations)
                fetch(`/api/locations/${locationId}`, {
                        method: 'POST', // Using POST with _method=PUT
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            , 'Accept': 'application/json'
                        }
                        , body: formData
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
                        alert('Location updated successfully');
                        closeEditModal();
                        window.location.reload();
                    })
                    .catch(err => {
                        console.error(err);
                        alert(err.message || 'Failed to update location');
                    });
            });

            function openLocationDetail(id) {
                currentLocationId = id;

                fetch(`/api/location/${id}`)
                    .then(response => {
                        if (!response.ok) throw new Error("Location not found");
                        return response.json();
                    })
                    .then(data => {
                        currentItems = data.items || [];
                        currentLocationData = data.location; // Store the location data

                        // Set view modal data
                        document.getElementById('modalLocationName').textContent = data.location.name || '-';
                        document.getElementById('modalLocationDescription').textContent = data.location.description || '-';

                        // Set image in view modal
                        const viewImage = document.querySelector('#viewProduct img');
                        viewImage.src = data.location.image === 'default.png' ?
                            '{{ asset("image/default.png") }}' :
                            '{{ asset("storage") }}/' + data.location.image;

                        // Set items list
                        const itemList = document.getElementById('modalItemList');
                        itemList.innerHTML = '';
                        const previewItems = currentItems.slice(0, 5);
                        previewItems.forEach(item => {
                            const li = document.createElement('li');
                            li.textContent = `${item.name} (${item.code}) - ${item.condition} [${item.category || 'No category'}]`;
                            itemList.appendChild(li);
                        });

                        // Show/hide "View All" button
                        const viewAllBtn = document.getElementById('viewAllBtn');
                        viewAllBtn.classList.toggle('hidden', currentItems.length <= 5);

                        // Set categories
                        const categoryList = document.getElementById('modalCategoryList');
                        categoryList.innerHTML = '';
                        (data.categories || []).forEach(cat => {
                            const span = document.createElement('span');
                            span.textContent = cat;
                            span.className = 'bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded';
                            categoryList.appendChild(span);
                        });

                        // Open view modal
                        document.getElementById('viewProduct').showModal();
                    })
                    .catch(err => {
                        alert('Failed to fetch location data.');
                        console.error(err);
                    });
            }

            function prepareEditModal() {
                if (!currentLocationData) return;

                // Close view modal
                document.getElementById('viewProduct').close();

                // Set edit modal data
                document.getElementById('edit_location_id').value = currentLocationData.id;
                document.getElementById('edit_name').value = currentLocationData.name;
                document.getElementById('edit_description').value = currentLocationData.description;

                // Set image preview
                const editImagePreview = document.getElementById('editImagePreview');
                editImagePreview.src = currentLocationData.image === 'default.png' ?
                    '{{ asset("image/default.png") }}' :
                    '{{ asset("storage") }}/' + currentLocationData.image;

                // Open edit modal
                document.getElementById('editProduct').showModal();
            }

            function openAllItemsModal() {
                const list = document.getElementById('allItemList');
                list.innerHTML = '';

                if (currentItems.length === 0) {
                    list.innerHTML = '<li class="text-gray-500">Tidak ada item di lokasi ini.</li>';
                } else {
                    currentItems.forEach(item => {
                        const li = document.createElement('li');
                        li.innerHTML = `
                    <div class="border border-gray-200 p-2 rounded">
                        <strong>${item.name}</strong> (${item.code})<br>
                        Kondisi: ${item.condition}<br>
                        Kategori: ${item.category || 'Tidak ada'}
                    </div>
                `;
                        list.appendChild(li);
                    });
                }

                document.getElementById('allItemsModal').showModal();
            }

        </script>



    </div>
</div>


@include('template.footer')
