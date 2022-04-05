<?php
    require_once "../../lib/config.php";
    // redireciona para o formulário selecionado, ou para o IPF, caso nenhum seja selecionado
    if(isset($_POST['tipo_relatorio'])){
        $tipo_relatorio = $_POST['tipo_relatorio'];
    }else{
        $tipo_relatorio = "IPF";
    }
    require("relatorio/".$tipo_relatorio."/relatorio_fornecedor.php");
    exit();
?>