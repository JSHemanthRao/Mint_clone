<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-100 to-purple-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white p-10 rounded-2xl shadow-xl border border-gray-200">
        <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Create Account</h2>

        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-5">
            <div>
                <label class="block text-gray-700 font-medium mb-1" for="name">Name</label>
                <input type="text" name="name" id="name" placeholder="Your Name"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition duration-200">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1" for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="example@mail.com"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition duration-200">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1" for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="********"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition duration-200">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1" for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="********"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition duration-200">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg shadow-md transition duration-200">
                Register
            </button>
        </form>

        <p class="mt-6 text-center text-gray-600">
            Already have an account? 
            <a href="{{ route('login.form') }}" class="text-blue-600 font-semibold hover:underline">Login</a>
        </p>
    </div>

</body>
</html>
