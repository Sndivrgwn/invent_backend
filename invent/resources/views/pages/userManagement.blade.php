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
                    <h1 class="text-2xl font-semibold">Manajemen Pengguna</h1>
                </div>

                <div class="flex flex-wrap gap-3 items-center">
                    {{-- new product --}}
                    <div class="relative inline-block text-left">
    <!-- Trigger Button -->
    <button onclick="toggleUserDropdown()"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-500 flex items-center gap-2">
        <i class="fa fa-bars !flex"></i>
        <span>Aksi Pengguna</span>
    </button>

    <!-- Dropdown Menu -->
    <div id="userDropdownMenu"
        class="hidden absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
        
        <!-- Pengguna Baru -->
        <button onclick="newProduct.showModal()"
            class="w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center gap-2 text-blue-600">
            <i class="fa-regular fa-plus"></i>
            <span>Pengguna Baru</span>
        </button>

        <!-- Hapus Semua Guest -->
        <form method="POST" id="deleteGuestForm" action="{{ route('admin.guests.destroyAll') }}">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center gap-2 text-red-600">
                <i class="fa fa-trash !flex"></i>
                Hapus Semua Guest
            </button>
        </form>
    </div>
</div>

                    <dialog id="newProduct" class="modal">
                        <div class="modal-box">
                            <!-- close button -->
                            <form method="POST" id="itemForm" action="{{ route('users.store') }}" class="w-full">
                                @csrf
                                <input type="hidden" name="_method" value="POST">
                                <button id="cancel" type="button" onclick="closeModal()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                <h1 class="font-semibold text-2xl mb-4">Pengguna baru</h1>
                                <div class="flex gap-5 justify-between text-gray-600 mb-3">
                                    <!-- Product -->
                                    <div class="w-[100%]">
                                        <h1 class="font-medium">NAMA</h1>
                                        <input type="text" name="name" placeholder="Ketik disini" class="input" style="width: 100%;" />
                                    </div>
                                </div>
                                <div class="flex gap-5 justify-between text-gray-600 mb-6">
                                    <!-- Brand -->
                                    <div class="w-[50%]">
                                        <h1 class="font-medium">EMAIL</h1>
                                        <input type="email" name="email" placeholder="Ketik disini" class="input" />
                                    </div>

                                    <!-- condition -->
                                    <div class="w-[50%]">
                                        <h1 class="font-medium">ROLE</h1>
                                        <div>
                                            <label class="select">
                                               <!-- In new user modal -->
                                            <!-- Di bagian new user modal -->
                                            <select id="condition" name="roles_id" class="select select-bordered w-full">
                                                <option value="" disabled selected>Pilih Role</option>
                                                @auth
                                                    @if(auth()->user()->roles_id == 3) <!-- Superadmin -->
                                                        <option value="1">Admin</option>
                                                        <option value="2">User</option>
                                                        <option value="4">Km</option>
                                                    @elseif(auth()->user()->roles_id == 1) <!-- Admin -->
                                                        <option value="1">Admin</option>
                                                        <option value="2">User</option>
                                                        <option value="4">Km</option>
                                                    @endif
                                                @endauth
                                            </select>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-5 justify-between text-gray-600 mb-3">
                                    <div class="w-[100%]">
                                        <h1 class="font-medium">KATA SANDI</h1>
                                        <input type="password" name="password" placeholder="masukkan password" class="input" style="width: 100%;" />
                                    </div>
                                </div>
                                <div class="flex gap-5 justify-between text-gray-600 mb-3">
                                    <div class="w-[100%] mb-4">
                                        <h1 class="font-medium">KONFIRMASI KATA SANDI</h1>
                                        <input type="password" name="password_confirmation" placeholder="konfirmasi password" class="input" style="width: 100%;" />
                                    </div>
                                </div>
                                {{-- <div class="flex gap-5  justify-between text-gray-600 mb-3">
                                </div> --}}
                                <div class="flex gap-5 justify-between text-gray-600">

                                    <!-- button -->
                                    <div class="w-full flex justify-end items-end gap-4">
                                        <button id="cancelButton" type="button" onclick="closeModal()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Batal</button>
                                        <button type="submit" class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Kirim</button>
                                    </div>
                            </form>
                        </div>
                    </dialog>
                </div>

            </div>

            <div class="list bg-base-100 rounded-box shadow-md mb-5">

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
                            <input type="text" id="searchInput" name="searchInput" class="block w-full p-2 ps-10 text-sm border border-gray-400 rounded-lg" placeholder="Cari...">
                        </form>

                    </div>

                    <!-- filter -->
                    <button class="btn flex justify-center items-center bg-transparent me-2 mt-3 md:mt-0" onclick="filterUsers.showModal()">Filter Role <i class="fa fa-filter" style="display: flex; justify-content: center; align-items: center;"></i></button>
                    <button class="btn flex justify-center items-center btn-secondary mt-3 md:mt-0" onclick="resetFilter()">Hapus filter</button>
                    <dialog id="filterUsers" class="modal">
                        <div class="modal-box">
                            <!-- close button -->
                            <form method="dialog">
                                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                            </form>

                            <form id="filterForm">
                                <!-- ROLE -->
                                <select id="roleFilter" class="select select-bordered">
                                    <option value="">Semua Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                    <option value="km">Km</option>

                                </select>


                                <!-- STATUS -->
                            </form>
                            <!-- Apply Button -->
                        </div>
                    </dialog>




                </div>
                <!-- table -->
                @php
