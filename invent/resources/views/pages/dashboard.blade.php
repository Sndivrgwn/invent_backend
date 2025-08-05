@include('template.head')

<div class="flex flex-col h-screen bg-gradient-to-b from-blue-100 to-white md:flex-row">
    <!-- Sidebar -->
    <div class="w-full md:w-auto relative">
        @include('template.sidebar')
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto px-4 md:px-6">
        {{-- navbar --}}
        <div>
            @include('template.navbar')
        </div>

        <div class="flex flex-col gap-6 pt-6">
            <h1 class="text-2xl font-semibold">Dasbor</h1>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Total Products -->
                <div class="bg-white p-4 rounded-lg shadow flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-medium text-gray-400 mb-1">Total Produk</h2>
                        <p class="text-2xl font-semibold">{{ $totalItems }}</p>
                        <p class="text-sm text-gray-400 mt-1">Jumlah produk yang ada di sistem.</p>
                    </div>
                    <div class="bg-blue-500 bg-opacity-25 text-white p-4 rounded-full flex items-center justify-center">
                        <i class="fas fa-cube text-blue-500"></i>
                    </div>
                </div>
                
                <!-- Total Categories -->
                <div class="bg-white p-4 rounded-lg shadow flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-medium text-gray-400 mb-1">Total Kategori</h2>
                        <p class="text-2xl font-semibold">{{ $totalCategories }}</p>
                        <p class="text-sm text-gray-400 mt-1">Kategori inventaris yang direkam.</p>
                    </div>
                    <div class="bg-green-500 bg-opacity-25 text-white p-4 rounded-full flex items-center justify-center">
                        <i class="fas fa-boxes text-green-500"></i>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-medium text-gray-400 mb-1">Total Rak Inventaris</h2>
                        <p class="text-2xl font-semibold">{{ $totalInventory }}</p>
                        <p class="text-sm text-gray-400 mt-1">Rak inventaris yang tersedia</p>
                    </div>
                    <div class="bg-red-500 bg-opacity-25 text-white p-4 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-red-500"></i>
                    </div>
                </div>
                
                <!-- Total Loans -->
                <div class="bg-white p-4 rounded-lg shadow flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-medium text-gray-400 mb-1">Total Peminjaman</h2>
                        <p class="text-2xl font-semibold">{{ $totalLoans }}</p>
                        <p class="text-sm text-gray-400 mt-1">Total transaksi peminjaman.</p>
                    </div>
                    <div class="bg-purple-500 bg-opacity-25 text-white p-4 rounded-full flex items-center justify-center">
                        <i class="fas fa-exchange-alt text-purple-500"></i>
                    </div>
                </div>
                
                <!-- Active Loans -->
                <div class="bg-white p-4 rounded-lg shadow flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-medium text-gray-400 mb-1">Peminjaman Aktif</h2>
                        <p class="text-2xl font-semibold">{{ $totalActiveLoans }}</p>
                        <p class="text-sm text-gray-400 mt-1">Peminjaman yang belum dikembalikan.</p>
                    </div>
                    <div class="bg-yellow-500 bg-opacity-25 text-white p-4 rounded-full flex items-center justify-center">
                        <i class="fas fa-handshake text-yellow-500"></i>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-lg shadow flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-medium text-gray-400 mb-1">Peminjaman Dikembalikan</h2>
                        <p class="text-2xl font-semibold">{{ $returnedLoans }}</p>
                        <p class="text-sm text-gray-400 mt-1">Peminjaman yang sudah selesai.</p>
                    </div>
                    <div class="bg-teal-500 bg-opacity-25 text-white p-4 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-teal-500"></i>
                    </div>
                </div>
            </div>
            
            <!-- Charts and Tables Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Loan Statistics Chart -->
                <div class="bg-white p-4 rounded-xl shadow-md lg:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Statistik Peminjaman (12 Bulan Terakhir)</h2>
                    <div class="h-64">
                        <canvas id="loansChart"></canvas>
                    </div>
                </div>
                
                <!-- Item Conditions -->
                <div class="bg-white p-4 rounded-xl shadow-md">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Kondisi Barang</h2>
                    <div class="h-64">
                        <canvas id="conditionsChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Latest Loans Table -->
            <div class="bg-white p-4 rounded-xl shadow-md">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
                    <h2 class="text-lg font-semibold text-gray-700">Pinjaman Terbaru</h2>
                    
                    
                </div>
                
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead class="text-gray-500 text-sm font-semibold border-b w-full">
                            <tr>
                                <th class="pb-2 text-left">Tanggal</th>
                                <th class="pb-2 text-left">Peminjam</th>
                                <th class="pb-2 text-left">Barang</th>
                                <th class="pb-2 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($latestLoans as $loan)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</td>
                                <td>{{ $loan->loaner_name }}</td>
                                <td>
                                    @foreach($loan->items as $item)
                                    <span class="inline-block bg-gray-100 rounded px-2 py-1 text-xs mr-1 mb-1">
                                        {{ $item->name }}
                                    </span>
                                    @endforeach
                                </td>
                                <td>
                                    <span class="px-2 py-1 rounded-full text-xs 
                                          {{ $loan->status === 'dipinjam' ? 'bg-yellow-100 text-yellow-800' : 
                                             ($loan->status === 'dikembalikan' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ $loan->status === 'dipinjam' ? 'Dipinjam' : 
                                           ($loan->status === 'dikembalikan' ? 'Dikembalikan' : 'Menunggu') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500">Tidak ada pinjaman</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Most Loaned Items -->
            <div class="bg-white p-4 rounded-xl shadow-md">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Barang Paling Sering Dipinjam</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    @foreach($mostLoanedItems as $item)
                    <div class="border rounded-lg p-3 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-medium">{{ $item->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $item->category->name ?? 'Uncategorized' }}</p>
                            </div>
                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                {{ $item->loans_count }}x
                            </span>
                        </div>
                        <div class="mt-2 text-sm">
                            <span class="inline-block px-2 py-1 rounded 
                                  {{ $item->condition === 'GOOD' ? 'bg-green-100 text-green-800' : 
                                     ($item->condition === 'NOT GOOD' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ $item->condition === 'GOOD' ? 'Baik' : 
                                   ($item->condition === 'NOT GOOD' ? 'Rusak' : 'Perlu Perbaikan') }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Loans Per Month Chart
    const loansCtx = document.getElementById('loansChart').getContext('2d');
    const loansChart = new Chart(loansCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($loansPerMonth->pluck('month')) !!},
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: {!! json_encode($loansPerMonth->pluck('total')) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Item Conditions Chart
    const conditionsCtx = document.getElementById('conditionsChart').getContext('2d');
    const conditionsChart = new Chart(conditionsCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($itemConditions->pluck('condition')) !!},
            datasets: [{
                data: {!! json_encode($itemConditions->pluck('total')) !!},
                backgroundColor: [
                    'rgba(16, 185, 129, 0.7)', // good - green
                    'rgba(245, 158, 11, 0.7)',  // needs repair - yellow
                    'rgba(239, 68, 68, 0.7)'     // damaged - red
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>

@stack('scripts')
@include('template.footer')