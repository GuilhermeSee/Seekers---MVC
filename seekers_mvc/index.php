<?php
require_once 'config/app.php';
require_once 'config/database.php';

$router = require_once 'config/routes.php';
$router->dispatch();