function sortLinkUser($field, $currentSortBy, $currentSortDir) {
    $newDir = ($currentSortBy === $field && $currentSortDir === 'asc') ? 'desc' : 'asc';
    return request()->fullUrlWithQuery([
        'sortBy' => $field,
        'sortDir' => $newDir,
    ]);
}
@endphp
                <div id="itemTableContainer" class="overflow-x-auto px-2">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center font-semibold">
    <a href="{{ sortLinkUser('name', $sortBy, $sortDir) }}">
        NAMA {!! $sortBy === 'name' ? ($sortDir === 'asc' ? '&uarr;' : '&darr;') : '' !!}
    </a>
</th>

<th class="text-center font-semibold">
    <a href="{{ sortLinkUser('email', $sortBy, $sortDir) }}">
        EMAIL {!! $sortBy === 'email' ? ($sortDir === 'asc' ? '&uarr;' : '&darr;') : '' !!}
    </a>
</th>

<th class="text-center font-semibold">ROLE</th>

<th class="text-center font-semibold">
    <a href="{{ sortLinkUser('last_active_at', $sortBy, $sortDir) }}">
        TERAKHIR AKTIF {!! $sortBy === 'last_active_at' ? ($sortDir === 'asc' ? '&uarr;' : '&darr;') : '' !!}
    </a>
</th>

