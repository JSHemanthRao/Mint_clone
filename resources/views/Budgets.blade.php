<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Budgets</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen flex">

  <!-- Sidebar -->
  <nav class="bg-gray-800 fixed left-0 top-0 h-full w-64 p-6 flex flex-col">
    <h1 class="text-2xl font-bold mb-8">Mint</h1>
    <div class="flex flex-col space-y-3 flex-grow">
      <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg hover:bg-gray-700">Dashboard</a>
      <a href="{{ route('accounts') }}" class="px-3 py-2 rounded-lg hover:bg-gray-700">Accounts</a>
      <a href="{{ route('bills') }}" class="px-3 py-2 rounded-lg hover:bg-gray-700">Bills</a>
      <a href="{{ route('budgets') }}" class="px-3 py-2 rounded-lg bg-gray-700">Budgets</a>
      <a href="{{ route('categories') }}" class="px-3 py-2 rounded-lg hover:bg-gray-700">Categories</a>
      <a href="{{ route('transactions') }}" class="px-3 py-2 rounded-lg hover:bg-gray-700">Transactions</a>
      <a href="{{ route('goals') }}" class="px-3 py-2 rounded-lg hover:bg-gray-700">Goals</a>
      <a href="#" class="px-3 py-2 rounded-lg hover:bg-gray-700">Notifications</a>
      <a href="{{ route('profile') }}" class="px-3 py-2 rounded-lg hover:bg-gray-700">Profile</a>
    </div>
    <button id="logoutBtn" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg font-semibold">Logout</button>
  </nav>

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

<!-- Popup Notification (Bottom Right) -->
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