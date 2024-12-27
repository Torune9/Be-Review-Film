<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        h2 {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            border-radius: 4px;
            display: inline-block;
            color: #000;
        }
        p {
            margin: 10px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h1>Halo, {{ $name }}</h1>
        <p>Terima kasih telah mendaftar di platform kami. Untuk memverifikasi akun Anda, silakan gunakan kode OTP berikut:</p>
        <h2>{{ $otp }}</h2>
        <p>Kode ini hanya berlaku selama 5 menit.</p>
        <p>Jika Anda tidak meminta kode ini, silakan abaikan email ini.</p>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Sanbercode</p>
        </div>
    </div>
</body>
</html>
