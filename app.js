        // Función para cargar videos de una temporada específica mediante AJAX
        function cargarVideos(temporada) {
            // Lógica para cargar videos de la temporada seleccionada
            $.ajax({
                url: 'cargar_videos.php', // Archivo PHP para cargar videos
                method: 'POST',
                data: { temporada: temporada }, // Parámetro a enviar al servidor
                success: function(response) {
                    // Actualizar el contenido del contenedor de videos con la respuesta del servidor
                    $('#video-row').html(response);
                    ajustarColumnas();
                },
                error: function(error) {
                    console.error('Error durante la carga de videos:', error);
                }
            });
        }

        // Función para ajustar el número de columnas según el tamaño de la pantalla
        function ajustarColumnas() {
            var screenWidth = $(window).width();
            var columnCount = (screenWidth >= 1200) ? 4 : (screenWidth >= 992) ? 3 : 1;

            // Configurar el número de columnas
            $('#video-row > div').removeClass().addClass('col-sm-12 col-md-6 col-lg-' + (12 / columnCount) + ' col-xl-' + (12 / columnCount));
        }

        // Lógica adicional de JavaScript
        // ...

        // Llamar a la función de ajuste al cargar la página y al cambiar el tamaño de la ventana
        $(document).ready(function() {
            ajustarColumnas();
        });

        $(window).resize(function() {
            ajustarColumnas();
        });