<th class="text-center font-semibold">TINDAKAN</th>

                            </tr>
                        </thead>
                        <tbody id="itemTableBody">
    @foreach ($users as $usr)
        @if(auth()->user()->roles_id == 3 || $usr->roles_id != 3)
        <tr>
            <td class="text-center">{{ $usr->name }}</td>
            <td class="text-center">{{ $usr->email }}</td>
            <td class="text-center">{{ $usr->roles->name }}</td>
            <td class="text-center">{{ $usr->last_active_at }}</td>
            <td class="text-center">
                <div class="flex justify-center items-center gap-2 min-w-[70px]">
                    @auth
                        @if(auth()->user()->roles_id == 3) <!-- Superadmin -->
                            @if($usr->id != auth()->id()) <!-- Tidak bisa menghapus/mengedit dirinya sendiri -->
                                <i class="fa fa-trash fa-lg cursor-pointer !leading-none" onclick="deleteItem({{ $usr->id }})"></i>
                                <i class="fa fa-pen-to-square fa-lg cursor-pointer !leading-none" onclick="openEditModal({{ $usr->id }}, '{{ $usr->name }}', '{{ $usr->email }}', {{ $usr->roles_id }})"></i>
                            @endif
                        @elseif(auth()->user()->roles_id == 1) <!-- Admin -->
                            @if($usr->roles_id == 2) <!-- Hanya bisa mengedit/menghapus user biasa -->
                                <i class="fa fa-trash fa-lg cursor-pointer !leading-none" onclick="deleteItem({{ $usr->id }})"></i>
                                <i class="fa fa-pen-to-square fa-lg cursor-pointer !leading-none" onclick="openEditModal({{ $usr->id }}, '{{ $usr->name }}', '{{ $usr->email }}', {{ $usr->roles_id }})"></i>
                            @endif
                            @if($usr->roles_id == 4) <!-- Hanya bisa mengedit/menghapus user biasa -->
                                <i class="fa fa-trash fa-lg cursor-pointer !leading-none" onclick="deleteItem({{ $usr->id }})"></i>
                                <i class="fa fa-pen-to-square fa-lg cursor-pointer !leading-none" onclick="openEditModal({{ $usr->id }}, '{{ $usr->name }}', '{{ $usr->email }}', {{ $usr->roles_id }})"></i>
                            @endif
                        @endif
                        <i class="fa-regular fa-eye fa-lg cursor-pointer mb-2" onclick="showUserDetails({{ $usr->id }})"></i>
                    @endauth
                </div>
            </td>
        </tr>
        @endif
    @endforeach
</tbody>
                    </table>
                </div>

                <dialog id="allLoansModal" class="modal">
    <div class="modal-box w-11/12 max-w-5xl">
        <h3 class="font-bold text-lg">Semua riwayat pinjaman</h3>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Tanggal pinjaman</th>
                        <th>Tanggal kembali</th>
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
                <button class="btn">Tutup</button>
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
                            <h1 class="text-xl font-bold text-center mb-4">Hapus item?</h1>
                            <p class="text-center text-gray-600">Apakah Anda yakin ingin menghapus User ini? Aksi ini tidak dapat dibatalkan</p>
                            <!-- Tombol -->
                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" onclick="closeDeleteDialog()" class="bg-gray-300 text-gray-800 rounded-lg px-4 py-2 hover:bg-gray-400 cursor-pointer">Batal</button>
                                <button type="button" onclick="confirmDelete()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-red-600 cursor-pointer">Ya, Hapus</button>
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
                                    <h1 class="font-medium">NAMA</h1>
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
                                   <!-- Di bagian edit modal -->
<select id="edit_role" name="roles_id" class="select select-bordered w-full">
    <option value="" disabled selected>Pilih Role</option>
    @auth
        @if(auth()->user()->roles_id == 3) <!-- Superadmin -->
            <option value="3">Superadmin</option>
            <option value="1">Admin</option>
            <option value="4">Km</option>
            <option value="2">User</option>
        @elseif(auth()->user()->roles_id == 1) <!-- Admin -->
            <option value="1">Admin</option>
            <option value="2">User</option>
            <option value="4">Km</option>
        @endif
    @endauth
