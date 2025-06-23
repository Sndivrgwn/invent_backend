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
                    <h1 class="text-2xl font-semibold py-4">User Management</h1>
                </div>

                <div class="flex-none">
                    {{-- new product --}}
                    <button class="bg-[#2563EB] text-white rounded-lg py-2 px-4 mx-5 hover:bg-blue-400 cursor-pointer flex justify-center items-center" onclick="newProduct.showModal()">
                        <div class="gap-2 flex">
                            <i class="fa fa-plus" style="display: flex; justify-content: center; align-items: center;"></i>
                            <span>New User</span>
                        </div>
                    </button>
                    <dialog id="newProduct" class="modal">
                        <div class="modal-box">
                            <!-- close button -->
                            <form method="POST" id="itemForm" action="{{ route('users.store') }}" class="w-full">
                                @csrf
                                <input type="hidden" name="_method" value="POST">
                                <button id="cancel" type="button" onclick="closeModal()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                <h1 class="font-semibold text-2xl mb-4">New Users</h1>
                                <div class="flex gap-5 justify-between text-gray-600 mb-3">
                                    <!-- Product -->
                                    <div class="w-[100%]">
                                        <h1 class="font-medium">NAME</h1>
                                        <input type="text" name="name" placeholder="Type here" class="input" style="width: 100%;" />
                                    </div>
                                </div>
                                <div class="flex gap-5 justify-between text-gray-600 mb-6">
                                    <!-- Brand -->
                                    <div class="w-[50%]">
                                        <h1 class="font-medium">EMAIL</h1>
                                        <input type="email" name="email" placeholder="Type here" class="input" />
                                    </div>

                                    <!-- condition -->
                                    <div class="w-[50%]">
                                        <h1 class="font-medium">ROLE</h1>
                                        <div>
                                            <label class="select">
                                                <select id="condition" name="roles_id" class="select select-bordered w-full">
                                                    <option value="" disabled selected>Select Role</option>
                                                    @foreach ($roles as $u)
                                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-5 justify-between text-gray-600 mb-3">
                                    <div class="w-[100%]">
                                        <h1 class="font-medium">PASSWORD</h1>
                                        <input type="password" name="password" placeholder="insert password" class="input" style="width: 100%;" />
                                    </div>
                                </div>
                                <div class="flex gap-5 justify-between text-gray-600 mb-3">
                                    <div class="w-[100%] mb-4">
                                        <h1 class="font-medium">CONFIRM PASSWORD</h1>
                                        <input type="password" name="password_confirmation" placeholder="please confirm password" class="input" style="width: 100%;" />
                                    </div>
                                </div>
                                {{-- <div class="flex gap-5  justify-between text-gray-600 mb-3">
                                </div> --}}
                                <div class="flex gap-5 justify-between text-gray-600">

                                    <!-- button -->
                                    <div class="w-full flex justify-end items-end gap-4">
                                        <button id="cancelButton" type="button" onclick="closeModal()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Cancel</button>
                                        <button type="submit" class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Submit</button>
                                    </div>
                            </form>
                        </div>
                    </dialog>
                </div>

            </div>

            <div class="list bg-base-100 rounded-box shadow-md">

                <div class="p-4 pb-2 md:flex">
                    <!-- search -->
                    <div class="relative w-full mr-4">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                            <span class="sr-only">Search icon</span>
                        </div>
                        <form id='filterFormSearch' class="relative w-full md:block">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                                <span class="sr-only">Search icon</span>
                            </div>
                            <input type="text" id="searchInput" name="searchInput" class="block w-full p-2 ps-10 text-sm border border-gray-400 rounded-lg" placeholder="Search...">
                        </form>

                    </div>

                    <!-- filter -->
                    <button class="btn flex justify-center items-center bg-transparent me-2 mt-3 md:mt-0" onclick="filterUsers.showModal()">Filter Role <i class="fa fa-filter" style="display: flex; justify-content: center; align-items: center;"></i></button>
                    <button class="btn flex justify-center items-center btn-secondary mt-3 md:mt-0" onclick="resetFilter()">Reset Filter</button>
                    <dialog id="filterUsers" class="modal">
                        <div class="modal-box">
                            <!-- close button -->
                            <form method="dialog">
                                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                            </form>

                            <form id="filterForm">
                                <!-- ROLE -->
                                <select id="roleFilter" class="select select-bordered">
                                    <option value="">All Roles</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                </select>


                                <!-- STATUS -->
                            </form>
                            <!-- Apply Button -->
                        </div>
                    </dialog>




                </div>
                <!-- table -->
                <div id="itemTableContainer" class="overflow-x-auto px-2">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center font-semibold">NAME</th>
                                <th class="text-center font-semibold">EMAIL</th>
                                <th class="text-center font-semibold">ROLE</th>
                                <th class="text-center font-semibold">LAST ACTIVE</th>
                                <th class="text-center font-semibold">ACTION</th>
                            </tr>
                        </thead>
                        <tbody id="itemTableBody">
                            @foreach ($user as $usr)
                            <tr>
                                <td class="text-center">{{ $usr->name }}</td>
                                <td class="text-center">{{ $usr->email }}</td>
                                <td class="text-center">{{ $usr->roles->name }}</td>
                                <td class="text-center">{{ $usr->last_active_at }}</td>
                                <td class="justify-center  ">
                                    <div class="flex justify-center items-center">
                                        <i class="fa fa-trash  fa-lg cursor-pointer !leading-none" onclick="deleteItem({{ $usr->id }})"></i>
                                        <i class="fa fa-pen-to-square fa-lg cursor-pointer !leading-none" onclick="openEditModal({{ $usr->id }}, '{{ $usr->name }}', '{{ $usr->email }}', {{ $usr->roles_id }})"></i>
                                        <i class="fa-regular fa-eye fa-lg cursor-pointer mb-2" onclick="showUserDetails({{ $usr->id }})"></i>
                                    </div>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

                <dialog id="allLoansModal" class="modal">
    <div class="modal-box w-11/12 max-w-5xl">
        <h3 class="font-bold text-lg">All Loan History</h3>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Loan Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="allLoansBody">
                    <!-- Will be populated by JavaScript -->
                </tbody>
            </table>
        </div>
        <div class="modal-action">
            <form method="dialog">
                <button class="btn">Close</button>
            </form>
        </div>
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
                        <form id="editForm" method="POST">
                            <button type="button" onclick="closeEditModal()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                            <h1 class="font-semibold text-2xl mb-4">Edit User</h1>
                            <input type="hidden" id="edit_user_id" name="id">

                            <div class="flex gap-5 justify-between text-gray-600 mb-3">
                                <div class="w-full">
                                    <h1 class="font-medium">NAME</h1>
                                    <input type="text" id="edit_name" name="name" class="input w-full" placeholder="User name">
                                </div>
                            </div>

                            <div class="flex gap-5 justify-between text-gray-600 mb-3">
                                <div class="w-full">
                                    <h1 class="font-medium">EMAIL</h1>
                                    <input type="email" id="edit_email" name="email" class="input w-full" placeholder="User email">
                                </div>
                            </div>

                            <div class="flex gap-5 justify-between text-gray-600 mb-6">
                                <div class="w-full">
                                    <h1 class="font-medium">ROLE</h1>
                                    <select id="edit_role" name="roles_id" class="select select-bordered w-full">
                                        <option value="" disabled selected>Select Role</option>
                                        @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Add these password fields -->
                            <div class="flex gap-5 justify-between text-gray-600 mb-3">
                                <div class="w-[50%]">
                                    <h1 class="font-medium">NEW PASSWORD (Leave blank to keep current)</h1>
                                    <input type="password" name="password" class="input" placeholder="New password">
                                </div>
                                <div class="w-[50%]">
                                    <h1 class="font-medium">CONFIRM NEW PASSWORD</h1>
                                    <input type="password" name="password_confirmation" class="input" placeholder="Confirm new password">
                                </div>
                            </div>

                            <div class="flex justify-end gap-4 mt-4">
                                <button type="button" onclick="closeEditModal()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Cancel</button>
                                <button type="submit" class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Save</button>
                            </div>
                        </form>
                    </div>
                </dialog>

                <dialog id="viewProduct" class="modal">
                    <div class="modal-box">
                        <form method="dialog" id="viewForm">
                            <!-- Tombol close -->
                            <button type="button" onclick="document.getElementById('viewProduct').close()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>

                            <h1 class="font-semibold text-2xl mb-4">User Details</h1>

                            <div class="flex gap-5 justify-between text-gray-600">
                                <div class="w-[50%]">
                                    <h1 class="font-medium">Name</h1>
                                    <p id="viewUserName"></p>
                                </div>
                                <div class="w-[50%]">
                                    <h1 class="font-medium">Email</h1>
                                    <p id="viewUserEmail"></p>
                                </div>
                            </div>

                            <div class="flex gap-5 justify-between text-gray-600 mt-3">
                                <div class="w-[50%]">
                                    <h1 class="font-medium">Role</h1>
                                    <p id="viewUserRole"></p>
                                </div>
                                <div class="w-[50%]">
                                    <h1 class="font-medium">Last Active</h1>
                                    <p id="viewUserLastActive"></p>
                                </div>
                            </div>

                            <div class="flex gap-5 justify-between text-gray-600 mt-3">
                                <div class="w-[50%]">
                                    <h1 class="font-medium">Total Loans</h1>
                                    <p id="viewUserTotalLoans"></p>
                                </div>
                                <div class="w-[50%]">
                                    <h1 class="font-medium">Total Returns</h1>
                                    <p id="viewUserTotalReturns"></p>
                                </div>
                            </div>

                            <div class="mt-6">
                                <h2 class="font-semibold text-xl mb-2">Loan History</h2>
                                <div class="overflow-x-auto">
                                    <table class="table table-zebra">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Loan Date</th>
                                                <th>Due Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="loanHistoryBody">
                                            <!-- Loan history will be populated here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="w-full flex justify-end items-end gap-4 mt-4">
                                <button type="button" onclick="document.getElementById('viewProduct').close()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Close</button>
                            </div>
                        </form>
                    </div>
                </dialog>
                {{-- tampilan preview --}}



            </div>
        </div>
    </div>

    

