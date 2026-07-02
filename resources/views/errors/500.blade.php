{{-- resources/views/errors/500.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 500 - Akunkeun</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #004aad, #ffd700);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
        }
        .container {
            text-align: center;
            padding: 20px;
        }
        .logo {
            width: 150px;
            margin-bottom: 20px;
            animation: bounce 2s infinite;
        }
        .title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        .subtitle {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            color: #f0f0f0;
        }
        .illustration {
            width: 300px;
            height: 200px;
            margin: 20px auto;
            background: url('https://jad.lldikti4.id/images/logonew.webp') no-repeat center/contain, 
                        linear-gradient(135deg, #004aad, #ffd700);
            border-radius: 15px;
            overflow: hidden;
            animation: float 4s ease-in-out infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ asset('/assets/images/logo akunkeun.png') }}" alt="Akunkeun Logo" class="logo">
        <div class="title">500 - Kesalahan Server</div>
        <div class="subtitle">Maaf menggangu kenyamanan anda. Hubungi Tim kami dan berikan informasi ini.</div>
        <div class="illustration"></div>
    </div>
</body>
</html>
