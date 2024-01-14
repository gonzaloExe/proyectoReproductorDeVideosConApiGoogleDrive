<!DOCTYPE html>
<html>

<head>
    <title>Listar archivos PDF en Google Drive</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />


    <script>
        function submitForm() {
            document.getElementById("search-form").submit();
        }
        document.getElementById("search-input").addEventListener("input", function(event) {
            event.preventDefault();
            if (this.value === "") {
                submitForm();
            }
        });
    </script>


</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fas fa-book text-dark fs-4 me-2"></i>Lengua y Literatura</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <form action="" method="get" class="mb-3" id="search-form">
                    <div class="input-group">
                        <input type="text" name="search" placeholder="Buscar por nombre" class="form-control" id="search-input" value="<?php echo $_GET['search'] ?? '' ?>">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </form>
            </div>
        </div>
    </nav>

    <hr>
    <?php

    use Google\Client;
    use Google\Service\Drive;

    // Autenticación de la API de Google Drive
    require_once 'api-google/vendor/autoload.php';
    $client = new Google_Client();
    $client->setApplicationName('BibliotecaDefinitiva');
    $client->setScopes(Google_Service_Drive::DRIVE_METADATA_READONLY);
    $client->setAuthConfig('bibliotecadefinitiva-93a29424a695.json');
    $service = new Google_Service_Drive($client);

    // ID de la carpeta de Google Drive donde se buscarán los archivos PDF
    $folder_id = '1Kk80gHbYxmWuOCVdb8EdYlaBjqU4Dwpo';

    // Buscar todos los archivos PDF en la carpeta de Google Drive y mostrarlos en una lista
    $query = "mimeType='application/pdf' and trashed=false and parents in '" . $folder_id . "'";
    $results = $service->files->listFiles(array(
        'q' => $query,
        'fields' => 'files(name,id)'
    ));
    $files = $results->getFiles();

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $_GET['search'];
        $query = "mimeType='application/pdf' and trashed=false and parents in '" . $folder_id . "' and name contains '" . $search . "'";
    } else {
        $query = "mimeType='application/pdf' and trashed=false and parents in '" . $folder_id . "'";
    }

    $results = $service->files->listFiles(array(
        'q' => $query,
        'fields' => 'files(name,id)'
    ));
    $files = $results->getFiles();
    ?>


    <?php if (empty($files)) : ?>

    <?php else : ?>
        <ul class="list-group">
            <?php foreach ($files as $file) : ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo $file->getName() ?>
                    <div class="d-inline-flex gap-2">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#pdfModal<?php echo $file->getId(); ?>">Previsualizar</button>
                        <a href="https://drive.google.com/file/d/<?php echo $file->getId() ?>/view" target="_blank" class="btn btn-primary btn-sm">Ver PDF</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>

    <?php foreach ($files as $file) : ?>
        <div class="modal fade" id="pdfModal<?php echo $file->getId(); ?>" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pdfModalLabel"><?php echo $file->getName(); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <iframe src="https://drive.google.com/file/d/<?php echo $file->getId(); ?>/preview" width="100%" height="600"></iframe>
                    </div>
                </div>
            </div>
        </div>


    <?php endforeach; ?>

</body>

</html>