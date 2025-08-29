<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home - Mint Clone</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

  <!-- Navbar -->
  <nav class="bg-gray-800 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16 items-center">
        <a href="/" class="text-xl font-bold text-green-400">MintClone</a>
        <!-- <div class="hidden md:flex space-x-6">
          <a href="accounts.html" class="hover:text-green-400">Accounts</a>
          <a href="budgets.html" class="hover:text-green-400">Budgets</a>
          <a href="goals.html" class="hover:text-green-400">Goals</a>
          <a href="transactions.html" class="hover:text-green-400">Transactions</a>
          <a href="about.html" class="hover:text-green-400">About</a>
          <a href="contact.html" class="hover:text-green-400">Contact</a>
        </div> -->
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <header class="flex-1 flex flex-col items-center justify-center text-center px-6 py-16">
    <h1 class="text-4xl md:text-6xl font-bold mb-6">
      Take Control of Your <span class="text-green-400">Finances</span>
    </h1>
    <p class="text-lg text-gray-400 mb-8 max-w-2xl">
      Track accounts, manage budgets, set goals, and monitor transactions all in one place.
    </p>
    <div class="flex space-x-4">
      <a href="/register"
        class="px-6 py-3 rounded-xl bg-green-500 text-white font-medium hover:bg-green-600 transition">
        Get Started
      </a>
      <a href="login"
        class="px-6 py-3 rounded-xl bg-gray-700 text-white font-medium hover:bg-gray-600 transition">
        Login
      </a>
    </div>
  </header>

  <!-- Features Section -->
  <section class="bg-gray-800 py-16">
    <div class="max-w-6xl mx-auto px-6 grid gap-10 md:grid-cols-3">
      <div class="p-6 bg-gray-900 rounded-2xl shadow-md hover:shadow-xl transition">
        <h3 class="text-xl font-semibold mb-4">💳 Manage Accounts</h3>
        <p class="text-gray-400">Keep track of all your accounts in one place with easy access and insights.</p>
      </div>
      <div class="p-6 bg-gray-900 rounded-2xl shadow-md hover:shadow-xl transition">
        <h3 class="text-xl font-semibold mb-4">📊 Track Budgets</h3>
        <p class="text-gray-400">Set monthly budgets and stay on top of your spending with detailed tracking.</p>
      </div>
      <div class="p-6 bg-gray-900 rounded-2xl shadow-md hover:shadow-xl transition">
        <h3 class="text-xl font-semibold mb-4">🎯 Achieve Goals</h3>
        <p class="text-gray-400">Plan your financial goals and track progress towards achieving them.</p>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-800 text-gray-400 text-center py-6 mt-auto">
    <p>&copy; 2025 MintClone. All rights reserved.</p>
  </footer>

</body>

</html>
