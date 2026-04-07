<?php
$result = null;
$error = null;
$expression = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num1 = $_POST['num1'] ?? '';
    $num2 = $_POST['num2'] ?? '';
    $operator = $_POST['operator'] ?? '';
    $expression = htmlspecialchars($num1) . ' ' . htmlspecialchars($operator) . ' ' . htmlspecialchars($num2);

    if (!is_numeric($num1) || !is_numeric($num2)) {
        $error = 'Please enter valid numbers.';
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
                    $error = 'Cannot divide by zero.';
                } else {
                    $result = $num1 / $num2;
                }
                break;
            default:
                $error = 'Please select an operation.';
        }
    }

    if ($result !== null && floor($result) == $result && abs($result) < 1e15) {
        $result = (int)$result;
    } elseif ($result !== null) {
        $result = round($result, 10);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CALC°</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@300;400;500&family=Syne:wght@400;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #0d0d0f;
            --surface: #16161a;
            --card: #1c1c21;
            --border: #2a2a32;
            --accent: #e8ff47;
            --accent-dim: rgba(232, 255, 71, 0.12);
            --accent-glow: rgba(232, 255, 71, 0.35);
            --text: #f0f0f2;
            --muted: #5a5a6e;
            --error: #ff5c5c;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Syne', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 60% 40% at 20% 10%, rgba(232,255,71,0.06) 0%, transparent 60%),
                radial-gradient(ellipse 50% 50% at 80% 90%, rgba(100,80,255,0.05) 0%, transparent 60%);
            pointer-events: none;
        }

        .calc-shell {
            width: 100%;
            max-width: 420px;
            animation: rise 0.6s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(32px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .calc-header {
            display: flex;
            align-items: baseline;
            gap: 8px;
            margin-bottom: 28px;
            padding-left: 4px;
        }

        .calc-header h1 {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.04em;
            color: var(--text);
        }

        .calc-header .dot { color: var(--accent); }

        .calc-header .tagline {
            font-family: 'DM Mono', monospace;
            font-size: 0.7rem;
            color: var(--muted);
            letter-spacing: 0.15em;
            text-transform: uppercase;
            margin-left: auto;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 28px;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(232,255,71,0.3), transparent);
        }

        .display {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 18px 20px;
            margin-bottom: 24px;
            min-height: 86px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            gap: 4px;
        }

        .display .expr {
            font-family: 'DM Mono', monospace;
            font-size: 0.75rem;
            color: var(--muted);
            letter-spacing: 0.05em;
            min-height: 16px;
        }

        .display .result-line {
            font-family: 'DM Mono', monospace;
            font-size: 2rem;
            font-weight: 500;
            color: var(--text);
            letter-spacing: -0.03em;
            transition: color 0.2s;
            word-break: break-all;
        }

        .display .result-line.has-result { color: var(--accent); }
        .display .result-line.has-error  { color: var(--error); font-size: 1rem; }
        .display .placeholder            { color: var(--muted); font-size: 1.4rem; }

        .inputs-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }

        .field { display: flex; flex-direction: column; gap: 6px; }

        .field label {
            font-family: 'DM Mono', monospace;
            font-size: 0.65rem;
            color: var(--muted);
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .field input[type="number"] {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-family: 'DM Mono', monospace;
            font-size: 1.1rem;
            padding: 12px 14px;
            width: 100%;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            -moz-appearance: textfield;
        }

        .field input[type="number"]::-webkit-outer-spin-button,
        .field input[type="number"]::-webkit-inner-spin-button { -webkit-appearance: none; }

        .field input[type="number"]:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-dim);
        }

        .op-label {
            font-family: 'DM Mono', monospace;
            font-size: 0.65rem;
            color: var(--muted);
            letter-spacing: 0.18em;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .op-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-bottom: 20px;
        }

        .op-btn {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--muted);
            font-family: 'Syne', sans-serif;
            font-size: 1.3rem;
            font-weight: 700;
            padding: 14px 0;
            cursor: pointer;
            transition: all 0.15s ease;
            position: relative;
            overflow: hidden;
        }

        .op-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-dim); }
        .op-btn:active { transform: scale(0.95); }

        .op-btn.selected {
            background: var(--accent);
            border-color: var(--accent);
            color: #0d0d0f;
            box-shadow: 0 0 18px var(--accent-glow);
        }

        .calc-btn {
            width: 100%;
            background: var(--accent);
            border: none;
            border-radius: 12px;
            color: #0d0d0f;
            font-family: 'Syne', sans-serif;
            font-size: 1rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 24px var(--accent-glow);
            position: relative;
            overflow: hidden;
        }

        .calc-btn::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 60%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
            transition: left 0.4s ease;
        }

        .calc-btn:hover::before { left: 150%; }
        .calc-btn:hover { box-shadow: 0 6px 32px var(--accent-glow); transform: translateY(-1px); }
        .calc-btn:active { transform: translateY(0) scale(0.98); }

        .calc-footer {
            text-align: center;
            margin-top: 20px;
            font-family: 'DM Mono', monospace;
            font-size: 0.65rem;
            color: var(--muted);
            letter-spacing: 0.12em;
        }

        @media (max-width: 400px) {
            .card { padding: 20px 16px; }
            .display .result-line { font-size: 1.5rem; }
        }
    </style>
