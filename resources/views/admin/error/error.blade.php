<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>404 - ضايع؟</title>
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(#aee2ff, #e0f7ff);
            font-family: 'Amatic SC', cursive;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .cloud {
            position: absolute;
            width: 200px;
            height: 100px;
            background: white;
            border-radius: 100px;
            animation: floatClouds 60s linear infinite;
            opacity: 0.5;
        }

        .cloud::before,
        .cloud::after {
            content: '';
            position: absolute;
            background: white;
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }

        .cloud::before {
            top: -50px;
            left: 10px;
        }

        .cloud::after {
            top: -40px;
            right: 10px;
        }

        @keyframes floatClouds {
            from { transform: translateX(-300px); }
            to { transform: translateX(100vw); }
        }

        .container {
            text-align: center;
            z-index: 2;
        }

        h1 {
            font-size: 100px;
            margin: 0;
            color: #ff6f61;
        }

        p {
            font-size: 32px;
            margin-bottom: 20px;
        }

        img.character {
            width: 180px;
            margin-bottom: 30px;
        }

        a {
            font-size: 24px;
            background: #ff6f61;
            color: white;
            padding: 10px 25px;
            border-radius: 20px;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        a:hover {
            background: #e6514d;
        }
    </style>
</head>
<body>

    <!-- Clouds -->
    <div class="cloud" style="top: 50px; left: -200px;"></div>
    <div class="cloud" style="top: 150px; left: -500px;"></div>
    <div class="cloud" style="top: 100px; left: -300px;"></div>

    <div class="container">
        <img class="character" src="https://cdn-icons-png.flaticon.com/512/542/542638.png" alt="شخصية كرتونية تايهة">
        <h1>404</h1>
        <p>أوبس! شكلك دخلت طريق مش موجود في الخريطة 🗺️</p>
        <a href="{{ route('admin.showlogin') }}">يلا نرجع للبيت 🏡</a>
    </div>

</body>
</html>
