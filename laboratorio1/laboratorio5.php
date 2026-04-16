<html>
<head>
    <title>Formulario de entrada de dato</title>
</head>

<body>
    <form method="post" action="laboratorio5.php">
        Ingrese su nombre: <input type="text" name="nombre" id="nombre" required><br><br>
        Ingrese su Edad: <input type="text" name="edad" id="edad" required><br><br>
        <input type="submit" value="confirmar">
    </form>
</body>
</html>
<?php
$nombre = $_POST['nombre'];
if(is_numeric($nombre)){
    echo "El nombre no puede ser un número <br>";
}else{
echo "El nombre es: ".ucfirst($nombre)."<br>";
$edad = $_POST['edad'];
if (isset($edad) and $edad >=18){
    echo "Usted puede votar en las próximas elecciones 2028";
}else echo "Usted no es mayor de edad";}

?>