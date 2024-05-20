<!DOCTYPE html>
<html>
<head>
    <title>Estatísticas de Atores</title>
    <!-- Inclua o CSS do Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h2 class="text-center my-4">Estatísticas de Atores</h2>
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <canvas id="barChart"></canvas>
            </div>
            <div class="col-lg-6 col-md-12">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>
    <!-- Seu código PHP e JavaScript aqui -->
    <?php
require 'consulta.php';
require 'conexao.php';

?>

<script>
    var ctxBar = document.getElementById('barChart').getContext('2d');
    var barChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($atores); ?>,
            datasets: [{
                label: 'Número de Ofertas',
                data: <?php echo json_encode($ofertas); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)'
            }]
        }
    });

    var ctxPie = document.getElementById('pieChart').getContext('2d');
    var pieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($atores); ?>,
            datasets: [{
                label: 'Número de Ofertas',
                data: <?php echo json_encode($ofertas); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ]
            }]
        }
    });
</script>

</body>
</html>
