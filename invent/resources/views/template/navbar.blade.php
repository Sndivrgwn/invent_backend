@include('template.head')

<div class="navbar bg-base-100 shadow-sm rounded-b-xl relative mt-0">
    <div class="flex-1 hidden md:inline-flex">
        <a class="btn btn-ghost text-xl">StockFlowICT</a>
    </div>
    <div class="flex w-full place-content-end md:w-2/5 lg:w-1/4">

        {{-- new loan --}}
        <a href="newLoan" class="w-1/2 md:w-2/3 mx-3">
            <button class="bg-[#2563EB] text-white rounded-lg p-2 px-5 w-full hover:bg-blue-400 cursor-pointer flex justify-center items-center gap-2">
                <i class="fa-regular fa-plus flex justify-center items-center"></i>
                <span>Pinjam baru</span>
            </button>
        </a>

        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                <div class="w-10 rounded-full">
                    <img  alt="profile image" src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp' }}" />
                </div>
            </div>
            <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                <li>
                    <a href="/profil" class="justify-between">
                        Profil
                        <span class="badge">Baru</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

@stack('scripts')
@include('template.footer')
