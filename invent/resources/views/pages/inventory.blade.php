@include('template.head')

<div class="flex h-screen bg-gradient-to-b from-blue-100 to-white">
    <!-- Sidebar -->
    @include('template.sidebar')

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto px-6">
        <!-- Navbar -->
        @include('template.navbar')

        <!-- Page Header -->
        <div class="navbar my-6">
            <div class="flex-1">
                <h1 class="text-2xl font-semibold py-4">Inventory Management</h1>
            </div>
            @can('isAdmin')
            <div class="flex-none">
                <button class="bg-[#2563EB] text-white rounded-lg py-2 px-4 mx-5 hover:bg-blue-400 cursor-pointer flex justify-center items-center" onclick="document.getElementById('newInventory').showModal()">
                    <div class="gap-2 flex">
                        <i class="fa fa-plus flex justify-center items-center"></i>
                        <span>New Inventory</span>
                    </div>
                </button>
            </div>
            @endcan
        </div>

        <!-- New Inventory Modal -->
        <dialog id="newInventory" class="modal">
            <div class="modal-box">
                <form method="POST" id="itemForm" enctype="multipart/form-data">
                    <button type="button" onclick="closeModal()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
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
                        <div class="w-[50%]">
                            <h1 class="font-medium">Name</h1>
                            <input type="text" name="name" id="locationName" class="input input-bordered w-full" placeholder="Enter location name" required>
                        </div>
                        <div class="w-[50%]">
                            <h1 class="font-medium">Description</h1>
                            <input type="text" name="description" id="locationDescription" class="input input-bordered w-full" placeholder="Enter location description">
                        </div>
                    </div>

                    <div class="w-full flex justify-end items-end gap-4 mt-4">
                        <button type="button" onclick="closeModal()" class="btn btn-error text-white">Cancel</button>
                        <button type="submit" class="btn btn-primary text-white">Submit</button>
                    </div>
                </form>
            </div>
        </dialog>

        <!-- Inventory List -->
        <div class="list bg-base-100 rounded-box shadow-md">
            <div class="p-4 pb-2 flex">
                <!-- Racks Grid -->
                <div class="w-full py-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        @forelse ($locations as $location)
                        <div onclick="openLocationDetail({{ $location['location']->id }})" class="card border border-[#64748B] cursor-pointer hover:shadow-lg transition-shadow duration-200 rounded-2xl">
                            <div class="flex justify-between p-5">
                                <div>
                                    <p class="text-[#64748B]">{{ $location['location']->name }}</p>
                                    <p class="text-3xl font-bold">{{ $location['total_items'] }}</p>
                                </div>
                                <i class="fa fa-server bg-[rgba(37,99,235,0.25)] rounded-3xl text-blue-500 text-2xl p-2"></i>
                            </div>
                            @if($location['categories']->isNotEmpty())
                            <div class="flex flex-wrap gap-2 justify-center items-center py-4 px-4">
                                @foreach ($location['categories'] as $category)
                                <span class="bg-[#2563EB40] text-[#2563EB] text-xs px-2 py-1 rounded">{{ $category }}</span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @empty
                        <div class="col-span-full text-center py-10">
                            <p class="text-gray-500">No locations found</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- View Product Modal -->
        <dialog id="viewProduct" class="modal">
            <div class="modal-box max-w-xl">
                <form method="dialog">
                    <!-- Product Image -->
                    <div class="w-full mb-4">
                        <img id="viewLocationImage" alt="Location Image" class="w-full h-48 object-cover rounded-lg" />
                    </div>

                    <!-- Close Button -->
                    <button type="button" onclick="document.getElementById('viewProduct').close()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>

                    <h1 class="font-semibold text-2xl mb-2">Location Details</h1>
                    <h2 id="modalLocationName" class="font-semibold text-xl text-primary mb-4"></h2>

                    <div class="w-full mt-3">
                        <h1 class="font-medium text-gray-600">DESCRIPTION</h1>
                        <p id="modalLocationDescription" class="text-gray-600"></p>
                    </div>

                    <div class="w-full mt-4">
                        <h1 class="font-medium text-gray-600 mb-2">ITEMS (Preview)</h1>
                        <ul id="modalItemList" class="list-disc pl-5 space-y-1 text-gray-700 text-sm max-h-40 overflow-y-auto"></ul>
                        <button id="viewAllBtn" class="text-sm text-primary mt-2 hover:underline hidden">View All Items →</button>
                    </div>

                    <div class="w-full mt-4">
                        <h1 class="font-medium text-gray-600 mb-2">CATEGORIES</h1>
                        <div id="modalCategoryList" class="flex flex-wrap gap-2"></div>
                    </div>

                    <div class="w-full flex justify-end items-end gap-4 mt-6">
                        @can('isAdmin')
                        <button type="button" class="btn btn-primary" onclick="prepareEditModal()">Edit</button>
                        <button type="button" class="btn btn-error" onclick="deleteItem(currentLocationId)">Delete</button>
                        @endcan
                        <button type="button" class="btn btn-secondary" onclick="document.getElementById('viewProduct').close()">Close</button>
                    </div>
                </form>
            </div>
        </dialog>

        <!-- All Items Modal -->
        <dialog id="allItemsModal" class="modal">
            <div class="modal-box max-w-2xl h-[80vh] overflow-y-auto">
                <form method="dialog">
                    <h2 class="font-semibold text-2xl mb-4">All Items in This Location</h2>
                    <ul id="allItemList" class="space-y-2"></ul>
                    <div class="text-center mt-6">
                        <button type="button" class="btn" onclick="document.getElementById('allItemsModal').close()">Close</button>
                    </div>
                </form>
            </div>
        </dialog>

        <!-- Delete Confirmation Modal -->
        <dialog id="confirmDeleteDialog" class="modal">
            <div class="modal-box">
                <form method="dialog">
                    <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeDeleteDialog()">✕</button>
                    <h1 class="text-xl font-bold text-center mb-4">Delete Location?</h1>
                    <p class="text-center text-gray-600">Are you sure you want to delete this location? All items in this location will also be deleted.</p>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" class="btn" onclick="closeDeleteDialog()">Cancel</button>
                        <button type="button" class="btn btn-error" onclick="confirmDelete()">Delete</button>
                    </div>
                </form>
            </div>
        </dialog>

        <!-- Edit Modal -->
        <dialog id="editProduct" class="modal">
            <div class="modal-box">
                <form method="POST" id="editForm" enctype="multipart/form-data">
                    <button type="button" onclick="closeEditModal()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
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
                            <input type="text" name="name" id="edit_name" class="input w-full" required>
                        </div>
                        <div class="w-[50%]">
                            <h1 class="font-medium">DESCRIPTION</h1>
                            <input type="text" name="description" id="edit_description" class="input w-full">
                        </div>
                    </div>

                    <div class="w-full flex justify-end items-end gap-4 mt-4">
                        <button type="button" class="btn btn-error" onclick="closeEditModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </dialog>
    </div>