</head>
<body>

<div class="calc-shell">
    <div class="calc-header">
        <h1>CALC<span class="dot">°</span></h1>
        <span class="tagline">Four ops, zero noise</span>
    </div>

    <div class="card">

        <div class="display">
            <div class="expr" id="liveExpr">
                <?php if ($expression): ?>
                    <?= $expression ?> =
                <?php else: ?>
                    &nbsp;
                <?php endif; ?>
            </div>
            <div class="result-line <?php
                if ($error) echo 'has-error';
                elseif ($result !== null) echo 'has-result';
            ?>" id="liveResult">
                <?php
                    if ($error) echo htmlspecialchars($error);
                    elseif ($result !== null) echo htmlspecialchars($result);
                    else echo '<span class="placeholder">—</span>';
                ?>
            </div>
        </div>

        <form method="POST" action="" id="calcForm">

            <div class="inputs-row">
                <div class="field">
                    <label>Number A</label>
                    <input type="number" name="num1" id="num1" step="any" placeholder="0"
                        value="<?= isset($_POST['num1']) ? htmlspecialchars($_POST['num1']) : '' ?>"
                        autocomplete="off" required>
                </div>
                <div class="field">
                    <label>Number B</label>
                    <input type="number" name="num2" id="num2" step="any" placeholder="0"
                        value="<?= isset($_POST['num2']) ? htmlspecialchars($_POST['num2']) : '' ?>"
                        autocomplete="off" required>
                </div>
            </div>

            <div class="op-label">Operation</div>
            <div class="op-grid">
                <?php
                    $ops = ['+' => '+', '-' => '−', '*' => '×', '/' => '÷'];
                    $selected = $_POST['operator'] ?? '';
                    foreach ($ops as $val => $sym):
                ?>
                <button type="button" class="op-btn <?= $selected === $val ? 'selected' : '' ?>" data-value="<?= $val ?>">
                    <?= $sym ?>
                </button>
                <?php endforeach; ?>
            </div>

            <input type="hidden" name="operator" id="operatorInput" value="<?= htmlspecialchars($selected) ?>">

            <button type="submit" class="calc-btn">Calculate</button>
        </form>

    </div>

    <div class="calc-footer">PHP · HTML · CSS &nbsp;|&nbsp; Addition · Subtraction · Multiplication · Division</div>
</div>

<script>
    const opBtns = document.querySelectorAll('.op-btn');
    const opInput = document.getElementById('operatorInput');
    const num1 = document.getElementById('num1');
    const num2 = document.getElementById('num2');
    const exprEl = document.getElementById('liveExpr');
    const resultEl = document.getElementById('liveResult');

    const opSymbols = { '+': '+', '-': '−', '*': '×', '/': '÷' };

    function updateDisplay() {
        const a = num1.value;
        const b = num2.value;
        const op = opInput.value;
        const sym = opSymbols[op] || '';
        if (a !== '' || b !== '' || op) {
            exprEl.textContent = (a !== '' ? a : '?') + (sym ? ' ' + sym + ' ' : ' ') + (b !== '' ? b : '?');
        } else {
            exprEl.innerHTML = '&nbsp;';
        }
    }

    opBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            opBtns.forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            opInput.value = btn.dataset.value;
            updateDisplay();
        });
    });

    num1.addEventListener('input', updateDisplay);
    num2.addEventListener('input', updateDisplay);
</script>

</body>
</html>
