<?php

include "core/Entity/config.php";

session_name("cinelist");
session_start();

if (isset($_POST["identifiant"])) {
    // requête SQL
    $sql = "SELECT * FROM user WHERE identifiant=:id";
    $id = $_POST["identifiant"];

    // Préparer la requête
    $query = $lienDB->prepare($sql);

    // Liaison des paramètres de la requête préparée
    $query->bindParam(":id", $id, PDO::PARAM_STR);

    // Exécution de la requête
    if ($query->execute()) {
        // traitement des résultats
        $results = $query->fetch();

        $_SESSION["identifiant"] = $results["id"];

        // débogage des résultats
        if ($results) {
            // Connexion réussie
            // var_dump($id);
            // var_dump($_SESSION["identifiant"]);
            header('Location: index.php');
        } else {
            header('Location: login.php');
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="core/css/main.css">
    <title>Login - Cinelist</title>
</head>

<body class="login">
    <header>
        <h1>Login</h1>
    </header>
    <main>
        <form method="post">
            <input type="text" name="identifiant" placeholder="Identifiant">
            <button type="submit">Se connecter</button>
        </form>
    </main>
    <footer>
        <p>Veuillez vous connecter avec votre identifiant pour acceder a votre playlist perso</p>
    </footer>
</body>

</html>