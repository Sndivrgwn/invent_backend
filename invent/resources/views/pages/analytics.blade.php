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
                <h1 class="text-2xl font-semibold py-4">Inventory Analytics </h1>
            </div>
            <div class="flex-none">
                {{-- new product --}}
                <button class="bg-[#ffffff] rounded-lg py-2 px-4 mx-5 hover:bg-blue-400 cursor-pointer flex justify-center items-center">
                    <div class="gap-2 flex">
                        <i class="fa fa-download" style="display: flex; justify-content: center; align-items: center;"></i>
                        <a href="{{ route('analytics.export') }}">Export Report</a>
                    </div>
                </button>
            </div>
        </div>

        <div class="list bg-base-100 rounded-box shadow-md ">

            <div class="p-4 pb-2 flex">
                <!-- search -->
                <div class="navbar ">
                    <div class="flex-1 relative w-full hidden md:block mr-4">
                        <p class="font-medium text-xl ms-5">Category Overview</p>
                    </div>
                @can('isAdmin')
                    
                    <div class="flex-none">
                        <button class="bg-[#2563EB] text-white rounded-lg py-2 px-4 mx-5 hover:bg-blue-400 cursor-pointer flex justify-center items-center" onclick="newProduct.showModal()">
                            <div class="gap-2 flex">
                                <i class="fa fa-plus" style="display: flex; justify-content: center; align-items: center;"></i>
                                <span>New Category</span>
                            </div>
                        </button>
                    </div>
                    @endcan
                    <dialog id="newProduct" class="modal">
                        <div class="modal-box">
                            <form method="POST" id="itemForm" action="{{ route('analytics.store') }}">
                                @csrf
                                <button id="cancel" type="button" onclick="document.getElementById('newProduct').close()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                <h1 class="font-semibold text-2xl mb-4">New Category</h1>
                                <div class="flex gap-5 justify-between text-gray-600">
                                    <!-- Rack -->
                                    <div class="w-[50%]">
                                        <h1 class="font-medium">Category Name</h1>
                                        <div class="mb-2">
                                            <input type="text" name="name" class="input input-bordered w-full max-w-xs" placeholder="Enter Category name">
                                        </div>
                                    </div>
                                    <!-- Location -->
                                    <div class="w-[50%]">
                                        <h1 class="font-medium">Desciption</h1>
                                        <input type="text" name="description" class="input input-bordered w-full max-w-xs" placeholder="Enter description">
                                    </div>
                                </div>
                                <div class="w-full flex justify-end items-end gap-4">
                                    <button id="cancelButton" type="button" onclick="document.getElementById('newProduct').close()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Cancel</button>
                                    <button type="submit" class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Submit</button>
                                </div>
                            </form>

                            @push('scripts')
                            <script>
                                function closeModal() {
                                    document.getElementById('newProduct').close();
                                }
                            </script>
                            @endpush
                        </div>
                    </dialog>
                </div>
            </div>
            <div class="flex flex-col gap-8 p-4">
                <!-- table -->
                @foreach($categories as $category)
<div class="mb-6 flex flex-col gap-4">
    <h2 class="text-lg ms-12 font-bold mb-2">{{ $category->name }}</h2>
    <p class="ms-12 font-italic">{{ $category->description }}</p>
    <div class="overflow-x-auto">
        <table class="table w-full bg-white rounded shadow-md">
            <thead>
                <tr>
                    <th class="text-center">TYPE</th>
                    <th class="text-center">QUANTITY</th>
                    <th class="text-center">AVAILABLE</th>
                    <th class="text-center">LOANED</th>
                    <th class="text-center">LOW STOCK</th>
                </tr>
            </thead>
            <tbody>
                @foreach($category->type_summaries as $type)
                <tr>
                    <td class="text-center">{{ $type->type }}</td>
                    <td class="text-center">{{ $type->quantity }}</td>
                    <td class="text-center">{{ $type->available }}</td>
                    <td class="text-center">{{ $type->loaned }}</td>
                    <td class="text-center">{{ $type->low_stock }}</td>
                </tr>
                @endforeach
                @can('isAdmin')
                
                <tr>
                    <td colspan="5" class="text-end">
                        <button type="button" class="btn btn-primary text-white rounded-lg px-4 py-2 cursor-pointer" onclick="openEditModal({{ $category->id }}, '{{ $category->name }}', '{{ $category->description }}')">
                            edit
                        </button>
                        <button type="button" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-red-800 cursor-pointer" onclick="deleteItem({{ $category->id }})">delete</button>
                    </td>
                </tr>
                @endcan
            </tbody>
        </table>
    </div>
