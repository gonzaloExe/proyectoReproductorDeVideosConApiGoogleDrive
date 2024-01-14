<?php
ini_set('memory_limit', '512M');

use Google\Client;
use Google\Service\Drive;

require_once 'api-google/vendor/autoload.php';

$client = new Google_Client();
$client->setApplicationName('BibliotecaDefinitiva');
$client->setScopes(Google_Service_Drive::DRIVE_METADATA_READONLY);
$client->setAuthConfig('proyectotv-410418-67f96b03c92d.json'); // Reemplaza con la ruta a tu archivo de credenciales JSON
$service = new Google_Service_Drive($client);

// Función para obtener archivos de Google Drive
function getArchivosToken($service, $pageSize, $archivosASaltar)
{
    $results = $service->files->listFiles(array(
        'pageSize' => $pageSize,
        'fields' => 'nextPageToken',
    ));
    $token = $results->getNextPageToken();

    $paginasASaltar = ceil($archivosASaltar / $pageSize);
    for ($i = 1; $i < $paginasASaltar; $i++) {
        $results = $service->files->listFiles(array(
            'pageSize' => $pageSize,
            'pageToken' => $token,
            'fields' => 'nextPageToken',
        ));
        $token = $results->getNextPageToken();
    }
    return $token;
}

// Consulta y muestra videos de una temporada específica
function mostrarVideosTemporadaEspecifica($service, $folderId, $archivosPorPagina, $archivosASaltar)
{
    $query = "mimeType='video/mp4' and trashed=false and parents in '" . $folderId . "'";
    $results = $service->files->listFiles(array(
        'q' => $query,
        'fields' => 'files(name,id)',
        'pageSize' => $archivosPorPagina,
        'pageToken' => $archivosASaltar > 0 ? getArchivosToken($service, $archivosPorPagina, $archivosASaltar) : null,
    ));
    return $results->getFiles();
}

// Recibir el parámetro 'temporada' mediante POST
$temporada = isset($_POST['temporada']) ? $_POST['temporada'] : '';

// Definir el ID de la carpeta de la temporada seleccionada
$folderId = '';
switch ($temporada) {
    case 'temporada72':
        $folderId = '1vXg4a6YoM99vfergu6DJngqNaSwdnUxw';
        break;
    case 'temporada73':
        $folderId = '1iE1nf5u91aG4cA2A5xySL-FsXBaSrYKk';
        break;
    case 'temporada74':
        $folderId = '1ked9xaFubnxpKUrW-MsGUAMWljXbf9r6';
        break;
    case 'temporada75':
        $folderId = '1OOJ_PsLXJpU717paTxQbB_PbFp-_rJHG';
        break;
    case 'temporada76':
        $folderId = '1HMkrwecP3sF_JV5IGjCP1NLpRW9kLJZz';
        break;
    // Agrega más casos según sea necesario
}

// Mostrar los videos de la temporada seleccionada
$videosTemporada = mostrarVideosTemporadaEspecifica($service, $folderId, 500, 0);

// Construir y devolver el HTML de las tarjetas de video
$htmlVideos = '';
if (!empty($videosTemporada)) {
    foreach ($videosTemporada as $videoTemporada) {
        $htmlVideos .= '<div class="card mb-3 col-sm-12 col-md-6 col-lg-4 col-xl-3">';
        $htmlVideos .= '<video src="https://drive.google.com/uc?id=' . $videoTemporada->getId() . '" controls class="card-img-top"></video>';
        $htmlVideos .= '<div class="card-body">';
        $htmlVideos .= '<h5 class="card-title">' . $videoTemporada->getName() . '</h5>';
        $htmlVideos .= '</div>';
        $htmlVideos .= '</div>';
    }
}

echo $htmlVideos;
?>
