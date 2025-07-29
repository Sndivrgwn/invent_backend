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
                <h1 class="text-2xl font-semibold py-4">Pinjaman aktif</h1>
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

        <div class="tabs tabs-border w-full">

            <!-- Tab 1 -->
            <label class="tab border-0 text-blue-700 my-2 w-[100%] sm:w-[40%]">
                <input type="radio" name="my_tabs_4" checked="checked" />
                <i class="fa-solid fa-circle-arrow-up mr-2" style="display: flex; justify-content: center;"></i>
                Produk Keluar 
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
                            <input type="text" name="search-navbar" value="{{ request('search-navbar') }}" class="block w-full p-2 ps-10 text-sm border border-gray-400 rounded-lg" placeholder="Cari...">

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
                                <h1 class="text-lg font-semibold mb-2">Rak</h1>
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
                                <h1 class="text-lg font-semibold mb-2">Kondisi</h1>
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
                @php
                function sortLink($field, $currentSortBy, $currentSortDir, $isIncoming = true) {
                $newDir = ($currentSortBy === $field && $currentSortDir === 'asc') ? 'desc' : 'asc';
                return request()->fullUrlWithQuery([
                $isIncoming ? 'sortByIncoming' : 'sortByOutgoing' => $field,
                $isIncoming ? 'sortDirIncoming' : 'sortDirOutgoing' => $newDir,
                ]);
                }
                @endphp

                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center font-semibold">
                                    <a href="{{ sortLink('loaner_name', $sortByIncoming, $sortDirIncoming) }}">NAMA PEMINJAM</a>
                                </th>
                                <th class="text-center font-semibold">PRODUK</th>
                                <th class="text-center font-semibold">
                                    <a href="{{ sortLink('loan_date', $sortByIncoming, $sortDirIncoming) }}">TANGGAL PINJAM</a>
                                </th>
                                <th class="text-center font-semibold">
                                    <a href="{{ sortLink('return_date', $sortByIncoming, $sortDirIncoming) }}">TENGGAT</a>
                                </th>
                                <th class="text-center font-semibold">STATUS</th>
                                <th class="text-center font-semibold">TINDAKAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($incomingLoans as $loan)
                            <tr>
                                <td class="text-center">{{ $loan->loaner_name }}</td>
                                <td class="text-center">@foreach ($loan->items as $item)
                                    {{ $item->name }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach</td>
                                <td class="text-center">{{ $loan->loan_date }}</td>
                                <td class="text-center">{{ $loan->return_date }}</td>
                                <td class="text-center">{{ $loan->status }}</td>

                                <td class="text-center gap-2">
        @can('adminFunction')     

                                    <i class="fa-solid fa-file-pdf fa-lg text-red-600 cursor-pointer" title="Lihat PDF" onclick="window.open('{{ route('loan.print.pdf', ['id' => \Illuminate\Support\Facades\Crypt::encryptString($loan->id)]) }}', '_blank')"></i>
                                    @endcan
                                    <i class="fa-regular fa-eye fa-lg cursor-pointer" onclick="showLoanDetails({{ $loan->id }})"></i>
                                </td>

                                {{-- tampilan preview --}}
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-gray-500">Tidak ada pinjaman</td>
                            </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
                {{-- tampilan edit --}}
                <dialog id="itemDetailsDialog" class="modal">
                    <div class="modal-box w-11/12 max-w-5xl">
                        <button onclick="document.getElementById('itemDetailsDialog').close()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                        <div id="itemDetailsContent"></div>
                    </div>
                </dialog>
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
                {{-- tampilan edit --}}

                {{-- tampilan preview --}}
                <dialog id="viewProductIncome" class="modal">
                    <div class="modal-box">
                        <form method="dialog" id="viewForm">
                            <!-- Gambar atas -->

                            <!-- Tombol close -->
                            <button type="button" onclick="document.getElementById('viewProductIncome').close()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>

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
                                <button type="button" onclick="document.getElementById('viewProductIncome').close()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Close</button>
                            </div>
                        </form>
                    </div>
                </dialog>
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
            <label class="tab border-0 text-blue-700 w-[100%] sm:w-[40%] sm:my-2">
                <input type="radio" name="my_tabs_4" />
                <i class="fa-solid fa-circle-arrow-up mr-2" style="display: flex; justify-content: center;"></i>
                Produk masuk
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
                            <input type="text" name="search-navbar" value="{{ request('search-navbar') }}" class="block w-full p-2 ps-10 text-sm border border-gray-400 rounded-lg" placeholder="Cari...">

                        </form>
                    </div>
                </div>
                <!-- table -->
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center font-semibold">
                                    <a href="{{ sortLink('loaner_name', $sortByOutgoing, $sortDirOutgoing, false) }}">NAMA</a>
                                </th>
                                <th class="text-center font-semibold">PRODUK</th>
                                <th class="text-center font-semibold">
                                    <a href="{{ sortLink('loan_date', $sortByOutgoing, $sortDirOutgoing, false) }}">TANGGAL PINJAM</a>
                                </th>
                                <th class="text-center font-semibold">
                                    <a href="{{ sortLink('return_date', $sortByOutgoing, $sortDirOutgoing, false) }}">TENGGAT</a>
                                </th>
                                <th class="text-center font-semibold">STATUS</th>
        @can('adminFunction')     

                                <th class="text-center font-semibold">TINDAKAN</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($outgoingLoans as $loan)
                            <tr>
                                <td class="text-center">{{ $loan->loaner_name }}</td>
                                <td class="text-center">@foreach ($loan->items as $item)
                                    {{ $item->name }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach</td>
                                <td class="text-center">{{ $loan->loan_date }}</td>
                                <td class="text-center">{{ $loan->return_date }}</td>
                                <td class="text-center">{{ $loan->status }}</td>

                                @can('adminFunction')
                                <td class="text-center">
                                    <i class="fa-solid fa-right-left fa-lg cursor-pointer !leading-none" onclick="showReturnProduct({{ $loan->id }})"></i>
                                    <i class="fa fa-pen-to-square fa-lg cursor-pointer" onclick="openEditModal(
                                    {{ $loan->id }}, 
                                    '{{ $loan->loaner_name }}', 
                                    '{{ $loan->description }}', 
                                    '{{ $loan->return_date }}',
                                    '{{ $loan->loan_date }}'
                                )"></i>
                                    <i class="fa-solid fa-file-pdf fa-lg text-red-600 cursor-pointer" title="Lihat PDF" onclick="window.open('{{ route('loan.print.pdf', ['id' => \Illuminate\Support\Facades\Crypt::encryptString($loan->id)]) }}', '_blank')"></i>
                                    <i class="fa-regular fa-eye fa-lg cursor-pointer" onclick="showLoanDetailsTwo({{ $loan->id }})"></i>
                                </td>
                                @endcan
                                {{-- tampilan delete --}}



                                {{-- tampilan preview --}}

                                {{-- tampilan preview --}}
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-gray-500">Tidak ada pinjaman</td>
                            </tr>
                            @endforelse
                        </tbody>


                    </table>
                </div>
                <dialog id="itemDetailsDialogtwo" class="modal">
                    <div class="modal-box w-11/12 max-w-5xl">
                        <button onclick="document.getElementById('itemDetailsDialogtwo').close()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                        <div id="itemDetailsContenttwo"></div>
                    </div>
                </dialog>
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
                {{-- tampilan edit --}}

                {{-- tampilan preview --}}

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

                {{-- tampilan edit --}}
                <dialog id="editProductOutgoing" class="modal">
                    <div class="modal-box">
                        <form method="dialog" id="editForm">
                            <input type="hidden" id="edit_loan_id" name="loan_id">
                            <button id="cancel" type="button" onclick="closeEditModal()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                            <h1 class="font-semibold text-2xl mb-4">Edit Produk</h1>

                            <div class="flex gap-5 justify-between text-gray-600">
                                <div class="w-[100%]">
                                    <h1 class="font-medium">NAMA PEMINJAM</h1>
                                    <input type="text" id="edit_borrower_name" class="input w-full" placeholder="Masukkan Nama Peminjam">
                                </div>

                            </div>

                            <div class="flex gap-5 justify-between text-gray-600">
                                <div class="w-[100%]">
                                    <h1 class="font-medium">TANGGAL PENGEMBALIAN</h1>
                                    <input type="date" id="edit_return_date" class="input w-full" placeholder="Insert return date">
                                </div>

                            </div>


                            <div class="w-full mt-3">
                                <h1 class="font-medium text-gray-600">DESKRIPSI</h1>
                                <textarea id="edit_description" class="textarea w-full text-gray-600" placeholder="deskripsi"></textarea>
                            </div>

                            <div class="w-full flex justify-end items-end gap-4 mt-4">
                                <button type="button" onclick="closeEditModal()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Batal</button>
                                <button type="submit" class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Perbarui</button>
                            </div>
                        </form>
                    </div>
                </dialog>
                {{-- tampilan edit --}}
                <!-- Return Product Dialog -->
                <dialog id="returnProduct" class="modal">
                    <div class="modal-box max-w-sm sm:max-w-xl">
                        <form method="dialog" id="returnForm">

                            <button type="button" onclick="document.getElementById('returnProduct').close()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>

                            <h1 class="font-semibold text-2xl mb-2">Detail Produk</h1>
                            <h2 class="font-semibold text-xl text-blue-600 mb-4" id="modalLocationName">-</h2>

                            <div class="w-full mt-4">
                                <h1 class="font-medium text-gray-600 mb-2">KONDISI</h1>
                                <select id="returnCondition" class="select select-bordered w-full">
                                    <option value="GOOD">BAGUS</option>
                                    <option value="NOT GOOD">TIDAK BAGUS</option>
                                </select>
                            </div>
                            <div class="w-full mt-4">
                                <h1 class="font-medium text-gray-600 mb-2">CATATAN</h1>
                                <textarea id="returnNotes" class="textarea textarea-bordered w-full" placeholder="Any additional catatan..."></textarea>
                            </div>

                            <div class="w-full mt-4">
                                <h1 class="font-medium text-gray-600 mb-2">ITEM (Pratinjau)</h1>
                                <ul id="modalItemList" class="list-disc pl-5 space-y-1 text-gray-700 text-sm max-h-40 overflow-y-auto">
                                </ul>

                                <button id="viewAllBtn" class="text-sm text-blue-600 mt-2 hover:underline hidden" onclick="openAllItemsModal()">
                                    Lihat Semua Item →
                                </button>
                            </div>

                            <div class="w-full mt-4">
                                <h1 class="font-medium text-gray-600 mb-2">KATEGORI</h1>
                                <div id="modalCategoryList" class="flex flex-wrap gap-2">
                                </div>
                            </div>

                            <div class="w-full flex justify-end items-end gap-4 mt-6">
                                <button type="button" onclick="document.getElementById('returnProduct').close()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Tutup</button>
                                <button type="button" class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer" id="confirmReturnButton">Kembalikan Produk</button>
                            </div>
                        </form>
                    </div>
                </dialog>
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
    async function showLoanDetails(loanId) {
        try {
            const response = await fetch(`/api/history/${loanId}`);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error("Respons bukan JSON");
            }

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message || 'Gagal memuat pinjaman');
            }

            const loan = data.data;
            const modal = document.getElementById('viewProduct');

            // Build modal content (same as before)
            let modalContent = `
            <button type="button" onclick="document.getElementById('viewProduct').close()"
                class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            <h1 class="font-semibold text-2xl mb-4">Detail pinjaman</h1>
            <div class="mb-6">
                <h2 class="font-semibold text-lg mb-2">informasi pinjaman</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600">Kode pinjaman:</p>
                        <p>${loan.code_loans || 'N/A'}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Admin:</p>
                        <p>${loan.user?.name || 'N/A'}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Peminjam:</p>
                        <p>${loan.loaner_name || 'N/A'}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Tanggal pinjam:</p>
                        <p>${loan.loan_date || 'N/A'}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Tanggal kembali:</p>
                        <p>${loan.return_date || 'belum dikembalikan'}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Dikembalikan pada:</p>
                        <p>${loan.return?.return_date || 'belum dikembalikan'}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Deskripsi:</p>
                        <p>${loan.description || 'N/A'}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Catatan:</p>
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

            // Add items if they exist (same as before)
            if (loan.items && loan.items.length > 0) {
                if (loan.items.length > 1) {
                    modalContent += `
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>Nama Item</th>
                                    <th>Nomor Serial</th>
                                    <th>Kategori</th>
                                    <th>Tindakan</th>
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
                                    Lihat detail
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
                                <p class="text-gray-600">Nama Produk:</p>
                                <p>${item.name || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Nomor Serial:</p>
                                <p>${item.code || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Kategori:</p>
                                <p>${item.category?.name || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Lokasi:</p>
                                <p>${item.location?.name || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Merek:</p>
                                <p>${item.brand || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Tipe:</p>
                                <p>${item.type || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Kondisi:</p>
                                <p>${item.condition || 'N/A'}</p>
                            </div>
                        </div>
                    </div>
                `;
                }
            } else {
                modalContent += `<p class="text-gray-500">Tidak ada item yang ditemukan untuk pinjaman ini</p>`;
            }

            modalContent += `</div>`; // Close items section

            // Add close button
            modalContent += `
            <div class="w-full flex justify-end items-end gap-4 mt-4">
                <button type="button" onclick="document.getElementById('viewProduct').close()"
                    class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Tutup</button>
            </div>
        `;

            // Update modal content
            modal.querySelector('form').innerHTML = modalContent;
            modal.showModal();

        } catch (error) {
            console.error('Error:', error);
            showToast('Gagal memuat detail pinjaman.Lihat console untuk detailnya.', 'error');
        }
    }

    async function showLoanDetailsTwo(loanId) {
        try {
            const response = await fetch(`/api/history/${loanId}`);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error("Respons bukan JSON");
            }

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message || 'Gagal memuat pinjaman');
            }

            const loan = data.data;
            const dialog = document.getElementById('itemDetailsDialogtwo');
            const content = document.getElementById('itemDetailsContenttwo');

            // Build modal content (same as before)
            let modalContent = `
            <h1 class="font-semibold text-2xl mb-4">Detail Pinjaman</h1>
            <div class="mb-6">
                <h2 class="font-semibold text-lg mb-2">Informasi pinjaman</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600">Kode pinjaman:</p>
                        <p>${loan.code_loans || 'N/A'}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Admin:</p>
                        <p>${loan.user?.name || 'N/A'}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Peminjam:</p>
                        <p>${loan.loaner_name || 'N/A'}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Tanggal Pinjaman:</p>
                        <p>${loan.loan_date || 'N/A'}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Tanggal kembali:</p>
                        <p>${loan.return_date || 'Not returned yet'}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Deskripsi:</p>
                        <p>${loan.description || 'N/A'}</p>
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

            // Add items if they exist (same as before)
            if (loan.items && loan.items.length > 0) {
                if (loan.items.length > 1) {
                    modalContent += `
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>Nama item</th>
                                    <th>Nomor serial</th>
                                    <th>Kategori</th>
                                    <th>tindakan</th>
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
                                <button onclick="showItemDetailsTwo(${index}, ${loanId})" 
                                    class="btn btn-sm btn-ghost">
                                    Lihat detail
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
                                <p class="text-gray-600">Nama produk:</p>
                                <p>${item.name || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Nomor serial:</p>
                                <p>${item.code || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Kategori:</p>
                                <p>${item.category?.name || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Lokasi:</p>
                                <p>${item.location?.name || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Merek:</p>
                                <p>${item.brand || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Tipe:</p>
                                <p>${item.type || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Kondisi:</p>
                                <p>${item.condition || 'N/A'}</p>
                            </div>
                        </div>
                    </div>
                `;
                }
            } else {
                modalContent += `<p class="text-gray-500">Tidak ada barang yang ditemukan untuk pinjaman ini</p>`;
            }

            modalContent += `</div>`; // Close items section

            // Add close button
            modalContent += `
            <div class="w-full flex justify-end items-end gap-4 mt-4">
                <button type="button" onclick="document.getElementById('itemDetailsDialogtwo').close()"
                    class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Tutup</button>
            </div>
        `;

            // Update modal content
            content.innerHTML = modalContent;
            dialog.showModal();

        } catch (error) {
            console.error('Error:', error);
            showToast('Gagal memuat detail pinjaman.Lihat console untuk detailnya.', 'error');
        }
    }

    async function showItemDetailsTwo(itemIndex, loanId) {
        try {
            const response = await fetch(`/api/history/${loanId}`);
            const data = await response.json();
            const item = data.data.items[itemIndex];

            const dialog = document.getElementById('itemDetailsDialogtwo');
            const content = document.getElementById('itemDetailsContenttwo');

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
            const content = document.getElementById('itemDetailsContenttwo');
            content.innerHTML = `
            <div class="alert alert-error">
                Gagal memuat detail item: ${error.message}
            </div>
        `;
            showToast('Gagal memuat detail item.Lihat console untuk detailnya.', 'error');
            dialog.showModal();
        }
    }

    // Function to show detailed view of a specific item
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
            const content = document.getElementById('itemDetailsContent');
            content.innerHTML = `
            <div class="alert alert-error">
                Gagal memuat detail item: ${error.message}
            </div>
        `;
            showToast('Gagal memuat detail item.Lihat console untuk detailnya.', 'error');
            dialog.showModal();
        }
    }

    // edit product
    function closeEditModal() {
        document.getElementById('editProductOutgoing').close();
    }

    function closeEditModalIncome() {
        document.getElementById('editProductIncome').close();
    }

    function openEditModal(id, name, description, returnDate, loanDate) {
        document.getElementById('edit_loan_id').value = id;
        document.getElementById('edit_borrower_name').value = name;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_return_date').value = returnDate;

        // Simpan loan_date sebagai data attribute
        document.getElementById('editProductOutgoing').dataset.loanDate = loanDate;

        document.getElementById('editProductOutgoing').showModal();

        // Set min dan max date untuk input return_date
        const today = new Date().toISOString().split('T')[0];
        const loanDateObj = new Date(loanDate);
        const maxDateObj = new Date(loanDateObj);
        maxDateObj.setDate(loanDateObj.getDate() + 14); // 2 minggu setelah loan_date
        const maxDate = maxDateObj.toISOString().split('T')[0];

        const returnDateInput = document.getElementById('edit_return_date');
        returnDateInput.min = today;
        returnDateInput.max = maxDate;

        // Tambahkan pesan validasi
        returnDateInput.title = `Tanggal pengembalian harus antara hari ini dan ${maxDate}`;
    }

    document.getElementById("editForm").addEventListener("submit", async function(e) {
        e.preventDefault();

        const loanId = document.getElementById("edit_loan_id").value;
        const returnDate = document.getElementById("edit_return_date").value;
        const loanDate = document.getElementById('editProductOutgoing').dataset.loanDate;

        // Validasi tanggal
        const today = new Date().toISOString().split('T')[0];
        const loanDateObj = new Date(loanDate);
        const maxDateObj = new Date(loanDateObj);
        maxDateObj.setDate(loanDateObj.getDate() + 14);
        const maxDate = maxDateObj.toISOString().split('T')[0];

        if (returnDate < today) {
            showToast('Tanggal pengembalian tidak bisa sebelum hari ini', 'error');
            return;
        }

        if (returnDate > maxDate) {
            showToast('Tanggal pengembalian tidak bisa lebih dari 2 minggu dari tanggal pinjaman', 'error');
            return;
        }

        const payload = {
            loaner_name: document.getElementById("edit_borrower_name").value
            , return_date: returnDate
            , description: document.getElementById("edit_description").value
        };

        try {
            const response = await fetch(`/api/loans/${loanId}`, {
                method: 'PUT'
                , headers: {
                    'Content-Type': 'application/json'
                    , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
                , body: JSON.stringify(payload)
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Gagal memperbarui pinjaman');
            }

            const data = await response.json();
            showToast('Pinjaman berhasil diperbarui!', 'success');
            location.reload();
        } catch (error) {
            console.error('Error:', error);
            showToast(error.message || 'Gagal memperbarui pinjaman', 'error');
        }
    });

    async function showReturnProduct(id) {
        returnTargetId = id;
        try {
            const response = await fetch(`/api/loans/${id}`);
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message || 'Gagal mengambil detail pinjaman');
            }

            const loan = data.data;
            const modal = document.getElementById('returnProduct');

            // Populate modal data
            document.getElementById('modalLocationName').textContent = loan.code_loans;

            const itemList = document.getElementById('modalItemList');
            itemList.innerHTML = '';

            loan.items.forEach((item, index) => {
                const li = document.createElement('li');
                li.textContent = `${item.code} - ${item.name}`;
                if (index >= 3) li.classList.add('hidden');
                itemList.appendChild(li);
            });

            // Show "View All" button if more than 3 items
            const viewAllBtn = document.getElementById('viewAllBtn');
            viewAllBtn.classList.toggle('hidden', loan.items.length <= 3);

            const categoryList = document.getElementById('modalCategoryList');
            categoryList.innerHTML = '';

            // Get unique categories
            const categories = [];
            loan.items.forEach(item => {
                if (item.category && !categories.find(c => c.id === item.category.id)) {
                    categories.push(item.category);
                }
            });

            categories.forEach(category => {
                const span = document.createElement('span');
                span.className = 'badge badge-primary';
                span.textContent = category.name;
                categoryList.appendChild(span);
            });

            modal.showModal();
        } catch (error) {
            console.error('Error:', error);
            showToast(error.message || 'Gagal memuat detail pinjaman', 'error');
        }
    }

    // Handle return confirmation
    document.getElementById('confirmReturnButton').addEventListener('click', async function() {
        if (!returnTargetId) return;

        const condition = document.getElementById('returnCondition').value;
        const notes = document.getElementById('returnNotes').value;

        try {
            const response = await fetch(`/api/return/${returnTargetId}`, {
                method: 'POST'
                , headers: {
                    'Accept': 'application/json'
                    , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    , 'Content-Type': 'application/json'
                }
                , body: JSON.stringify({
                    condition: condition
                    , notes: notes
                })
            });

            const data = await response.json();

            if (response.ok) {
                handleAjaxResponse({
                    toast: {
                        message: data.message || 'Pinjaman berhasil kembali'
                        , type: 'success'
                    }
                });
                window.location.href = "{{ route('history') }}";
            } else {
                throw new Error(data.message || 'Gagal mengembalikan pinjaman');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast(error.message || 'Terjadi kesalahan saat mengembalikan pinjaman', 'error');
        } finally {
            document.getElementById('returnProduct').close();
            returnTargetId = null;
        }
    });

</script>

@stack('scripts')
@include('template.footer')
