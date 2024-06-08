<!DOCTYPE html>
<html>
<head>
    <?php include_once "header.html"; ?>
    <title>PrO~ArT</title>
    <link rel="shortcut icon" href="imagens/logotipo/logotipo1.png">
</head>
<body>

    <!-- Início DO MENU SUPERIOR -->
    <?php include_once "menuSuperior.html"; ?>
    <!-- FIM DO MENU SUPERIOR -->

    <!-- BANNER -->
    <?php include_once "banner.php"; ?>
    <!-- FIM DO BANNER -->

    <!-- PRINCIPAL -->
    <hr>
    <h2 class="text-center">Oportunidades em Destaque</h2>
    <div class="row">
        <hr>
        <main class="container" style="width: 90%">
            <!-- INÍCIO DA VITRINE -->
            <div class="row OportunidadesEmDestaque">
                <!-- Primeira linha com três colunas -->
                <div class="col-md-4 col-sm-6 text-center">
                    <div class="card">
                        <img src="imagens/oportunidade/oportunidade1.jpg" alt="..." class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">SPOT MODELOS</h5>
                            <p class="card-text">Casting para uma marca de renome</p>
                            <a href="#" class="btn btn-primary">ver mais</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 text-center">
                    <div class="card">
                        <img src="imagens/oportunidade/oportunidade2.jpeg" alt="..." class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">BOOKVERÃO</h5>
                            <p class="card-text">Precisas de atores e modelos jovens </p>
                            <a href="#" class="btn btn-primary">ver mais</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 text-center">
                    <div class="card">
                        <img src="imagens/oportunidade/oportunidade3.jpeg" alt="..." class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">CASTING PARA NOVELA</h5>
                            <p class="card-text">Precisamos de atores de todas as idades</p>
                            <a href="#" class="btn btn-primary">ver mais</a>
                        </div>
                    </div>
                </div>
                <!-- Segunda linha com duas colunas -->
                <div class="col-md-4 col-sm-6 text-center">
                    <div class="card">
                        <img src="imagens/oportunidade/oportunidade4.jpeg" alt="..." class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">FOTOLOOK</h5>
                            <p class="card-text">Precisamos de modelos e atores</p>
                            <a href="#" class="btn btn-primary">ver mais</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 text-center">
                    <div class="card">
                        <img src="imagens/oportunidade/oportunidade5.jpeg" alt="..." class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">SABOR! É SENTIR A MÁGIA</h5>
                            <p class="card-text">Casting para todas as idades</p>
                            <a href="#" class="btn btn-primary">ver mais</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 text-center">
                    <div class="card">
                        <img src="imagens/oportunidade/oportunidade6.jpg" alt="..." class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">STELLA ARTOIS</h5>
                            <p class="card-text">Campanha de verão</p>
                            <a href="#" class="btn btn-primary">ver mais</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- FIM DA VITRINE -->

            <!-- INDICADORES -->
            <ol class="carousel-indicators">
                <li data-target="#bannerAgenciasProdutoras" data-slide-to="0" class="active"></li>
                <li data-target="#bannerAgenciasProdutoras" data-slide-to="1"></li>
                <li data-target="#bannerAgenciasProdutoras" data-slide-to="2"></li>
                <li data-target="#bannerAgenciasProdutoras" data-slide-to="3"></li>
                <li data-target="#bannerAgenciasProdutoras" data-slide-to="4"></li>
            </ol>
            <!-- FIM DOS INDICADORES -->

            <!-- galeria -->
            <hr>
            <h2 class="text-center">ATORES</h2>
            <div class="row">
                <hr>
                <br> <br>
                <div class="container" style="width: 100%">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <img src="imagens/atores/ator1.jpeg" class="img bw-to-color" alt="Descrição da imagem 1">
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <img src="imagens/atores/ator1.jpeg" class="img bw-to-color" alt="Descrição da imagem 2">
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <img src="imagens/atores/ator1.jpeg" class="img bw-to-color" alt="Descrição da imagem 3">
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <img src="imagens/atores/ator1.jpeg" class="img bw-to-color" alt="Descrição da imagem 4">
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <img src="imagens/atores/ator1.jpeg" class="img bw-to-color" alt="Descrição da imagem 5">
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <img src="imagens/atores/ator1.jpeg" class="img bw-to-color" alt="Descrição da imagem 6">
                        </div>
                    </div>
                </div>
                <br>
            </main>

            <!-- Link para o jQuery -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <!-- Link para o seu arquivo JavaScript -->
            <script src="JavaScript/script.js"></script>

                  <!-- RODAPÉ------------------------------>
                  
                  <?php include_once "rodape.html";?>
                  <!-- FIM DO RODAPÉ------------------------------>


</body>     
</html>