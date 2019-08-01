<?php
session_start();
require_once 'database.php';

if (isset($_POST['email']))

{
    //success
    $success = true;
    //test nickname
    $uzytkownik = $_POST['nick'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    if ((strlen($uzytkownik)<3)||(strlen($uzytkownik)>20)){
        $success = false;
        $_SESSION['e_nick'] = "imie lub nazwa musi posiadac od 3 do 20 znakow";
    }
    if (ctype_alnum($nick)==false)
    {
        $success=false;
	$_SESSION['e_nick']="Nick może składać się tylko z liter i cyfr (bez polskich znaków)";
    }
    //check email
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
    if((filter_var($emailB,FILTER_VALIDATE_EMAIL)==false)||($emailB!=email))
    {
        $success=false;
        $_SESSION['e_email']="Adres e-mail jest niepoprawny";
    }
    if ($success == true) {
        $query=$db->prepare("INSERT INTO public.users VALUES ( :user, :email,:password)");
        $query->bindValue(':user',$uzytkownik,PDO::PARAM_STR);
        $query->bindValue(':email',$email,PDO::PARAM_STR);
        $query->bindValue(':password',$password,PDO::PARAM_STR);
        $query->execute();
        exit();
    }
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
        <title>Rejestracja</title>
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

        <div class="col-6 mx-auto align-top">Rejestracja
            <form method="post">
                <div class="form-group">
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="icon-user"></i></div>
                        </div>
                        <input type="text" class="form-control" id="uzytkownik" name="nick" placeholder="nick lub imię">
                    </div>
                    <?php
			if (isset($_SESSION['e_nick']))
			{
				echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
				unset($_SESSION['e_nick']);
			}
                    ?>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="icon-mail"></i></div>
                        </div>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Adres e-mail">
                    </div>
                    <?php
			if (isset($_SESSION['e_email']))
			{
				echo '<div class="error">'.$_SESSION['e_email'].'</div>';
				unset($_SESSION['e_email']);
			}
                    ?>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="icon-key"></i></div>
                        </div>
                        <input type="password" class="form-control" name="password" placeholder="hasło">
                    </div>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="icon-key"></i></div>
                        </div>
                        <input type="password" class="form-control" placeholder="powtórz hasło">
                    </div>

                </div>
                <button class="btn btn-primary btn-block" type="submit" name="signup">Zarejestruj</button>

            </form>


        </div>

    </body>
</html>
