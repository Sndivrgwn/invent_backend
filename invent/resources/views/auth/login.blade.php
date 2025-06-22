@include('template.head')

<div class="login_page min-w-screen min-h-screen bg-gradient-to-b from-blue-100 to-white flex flex-col items-center justify-center lg:flex-row p-4">
    <div class="login_image w-3/4 md:w-2/4 lg:w-1/2">
        <img src="{{ asset('image/iventory_img_login.png') }}" alt="login image" class="mx-auto" />
    </div>
    <div class="login_card rounded-2xl bg-white items-center p-5 m-2 border border-[#64748B] w-full max-w-md lg:ml-10">
        <p class="font-bold text-[32px] lg:text-left">StockFlowICT</p>
        <p class="text-[#64748B]">Inventory Management System</p>
        
        <!-- Toast Notification Container -->
        <div id="toast-container" class="fixed top-5 right-5 z-50 w-full max-w-xs"></div>
        
        <div class="login_form">
            <form method="POST" class="flex flex-col gap-3 mt-5" action="{{ route('login') }}">
                @csrf

                <label for="email">Email</label>
                <input class="border-0 border-b-2 border-gray-400 focus:outline-none @error('email') border-red-500 @enderror" 
                       name="email" 
                       type="email"
                       value="{{ old('email') }}" 
                       placeholder='Enter your email' 
                       required
                       autofocus />
                
                @error('email')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <label for="password">Password</label>
                <input class="border-0 border-b-2 border-gray-400 focus:outline-none @error('password') border-red-500 @enderror" 
                       name="password" 
                       type="password"
                       placeholder='Enter your password' 
                       required />
                
                @error('password')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <fieldset class='fieldset bg-transparent border-none rounded-box flex w-64 border p-4'>
                    <label class='label text-[#000000] text-[14px] cursor-pointer flex items-center'>
                        <input type="checkbox" class='checkbox mr-2' name="remember" id="remember" />
                        Remember me
                    </label>
                </fieldset>

                <button class="bg-[#2563EB] text-white py-2 rounded-md hover:bg-blue-700 transition duration-300" 
                        type="submit">
                    Login
                </button>

                @if (Route::has('password.request'))
                <div class="text-center mt-2">
                    <a href="{{ route('password.request') }}" class="text-[#2563EB] text-sm hover:underline">
                        Forgot your password?
                    </a>
                </div>
                @endif
            </form>

            <div class="flex mt-3 justify-center text-[12px] text-[#64748B]">
                <span>&copy; itcsmkn5bdg</span>
            </div>
        </div>
    </div>
</div>


<!-- Toast Notification Script -->
@include('template.footer')
