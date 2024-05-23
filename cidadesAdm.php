<!DOCTYPE html>
<html>
<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    if ($_SESSION['acesso'] == true) {
?>
  <head>
      <?php
          include_once "header.html";
          include_once "conexao.php";
      ?>

      <title>HOME</title>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script type="text/javascript">
          function validaCampos(){
              if (document.fmCidades.txtCidade.value == "") {
                  Swal.fire({
                      title: 'Erro!',
                      text: 'Por favor, preencha o nome da cidade.',
                      icon: 'error',
                      confirmButtonText: 'OK',
                      customClass: {
                          confirmButton: 'btn btn-primary',
                          cancelButton: 'btn btn-danger'
                      },
                      buttonsStyling: false,
                      background: '#fff url(/images/trees.png)',
                      backdrop: `
                          rgba(0,0,123,0.4)
                          url("/images/nyan-cat.gif")
                          left top
                          no-repeat
                      `
                  });
                  document.fmCidades.txtCidade.focus();
                  return false;
              }
          }
      </script>
      <link rel="shortcut icon" href="imagens/logotipo/logotipo1.png">
  </head>
  <body class="administracao">

      <!-- Inicio DO MENU  SUPERIOR---------------->
      <?php include_once "menuSuperior.html";?>

      <!-- FIM DO MENU  SUPERIOR---------------->

      <br>
      <main class="container mt-5">
          <h1 class="text-center">Cidades-Administração</h1>
          <div class="row text-center">
              <div class="col-md-3 col-sm-3">
                  <?php include_once "menuAdm.html"; ?>
              </div>
              <div class="col-md-9 col-sm-9">
                  <?php
                      $alert = '';  // Adicione esta linha para definir $alert antes do bloco if

                      if (isset($_GET['btnSubmitCidade'])) {
                          $nomeCidade = $_GET['txtCidade'];
                          $link = $nomeCidade;
                          $sql = "CALL sp_cadastra_cidade('$nomeCidade', '$link', @saida, @rotulo);";
                          if ($res = mysqli_query($con, $sql)) {
                              $reg = mysqli_fetch_assoc($res);
                              $saida = isset($reg['saida']) ? $reg['saida'] : 'Essa cidade já existe!';
                              $rotulo = isset($reg['saida_rotulo']) ? $reg['saida_rotulo'] : '';
                              
                              switch ($rotulo) {
                                  case '!':
                                      $alert = 'alert-success';
                                      break;
                                  case 'OPS!':
                                      $alert = 'alert-warning';
                                      break;
                                  case 'ERRO!':
                                      $alert = 'alert-danger';
                                      break;
                              }

                              echo "<div class='alert $alert' role='alert'>";
                              echo "<h3>$rotulo</h3>";
                              echo "<h3>$saida</h3>";
                              echo "<a href='cidadesAdm.php' class='alert-link' target='_self'>Voltar</a>";
                              echo "</div>";
                              mysqli_free_result($res); // Libera o resultado da memória
                              // Add this line to clear the results
                              if (mysqli_more_results($con)) {
                                  while (mysqli_next_result($con)) {
                                      // Seu código aqui
                                  }
                              }
                          } else {
                              echo "ERRO ao executar a query.";
                          }
                      }
                  ?>
                  <h2 class="text-center">Cadastro de Cidades</h2>
                  <form name="fmCidade" method="get" action="cidadesAdm.php" onsubmit="return validaCampos()">
                      <label>Nome da Cidade:</label><br>
                      <input type="text" name="txtCidade" class="form-control" maxlength="50"><br>
                      <button type="submit" class="btn btn-primary w-100" name="btnSubmitCidade">Cadastrar</button><br>
                  </form>
                  <hr/>
                  <h2 class="text-center">Cidades Cadastradas:</h2><br>
                  <div class="row">
                      <?php
                          $sql = 'SELECT * FROM vw_retorna_cidade';
                          if ($res = mysqli_query($con, $sql)) {
                              $nomeCidade = array();
                              $linkCidade = array();
                              $idCidade = array();
                              $i = 0;
                              while ($reg = mysqli_fetch_assoc($res)) {
                                  $nomeCidade[$i] = $reg['nome_cidade']; // Certifique-se de que os nomes das colunas estão corretos
                                  $linkCidade[$i] = $reg['link_cidade'];
                                  $idCidade[$i] = $reg['id_cidade'];
                                  echo "<div class='col-md-3 itensCadastrados text-center'>";
                                  echo "<h4>$nomeCidade[$i]</h4>";
                                  echo "<div class='btn-group btn-group-sm' role='group' aria-label='Basic sample'>";
                                  echo "<a href='editaCidadeAdm.php?editaCidade=$idCidade[$i]&nomeCidade=$nomeCidade[$i]' class='btn btn-primary'>Editar</a>";
                                  echo "<a href='editaCidadeAdm.php?eliminarCidade=$idCidade[$i]' class='btn btn-primary' onclick='return confirm(\"Tem certeza que deseja eliminar esta Cidade?\")'>Eliminar</a>";
                                  echo "</div></div>";
                                  $i++;
                              }
                              mysqli_free_result($res); // Libera o resultado da memória
                          }
                      ?>
                  </div>
              </div>
          </div>
      </main>

      <!-- FECHANDO A CONEXÃO COM O BANCO DE DADOS -->
      <?php if (isset($con)) { mysqli_close($con); } ?>

  </body>
  <?php
    } else {
  ?>
      <meta http-equiv="refresh" content="0;url=login.php">
  <?php
    }
  ?>
</html>
