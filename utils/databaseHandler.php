<?php
    /**
     * Charge les données depuis un fichier.
     *
     * Cette fonction lit le contenu d'un fichier donné et renvoie les données
     * sous forme de tableau. Si le fichier n'existe pas ou est vide, la fonction
     * renvoie un tableau vide.
     *
     * @param string $path Chemin vers le fichier à lire.
     * @return array Les données lues depuis le fichier, ou un tableau vide si
     * le fichier n'existe pas ou est vide.
     */
    function loadDataFromFile($path) {
        // Vérifie si le fichier existe et qu'il n'est pas vide
        if(!file_exists($path) || filesize($path) == 0) {
            return [];
        }

        $content = file_get_contents($path);
        return json_decode($content, true);
    }

    /**
     * Sauvegarde des données dans un fichier.
     *
     * Cette fonction prend des données sous forme de tableau associatif et les
     * sauvegarde dans un fichier spécifié après les avoir converties en JSON.
     * Les données sont formatées pour être lisibles facilement.
     *
     * @param string $path Chemin vers le fichier où sauvegarder les données.
     * @param array $data Les données à sauvegarder dans le fichier.
     * @return void
     */
    function saveDataToFile($path, $data) {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($path, $jsonData);
    }
?>