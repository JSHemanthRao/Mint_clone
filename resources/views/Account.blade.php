<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accounts</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen flex lg:flex-col">

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
  <main class="flex-1 p-4 sm:p-6 space-y-6 lg:ml-64">
    <h2 class="text-2xl font-bold mb-4">Welcome!</h2>

    <!-- Accounts Section -->
    <div>
      <h3 class="text-xl font-semibold mb-2">Your Accounts</h3>
      <div id="accountsList" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3"></div>
    </div>

    <!-- Create Account Form -->
    <div class="bg-gray-800/90 backdrop-blur-lg p-6 rounded-2xl shadow-lg w-full max-w-md">
      <h2 class="text-2xl font-bold mb-6 text-center">Create Account</h2>

      <form id="accountForm" class="space-y-5">
        <div>
          <label for="name" class="block mb-2">Bank Name</label>
          <input type="text" id="name" name="name" required
            class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:border-yellow-400 focus:ring focus:ring-yellow-400 focus:ring-opacity-50">
        </div>

        <div>
          <label for="type" class="block mb-2">Account Type</label>
          <select id="type" name="type" required
            class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:border-yellow-400 focus:ring focus:ring-yellow-400 focus:ring-opacity-50">
            <option value="savings">Savings</option>
            <option value="current">Current</option>
            <option value="credit">Credit</option>
          </select>
        </div>

        <div>
          <label for="balance" class="block mb-2">Initial Balance</label>
          <input type="number" id="balance" name="balance" required step="0.01"
            class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:border-yellow-400 focus:ring focus:ring-yellow-400 focus:ring-opacity-50">
        </div>

        <button type="submit"
          class="w-full py-2 px-4 bg-yellow-500 hover:bg-yellow-600 text-black font-semibold rounded-lg shadow-md">
          Save Account
        </button>
      </form>
    </div>

    <!-- Toast Notification -->
    <div id="successToast"
      class="hidden fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg">
      Account created successfully!
    </div>
  </main>

  <!-- Script -->
  <script>
    let accountsList = document.getElementById("accountsList");
    let accountForm = document.getElementById("accountForm");
    let successToast = document.getElementById("successToast");

    // ✅ Load Accounts
    async function loadAccounts() {
      accountsList.innerHTML = "";
      let token = localStorage.getItem("jwt_token");

      try {
        let res = await fetch("/api/accounts", {
          headers: {
            "Authorization": "Bearer " + token
          }
        });

        let accounts = await res.json();
        accounts.forEach(acc => renderAccount(acc));
      } catch (err) {
        console.error("Error loading accounts:", err);
      }
    }

    // ✅ Render Account Card
    function renderAccount(account) {
      accountsList.innerHTML += `
        <div class="bg-gray-700 p-4 rounded-lg shadow-md flex flex-col justify-between">
          <h3 class="text-lg font-semibold truncate">${account.name}</h3>
          <p>Balance: <span class="font-medium">₹${account.balance}</span></p>
          <p>Type: ${account.type}</p>
          
          <div class="flex justify-between mt-2 space-x-2">
            <button onclick="updateBalance(${account.id}, 'deposit')" 
              class="text-sm bg-green-600 hover:bg-green-700 text-white py-1 px-3 rounded">Add</button>
            <button onclick="updateBalance(${account.id}, 'withdraw')" 
              class="text-sm bg-red-600 hover:bg-red-700 text-white py-1 px-3 rounded">Withdraw</button>
          </div>

          <div class="flex justify-end space-x-2 mt-3">
            <a href="/accounts/${account.id}/edit" 
              class="text-sm bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded">Edit</a>
            <form action="/accounts/${account.id}" method="POST" onsubmit="return confirm('Are you sure?')">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="_method" value="DELETE">
              <button type="submit" class="text-sm bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded">
                Delete
              </button>
            </form>
          </div>
        </div>
      `;
    }

    // ✅ Handle Deposit/Withdraw
    async function updateBalance(accountId, action) {
      let token = localStorage.getItem("jwt_token");
      let amount = parseFloat(prompt(`Enter amount to ${action}:`));

      if (!amount || isNaN(amount) || amount <= 0) {
        alert("Invalid amount");
        return;
      }

      try {
        let res = await fetch(`/api/accounts/${accountId}/${action}`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token
          },
          body: JSON.stringify({
            amount: amount
          })
        });

        let data = await res.json();
        if (res.ok) {
          loadAccounts();
        } else {
          alert("Error: " + (data.error || JSON.stringify(data)));
        }
      } catch (err) {
        console.error(err);
        alert("Something went wrong!");
      }
    }

    // ✅ Handle Create Account Form
    accountForm.addEventListener("submit", async function(e) {
      e.preventDefault();
      let token = localStorage.getItem("jwt_token");

      let formData = {
        name: document.getElementById("name").value,
        type: document.getElementById("type").value,
        balance: document.getElementById("balance").value,
      };

      try {
        let res = await fetch("/api/accounts", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token
          },
          body: JSON.stringify(formData)
        });

        let data = await res.json();
        if (res.ok) {
          accountForm.reset();
          showToast("Account created successfully!");
          loadAccounts();
        } else {
          alert("Error: " + (data.error || JSON.stringify(data)));
        }
      } catch (err) {
        console.error(err);
        alert("Something went wrong!");
      }
    });

    // ✅ Toast function
    function showToast(message) {
      successToast.innerText = message;
      successToast.classList.remove("hidden");
      setTimeout(() => {
        successToast.classList.add("hidden");
      }, 3000);
    }

    document.addEventListener("DOMContentLoaded", loadAccounts);
  </script>
</body>

</html>