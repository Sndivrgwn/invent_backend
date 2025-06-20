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
                <h1 class="text-2xl font-semibold py-4">User Management</h1>
            </div>

            <div class="flex-none">
                {{-- new product --}}
                <button
                    class="bg-[#2563EB] text-white rounded-lg py-2 px-4 mx-5 hover:bg-blue-400 cursor-pointer flex justify-center items-center"
                    onclick="newProduct.showModal()">
                    <div class="gap-2 flex">
                        <i class="fa fa-plus" style="display: flex; justify-content: center; align-items: center;"></i>
                        <span>New Product</span>
                    </div>
                </button>
                <dialog id="newProduct" class="modal">
                    <div class="modal-box">
                        <!-- close button -->
                        <form method="dialog" id="itemForm">
                            <button id="cancel" type="button" onclick="closeModal()"
                                class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                            <h1 class="font-semibold text-2xl mb-4">New Users</h1>
                            <div class="flex gap-5 justify-between text-gray-600 mb-3">
                                <!-- Product -->
                                <div class="w-[100%]">
                                    <h1 class="font-medium">NAME</h1>
                                    <input type="text" placeholder="Type here" class="input"  style="width: 100%;"/>
                                </div>
                            </div>
                            <div class="flex gap-5 justify-between text-gray-600 mb-6">
                                <!-- Brand -->
                                <div class="w-[50%]">
                                    <h1 class="font-medium">EMAIL</h1>
                                    <input type="text" placeholder="Type here" class="input"/>
                                </div>
                                <!-- condition -->
                                <div class="w-[50%]">
                                    <h1 class="font-medium">ROLE</h1>
                                    <div>
                                        <label class="select">
                                            <select id="condition">
                                                <option>Insert Role</option>
                                                <option value="SUPERADMIN">Super Admin</option>
                                                <option value="ADMIN">Admin</option>
                                                <option value="USER">User</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-5 justify-between text-gray-600">

                            <!-- button -->
                            <div class="w-full flex justify-end items-end gap-4">
                                <button id="cancelButton" type="button" onclick="closeModal()"
                                    class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Cancel</button>
                                <button
                                    class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Submit</button>
                            </div>
                        </form>
                    </div>
                </dialog>
            </div>

        </div>

        <div class="list bg-base-100 rounded-box shadow-md">

            <div class="p-4 pb-2 flex">
                <!-- search -->
                <div class="relative w-full hidden md:block mr-4">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                        <span class="sr-only">Search icon</span>
                    </div>
                    <form method="GET" action="{{ route('products') }}" class="relative w-full md:block">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                            <span class="sr-only">Search icon</span>
                        </div>
                        <input type="text" name="search-navbar" value="{{ request('search-navbar') }}"
                            class="block w-full p-2 ps-10 text-sm border border-gray-400 rounded-lg"
                            placeholder="Search...">

                    </form>

                </div>

                <!-- filter -->
                <button class="btn flex justify-center items-center bg-transparent"
                    onclick="filterUsers.showModal()">All Categories <i class="fa fa-filter"
                        style="display: flex; justify-content: center; align-items: center;"></i></button>
                <dialog id="filterUsers" class="modal">
                    <div class="modal-box">
                        <!-- close button -->
                        <form method="dialog">
                            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                        </form>

                        <form id="filterForm">
                            <!-- ROLE -->
                            <div class="mb-4">
                                <h1 class="text-lg font-semibold mb-2">Role</h1>
                                <form class="filter">
                                    <input class="btn btn-square" type="reset" value="×" />
                                    <input class="btn" type="radio" name="frameworks" aria-label="SuperAdmin" />
                                    <input class="btn" type="radio" name="frameworks" aria-label="Admin" />
                                    <input class="btn" type="radio" name="frameworks" aria-label="User" />
                                </form>
                            </div>
                            
                            <!-- STATUS -->
                            <div class="mb-4">
                                <h1 class="text-lg font-semibold mb-2">Status</h1>
                                <form class="filter">
                                    <input class="btn btn-square" type="reset" value="×" />
                                    <input class="btn" type="radio" name="frameworks" aria-label="ACTIVE" />
                                    <input class="btn" type="radio" name="frameworks" aria-label="NOT ACTIVE" />
                                </form>
                            </div>

                            <!-- Apply Button -->
                            <button type="button" class="btn btn-primary mt-4" onclick="applyFilter()">Apply</button>
                        </form>
                    </div>
                </dialog>




            </div>
            <!-- table -->
            <div id="itemTableContainer">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center font-semibold">NAME</th>
                            <th class="text-center font-semibold">EMAIL</th>
                            <th class="text-center font-semibold">ROLE</th>
                            <th class="text-center font-semibold">LAST ACTIVE</th>
                            <th class="text-center font-semibold">ACTION</th>
                        </tr>
                    </thead>
                    <tbody id="itemTableBody">
                        @foreach ($user as $usr)
                        <tr>
                            <td class="text-center">{{ $usr->name }}</td>
                            <td class="text-center">{{ $usr->email }}</td>
                            <td class="text-center">{{ $usr->roles->name }}</td>
                            <td class="text-center">{{ $usr->last_active_at }}</td>
                            <td class="text-center flex justify-center  ">
                                <div class="flex justify-center items-center">
                                    <i class="fa fa-trash fa-lg cursor-pointer !leading-none"></i>
                                    <i class="fa fa-pen-to-square fa-lg cursor-pointer !leading-none"></i>
                                    <i class="fa-regular fa-eye fa-lg cursor-pointer"></i>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

@include('template.footer')