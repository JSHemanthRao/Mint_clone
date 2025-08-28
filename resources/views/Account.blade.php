<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accounts</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen flex">

  <!-- Navbar -->
  <nav class="bg-gray-800 fixed left-0 top-0 h-full w-64 p-6 flex flex-col">
    <h1 class="text-2xl font-bold mb-8">Mint</h1>
    <div class="flex flex-col space-y-3 flex-grow">
      <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg hover:bg-gray-700">Dashboard</a>
      <a href="{{ route('accounts') }}" class="px-3 py-2 rounded-lg bg-gray-700">Accounts</a>
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
  <div class="flex-1 ml-64 p-8 space-y-10">


  <div class="min-h-screen flex items-center justify-center bg-gray-950">
    <form id="accountForm" class="max-w-md w-full bg-gray-900 shadow-lg rounded-2xl p-6 space-y-4 border border-gray-700">
        @csrf

        <h2 class="text-2xl font-semibold text-white text-center">Create New Account</h2>

        
        <div>
            <label for="name" class="block text-gray-300 mb-1 font-medium">Account Name</label>
            <input type="text" name="name" id="name" placeholder="e.g. Salary Account" autocomplete="off"
                   class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
        </div>

        
        <div>
            <label for="type" class="block text-gray-300 mb-1 font-medium">Account Type</label>
            <select name="type" id="type" class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                <option value="" disabled selected>-- Select Account Type --</option>
                <option value="Savings">Savings</option>
                <option value="Current">Current</option>
                <option value="Business">Business</option>
                <option value="Joint">Joint</option>
            </select>
        </div>

        
        <div>
            <label for="balance" class="block text-gray-300 mb-1 font-medium">Balance</label>
            <input type="number" name="balance" id="balance" placeholder="Enter balance" autocomplete="off"
                   class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
        </div>

        
        <button type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg shadow-md transition duration-200">
            Save Account
        </button>
    </form>
</div>


<div id="successToast" class="hidden fixed top-0 left-1/2 transform -translate-x-1/2 mt-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg text-lg font-semibold transition-all duration-500">
    Account created successfully!
</div>


<script>
  let accountsList = document.getElementById("accountsList");


  async function loadAccounts() {
    accountsList.innerHTML = ""; // clear list
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

  
  function renderAccount(account) {
    accountsList.innerHTML += `
      <div class="bg-gray-700 p-4 rounded-lg shadow-md w-full flex flex-col justify-between">
        <h3 class="text-lg font-semibold truncate">${account.name}</h3>
        <p>Balance: <span class="font-medium">₹${account.balance}</span></p>
        <p>Type: ${account.type}</p>
        
        <!-- Deposit & Withdraw -->
        <div class="flex justify-between mt-2 space-x-2">
          <button onclick="updateBalance(${account.id}, 'deposit')" 
            class="text-sm bg-green-600 hover:bg-green-700 text-white py-1 px-3 rounded">Add</button>
          <button onclick="updateBalance(${account.id}, 'withdraw')" 
            class="text-sm bg-red-600 hover:bg-red-700 text-white py-1 px-3 rounded">Withdraw</button>
        </div>

        <!-- Edit & Delete -->
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

  
  async function updateBalance(accountId, action) {
    let token = localStorage.getItem("jwt_token");
    let amount = prompt(`Enter amount to ${action}:`);

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
        body: JSON.stringify({ amount: amount })
      });

      let data = await res.json();
      if (res.ok) {
        alert(`Amount ${action === 'deposit' ? 'added' : 'withdrawn'} successfully!`);
        loadAccounts(); // refresh list
      } else {
        alert("Error: " + (data.error || JSON.stringify(data)));
      }
    } catch (err) {
      console.error(err);
      alert("Something went wrong!");
    }
  }

  
  document.addEventListener("DOMContentLoaded", loadAccounts);
</script>
</body>
</html>