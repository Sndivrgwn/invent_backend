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
                <h1 class="text-2xl font-semibold py-4">Profil Settings</h1>
            </div>

        </div>

        <div class="list bg-base-100 rounded-box shadow-md p-10">

            <div class="flex gap-4">
                <div class="w-50 rounded-full me-5">
                    <img
                        alt="profile image"
                        src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp"
                        class="rounded" />
                </div>
                <div class="flex w-full">
                    <div class="flex flex-col justify-evenly pe-20">
                        <div class="flex justify-between w-full">
                            <div class="flex">
                                <div class="flex justify-center align-middle p-4.5 ">
                                    <div class="item-center">
                                        <i class="fa-solid fa-user fa-2xl"></i>
                                    </div>
                                </div>
                                <div>
                                    <h1 class="text-xl font-semibold">Name</h1>
                                    <h1 class="text-lg font-medium">{{$user->name}}</h1>
                                </div>
                            </div>
                            <div class="flex justify-center align-middle p-4.5 ">
                                <div class="item-center">
                                    <i class="fa-solid fa-pen-to-square fa-2xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="flex me-10">
                                <div class="flex justify-center align-middle p-4.5 ">
                                    <div class="item-center">
                                        <i class="fa-solid fa-envelope fa-2xl"></i>
                                    </div>
                                </div>
                                <div>
                                    <h1 class="text-xl font-semibold">Email</h1>
                                    <h1 class="text-lg font-medium">{{$user->email}}</h1>
                                </div>
                            </div>
                            <div class="flex justify-center align-middle p-4.5 ">
                                <div class="item-center">
                                    <i class="fa-solid fa-pen-to-square fa-2xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col justify-evenly ">
                        <div class="flex">
                            <div class="flex justify-center align-middle p-4.5 ">
                                <div class="item-center">
                                    <i class="fa-solid fa-right-left fa-2xl"></i>
                                </div>
                            </div>
                            <div>
                                <h1 class="text-xl font-semibold">Jumlah Loan</h1>
                                <h1 class="text-lg font-medium">{{$totalLoans}}</h1>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="flex justify-center align-middle p-4.5 ">
                                <div class="item-center">
                                    <i class="fa-solid fa-rotate-left fa-2xl"></i>
                                </div>
                            </div>
                            <div>
                                <h1 class="text-xl font-semibold">Jumlah Return</h1>
                                <h1 class="text-lg font-medium">{{$totalReturns}}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 w-max">
                <fieldset class="fieldset">
                    <div class="flex justify-between">
                        <p class="text-xl font-medium">Edit Profil</p>
                        <label class="label">Max size 2MB</label>
                    </div>
                    <input type="file" class="file-input" />
                </fieldset>
            </div>
        </div>
    </div>
</div>

@include('template.footer')