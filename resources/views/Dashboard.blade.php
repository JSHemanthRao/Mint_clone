<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col lg:flex-row">
  <!-- Navbar -->
  <nav class="bg-gray-800 fixed left-0 top-0 h-full w-64 p-6 flex flex-col">
    <h1 class="text-2xl font-bold mb-8">Mint</h1>
    <div class="flex flex-col space-y-3 flex-grow">
      <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg bg-gray-700">Dashboard</a>
      <a href="{{ route('accounts') }}" class="px-3 py-2 rounded-lg hover:bg-gray-700">Accounts</a>
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
  <main class="flex-1 p-4 sm:p-6 space-y-6 lg:ml-64">
    <h2 class="text-2xl font-bold mb-4">Welcome!</h2>

    <!-- Accounts Section -->
    <div>
      <div class="flex items-center justify-between mb-2">
        <h3 class="text-xl font-semibold">Your Accounts</h3>
      </div>
      <div id="accountsList" class="h-40 overflow-y-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
    </div>

    <!-- Bills Section -->
    <div>
      <h3 class="text-xl font-semibold mb-2">Bills</h3>
      <div id="billsList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
    </div>

    <!-- Budgets Section -->
    <div>
      <h3 class="text-xl font-semibold mb-2">Budgets</h3>
      <div id="budgetsList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
    </div>

    <!-- Goals Section -->
    <div>
      <h3 class="text-xl font-semibold mb-2">Goals</h3>
      <div id="goalsList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
    </div>
  </main>

  <script>
    const token = localStorage.getItem("jwt_token");
    if (!token) {
      window.location.href = "/login";
    }

    function eyeSVG() {
      return `
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>`;
    }

    function eyeOffSVG() {
      return `
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c1.647 0 3.206-.355 4.611-.99M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.5a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243L9.88 9.88" />
          </svg>`;
    }

    async function fetchData(endpoint, containerId) {
      try {
        const res = await fetch(`/api/${endpoint}`, {
          headers: {
            Authorization: "Bearer " + token
          },
        });
        const data = await res.json();

        const container = document.getElementById(containerId);
        container.innerHTML = "";

        if (!data || data.length === 0) {
          const emptyMsg = document.createElement("div");
          emptyMsg.className = "bg-gray-700 text-gray-300 p-4 rounded-xl text-center";
          emptyMsg.innerHTML = `No ${endpoint} found`;
          container.appendChild(emptyMsg);
          return;
        }

        const inr = new Intl.NumberFormat("en-IN", {
          maximumFractionDigits: 2
        });

        data.forEach((item) => {
          const card = document.createElement("div");
          card.className = "bg-gray-800 p-4 rounded-xl shadow text-sm sm:text-base";

          let status = "";

          if (endpoint === "accounts") {
            // header
            const title = document.createElement("h4");
            title.className = "font-bold";
            title.textContent = item.name;

            const type = document.createElement("p");
            type.className = "text-gray-400";
            type.textContent = `Account Type: ${item.type}`;

            // balance row (masked by default)
            let isHidden = true;
            const balanceRow = document.createElement("div");
            balanceRow.className = "flex items-center gap-2 mt-1";

            const balanceEl = document.createElement("span");
            balanceEl.className = "text-gray-300";
            const renderBalance = () => {
              balanceEl.textContent = isHidden ? `Balance: ₹ ••••` : `Balance: ₹${inr.format(Number(item.balance || 0))}`;
              toggleBtn.innerHTML = isHidden ? eyeOffSVG() : eyeSVG();
              toggleBtn.setAttribute("aria-label", isHidden ? "Show balance" : "Hide balance");
              toggleBtn.title = isHidden ? "Show balance" : "Hide balance";
            };

            const toggleBtn = document.createElement("button");
            toggleBtn.type = "button";
            toggleBtn.className = "p-1 rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500";
            toggleBtn.addEventListener("click", () => {
              isHidden = !isHidden;
              renderBalance();
            });

            renderBalance();

            balanceRow.appendChild(balanceEl);
            balanceRow.appendChild(toggleBtn);

            card.appendChild(title);
            card.appendChild(type);
            card.appendChild(balanceRow);
          } else if (endpoint === "bills") {
            const today = new Date().toISOString().split("T")[0];
            if (item.due_date < today) {
              card.classList.add("bg-red-700/30", "border", "border-red-500");
              status = "Overdue";
            } else {
              card.classList.add("bg-green-900/30", "border", "border-green-500");
              status = "Upcoming";
            }
            card.innerHTML = `
                <h4 class="font-bold">Status: ${status}</h4>
                <h4 class="font-bold">Name: ${item.name}</h4>
                <p class="text-gray-400">Amount: ₹${inr.format(Number(item.amount || 0))}</p>
              `;
          } else if (endpoint === "budgets") {
            card.innerHTML = `
                <h4 class="font-bold">${item.category ? item.category.name : "No Category"}</h4>
                <p class="text-gray-400">Amount: ₹${inr.format(Number(item.amount || 0))}</p>
              `;
          } else if (endpoint === "goals") {
            card.innerHTML = `
                <h4 class="font-bold">${item.name}</h4>
                <p class="text-gray-400">Target: ₹${inr.format(Number(item.target_amount || 0))}</p>
                <p class="text-gray-400">Current: ₹${inr.format(Number(item.current_amount || 0))}</p>
                <p class="text-gray-400">Due Date: ${item.due_date}</p>
              `;
          } else {
            card.innerHTML = `<h4 class="font-bold">${item.name || item.title}</h4>`;
          }

          container.appendChild(card);
        });
      } catch (err) {
        console.error(`Error loading ${endpoint}:`, err);
      }
    }

    // Call APIs
    fetchData("accounts", "accountsList");
    fetchData("bills", "billsList");
    fetchData("budgets", "budgetsList");
    fetchData("goals", "goalsList");

    // Logout
    document.getElementById("logoutBtn").addEventListener("click", () => {
      localStorage.removeItem("jwt_token");
      window.location.href = "/login";
    });
  </script>
</body>

</html>