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

        <h1 class="text-2xl font-semibold py-4">Dashboard</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white p-4 rounded-lg shadow flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-medium text-gray-400 mb-2 ">Total Products</h2>
                    <p class="text-2xl font-semibold">{{ $totalItems }}</p>
                    <p class="text-sm font-normal text-gray-400 mb-2 "> <span class="inline-block -rotate-90"> > </span> <span>12% this month</span> </p>
                </div>
                <div class="bg-blue-500 bg-opacity-25 text-white p-3 rounded-full flex items-center justify-center"> 
                    <i class="fa fa-cube bg-blue-500"></i>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold mb-2">Total Inventory</h2>
                <p class="text-2xl font-bold">{{ $totalCategories }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold mb-2">Total Loans</h2>
                <p class="text-2xl font-bold">{{ $totalLoans }}</p>
            </div>
        </div>
    </div>
</div>

@include('template.footer')