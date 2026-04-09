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

      <!-- Notification Bell -->
      <div class="relative">
        <button id="notificationBtn" class="p-2 hover:bg-gray-700 rounded-full relative">
          <i data-feather="bell" class="w-5 h-5"></i>
          <span id="notificationBadge" class="absolute -top-1 -right-1 bg-red-500 text-xs text-white px-1 rounded-full hidden">0</span>
        </button>
        <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-64 bg-gray-800 rounded-lg shadow-lg overflow-hidden">
          <div class="p-4 border-b border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-semibold">Notifications</h3>
            <button id="clearNotifications" class="text-red-400 text-sm hover:underline">Clear All</button>
          </div>
          <ul id="notificationList" class="max-h-60 overflow-y-auto"></ul>
        </div>
      </div>
    </nav>

    <button id="logoutBtn" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg font-semibold">Logout</button>
  </div>
</header>