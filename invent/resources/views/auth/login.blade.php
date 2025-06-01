<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- link  -->
    @vite('resources/css/app.css')
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> -->
    <script src="https://kit.fontawesome.com/6477a0b2af.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="login_page min-w-screen min-h-screen bg-gradient-to-b from-blue-100 to-white flex flex-col items-center justify-center lg:flex-row p-4">
        <div class="login_image w-3/4 md:w-2/4 lg:w-1/2">
            <img src={{ asset('image/iventory_img_login.png') }} alt="login image" class="mx-auto" />
        </div>
        <div class="login_card rounded-2xl bg-white items-center p-5 m-2 border border-[#64748B] w-full max-w-md lg:ml-10">
            <p class="font-bold text-[32px] lg:text-left">StockFlowICT</p>
            <p class="text-[#64748B]">Inventory Management System</p>
            <div class="login_form  ">
                <form method="POST" class="flex flex-col gap-3 mt-5" action="{{ route('login') }}">
                    @csrf

                    <label htmlFor="username">Email / Username</label>
                    <input class="border-0 border-b-2 border-gray-400 focus:outline-none" name="email" type="text" placeholder='Enter your email or username' />
                    <label htmlFor="password">Password</label>
                    <input class="border-0 border-b-2 border-gray-400 focus:outline-none" name="password" type="password" placeholder='Enter your password' />
                    <fieldset class='fieldset bg-transparent border-none rounded-box flex w-64 border p-4 '>
                        <label class='label text-[#000000] text-[14px]'>
                            <input type="checkbox" class='checkbox' />
                            Remember me
                        </label>
                    </fieldset>
                    <button class="!bg-[#2563EB] text-white py-2 rounded-md hover:bg-blue-700 transition" type="submit">Login</button>
                    @error('email')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                    @if (session('message'))
                    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                        {{ session('message') }}
                    </div>
                    @endif

                </form>

                <div class="flex mt-3 justify-center text-[12px] text-[#64748B]">
                    <span>&copy; itcsmkn5bdg</span>
                </div>
            </div>
        </div>
    </div>
    <!-- script tailwind -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</body>

</html>
