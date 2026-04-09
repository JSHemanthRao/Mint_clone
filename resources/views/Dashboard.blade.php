<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-gray-100 min-h-screen flex flex-col">

  <header class="bg-gradient-to-r from-blue-900/40 to-purple-900/40 backdrop-blur-xl border-b border-blue-500/10 shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-5 flex justify-between items-center">
      <a href="/" class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">Mint Clone</a>
      <nav class="flex space-x-8 items-center">
        <a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-cyan-400 font-semibold transition-colors duration-200">Dashboard</a>
        <a href="{{ route('accounts') }}" class="text-gray-300 hover:text-cyan-400 font-semibold transition-colors duration-200">Accounts</a>
        <a href="{{ route('bills') }}" class="text-gray-300 hover:text-cyan-400 font-semibold transition-colors duration-200">Bills</a>
        <a href="{{ route('budgets') }}" class="text-gray-300 hover:text-cyan-400 font-semibold transition-colors duration-200">Budgets</a>
        <a href="{{ route('categories') }}" class="text-gray-300 hover:text-cyan-400 font-semibold transition-colors duration-200">Categories</a>
        <a href="{{ route('transactions') }}" class="text-gray-300 hover:text-cyan-400 font-semibold transition-colors duration-200">Transactions</a>
        <a href="{{ route('goals') }}" class="text-gray-300 hover:text-cyan-400 font-semibold transition-colors duration-200">Goals</a>

        <div class="relative">
          <button id="notificationBtn" class="p-2 hover:bg-blue-500/20 rounded-lg transition-all duration-200 relative">
            <i data-feather="bell" class="w-5 h-5"></i>
            <span id="notificationBadge"
              class="absolute -top-1 -right-1 bg-red-500 text-xs text-white px-1.5 rounded-full hidden font-semibold animate-pulse"></span>
          </button>

          <div id="notificationDropdown"
            class="hidden absolute right-0 mt-3 w-72 bg-slate-800/90 backdrop-blur-lg rounded-xl shadow-2xl overflow-hidden border border-blue-500/20">
            <div class="p-4 border-b border-blue-500/20 bg-gradient-to-r from-blue-900/20 to-purple-900/20">
              <h3 class="text-lg font-semibold">Notifications</h3>
            </div>
            <ul id="notificationList" class="max-h-60 overflow-y-auto"></ul>
            <div class="p-3 text-center border-t border-blue-500/20">
              <button id="clearNotifications" class="text-cyan-400 hover:text-cyan-300 font-semibold transition-colors">Clear All</button>
            </div>
          </div>
        </div>
      </nav>
      <button id="logoutBtn" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 px-6 py-2 rounded-lg font-semibold shadow-lg transition-all duration-200">Logout</button>
    </div>
  </header>

  <main class="flex-1 p-8 space-y-12 max-w-7xl mx-auto w-full">
    <div>
      <h2 class="text-4xl font-bold bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent mb-2">Welcome Back!</h2>
      <p class="text-gray-400">Here's your financial overview</p>
    </div>

    <section>
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-2xl font-bold text-white">Your Accounts</h3>
      </div>
      <div id="accountsList" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6"></div>
    </section>

    <section>
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-2xl font-bold text-white">Upcoming Bills</h3>
      </div>
      <div id="billsList" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6"></div>
    </section>

    <section>
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-2xl font-bold text-white">Budget Overview</h3>
      </div>
      <div id="budgetsList" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6"></div>
    </section>

    <section>
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-2xl font-bold text-white">Financial Goals</h3>
      </div>
      <div id="goalsList" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6"></div>
    </section>
  </main>

  <script src="https://unpkg.com/feather-icons"></script>

  <script>
    feather.replace();
    const token = localStorage.getItem("jwt_token");
    if (!token) window.location.href = "/login";


    const notificationBtn = document.getElementById("notificationBtn");
    const notificationDropdown = document.getElementById("notificationDropdown");
    const notificationBadge = document.getElementById("notificationBadge");
    const notificationList = document.getElementById("notificationList");
    const clearBtn = document.getElementById("clearNotifications");

    async function loadNotifications() {
      try {
        const res = await fetch('/api/notifications', {
          headers: { Authorization: "Bearer " + token }
        });
        const notifications = await res.json();

        notificationList.innerHTML = "";
        if (Array.isArray(notifications)) {
          notifications.forEach(notif => {
            const li = document.createElement("li");
            li.className = "p-4 hover:bg-blue-500/10 border-b border-blue-500/10 last:border-0 text-gray-200 text-sm transition-colors duration-200";
            li.textContent = notif.message;
            notificationList.appendChild(li);
          });
          updateBadge(notifications.length);
        }
      } catch (error) {
        console.error("Failed to load notifications", error);
      }
    }

    function updateBadge(count) {
      if (count > 0) {
        notificationBadge.textContent = count;
        notificationBadge.classList.remove("hidden");
      } else {
        notificationBadge.classList.add("hidden");
      }
    }

    // Clear notifications endpoint does not exist in backend yet or logic is different
    // We will just hide the badge for now or implement mark-read if needed
    // But for now, let's keep it simple.

    notificationBtn.addEventListener("click", () => notificationDropdown.classList.toggle("hidden"));

    // Optional: Add mark all read support if backend supports it
    clearBtn.addEventListener("click", async () => {
      try {
        await fetch('/api/notifications/read', {
          method: 'POST',
          headers: { Authorization: "Bearer " + token }
        });
        loadNotifications();
      } catch (e) {
        console.error(e);
      }
    });

    document.addEventListener("click", (e) => {
      if (!notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
        notificationDropdown.classList.add("hidden");
      }
    });

    loadNotifications();


    async function fetchItems(endpoint, containerId, renderCard) {
      const res = await fetch(`/api/${endpoint}`, { headers: { Authorization: "Bearer " + token } });
      const data = await res.json();
      const container = document.getElementById(containerId);
      container.innerHTML = "";
      data.forEach(item => container.appendChild(renderCard(item)));
    }


    function loadAccounts() {
      fetchItems("accounts", "accountsList", (acc) => {
        const div = document.createElement("div");
        div.className = "bg-gradient-to-br from-slate-800/50 to-slate-700/50 backdrop-blur-lg p-6 rounded-xl shadow-lg border border-cyan-500/20 hover:border-cyan-400/40 transition-all duration-300 hover:shadow-cyan-500/20 hover:shadow-xl";

        let isHidden = true;
        let balance = acc.balance;

        div.innerHTML = `
          <div class="flex items-start justify-between mb-4">
            <div>
              <h4 class="text-lg font-bold text-white">${acc.name}</h4>
              <p class="text-xs text-cyan-400/70 font-semibold mt-1">${acc.type.toUpperCase()}</p>
            </div>
            <i data-feather="credit-card" class="w-5 h-5 text-cyan-400/60"></i>
          </div>
          <div class="flex items-center justify-between pt-4 border-t border-cyan-500/10">
            <div>
              <p class="text-xs text-gray-400 mb-1">Balance</p>
              <p class="balance text-2xl font-bold bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">₹${isHidden ? "•••••" : balance.toLocaleString('en-IN')}</p>
            </div>
            <button class="toggle-eye p-2 hover:bg-cyan-500/20 rounded-lg transition-colors duration-200 text-cyan-400/60 hover:text-cyan-400">
              <i data-feather="${isHidden ? "eye-off" : "eye"}" class="w-5 h-5"></i>
            </button>
          </div>
        `;

        const eyeBtn = div.querySelector(".toggle-eye");
        const balanceEl = div.querySelector(".balance");

        eyeBtn.addEventListener("click", () => {
          isHidden = !isHidden;
          balanceEl.textContent = isHidden ? "₹•••••" : "₹" + balance.toLocaleString('en-IN');
          eyeBtn.innerHTML = `<i data-feather="${isHidden ? "eye-off" : "eye"}" class="w-5 h-5"></i>`;
          feather.replace();
        });

        feather.replace();
        return div;
      });
    }


    function loadBills() {
      fetchItems("bills", "billsList", (bill) => {
        const div = document.createElement("div");
        div.className = "bg-gradient-to-br from-slate-800/50 to-slate-700/50 backdrop-blur-lg p-6 rounded-xl shadow-lg border border-amber-500/20 hover:border-amber-400/40 transition-all duration-300 hover:shadow-amber-500/20 hover:shadow-xl";
        div.innerHTML = `
          <div class="flex items-start justify-between mb-4">
            <div>
              <h4 class="text-lg font-bold text-white">${bill.name}</h4>
              <p class="text-xs text-amber-400/70 font-semibold mt-1">Due: ${new Date(bill.due_date).toLocaleDateString()}</p>
            </div>
            <i data-feather="file-text" class="w-5 h-5 text-amber-400/60"></i>
          </div>
          <div class="pt-4 border-t border-amber-500/10">
            <p class="text-xs text-gray-400 mb-1">Amount</p>
            <p class="text-2xl font-bold bg-gradient-to-r from-orange-400 to-amber-500 bg-clip-text text-transparent">₹${bill.amount.toLocaleString('en-IN')}</p>
          </div>
        `;
        feather.replace();
        return div;
      });
    }


    function loadBudgets() {
      fetchItems("budgets", "budgetsList", (budget) => {
        const div = document.createElement("div");
        div.className = "bg-gradient-to-br from-slate-800/50 to-slate-700/50 backdrop-blur-lg p-6 rounded-xl shadow-lg border border-purple-500/20 hover:border-purple-400/40 transition-all duration-300 hover:shadow-purple-500/20 hover:shadow-xl";
        div.innerHTML = `
          <div class="flex items-start justify-between mb-4">
            <div>
              <h4 class="text-lg font-bold text-white">${budget.category?.name || budget.category}</h4>
              <p class="text-xs text-purple-400/70 font-semibold mt-1">Budget</p>
            </div>
            <i data-feather="pie-chart" class="w-5 h-5 text-purple-400/60"></i>
          </div>
          <div class="pt-4 border-t border-purple-500/10">
            <p class="text-xs text-gray-400 mb-1">Budget Limit</p>
            <p class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-pink-500 bg-clip-text text-transparent">₹${budget.amount.toLocaleString('en-IN')}</p>
          </div>
        `;
        feather.replace();
        return div;
      });
    }


    function loadGoals() {
      fetchItems("goals", "goalsList", (goal) => {
        const div = document.createElement("div");
        div.className = "bg-gradient-to-br from-slate-800/50 to-slate-700/50 backdrop-blur-lg p-6 rounded-xl shadow-lg border border-green-500/20 hover:border-green-400/40 transition-all duration-300 hover:shadow-green-500/20 hover:shadow-xl";
        const progressPercent = (goal.current_amount / goal.target_amount * 100).toFixed(0);
        div.innerHTML = `
          <div class="flex items-start justify-between mb-4">
            <div>
              <h4 class="text-lg font-bold text-white">${goal.name}</h4>
              <p class="text-xs text-green-400/70 font-semibold mt-1">Target: ${new Date(goal.due_date).toLocaleDateString()}</p>
            </div>
            <i data-feather="target" class="w-5 h-5 text-green-400/60"></i>
          </div>
          <div class="space-y-3 pt-4 border-t border-green-500/10">
            <div class="flex justify-between text-sm">
              <span class="text-gray-400">Progress</span>
              <span class="text-green-400 font-semibold">${progressPercent}%</span>
            </div>
            <div class="w-full h-2 bg-slate-700 rounded-full overflow-hidden">
              <div class="h-full bg-gradient-to-r from-green-400 to-emerald-500" style="width: ${progressPercent}%"></div>
            </div>
            <div class="flex justify-between text-xs text-gray-400 mt-2">
              <span>₹${goal.current_amount.toLocaleString('en-IN')}</span>
              <span>₹${goal.target_amount.toLocaleString('en-IN')}</span>
            </div>
          </div>
        `;
        feather.replace();
        return div;
      });
    }

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