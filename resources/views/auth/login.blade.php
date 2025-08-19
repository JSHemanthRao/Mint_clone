<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-100 to-purple-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white p-10 rounded-2xl shadow-xl border border-gray-200">
        <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Login to Your Account</h2>

        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-gray-700 font-medium mb-1" for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="example@mail.com"
                    value="{{ old('email') }}"
                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition duration-200
                    @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="relative">
                <label class="block text-gray-700 font-medium mb-1" for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="********"
                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition duration-200
                    @error('password') border-red-500 @enderror">
                <button type="button" onclick="togglePassword()" 
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 font-medium text-sm">
                    Show
                </button>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg shadow-md transition duration-200">
                Login
            </button>
        </form>

        <p class="mt-6 text-center text-gray-600">
            Don't have an account? 
            <a href="{{ route('register.form') }}" class="text-blue-600 font-semibold hover:underline">Register</a>
        </p>
    </div>

    <script>
        function togglePassword() {
            const pw = document.getElementById('password');
            pw.type = pw.type === 'password' ? 'text' : 'password';
        }
    </script>

</body>
</html>
