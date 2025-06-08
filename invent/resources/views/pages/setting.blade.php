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
                <h1 class="text-2xl font-semibold py-4">System Settings</h1>
            </div>

        </div>

        <div class="list bg-base-100 rounded-box shadow-md">

            <div class="tabs tabs-border p-8">
                <label class="tab text-blue-700 px-10! pb-2! mx-0!">
                    <input type="radio" name="my_tabs_4" checked="checked" />
                    <i class="fa fa-cog mr-1" style="display: flex; justify-content: center;"></i>
                    System Settings
                </label>
                <div class="tab-content bg-base-100 py-6 mx-5.5! mt-[-2px]!" style="border-top: 1px solid lightgray;">
                    <div class="mb-4">
                        <h1 class="font-medium text-lg mb-1">Time Zone</h1>
                        <label class="select" style="width: 100%;">
                            <span class="label">Zone</span>
                            <select>
                                <option>Asia/Jakarta</option>
                                <option>Lainnya</option>
                            </select>
                        </label>
                    </div>
                    <div>
                        <h1 class="font-medium text-lg mb-1">Fiscal Year Start</h1>
                        <label class="input" style="width: 100%;">
                            <span class="label">Publish date</span>
                            <input type="date" />
                        </label>
                    </div>
                </div>

                <label class="tab border-0 text-blue-700 px-10! pb-2! mx-0!">
                    <input type="radio" name="my_tabs_4" />
                    <i class="fa-solid fa-palette mr-2" style="display: flex; justify-content: center;"></i>
                    Appearance
                </label>
                <div class="tab-content bg-base-100 py-6 mx-5.5! mt-[-2px]!" style="border-top: 1px solid lightgray; ">
                    <div class="mb-4">
                        <h1 class="font-medium text-lg mb-1">Theme</h1>
                        <label class="select" style="width: 100%;">
                            <span class="label">Theme</span>
                            <select>
                                <option>Default</option>
                                <option>Dark</option>
                                <option>Retro</option>
                                <option>Aqua</option>
                                <option>Valentine</option>
                            </select>
                        </label>
                    </div>
                    <div class="mb-4">
                        <h1 class="font-medium text-lg mb-1">Accent Color</h1>
                        <div class="p-3 border border-gray-400 rounded w-min">
                            <label for="gradient-modal" class="cursor-pointer">
                                <div
                                    id="gradientBox"
                                    class="w-30 h-10 rounded bg-gradient-to-r from-[#C9E3FF] to-white"></div>
                            </label>
                        </div>

                        <!-- DaisyUI Modal -->
                        <input type="checkbox" id="gradient-modal" class="modal-toggle" />
                        <div class="modal" role="dialog">
                            <div class="modal-box">
                                <h3 class="font-bold text-lg mb-4">Change Color Gradient</h3>
                                <div class="flex flex-col gap-4">
                                    <div>
                                        <label class="label">
                                            <span class="label-text w-26">First Color</span>
                                        </label>
                                        <input type="color" id="colorFrom" value="#3b82f6" class="input input-bordered w-full h-12" />
                                    </div>
                                    <div>
                                        <label class="label">
                                            <span class="label-text w-26">Second Color</span>
                                        </label>
                                        <input type="color" id="colorTo" value="#ffffff" class="input input-bordered w-full h-12" />
                                    </div>
                                </div>
                                <div class="modal-action">
                                    <label for="gradient-modal" class="btn btn-primary">Close</label>
                                </div>
                            </div>
                        </div>

                        <script>
                            const gradientBox = document.getElementById('gradientBox');
                            const colorFrom = document.getElementById('colorFrom');
                            const colorTo = document.getElementById('colorTo');

                            function updateGradient() {
                                gradientBox.style.background = `linear-gradient(to right, ${colorFrom.value}, ${colorTo.value})`;
                            }

                            colorFrom.addEventListener('input', updateGradient);
                            colorTo.addEventListener('input', updateGradient);
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('template.footer')