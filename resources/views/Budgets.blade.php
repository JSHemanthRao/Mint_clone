<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Budgets</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen flex-col lg:flex-col">

  <!-- Sidebar Navbar -->
  <header class="bg-gray-900/90 backdrop-blur-lg shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-green-400">Mint Clone</h1>
      <nav class="flex space-x-6">
        <a href="{{ route('dashboard') }}" class="hover:text-green-400">Dashboard</a>
        <a href="{{ route('accounts') }}" class="hover:text-green-400">Accounts</a>
        <a href="{{ route('bills') }}" class="text-green-400 font-semibold">Bills</a>
        <a href="{{ route('budgets') }}" class="hover:text-green-400">Budgets</a>
        <a href="{{ route('categories') }}" class="hover:text-green-400">Categories</a>
        <a href="{{ route('transactions') }}" class="hover:text-green-400">Transactions</a>
        <a href="{{ route('goals') }}" class="hover:text-green-400">Goals</a>
      </nav>
      <button id="logoutBtn" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg font-semibold">Logout</button>
    </div>
  </header>


  <!-- Main Content -->
  <main class="ml-64 flex-1 p-8 space-y-8">

    <!-- Budget Form -->
    <div class="bg-gray-800/90 backdrop-blur-lg p-6 rounded-2xl shadow-lg w-full max-w-xl mx-auto mb-8">
      <h2 class="text-xl font-semibold mb-4 text-center text-white">Create a Budget</h2>
      <form id="budgetForm" class="flex flex-col gap-4">
        @csrf
        <input type="number" name="amount" placeholder="Enter Budget Amount"
          class="w-full p-3 rounded-lg border border-gray-600 bg-gray-700 text-white focus:ring-2 focus:ring-indigo-500 outline-none"
          required>

        <select name="category_id"
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

  <script>
    const token = localStorage.getItem("jwt_token");
    if (!token) {
      window.location.href = "/login";
    }

    const budgetsList = document.getElementById("budgetsList");

    function renderBudget(budget) {
      budgetsList.innerHTML += `
        <div class="bg-gray-700 p-5 rounded-xl shadow-md w-full h-40 flex flex-col justify-between hover:shadow-xl transition">
          <div>
            <h3 class="text-lg font-semibold text-white">${budget.category?.name ?? 'Unknown Category'}</h3>
            <p class="mt-2 text-gray-300">Amount: ₹${budget.amount}</p>
          </div>
        </div>
      `;
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

    async function loadBudgets() {
      try {
        let res = await fetch('/api/budgets', {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token
          }
        });

        let data = await res.json();

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

    document.getElementById('budgetForm').addEventListener('submit', async function (event) {
      event.preventDefault();

      let formData = new FormData(this);

      let res = await fetch("{{ route('budgets.store') }}", {
        method: "POST",
        headers: {
          "Authorization": "Bearer " + token,
          "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
        },
        body: formData
      });

      let data = await res.json();

      if (res.ok) {
        renderBudget(data);
        this.reset();
        showPopup("Budget created successfully!");
      } else {
        alert("Failed to create budget: " + (data.message ?? "Unknown error"));
      }
    });

    loadBudgets();
  </script>
</body>
</html>
