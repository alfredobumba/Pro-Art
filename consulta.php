<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados das Consultas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <?php
                        require 'conexao.php';

                        $sql_genero = "SELECT genero, COUNT(*) as count FROM atores GROUP BY genero";
                        $buscar_genero = mysqli_query($con, $sql_genero);

                        if (!$buscar_genero) {
                            die('Erro na consulta de gêneros: ' . mysqli_error($con));
                        }

                        $total_genero = 0;
                        $genero_counts = array();

                        while ($atores = mysqli_fetch_array($buscar_genero)) {
                            $genero = $atores['genero'];
                            $count = $atores['count'];
                            $total_genero += $count;
                            $genero_counts[$genero] = $count;
                        }

                        echo "<h5 class='card-title'>Percentagem de Gêneros dos atores</h5>";
                        if ($total_genero > 0) {
                            foreach ($genero_counts as $genero => $count) {
                                $percentage = ($count / $total_genero) * 100;
                                echo "<p class='card-text'>$genero: " . number_format($percentage, 2) . "%</p>";
                            }
                        } else {
                            echo "<p class='card-text'>Nenhum dado encontrado.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <?php
                        $sql_cache = "SELECT nome, cache FROM atores LIMIT 15";
                        $buscar_cache = mysqli_query($con, $sql_cache);

                        if (!$buscar_cache) {
                            die('Erro na consulta de cache: ' . mysqli_error($con));
                        }

                        echo "<h5 class='card-title'>Dados do Histograma do Cachê dos atores</h5>";
                        if (mysqli_num_rows($buscar_cache) > 0) {
                            while ($ator = mysqli_fetch_array($buscar_cache)) {
                                echo "<p class='card-text'>Nome: " . $ator['nome'] . " - Cachê: " . $ator['cache'] . "</p>";
                            }
                        } else {
                            echo "<p class='card-text'>Nenhum dado encontrado para o histograma de cache.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <?php
                        $sql_media_idade = "SELECT AVG(idade) AS media_idade FROM atores";
                        $resultado_media_idade = mysqli_query($con, $sql_media_idade);
                        $media_idade = 0;

                        if ($resultado_media_idade) {
                            $linha = mysqli_fetch_assoc($resultado_media_idade);
                            $media_idade = $linha['media_idade'];
                        }

                        echo "<h5 class='card-title'>Média das Idades dos atores</h5>";
                        if ($media_idade > 0) {
                            echo "<p class='card-text'>Média da idade: " . number_format($media_idade, 2) . "</p>";
                        } else {
                            echo "<p class='card-text'>Nenhuma média de idade calculada.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
           
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <?php
                        $sql_media_desvio = "SELECT AVG(cache) AS media_cache, STDDEV(cache) AS desvio_padrao FROM atores WHERE cache BETWEEN 100 AND 5000";
                        $resultado_media_desvio = mysqli_query($con, $sql_media_desvio);

                        if ($resultado_media_desvio) {
                            $row = mysqli_fetch_assoc($resultado_media_desvio);
                            $media_cache = $row['media_cache'];
                            $desvio_padrao = $row['desvio_padrao'];

                            echo "<h5 class='card-title'>Média e Desvio Padrão do Cachê dos atores</h5>";
                            if ($media_cache > 0 && $desvio_padrao > 0) {
                                echo "<p class='card-text'>Média do cachê: " . number_format($media_cache, 2) . "</p>";
                                echo "<p class='card-text'>Desvio padrão do cachê: " . number_format($desvio_padrao, 2) . "</p>";

                                $intervalo_inferior = 100 - $desvio_padrao;
                                $intervalo_superior = 5000 + $desvio_padrao;

                                echo "<p class='card-text'>Intervalo de valores para cachê: " . number_format($intervalo_inferior, 2) . " a " . number_format($intervalo_superior, 2) . "</p>";

                                $sql_intervalo = "SELECT nome, cache FROM atores WHERE cache BETWEEN $intervalo_inferior AND $intervalo_superior";
                                $resultado_intervalo = mysqli_query($con, $sql_intervalo);

                                if ($resultado_intervalo && mysqli_num_rows($resultado_intervalo) > 0) {
                                    echo "<h5 class='card-title'>Atores no Intervalo de Cachê</h5>";
                                    while ($ator = mysqli_fetch_array($resultado_intervalo)) {
                                        echo "<p class='card-text'>Nome: " . $ator['nome'] . " - Cachê: " . $ator['cache'] . "</p>";
                                    }
                                } else {
                                    echo "<p class='card-text'>Nenhum ator encontrado no intervalo.</p>";
                                }
                            } else {
                                echo "<p class='card-text'>Nenhuma média ou desvio padrão calculado.</p>";
                            }
                        } else {
                            die('Erro na consulta: ' . mysqli_error($con));
                        }
                        ?>
                    </div>
</body>
</html>
