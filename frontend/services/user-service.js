/**
 * User Service for handling all user-related operations in the frontend
 */
var UserService = {
    /**
     * Initialize the user service - check authentication and set up login form
     */
    init: function() {
        var token = localStorage.getItem("user_token");
        if (token && token !== undefined) {
            // Check if we're on login.html
            if (window.location.pathname.endsWith('login.html')) {
                window.location.replace("../index.html");
            }
        }
        
        // Set up login form validation if the form exists
        if ($("#login-form").length) {
            $("#login-form").validate({
                submitHandler: function(form) {
                    var entity = Object.fromEntries(new FormData(form).entries());
                    UserService.login(entity);
                },
            });
        }
    },
    
    /**
     * Authenticate user
     * @param {Object} entity - Login credentials
     */
    login: function(entity) {
        // Define the full API URL to avoid relative path issues
        const apiUrl = window.location.hostname === "127.0.0.1" || window.location.hostname === "localhost" 
            ? "http://localhost/NoraZehakWeb2025/webNora2025/backend/auth/login" 
            : Constants.PROJECT_BASE_URL + "auth/login";
            
        $.ajax({
            url: apiUrl,
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
            xhrFields: {
                withCredentials: true
            },
            crossDomain: true,
            success: function(result) {
                console.log("Login successful:", result);
                localStorage.setItem("user_token", result.data.token);
                toastr.success("Login successful!");
                
                // Detect if we're on a standalone login page or in SPA
                if (window.location.pathname.endsWith('login.html')) {
                    window.location.replace("../index.html");
                } else {
                    window.location.replace("index.html");
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.error("Login error:", textStatus, errorThrown);
                toastr.error(XMLHttpRequest?.responseText ? XMLHttpRequest.responseText : 'Login failed. Please check your credentials.');
            },
        });
    },
    
    /**
     * Log out the current user
     */
    logout: function() {
        localStorage.clear();
        toastr.info("You have been logged out");
        window.location.replace("login.html");
    },
    
    /**
     * Generate navigation menu based on user role
     */
    generateMenuItems: function() {
        try {
            const token = localStorage.getItem("user_token");
            if (!token) {
                window.location.replace("login.html");
                return;
            }
            
            const user = Utils.parseJwt(token).user;
            
            if (user && user.role) {
                let nav = "";
                let main = "";
                
                switch(user.role) {
                    case Constants.USER_ROLE:
                        nav = '<li class="nav-item mx-0 mx-lg-1">'+
                                '<a class="nav-link py-3 px-0 px-lg-3 rounded " href="#notes">My Notes</a>'+
                            '</li>'+
                            '<li class="nav-item mx-0 mx-lg-1">'+
                                '<a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#profile">My Profile</a>'+
                            '</li>'+
                            '<li>'+
                                '<button class="btn btn-primary" onclick="UserService.logout()">Logout</button>'+
                            '</li>';
                        $("#tabs").html(nav);
                        
                        main = '<section id="notes" data-load="notes.html"></section>'+
                               '<section id="profile" data-load="profile.html"></section>';
                        $("#spapp").html(main);
                        break;
                        
                    case Constants.ADMIN_ROLE:
                        nav = '<li class="nav-item mx-0 mx-lg-1">'+
                                '<a class="nav-link py-3 px-0 px-lg-3 rounded " href="#users">Users</a>'+
                            '</li>'+
                            '<li class="nav-item mx-0 mx-lg-1">'+
                                '<a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#notes">Notes</a>'+
                            '</li>'+
                            '<li class="nav-item mx-0 mx-lg-1">'+
                                '<a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#admin-dashboard">Admin Panel</a>'+
                            '</li>'+
                            '<li>'+
                                '<button class="btn btn-primary" onclick="UserService.logout()">Logout</button>'+
                            '</li>';
                        $("#tabs").html(nav);
                        
                        main = '<section id="users" data-load="users.html"></section>'+
                               '<section id="notes" data-load="notes.html"></section>'+
                               '<section id="admin" data-load="admin-dashboard.html"></section>';
                        $("#spapp").html(main);
                        break;
                        
                    default:
                        $("#tabs").html('');
                        $("#spapp").html('');
                        window.location.replace("login.html");
                }
                
                // Display user info if the element exists
                if ($("#user-info").length) {
                    $("#user-info").html(`<span>Welcome, ${user.fullName || user.username}</span>`);
                }
                
            } else {
                window.location.replace("login.html");
            }
        } catch (e) {
            console.error("Error generating menu:", e);
            window.location.replace("login.html");
        }
    },
    
    /**
     * Get all users (Admin only)
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    getAllUsers: function(successCallback, errorCallback) {
        // Ensure we're using the correct base URL
        const apiUrl = window.location.hostname === "127.0.0.1" || window.location.hostname === "localhost" 
            ? "http://localhost/NoraZehakWeb2025/webNora2025/backend/test-users" 
            : Constants.PROJECT_BASE_URL + "test-users";
            
        $.ajax({
            url: apiUrl,
            type: "GET",
            contentType: "application/json",
            dataType: "json",
            xhrFields: {
                withCredentials: true
            },
            crossDomain: true,
            beforeSend: function(xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            success: function(result) {
                if (successCallback) successCallback(result);
            },
            error: function(xhr) {
                if (errorCallback) {
                    errorCallback(xhr.responseText || "Failed to fetch users");
                } else {
                    toastr.error(xhr.responseText || "Failed to fetch users");
                }
            }
        });
    },
    
    /**
     * Get user by ID
     * @param {Number} id - User ID
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    getUserById: function(id, successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "test-user/" + id,
            type: "GET",
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            success: function(result) {
                if (successCallback) successCallback(result);
            },
            error: function(xhr) {
                if (errorCallback) {
                    errorCallback(xhr.responseText || "Failed to fetch user");
                } else {
                    toastr.error(xhr.responseText || "Failed to fetch user");
                }
            }
        });
    },
    
    /**
     * Get user by email (Admin only)
     * @param {String} email - User email
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    getUserByEmail: function(email, successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "test-user-by-email/" + email,
            type: "GET",
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            success: function(result) {
                if (successCallback) successCallback(result);
            },
            error: function(xhr) {
                if (errorCallback) {
                    errorCallback(xhr.responseText || "Failed to fetch user by email");
                } else {
                    toastr.error(xhr.responseText || "Failed to fetch user by email");
                }
            }
        });
    },
    
    /**
     * Create a new user (Admin only)
     * @param {Object} userData - User data
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    createUser: function(userData, successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "test-user",
            type: "POST",
            data: JSON.stringify(userData),
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            success: function(result) {
                toastr.success("User created successfully");
                if (successCallback) successCallback(result);
            },
            error: function(xhr) {
                if (errorCallback) {
                    errorCallback(xhr.responseText || "Failed to create user");
                } else {
                    toastr.error(xhr.responseText || "Failed to create user");
                }
            }
        });
    },
    
    /**
     * Update an existing user
     * @param {Number} id - User ID
     * @param {Object} userData - Updated user data
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    updateUser: function(id, userData, successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "test-user/" + id,
            type: "PUT",
            data: JSON.stringify(userData),
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            success: function(result) {
                toastr.success("User updated successfully");
                if (successCallback) successCallback(result);
            },
            error: function(xhr) {
                if (errorCallback) {
                    errorCallback(xhr.responseText || "Failed to update user");
                } else {
                    toastr.error(xhr.responseText || "Failed to update user");
                }
            }
        });
    },
    
    /**
     * Delete a user (Admin only)
     * @param {Number} id - User ID
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    deleteUser: function(id, successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "test-user/" + id,
            type: "DELETE",
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            success: function(result) {
                toastr.success("User deleted successfully");
                if (successCallback) successCallback(result);
            },
            error: function(xhr) {
                if (errorCallback) {
                    errorCallback(xhr.responseText || "Failed to delete user");
                } else {
                    toastr.error(xhr.responseText || "Failed to delete user");
                }
            }
        });
    },
    
    /**
     * Get current logged-in user information
     * @returns {Object|null} User object or null if not logged in
     */
    getCurrentUser: function() {
        const token = localStorage.getItem("user_token");
        if (!token) return null;
        
        try {
            return Utils.parseJwt(token).user;
        } catch (e) {
            console.error("Error parsing token:", e);
            return null;
        }
    },
    
    /**
     * Check if current user is an admin
     * @returns {Boolean} True if user is admin
     */
    isAdmin: function() {
        const user = this.getCurrentUser();
        return user && user.role === Constants.ADMIN_ROLE;
    }
};

// Initialize user service when document is ready
$(document).ready(function() {
    UserService.init();
});
