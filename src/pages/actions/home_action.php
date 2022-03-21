<?php
    require '../../../lib/config.php';

    if($_POST['acao'] == 'sair') {
        session_destroy();
        echo 'ok';
    }
?>