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
                <button
                    class="bg-[#ffffff] rounded-lg py-2 px-4 mx-5 hover:bg-blue-400 cursor-pointer flex justify-center items-center">
                    <div class="gap-2 flex">
                        <i class="fa fa-download"
                            style="display: flex; justify-content: center; align-items: center;"></i>
                        <a href="{{ route('analytics.export') }}">Export Report</a>
                    </div>
                </button>
            </div>
        </div>

        <div class="list bg-base-100 rounded-box shadow-md">

            <div class="p-4 pb-2 flex">
                <!-- search -->
                <div class="relative w-full hidden md:block mr-4">
                    <p class="font-medium text-xl ms-5">Category Overview</p>
                </div>

                <!-- filter -->
                {{-- <button class="btn flex justify-center items-center bg-transparent"
                    onclick="filterProduct.showModal()">All Categories <i class="fa fa-filter"
                        style="display: flex; justify-content: center; align-items: center;"></i></button>
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
            @foreach($categories as $category)
            <div class="mb-6 d-flex flex-col gap-4">
                <h2 class="text-lg ms-12 font-bold mb-2">{{ $category->name }}</h2>
                <table class="table w-full bg-white rounded shadow-md">
                    <thead>
                        <tr>
                            <th class="text-center">TYPE</th>
                            <th class="text-center">QUANTITY</th>
                            <th class="text-center">AVAILABLE</th>
                            <th class="text-center">LOANED</th>
                            <th class="text-center">LOW STOCK</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($category->type_summaries as $type)
                        <tr>
                            <td class="text-center">{{ $type['type'] }}</td>
                            <td class="text-center">{{ $type['quantity'] }}</td>
                            <td class="text-center">{{ $type['available'] }}</td>
                            <td class="text-center">{{ $type['loaned'] }}</td>
                            <td class="text-center">{{ $type['low_stock'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endforeach


        </div>
    </div>
</div>

@include('template.footer')