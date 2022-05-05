<?php
require "../../../lib/config.php";
global $pdo;

if ($_POST and $_POST['acao'] === 'remover_assinatura') {
    #@formatter:off
    $codigo        = $_POST['codigo'];
    $codigo_mensal = $_POST['codigo_mensal'];
    $tipo_relatorio = $_POST['tipo_relatorio'];

    $query = $pdo->prepare("SELECT assinaturas FROM avaliacao_mensal WHERE codigo = :c");
    $query->bindValue(':c', $codigo_mensal);
    $query->execute();

    $assinatura = $query->fetch(PDO::FETCH_ASSOC);

    $json = json_decode($assinatura['assinaturas'], true);
    $search = array_search($codigo, array_column($json, 'codigo'));
    array_splice($json, $search, 1);

    $query1 = $pdo->prepare("UPDATE avaliacao_mensal SET assinaturas = :j WHERE codigo = :c");
    $query1->bindValue(':j', json_encode($json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    $query1->bindValue(':c', $codigo_mensal);

    if (@$query1->execute()) {
        echo json_encode([
            'status'         => true,
            'msg'            => 'Assinatura removida com sucesso',
            'desabilita_btn' => $codigo == $_SESSION['musashi_cod_usu']

        ]);
    } else {
        echo json_encode([
            'status'   => false,
            'msg'      => 'Error ao remover assinatura',
            'db_error' => $query1->errorInfo()
        ]);
    }
    #@formatter:off
    exit();

}