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

// Función para cargar videos de una temporada específica
function cargarVideos($temporada, $service, $archivosPorPagina, $archivosASaltar)
{
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
    $videosTemporada = mostrarVideosTemporadaEspecifica($service, $folderId, $archivosPorPagina, $archivosASaltar);

    // Imprimir las tarjetas de los videos
    if (!empty($videosTemporada)) {
        foreach ($videosTemporada as $videoTemporada) {
            echo '<div class="card mb-3 col-sm-12 col-md-6 col-lg-4 col-xl-3">';
            echo '<video src="https://drive.google.com/uc?export=download&id=' . $videoTemporada->getId() . '" controls class="card-img-top"></video>';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $videoTemporada->getName() . '</h5>';
            echo '</div>';
            echo '</div>';
        }
    }
}

$archivosPorPagina = 500;
$páginaInicial = isset($_GET['page']) ? $_GET['page'] : 1;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Chabo Videos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <!-- Agregando el icono -->
            <a class="navbar-brand" href="#">
                <img src="img/imgicono.jpg" alt="Icono" width="30" height="30" class="d-inline-block align-text-top">
                The Chabo
            </a>

            <!-- Botón de hamburguesa para dispositivos móviles -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Elementos de la barra de navegación -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <!-- Temporada 72 seleccionada por defecto -->
                    <li class="nav-item active">
                        <a id="temporada72Link" class="nav-link" href="#" onclick="cargarVideos('temporada72')">Tem. 72</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="#" onclick="cargarVideos('temporada73')">Tem. 73</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="#" onclick="cargarVideos('temporada74')">Tem. 74</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="#" onclick="cargarVideos('temporada75')">Tem. 75</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="#" onclick="cargarVideos('temporada76')">Tem. 76</a>
                    </li>
                    <!-- Agrega más temporadas según sea necesario -->
                </ul>

                <!-- Formulario de búsqueda -->
                <form class="d-flex ms-auto">
                    <input id="search-input" class="form-control me-2" type="search" placeholder="Buscar" aria-label="Buscar">
                    <button class="btn btn-outline-success" type="button" onclick="buscarVideos()">Buscar</button>
                    <a href="modotv.php" class="btn btn-danger ms-2">Modo TV</a>
                </form>
            </div>
        </div>
    </nav>

    <!-- Contenedor para las tarjetas de videos -->
    <div class="container" id="videos-container">
        <div class="row g-4" id="video-row">

        </div>
    </div>

    <!-- Scripts de Bootstrap y Popper.js (asegúrate de incluir Popper.js antes de Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> <!-- Asegúrate de incluir jQuery -->

    <script src="app.js"> </script>
    <script>
        $(document).ready(function() {
            // Hacer clic automático en el enlace de la Temporada 72 al cargar la página
            $("#temporada72Link").click();
        });
    </script>
    <!-- Manejo de errores -->
    <script>
        window.addEventListener('error', function(e) {
            console.error('Error during execution of script:', e.error);
        });
    </script>

    <!-- Pie de página -->
    <footer class="mt-5 text-muted text-center">
        <p>&copy; 2024 The Chabo. Todos los derechos reservados.</p>
    </footer>
</body>

</html>