<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['logged_in'])){
    header('Location:index.php');
    exit();
}
if (isset($_POST['amount'])){
    //success
    $success = true;
    //check amount
    $amount = $_POST['amount'];
    if(empty($amount)){
        $success = false;
        $_SESSION['e_amount'] = "kwota nie moze byc pusta";
    }
    //check date
    $date=$_POST['date'];
    //check category
    $cat_income=$_POST['cat_income'];
    $comment=$_POST['comment'];
    
    if ($success == true) {
        $query=$db->prepare("INSERT INTO public.incomes VALUES ( :user_id, :income_category_assigned_to_user_id,:amount,:date_of_income,:income_comment)");
        $query->bindValue(':user_id',$_SESSION['logged_in']['id'],PDO::PARAM_INT);
        $query->bindValue(':income_category_assigned_to_user_id',$cat_income,PDO::PARAM_INT);
        $query->bindValue(':amount',$amount,PDO::PARAM_STR);
        $query->bindValue(':date_of_income',$date,PDO::PARAM_STR);
        $query->bindValue(':income_comment',$comment,PDO::PARAM_STR);
        $query->execute();
        header('Location:income.php');
        
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
        <title>Przychód</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="https://fonts.googleapis.com/css?family=Roboto+Mono&display=swap" rel="stylesheet"/> 
        <link rel="stylesheet" type="text/css" href="cssbs/bootstrap.min.css"/>      
        <link rel="stylesheet" type="text/css" href="style.css"/>
        <link rel="stylesheet" type="text/css" href="css/fontello.css"/>
        <script src="time.js" ></script>

    </head>
    <body onload="setToday();">
        <header>

            <nav class="navbar fixed-top navbar-expand-lg navbar-dark scrolling-navbar">
                <a class="navbar-brand" href="mainMenu.php">Web Budget</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainMenu"
                        aria-controls="mainMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainMenu">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="income.php"><i class="icon-down-big"></i>Dodaj przychód</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="expense.php"><i class="icon-up-big"></i> Dodaj wydatek</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="balance.php"><i class="icon-doc-text"></i> Przeglądaj bilans</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php"><i class="icon-logout"></i>Wyloguj się</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-cog"></i> Ustawienia</a>
                        </li>
                    </ul>
                </div>
                <div class="loginflag"><?php echo $_SESSION['logged_in']['username'] ?><i class="icon-user"></i></div>
            </nav>

        </header>
        <form method="post">
            <div class="col-6 mx-auto">
                
                <div class="form-group">
                    <label for="kwota">Podaj kwote</label>
                    <?php
			if (isset($_SESSION['e_amount']))
			{
				echo '<div class="error">'.$_SESSION['e_amount'].'</div>';
				unset($_SESSION['e_amount']);
			}
                    ?>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="icon-dollar"></i></div>
                        </div>
                        <input type="number" step="0.1" class="form-control" id="kwota" name="amount" placeholder="podaj kwotę">
                    </div>
                </div>
                <div class="form-group">
                    <label for="today">Data przychodu</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="icon-calendar"></i></div>
                        </div>
                        <input type="date" class="form-control" id="today" name="date">
                    </div>
                </div>
                <div class="form-group">
                    <label for="wynagrodzenie">Kategoria przychodu</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="cat_income" id="wynagrodzenie" value="1" checked>
                        <label class="form-check-label" for="wynagrodzenie">
                            Wynagrodzenie
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="cat_income" id="odsetki" value="2">
                        <label class="form-check-label" for="odsetki">
                            Odsetki bankowe
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="cat_income" id="allegro" value="3">
                        <label class="form-check-label" for="allegro">
                            Sprzedaż na Allegro
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="cat_income" id="inne" value="4">
                        <label class="form-check-label" for="inne">
                            Inne
                        </label>
                    </div>                        
                </div>
                <div class="form-group">
                    <label for="comment">Komentarz</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="icon-comment-empty"></i></div>
                        </div>
                        <input type="text" class="form-control" id="comment"name="comment">
                    </div>
                </div>

                <button class="btn btn-primary btn-block" type="submit">Zatwierdź</button>
            </div>

        </form>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="jsbs/bootstrap.min.js"></script>  
    </body>
</html>
