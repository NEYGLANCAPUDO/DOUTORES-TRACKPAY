<?php
session_start();
require_once(__DIR__ . "/../model/bill.php");

class billController {

    public function cadastrarBill() {
        {
            $id = $_SESSION['id'];
            $descricao_bill = $_POST['descricao'];
            $valor_bill = $_POST['valor'];
            $importancia_bill = $_POST['importancia'];
            create_bill($descricao_bill, $valor_bill, $importancia_bill, $id);
            header("Location: ../view/usuario/index_usuario.php");
            exit;
        }
    }
    public function deletarBill() {
        {
            $id = $_SESSION['id'];
            $id_conta = $_POST['id_conta'];
            delete_bill($id, $id_conta);
            header("Location: ../view/usuario/index_usuario.php");
            exit;
        }
    }
}
$acaoBill = $_REQUEST['acaoBill'] ?? null;

if($acaoBill) {
    $instanciaBill = new billController();

    if($acaoBill == 'cadastrarBill') {
        $instanciaBill->cadastrarBill();
    } elseif($acaoBill == 'deletarBill') {
        $instanciaBill->deletarBill();
    }
}