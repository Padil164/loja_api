<?php

// dependeces
require_once('inc/config.php');
require_once('inc/api_functions.php');
require_once('inc/functions.php');

if(!isset($_GET['id']) || $_GET['id'] == ''){
    header("Location: clientes.php");
    exit;
}
$cliente_id = $_GET['id'];

if(isset($_GET['confirm']) && $_GET['confirm'] == 'true'){
    $results = api_request('delete_client', 'GET', ['id' => $cliente_id]);
    header("Location: clientes.php");
    exit;
}


$results = api_request('get_client', 'GET', ['id' => $cliente_id]);

if(count($results['data']['results']) == 0){
    header("Location: clientes.php");
    exit;
}

if($results['data']['status'] == 'SUCCESS'){
    $cliente = $results['data']['results'][0]; // temos que colocar a chave 0 para poder indicar o indice do array, pois o array esta organizado em indices e sem indice ia dar erros
}else{
    $cliente = [];
}

if(empty($cliente)){
    header("Location: clientes.php");
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
    <title>App Consumidora - Clientes</title>
    <link rel="Stylesheet" href="assets/bootstrap/bootstrap.min.css">
</head>
<body>
    <?php include("inc/nav.php"); ?>
    <section class = "container">
        <div class="row">
            <div class="col p-5">
    
            <h5 class = "text-center">
                Deseja eliminar o cliente <strong><?=$cliente['nome']?></strong> ?
            </h5>

            <div class="text-center mt-3">
                <a href="clientes.php" class = "btn btn-secondary">NAO</a>
                <a href="clientes_delete.php?id=<?=$cliente_id?>&confirm=true" class = "btn btn-primary">SIM</a>
            </div>

           </div>
        </div>

    </section>
<script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>