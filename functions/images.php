<?php
// Se crea la función para guardar una imagen
function saveImg($image){
    $imgDir = null;

    // Se comprueba si se recibió una imagen y que su tamaño sea mayor que 0
    if (isset($image) && $image['size'] > 0) {
        $targetDir = "../public/resources/img/";
        $imageFileType = $image['type'];
        // Se verifica si el tipo de archivo es una imagen válida
        if($imageFileType != "image/jpg" && $imageFileType != "image/png" && $imageFileType != "image/jpeg") {
            return null;
        }
        // Ruta completa donde se guardará la imagen
        $targetFile = $targetDir . $image["name"];
        // Se mueve la imagen desde la ubicación temporal a la definitiva
        if (move_uploaded_file($image["tmp_name"], $targetFile)) {
            // Si se movió correctamente, se actualiza la ruta que se va a devolver
            $imgDir = "../resources/img/" .$image["name"];
        }
    } else {
        // Si no se recibe una imagen válida, se asigna una imagen por defecto
        $imgDir = "../resources/img/default.png";
    }

    return $imgDir;
}