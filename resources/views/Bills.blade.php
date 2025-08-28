<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bills</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen flex">

    <!-- Navbar -->
    <nav class="bg-gray-800 fixed left-0 top-0 h-full w-64 p-6 flex flex-col">
        <h1 class="text-2xl font-bold mb-8">Mint</h1>
        <div class="flex flex-col space-y-3 flex-grow">
            <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg hover:bg-gray-700">Dashboard</a>
            <a href="{{ route('accounts') }}" class="px-3 py-2 rounded-lg hover:bg-gray-700">Accounts</a>
            <a href="{{ route('bills') }}" class="px-3 py-2 rounded-lg bg-gray-700">Bills</a>
            <a href="{{ route('budgets') }}" class="px-3 py-2 rounded-lg hover:bg-gray-700">Budgets</a>
            <a href="{{ route('categories') }}" class="px-3 py-2 rounded-lg hover:bg-gray-700">Categories</a>
            <a href="{{ route('transactions') }}" class="px-3 py-2 rounded-lg hover:bg-gray-700">Transactions</a>
            <a href="{{ route('goals') }}" class="px-3 py-2 rounded-lg hover:bg-gray-700">Goals</a>
            <a href="#" class="px-3 py-2 rounded-lg hover:bg-gray-700">Notifications</a>
            <a href="{{ route('profile') }}" class="px-3 py-2 rounded-lg hover:bg-gray-700">Profile</a>
        </div>
        <button id="logoutBtn" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg font-semibold">Logout</button>
    </nav>

    <!-- Main Content (stacked layout) -->
    <main class="ml-64 flex-1 p-8 space-y-8">

        <!-- Bills Form -->
        <div class="min-h-screen flex items-center justify-center bg-gray-900">
            <div class="bg-gray-800 p-8 rounded-2xl shadow-lg w-full max-w-md">
                <h2 class="text-2xl font-bold text-white mb-6 text-center">Add Bill</h2>

                <form id="billForm">
                    @csrf

                    <!-- Bill Name -->
                    <input type="text" name="name" placeholder="Bill Name" required
                        class="w-full p-3 mb-4 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <!-- Amount -->
                    <input type="number" name="amount" placeholder="Amount" required
                        class="w-full p-3 mb-4 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <!-- Due Date -->
                    <input type="date" name="due_date" required
                        class="w-full p-3 mb-4 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                        Save Bill
                    </button>
                </form>

                <!-- Success/Fail Popup -->
                <div id="popupMessage" class="hidden mt-4 p-3 rounded-lg text-center text-white font-semibold"></div>
            </div>
        </div>

        <script>
            document.getElementById("billForm").addEventListener("submit", async function(e) {
                e.preventDefault();

                let token = localStorage.getItem("jwt_token");
                if (!token) {
                    showPopup("Authentication failed: No token found", "bg-red-600");
                    return;
                }

                let formData = {
                    name: this.name.value,
                    amount: this.amount.value,
                    due_date: this.due_date.value,
                };

                try {
                    let response = await fetch("{{ route('bills.store') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "Authorization": "Bearer " + token
                        },
                        body: JSON.stringify(formData)
                    });

                    let data;
                    try {
                        data = await response.json();
                    } catch (parseErr) {
                        // fallback if server returns HTML
                        data = {
                            message: "Unexpected server response"
                        };
                    }

                    if (response.ok) {
                        showPopup("Bill saved successfully!", "bg-green-600");
                        this.reset();
                    } else {
                        showPopup("Failed to save bill: " + (data.message ?? "Invalid data"), "bg-red-600");
                        console.error("Error:", data);
                    }
                } catch (err) {
                    showPopup("Server error, try again later", "bg-red-600");
                    console.error("Catch:", err);
                }
            });

            function showPopup(message, colorClass) {
                let popup = document.getElementById("popupMessage");
                popup.innerText = message;
                popup.className = `mt-4 p-3 rounded-lg text-center text-white font-semibold ${colorClass}`;
                popup.classList.remove("hidden");
                setTimeout(() => popup.classList.add("hidden"), 3000);
            }
        </script>
    </main>
</body>

</html>