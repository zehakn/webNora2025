$(document).ready(function () {
    // Load header and footer dynamically
    $("#header").load("index.html #navbar");
    $("#footer").load("index.html footer");

    // Initialize SPApp
    var app = $.spapp({
        defaultView: "#home",  // Default page
        templateDir: "views/"  // Folder where HTML files are stored
    });

    // Run the app
    app.run();
});
