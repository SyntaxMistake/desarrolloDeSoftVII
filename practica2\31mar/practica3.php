<?php
$xml = simple_load_file("ejemplo3.xml");
foreach ($xml->color as $color) {
    echo "El color es " . $color->nombre ." tiene el código hexadecimal " . $color->codigo_hexadecimal . "<br>";
}
?>