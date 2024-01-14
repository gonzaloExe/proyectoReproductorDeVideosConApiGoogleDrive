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
                <li class="nav-item active">
                    <a class="nav-link" href="#" onclick="cargarVideos('temporada72')">Temporada 72</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="#" onclick="cargarVideos('temporada73')">Temporada 73</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="#" onclick="cargarVideos('temporada74')">Temporada 74</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="#" onclick="cargarVideos('temporada75')">Temporada 75</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="#" onclick="cargarVideos('temporada76')">Temporada 76</a>
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
    <div class="container">
        <div class="row" id="videos-container"></div>
    </div>

    <!-- Scripts de Bootstrap y Popper.js (asegúrate de incluir Popper.js antes de Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script>
        // Ejecutar cargarVideos('temporada72') al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            cargarVideos('temporada72');
        });

        function cargarVideos(temporada) {
            var videosContainer = document.getElementById('videos-container');
            videosContainer.innerHTML = ''; // Limpiamos el contenedor de videos

            // Utilizamos un objeto para mapear temporadas a nombres de archivos
            var videosPorTemporada = {
                'temporada72': <?php echo json_encode(obtenerNombresVideos('temporada72')); ?>,
                'temporada73': <?php echo json_encode(obtenerNombresVideos('temporada73')); ?>,
                'temporada74': <?php echo json_encode(obtenerNombresVideos('temporada74')); ?>,
                'temporada75': <?php echo json_encode(obtenerNombresVideos('temporada75')); ?>,
                'temporada76': <?php echo json_encode(obtenerNombresVideos('temporada76')); ?>
                // Agrega más temporadas según sea necesario
            };

            var nombresVideos = videosPorTemporada[temporada];

            nombresVideos.forEach(function(nombreVideo) {
                var videoCard = document.createElement('div');
                videoCard.className = 'card mb-3 col-sm-12 col-md-6 col-lg-4 col-xl-3';

                // Crear el elemento de video
                var video = document.createElement('video');
                video.src = './videos/' + temporada + '/' + nombreVideo;
                video.controls = true;
                video.className = 'card-img-top';
                videoCard.appendChild(video);

                // Crear el cuerpo de la tarjeta con el nombre del video (sin extensión)
                var cardBody = document.createElement('div');
                cardBody.className = 'card-body';

                var cardTitle = document.createElement('h5');
                cardTitle.className = 'card-title';
                cardTitle.textContent = nombreVideo.replace(/\.[^/.]+$/, ""); // Elimina la extensión
                cardBody.appendChild(cardTitle);

                videoCard.appendChild(cardBody);

                videosContainer.appendChild(videoCard);
            });
        }

        function buscarVideos() {
            var searchTerm = document.getElementById('search-input').value.toLowerCase();
            var videosContainer = document.getElementById('videos-container');
            videosContainer.innerHTML = ''; // Limpiamos el contenedor de videos

            var videosPorTemporada = {
                'temporada72': <?php echo json_encode(obtenerNombresVideos('temporada72')); ?>,
                'temporada73': <?php echo json_encode(obtenerNombresVideos('temporada73')); ?>,
                'temporada74': <?php echo json_encode(obtenerNombresVideos('temporada74')); ?>,
                'temporada75': <?php echo json_encode(obtenerNombresVideos('temporada75')); ?>,
                'temporada76': <?php echo json_encode(obtenerNombresVideos('temporada76')); ?>
                // Agrega más temporadas según sea necesario
            };

            var resultados = [];

            // Búsqueda en cada temporada
            for (var temporada in videosPorTemporada) {
                var nombresVideos = videosPorTemporada[temporada];
                var coincidencias = nombresVideos.filter(function(nombre) {
                    return nombre.toLowerCase().includes(searchTerm);
                });

                resultados = resultados.concat(coincidencias.map(function(nombre) {
                    return {
                        temporada: temporada,
                        video: nombre
                    };
                }));
            }

            // Mostrar los resultados
            resultados.forEach(function(resultado) {
                var videoCard = document.createElement('div');
                videoCard.className = 'card mb-3 col-sm-12 col-md-6 col-lg-4 col-xl-3';

                var video = document.createElement('video');
                video.src = './videos/' + resultado.temporada + '/' + resultado.video;
                video.controls = true;
                video.className = 'card-img-top';
                videoCard.appendChild(video);

                var cardBody = document.createElement('div');
                cardBody.className = 'card-body';

                var cardTitle = document.createElement('h5');
                cardTitle.className = 'card-title';
                cardTitle.textContent = resultado.video.replace(/\.[^/.]+$/, "");
                cardBody.appendChild(cardTitle);

                videoCard.appendChild(cardBody);

                videosContainer.appendChild(videoCard);
            });

            if (resultados.length === 0) {
                var noResultsMessage = document.createElement('p');
                noResultsMessage.textContent = 'No se encontraron resultados.';
                videosContainer.appendChild(noResultsMessage);
            }
        }

        // Esta función PHP escanea la carpeta de videos y devuelve un array con los nombres de los archivos
        <?php
        function obtenerNombresVideos($temporada)
        {
            $directorio = "./videos/$temporada/";
            $videos = [];

            if (is_dir($directorio)) {
                if ($gestor = opendir($directorio)) {
                    while (($archivo = readdir($gestor)) !== false) {
                        if ($archivo != "." && $archivo != "..") {
                            $videos[] = $archivo;
                        }
                    }
                    closedir($gestor);
                }
            }

            return $videos;
        }
        ?>

        var videoIndex = 0;
        var shuffledVideos = [];

        function shuffleArray(array) {
            var currentIndex = array.length,
                randomIndex;

            // Mientras haya elementos a barajar
            while (currentIndex !== 0) {
                // Escoge un elemento restante
                randomIndex = Math.floor(Math.random() * currentIndex);
                currentIndex--;

                // E intercámbialo con el elemento actual
                [array[currentIndex], array[randomIndex]] = [array[randomIndex], array[currentIndex]];
            }

            return array;
        }
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

    <!-- Script del Modo TV mejorado -->





</body>

</html>