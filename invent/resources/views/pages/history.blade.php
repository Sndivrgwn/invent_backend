@include('template.head')


<div class="flex h-screen bg-gradient-to-b from-blue-100 to-white">
    <!-- Sidebar -->
    <div>@include('template.sidebar')</div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto px-6">
        <!-- Navbar -->
        <div>@include('template.navbar')</div>

        <!-- Header -->
        <div class="navbar my-6">
            <div class="flex-1">
                <h1 class="text-2xl font-semibold py-4">History</h1>
            </div>
            <div class="flex-none">
                <a href="{{ route('loans.exportHistory') }}" class="bg-white rounded-lg py-2 px-4 mx-5 hover:bg-blue-400 cursor-pointer flex items-center gap-2">
                    <i class="fa fa-download"></i> Export Report
                </a>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div class="list bg-base-100 rounded-box shadow-md">
            <div class="flex place-content-between">
                <div class="p-4 pb-2 flex flex-wrap gap-3">
                    <!-- Search -->
                    <form method="GET" action="{{ route('history') }}" class="relative w-full md:w-auto">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" class="block w-full p-2 ps-10 text-sm border border-gray-400 rounded-lg" placeholder="Search...">
                    </form>
    
                    <!-- Filter Button -->
                    <button class="btn bg-transparent" onclick="filterProduct.showModal()">All Categories <i class="fa fa-filter ml-2"></i></button>
    
                </div>
                <div class="calender px-5">
                    <!-- Date Filter Section -->
                    <!-- Filter Date -->
                    <form method="GET" action="{{ route('history') }}" class="flex flex-wrap items-center gap-3 mt-4">
                        <div>
                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="input input-bordered w-full max-w-xs" />
                        </div>
                        <div class="self-end">
                            <button type="submit" class="btn btn-primary">Filter Date</button>
                            <a href="{{ route('history') }}" class="btn btn-primary">Reset Filter</a>
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
                            <div class="flex flex-wrap gap-1">
                                <button type="button" class="btn btn-square" onclick="resetFilter('brand')">×</button>
                                @foreach($allItems->pluck('brand')->filter()->unique() as $brand)
                                <input class="btn" type="radio" name="brand" value="{{ $brand }}" aria-label="{{ $brand }}" {{ request('brand') == $brand ? 'checked' : '' }} />
                                @endforeach
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <h1 class="text-lg font-semibold mb-2">Category</h1>
                            <div class="flex flex-wrap gap-1">
                                <button type="button" class="btn btn-square" onclick="resetFilter('category')">×</button>
                                @foreach($allItems->pluck('category.name')->filter()->unique() as $category)
                                <input class="btn" type="radio" name="category" value="{{ $category }}" aria-label="{{ $category }}" {{ request('category') == $category ? 'checked' : '' }} />
                                @endforeach
                            </div>
                        </div>

                        <!-- Type -->
                        <div class="mb-4">
                            <h1 class="text-lg font-semibold mb-2">Type</h1>
                            <div class="flex flex-wrap gap-1">
                                <button type="button" class="btn btn-square" onclick="resetFilter('type')">×</button>
                                @foreach($allItems->pluck('type')->filter()->unique() as $type)
                                <input class="btn" type="radio" name="type" value="{{ $type }}" aria-label="{{ $type }}" {{ request('type') == $type ? 'checked' : '' }} />
                                @endforeach
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="mb-4">
                            <h1 class="text-lg font-semibold mb-2">Location</h1>
                            <div class="flex flex-wrap gap-1">
                                <button type="button" class="btn btn-square" onclick="resetFilter('location')">×</button>
                                @foreach($locations->pluck('description')->unique() as $loc)
                                <input class="btn" type="radio" name="location" value="{{ $loc }}" aria-label="{{ $loc }}" {{ request('location') == $loc ? 'checked' : '' }} />
                                @endforeach
                            </div>
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

                        <!-- Date Filter -->

                        <button type="button" class="btn btn-primary mt-4" onclick="applyFilter()">Apply</button>

                    </form>
                </div>
            </dialog>

           


            <!-- Table Section -->
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="text-gray-500 text-sm font-semibold border-b">
                        <tr>
                            <th>DATE</th>
                            <th>CODE</th>
                            <th>NAME</th>
                            <th>SERIAL NUMBER</th>
                            <th>PRODUCT</th>
                            <th>STATUS</th>
                            <th>RETURN DATE</th>
                            <th class="text-center">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody id="itemTableBody" class="text-sm">
                        @forelse($loans as $loan)
                        @foreach ($loan->items as $index => $item)
                        <tr class="hover">
                            @if ($index === 0)
                            <td rowspan="{{ count($loan->items) }}">{{ $loan->loan_date }}</td>
                            @endif
                            <td>{{ $loan->code_loans }}</td>
                            <td>{{ $loan->loaner_name }}</td>
                            <td class="font-semibold">{{ $item->code }}</td>
                            <td>{{ $item->name }}</td>
                            <td><span class="badge badge-warning text-xs">{{ $loan->status }}</span></td>
                            <td>{{ $loan->return_date }}</td>
                            @if ($index === 0)
                            <td class="text-center whitespace-nowrap" rowspan="{{ count($loan->items) }}">
                                <div class="flex justify-center items-center">
                                    <i class="fa fa-trash fa-lg cursor-pointer !leading-none" onclick="deleteItem({{ $item->id }})"></i>
                                    <i class="fa fa-pen-to-square fa-lg cursor-pointer !leading-none" onclick="document.getElementById('editProduct').showModal()"></i>
                                    <i class="fa-regular fa-eye fa-lg cursor-pointer" onclick="document.getElementById('viewProduct').showModal()"></i>
                                </div>
                            </td>
                            {{-- tampilan delete --}}
                            <dialog id="confirmDeleteDialog" class="modal">
                                <div class="modal-box">
                                    <form method="dialog">
                                        <!-- Close Button -->
                                        <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                                            onclick="closeDeleteDialog()">✕</button>
                                        <!-- Konten -->
                                        <h1 class="text-xl font-bold text-center mb-4">Delete Item?</h1>
                                        <p class="text-center text-gray-600">Are you sure you want to delete this item? This action cannot be undone.</p>
                                        <!-- Tombol -->
                                        <div class="flex justify-end gap-3 mt-6">
                                            <button type="button" onclick="closeDeleteDialog()"
                                                class="bg-gray-300 text-gray-800 rounded-lg px-4 py-2 hover:bg-gray-400">Cancel</button>
                                            <button type="button" onclick="confirmDelete()"
                                                class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-red-600">Yes, Delete</button>
                                        </div>
                                    </form>
                                </div>
                            </dialog>

                            {{-- tampilan edit --}}
                            <dialog id="editProduct" class="modal">
                                <div class="modal-box">
                                    <form method="dialog" id="editForm">
                                        <button id="cancel" type="button" onclick="closeEditModal()"
                                            class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                        <h1 class="font-semibold text-2xl mb-4">Edit Product</h1>

                                        <div class="flex gap-5 justify-between text-gray-600">
                                            <div class="w-[50%]">
                                                <h1 class="font-medium">PRODUCT</h1>
                                                <input type="text" id="edit_product" class="input w-full" placeholder="Insert Product">
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
                                            <button type="button" onclick="closeEditModal()"
                                                class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Cancel</button>
                                            <button type="submit"
                                                class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Edit</button>
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
                                            <img src="{{ asset('image/cyrene.jpg') }}" alt="Preview" class="w-full h-[180px] object-cover rounded-lg">
                                        </div>

                                        <!-- Tombol close -->
                                        <button type="button" onclick="document.getElementById('viewProduct').close()"
                                            class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>

                                        <h1 class="font-semibold text-2xl mb-4">Product Details</h1>

                                        <div class="flex gap-5 justify-between text-gray-600">
                                            <div class="w-[50%]">
                                                <h1 class="font-medium">PRODUCT</h1>
                                                <p>Access Point</p>
                                            </div>
                                            <div class="w-[50%]">
                                                <h1 class="font-medium">RACK</h1>
                                                <p>Rack 1</p>
                                            </div>
                                        </div>

                                        <div class="flex gap-5 justify-between text-gray-600 mt-3">
                                            <div class="w-[50%]">
                                                <h1 class="font-medium">BRAND</h1>
                                                <p>TP-Link</p>
                                            </div>
                                            <div class="w-[50%]">
                                                <h1 class="font-medium">CONDITION</h1>
                                                <p>Good</p>
                                            </div>
                                        </div>

                                        <div class="flex gap-5 justify-between text-gray-600 mt-3">
                                            <div class="w-[50%]">
                                                <h1 class="font-medium">TYPE</h1>
                                                <p>TL-WR840N</p>
                                            </div>
                                            <div class="w-[50%]">
                                                <h1 class="font-medium">STATUS</h1>
                                                <p>Ready</p>
                                            </div>
                                        </div>

                                        <div class="w-full mt-3">
                                            <h1 class="font-medium text-gray-600">SERIAL NUMBER</h1>
                                            <p>A1B2C3D4E5F6G7H</p>
                                        </div>

                                        <div class="w-full mt-3">
                                            <h1 class="font-medium text-gray-600">DESCRIPTION</h1>
                                            <p class="text-gray-600">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus vel enim eget lacus fermentum suscipit ut non ex.</p>
                                        </div>

                                        <div class="w-full flex justify-end items-end gap-4 mt-4">
                                            <button type="button" onclick="document.getElementById('viewProduct').close()"
                                                class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Close</button>
                                        </div>
                                    </form>
                                </div>
                            </dialog>
                            {{-- tampilan preview --}}
                            @endif
                        </tr>
                        @endforeach
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500">No history found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

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
                <td colspan="8" class="text-center text-gray-500">No items found</td>
            </tr>`;
                    return;
                }

                data.forEach(loan => {
                    loan.items.forEach((item, index) => {
                        const row = document.createElement('tr');
                        row.classList.add('hover');

                        let html = "";

                        if (index === 0) {
                            html += `<td rowspan="${loan.items.length}">${loan.loan_date}</td>`;
                        }

                        html += `
                <td>${loan.code_loans}</td>
                <td>${loan.loaner_name}</td>
                <td class="font-semibold">${item.code}</td>
                <td>${item.name}</td>
                <td><span class="badge badge-warning text-xs">${loan.status}</span></td>
                <td>${loan.return_date}</td>
            `;

                        if (index === 0) {
                            html += `
                    <td class="text-center" rowspan="${loan.items.length}">
                        <i class="fa fa-trash fa-lg cursor-pointer"></i>
                        <i class="fa fa-pen-to-square fa-lg mx-2 cursor-pointer"></i>
                        <i class="fa-regular fa-eye fa-lg cursor-pointer"></i>
                    </td>
                `;
                        }

                        row.innerHTML = html;
                        tbody.appendChild(row);
                    });
                });
            })

            .catch(error => {
                console.error("Error fetching filtered data:", error);
                const tbody = document.getElementById("itemTableBody");
                tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center text-red-500">Error loading data</td>
                </tr>`;
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

        const res = await fetch(`/api/items/${deleteTargetId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
// edit product
    function closeEditModal() {
        document.getElementById('editProduct').close();
    }

    document.getElementById("editForm").addEventListener("submit", function (e) {
        e.preventDefault();

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

        document.getElementById("editForm").reset();
        closeEditModal();
    });
</script>

@stack('scripts')
@include('template.footer')
