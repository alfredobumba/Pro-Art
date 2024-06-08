<!DOCTYPE html>
<html lang="pt-br">
<head>
  <?php include_once "header.html"; ?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC3hiuvwbGX6HJ22C4gBhFlHorK1OMfsu8"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script type="text/javascript">
    function initMap() {
      var mapOptions = {
        center: new google.maps.LatLng(38.7169, -9.1399), // Coordenadas de Lisboa
        zoom: 12,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };
      var map = new google.maps.Map(document.getElementById('map'), mapOptions);

      // Fetch markers and actors from database
      $.ajax({
        url: 'fetch_markers.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
          console.log('Dados recebidos:', data); // Depuração
          
          // Adiciona os marcadores ao mapa
          data.markers.forEach(markerData => {
            var marker = new google.maps.Marker({
              position: new google.maps.LatLng(markerData.lat, markerData.lng),
              map: map,
              title: markerData.name
            });

            var infowindow = new google.maps.InfoWindow();
            google.maps.event.addListener(marker, 'click', function() {
              infowindow.setContent(markerData.name + "<br>" + markerData.address);
              infowindow.open(map, marker);
            });

            // Desenha círculos com 100m e 200m de raio
            new google.maps.Circle({
              map: map,
              radius: 100, // 100 metros
              fillColor: '#AA0000',
              strokeWeight: 0,
              center: new google.maps.LatLng(markerData.lat, markerData.lng)
            });

            new google.maps.Circle({
              map: map,
              radius: 200, // 200 metros
              fillColor: '#00AA00',
              strokeWeight: 0,
              center: new google.maps.LatLng(markerData.lat, markerData.lng)
            });
          });

          // Adiciona os atores ao mapa
          data.atores.forEach(actorData => {
            var actorMarker = new google.maps.Marker({
              position: new google.maps.LatLng(actorData.lat, actorData.lng),
              map: map,
              title: actorData.nome,
              icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png' // Ícone diferente para os atores
            });

            var infowindow = new google.maps.InfoWindow();
            google.maps.event.addListener(actorMarker, 'click', function() {
              infowindow.setContent("Ator: " + actorData.nome);
              infowindow.open(map, actorMarker);
            });
          });

          // Traçar rota entre Lisboa e uma agência de publicidade específica
          var origem = new google.maps.LatLng(38.7223, -9.1393); // Lisboa
          var destino = new google.maps.LatLng(38.716237, -9.141810); // Agência de Publicidade X
          calcularRota(origem, destino, map);
        },
        error: function(xhr, status, error) {
          console.error('Erro ao buscar dados:', error); // Depuração
        }
      });

      // Tenta usar a geolocalização HTML5 para obter a localização atual do usuário
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          function(position) {
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };

            var userMarker = new google.maps.Marker({
              position: pos,
              map: map,
              title: "Você está aqui",
              icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 10,
                fillColor: "#00F",
                fillOpacity: 0.8,
                strokeWeight: 0.4
              }
            });

            map.setCenter(pos);
          },
          function() {
            handleLocationError(true, map.getCenter());
          }
        );
      } else {
        // Browser não suporta Geolocalização
        handleLocationError(false, map.getCenter());
      }
    }

    function handleLocationError(browserHasGeolocation, pos) {
      console.log(browserHasGeolocation ?
                  "Error: The Geolocation service failed." :
                  "Error: Your browser doesn't support geolocation.");
    }

    function calcularRota(origem, destino, map) {
      var directionsService = new google.maps.DirectionsService();
      var directionsRenderer = new google.maps.DirectionsRenderer();
      directionsRenderer.setMap(map);

      var request = {
        origin: origem,
        destination: destino,
        travelMode: 'DRIVING'
      };

      directionsService.route(request, function(result, status) {
        if (status == 'OK') {
          directionsRenderer.setDirections(result);
        } else {
          console.error('Erro ao traçar rota:', status);
        }
      });
    }
  </script>
  <style>
    #map {
      width: 90%;
      height: 600px;
      margin: 20px auto;
    }
  </style>
</head>
<body onload="initMap()">
  <!-- Inicio DO MENU SUPERIOR---------------->
  <?php include_once "menuSuperior.html"; ?>
  <!-- FIM DO MENU SUPERIOR------------------>
  <br><br><br>
  <main class="container">
    <div class="row">
      <!-- INICIO DO MENU LATERAL---------------->
      <div class="col-md-3 col-sm-3">
        <?php include_once "menuAdm.html"; ?>
      </div>
      <!-- FIM DO MENU LATERAL------------------>

      <div class="col-md-9 col-sm-9">
        <h2>Mapa de Marcadores</h2>
        <div id="map"></div>
      </div>
    </div>
  </main>
</body>
</html>
