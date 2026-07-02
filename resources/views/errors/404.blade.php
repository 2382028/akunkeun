<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan - Akunkeun</title>
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
            overflow: hidden;
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
            margin: 0 auto 30px auto;
            width: 350px;
            max-width: 90%;
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
        .btn-home {
            background-color: #ffffff;
            color: #004aad;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
        }
        .btn-home:hover {
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ asset('/assets/images/logo akunkeun.png') }}" alt="Akunkeun Logo" class="logo">
        <div class="title">404 - Halaman Tidak Ditemukan</div>
        <div class="subtitle">Sepertinya alamat yang Anda tuju tidak tersedia atau telah dipindahkan.</div>
        <a href="{{ url('/') }}" class="btn-home">Kembali ke Beranda</a>
    </div>
</body>
</html>
