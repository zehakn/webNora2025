<?php

require 'vendor/autoload.php';
require_once __DIR__ . '../rest/routes/UserRoutes.php';
require_once __DIR__ . '../rest/routes/StatusRoutes.php';
require_once __DIR__ . '../rest/routes/NotesRoutes.php';
require_once __DIR__ . '../rest/routes/CategoryRoutes.php';
require_once __DIR__ . '../rest/routes/PriorityRoutes.php';

Flight::route('GET /', function() {
    echo 'Hello, FlightPHP!';
});

Flight::start();