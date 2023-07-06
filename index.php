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


function download($url, $title)
{
    // Initialize a file URL to the variable
    // $url = 'https://static9.depositphotos.com/1000291/1113/i/600/depositphotos_11133127-stock-photo-goat-with-horn-at-green.jpg';

    // Use basename() function to return the base name of file
    $file_name = "./core/covers/" . $title . ".jpg";
    $file_folder = "./core/covers/*";

    $files = glob($file_folder);

    $nomTrouve = false;

    foreach ($files as $file) {
        $file = substr($file, 14);
        var_dump($file);
        // Vérifier si le nom du fichier correspond
        if (basename($file) == $title) {
            $nomTrouve = true;
            break;
        }
    }

    if ($nomTrouve) {
    } else {
        // Use file_get_contents() function to get the file
        // from url and use file_put_contents() function to
        // save the file by using base name
        if (file_put_contents($file_name, file_get_contents($url))) {
            echo "File downloaded successfully";
        } else {
            echo "File downloading failed.";
        }
    }
}

if (isset($_POST["title"])) {
    if ($_POST["genre"] == "movie") {
        Movie::AddMovie($_POST["title"], $_SESSION["identifiant"]);
        header('Location: index.php');
    }
    if ($_POST["genre"] == "serie") {
        Serie::AddSerie($_POST["title"], $_SESSION["identifiant"]);
        header('Location: index.php');
    }
    if ($_POST["genre"] == "anime") {
        Anime::AddAnime($_POST["title"], $_SESSION["identifiant"]);
        header('Location: index.php');
    }
}

if (isset($_GET["title"]) && isset($_GET["media"])) {
    if ($_GET["media"] == "serie") {
        Serie::DeleteSerie($_GET["title"]);
        header('Location: index.php');
    }
    if ($_GET["media"] == "movie") {
        Movie::DeleteMovie($_GET["title"]);
        header('Location: index.php');
    }
    if ($_GET["media"] == "anime") {
        Anime::DeleteAnime($_GET["title"]);
        header('Location: index.php');
    }
}


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="core/css/main.css">
    <title>CinéList</title>
</head>

<body class="index">
    <script src="core/js/app.js" defer></script>
    <header>
        <h1>Ciné List</h1>
        <div class="link">
            <form method="post" class="addmedia">
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
                <input type="hidden" name="reload" value="true">
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
                    <h2><?= $serie["title"] ?><a href="?title=<?= $serie["title"] ?>&media=serie"><i class="fa-solid fa-trash"></i></a></h2>
                    <div class="sub-card unactive">
                        <img src="<?= getMovie($serie["title"])["poster"] ?>">
                        <p><?= getMovie($serie["title"])["plot"] ?></p>
                        <h4><?= getMovie($serie["title"])["year"] ?></h4>
                    </div>
                </div>
            <?php
                download(getMovie($serie["title"])["poster"], $serie["title"]);
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
                    <h2><?= $movie["title"] ?><a href="?title=<?= $movie["title"] ?>&media=movie"><i class="fa-solid fa-trash"></i></a></h2>
                    <div class="sub-card unactive">
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
                    <h2><?= $anime["title"] ?><a href="?title=<?= $anime["title"] ?>&media=anime"><i class="fa-solid fa-trash"></i></a></h2>
                    <div class="sub-card unactive">
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