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

@include('header')


    <main class="flex-1 p-6 flex flex-col items-center gap-8">

      <!-- ADD TRANSACTION FORM -->
      <div class="bg-gray-800 p-6 rounded-xl w-full max-w-md">
        <h2 class="text-xl font-semibold text-center mb-4">Add Transaction</h2>

        <form id="transactionsForm" class="space-y-4">

          <input name="description" type="text" placeholder="Description"
            class="w-full p-3 bg-gray-700 rounded" required>

          <input name="amount" type="number" placeholder="Amount"
            class="w-full p-3 bg-gray-700 rounded" required>

          <input name="date" type="date"
            class="w-full p-3 bg-gray-700 rounded" required>

          <!-- ✅ FIX: TYPE FIELD -->
          <select name="type" class="w-full p-3 bg-gray-700 rounded" required>
            <option value="">-- Select Type --</option>
            <option value="income">Income</option>
            <option value="expense">Expense</option>
          </select>

          <select name="category_id" class="w-full p-3 bg-gray-700 rounded" required>
            <option value="">-- Select Category --</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
          </select>

          <select name="account_id" id="account_id_select"
            class="w-full p-3 bg-gray-700 rounded" required>
            <option value="">-- Select Account --</option>
          </select>

          <button type="submit"
            class="w-full bg-orange-600 py-3 rounded font-semibold">
            Save Transaction
          </button>

        </form>
      </div>

      <!-- ACCOUNTS -->
      <div class="w-full max-w-3xl bg-gray-800 p-4 rounded-xl">
        <h2 class="text-lg font-semibold mb-3 text-center">Account Balances</h2>
        <div id="accountsList" class="grid grid-cols-2 gap-3"></div>
      </div>

      <!-- TRANSACTIONS LIST -->
      <div class="w-full max-w-5xl bg-gray-800 p-6 rounded-xl">
        <h2 class="text-xl font-semibold text-center mb-4">Transactions</h2>
        <div id="transactionsList" class="grid grid-cols-3 gap-4"></div>
      </div>

    </main>

    <script>
      feather.replace();

      const token = localStorage.getItem("jwt_token");
      if (!token) location.href = "/login";

      // LOGOUT
      document.getElementById("logoutBtn").onclick = () => {
        localStorage.removeItem("jwt_token");
        location.href = "/login";
      };

      // LOAD ACCOUNTS
      async function loadAccounts() {
        const res = await fetch('/api/accounts', {
          headers: {
            Authorization: 'Bearer ' + token
          }
        });
        const data = await res.json();

        const select = document.getElementById('account_id_select');
        const list = document.getElementById('accountsList');

        select.innerHTML = `<option value="">-- Select Account --</option>`;
        list.innerHTML = "";

        data.forEach(a => {
          select.innerHTML += `<option value="${a.id}">${a.name}</option>`;
          list.innerHTML += `
      <div class="bg-gray-700 p-3 rounded flex justify-between">
        <span>${a.name}</span>
        <span>₹${a.balance}</span>
      </div>`;
        });
      }

      // LOAD TRANSACTIONS
      async function loadTransactions() {
        const res = await fetch('/api/transactions', {
          headers: {
            Authorization: 'Bearer ' + token
          }
        });
        const data = await res.json();

        const list = document.getElementById('transactionsList');
        list.innerHTML = "";

        data.forEach(t => {
          list.innerHTML += `
      <div class="bg-gray-700 p-4 rounded">
        <h3 class="font-semibold">${t.description}</h3>
        <p>₹${t.amount}</p>
        <p class="text-sm">${t.type}</p>
        <p class="text-sm">${t.date}</p>
      </div>`;
        });
      }

      // SUBMIT FORM
      document.getElementById('transactionsForm').addEventListener('submit', async e => {
        e.preventDefault();

        const data = {
          description: e.target.description.value,
          amount: parseFloat(e.target.amount.value),
          date: e.target.date.value,
          type: e.target.type.value,
          category_id: e.target.category_id.value,
          account_id: e.target.account_id.value,
        };

        const res = await fetch('/api/transactions', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
          },
          body: JSON.stringify(data)
        });

        const result = await res.json();

        if (!res.ok) {
          alert(result.message);
          return;
        }

        e.target.reset();
        loadAccounts();
        loadTransactions();
      });

      window.onload = () => {
        loadAccounts();
        loadTransactions();
      };
    </script>

  </body>

  </html>