</div>
@endforeach
            </div>

            <dialog id="confirmDeleteDialog" class="modal">
                <div class="modal-box">
                    <form method="dialog">
                        <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeDeleteDialog()">✕</button>
                        <h1 class="text-xl font-bold text-center mb-4">Delete Item?</h1>
                        <p class="text-center text-gray-600">Are you sure you want to delete this item? The product will also deleted. Check before you submit.</p>
                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" onclick="closeDeleteDialog()" class="bg-gray-300 text-gray-800 rounded-lg px-4 py-2 hover:bg-gray-400 cursor-pointer">Cancel</button>
                            <button type="button" onclick="confirmDelete()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-red-600 cursor-pointer">Yes, Delete</button>
                        </div>
                    </form>
                </div>
            </dialog>

            <dialog id="editProduct" class="modal">
                <div class="modal-box">
                    <form id="editForm" method="POST">
                        <button type="button" onclick="closeEditModal()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                        <h1 class="font-semibold text-2xl mb-4">Edit Category</h1>
                        <input type="hidden" id="edit_category_id" name="id">

                        <div class="flex gap-5 justify-between text-gray-600">
                            <div class="w-[50%]">
                                <h1 class="font-medium">NAME</h1>
                                <input type="text" id="edit_name" name="name" class="input w-full" placeholder="Category name">
                            </div>
                            <div class="w-[50%]">
                                <h1 class="font-medium">DESCRIPTION</h1>
                                <input type="text" id="edit_description" name="description" class="input w-full" placeholder="Category description">
                            </div>
                        </div>

                        <div class="w-full flex justify-end items-end gap-4 mt-4">
                            <button type="button" onclick="closeEditModal()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Cancel</button>
                            <button type="submit" class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Save</button>
                        </div>
                    </form>
                </div>
            </dialog>

            <script>
                let deleteTargetId = null;

                async function deleteItem(id) {
                    deleteTargetId = id;
                    document.getElementById("confirmDeleteDialog").showModal();
                }

                async function confirmDelete() {
    if (!deleteTargetId) return;

    try {
        const res = await fetch(`/api/analytics/${deleteTargetId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        const data = await res.json();

        if (res.ok) {
            showToast(data.toast.message, data.toast.type);
            if (data.reload) {
                window.location.reload();
            }
        } else {
            showToast(data.message || 'Failed to delete category', 'error');
            console.error(data.message || res.statusText);
        }
    } catch (error) {
        showToast('An error occurred while deleting the category', 'error');
        console.error('Error:', error);
    } finally {
        deleteTargetId = null;
        closeDeleteDialog();
    }
}
                

                function closeDeleteDialog() {
                    document.getElementById("confirmDeleteDialog").close();
                    deleteTargetId = null;
                }

                function closeEditModal() {
                    document.getElementById('editProduct').close();
                }

                function openEditModal(id, name, description) {
                    document.getElementById('edit_category_id').value = id;
                    document.getElementById('edit_name').value = name;
                    document.getElementById('edit_description').value = description;
                    document.getElementById('editProduct').showModal();
                }

                // Update the form submission handler
                document.getElementById("editForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const id = document.getElementById("edit_category_id").value;
    const payload = {
        name: document.getElementById("edit_name").value,
        description: document.getElementById("edit_description").value,
        _method: 'PUT'
    };

    try {
        const response = await fetch(`/api/analytics/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (response.ok) {
            showToast('Category updated successfully', 'success');
            closeEditModal();
            window.location.reload();
        } else {
            const errorMsg = data.message || data.errors ? Object.values(data.errors).join('<br>') : 'Failed to update category';
            showToast(errorMsg, 'error');
            console.error('Error:', data.message || response.statusText);
        }
    } catch (error) {
        showToast('An error occurred while updating the category', 'error');
        console.error('Error:', error);
    }
});

                // Toast functions
               
            </script>
        </div>
    </div>
</div>

@include('template.footer')