<form action="laboratorio1.php" method="post">
    <label for="radio">Radio de la circunferencia:</label>
    <input type="number" id="radio" name="radio" required>
    <br>
    <input type="submit" value="Enviar"> <br><br>
</form>

<?php
if ($_POST){
        $radio = $_POST['radio'];
        echo "El valor del radio es: $radio <br>";
        echo "El valor de area es: <br>";
        $area = pi() * pow($radio, 2);
        echo round($area,2);
        echo "<br> El valor de perimetro es: <br>";
        $perimetro = 2 * pi() * $radio;
        echo round($perimetro,2);
}
?>