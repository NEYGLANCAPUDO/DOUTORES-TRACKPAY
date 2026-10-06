<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}
require_once(__DIR__ . "/../../config/conexao.php");

$conn = conn();

$id = $_SESSION['id'];

$sql1 = "SELECT * FROM bill WHERE iduser = $id ";

$result = mysqli_query($conn, $sql1);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>index_usurio</title>
    <link rel="stylesheet" href="style_usuario.css">
</head>
<body>
    <a class="botao-link" href="../../../index.php">Voltar ao index </a>
    <a class="botao-link" href="gerenciar_usuario.php">Gerenciar usuario </a>
    <a class="botao-link" href="conta_variavel.php">Criar uma conta </a>
    <a class="botao-link" href="../../controller/logout.php">Sair da conta </a> <br>
    <table>
        <tr>
            <th>Nome</th> 
            <th>Valor</th> 
            <th>Importância</th>
            <th>Apagar conta</th>
        </tr>
        <?php while($dados = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $dados['descricao']; ?></td>
            <td><?php echo $dados['valor']; ?></td>
            <td><?php if($dados['importancia'] == 1) {
                        echo "Baixa";
                    } elseif($dados['importancia'] == 2) {
                        echo "Média";
                    } elseif($dados['importancia'] == 3) {
                        echo "Alta"; } ?> </td>
            <td> <form method="POST" action="../../controller/deleteBill.php">
                    <input type="hidden" name="id_conta" value="<?php echo $dados['id_conta']; ?>">
                    <button type="submit">Apagar</button>
                </form>
        </tr> <?php } ?>
    </table>
</body>
