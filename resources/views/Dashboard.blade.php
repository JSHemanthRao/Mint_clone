<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col lg:flex-row">

  <!-- Navbar -->
  <nav class="bg-gray-800 fixed left-0 top-0 h-full w-64 p-6 flex flex-col">
    <h1 class="text-2xl font-bold mb-8">Mint</h1>
    <div class="flex flex-col space-y-3 flex-grow">
      <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg bg-gray-700">Dashboard</a>
      <a href="{{ route('accounts') }}" class="px-3 py-2 rounded-lg hover:bg-gray-700">Accounts</a>
      <a href="{{ route('bills') }}" class="px-3 py-2 rounded-lg hover:bg-gray-700">Bills</a>
      <a href="{{ route('budgets') }}" class="px-3 py-2 rounded-lg hover:bg-gray-700">Budgets</a>
      <a href="{{ route('categories') }}" class="px-3 py-2 rounded-lg hover:bg-gray-700">Categories</a>
      <a href="{{ route('transactions') }}" class="px-3 py-2 rounded-lg hover:bg-gray-700">Transactions</a>
      <a href="{{ route('goals') }}" class="px-3 py-2 rounded-lg hover:bg-gray-700">Goals</a>
      <a href="#" class="px-3 py-2 rounded-lg hover:bg-gray-700">Notifications</a>
      <a href="{{ route('profile') }}" class="px-3 py-2 rounded-lg hover:bg-gray-700">Profile</a>
    </div>
    <button id="logoutBtn" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg font-semibold">Logout</button>
  </nav>

  <!-- Main Content -->
  <main class="flex-1 p-4 sm:p-6 space-y-6 lg:ml-64">
    <h2 class="text-2xl font-bold mb-4">Welcome!</h2>

    <!-- Accounts Section -->
    <div>
      <h3 class="text-xl font-semibold mb-2">Your Accounts</h3>
      <div id="accountsList" class="h-40 overflow-y-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Accounts will be inserted here -->
      </div>
    </div>

    <!-- Bills Section -->
    <div>
      <h3 class="text-xl font-semibold mb-2">Bills</h3>
      <div id="billsList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Bills will be inserted here -->
      </div>
    </div>

    <!-- Budgets Section -->
    <div>
      <h3 class="text-xl font-semibold mb-2">Budgets</h3>
      <div id="budgetsList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Budgets will be inserted here -->
      </div>
    </div>

    <!-- Goals Section -->
    <div>
      <h3 class="text-xl font-semibold mb-2">Goals</h3>
      <div id="goalsList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Goals will be inserted here -->
      </div>
    </div>
  </main>

  <script>
    const token = localStorage.getItem("jwt_token");
    if (!token) {
      window.location.href = "/login";
    }
async function fetchData(endpoint, containerId) {
  try {
    let res = await fetch(`/api/${endpoint}`, {
      headers: { "Authorization": "Bearer " + token }
    });
    let data = await res.json();

    const container = document.getElementById(containerId);
    container.innerHTML = "";

    if (!data || data.length === 0) {
      // Show fallback if empty
      let emptyMsg = document.createElement("div");
      emptyMsg.className = "bg-gray-700 text-gray-300 p-4 rounded-xl text-center";
      emptyMsg.innerHTML = `No ${endpoint} found`;
      container.appendChild(emptyMsg);
      return;
    }

    data.forEach(item => {
      let card = document.createElement("div");
      card.className = "bg-gray-800 p-4 rounded-xl shadow text-sm sm:text-base";

      let status="";

      if (endpoint === "budgets") {
        card.innerHTML = `
          <h4 class="font-bold">${item.category ? item.category.name : "No Category"}</h4>
          <p class="text-gray-400">Amount: ₹${item.amount}</p>
        `;
      } else if (endpoint === "accounts") {
        card.innerHTML = `
          <h4 class="font-bold">${item.name}</h4>
          <p class="text-gray-400">Account Type: ${item.type}</p>
          <p class="text-gray-400">Balance: ₹${item.balance}</p>
        `;
      } else if (endpoint === "bills") {
        if (item.due_date < new Date().toISOString().split("T")[0]) {
          card.classList.add("bg-red-700/30", "border", "border-red-500");
          status = "Overdue";
        } else {
          card.classList.add("bg-green-900", "border", "border-green-500");
          status = "Upcoming";
        }
        card.innerHTML = `
          <h4 class="font-bold">Status: ${status}</h4>
          <h4 class="font-bold">Name: ${item.name}</h4>
          <p class="text-gray-400">Amount: ₹${item.amount}</p>
        `;
      } else if (endpoint === "goals") {
        card.innerHTML = `
          <h4 class="font-bold">${item.name}</h4>
          <p class="text-gray-400">Target: ₹${item.target_amount}</p>
          <p class="text-gray-400">Current: ₹${item.current_amount}</p>
          <p class="text-gray-400">Due Date: ${item.due_date}</p>
        `;
      } else {
        card.innerHTML = `
          <h4 class="font-bold">${item.name || item.title}</h4>
        `;
      }

      container.appendChild(card);
    });
  } catch (err) {
    console.error(`Error loading ${endpoint}:`, err);
  }
}



    // Call APIs
    fetchData("accounts", "accountsList");
    fetchData("bills", "billsList");
    fetchData("budgets", "budgetsList");
    fetchData("goals", "goalsList");

    // Logout
    document.getElementById("logoutBtn").addEventListener("click", async function() {
      localStorage.removeItem("jwt_token");
      window.location.href = "/login";
    });
  </script>

</body>
</html>