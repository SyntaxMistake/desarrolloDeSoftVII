<form action="laboratorio2.php" method="post">
    <label for="pulgadas">Valor en pulgadas:</label>
    <input type="number" id="pulgadas" name="pulgadas" required>
    <br>
    <input type="submit" value="Enviar"> <br><br>
</form>

<?php
if ($_POST) {
    $pulgadas = $_POST['pulgadas'];
    echo "El valor ingresado en pulgadas es: $pulgadas <br>";
    echo "El valor equivalente en centímetros es: <br>";
    $cm = $pulgadas * 2.54;
    echo round($cm, 2) . " cm";
}
?>