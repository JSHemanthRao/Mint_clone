<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Category</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen flex-col lg:flex-col">

  <!-- Navbar -->
  <header class="bg-gray-900/90 backdrop-blur-lg shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
      <a href="/" class="text-2xl font-bold text-green-400">Mint Clone</a>
      <nav class="flex space-x-6 items-center">
        <a href="{{ route('dashboard') }}" class="hover:text-green-400 font-semibold">Dashboard</a>
        <a href="{{ route('accounts') }}" class="hover:text-green-400 font-semibold">Accounts</a>
        <a href="{{ route('bills') }}" class="hover:text-green-400 font-semibold">Bills</a>
        <a href="{{ route('budgets') }}" class="hover:text-green-400 font-semibold">Budgets</a>
        <a href="{{ route('categories') }}" class="hover:text-green-400 font-semibold">Categories</a>
        <a href="{{ route('transactions') }}" class="hover:text-green-400 font-semibold">Transactions</a>
        <a href="{{ route('goals') }}" class="hover:text-green-400 font-semibold">Goals</a>

        <!-- Notification Bell Icon -->
        <div class="relative">
          <button id="notificationBtn" class="p-2 hover:bg-gray-700 rounded-full relative">
            <i data-feather="bell" class="w-5 h-5"></i>
            <span id="notificationBadge" class="absolute -top-1 -right-1 bg-red-500 text-xs text-white px-1 rounded-full">2</span>
          </button>

          <!-- Notification Dropdown -->
          <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-64 bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="p-4 border-b border-gray-700">
              <h3 class="text-lg font-semibold">Notifications</h3>
            </div>
            <ul class="max-h-60 overflow-y-auto">
              <li class="p-3 hover:bg-gray-700 cursor-pointer">🔔 New account created</li>
              <li class="p-3 hover:bg-gray-700 cursor-pointer">💰 Transaction of $250 added</li>
            </ul>
            <div class="p-3 text-center border-t border-gray-700">
              <button class="text-green-400 hover:underline">View All</button>
            </div>
          </div>
        </div>
      </nav>

      <!-- JS at bottom -->
      <script src="https://unpkg.com/feather-icons"></script>
      <script>
        feather.replace();

        const notificationBtn = document.getElementById("notificationBtn");
        const notificationDropdown = document.getElementById("notificationDropdown");
        const notificationBadge = document.getElementById("notificationBadge");

        let notificationsSeen = false; // Track if user already opened

        notificationBtn.addEventListener("click", () => {
          notificationDropdown.classList.toggle("hidden");

          // Hide badge once user opens dropdown for the first time
          if (!notificationsSeen) {
            notificationBadge.style.display = "none";
            notificationsSeen = true;
          }
        });

        // Close dropdown when clicking outside
        document.addEventListener("click", (e) => {
          if (!notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
            notificationDropdown.classList.add("hidden");
          }
        });
      </script>

      <button id="logoutBtn" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg font-semibold">Logout</button>
    </div>
  </header>


  <!-- Main Content -->
  <main class="ml-64 flex-1 p-10 flex flex-col items-center space-y-10">

    <!-- Category Form -->
    <div class="bg-gray-800/90 backdrop-blur-lg p-6 rounded-2xl shadow-lg w-full max-w-md">
      <h2 class="text-2xl font-bold mb-6 text-center">Add Category</h2>

      <form id="categoryForm" class="space-y-5" method="POST" action="{{ route('categories.store') }}">
        @csrf
        <!-- Category Name -->
        <div>
          <label for="name" class="block text-sm font-medium mb-1">Category Name</label>
          <input
            type="text"
            id="name"
            name="name"
            placeholder="Enter category name"
            class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 
                   focus:ring-2 focus:ring-indigo-500 focus:outline-none"
            required>
          <!-- Inline Error -->
          <p id="nameError" class="text-red-500 text-sm mt-1 hidden"></p>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          class="w-full py-2 px-4 rounded-lg bg-indigo-600 hover:bg-indigo-700 
                 transition text-white font-medium">
          Add Category
        </button>
      </form>

      <!-- Back to Dashboard -->
      <p class="text-sm text-gray-400 mt-6 text-center">
        <a href="/dashboard" class="text-indigo-400 hover:underline">← Back to Dashboard</a>
      </p>
    </div>

    <!-- Categories List -->
    <div class="w-full max-w-5xl bg-gray-800/90 backdrop-blur-lg p-6 rounded-2xl shadow-lg">
      <h2 class="text-2xl font-semibold text-center mb-6">Categories List</h2>

      <div id="categoriesList"
        class="flex flex-col space-y-4 max-h-96 overflow-y-auto p-2">
        <!-- Categories will be dynamically loaded here -->
      </div>
    </div>
  </main>

  <script>
    const token = localStorage.getItem("jwt_token");
    if (!token) {
      window.location.href = "/login";
    }

    // Logout
    document.getElementById("logoutBtn").addEventListener("click", async function() {
      localStorage.removeItem("jwt_token");
      window.location.href = "/login";
    });

    // Handle form submit
    document.getElementById('categoryForm').addEventListener('submit', async function(event) {
      event.preventDefault();

      // Clear old error
      document.getElementById('nameError').textContent = "";
      document.getElementById('nameError').classList.add("hidden");

      let categoryData = {
        name: document.getElementById('name').value
      };

      let res = await fetch('/api/categories', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify(categoryData)
      });

      let data = await res.json();

      if (res.ok) {
        // Append category to list
        document.getElementById('categoriesList').innerHTML += `
          <div class="bg-gray-700 p-4 rounded-lg shadow-md text-center">
            <h3 class="text-lg font-semibold truncate">${data.name}</h3>
          </div>
        `;
        document.getElementById('categoryForm').reset();
      } else {
        // Show inline error if available
        if (data.errors && data.errors.name) {
          document.getElementById('nameError').textContent = data.errors.name[0];
          document.getElementById('nameError').classList.remove("hidden");
        } else if (data.message) {
          document.getElementById('nameError').textContent = data.message;
          document.getElementById('nameError').classList.remove("hidden");
        }
      }
    });

    // Load existing categories on page load
    window.onload = async () => {
      let res = await fetch('/api/categories', {
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer ' + token
        }
      });

      let categories = await res.json();

      if (res.ok && Array.isArray(categories)) {
        categories.forEach(cat => {
          document.getElementById('categoriesList').innerHTML += `
            <div class="bg-gray-700 p-4 rounded-lg shadow-md text-center">
              <h3 class="text-lg font-semibold truncate">${cat.name}</h3>
            </div>
          `;
        });
      }
    };
  </script>
</body>

</html>