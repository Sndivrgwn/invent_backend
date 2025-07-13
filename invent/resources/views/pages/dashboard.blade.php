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

        <div class="flex flex-col gap-6 pt-6">
            <h1 class="text-2xl font-semibold">Dashboard</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Total Products -->
                <div class="bg-white p-4 rounded-lg shadow flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-medium text-gray-400 mb-1">Jumlah Produk</h2>
                        <p class="text-2xl font-semibold">{{ $totalItems }}</p>
                        <p class="text-sm text-gray-400 mt-1">Jumlah produk yang ada di sistem.</p>
                    </div>
                    <div class="bg-blue-500 bg-opacity-25 text-white p-4 rounded-full flex items-center justify-center">
                        <i class="fa fa-cube bg-blue-500" style="display: flex; justify-content: center;"></i>
                    </div>
                </div>
                <!-- Total Inventory -->
                <div class="bg-white p-4 rounded-lg shadow flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-medium text-gray-400 mb-1">jumlah Inventori</h2>
                        <p class="text-2xl font-semibold">{{ $totalCategories }}</p>
                        <p class="text-sm text-gray-400 mt-1">Kategori atau jenis inventaris yang direkam.</p>
                    </div>
                    <div class="bg-green-500 bg-opacity-25 text-white p-4 rounded-full flex items-center justify-center">
                        <i class="fa fa-boxes bg-green-500" style="display: flex; justify-content: center;"></i>
                    </div>
                </div>
                <!-- Total Loans -->
                <div class="bg-white p-4 rounded-lg shadow flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-medium text-gray-400 mb-1">Total pinjaman</h2>
                        <p class="text-2xl font-semibold">{{ $totalLoanedItems }}</p>
                        <p class="text-sm text-gray-400 mt-1">Item yang saat ini dipinjamkan.</p>
                    </div>
                    <div class="bg-yellow-500 bg-opacity-25 text-white p-4 rounded-full flex items-center justify-center">
                        <i class="fa fa-handshake bg-yellow-500" style="display: flex; justify-content: center;"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-md">
                <!-- Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
                    <h2 class="text-lg font-semibold text-gray-700 w-full md:w-auto">Pinjaman terbaru</h2>

                    <!-- Controls -->
                    <div class="flex flex-col md:flex-row items-stretch md:items-center gap-2 w-full md:w-auto">
                        <!-- Search -->
                        <div class="relative w-full md:block">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                                <span class="sr-only">Search icon</span>
                            </div>
                            <form method="GET" action="{{ route('dashboard') }}" class="relative w-full md:block">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                    <span class="sr-only">Search icon</span>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" class="block w-full p-2 ps-10 text-sm border border-gray-400 rounded-lg" placeholder="Cari...">
                            </form>

                        </div>



                        <!-- Export Button -->

                    </div>
                </div>


                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead class="text-gray-500 text-sm font-semibold border-b">
                            <tr>
                                <th>
                                    <a href="{{ route('dashboard', ['sortBy' => 'loan_date', 'sortDir' => $sortBy === 'loan_date' && $sortDir === 'asc' ? 'desc' : 'asc', 'search' => $search]) }}">
                                        Tanggal
                                        @if ($sortBy === 'loan_date')
                                        {{ $sortDir === 'asc' ? '↑' : '↓' }}
                                        @endif
                                    </a>
                                </th>

                                <th>
                                    <a href="{{ route('dashboard', ['sortBy' => 'loaner_name', 'sortDir' => $sortBy === 'loaner_name' && $sortDir === 'asc' ? 'desc' : 'asc', 'search' => $search]) }}">
                                        NAMA PEMINJAM
                                        @if ($sortBy === 'loaner_name')
                                        {{ $sortDir === 'asc' ? '↑' : '↓' }}
                                        @endif
                                    </a>
                                </th>

                                <th>NOMOR SERIAL</th>
                                <th>PRODUK</th>
                                <th>KONDISI</th>

                                <th>
                                    STATUS
                                </th>

                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($loans as $loan)
                            @foreach ($loan->items as $index => $item)
                            <tr class="hover">
                                {{-- Tampilkan loan_date hanya di baris pertama --}}
                                @if ($index === 0)
                                <td rowspan="{{ count($loan->items) }}">{{ $loan->loan_date }}</td>
                                @endif

                                <td>{{ $loan->loaner_name }}</td>
                                <td class="font-semibold">{{ $item->code }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->condition }}</td>
                                <td>
                                    <div class="badge badge-soft p-5 {{ $item->status === 'READY' ? 'badge-success' : 'badge-error' }}">
                                        {{ $item->status }}
                                    </div>
                                </td>

                                {{-- Tampilkan action hanya di baris pertama --}}
                                @if ($index === 0)

                                @endif
                            </tr>
                            @endforeach
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-gray-500">Tidak ada pinjaman</td>
                            </tr>
                            @endforelse
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
                        <a href="{{ $url }}" class="join-item btn {{ $loans->currentPage() == $page ? 'btn-primary' : '' }}">
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

<script>
    // delete
    let deleteTargetId = null;

    async function deleteItem(id) {
        deleteTargetId = id;
        document.getElementById("confirmDeleteDialog").showModal();
    }

    async function confirmDelete() {
        if (!deleteTargetId) return;

        const res = await fetch(`/api/items/${deleteTargetId}`, {
            method: 'DELETE'
            , headers: {
                'Accept': 'application/json'
                , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        if (res.ok) {
            alert('Item deleted');
            window.location.reload();
        } else {
            const data = await res.json();
            alert('Error bray cek console');
            console.log(data.message || res.statusText);
        }

        deleteTargetId = null;
        closeDeleteDialog();
    }

    function closeDeleteDialog() {
        document.getElementById("confirmDeleteDialog").close();
        deleteTargetId = null;
    }
    // edit product
    function closeEditModal() {
        document.getElementById('editProduct').close();
    }

    document.getElementById("editForm").addEventListener("submit", function(e) {
        e.preventDefault();

        const payload = {
            product: document.getElementById("edit_product").value
            , rack: document.getElementById("edit_rack").value
            , brand: document.getElementById("edit_brand").value
            , condition: document.getElementById("edit_condition").value
            , type: document.getElementById("edit_type").value
            , status: document.getElementById("edit_status").value
            , serial: document.getElementById("edit_serial").value
            , description: document.getElementById("edit_description").value
        , };

        console.log("Edit payload:", payload);
        alert("Simulasi update berhasil. Kirim ke API sesuai kebutuhan.");

        document.getElementById("editForm").reset();
        closeEditModal();
    });

</script>

@stack('scripts')
@include('template.footer')
