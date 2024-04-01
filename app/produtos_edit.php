<?php

// dependeces
require_once('inc/config.php');
require_once('inc/api_functions.php');
require_once('inc/functions.php');

// logica e regras de negocio

$error_message = '';
$success_message = '';

if($_SERVER['REQUEST_METHOD'] != 'POST'){

    if(!isset($_GET['id']) || $_GET['id'] == ''){
        header("Location: produtos.php");
    }
    
    $produto = api_request('get_product', 'POST', ['id' => $_GET['id']])['data']['results'][0];
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $id_produto = $_POST['id_produto'];
    $produto = $_POST['text_produto'];
    $quantidade = $_POST['text_quantidade'];

    $results = api_request('update_product', 'POST', [
        'id_produto' => $id_produto,
        'produto' => $produto,
        'quantidade' => $quantidade,
    ]);


    // dsiplay the results of the operations on the api
    if($results['data']['status'] == 'ERROR'){
        $error_message = $results['data']['message'];
    }else if($results['data']['status'] == 'SUCCESS'){
        $success_message = $results['data']['message'];
    }

    $produto = api_request('get_product', 'POST', ['id' => $id_produto])['data']['results'][0];
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Consumidora - Editar Produto</title>
    <link rel="Stylesheet" href="assets/bootstrap/bootstrap.min.css">
</head>
<body>
    <?php include("inc/nav.php"); ?>

    <section class = "container">
        <div class="row my-5">
            <div class="col-sm-6 offset-sm-3 p-4 card bg-light">

            <form action="produtos_edit.php" method="post">

            <input type="hidden" name="id_produto" value="<?=$produto['id_produto']?>">

                <div class="mb-3">
                    <label>Nome do produto: </label>
                    <input type="text" name = "text_produto" class = "form-control" value="<?=$produto['produto']?>">
                </div>
    
                <div class="mb-3">
                    <label>Quantidade: </label>
                    <input type="number" name = "text_quantidade" class = "form-control" value="<?=$produto['quantidade']?>">
                </div>
    
                <div class="mb-3 text-center">
                    <a href="produtos.php"  class = "btn btn-secondary btn-sm">Cancelar</a>
                    <input type="submit" value = "Atualizar" class = "btn btn-primary btn-sm">
                </div>

                <?php if(!empty($error_message)): ?>
                    <div class = "alert alert-danger p-2 text-center">
                        <?=$error_message?>
                    </div>
                <?php elseif(!empty($success_message)): ?>
                    <div class = "alert alert-success p-2 text-center">
                        <?=$success_message?>
                    </div>
                <?php endif; ?>


            </form>


            </div>
        </div>
    </section>

<script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>