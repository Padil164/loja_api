<?php

// dependeces
require_once('inc/config.php');
require_once('inc/api_functions.php');
require_once('inc/functions.php');

if(!isset($_GET['id']) || $_GET['id'] == ''){
    header("Location: produtos.php");
    exit;
}
$produto_id = $_GET['id'];

if(isset($_GET['confirm']) && $_GET['confirm'] == 'true'){
    $results = api_request('delete_product', 'GET', ['id' => $produto_id]);
    header("Location: produtos.php");
    exit;
}


$results = api_request('get_product', 'GET', ['id' => $produto_id]);

if(count($results['data']['results']) == 0){
    header("Location: produtos.php");
    exit;
}

if($results['data']['status'] == 'SUCCESS'){
    $produto = $results['data']['results'][0]; // temos que colocar a chave 0 para poder indicar o indice do array, pois o array esta organizado em indices e sem indice ia dar erros
}else{
    $produto = [];
}

if(empty($produto)){
    header("Location: produtos.php");
    exit;
}
// ['data']['results']
// printData($cliente_id);
// regras do negocio


?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Consumidora - Produtos</title>
    <link rel="Stylesheet" href="assets/bootstrap/bootstrap.min.css">
</head>
<body>
    <?php include("inc/nav.php"); ?>
    <section class = "container">
        <div class="row">
            <div class="col p-5">
    
            <h5 class = "text-center">
                Deseja eliminar o produto <strong><?=$produto['produto']?></strong> ?
            </h5>

            <div class="text-center mt-3">
                <a href="produtos.php" class = "btn btn-secondary">NAO</a>
                <a href="produtos_delete.php?id=<?=$produto_id?>&confirm=true" class = "btn btn-primary">SIM</a>
            </div>

           </div>
        </div>

    </section>
<script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>