<script>
    // Global variables
    let deleteTargetId = null;

    

    // User management functions
    async function deleteItem(id) {
        deleteTargetId = id;
        document.getElementById("confirmDeleteDialog").showModal();
    }

    async function confirmDelete() {
        if (!deleteTargetId) return;

        try {
            const res = await fetch(`/api/users/${deleteTargetId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await res.json();
            
            if (res.ok) {
                handleAjaxResponse({
                    toast: {
                        type: 'success',
                        message: 'User deleted successfully'
                    },
                    reload: true
                });
            } else {
                handleAjaxResponse({
                    toast: {
                        type: 'error',
                        message: data.message || 'Failed to delete user'
                    }
                });
            }
        } catch (error) {
            showToast('An error occurred while deleting user', 'error');
            console.error(error);
        }

        deleteTargetId = null;
        closeDeleteDialog();
    }

    function closeDeleteDialog() {
        document.getElementById("confirmDeleteDialog").close();
        deleteTargetId = null;
    }

    // Modal control functions
    function closeModal() {
        document.getElementById('newProduct').close();
    }

    function closeEditModal() {
        document.getElementById('editProduct').close();
    }

    // Edit user functions
    function openEditModal(id, name, email, roleId) {
        document.getElementById('edit_user_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_role').value = roleId;
        document.getElementById('editProduct').showModal();
    }

    // View user details function
    async function showUserDetails(userId) {
        try {
            // Show loading state
            const modal = document.getElementById('viewProduct');
            modal.showModal();
            
            // Add loading indicator
            document.getElementById('viewUserName').textContent = 'Loading...';
            document.getElementById('loanHistoryBody').innerHTML = '<tr><td colspan="4" class="text-center">Loading...</td></tr>';
            
            // Fetch user details
            const response = await fetch(`/api/users/${userId}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            // Check if we got valid user data
            if (!data.user) {
                throw new Error('Invalid user data received');
            }
            
            const user = data.user;
            const loans = user.loans || [];
            
            // Populate user details
            document.getElementById('viewUserName').textContent = user.name;
            document.getElementById('viewUserEmail').textContent = user.email;
            document.getElementById('viewUserRole').textContent = user.roles?.name || 'N/A';
            document.getElementById('viewUserLastActive').textContent = user.last_active_at || 'N/A';
            document.getElementById('viewUserTotalLoans').textContent = data.total_loans || 0;
            document.getElementById('viewUserTotalReturns').textContent = data.total_returned_loans || 0;
            
            // Populate loan history (limited to 4)
            const loanHistoryBody = document.getElementById('loanHistoryBody');
            loanHistoryBody.innerHTML = '';
            
            if (loans.length === 0) {
                loanHistoryBody.innerHTML = '<tr><td colspan="4" class="text-center">No loan history found</td></tr>';
            } else {
                loans.forEach(loan => {
                    if (loan.items && loan.items.length > 0) {
                        loan.items.forEach(item => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${item.name || 'N/A'} (Qty: ${item.pivot?.quantity || 1})</td>
                                <td>${loan.loan_date ? new Date(loan.loan_date).toLocaleDateString() : 'N/A'}</td>
                                <td>${loan.return_date ? new Date(loan.return_date).toLocaleDateString() : 'N/A'}</td>
                                <td>${loan.status || 'N/A'}</td>
                            `;
                            loanHistoryBody.appendChild(row);
                        });
                    } else {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>No items</td>
                            <td>${loan.loan_date ? new Date(loan.loan_date).toLocaleDateString() : 'N/A'}</td>
                            <td>${loan.return_date ? new Date(loan.return_date).toLocaleDateString() : 'N/A'}</td>
                            <td>${loan.status || 'N/A'}</td>
                        `;
                        loanHistoryBody.appendChild(row);
                    }
                });
            }
            
            // Add "View More" button if there are more loans
            if (data.has_more_loans) {
                const viewMoreRow = document.createElement('tr');
                viewMoreRow.innerHTML = `
                    <td colspan="4" class="text-center">
                        <button onclick="showAllLoans(${userId})" class="btn btn-primary">
                            View All ${data.total_loans} Loans
                        </button>
                    </td>
                `;
                loanHistoryBody.appendChild(viewMoreRow);
            }
            
        } catch (error) {
            console.error('Error fetching user details:', error);
            document.getElementById('loanHistoryBody').innerHTML = `
                <tr>
                    <td colspan="4" class="text-center text-red-500">
                        Error loading data: ${error.message}
                    </td>
                </tr>
            `;
            showToast('Error loading user details', 'error');
            document.getElementById('viewProduct').showModal();
        }
    }

    // New function to show all loans
    async function showAllLoans(userId) {
        try {
            // Show loading state
            const allLoansModal = document.getElementById('allLoansModal');
            allLoansModal.showModal();
            document.getElementById('allLoansBody').innerHTML = '<tr><td colspan="4" class="text-center">Loading...</td></tr>';
            
            // Fetch all loans
            const response = await fetch(`/api/users/${userId}/loans`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const loans = await response.json();
            
            // Populate all loans
            const allLoansBody = document.getElementById('allLoansBody');
            allLoansBody.innerHTML = '';
            
            if (loans.length === 0) {
                allLoansBody.innerHTML = '<tr><td colspan="4" class="text-center">No loan history found</td></tr>';
            } else {
                loans.forEach(loan => {
                    if (loan.items && loan.items.length > 0) {
                        loan.items.forEach(item => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${item.name || 'N/A'} (Qty: ${item.pivot?.quantity || 1})</td>
                                <td>${loan.loan_date ? new Date(loan.loan_date).toLocaleDateString() : 'N/A'}</td>
                                <td>${loan.return_date ? new Date(loan.return_date).toLocaleDateString() : 'N/A'}</td>
                                <td>${loan.status || 'N/A'}</td>
                            `;
                            allLoansBody.appendChild(row);
                        });
                    } else {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>No items</td>
                            <td>${loan.loan_date ? new Date(loan.loan_date).toLocaleDateString() : 'N/A'}</td>
                            <td>${loan.return_date ? new Date(loan.return_date).toLocaleDateString() : 'N/A'}</td>
                            <td>${loan.status || 'N/A'}</td>
                        `;
                        allLoansBody.appendChild(row);
                    }
                });
            }
            
        } catch (error) {
            console.error('Error fetching all loans:', error);
            document.getElementById('allLoansBody').innerHTML = `
                <tr>
                    <td colspan="4" class="text-center text-red-500">
                        Error loading data: ${error.message}
                    </td>
                </tr>
            `;
            showToast('Error loading loan history', 'error');
        }
    }

    // Form submission handler
    document.getElementById("editForm").addEventListener("submit", async function(e) {
        e.preventDefault();

        const id = document.getElementById("edit_user_id").value;
        const formData = new FormData(this);
        formData.append('_method', 'PUT');

        try {
            const response = await fetch(`/api/users/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();
            
            if (response.ok) {
                handleAjaxResponse({
                    toast: {
                        type: 'success',
                        message: 'User updated successfully'
                    },
                    reload: true
                });
            } else {
                handleAjaxResponse({
                    toast: {
                        type: 'error',
                        message: data.message || 'Failed to update user'
                    }
                });
            }
        } catch (error) {
            showToast('An error occurred while updating user', 'error');
            console.error(error);
        }
    });

    function resetFilter() {
        window.location.reload();
    }

    // Search and filter functionality
    const filter = document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const roleFilter = document.getElementById('roleFilter');
        const tableBody = document.getElementById('itemTableBody');
        

        async function fetchUsers() {
            const search = searchInput.value;
            const role = roleFilter.value;

            try {
                const response = await fetch(`/users?search=${search}&role=${role}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Failed to fetch users');
                }
                
                const data = await response.json();
                tableBody.innerHTML = '';
                
                if (data.data && data.data.length > 0) {
                    data.data.forEach(user => {
                        const row = `
                            <tr>
                                <td class="text-center">${user.name}</td>
                                <td class="text-center">${user.email}</td>
                                <td class="text-center">${user.roles?.name || '-'}</td>
                                <td class="text-center">${user.last_active_at || '-'}</td>
                                <td class="text-center flex justify-center">
                                    <div class="flex justify-center items-center">
                                        <i class="fa fa-trash fa-lg cursor-pointer" onclick="deleteItem(${user.id})"></i>
                                        <i class="fa fa-pen-to-square fa-lg cursor-pointer" onclick="openEditModal(${user.id}, '${user.name}', '${user.email}', ${user.roles_id})"></i>
                                        <i class="fa-regular fa-eye fa-lg cursor-pointer" onclick="showUserDetails(${user.id})"></i>
                                    </div>
                                </td>
                            </tr>`;
                        tableBody.insertAdjacentHTML('beforeend', row);
                    });
                } else {
                    tableBody.innerHTML = '<tr><td colspan="5" class="text-center">No users found</td></tr>';
                }
            } catch (error) {
                showToast('Error fetching users', 'error');
                console.error(error);
            }
        }

        searchInput.addEventListener('input', fetchUsers);
        roleFilter.addEventListener('change', fetchUsers);
        
        // Prevent form submission on Enter key in search
        document.getElementById('searchInput').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });
    });
</script>

@include('template.footer')