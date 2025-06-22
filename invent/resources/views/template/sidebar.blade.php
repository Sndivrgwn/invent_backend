@include('template.head')

<!-- MOBILE TOGGLE BUTTON -->
<div class="md:hidden  md:p-4">
    <button id="mobile-menu-button" class="bg-white px-1 rounded text-blue-700 text-xl absolute left-8 top-1/2 transform translate-y-1/2 z-40 text-blue-700 text-xl">
        <i class="fa fa-bars"></i>
    </button>
</div>

<!-- OVERLAY (hidden by default) -->
<div id="mobile-overlay" class="fixed inset-0 bg-black opacity-50 z-40 hidden"></div>

<!-- SIDEBAR -->
<div id="sidebar"
    class="w-80 md:static fixed top-0 left-0 h-full bg-white border-r border-gray-400 rounded-tr-2xl rounded-br-2xl shadow px-4 py-6 transition-all duration-300 z-50 transform md:translate-x-0 -translate-x-full md:block">

    <div class="flex items-center justify-between mb-6 border-b pb-2 border-gray-400">
        <h2 class="text-xl font-bold text-blue-700 sidebar-text">StockFlowICT</h2>
        <button class="p-2 md:hidden" id="close-sidebar">
            <i class="fa fa-times"></i>
        </button>
        <button class="p-2 hidden md:block" id="toggle-sidebar">
            <i class="fa fa-chevron-left"></i>
        </button>
    </div>

    <div class="space-y-6">
        <!-- MANAGEMENT -->
        <div>
            <p class="text-gray-500 text-xs font-semibold uppercase mb-2 sidebar-text">Management</p>
            <ul class="space-y-1">
                <li class="rounded-lg">
                    <a href="/dashboard" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-100 active:bg-blue-200 transition-colors duration-200
    {{ request()->is('dashboard') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:text-blue-700 active:text-blue-800' }}">
                        <i class="fa fa-home" style="display: flex; justify-content: center;"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li class="rounded-lg">
                    <a href="/products" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-100 active:bg-blue-200 transition-colors duration-200
        {{ request()->is('products') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:text-blue-700 active:text-blue-800' }}">
                        <i class="fa fa-cube" style="display: flex; justify-content: center;"></i>
                        <span class="sidebar-text">Products</span>
                    </a>
                </li>
                <li class="rounded-lg">
                    <a href="/inventory" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-100 active:bg-blue-200 transition-colors duration-200
        {{ request()->is('inventory') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:text-blue-700 active:text-blue-800' }}">
                        <i class="fa fa-archive" style="display: flex; justify-content: center;"></i>
                        <span class="sidebar-text">Inventory</span>
                    </a>
                </li>
                <li class="rounded-lg">
                    <a href="/loan" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-100 active:bg-blue-200 transition-colors duration-200
        {{ request()->is('loan') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:text-blue-700 active:text-blue-800' }}">
                        <i class="fa fa-exchange" style="display: flex; justify-content: center;"></i>
                        <span class="sidebar-text">Loan</span>
                    </a>
                </li>
                <li class="rounded-lg">
                    <a href="/manageLoan" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-100 active:bg-blue-200 transition-colors duration-200
        {{ request()->is('manageLoan') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:text-blue-700 active:text-blue-800' }}">
                        <i class="fa fa-screwdriver-wrench" style="display: flex; justify-content: center;"></i>
                        <span class="sidebar-text">Manage Loan</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- REPORTS -->
        <div>
            <p class="text-gray-500 text-xs font-semibold uppercase mb-2 sidebar-text">Reports</p>
            <ul class="space-y-1">
                <li class="rounded-lg">
                    <a href="/analytics" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-100 active:bg-blue-200 transition-colors duration-200
        {{ request()->is('analytics') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:text-blue-700 active:text-blue-800' }}">
                        <i class="fa fa-line-chart" style="display: flex; justify-content: center;"></i>
                        <span class="sidebar-text">Analytics</span>
                    </a>
                </li>
                <li class="rounded-lg">
                    <a href="/history" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-100 active:bg-blue-200 transition-colors duration-200
        {{ request()->is('history') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:text-blue-700 active:text-blue-800' }}">
                        <i class="fa fa-history" style="display: flex; justify-content: center;"></i>
                        <span class="sidebar-text">History</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- ADMINISTRATION -->
        <div>
            <p class="text-gray-500 text-xs font-semibold uppercase mb-2 sidebar-text">Administration</p>
            <ul class="space-y-1">
                @can('isAdmin')
                <li class="rounded-lg">
                    <a href="/users" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-100 active:bg-blue-200 transition-colors duration-200
        {{ request()->is('users') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:text-blue-700 active:text-blue-800' }}">
                        <i class="fa fa-users" style="display: flex; justify-content: center;"></i>
                        <span class="sidebar-text">User Management</span>
                    </a>
                </li>
                @endcan
                <li class="rounded-lg">
                    <a href="/settings" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-100 active:bg-blue-200 transition-colors duration-200
        {{ request()->is('settings') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:text-blue-700 active:text-blue-800' }}">
                        <i class="fa fa-cog" style="display: flex; justify-content: center;"></i>
                        <span class="sidebar-text">System Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- LOGOUT -->
    <div class="mt-3">
        <form method="POST" action="{{ route('logout') }}" id="logout-form">
            @csrf
            <button
                class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 active:bg-red-800 transition-colors duration-200">
                <i class="fa fa-sign-out"></i>
                <span class="sidebar-text">Logout</span>
            </button>
        </form>
    </div>
</div>

<style>
    .sidebar-collapsed {
        width: 4.5rem !important;
    }

    .sidebar-collapsed .sidebar-text {
        display: none;
    }

    .sidebar-collapsed #toggle-sidebar i {
        transform: rotate(180deg);
    }

    .fa {
        font-size: 1rem;
        width: 1.5rem;
        text-align: center;
        height: 1.5rem;
        line-height: 1.5rem;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>

<script>
    // COLLAPSIBLE SIDEBAR (desktop)
    document.getElementById('toggle-sidebar')?.addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('sidebar-collapsed');
        const isCollapsed = sidebar.classList.contains('sidebar-collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed);
    });

    document.addEventListener('DOMContentLoaded', function() {
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed && window.innerWidth >= 768) {
            document.getElementById('sidebar').classList.add('sidebar-collapsed');
        }
    });

    // MOBILE MENU TOGGLE
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobile-overlay');

    document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
    });

    document.getElementById('close-sidebar')?.addEventListener('click', function() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    });

    overlay?.addEventListener('click', function() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    });
</script>

@include('template.footer')