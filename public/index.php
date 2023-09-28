<?php

// Require autoloader
require __DIR__.'/../vendor/autoload.php';

// Load .env (to $_ENV)
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

// Require app
require_once('../app.php');