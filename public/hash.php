<?php
$password = $_POST['password'] ?? '';
$hash = '';
if ($password) {
    // Laravel uses BCRYPT for hashing passwords
    $hash = password_hash($password, PASSWORD_BCRYPT);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bcrypt Password Hasher</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #f4f7f6;
            padding: 40px; 
            display: flex;
            justify-content: center;
        }
        .container { 
            background: white;
            max-width: 500px; 
            width: 100%;
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h2 { margin-top: 0; color: #333; }
        input[type="text"] { 
            width: 100%; 
            padding: 12px; 
            margin: 10px 0 20px; 
            box-sizing: border-box; 
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button { 
            padding: 12px 20px; 
            background-color: #ff2d20; 
            color: white; 
            border: none; 
            border-radius: 5px;
            cursor: pointer; 
            font-size: 16px;
            width: 100%;
            font-weight: bold;
        }
        button:hover { background-color: #cc2419; }
        .result { 
            margin-top: 25px; 
            padding: 15px; 
            background-color: #e9ecef; 
            border-radius: 5px;
            word-wrap: break-word; 
            border-left: 4px solid #ff2d20;
        }
        .copy-text {
            display: block;
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Laravel Bcrypt Generator</h2>
        <p>Alat simpel untuk generate password acak (hash) secara manual.</p>
        <form method="POST">
            <label for="password">Masukkan Password Asli:</label>
            <input type="text" id="password" name="password" placeholder="Contoh: 123456" value="<?php echo htmlspecialchars($password); ?>" required>
            <button type="submit">Generate Hash</button>
        </form>

        <?php if ($hash): ?>
            <div class="result">
                <strong>Hasil Hash:</strong><br><br>
                <code style="font-size: 18px; color: #d63384;"><?php echo htmlspecialchars($hash); ?></code>
                <span class="copy-text">Kamu bisa copy kode merah di atas dan mem-paste nya langsung ke database.</span>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
