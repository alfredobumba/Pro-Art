<?php
include_once "conexao.php";

if (!isset($_SESSION)) {
    session_start();
}

if ($_SESSION['acesso'] == true) {
    // Supondo que os IDs estejam sendo passados por um formulário ou URL
    $ofertas_id = $_POST['ofertas_id'] ?? null;
    $produtoras_id = $_POST['produtoras_id'] ?? null;
    $agencias_id = $_POST['agencias_id'] ?? null;
    $atores_id = $_POST['atores_id'] ?? null;

    // Função para verificar a existência de um ID em uma tabela específica
    function check_id_exists($con, $table, $column, $id) {
        $sql_check = "SELECT $column FROM $table WHERE $column = ?";
        $stmt_check = $con->prepare($sql_check);
        $stmt_check->bind_param('i', $id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        return $result_check->num_rows > 0;
    }

    // Verificar se todos os IDs existem
    if (check_id_exists($con, 'ofertas', 'id', $ofertas_id) &&
        check_id_exists($con, 'produtoras', 'id', $produtoras_id) &&
        check_id_exists($con, 'agencias', 'id', $agencias_id) &&
        check_id_exists($con, 'atores', 'id', $atores_id)) {

        // Caminho e link da imagem
        $caminho = $_FILES['imagem']['name'];
        $link = $_FILES['imagem']['name'];
        $tempArquivo = $_FILES['imagem']['tmp_name'];
        $diretorio = "uploads/";

        // Mover o arquivo para o diretório de uploads
        move_uploaded_file($tempArquivo, $diretorio . $caminho);

        // Inserir na tabela imagens usando a stored procedure
        $sql = "CALL sp_cadastra_imagem(?, ?, ?, ?, ?, ?, @saida, @rotulo)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('issiiii', $ofertas_id, $caminho, $link, $produtoras_id, $agencias_id, $atores_id);
        $stmt->execute();
        $stmt->close();

        // Obter a saída e o rótulo da stored procedure
        $result = $con->query("SELECT @saida AS saida, @rotulo AS rotulo");
        $row = $result->fetch_assoc();
        $saida = $row['saida'];
        $rotulo = $row['rotulo'];

        // Exibir a mensagem de saída
        echo "<div class='alert alert-success'>$rotulo: $saida</div>";
    } else {
        // Um ou mais IDs não existem
        echo "<div class='alert alert-danger'>Erro: Um ou mais IDs fornecidos não existem.</div>";
    }

    $con->close();
} else {
    echo "<meta http-equiv='refresh' content='0;url=login.php'>";
}
?>
