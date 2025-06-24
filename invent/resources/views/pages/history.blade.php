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

        <!-- Header -->
        <div class="navbar my-6">
            <div class="flex-1">
                <h1 class="text-2xl font-semibold py-4">History</h1>
            </div>
            <div class="flex-none">
                <a href="{{ route('loans.exportHistory') }}" class="bg-white rounded-lg py-2 px-4 mx-5 hover:bg-blue-400 cursor-pointer flex items-center gap-2">
                    <i class="fa fa-download" style="display: flex; justify-content: center; align-items: center;"></i> Export Report
                </a>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div class="list bg-base-100 rounded-box shadow-md">
            <div class="flex flex-col overflow-x-auto sm:flex-row sm:place-content-between">
                <div class="p-2 sm:p-4 pb-0 sm:pb-2 flex items-center gap-2 w-full">
                    <!-- Search -->
                    <form method="GET" action="{{ route('history') }}" class="relative w-full sm:w-auto">
                        <div class="absolute inset-y-2 start-0 sm:mb-1 flex items-center justify-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" class="block w-full p-2 ps-10 text-sm border border-gray-400 rounded-lg" placeholder="Search...">
                    </form>

                    <!-- Filter Button -->
                    <button class="btn bg-transparent btn-sm sm:btn-md items-center justify-center" onclick="filterProduct.showModal()">
                        <span class="hidden sm:inline">Filter</span> <i class="fa fa-filter sm:ml-2" style="display: flex; justify-content: center; align-items: center;"></i>
                    </button>
                </div>

                <!-- Date Filter Section -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4 p-2 sm:p-4 w-full sm:w-auto">
                    <!-- Date Range Filter -->
                    <form method="GET" action="{{ route('history') }}" class="flex flex-col sm:flex-row gap-2 sm:gap-3 w-full">
                        <div class="flex flex-row gap-2 sm:gap-3 flex-grow w-full">
                            <!-- Changed to flex-row for all screens -->
                            <div class="flex flex-col w-full sm:w-auto">
                                <label for="start_date" class="text-xs sm:text-sm font-medium mb-1">From</label>
                                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') ?? \Carbon\Carbon::today()->format('Y-m-d') }}" class="input input-bordered input-sm sm:input-md w-full" placeholder="Select start date" />
                            </div>
                            <div class="flex flex-col w-full sm:w-auto">
                                <label for="end_date" class="text-xs sm:text-sm font-medium mb-1">To</label>
                                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') ?? \Carbon\Carbon::today()->format('Y-m-d') }}" class="input input-bordered input-sm sm:input-md w-full" placeholder="Select end date" />
                            </div>
                        </div>
                        <div class="flex gap-2 self-end mt-2 sm:mt-0">
                            <!-- Added mt-2 for mobile spacing -->
                            <button type="submit" class="btn btn-primary btn-sm sm:btn-md">Apply</button>
                            <a href="{{ route('history') }}" class="btn btn-secondary btn-sm sm:btn-md">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Filter Modal -->
            <dialog id="filterProduct" class="modal">
                <div class="modal-box">
                    <form method="GET" id="filterForm" onsubmit="event.preventDefault(); applyFilter();">
                        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" type="button" onclick="filterProduct.close()">✕</button>

                        @php
                        $allItems = collect($loans->items())->flatMap->items;
                        @endphp

                        <!-- Brand -->
                        <div class="mb-4">
                            <h1 class="text-lg font-semibold mb-2">Brand</h1>
                            <select name="brand" class="select select-bordered w-full max-w-xs">
                                <option value="" selected>All Brands</option>
                                @foreach($allItems->pluck('brand')->filter()->unique() as $brand)
                                <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <h1 class="text-lg font-semibold mb-2">Category</h1>
                            <select name="category" class="select select-bordered w-full max-w-xs">
                                <option value="" selected>All Categories</option>
                                @foreach($allItems->pluck('category.name')->filter()->unique() as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Type -->
                        <div class="mb-4">
                            <h1 class="text-lg font-semibold mb-2">Type</h1>
                            <select name="type" class="select select-bordered w-full max-w-xs">
                                <option value="" selected>All Types</option>
                                @foreach($allItems->pluck('type')->filter()->unique() as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Location -->
                        <div class="mb-4">
                            <h1 class="text-lg font-semibold mb-2">Location</h1>
                            <select name="location" class="select select-bordered w-full max-w-xs">
                                <option value="" selected>All Locations</option>
                                @foreach($locations->pluck('description')->unique() as $loc)
                                <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Condition -->
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
                                <input class="btn" type="radio" name="status" value="borrowed" aria-label="dipinjam" />
                                <input class="btn" type="radio" name="status" value="returned" aria-label="returned" />
                            </div>
                        </div>

                        <button type="button" class="btn btn-primary mt-4" onclick="applyFilter()">Apply</button>
                    </form>
                </div>
            </dialog>

            <!-- Table Section -->
            <div class="overflow-x-auto px-2">
                <table class="table w-full">
                    <thead class="text-gray-500 text-sm font-semibold border-b">
                        <tr>
                            <th>DATE</th>
                            <th>RETURNED AT</th>
                            <th>DUE DATE</th>
                            <th>LOAN CODE</th>
                            <th>BORROWER NAME</th>
                            <th>SERIAL NUMBER</th>
                            <th>PRODUCT</th>
                            <th>STATUS</th>
                            <th class="text-center">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody id="itemTableBody" class="text-sm">
                        @forelse($loans as $loan)
                        @if ($loan->items->isEmpty())
                        <tr class="hover">
                            <td>{{ $loan->loan_date }}</td>
                            <td>{{ $loan->return?->return_date }}</td>
                            <td>{{ $loan->return_date }}</td>
                            <td>{{ $loan->code_loans }}</td>
                            <td>{{ $loan->loaner_name }}</td>
                            <td colspan="2" class="italic text-gray-400">Tidak ada item</td>
                            <td><span class="badge badge-warning text-xs">{{ $loan->status }}</span></td>
                            <td class="whitespace-nowrap">
                                <div class="flex justify-center items-center gap-2">
                                    @can('adminFunction')
                                    <i class="fa fa-trash fa-lg cursor-pointer !leading-none mt-1" onclick="deleteItem({{ $loan->id }})"></i>
                                    @endcan
                                    <i class="fa-solid fa-file-pdf fa-lg text-red-600 cursor-pointer !leading-none" title="Lihat PDF" onclick="window.open('{{ route('loan.print.pdf', ['id' => \Illuminate\Support\Facades\Crypt::encryptString($loan->id)]) }}', '_blank')"></i>
                                    <i class="fa-regular fa-eye fa-lg cursor-pointer" onclick="showLoanDetails({{ $loan->id }})"></i>
                                </div>
                            </td>
                        </tr>
                        @else
                        @foreach ($loan->items as $index => $item)
                        <tr class="hover">
                            @if ($index === 0)
                            <td rowspan="{{ count($loan->items) }}">{{ $loan->loan_date }}</td>
                            @endif
                            <td>{{ $loan->return?->return_date }}</td>
                            <td>{{ $loan->return_date }}</td>
                            <td>{{ $loan->code_loans }}</td>
                            <td>{{ $loan->loaner_name }}</td>
                            <td class="font-semibold">{{ $item->code }}</td>
                            <td>{{ $item->name }}</td>
                            <td><span class="badge badge-warning text-xs">{{ $loan->status }}</span></td>
                            @if ($index === 0)
                            <td class="whitespace-nowrap" rowspan="{{ count($loan->items) }}">
                                <div class="flex justify-center items-center gap-2">
                                    @can('adminFunction')
                                    <i class="fa fa-trash fa-lg cursor-pointer !leading-none mt-1" onclick="deleteItem({{ $loan->id }})"></i>
                                    @endcan
                                    <i class="fa-solid fa-file-pdf fa-lg text-red-600 cursor-pointer !leading-none" title="Lihat PDF" onclick="window.open('{{ route('loan.print.pdf', ['id' => \Illuminate\Support\Facades\Crypt::encryptString($loan->id)]) }}', '_blank')"></i>
                                    <i class="fa-regular fa-eye fa-lg cursor-pointer" onclick="showLoanDetails({{ $loan->id }})"></i>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                        @endif

                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500">No history found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- tampilan delete --}}
            <dialog id="itemDetailsDialog" class="modal">
                <div class="modal-box w-11/12 max-w-5xl">
                    <button onclick="document.getElementById('itemDetailsDialog').close()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                    <div id="itemDetailsContent"></div>
                </div>
            </dialog>
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

            {{-- tampilan preview --}}
            <dialog id="viewProduct" class="modal">
                <div class="modal-box w-11/12 max-w-5xl">
                    <form method="dialog" id="viewForm">
                        <!-- Image will be dynamically updated -->

                        <!-- Loan details will be inserted here by JavaScript -->

                        <!-- Tombol close -->
                        <button type="button" onclick="document.getElementById('viewProduct').close()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>


                    </form>
                </div>
            </dialog>
            <!-- Pagination -->
            <div class="flex justify-end my-4">
                {{ $loans->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>


<script>
    function resetFilter(name) {
        document.querySelectorAll(`input[name="${name}"]`).forEach(r => r.checked = false);
    }

</script>

<script>
    function resetFilter(name) {
        document.querySelectorAll(`input[name="${name}"]`).forEach(r => r.checked = false);
    }



    async function showLoanDetails(loanId) {
        try {
            const response = await fetch(`/api/history/${loanId}`);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error("Response isn't JSON");
            }

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message || 'Failed to load loan');
            }

            const loan = data.data;
            const modal = document.getElementById('viewProduct');

            // Build modal content
            let modalContent = `
                <button type="button" onclick="document.getElementById('viewProduct').close()"
                    class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                <h1 class="font-semibold text-2xl mb-4">Loan Details</h1>
                <div class="mb-6">
                    <h2 class="font-semibold text-lg mb-2">Loan Information</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-600">Loan Code:</p>
                            <p>${loan.code_loans || 'N/A'}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Admin:</p>
                            <p>${loan.user?.name || 'N/A'}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Borrower:</p>
                            <p>${loan.loaner_name || 'N/A'}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Loan Date:</p>
                            <p>${loan.loan_date || 'N/A'}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Due Date:</p>
                            <p>${loan.return_date || 'Not returned yet'}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Returned At:</p>
                            <p>${loan.return?.return_date || 'Not returned yet'}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Description:</p>
                            <p>${loan.description || 'N/A'}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Notes:</p>
                            <p>${loan.return?.notes || 'N/A'}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Status:</p>
                            <p class="${loan.status === 'returned' ? 'text-green-500' : 'text-yellow-500'}">
                                ${loan.status || 'N/A'}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mb-6">
                    <h2 class="font-semibold text-lg mb-2">Items (${loan.items?.length || 0})</h2>
            `;

            // Add items if they exist
            if (loan.items && loan.items.length > 0) {
                if (loan.items.length > 1) {
                    modalContent += `
                        <div class="overflow-x-auto">
                            <table class="table w-full">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Serial Number</th>
                                        <th>Category</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;

                    loan.items.forEach((item, index) => {
                        modalContent += `
                            <tr>
                                <td>${item.name || 'N/A'}</td>
                                <td>${item.code || 'N/A'}</td>
                                <td>${item.category?.name || 'N/A'}</td>
                                <td>
                                    <button onclick="showItemDetails(${index}, ${loanId})" 
                                        class="btn btn-sm btn-ghost">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        `;
                    });

                    modalContent += `
                                </tbody>
                            </table>
                        </div>
                        <div id="itemDetailsContainer" class="mt-4"></div>
                    `;
                } else {
                    const item = loan.items[0];
                    modalContent += `
                        <div class="border p-4 rounded-lg">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-600">Product Name:</p>
                                    <p>${item.name || 'N/A'}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Serial Number:</p>
                                    <p>${item.code || 'N/A'}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Category:</p>
                                    <p>${item.category?.name || 'N/A'}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Location:</p>
                                    <p>${item.location?.name || 'N/A'}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Brand:</p>
                                    <p>${item.brand || 'N/A'}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Type:</p>
                                    <p>${item.type || 'N/A'}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Condition:</p>
                                    <p>${item.condition || 'N/A'}</p>
                                </div>
                            </div>
                        </div>
                    `;
                }
            } else {
                modalContent += `<p class="text-gray-500">No items found for this loan</p>`;
            }

            modalContent += `</div>`; // Close items section

            modalContent += `
                <div class="w-full flex justify-end items-end gap-4 mt-4">
                    <button type="button" onclick="document.getElementById('viewProduct').close()"
                        class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Close</button>
                </div>
            `;

            modal.querySelector('form').innerHTML = modalContent;
            modal.showModal();

        } catch (error) {
            console.error('Error:', error);
            showToast('Failed to load loan details', 'error');
        }
    }

    async function showItemDetails(itemIndex, loanId) {
        try {
            const response = await fetch(`/api/history/${loanId}`);
            const data = await response.json();
            const item = data.data.items[itemIndex];

            const dialog = document.getElementById('itemDetailsDialog');
            const content = document.getElementById('itemDetailsContent');

            content.innerHTML = `
                <div class="flex gap-6">
                    <div class="w-2/3">
                        <h2 class="text-2xl font-bold mb-4">${item.name}</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600">Serial Number:</p>
                                <p class="font-semibold">${item.code || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Category:</p>
                                <p>${item.category?.name || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Location:</p>
                                <p>${item.location?.name || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Brand:</p>
                                <p>${item.brand || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Type:</p>
                                <p>${item.type || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Condition:</p>
                                <p>${item.condition || 'N/A'}</p>
                            </div>
                        </div>
                        ${item.description ? `
                            <div class="mt-4">
                                <p class="text-gray-600">Description:</p>
                                <p class="mt-1">${item.description}</p>
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;

            dialog.showModal();

        } catch (error) {
            console.error('Error showing item details:', error);
            showToast('Failed to load item details', 'error');
        }
    }

    function applyFilter() {
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams();

        for (const [key, value] of formData.entries()) {
            params.append(key, value);
        }

        fetch(`/history/filter?${params.toString()}`)
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(data => {
                const tbody = document.getElementById("itemTableBody");
                tbody.innerHTML = "";

                if (data.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="9" class="text-center text-gray-500">No items found</td>
                        </tr>`;
                    return;
                }

                data.forEach(loan => {
                    if (loan.items.length === 0) {
        const row = document.createElement('tr');
        row.classList.add('hover');
        row.innerHTML = `
            <td>${loan.loan_date}</td>
            <td>${loan.return?.return_date || '-'}</td>
            <td>${loan.return_date}</td>
            <td>${loan.code_loans}</td>
            <td>${loan.loaner_name}</td>
            <td colspan="2" class="italic text-gray-400">Tidak ada item</td>
            <td><span class="badge badge-warning text-xs">${loan.status}</span></td>
            <td class="text-center whitespace-nowrap">
                <div class="flex justify-center items-center gap-2">
                    ${loan.can_delete ? `
                    <i class="fa fa-trash fa-lg cursor-pointer !leading-none mt-1" onclick="deleteItem(${loan.id})"></i>
                    ` : ''}
                    <i class="fa-solid fa-file-pdf fa-lg text-red-600 cursor-pointer !leading-none"
                    title="Lihat PDF"
                    onclick="window.open('/loan/${loan.encrypted_id}/pdf', '_blank')"></i>
                    <i class="fa-regular fa-eye fa-lg cursor-pointer" onclick="showLoanDetails(${loan.id})"></i> 
                </div>
            </td>
        `;
        tbody.appendChild(row);
    } else {
        loan.items.forEach((item, index) => {
            const row = document.createElement('tr');
            row.classList.add('hover');

            let html = "";

            if (index === 0) {
                html += `<td rowspan="${loan.items.length}">${loan.loan_date}</td>`;
            }

            html += `
                <td>${loan.return?.return_date || '-'}</td>
                <td>${loan.return_date}</td>
                <td>${loan.code_loans}</td>
                <td>${loan.loaner_name}</td>
                <td class="font-semibold">${item.code}</td>
                <td>${item.name}</td>
                <td><span class="badge badge-warning text-xs">${loan.status}</span></td>
            `;

            if (index === 0) {
                html += `
                    <td class="text-center whitespace-nowrap" rowspan="${loan.items.length}">
                        <div class="flex justify-center items-center gap-2">
                            ${loan.can_delete ? `
                            <i class="fa fa-trash fa-lg cursor-pointer !leading-none mt-1" onclick="deleteItem(${loan.id})"></i>
                            ` : ''}
                            <i class="fa-solid fa-file-pdf fa-lg text-red-600 cursor-pointer !leading-none"
                            title="Lihat PDF"
                            onclick="window.open('/loan/${loan.encrypted_id}/pdf', '_blank')"></i>
                            <i class="fa-regular fa-eye fa-lg cursor-pointer" onclick="showLoanDetails(${loan.id})"></i> 
                        </div>
                    </td>
                `;
            }

            row.innerHTML = html;
            tbody.appendChild(row);
        });
    }
                });
            })
            .catch(error => {
                console.error("Error fetching filtered data:", error);
                showToast('Error loading filtered data', 'error');
            });
    }

    // delete
    let deleteTargetId = null;

    async function deleteItem(id) {
        deleteTargetId = id;
        document.getElementById("confirmDeleteDialog").showModal();
    }

    async function confirmDelete() {
        if (!deleteTargetId) return;

        const res = await fetch(`/api/history/${deleteTargetId}`, {
            method: 'DELETE'
            , headers: {
                'Accept': 'application/json'
                , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        if (res.ok) {
            showToast('Item deleted successfully', 'success');
            window.location.reload();
        } else {
            const data = await res.json();
            showToast('Failed to delete item', 'error');
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

@stack('scripts')
@include('template.footer')
