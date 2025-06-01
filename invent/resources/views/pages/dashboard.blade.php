<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://kit.fontawesome.com/your-kit.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div>
            @include('components.sidebar')
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6 bg-gray-100 overflow-y-auto">
            <h1 class="text-2xl font-semibold mb-4">Dashboard</h1>
            <p>Welcome to the admin dashboard!</p>
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
</body>

</html>