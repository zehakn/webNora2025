var UserService = {
  init: function () {
    const path = window.location.pathname;
    const token = localStorage.getItem("user_token");
    const baseAppPath = "/NoraZehakWeb2025/webNora2025/frontend/";
    const isIndexPage = path === baseAppPath || path.endsWith("index.html") || path === baseAppPath + "index.html";

    console.log("UserService.init() - Path:", path, "IsIndexPage:", isIndexPage, "Token:", token);

    if ($.validator && !$.validator.methods.lettersandspacesonly) {
        $.validator.addMethod("lettersandspacesonly", function(value, element) {
            return this.optional(element) || /^[a-zA-ZčćžšđČĆŽŠĐ\s]+$/.test(value);
        }, "Please enter only letters and spaces.");
    }
    if ($.validator && !$.validator.methods.pwcheck) {
        $.validator.addMethod("pwcheck", function(value) {
            return /^(?=.*[A-Za-z])(?=.*\d).{6,}$/.test(value);
        }, "Password must be at least 6 characters and include at least one letter and one number.");
    }

    if (path.endsWith("login.html")) {
        console.log("On login.html");
        if (token && token !== "undefined") {
            console.log("Token found on login page, redirecting to index.html");
            window.location.replace("index.html"); 
            return;
        }
        if ($("#login-form").length) {
            $("#login-form").validate({
                rules: { username: { required: true, minlength: 3 }, password: { required: true, minlength: 6 } },
                messages: { username: { required: "Username is required.", minlength: "Username must be at least 3 characters." }, password: { required: "Password is required.", minlength: "Password must be at least 6 characters."}},
                submitHandler: function (form) {
                    var entity = Object.fromEntries(new FormData(form).entries());
                    UserService.login(entity, form);
                }
            });
        }
    } else if (path.endsWith("register.html")) {
        console.log("On register.html");
        if (token && token !== "undefined") {
            console.log("Token found on register page, redirecting to index.html");
            window.location.replace("index.html"); 
            return;
        }
        if ($("#register-form").length) {
            $("#register-form").validate({
                rules: { fullname: { required: true, minlength: 3, lettersandspacesonly: true }, email: { required: true, email: true }, username: { required: true, minlength: 3 }, password: { required: true, minlength: 6, pwcheck: true } },
                messages: { fullname: { required: "Full name is required.", minlength: "Full name must be at least 3 characters.", lettersandspacesonly: "Full name must contain only letters and spaces." }, email: { required: "Email is required.", email: "Please enter a valid email address." }, username: { required: "Username is required.", minlength: "Username must be at least 3 characters." }, password: { required: "Password is required.", minlength: "Password must be at least 6 characters.", pwcheck: "Password must include at least one letter and one number."}},
                submitHandler: function (form) {
                    var entity = Object.fromEntries(new FormData(form).entries());
                    UserService.register(entity, form);
                }
            });
        }
    } else { 
        console.log("On index.html or base path. Generating menu items.");
      
        if (typeof UserService.generateMenuItems === 'function') {
            UserService.generateMenuItems();
        } else {
            console.error("generateMenuItems function is not defined!");
        }
    }
  },

  login: function (entity, formEl) {
    console.log("UserService.login called with:", entity);
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "auth/login",
      type: "POST", data: JSON.stringify(entity), contentType: "application/json", dataType: "json",
      success: function (result) {
        if (result.data && result.data.token) {
            localStorage.setItem("user_token", result.data.token);
            if (formEl) { formEl.reset(); }
            window.location.replace("index.html");
        } else { toastr.error('Login failed: No token received.'); }
      },
      error: function (XMLHttpRequest) {
        console.error("Login failed:", XMLHttpRequest.responseText || "Unknown error");
        toastr.error("Login failed. Please check your credentials and try again.");
      }
    });
  },

  register: function (entity, formEl) {
    console.log("UserService.register called with entity:", entity);
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "auth/register",
      type: "POST", data: JSON.stringify(entity), contentType: "application/json", dataType: "json",
      success: function (result) {
        toastr.success("Registration successful! Please login.");
        if (formEl) { formEl.reset(); }
        window.location.replace("login.html");
      },
      error: function (XMLHttpRequest) {
        console.error("Registration failed:", XMLHttpRequest.responseText || "Unknown error");
        toastr.error("Registration failed. Please try again.");
      }
    });
  },

  logout: function () {
    console.log("UserService.logout() called.");
    localStorage.removeItem("user_token");
    toastr.info("You have been logged out.");
    window.location.replace("index.html");
  },

  generateMenuItems: function () {
    console.log("UserService.generateMenuItems() called.");
    const token = localStorage.getItem("user_token");
    let nav = "";

    if (token && token !== "undefined") {
        let user = null;
        try {
          user = Utils.parseJwt(token).user;
          if (!user || !user.role) { throw new Error("Token invalid: missing user or role."); }
        } catch (e) {
          console.error("Token parsing error or invalid token:", e);
          localStorage.removeItem("user_token"); 
          
          nav = `<li class="nav-item"><a class="nav-link fs-5" aria-current="page" href="#home">Home</a></li>
                 <li class="nav-item"><a class="nav-link fs-5" href="login.html">Login</a></li>`;
          $("#menuTabs").html(nav);
          
          if ($.spapp && window.location.hash !== "#home" && window.location.pathname.match(/(\/index\.html|\/)$/)) {
            try { $.spapp.navigate("#home"); } catch(spappError){ console.warn("SPApp navigation error on bad token", spappError); }
          }
          return;
        }

        console.log("Logged-in. Role:", user.role);
        nav += `<li class="nav-item"><a class="nav-link fs-5" aria-current="page" href="#home">Home</a></li>`;
        if (user.role === Constants.USER_ROLE) {
          nav += `<li class="nav-item"><a class="nav-link fs-5" href="#profile">Profile</a></li>`;
        } else if (user.role === Constants.ADMIN_ROLE) {
          nav += `<li class="nav-item"><a class="nav-link fs-5" href="#profile">Profile</a></li>`;
          nav += `<li class="nav-item"><a class="nav-link fs-5" href="#admin-dashboard">Dashboard</a></li>`;
        } else {
          console.warn("Unknown user role:", user.role, ". Defaulting to public menu after clearing token.");
          localStorage.removeItem("user_token");
          nav = `<li class="nav-item"><a class="nav-link fs-5" aria-current="page" href="#home">Home</a></li>
                 <li class="nav-item"><a class="nav-link fs-5" href="login.html">Login</a></li>`;
          toastr.error("Unknown user role. Please login again.");
        }
        nav += `<li class="nav-item"><a class="nav-link fs-5" href="#" id="logoutButton">Logout</a></li>`;
    } else {
        // Not logged in - Public Menu: Home and Login only
        console.log("No token. Generating public menu (Home, Login).");
        nav = `<li class="nav-item"><a class="nav-link fs-5" aria-current="page" href="#home">Home</a></li>
               <li class="nav-item"><a class="nav-link fs-5" href="login.html">Login</a></li>`;
    }
    $("#menuTabs").html(nav);
  }
};



$(document).ready(function () {
  console.log("Document ready. Calling UserService.init().");
  UserService.init();
  $(document).on("click", "#logoutButton", function (e) {
    e.preventDefault();
    UserService.logout();
  });
});
