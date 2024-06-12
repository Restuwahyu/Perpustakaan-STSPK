<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Perpustakaan Seminari Tinggi St. Paulus Kentungan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            width: 200px;
        }

        h1 {
            font-size: 20px;
            color: #333;
            text-align: center;
        }

        p {
            color: #666;
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        ul {
            margin-bottom: 20px;
        }

        a.btn-reset {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            margin: 0 auto;
            display: block;
            width: fit-content;
        }

        a.btn-reset:hover {
            background-color: #0056b3;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">
            {{-- <img src="https://pustaka.stpaulus.id/Zegva/Zegva_v1.0.0/Admin/vertical/assets/images/Logodark.png"> --}}
        </div>

        <h1>Reset Password <br> Perpustakaan Seminari Tinggi St. Paulus Kentungan</h1>

        <div class="card">
            <p>Halo Kak {{ $namaMember }},</p>

            <p>Kami menerima permintaan untuk mereset kata sandi untuk akun Anda di Perpustakaan Seminari Tinggi St.
                Paulus Kentungan. Silakan klik tautan di bawah ini untuk mereset kata sandi Anda:</p>

            <a href="{{ route('reset.verify', $verificationToken) }}" class="btn-reset">Reset Password</a>

            <p>Jika Anda tidak melakukan permintaan ini, Anda dapat mengabaikan email ini.</p>
        </div>
    </div>
</body>

</html>
