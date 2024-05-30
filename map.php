<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa com Localização e Marcadores</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
    <script type="text/javascript">
        let map;
        let userMarker;

        function toRad(x) {
            return x * Math.PI / 180;
        }

        function haversineDistance(coords1, coords2) {
            const lat1 = coords1.lat;
            const lon1 = coords1.lng;
            const lat2 = coords2.lat;
            const lon2 = coords2.lng;

            const R = 6371; // Raio da Terra em km
            const dLat = toRad(lat2 - lat1);
            const dLon = toRad(lon2 - lon1);
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                      Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
                      Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            const distance = R * c;

            return distance; // Retorna a distância em km
        }

        function isWithinRadius(center, point, radius) {
            const distance = haversineDistance(center, point);
            return distance <= (radius / 1000); // Convertendo metros para km
        }

        function drawLine(pointA, pointB) {
            const line = new google.maps.Polyline({
                path: [pointA, pointB],
                geodesic: true,
                strokeColor: '#FF0000',
                strokeOpacity: 1.0,
                strokeWeight: 2
            });
            line.setMap(map);
        }

        function drawCircle(center, radius) {
            const circle = new google.maps.Circle({
                map: map,
                radius: radius, // Raio em metros
                center: center,
                fillColor: '#AA0000',
                fillOpacity: 0.35,
                strokeColor: '#AA0000',
                strokeOpacity: 0.8,
                strokeWeight: 2
            });
        }

        function initMap() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    const userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    map = new google.maps.Map(document.getElementById('map_canvas'), {
                        zoom: 12,
                        center: userLocation,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    });

                    userMarker = new google.maps.Marker({
                        map: map,
                        position: userLocation,
                        title: 'Sua localização'
                    });

                    drawCircle(userLocation, 100); // Desenha um círculo de 100 metros ao redor da localização do usuário
                    loadMarkers();
                }, () => {
                    handleLocationError(true, map.getCenter());
                });
            } else {
                handleLocationError(false, map.getCenter());
            }
        }

        function handleLocationError(browserHasGeolocation, pos) {
            alert(browserHasGeolocation ? 'Erro: Falha no serviço de geolocalização.' : 'Erro: Seu navegador não suporta geolocalização.');
        }

        function loadMarkers() {
            $.ajax({
                url: 'get_markers.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    data.forEach(markerData => {
                        const markerPosition = { lat: parseFloat(markerData.lat), lng: parseFloat(markerData.lng) };
                        const marker = new google.maps.Marker({
                            map: map,
                            position: markerPosition,
                            title: markerData.name
                        });

                        const infowindow = new google.maps.InfoWindow({
                            content: `<div><strong>${markerData.name}</strong><br>${markerData.type}</div>`
                        });

                        marker.addListener('click', function() {
                            infowindow.open(map, marker);
                        });

                        // Verifica se o marcador está dentro do raio de 100m
                        if (isWithinRadius(userMarker.getPosition().toJSON(), markerPosition, 100)) {
                            console.log(`O marcador ${markerData.name} está dentro do raio de 100 metros.`);
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao carregar marcadores:', error);
                }
            });
        }
    </script>
    <style>
        #map_canvas {
            width: 100%;
            height: 500px;
        }
    </style>
</head>
<body onload="initMap()">
    <div id="map_canvas"></div>
</body>
</html>
