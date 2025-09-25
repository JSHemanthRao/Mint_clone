<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accounts</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen flex lg:flex-col">

  <!-- Header -->
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

        <!-- Notifications -->
        <div class="relative">
          <button id="notificationBtn" class="p-2 hover:bg-gray-700 rounded-full relative">
            <i data-feather="bell" class="w-5 h-5"></i>
            <span id="notificationBadge"
              class="absolute -top-1 -right-1 bg-red-500 text-xs text-white px-1 rounded-full hidden">0</span>
          </button>
          <div id="notificationDropdown"
            class="hidden absolute right-0 mt-2 w-64 bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="p-4 border-b border-gray-700">
              <h3 class="text-lg font-semibold">Notifications</h3>
            </div>
            <ul id="notificationList" class="max-h-60 overflow-y-auto"></ul>
            <div class="p-3 text-center border-t border-gray-700">
              <button class="text-green-400 hover:underline">View All</button>
            </div>
          </div>
        </div>
      </nav>

      <button id="logoutBtn"
        class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg font-semibold">Logout</button>
    </div>
  </header>

  <!-- Main Content -->
  <main class="flex-1 p-4 sm:p-6 space-y-6 lg:ml-64">
    <h2 class="text-2xl font-bold mb-4">Welcome!</h2>

    <!-- Accounts -->
    <div>
      <h3 class="text-xl font-semibold mb-2">Your Accounts</h3>
      <div id="accountsList" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3"></div>
    </div>

    <!-- Create Account -->
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

    <!-- Toast -->
    <div id="successToast"
      class="hidden fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg">
      Account created successfully!
    </div>
  </main>

  <!-- Scripts -->
  <script src="https://unpkg.com/feather-icons"></script>
