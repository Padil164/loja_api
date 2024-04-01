<?php

// dependeces
require_once('inc/config.php');
require_once('inc/api_functions.php');

$results = api_request('get_all_products', 'GET');

if($results['data']['status'] == 'SUCCESS'){
    $produtos = $results['data']['results'];
}else{
    $produtos = [];
}
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
            <div class="col">
            <div class="row">
                    <div class="col">
                        <h2>Produtos</h2>
                    </div>
                    <div class="col align-self-center text-end">
                    <a href="produtos_novo.php" class="btn btn-primary btn-sm">Adicionar produto...</a>
                    </div>
                </div>
                <hr>
                <?php if(count($produtos) == 0): ?>
                    <p class = "text-center">Nao existem produtos registados</p>
                <?php else: ?>

                    <table class = "table">
                        <thead class = "table-dark">
                            <tr>
                                <th>Produto</th>
                                <th>Quantiadde</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($produtos as $produto): ?>
                                <tr>
                                    <td>
                                    <a href="produtos_edit.php?id=<?=$produto['id_produto']?>">&#9998;</a>  
                                    <?=$produto['produto']?></td>
                                    <td><?=$produto['quantidade']?></td>
                                    <td>
                                    <a href="produtos_delete.php?id=<?=$produto['id_produto']?>">&#128465;</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <p class = "text-end">Total: <strong><?=count($produtos)?></strong></p>

                <?php endif; ?>
            </div>
        </div>

    </section>
<script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>