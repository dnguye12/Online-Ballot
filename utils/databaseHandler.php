<?php
    function loadDataFromFile($path) {
        if(!file_exists($path) || filesize($path) == 0) {
            return [];
        }

        $content = file_get_contents($path);
        return json_decode($content, true);
    }

    function saveDataToFile($path, $data) {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($path, $jsonData);
    }
?>