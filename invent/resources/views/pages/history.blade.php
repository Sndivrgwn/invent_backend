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
                <h1 class="text-2xl font-semibold py-4">History </h1>
            </div>
            <div class="flex-none">
                {{-- new product --}}
                <button class="bg-[#ffffff] rounded-lg py-2 px-4 mx-5 hover:bg-blue-400 cursor-pointer flex justify-center items-center">
                    <div class="gap-2 flex">
                        <i class="fa fa-download" style="display: flex; justify-content: center; align-items: center;"></i>
                        <a href="{{ route('loans.exportHistory') }}">Export Report</a>
                    </div>
                </button>
            </div>
        </div>

        <div class="list bg-base-100 rounded-box shadow-md">

            <div class="p-4 pb-2 flex">
                <!-- search -->
                        <div class="relative w-full hidden md:block mr-4">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                                <span class="sr-only">Search icon</span>
                            </div>
                            <form method="GET" action="{{ route('history') }}" class="relative w-full md:block">
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

                <!-- filter --> 
                <button class="btn flex justify-center items-center bg-transparent" onclick="filterProduct.showModal()">All Categories <i class="fa fa-filter" style="display: flex; justify-content: center; align-items: center;"></i></button>
                <dialog id="filterProduct" class="modal">
                    <div class="modal-box">
                        <!-- close button -->
                        <form method="dialog">
                            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                        </form>
                        <!-- product filter -->
                        <div>
                            <h1 class="text-lg font-semibold mb-2">Product</h1>
                            <form class="filter">
                                <input class="btn mb-1 btn-square" type="reset" value="×" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="MikroTik" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="Access Point" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="Crimping Tool" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="Switch" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="Cable Tester" />
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
                {{-- pick date --}}
                <button popovertarget="cally-popover1" class="border rounded mx-3 px-3 py-2 flex items-center gap-2 shadow-sm bg-white hover:bg-gray-100" id="cally1">
                    <span id="calendar-date-label">hh/bb/tttt</span>
                    <i class="fa fa-calendar text-gray-500"></i>
                </button>
                <div popover id="cally-popover1" class="absolute z-50 mt-2 bg-white border rounded shadow-lg p-2 w-max max-h-[300px] overflow-y-auto">
                    <calendar-date class="cally" onchange="document.getElementById('calendar-date-label').innerText = this.value">
                        <svg aria-label="Previous" class="fill-current size-4" slot="previous" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M15.75 19.5 8.25 12l7.5-7.5"></path></svg>
                        <svg aria-label="Next" class="fill-current size-4" slot="next" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="m8.25 4.5 7.5 7.5-7.5 7.5"></path></svg>
                        <calendar-month></calendar-month>
                    </calendar-date>
                </div>

            </div>
            <!-- table -->
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
                        <tbody class="text-sm">
                            @foreach($loans as $loan)
                            @foreach ($loan->items as $index => $item)
                            <tr class="hover">
                                {{-- Tampilkan loan_date hanya di baris pertama --}}
                                @if ($index === 0)
                                <td rowspan="{{ count($loan->items) }}">{{ $loan->loan_date }}</td>
                                @endif

                                <td>{{ $loan->code_loans }}</td>
                                <td>{{ $loan->loaner_name }}</td>
                                <td class="font-semibold">{{ $item->code }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    <span class="badge badge-warning text-xs">{{$loan->status}}</span>
                                </td>
                                <td>{{ $loan->return_date }}</td>

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