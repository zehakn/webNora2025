<?php

require 'vendor/autoload.php';

Flight::route('GET /', function() {
    echo 'Hello, FlightPHP!';
});

Flight::start();
