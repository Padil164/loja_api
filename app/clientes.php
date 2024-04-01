<?php

// dependeces
require_once('inc/config.php');
require_once('inc/api_functions.php');
require_once('inc/functions.php');

$results = api_request('get_all_clients', 'GET');

if($results['data']['status'] == 'SUCCESS'){
    $clientes = $results['data']['results'];
}else{
    $clientes = [];
}

// ['data']['results']
// printData($clientes);
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
            <div class="col">
                <div class="row">
                    <div class="col">
                        <h2>Clientes</h2>
                    </div>
                    <div class="col align-self-center text-end">
                    <a href="clientes_novo.php" class="btn btn-primary btn-sm">Adicionar cliente...</a>
                    </div>
                </div>
                <hr>
                <?php if(count($clientes) == 0): ?>
                    <p class = "text-center">Nao existem clientes registados...</p>
                <?php else: ?>
                    <table class = "table">
                        <thead class = "table-dark">
                            <tr>
                                <th>Nome do Cliente</th>
                                <th>Email</th>
                                <th>Contato</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach($clientes as $cliente): ?>
                                <tr>
                                    <td>
                                        <a href="clientes_edit.php?id=<?=$cliente['id_cliente']?>">&#9998;</a>
                                        <?=$cliente['nome']?></td>
                                    <td><?=$cliente['email']?></td>
                                    <td><?=$cliente['telefone']?></td>
                                    <td>
                                        <a href="clientes_delete.php?id=<?=$cliente['id_cliente']?>">&#128465;</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                    <p class = "text-end">Total: <strong><?=count($clientes)?></strong></p>
                <?php endif; ?>
            </div>
        </div>

    </section>
<script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>