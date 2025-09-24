<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transactions</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/feather-icons"></script>
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

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

        <!-- Notification Bell -->
        <div class="relative">
          <button id="notificationBtn" class="p-2 hover:bg-gray-700 rounded-full relative">
            <i data-feather="bell" class="w-5 h-5"></i>
            <span id="notificationBadge" class="absolute -top-1 -right-1 bg-red-500 text-xs text-white px-1 rounded-full hidden">0</span>
          </button>
          <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-64 bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="p-4 border-b border-gray-700 flex justify-between items-center">
              <h3 class="text-lg font-semibold">Notifications</h3>
              <button id="clearNotifications" class="text-red-400 text-sm hover:underline">Clear All</button>
            </div>
            <ul id="notificationList" class="max-h-60 overflow-y-auto"></ul>
          </div>
        </div>
      </nav>

      <button id="logoutBtn" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg font-semibold">Logout</button>
    </div>
  </header>

  <!-- Main Content -->
  <main class="flex-1 p-6 flex flex-col items-center space-y-10">

    <!-- Transactions Form -->
    <div class="bg-gray-800/90 backdrop-blur-lg p-6 rounded-2xl shadow-lg w-full max-w-md">
      <h2 class="text-2xl font-semibold text-center mb-6">Add Transaction</h2>
      <form id="transactionsForm" class="space-y-4">
        <input name="description" type="text" placeholder="Description (e.g., Starbucks)"
          class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-orange-500 outline-none" required>

        <input name="amount" type="number" placeholder="Amount"
          class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-orange-500 outline-none" required>

        <input name="date" type="date"
          class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-orange-500 outline-none" required>

        <select name="category_id"
          class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-orange-500 outline-none" required>
          <option value="">-- Select Category --</option>
          @foreach($categories as $category)
          <option value="{{ $category->id }}">{{ $category->name }}</option>
          @endforeach
        </select>

        <select name="account_id" id="account_id_select"
          class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-orange-500 outline-none" required>
          <option value="">-- Select Account --</option>
        </select>

        <button type="submit"
          class="w-full bg-orange-600 hover:bg-orange-700 py-3 rounded-lg text-white font-medium">Save Transaction</button>
      </form>
    </div>

    <!-- Accounts Balance Display -->
    <!-- <div class="w-full max-w-3xl bg-gray-700/90 backdrop-blur-lg p-4 rounded-2xl shadow-lg">
      <h2 class="text-xl font-semibold mb-4 text-center">Accounts Balances</h2>
      <div id="accountsList" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
    </div> -->

    <!-- Transactions List -->
    <div class="w-full max-w-5xl bg-gray-700/90 backdrop-blur-lg p-6 rounded-2xl shadow-lg">
      <h2 class="text-2xl font-semibold text-center mb-6">Transactions List</h2>
      <div id="transactionsList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"></div>
    </div>
  </main>

  <script>
    feather.replace();

    const token = localStorage.getItem("jwt_token");
    if (!token) window.location.href = "/login";

    // ------------------ Notifications ------------------
    let notifications = JSON.parse(localStorage.getItem("notifications")) || [];
    const notificationBtn = document.getElementById("notificationBtn");
    const notificationDropdown = document.getElementById("notificationDropdown");
    const notificationBadge = document.getElementById("notificationBadge");
    const notificationList = document.getElementById("notificationList");
    const clearNotificationsBtn = document.getElementById("clearNotifications");

    function renderNotifications() {
      notificationList.innerHTML = "";
      notifications.forEach(n => {
        let li = document.createElement("li");
        li.className = "p-3 hover:bg-gray-700 cursor-pointer";
        li.textContent = n;
        notificationList.appendChild(li);
      });
      if (notifications.length) {
        notificationBadge.textContent = notifications.length;
        notificationBadge.style.display = "block";
      } else notificationBadge.style.display = "none";
      localStorage.setItem("notifications", JSON.stringify(notifications));
    }

    function addNotification(msg) {
      notifications.unshift(msg);
      renderNotifications();
    }

    notificationBtn.addEventListener("click", () => notificationDropdown.classList.toggle("hidden"));
    clearNotificationsBtn.addEventListener("click", () => { notifications = []; renderNotifications(); });
    document.addEventListener("click", (e) => { if (!notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) notificationDropdown.classList.add("hidden"); });

    // ------------------ Logout ------------------
    document.getElementById("logoutBtn").addEventListener("click", () => {
      localStorage.removeItem("jwt_token");
      window.location.href = "/login";
    });

    // ------------------ Accounts ------------------
    let accountsData = [];
    async function loadAccounts() {
      try {
        let res = await fetch('/api/accounts', {
          headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token }
        });
        let data = await res.json();
        if (res.ok) {
          accountsData = data;
          const select = document.getElementById('account_id_select');
          const listDiv = document.getElementById('accountsList');
          select.innerHTML = '<option value="">-- Select Account --</option>';
          listDiv.innerHTML = "";
          data.forEach(a => {
            select.innerHTML += `<option value="${a.id}">${a.name} (₹${a.balance})</option>`;
            listDiv.innerHTML += `<div class="p-3 bg-gray-800 rounded-lg text-white flex justify-between">${a.name}<span>₹${a.balance}</span></div>`;
          });
        }
      } catch (e) { console.error(e); }
    }

    // ------------------ Transactions ------------------
    async function loadTransactions() {
      try {
        let res = await fetch('/api/transactions', {
          headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token }
        });
        let transactions = await res.json();
        if (res.ok) {
          const list = document.getElementById('transactionsList');
          list.innerHTML = "";
          transactions.forEach(t => {
            list.innerHTML += `
              <div class="bg-gray-800 p-5 rounded-xl shadow-md">
                <h3 class="text-lg font-semibold">${t.description}</h3>
                <p class="text-gray-300">Amount: ₹${t.amount}</p>
                <p class="text-gray-300">Date: ${t.date}</p>
                <p class="text-gray-400 text-sm">Category: ${t.category ? t.category.name : 'N/A'}</p>
                <p class="text-gray-400 text-sm">Account: ${t.account ? t.account.name : 'N/A'}</p>
              </div>
            `;
          });
        }
      } catch (e) { console.error(e); }
    }

    // ------------------ Form Submit ------------------
    document.getElementById('transactionsForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const data = {
        account_id: e.target.account_id.value,
        category_id: e.target.category_id.value,
        description: e.target.description.value,
        amount: parseFloat(e.target.amount.value),
        date: e.target.date.value
      };
      try {
        let res = await fetch('/api/transactions', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer ' + token },
          body: JSON.stringify(data)
        });
        let t = await res.json();
        if (res.ok) {
          addNotification(`💰 Transaction "${t.description}" added`);
          e.target.reset();
          await loadAccounts();
          await loadTransactions();
        } else console.error(t);
      } catch (e) { console.error(e); }
    });

    window.onload = async () => {
      await loadAccounts();
      await loadTransactions();
      renderNotifications();
    };
  </script>
</body>
</html>
