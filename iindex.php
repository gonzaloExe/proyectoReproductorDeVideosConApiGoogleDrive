<?php
require_once 'api-google/vendor/autoload.php';

use Google\Client;
use Google\Service\Drive;

$client = new Google_Client();
$client->setApplicationName('BibliotecaDefinitiva');
$client->setScopes(Google_Service_Drive::DRIVE_METADATA_READONLY);
$client->setAuthConfig('proyectotv-410418-67f96b03c92d.json'); // Reemplaza con la ruta a tu archivo de credenciales JSON
$service = new Google_Service_Drive($client);

// Definir el ID de la carpeta de la temporada seleccionada (temporada 72)
$folderId = '1OOJ_PsLXJpU717paTxQbB_PbFp-_rJHG';

// Consulta y muestra videos de la temporada específica
$query = "mimeType='video/mp4' and trashed=false and parents in '" . $folderId . "'";
$results = $service->files->listFiles(array(
    'q' => $query,
    'fields' => 'files(name,id)',
));

$videosTemporada = $results->getFiles();
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

    <div class="container">
        <div class="row g-4">
            <?php foreach ($videosTemporada as $videoTemporada): ?>
                <div class="card mb-3 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                    <video src="https://drive.google.com/uc?export=download&id=<?php echo $videoTemporada->getId(); ?>" controls class="card-img-top"></video>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $videoTemporada->getName(); ?></h5>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>
