<?php

// dependeces
require_once('inc/config.php');
require_once('inc/api_functions.php');
require_once('inc/functions.php');

// logica e regras de negocio

$error_message = '';
$success_message = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nome = $_POST['text_nome'];
    $email = $_POST['text_email'];
    $telefone = $_POST['text_telefone'];

    $results = api_request('create_new_client', 'POST', [
        'nome' => $nome,
        'email' => $email,
        'telefone' => $telefone
    ]);

    // dsiplay the results of the operations on the api
    if($results['data']['status'] == 'ERROR'){
        $error_message = $results['data']['message'];
    }else if($results['data']['status'] == 'SUCCESS'){
        $success_message = $results['data']['message'];
    }


}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Consumidora</title>
    <link rel="Stylesheet" href="assets/bootstrap/bootstrap.min.css">
</head>
<body>
    <?php include("inc/nav.php"); ?>

    <section class = "container">
        <div class="row my-5">
            <div class="col-sm-6 offset-sm-3 p-4 card bg-light">

            <form action="clientes_novo.php" method="post">
                <div class="mb-3">
                    <label>Nome do cliente: </label>
                    <input type="text" name = "text_nome" class = "form-control">
                </div>
    
                <div class="mb-3">
                    <label>Telefone: </label>
                    <input type="number" name = "text_telefone" class = "form-control">
                </div>
    
                <div class="mb-3">
                    <label>Email: </label>
                    <input type="email" name = "text_email" class = "form-control">
                </div>
    
                <div class="mb-3 text-center">
                    <a href="clientes.php"  class = "btn btn-secondary btn-sm">Cancelar</a>
                    <input type="submit" value = "Guardar" class = "btn btn-primary btn-sm">
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