@include('template.head')

<div class="navbar bg-base-100 shadow-sm rounded-b-xl">
    <div class="flex-1">
        <a class="btn btn-ghost text-xl">StockFlowICT</a>
    </div>
    <div class="flex gap-2">
        {{-- search --}}
        {{-- <div class="flex md:order-2">
    <button type="button" data-collapse-toggle="navbar-search" aria-controls="navbar-search" aria-expanded="false" class="md:hidden text-white focus:outline-none rounded-lg text-sm p-2.5 me-1">
      <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
      </svg>
      <span class="sr-only">Search</span>
    </button>
    <div class="relative hidden md:block">
      <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
        </svg>
        <span class="sr-only">Search icon</span>
      </div>
      <input type="text" id="search-navbar" class="block w-full p-2 ps-10 text-sm border rounded-lg" placeholder="Search...">
    </div>
    <button data-collapse-toggle="navbar-search" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm rounded-lg md:hidden focus:outline-none focus:ring-2" aria-controls="navbar-search" aria-expanded="false"> --}}

        {{-- new loan --}}
        <a href="newLoan" class="w-2/3 mx-5">
            <button class="bg-[#2563EB] text-white rounded-lg p-2 px-5 w-full hover:bg-blue-400 cursor-pointer flex justify-center items-center gap-2">
                <i class="fa-regular fa-plus flex justify-center items-center"></i>
                <span>New Loan</span>
            </button>
        </a>

        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                <div class="w-10 rounded-full">
                    <img alt="profile image" src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
                </div>
            </div>
            <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                <li>
                    <a href="/profil" class="justify-between">
                        Profile
                        <span class="badge">New</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

@stack('scripts')
@include('template.footer')
