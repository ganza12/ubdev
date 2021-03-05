<?php
session_start();
require 'vendor/autoload.php';

use Src\System\DatabaseConnector;

$connection = (new DatabaseConnector())->getConnection();


?>