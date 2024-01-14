<?php
// cargar_videos.php

// Obtener el parámetro de la temporada desde la solicitud GET
$temporada = $_GET['temporada'];

// Lógica para obtener los videos de la temporada especificada
// (Debes adaptar esto según la estructura de tu código actual)

// ... (tu código para obtener videos de la temporada seleccionada)

// Supongamos que $videosTemporada es un arreglo de objetos con información de los videos
// Convertir el arreglo de videos a formato JSON y devolverlo como respuesta
echo json_encode($videosTemporada);
?>
