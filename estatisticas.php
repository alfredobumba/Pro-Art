<?php
require 'conexao.php'; 
?>

<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Gênero', 'Percentagem'],
        <?php
        include 'conexao.php';
        
        $sql = "SELECT genero, COUNT(*) as count FROM atores GROUP BY genero";
        $buscar = mysqli_query($con, $sql);

        // Verifique se a consulta foi bem-sucedida
        if (!$buscar) {
            die('Erro na consulta: ' . mysqli_error($con));
        }

        $total = 0;
        $genero_counts = array();

        // Processar os resultados da consulta
        while ($atores = mysqli_fetch_array($buscar)) {
          $genero = $atores['genero'];
          $count = $atores['count'];
          $total += $count;
          $genero_counts[$genero] = $count;
        }

        // Verificar se há dados para exibir
        if ($total > 0) {
          foreach ($genero_counts as $genero => $count) {
            $percentage = ($count / $total) * 100;
            echo "['$genero', $percentage],";
          }
        } else {
          echo "['No Data', 100]";
        }
        ?>
        ]);

        var options = {
          title: 'Gênero dos atores',
          colors: ['#1E90FF', '#FF69B4'], // Cores para os diferentes gêneros
          pieHole: 0.4,
          pieSliceText: 'percentage',
          slices: {
            0: { color: '#1E90FF' }, // Masculino
            1: { color: '#FF69B4' }  // Feminino
          }
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8">
            <div id="piechart" style="width: 900px; height: 500px;"></div>
            </div>
          </div class="col-md-8">
          <h2>Gráfico do cache dos atores</h2> 
          </div>
        </div>
    </div>
  </body>
</html>
