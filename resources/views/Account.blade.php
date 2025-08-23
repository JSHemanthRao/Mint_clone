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

        <!-- Account Name -->
        <div>
            <label for="name" class="block text-gray-300 mb-1 font-medium">Account Name</label>
            <input type="text" name="name" id="name" placeholder="e.g. Salary Account" autocomplete="off"
                   class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
        </div>

        <!-- Account Type (Dropdown) -->
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

        <!-- Balance -->
        <div>
            <label for="balance" class="block text-gray-300 mb-1 font-medium">Balance</label>
            <input type="number" name="balance" id="balance" placeholder="Enter balance" autocomplete="off"
                   class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg shadow-md transition duration-200">
            Save Account
        </button>
    </form>
</div>

<!-- Success Toast -->
<div id="successToast" class="hidden fixed top-0 left-1/2 transform -translate-x-1/2 mt-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg text-lg font-semibold transition-all duration-500">
    Account created successfully!
</div>

<script>
    document.getElementById("accountForm").addEventListener("submit", async function(e) {
        e.preventDefault();

        let token = localStorage.getItem("jwt_token"); 
        let formData = {
            name: document.getElementById("name").value,
            type: document.getElementById("type").value,
            balance: document.getElementById("balance").value,
        };

        try {
            let response = await fetch("{{ route('accounts.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": "Bearer " + token,
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(formData)
            });

            if (response.ok) {
                // Show toast popup
                let toast = document.getElementById("successToast");
                toast.classList.remove("hidden");
                toast.classList.add("opacity-100");

                // Hide after 3 seconds
                setTimeout(() => {
                    toast.classList.add("hidden");
                }, 3000);

                // Reset form
                document.getElementById("accountForm").reset();
            } else {
                alert("Error creating account");
            }
        } catch (error) {
            console.error("Error:", error);
            alert("Something went wrong!");
        }
    });
</script>




    <p class="text-sm text-gray-400 mt-6 text-center">
      <a href="/dashboard" class="text-indigo-400 hover:underline">← Back to Dashboard</a>
    </p>

    <!-- Accounts List -->
    <div class="bg-gray-800/90 backdrop-blur-lg p-6 rounded-2xl shadow-lg w-full max-w-5xl mx-auto">
      <h2 class="text-2xl font-semibold text-center mb-6">Accounts List</h2>
      <div id="accountsList"
        class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 max-h-[500px] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-gray-800">
        <!-- Accounts will be dynamically loaded here -->
      </div>
    </div>
  </div>


  <script>
    const token = localStorage.getItem("jwt_token");
    if (!token) {
      window.location.href = "/login";
    }

    // Logout
    document.getElementById("logoutBtn").addEventListener("click", async function() {
      localStorage.removeItem("jwt_token");
      window.location.href = "/login";
    });

    const accountsList = document.getElementById("accountsList");

    // Function to render a single account card
    function renderAccount(account) {
      accountsList.innerHTML += `
        <div class="bg-gray-700 p-4 rounded-lg shadow-md w-full h-[150px] flex flex-col justify-between">
          <h3 class="text-lg font-semibold truncate">${account.name}</h3>
          <p>Balance: <span class="font-medium">₹${account.balance}</span></p>
          <p>Type: ${account.type}</p>
          <div class="flex justify-end space-x-2">
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
    // Load all accounts on page load
    async function loadAccounts() {
      try {
        let res = await fetch('/api/accounts', {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token
          }
        });

        let data = await res.json();

        if (res.ok) {
          accountsList.innerHTML = ""; // clear before adding
          data.forEach(account => renderAccount(account));
        } else {
          alert("Failed to load accounts: " + JSON.stringify(data));
        }
      } catch (error) {
        console.error("Error loading accounts", error);
      }
    }

    // Handle form submission (Add new account)
    document.getElementById('accountsForm').addEventListener('submit', async function(event) {
      event.preventDefault();

      // Reset errors
      document.getElementById("nameError").classList.add("hidden");
      document.getElementById("balanceError").classList.add("hidden");
      document.getElementById("typeError").classList.add("hidden");

      let name = document.querySelector('input[name="name"]').value.trim();
      let balance = document.querySelector('input[name="balance"]').value.trim();
      let type = document.querySelector('select[name="type"]').value;

      let valid = true;

      if (!name) {
        document.getElementById("nameError").classList.remove("hidden");
        valid = false;
      }

      if (!balance) {
        document.getElementById("balanceError").classList.remove("hidden");
        valid = false;
      }

      if (!type) {
        document.getElementById("typeError").classList.remove("hidden");
        valid = false;
      }

      if (!valid) return; // Stop if validation fails

      let accountData = {
        name,
        balance,
        type
      };

      let res = await fetch('/api/accounts', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify(accountData)
      });

      let data = await res.json();

      if (res.ok) {
        renderAccount(data);
        document.getElementById('accountsForm').reset();
      } else {
        // show backend validation errors inline
        if (data.errors) {
          if (data.errors.name) {
            document.getElementById("nameError").innerText = data.errors.name[0];
            document.getElementById("nameError").classList.remove("hidden");
          }
          if (data.errors.balance) {
            document.getElementById("balanceError").innerText = data.errors.balance[0];
            document.getElementById("balanceError").classList.remove("hidden");
          }
          if (data.errors.type) {
            document.getElementById("typeError").innerText = data.errors.type[0];
            document.getElementById("typeError").classList.remove("hidden");
          }
        }
      }
    });


    // Call loadAccounts() when page loads
    window.onload = loadAccounts;
  </script>

</body>

</html>