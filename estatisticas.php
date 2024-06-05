<!DOCTYPE html>
<html lang="pt-br">
<head>
  <?php include_once "header.html"; ?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load('current', {'packages':['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawPieChart);
    google.charts.setOnLoadCallback(drawBarChart);
    google.charts.setOnLoadCallback(drawHistogram);

    function drawPieChart() {
      var data = google.visualization.arrayToDataTable([
        ['Gênero', 'Percentagem'],
        <?php
        include 'conexao.php';
        
        $sql = "SELECT genero, COUNT(*) as count FROM atores GROUP BY genero";
        $buscar = mysqli_query($con, $sql);

        if (!$buscar) {
            die('Erro na consulta: ' . mysqli_error($con));
        }

        $total = 0;
        $genero_counts = array();

        while ($atores = mysqli_fetch_array($buscar)) {
          $genero = $atores['genero'];
          $count = $atores['count'];
          $total += $count;
          $genero_counts[$genero] = $count;
        }

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
        colors: ['#1E90FF', '#FF69B4'],
        pieHole: 0.4,
        pieSliceText: 'percentage',
        slices: {
          0: { color: '#1E90FF' },
          1: { color: '#FF69B4' }
        },
        fontName: 'Arial',
        fontSize: 16,
        chartArea: {
          width: '90%',
          height: '80%'
        },
        legend: {
          position: 'bottom',
          textStyle: { fontSize: 14 }
        }
      };

      var chart = new google.visualization.PieChart(document.getElementById('piechart'));
      chart.draw(data, options);
    }

    function drawBarChart() {
      var data = google.visualization.arrayToDataTable([
        ['Ator', 'Idade', { role: 'style' }],
        <?php
        include 'conexao.php';

        $sql = "SELECT nome, idade FROM atores WHERE idade IS NOT NULL GROUP BY nome LIMIT 15";
        $buscar = mysqli_query($con, $sql);

        if (!$buscar) {
            die('Erro na consulta: ' . mysqli_error($con));
        }

        while ($atores = mysqli_fetch_array($buscar)) {
          $nome = $atores['nome'];
          $idade = $atores['idade'];
          echo "['$nome', $idade, '#e7711c'],";
        }
        ?>
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
        { calc: "stringify",
          sourceColumn: 1,
          type: "string",
          role: "annotation" },
        2]);

      var options = {
        width: 600,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
        hAxis: {
          title: 'Idade',
          titleTextStyle: {
            italic: false,
            bold: true
          },
          textStyle: { fontSize: 14 }
        },
        vAxis: {
          title: 'Ator',
          titleTextStyle: {
            italic: false,
            bold: true
          },
          textStyle: { fontSize: 14 }
        },
        fontName: 'Arial',
        fontSize: 16,
        chartArea: {
          width: '90%',
          height: '80%'
        }
      };

      var chart = new google.visualization.ColumnChart(document.getElementById('barchart'));
      chart.draw(view, options);
    }
    
    function drawHistogram() {
      var data = google.visualization.arrayToDataTable([
        ['Nome', 'Cache'],
        <?php
        include 'conexao.php';

        $sql = "SELECT nome, cache FROM atores LIMIT 15";
        $buscar = mysqli_query($con, $sql);

        if (!$buscar) {
            die('Erro na consulta: ' . mysqli_error($con));
        }

        while ($atores = mysqli_fetch_array($buscar)) {
          $nome = $atores['nome'];
          $cache = $atores['cache'];
          echo "['$nome', $cache],";
        }
        ?>
      ]);

      var options = {
        legend: { position: 'none' },
        colors: ['#e44d26'],
        hAxis: {
          title: 'Cachê',
          titleTextStyle: {
            italic: false,
            bold: true
          },
          textStyle: { fontSize: 14 }
        },
        vAxis: {
          title: 'Contagem',
          titleTextStyle: {
            italic: false,
            bold: true
          },
          textStyle: { fontSize: 14 }
        },
        fontName: 'Arial',
        fontSize: 16,
        chartArea: {
          width: '90%',
          height: '80%'
        },
        histogram: { lastBucketPercentile: 5 },
        bar: { gap: 0 }
      };

      var chart = new google.visualization.Histogram(document.getElementById('histogram_chart')); 
      chart.draw(data, options);
    }
  </script>
  <style>
    .chart-container {
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .chart {
      width: 80%;
      height: 400px;
      margin: 20px;
    }
  </style>
</head>
<body>
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
        <div class="chart-container">
          <br>
          <h2>Gênero dos atores</h2>
          <div id="piechart" class="chart"></div>

          <br>
          <h2>Idade dos Atores</h2>
          <div id="barchart" class="chart"></div>

          <br>
          <h2>Distribuição do Cachê dos Atores</h2>
          <div id="histogram_chart" class="chart"></div>
        </div>
      </div>
    </div>
  </main>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
