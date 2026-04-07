<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num1 = $_POST['num1'] ?? '';
    $num2 = $_POST['num2'] ?? '';
    $operator = $_POST['operator'] ?? '';

    if (!is_numeric($num1) || !is_numeric($num2)) {
        $error = 'Ingresa un numero valido.';
    } else {
        $num1 = (float)$num1;
        $num2 = (float)$num2;

        switch ($operator) {
            case '+':
                $result = $num1 + $num2;
                break;
            case '-':
                $result = $num1 - $num2;
                break;
            case '*':
                $result = $num1 * $num2;
                break;
            case '/':
                if ($num2 == 0) {
                    $error = 'No se puede dividir por cero.';
                } else {
                    $result = $num1 / $num2;
                }
                break;
            default:
                $error = 'Escoge una operación.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Calculator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: #f0f0f0;
        }
        .calculator {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-top: 0;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            background: #e9ecef;
            border-radius: 5px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
        }
        .success {
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="calculator">
        <h1>Calculadora sencilla</h1>
        
        <form method="POST" action="">
            <input type="number" name="num1" step="any" placeholder="Ingresa el primer numero" 
                   value="<?= isset($_POST['num1']) ? htmlspecialchars($_POST['num1']) : '' ?>" required>
            
            <select name="operator" required>
                <option value="">Selecciona una operacion</option>
                <option value="+" <?= isset($_POST['operator']) && $_POST['operator'] == '+' ? 'selected' : '' ?>>+ (Suma)</option>
                <option value="-" <?= isset($_POST['operator']) && $_POST['operator'] == '-' ? 'selected' : '' ?>>- (Resta)</option>
                <option value="*" <?= isset($_POST['operator']) && $_POST['operator'] == '*' ? 'selected' : '' ?>>× (Multiplicacion)</option>
                <option value="/" <?= isset($_POST['operator']) && $_POST['operator'] == '/' ? 'selected' : '' ?>>÷ (División)</option>
            </select>
            
            <input type="number" name="num2" step="any" placeholder="Ingresa el segundo numero" 
                   value="<?= isset($_POST['num2']) ? htmlspecialchars($_POST['num2']) : '' ?>" required>
            
            <button type="submit">Resolver</button>
        </form>

        <?php if ($result !== null || $error !== null): ?>
            <div class="result <?= $error ? 'error' : 'success' ?>">
                <?php if ($error): ?>
                    <?= htmlspecialchars($error) ?>
                <?php else: ?>
                    Result: <?= htmlspecialchars($result) ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>