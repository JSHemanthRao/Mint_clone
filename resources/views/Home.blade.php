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

        <!-- Buttons that will toggle based on login -->
        <div id="authButtons" class="flex space-x-4"></div>
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
    <div id="heroButtons" class="flex space-x-4"></div>
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

  <!-- Script to handle auth logic -->
  <script>
    const token = localStorage.getItem("token");
    const authButtons = document.getElementById("authButtons");
    const heroButtons = document.getElementById("heroButtons");

    if (token) {
      // User logged in
      authButtons.innerHTML = `
        <a href="/dashboard" class="px-4 py-2 rounded-lg bg-green-500 text-white font-medium hover:bg-green-600 transition">Dashboard</a>
        <button id="logoutBtn" class="px-4 py-2 rounded-lg bg-red-500 text-white font-medium hover:bg-red-600 transition">Logout</button>
      `;

      heroButtons.innerHTML = `
        <a href="/dashboard" class="px-6 py-3 rounded-xl bg-green-500 text-white font-medium hover:bg-green-600 transition">Go to Dashboard</a>
      `;

      document.getElementById("logoutBtn").addEventListener("click", () => {
        localStorage.removeItem("token");
        window.location.href = "/";
      });
    } else {
      // User not logged in
      authButtons.innerHTML = `
        <a href="/login" class="px-4 py-2 rounded-lg bg-gray-700 text-white font-medium hover:bg-gray-600 transition">Login</a>
        <a href="/register" class="px-4 py-2 rounded-lg bg-green-500 text-white font-medium hover:bg-green-600 transition">Register</a>
      `;

      heroButtons.innerHTML = `
        <a href="/register" class="px-6 py-3 rounded-xl bg-green-500 text-white font-medium hover:bg-green-600 transition">Get Started</a>
        <a href="/login" class="px-6 py-3 rounded-xl bg-gray-700 text-white font-medium hover:bg-gray-600 transition">Login</a>
      `;
    }
  </script>
</body>
</html>
