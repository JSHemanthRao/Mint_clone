

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 flex items-center justify-center h-screen">
  <div class="w-full max-w-md bg-gray-800 p-8 rounded-2xl shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

    <form id="loginForm" class="space-y-4" action="/api/login" method="POST">
      @csrf

      <!-- Email -->
      <div>
        <input type="email" id="login_email" name="email" placeholder="Enter email"
          class="w-full px-4 py-2 rounded-lg bg-gray-700 text-gray-100 focus:outline-none focus:ring focus:ring-indigo-500">
        <p id="emailError" class="text-red-500 text-sm mt-1 hidden"></p>
      </div>

      <!-- Password -->
      <div>
        <input type="password" id="login_password" name="password" placeholder="Enter Password"
          class="w-full px-4 py-2 rounded-lg bg-gray-700 text-gray-100 focus:outline-none focus:ring focus:ring-indigo-500">
        <p id="passwordError" class="text-red-500 text-sm mt-1 hidden"></p>
      </div>

      <!-- Submit -->
      <button type="submit"
        class="w-full bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg font-semibold">
        Login
      </button>

      <!-- General error -->
      <p id="generalError" class="text-red-500 text-sm mt-2 text-center hidden"></p>
    </form>

    <p class="mt-4 text-sm text-center">
      Don’t have an account? <a href="/" class="text-indigo-400 hover:underline">Register</a>
    </p>
  </div>

  <script>
    localStorage.removeItem("jwt_token");

    document.getElementById('loginForm').addEventListener('submit', async function(event){
      event.preventDefault();

      // Reset error messages
      document.getElementById('emailError').classList.add('hidden');
      document.getElementById('passwordError').classList.add('hidden');
      document.getElementById('generalError').classList.add('hidden');

      let email = document.getElementById('login_email').value.trim();
      let password = document.getElementById('login_password').value.trim();
      let hasError = false;



      // Client-side validation
      if (!email) {
        document.getElementById('emailError').textContent = "Email is required.";
        document.getElementById('emailError').classList.remove('hidden');
        hasError = true;
      } else if (!/\S+@\S+\.\S+/.test(email)) {
        document.getElementById('emailError').textContent = "Enter a valid email.";
        document.getElementById('emailError').classList.remove('hidden');
        hasError = true;
      }

      if (!password) {
        document.getElementById('passwordError').textContent = "Password is required.";
        document.getElementById('passwordError').classList.remove('hidden');
        hasError = true;
      }

      if (hasError) return;

      // API request
      let res = await fetch('/api/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ email, password })
      });

      let data = await res.json();

      if (res.ok) {
        localStorage.setItem("jwt_token", data.token);
        window.location.href = "/dashboard";
      } else {
        document.getElementById('generalError').textContent =
          data.error || "Login failed. Please check credentials.";
        document.getElementById('generalError').classList.remove('hidden');
      }

      if (localStorage.getItem("token")) {
      window.location.href = "/dashboard";
    }

    // Fake login logic (replace with API call)
    document.getElementById("loginForm").addEventListener("submit", (e) => {
      e.preventDefault();
      localStorage.setItem("token", "fake-jwt-token");
      window.location.href = "/dashboard";
    });
      
    });
  </script>

</body>
</html>