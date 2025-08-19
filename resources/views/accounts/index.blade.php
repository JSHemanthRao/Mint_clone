<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-50 to-purple-50 min-h-screen">

    <div class="container mx-auto py-10">
        <h1 class="text-4xl font-bold mb-10 text-center text-gray-800">Your Accounts</h1>

        {{-- Add Account Form --}}
        <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200 mb-10 max-w-lg mx-auto">
            <h2 class="text-2xl font-semibold mb-6 text-gray-700">Add New Account</h2>
            <form action="{{ route('accounts.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-gray-600 font-medium mb-2">Name</label>
                    <input type="text" name="name" placeholder="Savings Account"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition duration-200" required>
                </div>
                <div>
                    <label class="block text-gray-600 font-medium mb-2">Type</label>
                    <input type="text" name="type" placeholder="Savings / Credit Card"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition duration-200" required>
                </div>
                <div>
                    <label class="block text-gray-600 font-medium mb-2">Balance</label>
                    <input type="number" step="0.01" name="balance" placeholder="1000.00"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition duration-200" required>
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg shadow-md transition duration-200">
                    Add Account
                </button>
            </form>
        </div>

        {{-- Accounts List --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($accounts as $account)
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200 hover:shadow-xl transition duration-300">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-2">{{ $account->name }}</h3>
                    <p class="text-gray-600 mb-1"><span class="font-medium">Type:</span> {{ $account->type }}</p>
                    <p class="text-gray-600"><span class="font-medium">Balance:</span> ${{ number_format($account->balance, 2) }}</p>
                </div>
            @endforeach
        </div>
    </div>

</body>
</html>
