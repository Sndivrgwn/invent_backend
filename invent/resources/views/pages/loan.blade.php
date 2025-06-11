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
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                                <span class="sr-only">Search icon</span>
                            </div>
                            <input type="text" id="search-navbar" class="block w-full p-2 ps-10 text-sm border border-gray-400 rounded-lg" placeholder="Search...">
                        </div>

                        <!-- filter -->
                        <button class="btn flex justify-center items-center bg-transparent" onclick="filterProduct.showModal()">All Status <i class="fa fa-filter" style="display: flex; justify-content: center; align-items: center;"></i></button>
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
                               
                                <tr>
                                    <td class="text-center">SANDI</td>
                                    <td class="text-center">MIKROTIK</td>
                                    <td class="text-center">TANGGAL</td>
                                    <td class="text-center">TANGGAL</td>
                                    <td class="text-center">LOANED</td>
                                    
                                    <td class="text-center">
                                        <i class="fa fa-pen-to-square fa-lg"></i>
                                        <i class="fa-regular fa-eye fa-lg"></i>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
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
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                                <span class="sr-only">Search icon</span>
                            </div>
                            <input type="text" id="search-navbar" class="block w-full p-2 ps-10 text-sm border border-gray-400 rounded-lg" placeholder="Search...">
                        </div>

                        <!-- filter -->
                        <button class="btn flex justify-center items-center bg-transparent" onclick="filterProduct.showModal()">All Status <i class="fa fa-filter" style="display: flex; justify-content: center; align-items: center;"></i></button>
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
                               
                                <tr>
                                    <td class="text-center">SANDI</td>
                                    <td class="text-center">MIKROTIK</td>
                                    <td class="text-center">TANGGAL</td>
                                    <td class="text-center">TANGGAL</td>
                                    <td class="text-center">READY</td>

                                    <td class="text-center">
                                        <i class="fa fa-pen-to-square fa-lg"></i>
                                        <i class="fa-regular fa-eye fa-lg"></i>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                </div>
            </div>
        </div>

        </div>
    </div>
</div>

@include('template.footer')