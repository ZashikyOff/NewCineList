<?php

namespace Core\Entity;

use DateTime;
use \PDO;
use \PDOException;
use \Exception;
use \Error;

class Anime
{
    /* attributs (propriete properties) */
    private string $title;
    private int $years;
    private string $description;
    private string $img_path;
    private int $id_user;

    /** Constructeur */
    public function __construct($title = "",$years = 0,$description = "", $poster_path = "",$id_user = 0)
    {
        $this->title = $title;
        $this->years = $years;
        $this->description = $description;
        $this->poster_path = $poster_path;
        $this->id_user = $id_user;
    }

    /** Accesseurs */
    // setters magiques
    public function __set($attribut, $valeur)
    {
        $this->$attribut = $valeur;
    }

    // getters magiques
    public function __get($attribut)
    {
        return $this->$attribut;
    }

    public static function AnimeById($id)
    {
        require "config.php";
        try {
            $sql = "SELECT * FROM anime WHERE id_user = :id";
            $query = $lienDB->prepare($sql);
            $query->bindValue(":id", $id, PDO::PARAM_INT);
            $query->execute();
            $results = $query->fetchAll();
        } catch (Exception $e) {
            print_r($e);
        }
        return $results;
    }

    public static function AddAnime($title, $id)
    {
        require "config.php";
        try {
            $sql = "INSERT INTO `anime`(`title`, `years`, `description`, `poster_path`, `id_user`) VALUES (:title,:date,'description','img',:id    )";
            $query = $lienDB->prepare($sql);

            //On injecte les valeurs
            date_default_timezone_set('Europe/Paris');
            $date = date('d-m-y h:i:s');
            $query->bindParam(":date", $date);
            $query->bindValue(":title", $title, PDO::PARAM_STR);
            $query->bindValue(":id", $id, PDO::PARAM_INT);
            $query->execute();
            $results = $query->fetchAll();
        } catch (Exception $e) {
            print_r($e);
        }
        return $results;
    }
}
