<?php
require "../../../lib/config.php";

global $pdo;
#@formatter:off
if ($_POST['acao'] === 'remover_assinatura') {
    $codigo         = $_POST['codigo'];
    $cod_assinatura = $_POST['cod_assinatura'];

    $query = $pdo->prepare("SELECT assinaturas FROM assinatura_geral WHERE codigo = :codigo");
    $query->bindValue(':codigo', $codigo);
    $query->execute();

    $assinatura_fetch = $query->fetch();

    $assinatura_json = json_decode($assinatura_fetch['assinaturas'], true);
    $search = array_search($cod_assinatura, array_column($assinatura_json, 'codigo'));
    array_splice($assinatura_json, $search, 1);

    $query1 = $pdo->prepare("UPDATE assinatura_geral SET assinaturas = :assinaturas WHERE codigo = :codigo");
    $query1->bindValue(':assinaturas', json_encode($assinatura_json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    $query1->bindValue(':codigo', $codigo);

    if (@$query1->execute()) {
        echo json_encode([
            'status' => true,
            'msg'    => 'Assinatura removida com sucesso',
        ]);
    } else {
        echo json_encode([
            'status' => false,
            'msg'    => 'Error ao remover assinatura',
            'db_error' => $query1->errorInfo()
        ]);
    }

    exit();
}
#@formatter:on