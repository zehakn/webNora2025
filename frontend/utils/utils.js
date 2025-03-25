var Utils = {
    init_spapp: () => {
        

        var app = $.spapp({
            defaultView: "#home",
            templateDir: "./views/"
        });

        app.route({ view: "register", load: "register.html" });
        app.route({ view: "login", load: "login.html" });
        app.route({ view: "home", load: "home.html" });
        app.route({ view: "tasks", load: "tasks.html" });
        app.route({ view: "add_task", load: "add_task.html" });
        app.route({ view: "about-contact", load: "about-contact.html" });
        app.route({ view: "profile", load:"profile.html "});
        console.log("Current Hash:", location.hash);
        app.run();
    }
};