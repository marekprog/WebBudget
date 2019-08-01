<?php
session_start();
if (!isset($_SESSION['logged_in'])){
    header('Location:index.php');
    exit();
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
            <div id="incomes" class="col-sm-5 col-md-5 col-lg-5" style="display: none;">
                <table>
                    <thead>
                        <tr>
                            <th>Kategoria</th>
                            <th>Suma przychodów</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Wynagrodzenie</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Odsetki Bankowe</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Sprzedaż na allegro</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Inne</td>
                            <td></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Suma</th>
                            <th></th>
                        </tr>
                    </tfoot>
                    
                </table>                
            </div>
            <div id="expenses" class="col-sm-5 col-md-5 col-lg-5" style="display: none;">
                <table>
                    <thead>
                        <tr>
                            <th>Kategoria</th>
                            <th>Suma wydatków</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Jedzenie</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Mieszkanie</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Transport</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Telekomunikacja</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Opieka zdrowotna</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Ubranie</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Higiena</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Dzieci</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Rozrywka</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Wycieczka</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Szkolenia</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Książki</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Oszczędności</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Na złotą jesień, czyli emeryturę</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Spłata długów</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Darowizna</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Inne wydatki</td>
                            <td></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Suma</th>
                            <th></th>
                        </tr>
                    </tfoot>
                    
                </table>
            </div>
        </section>
        <aside class="col-sm-2 col-md-2 col-lg-2 float-md-right">
            <div class="form-group">
                <label for="balanceRange">Sposob płatności</label>
                <div class="input-group input-group-sm">                       
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="icon-wallet"></i></div>
                    </div>
                    <select class="form-control form-control-sm" id="balanceRange">
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
                            <label >Początek<input type="date" name="today" ></label>
                            <label >Koniec<input type="date" name="today" id="today"></label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cofnij</button>
                            <button type="button" class="btn btn-primary">Zapisz zmiany</button>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary btn-block" id="showBalance" onclick="showDiv()">Zatwierdź</button>      
        </aside>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="jsbs/bootstrap.min.js"></script>            
    </body>
</html>