<script>
  feather.replace();


  const notificationBtn = document.getElementById("notificationBtn");
  const notificationDropdown = document.getElementById("notificationDropdown");
  const notificationBadge = document.getElementById("notificationBadge");
  const notificationList = document.getElementById("notificationList");
  let unseenCount = 0;

  async function loadNotifications() {
    const token = localStorage.getItem("jwt_token");
    try {
      const res = await fetch("/api/notifications", {
        headers: { "Authorization": "Bearer " + token }
      });
      const notifications = await res.json();
      renderNotifications(notifications);
    } catch (err) {
      console.error("Error loading notifications:", err);
    }
  }

  function renderNotifications(notifications) {
    notificationList.innerHTML = "";
    unseenCount = 0;
    notifications.forEach(n => {
      const li = document.createElement("li");
      li.className = "p-3 hover:bg-gray-700 cursor-pointer text-sm";
      li.textContent = n.message;
      notificationList.prepend(li);
      if (!n.read) unseenCount++;
    });
    updateBadge();
  }

  async function addNotification(message) {
    const token = localStorage.getItem("jwt_token");
    await fetch("/api/notifications", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Authorization": "Bearer " + token
      },
      body: JSON.stringify({ message })
    });
    loadNotifications();
  }

  function updateBadge() {
    if (unseenCount > 0) {
      notificationBadge.textContent = unseenCount;
      notificationBadge.classList.remove("hidden");
    } else {
      notificationBadge.classList.add("hidden");
    }
  }

  notificationBtn.addEventListener("click", async () => {
    notificationDropdown.classList.toggle("hidden");
    if (!notificationDropdown.classList.contains("hidden")) {
      unseenCount = 0;
      updateBadge();
      const token = localStorage.getItem("jwt_token");
      await fetch("/api/notifications/read", {
        method: "POST",
        headers: { "Authorization": "Bearer " + token }
      });
    }
  });

  document.addEventListener("click", (e) => {
    if (!notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
      notificationDropdown.classList.add("hidden");
    }
  });

  /* ---------------- Accounts ---------------- */
  const accountsList = document.getElementById("accountsList");
  const accountForm = document.getElementById("accountForm");
  const successToast = document.getElementById("successToast");

  function fmtAmount(raw) {
    if (raw == null) return "0.00";
    const n = Number(raw);
    return Number.isFinite(n) ? n.toFixed(2) : String(raw);
  }

  function escapeHtml(s) {
    return String(s ?? "").replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;");
  }

  async function loadAccounts() {
    accountsList.innerHTML = "";
    const token = localStorage.getItem("jwt_token");
    try {
      const res = await fetch("/api/accounts", {
        headers: { "Authorization": "Bearer " + token }
      });
      const json = await res.json();
      let accounts = Array.isArray(json) ? json : Array.isArray(json.data) ? json.data : [];
      if (!accounts.length) {
        accountsList.innerHTML = '<p class="text-sm text-gray-400">No accounts found</p>';
        return;
      }
      accounts.forEach(acc => renderAccount(acc));
    } catch (err) {
      console.error("Error loading accounts:", err);
      accountsList.innerHTML = '<p class="text-sm text-gray-400">Error loading accounts</p>';
    }
  }

  function renderAccount(account) {
    const balance = fmtAmount(account.balance ?? 0);

    accountsList.insertAdjacentHTML('beforeend', `
      <div class="bg-gray-700 p-4 rounded-lg shadow-md flex flex-col justify-between">
        <h3 class="text-lg font-semibold truncate">${escapeHtml(account.name ?? 'Untitled')}</h3>
        <p>Balance: <span class="font-medium">₹${escapeHtml(balance)}</span></p>
        <p>Type: ${escapeHtml(account.type ?? '')}</p>

        <div class="flex justify-between mt-2 space-x-2">
          <button onclick="updateBalance(${account.id}, 'deposit')" 
            class="text-sm bg-green-600 hover:bg-green-700 text-white py-1 px-3 rounded">Add</button>
          <button onclick="updateBalance(${account.id}, 'withdraw')" 
            class="text-sm bg-red-600 hover:bg-red-700 text-white py-1 px-3 rounded">Withdraw</button>
        </div>

        <div class="flex justify-end space-x-2 mt-3">
          <button class="edit-btn text-sm bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded" data-id="${account.id}">
            Edit
          </button>
          <form class="delete-form" action="/api/accounts/${account.id}" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="text-sm bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded">
              Delete
            </button>
          </form>
        </div>
      </div>
    `);
  }

  async function updateBalance(accountId, action) {
    const token = localStorage.getItem("jwt_token");
    const amount = parseFloat(prompt(`Enter amount to ${action}:`));
    if (!amount || isNaN(amount) || amount <= 0) return alert("Invalid amount");

    try {
      const res = await fetch(`/api/accounts/${accountId}/${action}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer " + token
        },
        body: JSON.stringify({ amount })
      });
      const data = await res.json();
      if (res.ok) {
        loadAccounts();
        addNotification(`💰 ${action === "deposit" ? "Deposited" : "Withdrew"} ₹${amount} in account #${accountId}`);
      } else {
        alert("Error: " + (data.error || JSON.stringify(data)));
      }
    } catch (err) {
      console.error(err);
      alert("Something went wrong!");
    }
  }

  accountForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const token = localStorage.getItem("jwt_token");
    const formData = {
      name: document.getElementById("name").value,
      type: document.getElementById("type").value,
      balance: parseFloat(document.getElementById("balance").value) || 0
    };

    try {
      const res = await fetch("/api/accounts", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer " + token
        },
        body: JSON.stringify(formData)
      });
      const data = await res.json();
      if (res.ok) {
        accountForm.reset();
        showToast("Account created successfully!");
        loadAccounts();
        addNotification(`🏦 New account "${formData.name}" created`);
      } else {
        alert("Error: " + (data.error || JSON.stringify(data)));
      }
    } catch (err) {
      console.error(err);
      alert("Something went wrong!");
    }
  });

  function showToast(message) {
    successToast.innerText = message;
    successToast.classList.remove("hidden");
    setTimeout(() => successToast.classList.add("hidden"), 3000);
  }

  /* ---------------- Edit & Delete via AJAX ---------------- */
  document.addEventListener("click", async (e) => {
    // Delete
    if (e.target.closest(".delete-form button")) {
      e.preventDefault();
      const form = e.target.closest(".delete-form");
      if (!confirm("Are you sure you want to delete this account?")) return;

      const action = form.action;
      const token = localStorage.getItem("jwt_token");

      try {
        const res = await fetch(action, {
          method: "DELETE",
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token,
            "X-CSRF-TOKEN": form.querySelector("input[name='_token']").value
          }
        });
        const data = await res.json();
        if (res.ok) {
          showToast(data.message || "Account deleted successfully!");
          loadAccounts();
        } else {
          alert("Error: " + (data.error || JSON.stringify(data)));
        }
      } catch (err) {
        console.error(err);
        alert("Something went wrong!");
      }
    }

    // Edit
    if (e.target.matches(".edit-btn")) {
      const accountId = e.target.dataset.id;
      editAccount(accountId);
    }
  });

  async function editAccount(accountId) {
    const token = localStorage.getItem("jwt_token");
    try {
      const res = await fetch(`/api/accounts/${accountId}`, {
        headers: { "Authorization": "Bearer " + token }
      });
      const account = await res.json();

      const name = prompt("Edit account name:", account.name);
      const type = prompt("Edit account type:", account.type);
      if (!name || !type) return;

      const updateRes = await fetch(`/api/accounts/${accountId}`, {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer " + token
        },
        body: JSON.stringify({ name, type, balance: account.balance })
      });
      const updated = await updateRes.json();
      if (updateRes.ok) {
        showToast(`Account "${updated.name}" updated!`);
        loadAccounts();
        addNotification(`✏️ Account "${updated.name}" updated`);
      } else {
        alert("Error: " + (updated.error || JSON.stringify(updated)));
      }
    } catch (err) {
      console.error(err);
      alert("Something went wrong!");
    }
  }

  document.addEventListener("DOMContentLoaded", () => {
    loadAccounts();
    loadNotifications();
  });
</script>


</body>

</html>