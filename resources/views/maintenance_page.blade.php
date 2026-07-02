<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Akunkeun - Maintenance</title>
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
            .countdown {
                display: flex;
                justify-content: center;
                gap: 20px;
                margin-top: 20px;
            }
            .countdown div {
                background: rgba(255, 255, 255, 0.1);
                padding: 20px 25px;
                border-radius: 8px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
                color: #ffd700;
            }
            .countdown div span {
                display: block;
                font-size: 2.5rem;
                font-weight: 600;
            }
            .countdown div small {
                display: block;
                font-size: 1rem;
                font-weight: 400;
            }
            .illustration {
                position: relative;
                width: 300px;
                height: 200px;
                margin: 20px auto;
                background: url('https://jad.lldikti4.id/images/logonew.webp') no-repeat center/contain, 
                        linear-gradient(135deg, #004aad, #ffd700);
        
                border-radius: 15px;
                overflow: hidden;
                animation: float 4s ease-in-out infinite;
            }
            .illustration::before, .illustration::after {
                content: '';
                position: absolute;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                animation: move 6s linear infinite;
            }
            .illustration::before {
                width: 100px;
                height: 100px;
                top: -50px;
                left: 20px;
            }
            .illustration::after {
                width: 70px;
                height: 70px;
                bottom: -35px;
                right: 20px;
            }
            @keyframes bounce {
                0%, 100% {
                    transform: translateY(0);
                }
                50% {
                    transform: translateY(-10px);
                }
            }
            @keyframes float {
                0%, 100% {
                    transform: translateY(0);
                }
                50% {
                    transform: translateY(-15px);
                }
            }
            @keyframes move {
                0% {
                    transform: translateX(0);
                }
                50% {
                    transform: translateX(30px);
                }
                100% {
                    transform: translateX(0);
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <img src="{{ asset('/assets/images/logo akunkeun.png') }}" alt="Akunkeun Logo" class="logo">
            <div class="title">Akunkeun Sedang Dalam Proses Pemeliharaan</div>
            <div class="subtitle">Kami akan segera kembali! Terima kasih telah menunggu dan tetap bersama kami.</div>
            <div class="illustration"></div>
            <div class="countdown">
                <div>
                    <span id="hours">00</span>
                    <small>Jam</small>
                </div>
                <div>
                    <span id="minutes">00</span>
                    <small>Menit</small>
                </div>
                <div>
                    <span id="seconds">00</span>
                    <small>Detik</small>
                </div>
            </div>
        </div>
        <script>
            // Ambil waktu target dari .env
            const targetHour = {{ env('TARGET_TIME', 22) }}; // Default ke 22 jika tidak ada nilai di .env

            const targetTime = new Date();
            targetTime.setHours(targetHour, 0, 0, 0); // Set target time sesuai dengan jam dari .env


            function updateCountdown() {
                const now = new Date();
                const difference = targetTime - now;

                if (difference <= 0) {
                    document.querySelector('.subtitle').innerText = 'Pemeliharaan selesai! Selamat menggunakan Akunkeun!';
                    clearInterval(interval);
                    return;
                }

                const hours = Math.floor((difference / (1000 * 60 * 60)) % 24);
                const minutes = Math.floor((difference / (1000 * 60)) % 60);
                const seconds = Math.floor((difference / 1000) % 60);

                document.getElementById('hours').innerText = String(hours).padStart(2, '0');
                document.getElementById('minutes').innerText = String(minutes).padStart(2, '0');
                document.getElementById('seconds').innerText = String(seconds).padStart(2, '0');
            }

            const interval = setInterval(updateCountdown, 1000);
            updateCountdown(); // Initial call
        </script>
    </body>
    </html>   