<?php
include_once "conexao.php";
include_once "log.php";

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Mapa com PHP</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map { height: 400px; width: 100%; }
    </style>
    <link rel="icon" href="data:,"> <!-- Supressão da solicitação de favicon -->
</head>
<body>
    <div id="map"></div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Inicializa o mapa
        const map = L.map('map').setView([51.505, -0.09], 13);

        // Adiciona a camada do OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);



        // Adiciona os marcadores
        <?php
        // Consulta SQL para buscar os marcadores
        $sql = "SELECT * FROM vw_retorna_markers";
        $res = mysqli_query($con, $sql);

        if ($res) {
            while ($reg = mysqli_fetch_assoc($res)) {
                if (isset($reg['latitude_maker']) && isset($reg['longitude_maker']) && isset($reg['name_maker'])) {
                    $latitude = $reg['latitude_maker'];
                    $longitude = $reg['longitude_maker'];
                    $name = addslashes($reg['name_maker']); // addslashes para evitar problemas com aspas
                    echo "L.marker([$latitude, $longitude]).addTo(map).bindPopup('$name');\n";
                } else {
                    error_log("Dados incompletos para o marcador: ". print_r($reg, true));
                }
            }
        } else {
            error_log("Erro ao consultar o banco de dados: ". mysqli_error($con));
        }
      ?>

        // Função de sucesso na obtenção da geolocalização
        function success(pos) {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            map.setView([lat, lng], 13);
        }

        // Função de erro na obtenção da geolocalização
        function error(err) {
            if (err.code === 1) {
                alert("Por favor, permita o acesso à geolocalização.");
            } else {
                alert("Não é possível obter a localização atual.");
            }
        }

        // Verifica se o navegador suporta geolocalização
        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(success, error);
        } else {
            alert("Geolocalização não é suportada pelo seu navegador.");
        }
    </script>
</body>
</html>