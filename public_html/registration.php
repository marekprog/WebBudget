<?php
session_start();
if (isset($_SESSION['logged_in'])){
    header('Location:mainMenu.php');
    exit();
}
require_once 'database.php';

if (isset($_POST['email']))

{
    //success
    $success = true;
    //test nickname
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $r_password = $_POST['r_password'];
    if ((strlen($username)<3)||(strlen($username)>20)){
        $success = false;
        $_SESSION['e_nick'] = "imie lub nazwa musi posiadac od 3 do 20 znakow";
    }
    if (ctype_alnum($username)==false)
    {
        $success=false;
	$_SESSION['e_nick']="Nick może składać się tylko z liter i cyfr (bez polskich znaków)";
    }
    //check email
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
    if((filter_var($emailB,FILTER_VALIDATE_EMAIL)==false)||($emailB!=$email))
    {
        $success=false;
        echo $email;
        $_SESSION['e_email']="Adres e-mail jest niepoprawny: {$email}";
    }
    //check pass
    if ((strlen($password) < 4) || (strlen($password) > 20)) {
        $success = false;
        $_SESSION['e_pass'] = "Hasło musi posiadać od 4 do 20 znaków!";
    }

    if ($password != $r_password) {
        $success = false;
        $_SESSION['e_pass'] = "Podane hasła nie są identyczne!";
    }

    $pass_hash = password_hash($password, PASSWORD_DEFAULT);
    
    //check if user exists in db
    $userQ=$db->prepare('SELECT id FROM public.users WHERE username=:username');
    $userQ->bindValue(':username',$username,PDO::PARAM_STR);
    $userQ->execute();
    if ($userQ->rowCount()>0){
        $success= false;
        $_SESSION['e_nick']="Podany uzytkownik juz istnieje";
    }
    //check if email exists
    $userQ=$db->prepare('SELECT id FROM public.users WHERE email=:email');
    $userQ->bindValue(':email',$email,PDO::PARAM_STR);
    $userQ->execute();
    if ($userQ->rowCount()>0){
        $success= false;
        $_SESSION['e_email']="Podany email jest juz zajety";
    }

    
    if ($success == true) {
        $query=$db->prepare("INSERT INTO public.users VALUES ( :username, :email,:password)");
        $query->bindValue(':username',$username,PDO::PARAM_STR);
        $query->bindValue(':email',$email,PDO::PARAM_STR);
        $query->bindValue(':password',$pass_hash,PDO::PARAM_STR);
        $query->execute();
        
        //get user id
        $qgetUserId=$db->prepare("SELECT id from public.users WHERE username=:username and  email=:email");
        $qgetUserId->bindValue(':username',$username,PDO::PARAM_STR);
        $qgetUserId->bindValue(':email',$email,PDO::PARAM_STR);
        $qgetUserId->execute();
        $UserId=$qgetUserId->fetch();
        
        
       //copy incomes
        $qgetCopyIncomes=$db->prepare("SELECT * from public.incomes_category_default");
        $qgetCopyIncomes->execute();
        $copyIncomes=$qgetCopyIncomes->fetchAll();
        foreach ($copyIncomes as $income){
             $qinsertIncome=$db->prepare("INSERT INTO public.incomes_category_assigned_to_users VALUES ( :user_id, :name,:id)");
             $qinsertIncome->bindValue(':user_id',$UserId[0],PDO::PARAM_INT);
             $qinsertIncome->bindValue(':name',$income['name'],PDO::PARAM_STR);
             $qinsertIncome->bindValue(':id',$income['id'],PDO::PARAM_INT);
             $qinsertIncome->execute();              
        }
        //copy expenses
        $qgetCopyExp=$db->prepare("SELECT * from public.expenses_category_default");
        $qgetCopyExp->execute();
        $copyExp=$qgetCopyExp->fetchAll();
        foreach ($copyExp as $exp){
             $qinsertExp=$db->prepare("INSERT INTO public.expenses_category_assigned_to_users VALUES ( :user_id, :name,:id)");
             $qinsertExp->bindValue(':user_id',$UserId[0],PDO::PARAM_INT);
             $qinsertExp->bindValue(':name',$exp['name'],PDO::PARAM_STR);
             $qinsertExp->bindValue(':id',$exp['id'],PDO::PARAM_INT);
             $qinsertExp->execute();              
        }
        //copy payment methods
        $qgetCopyPay=$db->prepare("SELECT * from public.payment_methods_default");
        $qgetCopyPay->execute();
        $copyPay=$qgetCopyPay->fetchAll();
        foreach ($copyPay as $pay){
             $qinsertPay=$db->prepare("INSERT INTO public.payment_methods_assigned_to_users VALUES ( :user_id, :name,:id)");
             $qinsertPay->bindValue(':user_id',$UserId[0],PDO::PARAM_INT);
             $qinsertPay->bindValue(':name',$pay['name'],PDO::PARAM_STR);
             $qinsertPay->bindValue(':id',$pay['id'],PDO::PARAM_INT);
             $qinsertPay->execute();              
        }
        header('Location:login.php');
        //exit();
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
                    <?php
			if (isset($_SESSION['e_nick']))
			{
				echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
				unset($_SESSION['e_nick']);
			}
                    ?>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="icon-user"></i></div>
                        </div>
                        <input type="text" class="form-control" id="uzytkownik" name="username" placeholder="nick lub imię">
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
                            <div class="input-group-text"><i class="icon-mail"></i></div>
                        </div>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Adres e-mail">
                    </div>
                    <?php
			if (isset($_SESSION['e_pass']))
			{
				echo '<div class="error">'.$_SESSION['e_pass'].'</div>';
				unset($_SESSION['e_pass']);
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
                        <input type="password" class="form-control" name="r_password" placeholder="powtórz hasło">
                    </div>
                    

                </div>
                <button class="btn btn-primary btn-block" type="submit" name="signup">Zarejestruj</button>

            </form>


        </div>

    </body>
</html>
