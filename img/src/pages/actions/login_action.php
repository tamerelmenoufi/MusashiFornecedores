<?php
    require "../../../lib/config.php";

    global $pdo;

    if($_POST['acao'] == 'logar'){
        $sql = $pdo->prepare("SELECT codigo FROM login WHERE usuario = :u AND senha = :s");
        $sql->bindValue(':u', $_POST['usuario']);
        $sql->bindValue(':s', md5($_POST['senha']));
        $sql->execute();

        $UsuCod = $sql->fetch();

        if($sql->rowCount() > 0){
            $_SESSION['musashi_cod_usu'] = $UsuCod['codigo'];
            echo "ok";
        }else{
            echo "erro";
        }
    }
?>