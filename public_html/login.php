<?php
session_start();
if (isset($_SESSION['logged_in'])){
    header('Location:mainMenu.php');
    exit();
}
require_once 'database.php';

    if(isset($_POST['login'])){
        $login=filter_input(INPUT_POST,'login');
        $password= filter_input(INPUT_POST, 'pass');
        $userQ=$db->prepare('SELECT id,password,username FROM public.users WHERE username=:login');
        $userQ->bindValue(':login',$login,PDO::PARAM_STR);
        $userQ->execute();
                
        $userData=$userQ->fetch();
        if ($userData && password_verify($password,$userData['password'])){
            $_SESSION['logged_in']=$userData;
            header('Location:mainMenu.php');
            unset($_SESSION['login_failure']);
            exit();
                        
        } else {
            $_SESSION['login_failure']=true;
            header('Location:login.php');
            exit();
            
        }
        exit();
        echo $userData['id'];
    }
    
    
?>

<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html lang="pl">
    <head>
        <title>Logowanie</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="https://fonts.googleapis.com/css?family=Roboto+Mono&display=swap" rel="stylesheet"/> 
        <link rel="stylesheet" type="text/css" href="cssbs/bootstrap.min.css"/>      
        <link rel="stylesheet" type="text/css" href="style.css"/>
        <link rel="stylesheet" type="text/css" href="css/fontello.css"/>

    </head>
    <body>
        <header>

            <nav class="navbar fixed-top navbar-expand-lg navbar-dark scrolling-navbar">
                <a class="navbar-brand" href="index.php">Web Budget</a>
            </nav>


        </header>
        <div class="col-6 mx-auto">Logowanie
            <form method="post">
                <div class="form-group">
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="icon-user"></i></div>
                        </div>
                        <input type="text" class="form-control" id="uzytkownik" name="login" placeholder="użytkownik lub email">
                    </div>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="icon-key"></i></div>
                        </div>
                        <input type="password" class="form-control" id="haslo" name="pass" placeholder="hasło">
                    </div>
                </div>                                      
                <button class="btn btn-primary btn-block" type="submit" role="button" ><i class="icon-login"></i> Zaloguj</button>
            </form>
        </div>


    </body>
</html>