</select>
                                </div>
                            </div>

                            <!-- Add these password fields -->
                            <div class="flex gap-5 justify-between text-gray-600 mb-3">
                                <div class="w-[100%]">
                                    <h1 class="font-medium">KATA SANDI BARU </h1>
                                    <input type="password" name="password" class="input" placeholder="Leave blank to keep current" style="width: 100%;">
                                </div>
                            </div>
                            <div class="flex gap-5 justify-between text-gray-600 mb-3">
                                <div class="w-[100%]">
                                    <h1 class="font-medium">KONFIRMASI KATA SANDI</h1>
                                    <input type="password" name="password_confirmation" class="input" placeholder="Confirm new password" style="width: 100%;">
                                </div>
                            </div>
                            {{-- <div class="flex gap-5 justify-between text-gray-600 mb-3">
                            </div> --}}

                            <div class="flex justify-end gap-4 mt-4">
                                <button type="button" onclick="closeEditModal()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Batal</button>
                                <button type="submit" class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Simpan</button>
                            </div>
                        </form>
                    </div>
                </dialog>

                <dialog id="viewProduct" class="modal">
                    <div class="modal-box">
                        <form method="dialog" id="viewForm">
                            <!-- Tombol close -->
                            <button type="button" onclick="document.getElementById('viewProduct').close()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>

                            <h1 class="font-semibold text-2xl mb-4">Detail Pengguna</h1>

                            <div class="flex gap-5 justify-between text-gray-600">
                                <div class="w-[50%]">
                                    <h1 class="font-medium">Nama</h1>
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
                                    <h1 class="font-medium">Terakhir Aktif</h1>
                                    <p id="viewUserLastActive"></p>
                                </div>
                            </div>

                            <div class="flex gap-5 justify-between text-gray-600 mt-3">
                                <div class="w-[50%]">
                                    <h1 class="font-medium">Total pinjaman</h1>
                                    <p id="viewUserTotalLoans"></p>
                                </div>
                                <div class="w-[50%]">
                                    <h1 class="font-medium">Total pengembalian</h1>
                                    <p id="viewUserTotalReturns"></p>
                                </div>
                            </div>

                            <div class="mt-6">
                                <h2 class="font-semibold text-xl mb-2">Riwayat pinjaman</h2>
                                <div class="overflow-x-auto">
                                    <table class="table table-zebra">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Tanggal pinjaman</th>
                                                <th>Tenggat</th>
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
                                <button type="button" onclick="document.getElementById('viewProduct').close()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Tutup</button>
                            </div>
                        </form>
                    </div>
                </dialog>
                {{-- tampilan preview --}}



            </div>
        </div>
    </div>

    <script>
    const currentUserRoleId = {{ auth()->user()->roles_id }};
    const currentUserId = {{ auth()->user()->id }};
</script>


