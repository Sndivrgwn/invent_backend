@include('template.head')

    <div class="flex h-screen">
        <!-- Sidebar -->
        <div>
            @include('template.sidebar')
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6 bg-gray-100 overflow-y-auto">
        
         {{-- navbar --}}
            <div>
            @include('template.navbar')
            </div>

            <h1 class="text-2xl font-semibold mb-4">Dashboard</h1>
            <p>Welcome to the admin dashboard! {{ auth()->user()->name }}!</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-2">Total Products</h2>
                    <p class="text-2xl font-bold">150</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-2">Total Inventory</h2>
                    <p class="text-2xl font-bold">300</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-2">Total Loans</h2>
                    <p class="text-2xl font-bold">50</p>
                </div>
            </div>
        </div>
    </div>

@include('template.footer')