<div class="w-80 min-h-screen bg-white border-r border-gray-400 rounded-tr-2xl rounded-br-2xl shadow px-4 py-6 transition-all duration-300" id="sidebar">
    <div class="flex items-center justify-between mb-6 border-b pb-2 border-gray-400">
        <h2 class="text-xl font-bold text-blue-700 sidebar-text">StockFlowICT</h2>
        <button class="p-2" id="toggle-sidebar">
            <i class="fa fa-chevron-left"></i>
        </button>
    </div>

    <div class="space-y-6">
        <!-- MANAGEMENT -->
        <div>
            <p class="text-gray-500 text-xs font-semibold uppercase mb-2 sidebar-text">Management</p>
            <ul class="space-y-1">
                <li class="rounded-lg active:bg-blue-500">
                    <a href="/dashboard" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-100 text-blue-700 font-medium hover:bg-blue-200 active:bg-blue-300  transition-colors duration-200">
                        <i class="fa fa-home"></i> 
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li class="rounded-lg">
                    <a href="/Products" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-100 active:bg-blue-200 text-gray-700 hover:text-blue-700 active:text-blue-800 transition-colors duration-200">
                        <i class="fa fa-cube"></i> 
                        <span class="sidebar-text">Products</span>
                    </a>
                </li>
                <li class="rounded-lg">
                    <a href="/inventory" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-100 active:bg-blue-200 text-gray-700 hover:text-blue-700 active:text-blue-800 transition-colors duration-200">
                        <i class="fa fa-archive"></i> 
                        <span class="sidebar-text">Inventory</span>
                    </a>
                </li>
                <li class="rounded-lg">
                    <a href="/loan" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-100 active:bg-blue-200 text-gray-700 hover:text-blue-700 active:text-blue-800 transition-colors duration-200">
                        <i class="fa fa-exchange"></i> 
                        <span class="sidebar-text">Loan</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- REPORTS -->
        <div>
            <p class="text-gray-500 text-xs font-semibold uppercase mb-2 sidebar-text">Reports</p>
            <ul class="space-y-1">
                <li class="rounded-lg">
                    <a href="/analytics" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-100 active:bg-blue-200 text-gray-700 hover:text-blue-700 active:text-blue-800 transition-colors duration-200">
                        <i class="fa fa-line-chart"></i> 
                        <span class="sidebar-text">Analytics</span>
                    </a>
                </li>
                <li class="rounded-lg">
                    <a href="/logs" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-100 active:bg-blue-200 text-gray-700 hover:text-blue-700 active:text-blue-800 transition-colors duration-200">
                        <i class="fa fa-history"></i> 
                        <span class="sidebar-text">History</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- ADMINISTRATION -->
        <div>
            <p class="text-gray-500 text-xs font-semibold uppercase mb-2 sidebar-text">Administration</p>
            <ul class="space-y-1">
                <li class="rounded-lg">
                    <a href="/users" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-100 active:bg-blue-200 text-gray-700 hover:text-blue-700 active:text-blue-800 transition-colors duration-200">
                        <i class="fa fa-users"></i> 
                        <span class="sidebar-text">User Management</span>
                    </a>
                </li>
                <li class="rounded-lg">
                    <a href="/settings" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-100 active:bg-blue-200 text-gray-700 hover:text-blue-700 active:text-blue-800 transition-colors duration-200">
                        <i class="fa fa-cog"></i> 
                        <span class="sidebar-text">System Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- LOGOUT -->
    <div class="mt-8">
        <form action="/logout" method="POST">
            @csrf
            <button class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 active:bg-red-800 transition-colors duration-200">
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
    }
</style>

<script>
    document.getElementById('toggle-sidebar').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('sidebar-collapsed');
        
        const isCollapsed = sidebar.classList.contains('sidebar-collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed);
    });

    document.addEventListener('DOMContentLoaded', function() {
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) {
            document.getElementById('sidebar').classList.add('sidebar-collapsed');
        }
    });
</script>