@include('template.head')

<div class="flex h-screen bg-gradient-to-b from-blue-100 to-white">
    <!-- Sidebar -->
    <div>
        @include('template.sidebar')
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto px-6">
        {{-- header --}}

        {{-- navbar --}}
        <div>
            @include('template.navbar')
        </div>
        <div class="flex flex-col gap-6 pt-6">
            <h1 class="text-2xl font-semibold">Dashboard</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Total Products -->
                <div class="bg-white p-4 rounded-lg shadow flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-medium text-gray-400 mb-1">Total Products</h2>
                        <p class="text-2xl font-semibold">{{ $totalItems }}</p>
                        <p class="text-sm text-gray-400 mt-1">Total number of products in the system.</p>
                    </div>
                    <div class="bg-blue-500 bg-opacity-25 text-white p-4 rounded-full flex items-center justify-center">
                        <i class="fa fa-cube bg-blue-500" style="display: flex; justify-content: center;"></i>
                    </div>
                </div>
                <!-- Total Inventory -->
                <div class="bg-white p-4 rounded-lg shadow flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-medium text-gray-400 mb-1">Total Inventory</h2>
                        <p class="text-2xl font-semibold">{{ $totalCategories }}</p>
                        <p class="text-sm text-gray-400 mt-1">Recorded inventory categories or types.</p>
                    </div>
                    <div
                        class="bg-green-500 bg-opacity-25 text-white p-4 rounded-full flex items-center justify-center">
                        <i class="fa fa-boxes bg-green-500" style="display: flex; justify-content: center;"></i>
                    </div>
                </div>
                <!-- Total Loans -->
                <div class="bg-white p-4 rounded-lg shadow flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-medium text-gray-400 mb-1">Total Loans</h2>
                        <p class="text-2xl font-semibold">{{ $totalLoans }}</p>
                        <p class="text-sm text-gray-400 mt-1">Items currently on loaned.</p>
                    </div>
                    <div
                        class="bg-yellow-500 bg-opacity-25 text-white p-4 rounded-full flex items-center justify-center">
                        <i class="fa fa-handshake bg-yellow-500" style="display: flex; justify-content: center;"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-md">
                <!-- Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
                    <h2 class="text-lg font-semibold text-gray-700 w-full md:w-auto">Recent Loan</h2>

                    <!-- Controls -->
                    <div class="flex flex-col md:flex-row items-stretch md:items-center gap-2 w-full md:w-auto">
                        <!-- Search -->
                        <div class="relative w-full md:block">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                                <span class="sr-only">Search icon</span>
                            </div>
                            <form method="GET" action="{{ route('dashboard') }}" class="relative w-full md:block">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                    <span class="sr-only">Search icon</span>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="block w-full p-2 ps-10 text-sm border border-gray-400 rounded-lg"
                                    placeholder="Search...">
                            </form>

                        </div>

                        

                        <!-- Export Button -->

                    </div>
                </div>


                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead class="text-gray-500 text-sm font-semibold border-b">
                            <tr>
                                <th>DATE</th>
                                <th>LOANER NAME</th>
                                <th>SERIAL NUMBER</th>
                                <th>PRODUCT</th>
                                <th>CONDITION</th>
                                <th>STATUS</th>
                                <th class="text-center">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach($loans as $loan)
                            @foreach ($loan->items as $index => $item)
                            <tr class="hover">
                                {{-- Tampilkan loan_date hanya di baris pertama --}}
                                @if ($index === 0)
                                <td rowspan="{{ count($loan->items) }}">{{ $loan->loan_date }}</td>
                                @endif

                                <td>{{ $loan->loaner_name }}</td>
                                <td class="font-semibold">{{ $item->code }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->condition }}</td>
                                <td>
                                    <span class="badge badge-warning text-xs">{{$item->status}}</span>
                                </td>

                                {{-- Tampilkan action hanya di baris pertama --}}
                                @if ($index === 0)
                                <td class="text-center" rowspan="{{ count($loan->items) }}">
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
                            @endforeach
                        </tbody>

                    </table>
                </div>

                <!-- Footer Pagination -->
                <div class="flex justify-end mb-4 mt-4">
                    <div class="join">
                        {{-- Previous Page Link --}}
                        @if ($loans->onFirstPage())
                        <button class="join-item btn btn-disabled">«</button>
                        @else
                        <a href="{{ $loans->previousPageUrl() }}" class="join-item btn">«</a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($loans->getUrlRange(1, $loans->lastPage()) as $page => $url)
                        <a href="{{ $url }}"
                            class="join-item btn {{ $loans->currentPage() == $page ? 'btn-primary' : '' }}">
                            {{ $page }}
                        </a>
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($loans->hasMorePages())
                        <a href="{{ $loans->nextPageUrl() }}" class="join-item btn">»</a>
                        @else
                        <button class="join-item btn btn-disabled">»</button>
                        @endif
                    </div>
                </div>
            </div>



        </div>


    </div>
</div>

<script>
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