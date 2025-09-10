<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
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
            <span id="notificationBadge"
              class="absolute -top-1 -right-1 bg-red-500 text-xs text-white px-1 rounded-full hidden"></span>
          </button>

          <!-- Notification Dropdown -->
          <div id="notificationDropdown"
            class="hidden absolute right-0 mt-2 w-64 bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="p-4 border-b border-gray-700">
              <h3 class="text-lg font-semibold">Notifications</h3>
            </div>
            <ul id="notificationList" class="max-h-60 overflow-y-auto"></ul>
            <div class="p-3 text-center border-t border-gray-700">
              <button id="clearNotifications" class="text-green-400 hover:underline">Clear All</button>
            </div>
          </div>
        </div>
      </nav>
      <button id="logoutBtn"
        class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg font-semibold">Logout</button>
    </div>
  </header>

  <!-- Main Content -->
  <main class="flex-1 p-6 space-y-10 lg:ml-64">
    <h2 class="text-2xl font-bold mb-4">Welcome!</h2>

    <!-- Accounts -->
    <section>
      <div class="flex items-center justify-between mb-2">
        <h3 class="text-xl font-semibold">Accounts</h3>
        <!-- Eye Button -->
        <button id="toggleBalance" class="p-2 hover:bg-gray-700 rounded-full">
          <i data-feather="eye" class="w-5 h-5"></i>
        </button>
      </div>
      <div id="accountsList" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
    </section>

    <!-- Bills -->
    <section>
      <h3 class="text-xl font-semibold mb-2">Bills</h3>
      <div id="billsList" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
    </section>

    <!-- Budgets -->
    <section>
      <h3 class="text-xl font-semibold mb-2">Budgets</h3>
      <div id="budgetsList" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
    </section>

    <!-- Goals -->
    <section>
      <h3 class="text-xl font-semibold mb-2">Goals</h3>
      <div id="goalsList" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
    </section>
  </main>

  <!-- Feather Icons -->
  <script src="https://unpkg.com/feather-icons"></script>

  <script>
    feather.replace();
    const token = localStorage.getItem("jwt_token");
    if (!token) window.location.href = "/login";

    /* ------------------- Notifications ------------------- */
    const notificationBtn = document.getElementById("notificationBtn");
    const notificationDropdown = document.getElementById("notificationDropdown");
    const notificationBadge = document.getElementById("notificationBadge");
    const notificationList = document.getElementById("notificationList");
    const clearBtn = document.getElementById("clearNotifications");

    function loadNotifications() {
      const notifications = JSON.parse(localStorage.getItem("notifications")) || [];
      notificationList.innerHTML = "";
      notifications.forEach(msg => {
        const li = document.createElement("li");
        li.className = "p-3 hover:bg-gray-700";
        li.textContent = msg;
        notificationList.appendChild(li);
      });
      updateBadge(notifications.length);
    }
    function updateBadge(count) {
      if (count > 0) {
        notificationBadge.textContent = count;
        notificationBadge.classList.remove("hidden");
      } else notificationBadge.classList.add("hidden");
    }
    function addNotification(message) {
      const notifications = JSON.parse(localStorage.getItem("notifications")) || [];
      notifications.unshift("🔔 " + message);
      localStorage.setItem("notifications", JSON.stringify(notifications));
      loadNotifications();
    }
    notificationBtn.addEventListener("click", () => {
      notificationDropdown.classList.toggle("hidden");
    });
    clearBtn.addEventListener("click", () => {
      localStorage.removeItem("notifications");
      loadNotifications();
    });
    document.addEventListener("click", (e) => {
      if (!notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
        notificationDropdown.classList.add("hidden");
      }
    });
    loadNotifications();

    /* ------------------- CRUD Helpers ------------------- */
    async function fetchItems(endpoint, containerId, renderCard) {
      const res = await fetch(`/api/${endpoint}`, { headers: { Authorization: "Bearer " + token } });
      const data = await res.json();
      const container = document.getElementById(containerId);
      container.innerHTML = "";
      data.forEach(item => container.appendChild(renderCard(item)));
    }

    async function deleteItem(endpoint, id, onSuccessMsg, reloadFn) {
      if (!confirm("Are you sure?")) return;
      const res = await fetch(`/api/${endpoint}/${id}`, {
        method: "DELETE",
        headers: { Authorization: "Bearer " + token }
      });
      if (res.ok) {
        addNotification(onSuccessMsg);
        reloadFn();
      }
    }

    /* ------------------- Accounts ------------------- */
    let showBalance = false; // default hidden

    function loadAccounts() {
      fetchItems("accounts", "accountsList", (acc) => {
        const div = document.createElement("div");
        div.className = "bg-gray-800 p-4 rounded shadow";
        div.innerHTML = `
          <h4>${acc.name}</h4>
          <p>${acc.type}</p>
          <p class="font-bold">${showBalance ? "₹" + acc.balance : "•••••"}</p>
          <button class="bg-red-600 px-2 py-1 rounded mt-2">Delete</button>`;
        div.querySelector("button").onclick = () => deleteItem("accounts", acc.id, "Account deleted", loadAccounts);
        return div;
      });
    }

    document.getElementById("toggleBalance").addEventListener("click", () => {
      showBalance = !showBalance;
      const icon = document.querySelector("#toggleBalance i");
      icon.setAttribute("data-feather", showBalance ? "eye-off" : "eye");
      feather.replace();
      loadAccounts();
    });

    /* ------------------- Bills ------------------- */
    function loadBills() {
      fetchItems("bills", "billsList", (bill) => {
        const div = document.createElement("div");
        div.className = "bg-gray-800 p-4 rounded shadow";
        div.innerHTML = `<h4>${bill.name}</h4><p>₹${bill.amount}</p><p>${bill.due_date}</p>
          <button class="bg-red-600 px-2 py-1 rounded mt-2">Delete</button>`;
        div.querySelector("button").onclick = () => deleteItem("bills", bill.id, "Bill deleted", loadBills);
        return div;
      });
    }

    /* ------------------- Budgets ------------------- */
    function loadBudgets() {
      fetchItems("budgets", "budgetsList", (budget) => {
        const div = document.createElement("div");
        div.className = "bg-gray-800 p-4 rounded shadow";
        div.innerHTML = `<h4>${budget.category?.name || budget.category}</h4><p>₹${budget.amount}</p>
          <button class="bg-red-600 px-2 py-1 rounded mt-2">Delete</button>`;
        div.querySelector("button").onclick = () => deleteItem("budgets", budget.id, "Budget deleted", loadBudgets);
        return div;
      });
    }

    /* ------------------- Goals ------------------- */
    function loadGoals() {
      fetchItems("goals", "goalsList", (goal) => {
        const div = document.createElement("div");
        div.className = "bg-gray-800 p-4 rounded shadow";
        div.innerHTML = `<h4>${goal.name}</h4><p>Target: ₹${goal.target_amount}</p>
          <p>Current: ₹${goal.current_amount}</p><p>${goal.due_date}</p>
          <button class="bg-red-600 px-2 py-1 rounded mt-2">Delete</button>`;
        div.querySelector("button").onclick = () => deleteItem("goals", goal.id, "Goal deleted", loadGoals);
        return div;
      });
    }

    /* ------------------- Init ------------------- */
    loadAccounts();
    loadBills();
    loadBudgets();
    loadGoals();

    document.getElementById("logoutBtn").onclick = () => {
      localStorage.removeItem("jwt_token");
      window.location.href = "/login";
    };
  </script>
</body>
</html>
