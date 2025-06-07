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
                <h1 class="text-2xl font-semibold py-4">Inventory Management</h1>
            </div>
            <div class="flex-none">
                {{-- new product --}}
                <button class="bg-[#2563EB] text-white rounded-lg py-2 px-4 mx-5 hover:bg-blue-400 cursor-pointer flex justify-center items-center">
                    <div class="gap-2 flex">
                        <i class="fa fa-plus" style="display: flex; justify-content: center; align-items: center;"></i>
                        <span>New Inventory</span>
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
                    <input type="text" id="search-navbar" class="block w-full p-2 ps-10 text-sm border border-gray-400 rounded-lg" placeholder="Search...">
                </div>

                <!-- filter -->
                <button class="btn flex justify-center items-center bg-transparent" onclick="filterProduct.showModal()">All Location <i class="fa fa-filter" style="display: flex; justify-content: center; align-items: center;"></i></button>
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
            <div class="racks grid grid-cols-2 flex justify-center mx-auto w-3/4 gap-5 py-5">
                {{-- rack 1 --}}
                <div class="rack1 card border border-[#64748B]" style="border-radius: 20px">
                    <div class="flex place-content-between p-5">
                        <div>
                            <p class="text-[#64748B]">rack 1</p>
                            <p class="text-[#000000] text-3xl font-bold">75</p>
                        </div>
                        <i class="fa fa-server bg-[rgba(37,99,235,0.25)] rounded-3xl text-blue-500 w-5" style="width: 40px; height:40px;"></i>
                    </div>
                    <div class="rack-category flex gap-3 text-[#2563EB] justify-center items-center py-4">
                        <p class="bg-[#2563EB40] rounded-sm px-1">Mikrotik</p>
                        <p class="bg-[#2563EB40] rounded-sm px-1">Access Point</p>
                        <p class="bg-[#2563EB40] rounded-sm px-1">Router</p>
                    </div>
                </div>
                {{-- rack 2 --}}
                <div class="rack2 card border border-[#64748B]" style="border-radius: 20px">
                    <div class="flex place-content-between p-5">
                        <div>
                            <p class="text-[#64748B]">rack 2</p>
                            <p class="text-[#000000] text-3xl font-bold">75</p>
                        </div>
                        <i class="fa fa-server bg-[rgba(37,99,235,0.25)] rounded-3xl text-blue-500"></i>
                    </div>
                    <div class="rack-category flex gap-3 text-[#2563EB] justify-center items-center py-4">
                        <p class="bg-[#2563EB40] rounded-sm px-1">Mikrotik</p>
                        <p class="bg-[#2563EB40] rounded-sm px-1">Access Point</p>
                        <p class="bg-[#2563EB40] rounded-sm px-1">Router</p>
                    </div>
                </div>
                {{-- rack 3 --}}
                <div class="rack3 card border border-[#64748B]" style="border-radius: 20px">
                    <div class="flex place-content-between p-5">
                        <div>
                            <p class="text-[#64748B]">rack 3</p>
                            <p class="text-[#000000] text-3xl font-bold">75</p>
                        </div>
                        <i class="fa fa-server bg-[rgba(37,99,235,0.25)] rounded-3xl text-blue-500"></i>
                    </div>
                    <div class="rack-category flex gap-3 text-[#2563EB] justify-center items-center py-4">
                        <p class="bg-[#2563EB40] rounded-sm px-1">Mikrotik</p>
                        <p class="bg-[#2563EB40] rounded-sm px-1">Access Point</p>
                        <p class="bg-[#2563EB40] rounded-sm px-1">Router</p>
                    </div>
                </div>
                {{-- rack 4 --}}
                <div class="rack4 card border border-[#64748B]" style="border-radius: 20px">
                    <div class="flex place-content-between p-5">
                        <div>
                            <p class="text-[#64748B]">rack 4</p>
                            <p class="text-[#000000] text-3xl font-bold">75</p>
                        </div>
                        <i class="fa fa-server bg-[rgba(37,99,235,0.25)] rounded-3xl text-blue-500"></i>
                    </div>
                    <div class="rack-category flex gap-3 text-[#2563EB] justify-center items-center py-4">
                        <p class="bg-[#2563EB40] rounded-sm px-1">Mikrotik</p>
                        <p class="bg-[#2563EB40] rounded-sm px-1">Access Point</p>
                        <p class="bg-[#2563EB40] rounded-sm px-1">Router</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@include('template.footer')