<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HyperRack - Login Petugas</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f0f0f0; /* Warna background luar gelap sesuai gambar */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            background-color: #ffffff;
            width: 400px;
            padding: 60px 40px;
            text-align: center;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.5); /* Shadow halus di bagian bawah */
            border-radius: 4px; /* Sedikit rounded sesuai gambar */
        }

        .brand-name {
            font-size: 42px;
            font-weight: 800;
            color: #000000;
            margin-bottom: 5px;
            letter-spacing: -1px;
        }

        .sub-title {
            font-size: 24px;
            font-weight: 400;
            color: #333333;
            margin-bottom: 40px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #999999;
            border-radius: 5px;
            font-size: 14px;
            outline: none;
            color: #333;
        }

        .form-control::placeholder {
            color: #aaaaaa;
        }

        .btn-masuk {
            width: 100%;
            padding: 12px;
            background-color: #000000;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            font-weight: 400;
            cursor: pointer;
            margin-top: 10px;
            transition: opacity 0.3s ease;
        }

        .btn-masuk:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h1 class="brand-name">HyperRack</h1>
        <p class="sub-title">Login petugas</p>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <input type="text" name="nama" class="form-control" placeholder="Nama" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="kata sandi" required>
            </div>
            <button type="submit" class="btn-masuk">masuk</button>
        </form>
    </div>

</body>
</html>
