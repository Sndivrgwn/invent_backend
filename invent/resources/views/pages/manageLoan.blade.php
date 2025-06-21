@include('template.head')

<div class="flex flex-col h-screen bg-gradient-to-b from-blue-100 to-white md:flex-row">
    <div class="w-full md:w-auto"> {{-- Tambahkan w-full untuk mobile, dan w-auto untuk desktop --}}
        @include('template.sidebar')
    </div>

    <div class="flex-1 overflow-y-auto px-4 md:px-6"> {{-- Padding disesuaikan --}}
        {{-- header --}}

        {{-- navbar --}}
        <div>
            @include('template.navbar')
        </div>

        <div class="navbar my-6">
            <div class="flex-1">
                <h1 class="text-2xl font-semibold py-4">Manage Loans</h1>
            </div>
        </div>

        <div class="list bg-base-100 rounded-box shadow-md">

            <div class="p-4 sm:p-8"> {{-- Padding disesuaikan untuk mobile dan desktop --}}
                <label class="tab border-0 px-6 pb-2 mx-0! sm:px-10! sm:pb-2! sm:mx-0!"> {{-- Padding tab disesuaikan --}}
                    <input type="radio" name="my_tabs_4" />
                    <i class="fa-solid fa-circle-arrow-up mr-2 flex justify-center items-center"></i> {{-- Tambah items-center --}}
                    Return Product
                </label>
                <div class="bg-base-100" style="border-top: 1px solid lightgray;">
                    <div class="p-4 pb-2 flex flex-col md:flex-row items-center"> {{-- Layout kolom disesuaikan --}}
                        <div class="relative w-full md:w-1/2 lg:w-1/3 mr-0 md:mr-4 mb-4 md:mb-0"> {{-- Lebar search disesuaikan --}}
                            <form method="GET" action="{{ route('loan') }}" class="relative w-full">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                    <span class="sr-only">Search icon</span>
                                </div>
                                <input type="text" name="search-navbar" value="{{ request('search-navbar') }}" class="block w-full p-2 ps-10 text-sm border border-gray-400 rounded-lg" placeholder="Search...">
                            </form>
                        </div>

                        <dialog id="filterProduct" class="modal">
                            <div class="modal-box">
                                <form method="dialog">
                                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                </form>
                                <div>
                                    <h1 class="text-lg font-semibold mb-2">Rack</h1>
                                    <form class="filter flex flex-wrap gap-2"> {{-- Flex wrap untuk filter --}}
                                        <input class="btn mb-1 btn-square" type="reset" value="×" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="Rack 1" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="Rack 2" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="Rack 3" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="Rack 4" />
                                    </form>
                                </div>
                                <div class="mt-4"> {{-- Margin top untuk pemisah filter --}}
                                    <h1 class="text-lg font-semibold mb-2">Condition</h1>
                                    <form class="filter flex flex-wrap gap-2">
                                        <input class="btn mb-1 btn-square" type="reset" value="×" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="GOOD" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="BAD" />
                                    </form>
                                </div>
                                <div class="mt-4"> {{-- Margin top untuk pemisah filter --}}
                                    <h1 class="text-lg font-semibold mb-2">Status</h1>
                                    <form class="filter flex flex-wrap gap-2">
                                        <input class="btn mb-1 btn-square" type="reset" value="×" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="READY" />
                                        <input class="btn mb-1" type="radio" name="frameworks" aria-label="NOT READY" />
                                    </form>
                                </div>
                            </div>
                        </dialog>
                    </div>
                    <div class="overflow-x-auto"> {{-- Tambahkan overflow-x-auto untuk tabel --}}
                        <table class="table w-full"> {{-- Pastikan tabel mengambil lebar penuh --}}
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
                                {{-- @foreach ($outgoingLoans as $loan) --}}
                                <tr>
                                    {{-- <td class="text-center">{{ $loan->loaner_name }}</td>
                                    <td class="text-center">@foreach ($loan->items as $item)
                                        {{ $item->name }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach</td>
                                    <td class="text-center">{{ $loan->loan_date }}</td>
                                    <td class="text-center">{{ $loan->return_date }}</td>
                                    <td class="text-center">{{ $loan->status }}</td> --}}
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-center">
                                                <i class="fa fa-right-left fa-lg cursor-pointer" onclick="document.getElementById('returnProduct').showModal()"></i>
                                                <i class="fa-regular fa-eye fa-lg cursor-pointer" onclick="document.getElementById('viewProduct').showModal()"></i>
                                                <i class="fa fa-trash fa-lg cursor-pointer !leading-none" onclick="showDeleteConfirmation()"></i> 
                                    </td>
                                    {{-- tampilan delete --}}
                                <dialog id="confirmDeleteDialog" class="modal">
                                    <div class="modal-box">
                                        <form method="dialog">
                                            <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                                                onclick="document.getElementById('confirmDeleteDialog').close()">✕</button>
                                            <h1 class="text-xl font-bold text-center mb-4">Delete Item?</h1>
                                            <p class="text-center text-gray-600">Are you sure you want to delete this item? This action cannot be undone.</p>
                                            <div class="flex justify-end gap-3 mt-6">
                                                <button type="button" onclick="document.getElementById('confirmDeleteDialog').close()"
                                                    class="bg-gray-300 text-gray-800 rounded-lg px-4 py-2 hover:bg-gray-400">Cancel</button>
                                                <button type="button" onclick="document.getElementById('confirmDeleteDialog').close()"
                                                    class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-red-600">Yes, Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </dialog>

                                {{-- tampilan return --}}
                                <dialog id="returnProduct" class="modal">
                                    <div class="modal-box max-w-sm sm:max-w-xl">
                                        <form method="dialog" id="returnForm">
                                            <div class="w-full mb-4">
                                                <img src="{{ asset('image/cyrene.jpg') }}" alt="Preview" class="w-full h-[180px] object-cover rounded-lg">
                                            </div>

                                            <button type="button" onclick="document.getElementById('returnProduct').close()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>

                                            <h1 class="font-semibold text-2xl mb-2">Product Details</h1>
                                            <h2 class="font-semibold text-xl text-blue-600 mb-4" id="modalLocationName">-</h2>

                                            <div class="w-full mt-3">
                                                <h1 class="font-medium text-gray-600">DESCRIPTION</h1>
                                                <p class="text-gray-600" id="modalLocationDescription">-</p>
                                            </div>

                                            <div class="w-full mt-4">
                                                <h1 class="font-medium text-gray-600 mb-2">ITEMS (Preview)</h1>
                                                <ul id="modalItemList" class="list-disc pl-5 space-y-1 text-gray-700 text-sm max-h-40 overflow-y-auto">
                                                    </ul>

                                                <button id="viewAllBtn" class="text-sm text-blue-600 mt-2 hover:underline hidden" onclick="openAllItemsModal()">
                                                    Lihat Semua Item →
                                                </button>
                                            </div>

                                            <div class="w-full mt-4">
                                                <h1 class="font-medium text-gray-600 mb-2">CATEGORIES</h1>
                                                <div id="modalCategoryList" class="flex flex-wrap gap-2">
                                                    </div>
                                            </div>

                                            <div class="w-full flex justify-end items-end gap-4 mt-6">
                                                <button type="button" onclick="document.getElementById('returnProduct').close()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Close</button>
                                                <button type="button" class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer" id="confirmReturnButton">Return Product</button>
                                            </div>
                                        </form>
                                    </div>
                                </dialog>
                                {{-- tampilan return --}}

                                {{-- tampilan preview --}}
                                <dialog id="viewProduct" class="modal">
                                    <div class="modal-box max-w-sm sm:max-w-md"> 
                                        <form method="dialog" id="viewForm">
                                            <div class="w-full mb-4">
                                                <img src="{{ asset('image/cyrene.jpg') }}" alt="Preview" class="w-full h-[180px] object-cover rounded-lg">
                                            </div>

                                            <button type="button" onclick="document.getElementById('viewProduct').close()"
                                                class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>

                                            <h1 class="font-semibold text-2xl mb-4">Product Details</h1>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-3 text-gray-600"> 
                                                <div>
                                                    <h1 class="font-medium">PRODUCT</h1>
                                                    <p>Access Point</p>
                                                </div>
                                                <div>
                                                    <h1 class="font-medium">RACK</h1>
                                                    <p>Rack 1</p>
                                                </div>
                                                <div>
                                                    <h1 class="font-medium">BRAND</h1>
                                                    <p>TP-Link</p>
                                                </div>
                                                <div>
                                                    <h1 class="font-medium">CONDITION</h1>
                                                    <p>Good</p>
                                                </div>
                                                <div>
                                                    <h1 class="font-medium">TYPE</h1>
                                                    <p>TL-WR840N</p>
                                                </div>
                                                <div>
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
                                </tr>
                                {{-- @endforeach --}}
                            </tbody>
                        </table>
                    </div> 

                    <div class="flex justify-end mb-4 mt-4">
                        <div class="join flex-wrap justify-end"> 
                            {{-- Previous Page Link --}}
                            {{-- @if ($outgoingLoans->onFirstPage())
                            <button class="join-item btn btn-disabled">«</button>
                            @else
                            <a href="{{ $outgoingLoans->previousPageUrl() }}" class="join-item btn">«</a>
                            @endif --}}

                            {{-- Pagination Elements --}}
                            {{-- @foreach ($outgoingLoans->getUrlRange(1, $outgoingLoans->lastPage()) as $page => $url)
                            <a href="{{ $url }}" class="join-item btn {{ $outgoingLoans->currentPage() == $page ? 'btn-primary' : '' }}">
                                {{ $page }}
                            </a>
                            @endforeach --}}

                            {{-- Next Page Link --}}
                            {{-- @if ($outgoingLoans->hasMorePages())
                            <a href="{{ $outgoingLoans->appends(request()->query())->previousPageUrl() }}" class="join-item btn">«</a>
                            @else
                            <button class="join-item btn btn-disabled">»</button>
                            @endif --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>

<script>

    function showDeleteConfirmation(index) {
    itemIndexToDelete = index; 
    document.getElementById('confirmDeleteDialog').showModal();
    }

</script>

@stack('scripts')
@include('template.footer')