<script>
    // Global variables
    let deleteTargetId = null;

    

    // User management functions
    async function deleteItem(id) {
    try {
        // Cek role user yang akan dihapus
        const response = await fetch(`/api/users/${id}`);
        const data = await response.json();
        
        @if(auth()->user()->roles_id == 1) // Jika admin
            if (data.roles_id == 3) { // Coba hapus superadmin
                showToast('Anda tidak berwenang untuk menghapus superadmin', 'error');
                return;
            }
        @endif
        
        deleteTargetId = id;
        document.getElementById("confirmDeleteDialog").showModal();
    } catch (error) {
        showToast('Kesalahan Memeriksa Peran Pengguna', 'error');
        console.error(error);
    }
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
                    message: 'Pengguna berhasil dihapus'
                },
                reload: true
            });
        } else {
            handleAjaxResponse({
                toast: {
                    type: 'error',
                    message: data.message || 'Gagal Menghapus Pengguna'
                }
            });
        }
    } catch (error) {
        showToast('Terjadi kesalahan saat menghapus pengguna', 'error');
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
                throw new Error('Data pengguna tidak valid diterima');
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
                loanHistoryBody.innerHTML = '<tr><td colspan="4" class="text-center">Tidak ada riwayat pinjaman</td></tr>';
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
            showToast('Kesalahan Memuat Detail Pengguna', 'error');
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
            showToast('Kesalahan memuat riwayat pinjaman', 'error');
        }
    }

    // Form submission handler
    document.getElementById("editForm").addEventListener("submit", async function(e) {
        e.preventDefault();

        try {
        const id = document.getElementById("edit_user_id").value;
        const formData = new FormData(this);
        formData.append('_method', 'PUT');

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
                        message: 'Pengguna berhasil diperbarui'
                    },
                    reload: true
                });
            } else {
                handleAjaxResponse({
                    toast: {
                        type: 'error',
                        message: data.message || 'Gagal memperbarui pengguna'
                    }
                });
            }
        } catch (error) {
            showToast('Terjadi kesalahan saat memperbarui pengguna', 'error');
            console.error(error);
        }
    });

    function resetFilter() {
        window.location.href = '/users'; // Reset to the base URL
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

        if (!response.ok) throw new Error('Failed to fetch users');

        const data = await response.json();
        tableBody.innerHTML = '';

        if (data.data && data.data.length > 0) {
            data.data.forEach(user => {
                // Superadmin (3) boleh lihat semua
                // Admin (1) boleh lihat semua (tapi nanti diatur hak akses tombol)
                // Cek apakah user login adalah superadmin atau bukan
                const isSelf = user.id === currentUserId;
                const isSuperadmin = currentUserRoleId === 3;
                const isAdmin = currentUserRoleId === 1;
                let mtClass = '';
                if (roleFilter.value === 'user') {
                    mtClass = 'mt-6';
                } else if (roleFilter.value === 'admin') {
                    mtClass = 'mt-6'; // atau kosongkan: ''
                } else {
                    mtClass = 'mt-6';
                }

                // Kalau yang login bukan superadmin dan user target adalah superadmin, sembunyikan barisnya
                if (!isSuperadmin && user.roles_id === 3) return;

                // Default action hanya tombol detail
                let actions = `<i class="fa-regular fa-eye fa-lg cursor-pointer" onclick="showUserDetails(${user.id})"></i>`;

                // Tambahkan tombol edit/hapus jika role login mengizinkan
                if (isSuperadmin && !isSelf) {
                    actions = `
                        <i class="fa fa-trash fa-lg cursor-pointer ${mtClass}" onclick="deleteItem(${user.id})"></i>
                        <i class="fa fa-pen-to-square fa-lg cursor-pointer ${mtClass}" onclick="openEditModal(${user.id}, '${user.name}', '${user.email}', ${user.roles_id})"></i>
                        ${actions}
                    `;
                }

                if (isAdmin && user.roles_id === 2) {
                    actions = `
                        <i class="fa fa-trash fa-lg cursor-pointer ${mtClass}" onclick="deleteItem(${user.id})"></i>
                        <i class="fa fa-pen-to-square fa-lg cursor-pointer ${mtClass}" onclick="openEditModal(${user.id}, '${user.name}', '${user.email}', ${user.roles_id})"></i>
                        ${actions}
                    `;
                }
                
                const row = `
                    <tr>
                        <td class="text-center">${user.name}</td>
                        <td class="text-center">${user.email}</td>
                        <td class="text-center">${user.roles?.name || '-'}</td>
                        <td class="text-center">${user.last_active_at || '-'}</td>
                        <td class="text-center">
                            <div class="flex justify-center items-center gap-2 min-w-[70px]">
                                ${actions}
                            </div>
                        </td>
                    </tr>`;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
        } else {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center">No users found</td></tr>';
        }
    } catch (error) {
        showToast('Kesalahan mengambil pengguna', 'error');
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

{{-- confirm delete guest --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('deleteGuestForm').addEventListener('submit', function (e) {
    e.preventDefault();
    Swal.fire({
        title: 'Yakin?',
        text: "Semua akun tamu akan dihapus",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Ya, hapus semua!'
    }).then((result) => {
        if (result.isConfirmed) {
            e.target.submit();
        }
    });
});

function closeModal() {
    document.getElementById('newProduct').close();
}
// dropdown aksi 
function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdownMenu');
    dropdown.classList.toggle('hidden');
    dropdown.classList.toggle('block');
}

// Optional: auto close dropdown jika klik di luar area dropdown
window.addEventListener('click', function(e) {
    const btn = document.querySelector('[onclick="toggleUserDropdown()"]');
    const menu = document.getElementById('userDropdownMenu');
    if (!btn.contains(e.target) && !menu.contains(e.target)) {
        menu.classList.add('hidden');
        menu.classList.remove('block');
    }
});

</script>


@include('template.footer')