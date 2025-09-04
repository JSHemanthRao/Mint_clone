<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bills - Dark UI</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-950 text-gray-100 min-h-screen flex flex-col">

  <!-- Top Navbar -->
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
            <span id="notificationBadge" class="absolute -top-1 -right-1 bg-red-500 text-xs text-white px-1 rounded-full">2</span>
          </button>

          <!-- Notification Dropdown -->
          <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-64 bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="p-4 border-b border-gray-700">
              <h3 class="text-lg font-semibold">Notifications</h3>
            </div>
            <ul class="max-h-60 overflow-y-auto">
              <li class="p-3 hover:bg-gray-700 cursor-pointer">🔔 New account created</li>
              <li class="p-3 hover:bg-gray-700 cursor-pointer">💰 Transaction of $250 added</li>
            </ul>
            <div class="p-3 text-center border-t border-gray-700">
              <button class="text-green-400 hover:underline">View All</button>
            </div>
          </div>
        </div>
      </nav>

      <!-- JS at bottom -->
      <script src="https://unpkg.com/feather-icons"></script>
      <script>
        feather.replace();

        const notificationBtn = document.getElementById("notificationBtn");
        const notificationDropdown = document.getElementById("notificationDropdown");
        const notificationBadge = document.getElementById("notificationBadge");

        let notificationsSeen = false; // Track if user already opened

        notificationBtn.addEventListener("click", () => {
          notificationDropdown.classList.toggle("hidden");

          // Hide badge once user opens dropdown for the first time
          if (!notificationsSeen) {
            notificationBadge.style.display = "none";
            notificationsSeen = true;
          }
        });

        // Close dropdown when clicking outside
        document.addEventListener("click", (e) => {
          if (!notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
            notificationDropdown.classList.add("hidden");
          }
        });
      </script>

      <button id="logoutBtn" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg font-semibold">Logout</button>
    </div>
  </header>

  <!-- Main Content -->
  <main class="flex-1 max-w-7xl mx-auto p-6 w-full">

    <!-- Section Heading -->
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-3xl font-semibold">Your Bills</h2>
    </div>

    <!-- Bills Grid -->
    <div id="billsGrid" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- Bills will be rendered dynamically -->
    </div>

  </main>

  <!-- Floating Action Button -->
  <button id="addBillBtn"
    class="fixed bottom-6 right-6 w-14 h-14 rounded-full bg-green-600 hover:bg-green-700 flex items-center justify-center shadow-lg text-2xl font-bold">
    +
  </button>

  <!-- Add/Edit Bill Modal -->
  <div id="billModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
    <div class="bg-gray-900/95 backdrop-blur-lg p-6 rounded-xl shadow-lg w-full max-w-md">
      <h2 id="modalTitle" class="text-xl font-semibold mb-4">Add Bill</h2>
      <form id="billForm" class="space-y-4">
        @csrf
        <input type="hidden" id="billId">

        <input type="text" id="billName" name="name" placeholder="Bill Name"
          class="w-full p-3 rounded-lg bg-gray-800 border border-gray-700 focus:ring-2 focus:ring-green-500 outline-none">

        <input type="number" id="billAmount" name="amount" placeholder="Amount"
          class="w-full p-3 rounded-lg bg-gray-800 border border-gray-700 focus:ring-2 focus:ring-green-500 outline-none">

        <input type="date" id="billDueDate" name="due_date"
          class="w-full p-3 rounded-lg bg-gray-800 border border-gray-700 focus:ring-2 focus:ring-green-500 outline-none">

        <button type="submit"
          class="w-full bg-green-600 hover:bg-green-700 py-3 rounded-lg font-medium">Save</button>
      </form>
      <button onclick="closeModal()" class="mt-4 text-gray-400 hover:underline w-full">Cancel</button>
    </div>
  </div>

  <!-- JS Script -->
  <script>
    const token = localStorage.getItem("jwt_token");
    if (!token) window.location.href = "/login";

    const billsGrid = document.getElementById("billsGrid");
    const modal = document.getElementById("billModal");
    const modalTitle = document.getElementById("modalTitle");
    const billForm = document.getElementById("billForm");
    const addBillBtn = document.getElementById("addBillBtn");
    const billIdField = document.getElementById("billId");

    let editingBillId = null;

    // Logout
    document.getElementById("logoutBtn").addEventListener("click", () => {
      localStorage.removeItem("jwt_token");
      window.location.href = "/login";
    });

    // Open Modal
    addBillBtn.addEventListener("click", () => {
      modalTitle.textContent = "Add Bill";
      billForm.reset();
      editingBillId = null;
      modal.classList.remove("hidden");
      modal.classList.add("flex");
    });

    function closeModal() {
      modal.classList.add("hidden");
    }

    // Render Bills
    function renderBillCard(bill) {
      let today = new Date().toISOString().split("T")[0];
      let isOverdue = bill.due_date < today;

      return `
        <div class="p-5 rounded-xl shadow-lg bg-gray-800 border ${isOverdue ? 'border-red-500' : 'border-green-500'}">
          <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold">${bill.name}</h3>
            <span class="px-2 py-1 text-xs rounded-md ${isOverdue ? 'bg-red-500/20 text-red-400' : 'bg-green-500/20 text-green-400'}">
              ${isOverdue ? 'Overdue' : 'Upcoming'}
            </span>
          </div>
          <p class="mt-2">💰 <span class="font-medium">₹${bill.amount}</span></p>
          <p>📅 ${bill.due_date}</p>
          <div class="flex justify-end space-x-3 mt-4">
            <button class="text-blue-400 hover:underline" onclick="editBill(${bill.id})">Edit</button>
            <button class="text-red-400 hover:underline" onclick="deleteBill(${bill.id})">Delete</button>
          </div>
        </div>
      `;
    }

    // Load Bills
    async function loadBills() {
      let res = await fetch('/api/bills', {
        headers: {
          'Authorization': 'Bearer ' + token
        }
      });
      let data = await res.json();

      if (res.ok) {
        billsGrid.innerHTML = "";
        data.forEach(bill => {
          billsGrid.innerHTML += renderBillCard(bill);
        });
      }
    }

    // Submit Bill Form
    billForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      let billData = {
        name: document.getElementById("billName").value.trim(),
        amount: document.getElementById("billAmount").value.trim(),
        due_date: document.getElementById("billDueDate").value
      };

      let url = "/api/bills" + (editingBillId ? `/${editingBillId}` : "");
      let method = editingBillId ? "PUT" : "POST";

      let res = await fetch(url, {
        method,
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify(billData)
      });

      if (res.ok) {
        closeModal();
        loadBills();
      }
    });

    // Edit Bill
    async function editBill(id) {
      let res = await fetch(`/api/bills/${id}`, {
        headers: {
          'Authorization': 'Bearer ' + token
        }
      });
      if (res.ok) {
        let bill = await res.json();
        editingBillId = bill.id;
        modalTitle.textContent = "Edit Bill";
        document.getElementById("billName").value = bill.name;
        document.getElementById("billAmount").value = bill.amount;
        document.getElementById("billDueDate").value = bill.due_date;
        modal.classList.remove("hidden");
        modal.classList.add("flex");
      }
    }

    // Delete Bill
    async function deleteBill(id) {
      if (confirm("Are you sure you want to delete this bill?")) {
        let res = await fetch(`/api/bills/${id}`, {
          method: "DELETE",
          headers: {
            'Authorization': 'Bearer ' + token
          }
        });
        if (res.ok) loadBills();
      }
    }

    window.onload = loadBills;
  </script>
</body>

</html>