<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Kode Verifikasi OTP</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: white;
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .logo-img {
            width: 48px;
            height: 48px;
            margin-right: 12px;
            object-fit: contain;
        }

        .logo-text {
            color: #10b981;
            font-size: 24px;
            font-weight: bold;
        }

        .agriconnect-primary {
            background: linear-gradient(135deg, #10b981, #0d9488);
        }

        .agriconnect-secondary {
            background: linear-gradient(135deg, #059669, #0f766e);
        }

        .text-agriconnect-primary {
            color: #10b981;
        }

        .bg-agriconnect-pattern {
            background-image:
                radial-gradient(circle at 25% 25%, rgba(16, 185, 129, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(5, 150, 105, 0.1) 0%, transparent 50%);
        }

        .otp-code {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(13, 148, 136, 0.1));
            border: 2px dashed #10b981;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            border-radius: 8px;
            position: relative;
        }

        .otp-code::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 25% 25%, rgba(16, 185, 129, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(5, 150, 105, 0.05) 0%, transparent 50%);
            border-radius: 6px;
            z-index: -1;
        }

        .otp-number {
            font-size: 32px;
            font-weight: bold;
            background: linear-gradient(135deg, #10b981, #0d9488);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 12px;
        }

        /* Responsive untuk mobile */
        @media only screen and (max-width: 600px) {
            .container {
                padding: 20px;
            }

            .logo-img {
                width: 40px;
                height: 40px;
            }

            .logo-text {
                font-size: 20px;
            }

            .otp-number {
                font-size: 28px;
                letter-spacing: 3px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo-container">
                <img src="https://agriconnect.click/images/logo.jpg" alt="AgriConnect Logo" class="logo-img">
                <div class="logo-text">AgriConnect</div>
            </div>
        </div>

        <h2>Halo {{ $userName }}!</h2>

        <p>Terima kasih telah mendaftar di AgriConnect. Untuk melengkapi proses pendaftaran, silakan masukkan kode OTP
            berikut:</p>

        <div class="otp-code">
            <div class="otp-number">{{ $otp }}</div>
        </div>

        <p><strong>Catatan penting:</strong></p>
        <ul>
            <li>Kode OTP ini berlaku selama <strong>10 menit</strong></li>
            <li>Jangan bagikan kode ini kepada siapa pun</li>
            <li>Jika Anda tidak merasa mendaftar, abaikan email ini</li>
        </ul>

        <p>Selamat bergabung dengan komunitas AgriConnect!</p>

        <div class="footer">
            <p>Email ini dikirim otomatis, mohon tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} AgriConnect. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
