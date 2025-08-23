<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Account</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col items-center p-6">
  <div class="bg-gray-800/90 backdrop-blur-lg p-6 rounded-2xl shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-semibold text-center mb-6">Edit Account</h2>
    <form class="space-y-4" method="POST" action="{{ route('accounts.update', $account->id) }}">
      @csrf
      @method('PUT')
      <div>
        <label for="name" class="block text-sm font-medium text-gray-300">Account Name</label>
        <input type="text" name="name" id="name" value="{{ old('name', $account->name) }}"
          class="mt-1 w-full p-3 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500 outline-none">
      </div>
      <div>
        <label for="balance" class="block text-sm font-medium text-gray-300">Balance</label>
        <input type="number" name="balance" id="balance" value="{{ old('balance', $account->balance) }}"
          class="mt-1 w-full p-3 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500 outline-none">
      </div>
      <div>
        <label for="type" class="block text-sm font-medium text-gray-300">Account Type</label>
        <select name="type" id="type"
          class="mt-1 w-full p-3 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500 outline-none">
          <option value="Savings" @selected(old('type', $account->type) == 'Savings Account')>Savings Account</option>
          <option value="Checking" @selected(old('type', $account->type) == 'Current Account')>Current Account</option>
          <option value="Credit" @selected(old('type', $account->type) == 'Credit Card')>Credit Card</option>
        </select>
      </div>
      <p class="text-sm text-gray-400 pt-4 text-center">
        <a href="{{ route('accounts.index') }}" class="text-indigo-400 hover:underline">← Cancel</a>
      </p>
      <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 py-3 rounded-lg text-white font-medium">Save Changes</button>
    </form>
  </div>
</body>
</html>