</div>

<script>
    // Global variables
    let currentItems = [];
    let currentLocationId = null;
    let currentLocationData = null;
    let deleteTargetId = null;
    // Image Preview for New Location
    document.getElementById('imageUpload')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('imagePreview').src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Image Preview for Edit Location
    document.getElementById('editImageUpload')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('editImagePreview').src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Form Submission for New Location
    document.getElementById('itemForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        submitForm(this, '/locations');
    });

    // Form Submission for Edit Location
    document.getElementById('editForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const locationId = document.getElementById('edit_location_id').value;
        submitForm(this, `/api/locations/${locationId}`);
    });

    // Generic Form Submission Function
    // Updated submitForm function with better error handling
function submitForm(form, url) {
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';
    submitButton.disabled = true;

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async response => {
        const data = await response.json();
        
        if (!response.ok) {
            const errorMessage = data.toast?.message || data.message || 'An error occurred';
            showToast(errorMessage, 'error');
            throw new Error(errorMessage);
        }
        
        handleAjaxResponse(data);
        if (!data.redirect && !data.reload) {
            closeModal();
            document.getElementById('newInventory').close();
            document.getElementById('editProduct').close();
        }
        return data;
    })
    .catch(error => {
        console.error('Error:', error);
        if (!error.message.includes('An error occurred')) {
            showToast(error.message || 'Request failed', 'error');
        }
    }).finally(() => {
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    });
}

    // Modal Functions
    function closeModal() {
        document.getElementById('newInventory').close();
    }

    function closeEditModal() {
        document.getElementById('editProduct').close();
    }

    function closeDeleteDialog() {
        document.getElementById('confirmDeleteDialog').close();
    }

    // Location Detail Functions
    function openLocationDetail(id) {
    currentLocationId = id;
    
    fetch(`/api/location/${id}`, {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(async response => {
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            const data = await response.json();
            
            if (!response.ok) {
                const errorMessage = data.toast?.message || data.message || 'Location not found';
                showToast(errorMessage, 'error');
                throw new Error(errorMessage);
            }
            
            currentItems = data.items || [];
            currentLocationData = data.location;

            // Update View Modal
            document.getElementById('modalLocationName').textContent = data.location.name;
            document.getElementById('modalLocationDescription').textContent = data.location.description || 'No description';
            document.getElementById('viewLocationImage').src = data.location.image === 'default.png' 
                ? '{{ asset("image/default.png") }}' 
                : '{{ asset("storage") }}/' + data.location.image;

            // Update Items List
            const itemList = document.getElementById('modalItemList');
            itemList.innerHTML = '';
            const previewItems = currentItems.slice(0, 5);
            previewItems.forEach(item => {
                const li = document.createElement('li');
                li.textContent = `${item.name} (${item.code}) - ${item.condition}`;
                itemList.appendChild(li);
            });

            // Toggle View All Button
            document.getElementById('viewAllBtn').classList.toggle('hidden', currentItems.length <= 5);
            document.getElementById('viewAllBtn').onclick = openAllItemsModal;

            // Update Categories
            const categoryList = document.getElementById('modalCategoryList');
            categoryList.innerHTML = '';
            (data.categories || []).forEach(cat => {
                const span = document.createElement('span');
                span.className = 'badge badge-primary';
                span.textContent = cat;
                categoryList.appendChild(span);
            });

            // Show Modal
            document.getElementById('viewProduct').showModal();
            return data;
        } else {
            const text = await response.text();
            showToast('Unexpected response from server', 'error');
            console.error('Non-JSON response:', text);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast(error.message || 'Failed to load location', 'error');
    });
}
    function prepareEditModal() {
        if (!currentLocationData) return;
        
        document.getElementById('viewProduct').close();
        
        // Fill Edit Form
        document.getElementById('edit_location_id').value = currentLocationData.id;
        document.getElementById('edit_name').value = currentLocationData.name;
        document.getElementById('edit_description').value = currentLocationData.description || '';
        document.getElementById('editImagePreview').src = currentLocationData.image === 'default.png' 
            ? '{{ asset("image/default.png") }}' 
            : '{{ asset("storage") }}/' + currentLocationData.image;

        document.getElementById('editProduct').showModal();
    }

    function openAllItemsModal() {
        const list = document.getElementById('allItemList');
        list.innerHTML = currentItems.length === 0 
            ? '<li class="text-gray-500">No items in this location</li>'
            : currentItems.map(item => `
                <li class="border border-gray-200 p-3 rounded-lg">
                    <strong>${item.name}</strong> (${item.code})<br>
                    Condition: ${item.condition}<br>
                    ${item.category ? `Category: ${item.category}` : ''}
                </li>
            `).join('');

        document.getElementById('allItemsModal').showModal();
    }

    // Delete Functions
    function deleteItem(id) {
        deleteTargetId = id;
        document.getElementById('confirmDeleteDialog').showModal();
    }

   function confirmDelete() {
    if (!deleteTargetId) return;

    fetch(`/api/location/${deleteTargetId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(async response => {
        const data = await response.json();
        
        if (!response.ok) {
            const errorMessage = data.toast?.message || data.message || 'Failed to delete location';
            showToast(errorMessage, 'error');
            throw new Error(errorMessage);
        }
        
        handleAjaxResponse(data);
        closeDeleteDialog();
        window.location.reload();
        return data;
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>

@include('template.footer')