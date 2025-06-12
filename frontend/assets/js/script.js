$(document).ready(function () {
    console.log("script.js: Document ready. Initializing SPAPP.");

    // Note on header/footer loading:
    // For an SPA, the main navigation (#navbar) and footer are typically part of the static index.html shell.
    // Dynamically loading them on every $(document).ready might be redundant or cause flickering.
    // Consider having them directly in your index.html.
    // If you must load them, ensure it doesn't interfere with SPApp's content loading.
    // For now, I'll keep them as you had them, but review this later.
    // $("#header").load("index.html #navbar"); // This might be problematic if #navbar is inside #spapp
    // $("#footer").load("index.html footer");

    var app = $.spapp({
        defaultView: "#home",
        templateDir: "views/", // This means profile.html should be at 'views/profile.html'
        // pageNotFound: "error_404.html" // Optional: if you have views/error_404.html
    });

    // Define the route for the #profile view
    app.route({
        view: 'profile',        // Matches <section id="profile"> and href="#profile"
        load: 'profile.html',   // SPApp will look for 'views/profile.html'
        onReady: function() {   // This function is executed when the #profile view is loaded and ready
            console.log('SPAPP: #profile view is READY. Attempting to call UserService.loadUserProfile().');
            if (typeof UserService !== 'undefined' && typeof UserService.loadUserProfile === 'function') {
                UserService.loadUserProfile();
            } else {
                console.error("SPAPP Error in #profile route: UserService.loadUserProfile function is NOT available!");
                toastr.error("Critical error: Profile loading component missing.");
            }
        }
    });

    // You can explicitly define other routes too for consistency or if they need callbacks
    // For example:
    // app.route({ view: 'home', load: 'home.html' });
    // app.route({ view: 'admin-dashboard', load: 'admin-dashboard.html' });
    // If you explicitly define routes here, remove the data-load attributes from the
    // corresponding <section> tags in index.html.

    app.run();
    console.log("SPAPP initialized and has run.");
});