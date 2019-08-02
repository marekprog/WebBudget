<?php
$config= require_once 'config.php';
$postgres = "pgsql:host={$config['host']};port=5433;dbname={$config['database']};user={$config['user']};password={$config['password']}";
try {
    $db =new PDO($postgres);
    
} catch (PDOException $error) {
    echo $error;
    exit('Database error');

}