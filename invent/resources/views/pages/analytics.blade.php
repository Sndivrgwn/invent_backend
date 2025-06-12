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
                <h1 class="text-2xl font-semibold py-4">Inventory Analytics </h1>
            </div>
            <div class="flex-none">
                {{-- new product --}}
                <button class="bg-[#ffffff] rounded-lg py-2 px-4 mx-5 hover:bg-blue-400 cursor-pointer flex justify-center items-center">
                    <div class="gap-2 flex">
                        <i class="fa fa-download" style="display: flex; justify-content: center; align-items: center;"></i>
                        <a href="{{ route('analytics.export') }}">Export Report</a>
                    </div>
                </button>
            </div>
        </div>

        <div class="list bg-base-100 rounded-box shadow-md">

            <div class="p-4 pb-2 flex">
                <!-- search -->
                <div class="relative w-full hidden md:block mr-4">
                    <p class="font-medium text-xl ms-12">Category Overview</p>
                </div>

                <!-- filter --> 
                {{-- <button class="btn flex justify-center items-center bg-transparent" onclick="filterProduct.showModal()">All Categories <i class="fa fa-filter" style="display: flex; justify-content: center; align-items: center;"></i></button>
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
                </dialog> --}}

            </div>
            <!-- table -->
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center font-semibold">CATEGORY</th>
                        <th class="text-center font-semibold">QUANTITY</th>
                        <th class="text-center font-semibold">AVAILABLE</th>
                        <th class="text-center font-semibold">LOANED</th>
                        <th class="text-center font-semibold">LOW STOCK</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td class="text-center">{{ $category->name }}</td>
                            <td class="text-center">{{ $category->items_count }}</td>
                            <td class="text-center">{{ $category->available_count }}</td>
                            <td class="text-center">{{ $category->loan_count }}</td>
                            <td class="text-center">{{ $category->low_stock }}</td>
                        </tr>
                        
                    @endforeach
                    
            </table>

        </div>
    </div>
</div>

@include('template.footer')