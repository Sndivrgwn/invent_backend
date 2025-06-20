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

        <div class="navbar my-6">
            <div class="flex-1">
                <h1 class="text-2xl font-semibold py-4">Active Loans</h1>
            </div>
            {{-- <div class="flex-none">
                <a href="{{ route('pages.manageLoan') }}" class="">
                    <button class="bg-[#2563EB] text-white rounded-lg p-2 px-5 w-full hover:bg-blue-400 cursor-pointer flex justify-center items-center gap-2">
                        <i class="fa fa-screwdriver-wrench flex justify-center items-center"></i>
                        <span>Manage Loan</span>
                    </button>
                </a>
            </div> --}}
        </div>

        <div class="list bg-base-100 rounded-box shadow-md">

            <div class="tabs tabs-border p-8">

                <!-- Tab 1 -->
                <label class="tab text-blue-700 px-10! pb-2! mx-0!">
                    <input type="radio" name="my_tabs_4" checked="checked" />
                    <i class="fa fa-circle-arrow-down mr-1" style="display: flex; justify-content: center;"></i>
                    Incoming Product
                </label>
                <!-- Value Tab 1 -->
                <div class="tab-content bg-base-100" style="border-top: 1px solid lightgray;">
                    <div class="p-4 pb-2 flex">
                        <!-- search -->
                        <div class="relative w-full hidden md:block mr-4">
                            <form method="GET" action="{{ route('loan') }}" class="relative w-full md:block">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                    <span class="sr-only">Search icon</span>
                                </div>
                                <input type="text" name="search-navbar" value="{{ request('search-navbar') }}" class="block w-full p-2 ps-10 text-sm border border-gray-400 rounded-lg" placeholder="Search...">

                            </form>
                        </div>

                        </button>
                        <dialog id="filterProduct" class="modal">
                            <div class="modal-box">
                                <!-- close button -->
                                <form method="dialog">
                                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                </form>
                                <!-- product filter -->
                                <div>
                                    <h1 class="text-lg font-semibold mb-2">Rack</h1>
                                    <form class="filter">
                                        <input class="btn mb-1 btn-square" type="reset" value="×" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="Rack 1" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="Rack 2" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="Rack 3" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="Rack 4" />

                                    </form>
                                </div>
                                <!-- condition filter -->
                                <div>
                                    <h1 class="text-lg font-semibold mb-2">Condition</h1>
                                    <form class="filter">
                                        <input class="btn mb-1 btn-square" type="reset" value="×" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="GOOD" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="BAD" />
                                    </form>
                                </div>
                                <!-- status filter -->
                                <div>
                                    <h1 class="text-lg font-semibold mb-2">Status</h1>
                                    <form class="filter">
                                        <input class="btn mb-1 btn-square" type="reset" value="×" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="READY" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="NOT READY" />
                                    </form>
                                </div>
                            </div>
                        </dialog>
                    </div>
                    <!-- table -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center font-semibold">NAME</th>
                                <th class="text-center font-semibold">PRODUCT</th>
                                <th class="text-center font-semibold">BORROW DATE</th>
                                <th class="text-center font-semibold">DUE DATE</th>
                                <th class="text-center font-semibold">STATUS</th>
                                <th class="text-center font-semibold">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($incomingLoans as $loan)
                            <tr>
                                <td class="text-center">{{ $loan->loaner_name }}</td>
                                <td class="text-center">@foreach ($loan->items as $item)
                                    {{ $item->name }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach</td>
                                <td class="text-center">{{ $loan->loan_date }}</td>
                                <td class="text-center">{{ $loan->return_date }}</td>
                                <td class="text-center">{{ $loan->status }}</td>

                                <td class="text-center">
                                    <i class="fa fa-pen-to-square fa-lg cursor-pointer" onclick="document.getElementById('editProductIncome').showModal()"></i>
                                    <i class="fa-regular fa-eye fa-lg cursor-pointer" onclick="document.getElementById('viewProductIncome').showModal()"></i>
                                </td>
                                {{-- tampilan edit --}}
                            <dialog id="editProductIncome" class="modal">
                                <div class="modal-box">
                                    <form method="dialog" id="editForm">
                                        <button id="cancel" type="button" onclick="closeEditModalIncome()"
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
                                            <button type="button" onclick="closeEditModalIncome()"
                                                class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Cancel</button>
                                            <button type="submit"
                                                class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Edit</button>
                                        </div>
                                    </form>
                                </div>
                            </dialog>
                            {{-- tampilan edit --}}

                            {{-- tampilan preview --}}
                            <dialog id="viewProductIncome" class="modal">
                                <div class="modal-box">
                                    <form method="dialog" id="viewForm">
                                        <!-- Gambar atas -->
                                        <div class="w-full mb-4">
                                            <img src="{{ asset('image/cyrene.jpg') }}" alt="Preview" class="w-full h-[180px] object-cover rounded-lg">
                                        </div>

                                        <!-- Tombol close -->
                                        <button type="button" onclick="document.getElementById('viewProductIncome').close()"
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
                                            <button type="button" onclick="document.getElementById('viewProductIncome').close()"
                                                class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Close</button>
                                        </div>
                                    </form>
                                </div>
                            </dialog>
                            {{-- tampilan preview --}}
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                    <div class="flex justify-end mb-4 mt-4">
                        <div class="join">
                            {{-- Previous Page Link --}}
                            @if ($incomingLoans->onFirstPage())
                            <button class="join-item btn btn-disabled">«</button>
                            @else
                            <a href="{{ $incomingLoans->previousPageUrl() }}" class="join-item btn">«</a>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($incomingLoans->getUrlRange(1, $incomingLoans->lastPage()) as $page => $url)
                            <a href="{{ $url }}" class="join-item btn {{ $incomingLoans->currentPage() == $page ? 'btn-primary' : '' }}">
                                {{ $page }}
                            </a>
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($incomingLoans->hasMorePages())
                            <a href="{{ $incomingLoans->appends(request()->query())->previousPageUrl() }}" class="join-item btn">«</a>
                            @else
                            <button class="join-item btn btn-disabled">»</button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Tab 2 -->
                <label class="tab border-0 text-blue-700 px-10! pb-2! mx-0!">
                    <input type="radio" name="my_tabs_4" />
                    <i class="fa-solid fa-circle-arrow-up mr-2" style="display: flex; justify-content: center;"></i>
                    Outgoing Product
                </label>
                <!-- Value Tab 2 -->
                <div class="tab-content bg-base-100" style="border-top: 1px solid lightgray; ">
                    <div class="p-4 pb-2 flex">
                        <!-- search -->
                        <div class="relative w-full hidden md:block mr-4">
                            <form method="GET" action="{{ route('loan') }}" class="relative w-full md:block">
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

                        <dialog id="filterProduct" class="modal">
                            <div class="modal-box">
                                <!-- close button -->
                                <form method="dialog">
                                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                </form>
                                <!-- product filter -->
                                <div>
                                    <h1 class="text-lg font-semibold mb-2">Rack</h1>
                                    <form class="filter">
                                        <input class="btn mb-1 btn-square" type="reset" value="×" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="Rack 1" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="Rack 2" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="Rack 3" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="Rack 4" />

                                    </form>
                                </div>
                                <!-- condition filter -->
                                <div>
                                    <h1 class="text-lg font-semibold mb-2">Condition</h1>
                                    <form class="filter">
                                        <input class="btn mb-1 btn-square" type="reset" value="×" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="GOOD" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="BAD" />
                                    </form>
                                </div>
                                <!-- status filter -->
                                <div>
                                    <h1 class="text-lg font-semibold mb-2">Status</h1>
                                    <form class="filter">
                                        <input class="btn mb-1 btn-square" type="reset" value="×" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="READY" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="NOT READY" />
                                    </form>
                                </div>
                            </div>
                        </dialog>
                    </div>
                    <!-- table -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center font-semibold">NAME</th>
                                <th class="text-center font-semibold">PRODUCT</th>
                                <th class="text-center font-semibold">BORROW DATE</th>
                                <th class="text-center font-semibold">DUE DATE</th>
                                <th class="text-center font-semibold">STATUS</th>
                                <th class="text-center font-semibold">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($outgoingLoans as $loan)
                            <tr>
                                <td class="text-center">{{ $loan->loaner_name }}</td>
                                <td class="text-center">@foreach ($loan->items as $item)
                                    {{ $item->name }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach</td>
                                <td class="text-center">{{ $loan->loan_date }}</td>
                                <td class="text-center">{{ $loan->return_date }}</td>
                                <td class="text-center">{{ $loan->status }}</td>

                                <td class="text-center">
                                    <i class="fa fa-pen-to-square fa-lg cursor-pointer" onclick="document.getElementById('editProductOutgoing').showModal()"></i>
                                    <i class="fa-regular fa-eye fa-lg cursor-pointer" onclick="document.getElementById('viewProductOutgoing').showModal()"></i>
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
                            <dialog id="editProductOutgoing" class="modal">
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
                            <dialog id="viewProductOutgoing" class="modal">
                                <div class="modal-box">
                                    <form method="dialog" id="viewForm">
                                        <!-- Gambar atas -->
                                        <div class="w-full mb-4">
                                            <img src="{{ asset('image/cyrene.jpg') }}" alt="Preview" class="w-full h-[180px] object-cover rounded-lg">
                                        </div>

                                        <!-- Tombol close -->
                                            <button type="button" onclick="document.getElementById('viewProductOutgoing').close()"
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
                                            <button type="button" onclick="document.getElementById('viewProductOutgoing').close()"
                                                class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Close</button>
                                        </div>
                                    </form>
                                </div>
                            </dialog>
                            {{-- tampilan preview --}}
                            </tr>
                            @endforeach
                        </tbody>


                    </table>

                    <div class="flex justify-end mb-4 mt-4">
                        <div class="join">
                            {{-- Previous Page Link --}}
                            @if ($outgoingLoans->onFirstPage())
                            <button class="join-item btn btn-disabled">«</button>
                            @else
                            <a href="{{ $outgoingLoans->previousPageUrl() }}" class="join-item btn">«</a>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($outgoingLoans->getUrlRange(1, $outgoingLoans->lastPage()) as $page => $url)
                            <a href="{{ $url }}" class="join-item btn {{ $outgoingLoans->currentPage() == $page ? 'btn-primary' : '' }}">
                                {{ $page }}
                            </a>
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($outgoingLoans->hasMorePages())
                            <a href="{{ $outgoingLoans->appends(request()->query())->previousPageUrl() }}" class="join-item btn">«</a>
                            @else
                            <button class="join-item btn btn-disabled">»</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>

<script>

// edit product
    function closeEditModal() {
        document.getElementById('editProductOutgoing').close();
    }
    function closeEditModalIncome() {
        document.getElementById('editProductIncome').close();
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
