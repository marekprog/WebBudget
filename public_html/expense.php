<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html lang="pl">
    <head>
        <title>Wydatek</title>
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
                            <a class="nav-link" href="index.php"><i class="icon-logout"></i>Wyloguj się</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-cog"></i> Ustawienia</a>
                        </li>
                    </ul>
                </div>
            </nav>

        </header>
        <form action="index.php.html" method="post">
            <div class="col-6 mx-auto">
                <div class="form-group">
                    <label for="kwota">Podaj kwotę</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="icon-dollar"></i></div>
                        </div>
                        <input type="number" step="0.1" class="form-control" id="kwota" placeholder="$">
                    </div>
                </div>
                <div class="form-group">
                    <label for="today">Data przychodu</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="icon-calendar"></i></div>
                        </div>
                        <input type="date" class="form-control" name="today" id="today">
                    </div>
                </div>
                <div class="form-group">
                    <label for="sposobplatn">Sposób płatności</label>
                    <div class="input-group mb-2">                       
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="icon-wallet"></i></div>
                        </div>
                        <select class="form-control" id="sposobplatn">
                            <option value="g">Gotówka</option>
                            <option value="d">Karta debetowa</option>
                            <option value="k">Karta kredytowa</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="kategoria">Kategoria</label>
                    <div class="input-group mb-2">                       
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="icon-tag"></i></div>
                        </div>
                        <select class="form-control" id="kategoria">
                            <option class="kat" value="1">Jedzenie</option>
                            <option class="kat" value="2">Mieszkanie</option>
                            <option class="kat" value="3">Transport</option>
                            <option class="kat" value="4">Telekomunikacja</option>
                            <option class="kat" value="5">Opieka zdrowotna</option>
                            <option class="kat" value="6">Ubranie</option>
                            <option class="kat" value="7">Higiena</option>
                            <option class="kat" value="8">Dzieci</option>
                            <option class="kat" value="9">Rozrywka</option>
                            <option class="kat" value="10">Wycieczka</option>
                            <option class="kat" value="11">Szkolenia</option>
                            <option class="kat" value="12">Książki</option>
                            <option class="kat" value="13">Oszczędności</option>
                            <option class="kat" value="14">Na złotą jesień, czyli emeryturę</option>
                            <option class="kat" value="15">Spłata długów</option>
                            <option class="kat" value="16">Darowizna</option>
                            <option class="kat" value="17">Inne wydatki</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="comment">Komentarz</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="icon-comment-empty"></i></div>
                        </div>
                        <input type="text" class="form-control" id="comment">
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
