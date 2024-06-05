<?php

        //FUNÇÃO PARA SUBIR IMAGEM
        function enviaImagem($imagem, $caminho, $imagemTemp){
            $extensao = pathinfo($imagem, PATHINFO_EXTENSION);
            $extensao = strtolower($extensao);

            if(strstr('.jpg;.jpeg;.png', $extensao)){
                $imagem = $caminho.mt_rand().".".$extensao;

                $diretorio = "imagens/".$caminho."/";

                move_uploaded_file($imagemTemp, $diretorio.$imagem);
            }else{ ?>
                <div class="alert alert-danger" role="alert">
                    A imagem deve ser no formato *.jpeg, *jpg e *.png!
                </div>
            <?php
            }
            return $imagem;
        }


// Função para verificar se uma oferta existe
function verificaOfertasId($ofertas_id) {
    include "conexao.php";

    $sql = "SELECT id FROM ofertas WHERE id = $ofertas_id";
    $res = mysqli_query($con, $sql);

    $exists = mysqli_num_rows($res) > 0;

    mysqli_free_result($res);
    mysqli_close($con);

    return $exists;
}

// Outras funções já existentes
// ...

// FUNÇÕES PARA EXECUTAR AS QUERYS E AS MENSAGENS DE SAÍDA
function executaQuery($sql, $paginaDeRetorno) {
    include "conexao.php";

    try {
        if ($res = mysqli_query($con, $sql)) {
            $reg = mysqli_fetch_assoc($res);

            $saida = isset($reg['saida']) ? $reg['saida'] : '';
            $rotulo = isset($reg['saida_rotulo']) ? $reg['saida_rotulo'] : '';

            // Definindo a classe de alerta com base no rótulo
            switch ($rotulo) {
                case 'Tudo certo!':
                    $alert = 'alert-success';
                    break;
                case 'OPS!':
                    $alert = 'alert-warning';
                    break;
                case 'ERRO!':
                    $alert = 'alert-danger';
                    break;
                default:
                    $alert = 'alert-info';
                    break;
            }

            // Exibindo a mensagem de alerta
            echo "<div class='alert $alert' role='alert'>";
            echo "<h3>$rotulo</h3>";
            echo "<p>$saida</p>";
            echo "<a href='$paginaDeRetorno' class='alert-link' target='_self'>Voltar</a>";
            echo "</div>";

            // Liberando o resultado da memória
            mysqli_free_result($res);

            // Limpando quaisquer resultados pendentes
            while (mysqli_more_results($con)) {
                mysqli_next_result($con);
            }
        } else {
            throw new mysqli_sql_exception("ERRO ao executar a query.");
        }
    } catch (mysqli_sql_exception $e) {
        // Capturando e exibindo a exceção
        echo "<div class='alert alert-danger' role='alert'>";
        echo "<h3>ERRO!</h3>";
        echo "<p>Erro ao executar a query: {$e->getMessage()}</p>";
        echo "</div>";
    }

    // FECHANDO A CONEXÃO COM O BANCO DE DADOS
    if (isset($con)) {
        mysqli_close($con);
    }
}

    
//FUNÇÃO PARA EXLUIR TODAS AS IMAGENS DE UM ATOR, OFERTAS, AGENCIAS, PRODUTORAS, BANNER
function  excluiTodasImagens($id, $alvo) {
    include_once "conexao.php";

    $imagens = array();
    $linhas = 0;
    $where = $alvo."_id";


    //SELECT * FROM imagens WHERE atores_id = 92
    $sql = "SELECT * FROM imagens WHERE ".$where." = $id";
    if ($res = mysqli_query($con,$sql)) {
        $linhas = mysqli_affected_rows($con);
        if ($linhas > 0) {
            while ($reg = mysqli_fetch_assoc($res)) {

                $delete = unlink("imagens/".$alvo."/".$reg["caminho"]);
                if (!$delete) {
                    ?>
                    <div class="alert danger" role="alert">
                        <h3>Erro!</h3>
                        <p>Algo deu errado ao excluír a imagem: <?php $reg["caminho"]; ?></p>
                        <br>
                    </div>
                    <?php
                }
            }
        }
    }else{ ?>

            <div class="alert danger" role="alert">
                <h3>Erro!</h3>
                <p>Algo deu errado ao executar a query!</p>
                <br>
            </div>
        <?php
    }

     // FECHANDO A CONEXÃO COM O BANCO DE DADOS
     if (isset($con)) {
        mysqli_close($con);
    }
}


function  excluiImagem($id, $alvo) {
    include_once "conexao.php";

    $imagens = array();
    $linhas = 0;
    $where = $alvo."_id";


    //SELECT * FROM imagens WHERE atores_id = 92
    $sql = "SELECT * FROM imagens WHERE ".$where." = $id";
    if ($res = mysqli_query($con,$sql)) {
        $linhas = mysqli_affected_rows($con);
        if ($linhas > 0) {
            while ($reg = mysqli_fetch_assoc($res)) {

                $delete = unlink("imagens/".$alvo."/".$reg["caminho"]);
                if (!$delete) {
                    ?>
                    <div class="alert danger" role="alert">
                        <h3>Erro!</h3>
                        <p>Algo deu errado ao excluír a imagem: <?php $reg["caminho"]; ?></p>
                        <br>
                    </div>
                    <?php
                }
            }
        }
    }else{ ?>

            <div class="alert danger" role="alert">
                <h3>Erro!</h3>
                <p>Algo deu errado ao executar a query!</p>
                <br>
            </div>
        <?php
    }

     // FECHANDO A CONEXÃO COM O BANCO DE DADOS
     if (isset($con)) {
        mysqli_close($con);
    }
}

function  excluiUmaImagens($id, $alvo) {
    include_once "conexao.php";

    $imagens = array();
    $linhas = 0;
    $where = $alvo."_id";


    //SELECT * FROM imagens WHERE atores_id = 92
    $sql = "SELECT * FROM imagens WHERE id = $id";
    if ($res = mysqli_query($con,$sql)) {
        $linhas = mysqli_affected_rows($con);
        if ($linhas > 0) {
            while ($reg = mysqli_fetch_assoc($res)) {

                $delete = unlink("imagens/".$alvo."/".$reg["caminho"]);
                if (!$delete) {
                    ?>
                    <div class="alert danger" role="alert">
                        <h3>Erro!</h3>
                        <p>Algo deu errado ao excluír a imagem: <?php $reg["caminho"]; ?></p>
                        <br>
                    </div>
                    <?php
                }
            }
        }
    }else{ ?>

            <div class="alert danger" role="alert">
                <h3>Erro!</h3>
                <p>Algo deu errado ao executar a query!</p>
                <br>
            </div>
        <?php
    }

     // FECHANDO A CONEXÃO COM O BANCO DE DADOS
     if (isset($con)) {
        mysqli_close($con);
    }
}

      ?>