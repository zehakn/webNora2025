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
    },


 datatable: function (table_id, columns, data, pageLength=15) {
       if ($.fn.dataTable.isDataTable("#" + table_id)) {
         $("#" + table_id)
           .DataTable()
           .destroy();
       }
       $("#" + table_id).DataTable({
         data: data,
         columns: columns,
         pageLength: pageLength,
         lengthMenu: [2, 5, 10, 15, 25, 50, 100, "All"],
       });
     },
     parseJwt: function(token) {
       if (!token) return null;
       try {
         const payload = token.split('.')[1];
         const decoded = atob(payload);
         return JSON.parse(decoded);
       } catch (e) {
         console.error("Invalid JWT token", e);
         return null;
       }
     }  
};
// Export the Utils object for use in other modules
