<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Budgets</title>
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
            <span id="notificationBadge"
              class="absolute -top-1 -right-1 bg-red-500 text-xs text-white px-1 rounded-full hidden">0</span>
          </button>

          <!-- Notification Dropdown -->
          <div id="notificationDropdown"
            class="hidden absolute right-0 mt-2 w-64 bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="p-4 border-b border-gray-700">
              <h3 class="text-lg font-semibold">Notifications</h3>
            </div>
            <ul id="notificationList" class="max-h-60 overflow-y-auto">
              <!-- Notifications will be injected here -->
            </ul>
            <div class="p-3 text-center border-t border-gray-700">
              <button id="clearNotifications" class="text-green-400 hover:underline">Clear All</button>
            </div>
          </div>
        </div>
      </nav>
      <button id="logoutBtn" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg font-semibold">Logout</button>
    </div>
  </header>

  <!-- Main Content -->
  <main class="ml-0 lg:ml-64 flex-1 p-8 space-y-8">

    <!-- Budget Form -->
    <div class="bg-gray-800/90 backdrop-blur-lg p-6 rounded-2xl shadow-lg w-full max-w-xl mx-auto mb-8">
      <h2 class="text-xl font-semibold mb-4 text-center text-white">Create a Budget</h2>
      <form id="budgetForm" class="flex flex-col gap-4">
        @csrf
        <input type="number" name="amount" id="amount" placeholder="Enter Budget Amount"
          class="w-full p-3 rounded-lg border border-gray-600 bg-gray-700 text-white focus:ring-2 focus:ring-indigo-500 outline-none"
          required>

        <select name="category_id" id="category_id"
          class="w-full p-3 rounded-lg border border-gray-600 bg-gray-700 text-white focus:ring-2 focus:ring-indigo-500 outline-none"
          required>
          <option value="">-- Select Category --</option>
          @foreach($categories as $category)
          <option value="{{ $category->id }}">{{ $category->name }}</option>
          @endforeach
        </select>

        <button type="submit"
          class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition">
          Save Budget
        </button>
      </form>
    </div>

    <!-- Budgets List -->
    <div class="bg-gray-800/90 backdrop-blur-lg p-6 rounded-2xl shadow-lg w-full max-w-5xl mx-auto">
      <h2 class="text-2xl font-semibold text-center mb-6 text-white">Budgets List</h2>
      <div id="budgetsList"
        class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 max-h-[400px] overflow-y-auto p-2">
        <!-- Budget cards will be injected -->
      </div>
    </div>
  </main>

  <!-- Popup Notification -->
  <div id="popupNotification"
    class="hidden fixed bottom-6 right-6 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-y-4 opacity-0 transition-all duration-500 ease-in-out">
    Budget created successfully
  </div>

  <script src="https://unpkg.com/feather-icons"></script>
  <script>
    feather.replace();

    const token = localStorage.getItem("jwt_token");
    if (!token) {
      window.location.href = "/login";
    }

    const budgetsList = document.getElementById("budgetsList");

    // ------------------ Render Budget ------------------
    function renderBudget(budget) {
      const card = document.createElement("div");
      card.className =
        "bg-gray-700 p-5 rounded-xl shadow-md w-full h-44 flex flex-col justify-between hover:shadow-xl transition";

      card.innerHTML = `
        <div>
          <h3 class="text-lg font-semibold text-white">${budget.category?.name ?? 'Unknown Category'}</h3>
          <p class="mt-2 text-gray-300">Amount: ₹${budget.amount}</p>
        </div>
        <button onclick="deleteBudget(${budget.id}, this)"
          class="mt-3 bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm">
          Delete Budget
        </button>
      `;
      budgetsList.appendChild(card);
    }

    function showPopup(message) {
      const popup = document.getElementById("popupNotification");
      popup.textContent = message;
      popup.classList.remove("hidden", "translate-y-4", "opacity-0");
      popup.classList.add("translate-y-0", "opacity-100");

      setTimeout(() => {
        popup.classList.add("translate-y-4", "opacity-0");
        setTimeout(() => popup.classList.add("hidden"), 500);
      }, 3000);
    }

    // ------------------ Load Budgets ------------------
    async function loadBudgets() {
      try {
        const res = await fetch("/api/budgets", {
          headers: {
            "Accept": "application/json",
            "Authorization": "Bearer " + token
          }
        });

        const data = await res.json();
        if (res.ok) {
          budgetsList.innerHTML = "";
          data.forEach(budget => renderBudget(budget));
        } else {
          console.error("Failed to load budgets", data);
        }
      } catch (error) {
        console.error("Error loading budgets", error);
      }
    }

    // ------------------ Create Budget ------------------
    document.getElementById('budgetForm').addEventListener('submit', async function (event) {
      event.preventDefault();

      const formData = new FormData(this);

      try {
        const res = await fetch("/api/budgets", {
          method: "POST",
          headers: {
            "Authorization": "Bearer " + token
          },
          body: formData
        });

        const data = await res.json();

        if (res.ok) {
          renderBudget(data);
          this.reset();
          showPopup("Budget created successfully!");
          saveNotification("✅ Budget created");
        } else {
          alert("Failed to create budget: " + (data.message ?? JSON.stringify(data)));
        }
      } catch (err) {
        console.error("Error creating budget:", err);
        alert("Something went wrong!");
      }
    });

    // ------------------ Delete Budget ------------------
    async function deleteBudget(id, btn) {
      if (!confirm("Are you sure you want to delete this budget?")) return;

      try {
        const res = await fetch(`/api/budgets/${id}`, {
          method: "DELETE",
          headers: {
            "Accept": "application/json",
            "Authorization": "Bearer " + token,
            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
          }
        });

        if (res.ok) {
          btn.closest("div").remove();
          showPopup("Budget deleted successfully!");
          saveNotification("❌ Budget deleted");
        } else {
          const error = await res.json();
          alert("Failed to delete budget: " + (error.message ?? "Unknown error"));
        }
      } catch (err) {
        console.error("Error deleting budget:", err);
      }
    }

    // ------------------ Notifications ------------------
    const notificationBtn = document.getElementById("notificationBtn");
    const notificationDropdown = document.getElementById("notificationDropdown");
    const notificationBadge = document.getElementById("notificationBadge");
    const notificationList = document.getElementById("notificationList");
    const clearBtn = document.getElementById("clearNotifications");

    function loadNotifications() {
      let notifications = JSON.parse(localStorage.getItem("notifications")) || [];
      notificationList.innerHTML = "";
      if (notifications.length === 0) {
        notificationList.innerHTML = `<li class="p-3 text-gray-400">No new notifications</li>`;
        notificationBadge.classList.add("hidden");
      } else {
        notifications.forEach(note => {
          let li = document.createElement("li");
          li.className = "p-3 hover:bg-gray-700 cursor-pointer";
          li.textContent = note;
          notificationList.appendChild(li);
        });
        notificationBadge.textContent = notifications.length;
        notificationBadge.classList.remove("hidden");
      }
    }

    function saveNotification(message) {
      let notifications = JSON.parse(localStorage.getItem("notifications")) || [];
      notifications.unshift(message);
      localStorage.setItem("notifications", JSON.stringify(notifications));
      loadNotifications();
    }

    clearBtn.addEventListener("click", () => {
      localStorage.removeItem("notifications");
      loadNotifications();
    });

    notificationBtn.addEventListener("click", () => {
      notificationDropdown.classList.toggle("hidden");
    });

    document.addEventListener("click", (e) => {
      if (!notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
        notificationDropdown.classList.add("hidden");
      }
    });

    // ------------------ Initialize ------------------
    loadBudgets();
    loadNotifications();
  </script>
</body>
</html>
