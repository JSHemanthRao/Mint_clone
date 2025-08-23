<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 flex items-center justify-center min-h-screen">

  <div class="bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-lg w-full max-w-md">
    <h1 class="text-2xl sm:text-3xl font-bold mb-6 text-center text-white">Create Account</h1>

    <form id="registerForm" class="space-y-4" action="/api/register" method="POST">
      @csrf
      <!-- Name -->
      <div>
        <label class="block text-sm font-medium text-gray-300">Name</label>
        <input type="text" id="username" required placeholder="Enter your name" name="name"
          class="mt-1 w-full px-3 py-2 sm:px-4 sm:py-3 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base">
        <p id="error-username" class="text-red-500 text-xs mt-1 hidden"></p>
      </div>

      <!-- Email -->
      <div>
        <label class="block text-sm font-medium text-gray-300">Email</label>
        <input type="email" id="email" required placeholder="Enter your email" name="email"
          class="mt-1 w-full px-3 py-2 sm:px-4 sm:py-3 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base">
        <p id="error-email" class="text-red-500 text-xs mt-1 hidden"></p>
      </div>

      <!-- Password -->
      <div>
        <label class="block text-sm font-medium text-gray-300">Password</label>
        <input type="password" id="password" required placeholder="Enter your password" name="password"
          class="mt-1 w-full px-3 py-2 sm:px-4 sm:py-3 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base">
        <p id="error-password" class="text-red-500 text-xs mt-1 hidden"></p>
      </div>

      <!-- Confirm Password -->
      <div>
        <label class="block text-sm font-medium text-gray-300">Confirm Password</label>
        <input type="password" id="confirm_password" required placeholder="Confirm your password" name="confirm_password"
          class="mt-1 w-full px-3 py-2 sm:px-4 sm:py-3 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base">
        <p id="error-confirm-password" class="text-red-500 text-xs mt-1 hidden"></p>
      </div>


      <!-- Register Button -->
      <button type="submit"
        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 sm:py-3 rounded-lg text-sm sm:text-base">
        Register
      </button>
    </form>

    <!-- Redirect to Login -->
    <p class="mt-4 text-gray-400 text-center text-sm sm:text-base">
      Already have an account?
      <a href="/login" class="text-blue-400 hover:underline">Login</a>
    </p>
  </div>

  <script>
    localStorage.removeItem("jwt_token");
    document.getElementById('registerForm').addEventListener('submit', async function(event) {
      event.preventDefault();

      // Clear old errors
      document.querySelectorAll("p[id^='error-']").forEach(el => {
        el.textContent = "";
        el.classList.add("hidden");
      });

      let user = {
        name: document.getElementById('username').value.trim(),
        email: document.getElementById('email').value.trim(),
        password: document.getElementById('password').value,
        password_confirmation: document.getElementById('confirm_password').value
      };

      let res = await fetch('/api/register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify(user)
      });

      let data = await res.json();

      if (res.ok) {
        localStorage.setItem("jwt_token", data.token);
        window.location.href = "/dashboard";
      } else {
        if (data.errors) {
          // Laravel sends validation errors in "errors"
          if (data.errors.name) {
            let el = document.getElementById("error-username");
            el.textContent = data.errors.name[0];
            el.classList.remove("hidden");
          }
          if (data.errors.email) {
            let el = document.getElementById("error-email");
            el.textContent = data.errors.email[0];
            el.classList.remove("hidden");
          }
          if (data.errors.password) {
            let el = document.getElementById("error-password");
            el.textContent = data.errors.password[0];
            el.classList.remove("hidden");
          }
          if (data.errors.password_confirmation) {
            let el = document.getElementById("error-confirm-password");
            el.textContent = data.errors.password_confirmation[0];
            el.classList.remove("hidden");
          }
        } else if (data.error) {
          // For general errors
          let el = document.getElementById("error-email");
          el.textContent = data.error;
          el.classList.remove("hidden");
        }
      }
    });
  </script>


</body>

</html>