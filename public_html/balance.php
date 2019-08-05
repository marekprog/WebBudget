<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['logged_in'])){
    header('Location:index.php');
    exit();
}
if (isset($_POST['balance'])){
    $balancerange = $_POST['balance'];
    if ($balancerange=="bm"){
        //obecny miesiac
        $date_from=date("Y-m-01");
        $date_to=date("Y-m-d");
    }
    else if ($balancerange=="pm"){
        //poprzedni miesiac
        $date = date_create();
        //$date_to= mktime(0, 0, 0, date("m")-1, date("d"),   date("Y"));
        $date_from=date('Y-m-d', strtotime("first day of previous month"));
        $date_to=date('Y-m-d', strtotime("last day of previous month"));
        
    }
    else if ($balancerange=="br"){
        //biezacy rok
        $date_from=date("Y-01-01");
        $date_to=date("Y-m-d");
    }
    else if ($balancerange=="ns"){
        //niestandardowe
        $date_from=$_POST['date_from'];
        $date_to=$_POST['date_to'];
    }

}
if (!isset($_POST['balance'])){
    $date_from=date("Y-m-01");
    $date_to=date("Y-m-d");
}
    //select data
    //incomes
    $queryIncome=$db->prepare("SELECT income_category_assigned_to_user_id,SUM(amount) from public.incomes where user_id=:user_id and date_of_income BETWEEN :date_from and :date_to GROUP BY income_category_assigned_to_user_id");
    $queryIncome->bindValue(':user_id',$_SESSION['logged_in']['id'],PDO::PARAM_INT);
    $queryIncome->bindValue(':date_from',$date_from,PDO::PARAM_STR);
    $queryIncome->bindValue(':date_to',$date_to,PDO::PARAM_STR);
    $queryIncome->execute();
    $queryIncomeSum=$db->prepare("SELECT SUM(amount) from public.incomes where user_id=:user_id and date_of_income BETWEEN :date_from and :date_to");
    $queryIncomeSum->bindValue(':user_id',$_SESSION['logged_in']['id'],PDO::PARAM_INT);
    $queryIncomeSum->bindValue(':date_from',$date_from,PDO::PARAM_STR);
    $queryIncomeSum->bindValue(':date_to',$date_to,PDO::PARAM_STR);
    $queryIncomeSum->execute();
    $incomesSumAll=$queryIncomeSum->fetchAll();
    $incomesTable=$queryIncome->fetchAll();
    $incomeSums=array_fill(0,4,0);
    for ($i = 0; $i <= count($incomeSums); $i++) {
        foreach ($incomesTable as $income){
            if ($i==($income['income_category_assigned_to_user_id']-1))
                $incomeSums[$i]=$income['sum'];
        }

    }
    //incomes
    $queryExpSum=$db->prepare("SELECT SUM(amount) from public.expenses where user_id=:user_id and date_of_expense BETWEEN :date_from and :date_to");
    $queryExpSum->bindValue(':user_id',$_SESSION['logged_in']['id'],PDO::PARAM_INT);
    $queryExpSum->bindValue(':date_from',$date_from,PDO::PARAM_STR);
    $queryExpSum->bindValue(':date_to',$date_to,PDO::PARAM_STR);
    $queryExpSum->execute();
    $expSumAll=$queryExpSum->fetchAll();
    $queryExp=$db->prepare("SELECT expense_category_assigned_to_user_id,SUM(amount) from public.expenses where user_id=:user_id and date_of_expense BETWEEN :date_from and :date_to GROUP BY expense_category_assigned_to_user_id");
    $queryExp->bindValue(':user_id',$_SESSION['logged_in']['id'],PDO::PARAM_INT);
    $queryExp->bindValue(':date_from',$date_from,PDO::PARAM_STR);
    $queryExp->bindValue(':date_to',$date_to,PDO::PARAM_STR);
    $queryExp->execute();
    $expTable=$queryExp->fetchAll();
    $expSums=array_fill(0,17,0);
    for ($i = 0; $i <= count($expSums); $i++) {
        foreach ($expTable as $expense){
            if ($i==($expense['expense_category_assigned_to_user_id']-1))
                $expSums[$i]=$expense['sum'];
        }

    }
    //print_r($expSumAll);
    //exit();  
    

