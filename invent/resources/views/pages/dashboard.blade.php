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
                            <input type="text" id="search-navbar"
                                class="block w-full p-2 ps-10 text-sm border border-gray-400 rounded-lg"
                                placeholder="Search...">
                        </div>

                        <!-- Filter -->
                        <select class="select select-bordered w-full md:w-40">
                            <option selected>All Status</option>
                            <option>Ready</option>
                            <option>Not Ready</option>
                        </select>

                        <!-- Export Button -->
                        <button class="btn btn-outline w-full md:w-auto">
                            Export
                        </button>
                    </div>
                </div>


                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead class="text-gray-500 text-sm font-semibold border-b">
                            <tr>
                                <th>DATE</th>
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

                                <td class="font-semibold">{{ $item->code }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->condition }}</td>
                                <td>
                                    <span class="badge badge-warning text-xs">{{$item->status}}</span>
                                </td>

                                {{-- Tampilkan action hanya di baris pertama --}}
                                @if ($index === 0)
                                <td class="text-center" rowspan="{{ count($loan->items) }}">
                                    <i class="fa fa-trash fa-lg"></i>
                                    <i class="fa fa-pen-to-square fa-lg"></i>
                                    <i class="fa-regular fa-eye fa-lg"></i>
                                </td>
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

@include('template.footer')