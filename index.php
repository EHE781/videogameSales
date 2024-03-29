<!DOCTYPE html>
<html lang="es">
<?php
session_start();

require_once 'scripts/data.php';
?>

<head>
    <!--JQuery-->
    <script src="js/jquery-3.6.1.js"></script>
    <!--Popper JS-->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <!--Bootstrap JS-->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
    <!--Highcharts-->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>¡ADIIU DASHBOARD!</title>

    <link rel="stylesheet" href="css/index.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!--BOOTSTRAP NAVBAR-->

    <nav class="navbar navbar-dark bg-dark navbar-expand-lg">
        <a class="navbar-brand" href="#"><img src="media\img\logoUIB.png" alt="..." height="36"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02"
            aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                        href="https://github.com/bbrumm/databasestar/tree/main/sample_databases/sample_db_videogames/mysql">Data
                        Source</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        More...
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="https://www.uib.es/">Our University</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="https://www.linkedin.com/in/emanuel-hegedus-1333301a7/">About
                            us</a>
                    </div>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>
</head>

<body>


    <h1 class="title">Videogames Dashboard</h1>

    <h2 class="subtitle">An insight on publishers and videogames in time</h2>

    <?php
    #getDatos();
    ?>
    <div class="pie-container">
        <div id="piechart head">
            <!--Title and head of piechart-->
            <p class="pie-title">Publishers ranked by most sales by top 20</p>
        </div>
        <div id="pieContainer">
            <!--Pie chart-->
            <script lang="JavaScript">
            $(document).ready(function() {
                pieChart();
            });
            </script>
        </div>
    </div>
    <div class="spacer"></div>
    <div class='raceChart-container'>
        <figure class="highcharts-figure">
            <div id="parent-container">
                <div id="play-controls">
                    <button id="play-pause-button" class="fa fa-play" title="play"></button>
                    <input id="play-range" type="range" value="1980" min="1980" max="2020" />
                </div>
                <div id="raceContainer">
                    <!--Racebar chart-->
                    <script lang="JavaScript">
                    $(document).ready(function() {
                        raceChart();
                    });
                    </script>
                </div>
            </div>
            <p class="highcharts-description">
                Bar chart showing the world videogames sales (The Top 20) from 1980 to 2020.
            </p>
        </figure>
    </div>
    <div class="spacer"></div>
    <div class='donutChart-container'>
        <figure class="highcharts-figure">
            <div id="donutContainer">
                <!--Donut hierarchy chart-->
                <script lang="JavaScript">
                $(document).ready(function() {
                    donutChart();
                });
                </script>
            </div>
            <p class="highcharts-description">
                Videogames popularity by genre
            </p>
        </figure>
    </div>
    <div class="spacer"></div>
    <div class="info">
        <p class="info-text">These three charts give us a better view on how videogames evolved and the user's
            preferences over the years.
            In the first years we see a lot of movement, but as we get closer to actuality, the games remain on a steady
            way.
            Also we can observe that currently Nintendo has held most of the market and the preferred genres are
            usually<br>
            <span class="dot"></span>Action,<br> Adventure,<br>Sports.
        </p>
    </div>

</body>

<script src="scripts/functions.js"></script>

</html>