?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html lang="pl">
    <head>
        <title>Bilans</title>
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
                <a class="navbar-brand" href="index.php">Web Budget</a>
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

        <section>
            <div id="incomes" class="col-sm-5 col-md-5 col-lg-5">
                <table>
                    <thead>
                        <tr>
                            <th>Kategoria</th>
                            <th>Suma przychodów</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($incomeSums[0]!=0){
                            echo "<tr><td>Wynagrodzenie</td><td>".$incomeSums[0]."</td></tr>";
                        } ?>  
                        <?php if($incomeSums[1]!=0){
                            echo "<tr><td>Odsetki Bankowe</td><td>".$incomeSums[1]."</td></tr>";
                        } ?> 
                        <?php if($incomeSums[2]!=0){
                            echo "<tr><td>Sprzedaż na allegro</td><td>".$incomeSums[2]."</td></tr>";
                        } ?>
                        <?php if($incomeSums[3]!=0){
                            echo "<tr><td>Inne</td><td>".$incomeSums[3]."</td></tr>";
                        } ?>                         
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Suma</th>
                            <th><?=$incomesSumAll[0]['sum']?></th>
                        </tr>
                    </tfoot>
                    
                </table>                
            </div>
            <div id="expenses" class="col-sm-5 col-md-5 col-lg-5" >
                <table>
                    <thead>
                        <tr>
                            <th>Kategoria</th>
                            <th>Suma wydatków</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($expSums[0]!=0){
                            echo "<tr><td>Jedzenie</td><td>".$expSums[0]."</td></tr>";
                        } ?>
                        <?php if($expSums[1]!=0){
                            echo "<tr><td>Mieszkanie</td><td>".$expSums[1]."</td></tr>";
                        } ?>
                        <?php if($expSums[2]!=0){
                            echo "<tr><td>Transport</td><td>".$expSums[2]."</td></tr>";
                        } ?>
                        <?php if($expSums[3]!=0){
                            echo "<tr><td>Telekomunikacja</td><td>".$expSums[3]."</td></tr>";
                        } ?>
                        <?php if($expSums[4]!=0){
                            echo "<tr><td>Opieka zdrowotna</td><td>".$expSums[4]."</td></tr>";
                        } ?>
                        <?php if($expSums[5]!=0){
                            echo "<tr><td>Ubranie</td><td>".$expSums[5]."</td></tr>";
                        } ?>
                        <?php if($expSums[6]!=0){
                            echo "<tr><td>Higiena</td><td>".$expSums[6]."</td></tr>";
                        } ?>
                        <?php if($expSums[7]!=0){
                            echo "<tr><td>Dzieci</td><td>".$expSums[7]."</td></tr>";
                        } ?>
                        <?php if($expSums[8]!=0){
                            echo "<tr><td>Rozrywka</td><td>".$expSums[8]."</td></tr>";
                        } ?>
                        <?php if($expSums[9]!=0){
                            echo "<tr><td>Wycieczka</td><td>".$expSums[9]."</td></tr>";
                        } ?>
                        <?php if($expSums[10]!=0){
                            echo "<tr><td>Szkolenia</td><td>".$expSums[10]."</td></tr>";
                        } ?>
                        <?php if($expSums[11]!=0){
                            echo "<tr><td>Książki</td><td>".$expSums[11]."</td></tr>";
                        } ?>
                        <?php if($expSums[12]!=0){
                            echo "<tr><td>Oszczędności</td><td>".$expSums[12]."</td></tr>";
                        } ?>
                        <?php if($expSums[13]!=0){
                            echo "<tr><td>Na złotą jesień, czyli emeryturę</td><td>".$expSums[13]."</td></tr>";
                        } ?>
                        <?php if($expSums[14]!=0){
                            echo "<tr><td>Spłata długów</td><td>".$expSums[14]."</td></tr>";
                        } ?>
                        <?php if($expSums[15]!=0){
                            echo "<tr><td>Darowizna</td><td>".$expSums[15]."</td></tr>";
                        } ?>
                        <?php if($expSums[16]!=0){
                            echo "<tr><td>Inne wydatki</td><td>".$expSums[16]."</td></tr>";
                        } ?>             
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Suma</th>
                            <th><?=$expSumAll[0]['sum']?></th>
                        </tr>
                    </tfoot>
                    
                </table>
            </div>
        </section>
        <form method="post">
        <aside class="col-sm-2 col-md-2 col-lg-2 float-md-right">
            <div class="form-group">
                <label for="balanceRange">Wybierz zakres dat</label>
                <div class="input-group input-group-sm">                       
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="icon-wallet"></i></div>
                    </div>
                    <select class="form-control form-control-sm" id="balanceRange" name="balance">
                        <option value="bm" selected="selected">Bieżący miesiąc</option>
                        <option value="pm">Poprzedni miesiąc</option>
                        <option value="br">Bieżący rok</option>
                        <option value="ns" class="btn btn-primary" data-toggle="modal" data-target="#niestandardoweModal">niestandardowe</option>
                    </select>
                </div>
            </div>

            <div class="modal fade" id="niestandardoweModal" tabindex="-1" role="dialog" aria-labelledby="zakresDat" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="zakresDat">Wybierz zakres dat</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <label >Początek<input type="date" name="date_from" ></label>
                            <label >Koniec<input type="date" name="date_to" id="today"></label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
                           
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary btn-block" id="showBalance" onclick="showDiv()" type="submit" >Zatwierdź</button>      
        </aside>
        </form>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="jsbs/bootstrap.min.js"></script>            
    </body>
</html>
