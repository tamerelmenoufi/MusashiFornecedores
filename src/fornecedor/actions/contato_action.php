<?php
    require "../../../lib/config.php";

    global $pdo;

    if($_POST['acao'] == 'salvar_contato'){
        $sql = $pdo->prepare("INSERT INTO contatos VALUES (default, :fornecedora, :nome, :email, :cpf, :contato, :setor, default)");

        $sql->bindValue(":fornecedora", $_POST['fornecedor']);
        $sql->bindValue(":nome", utf8decode($_POST['nome']));
        $sql->bindValue(":email", utf8decode($_POST['email']));
        $sql->bindValue(":cpf", $_POST['cpf']);
        $sql->bindValue(":contato", $_POST['contato']);
        $sql->bindValue(":setor", utf8decode($_POST['setor']));

        $sql->execute();
    }
?>