<?php

include "core/Entity/config.php";

include "core/Entity/Anime.php";
include "core/Entity/Movie.php";
include "core/Entity/Serie.php";

use Core\Entity\Anime;
use Core\Entity\Movie;
use Core\Entity\Serie;

session_name("cinelist");
session_start();

if (!isset($_SESSION["identifiant"])) {
    header('Location: login.php');
}

function getMovie($movieName)
{
    $url = "https://www.omdbapi.com/?t=" . urlencode($movieName) . "&apikey=e88ae1af";
    $data = json_decode(file_get_contents($url));
    $result["poster"] = $data->Poster;
    $result["plot"] = "$data->Plot";
    $result["year"] = "$data->Year";
    return $result;
}

if(isset($_POST["title"])){
    if($_POST["genre"] == "movie"){
        Movie::AddMovie($_POST["title"],$_SESSION["identifiant"]);
        header('Location: index.php');
    }
    if($_POST["genre"] == "serie"){
        Serie::AddSerie($_POST["title"],$_SESSION["identifiant"]);
        header('Location: index.php');
    }
    if($_POST["genre"] == "anime"){
        Anime::AddAnime($_POST["title"],$_SESSION["identifiant"]);
        header('Location: index.php');
    }
}


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="core/css/main.css">
    <title>CinéList</title>
</head>

<body class="index">
    <header>
        <h1>Ciné List</h1>
        <div class="link">
            <form method="post">
                <input type="text" name="title" required>
                <select name="genre" required>
                    <option value="">-- Choose --</option>
                    <option value="movie">Movie</option>
                    <option value="serie">Serie</option>
                    <option value="anime">Animé</option>
                </select>
                <button type="submit">Ajouter</button>
            </form>
            <form method="post">
                <input type="hidden" name="reload" value="True">
                <input type="submit" value="Reload Cover">
            </form>
            <a href="core/logout.php">Deconnexion</a>
        </div>
    </header>
    <main>
        <div class="series">
            <h2>Series</h2>
            <?php
            $resultserie = Serie::SerieById($_SESSION["identifiant"]);
            foreach ($resultserie as $serie) {
            ?>
                <div class="card">
                    <h2><?= $serie["title"] ?></h2>
                    <div class="sub-card">
                        <img src="<?= getMovie($serie["title"])["poster"] ?>">
                        <p><?= getMovie($serie["title"])["plot"] ?></p>
                        <h4><?= getMovie($serie["title"])["year"] ?></h4>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="movies">
            <h2>Movies</h2>
            <?php
            $resultfilm = Movie::MovieById($_SESSION["identifiant"]);
            foreach ($resultfilm as $movie) {
            ?>
                <div class="card">
                    <h2><?= $movie["title"] ?></h2>
                    <div class="sub-card">
                        <img src="<?= getMovie($movie["title"])["poster"] ?>">
                        <p><?= getMovie($movie["title"])["plot"] ?></p>
                        <h4><?= getMovie($movie["title"])["year"] ?></h4>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="animes">
            <h2>Animes</h2>
            <?php
            $resultanime = Anime::AnimeById($_SESSION["identifiant"]);
            foreach ($resultanime as $anime) {
            ?>
                <div class="card">
                    <h2><?= $anime["title"] ?></h2>
                    <div class="sub-card">
                        <img src="<?= getMovie($anime["title"])["poster"] ?>">
                        <p><?= getMovie($anime["title"])["plot"] ?></p>
                        <h4><?= getMovie($anime["title"])["year"] ?></h4>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </main>
    <footer>

    </footer>
</body>

</html>