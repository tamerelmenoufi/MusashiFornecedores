<?php
    require_once "../../lib/config.php";
    // redireciona para o formulário selecionado, ou para o IPF, caso nenhum seja selecionado
    if(isset($_POST['tipo_relatorio'])){
        $tipo_relatorio = $_POST['tipo_relatorio'];
    }else{
        $tipo_relatorio = "IPF";
    }

    if(isset($_POST['ano'])){
        $Y = $_POST['ano'];
    }else{
        $Y = date("Y");
    }

    if(isset($_POST['mes'])){
        $M = str_pad($_POST['mes'], 2, "0", STR_PAD_LEFT);
    }else{
        $M = date("m");
    }
    require("relatorio/".$tipo_relatorio."/relatorio_fornecedor.php");
    exit();
?>