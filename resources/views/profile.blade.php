<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col lg:flex-row">

  <!-- Sidebar -->
  <nav class="bg-gray-800 p-3 sm:p-4 flex justify-around lg:flex-col lg:justify-start lg:w-64 lg:min-h-screen">
    <h1 class="text-lg sm:text-xl font-bold mb-4 hidden lg:block">Dashboard</h1>
    <div class="flex space-x-2 sm:space-x-4 lg:space-x-0 lg:flex-col lg:space-y-3 w-full">
      <a href="/dashboard" class="block px-4 py-2 rounded-lg hover:bg-gray-700">Dashboard</a>
      <a href="{{ route('accounts') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-700">Accounts</a>
      <a href="{{ route('bills') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-700">Bills</a>
      <a href="{{ route('budgets') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-700">Budgets</a>
      <a href="{{ route('categories') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-700">Categories</a>
      <a href="{{ route('transactions') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-700">Transactions</a>
      <a href="{{ route('goals') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-700">Goals</a>
      <a href="#" class="block px-4 py-2 rounded-lg hover:bg-gray-700">Notifications</a>
      <a href="{{ route('profile') }}" class="block px-4 py-2 rounded-lg bg-gray-700">Profile</a>
    </div>
    <button id="logoutBtn" class="mt-4 bg-red-600 hover:bg-red-700 w-full px-4 py-2 rounded-lg font-semibold">Logout</button>
  </nav>

  <!-- Main Content -->
  <main class="flex-1 p-4 sm:p-6 space-y-6">
    <h2 class="text-3xl font-bold mb-6">Your Profile</h2>

    <!-- Profile Info -->
    <div id="profileCard" class="bg-gray-800 p-6 rounded-2xl shadow-lg flex items-center space-x-6"></div>

    <!-- Accounts -->
    <div>
      <h3 class="text-xl font-semibold mt-8 mb-4">Your Accounts</h3>
      <div id="accountsList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
    </div>
  </main>

  <script>
    const token = localStorage.getItem("jwt_token");
    if (!token) {
      window.location.href = "/login";
    }

    async function fetchProfile() {
    const token = localStorage.getItem("token");

    const response = await fetch("http://127.0.0.1:8000/api/me", {
        method: "GET",
        headers: {
            "Authorization": "Bearer " + token,
            "Accept": "application/json",
        },
    });

    if (response.ok) {
        const data = await response.json();
        console.log("Profile Data:", data);
        // Redirect to profile page and pass user info if needed
    } else {
        console.log("Not logged in, redirecting to login...");
        window.location.href = "/login";
    }
}

  </script>

</body>
</html>