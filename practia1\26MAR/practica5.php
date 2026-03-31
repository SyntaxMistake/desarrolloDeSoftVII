<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practica 5</title>
</head>
<body>
    <h1>Practica 5</h1>
    <?php
    $moneda = [ "España" => "Euro",
                "Reino Unido" => "Libra",
                "USA" => "Dolar"];
    foreach ($moneda as $clave => $valor) {
        echo "pais: $clave Moneda: $valor <br>";
    }
    ?>
</body>
</html>