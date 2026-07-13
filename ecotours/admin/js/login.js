document.addEventListener("DOMContentLoaded", function () {
  const loginForm = document.getElementById("loginForm");
  const togglePassword = document.querySelector(".toggle-password");
  const passwordInput = document.getElementById("password");

  // Toggle password visibility
  togglePassword.addEventListener("click", function () {
    const type =
      passwordInput.getAttribute("type") === "password" ? "text" : "password";
    passwordInput.setAttribute("type", type);

    // Toggle eye icon
    const icon = this.querySelector("i");
    icon.classList.toggle("fa-eye");
    icon.classList.toggle("fa-eye-slash");
  });

  // Form submission
  loginForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const loginBtn = this.querySelector(".login-btn");
    const originalContent = loginBtn.innerHTML;

    // Show loading state
    loginBtn.innerHTML =
      '<i class="fas fa-circle-notch fa-spin"></i> Signing in...';
    loginBtn.disabled = true;

    const formData = {
      email: document.getElementById("email").value,
      password: document.getElementById("password").value,
    };

    fetch("../handlers/loginHandler.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(formData),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Show success notification
          showNotification(data.message, "success");
          setTimeout(() => {
            window.location.href = data.data.redirect;
          }, 1000);
        } else {
          // Show error notification
          showNotification(data.message, "error");
          loginBtn.innerHTML = originalContent;
          loginBtn.disabled = false;
        }
      })
      .catch((error) => {
        showNotification("An error occurred. Please try again.", "error");
        loginBtn.innerHTML = originalContent;
        loginBtn.disabled = false;
      });
  });

  function showNotification(message, type) {
    const notification = document.createElement("div");
    notification.className = `notification ${type}`;
    notification.innerHTML = message;
    document.body.appendChild(notification);

    setTimeout(() => {
      notification.classList.add("show");
      setTimeout(() => {
        notification.classList.remove("show");
        setTimeout(() => {
          notification.remove();
        }, 300);
      }, 3000);
    }, 100);
  }

  // Add input animation
  const inputs = document.querySelectorAll(".input-icon input");
  inputs.forEach((input) => {
    input.addEventListener("focus", function () {
      this.parentElement.classList.add("focused");
    });

    input.addEventListener("blur", function () {
      if (!this.value) {
        this.parentElement.classList.remove("focused");
      }
    });

    // Check initial state
    if (input.value) {
      input.parentElement.classList.add("focused");
    }
  });
});
