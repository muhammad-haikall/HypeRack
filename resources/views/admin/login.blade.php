<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HyperRack - Login Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f0f0f0; 
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-card {
            background-color: #ffffff;
            width: 380px;
            padding: 60px 40px;
            text-align: center;
            border-radius: 2px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
        }

        .brand {
            font-size: 42px;
            font-weight: 800;
            color: #000000;
            margin-bottom: 0px;
            line-height: 1.2;
        }

        .title {
            font-size: 24px;
            font-weight: 400;
            color: #333333;
            margin-bottom: 40px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
        }

        .form-control::placeholder {
            color: #aaa;
        }

        .btn-masuk {
            width: 100%;
            padding: 12px;
            background-color: #000000;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            font-weight: 400;
            cursor: pointer;
            margin-top: 15px;
            transition: 0.3s;
            text-transform: lowercase; /* Sesuai desain tombol 'masuk' */
        }

        .btn-masuk:hover {
            background-color: #333;
        }

        /* Styling Notifikasi Error */
        .alert-error {
            background-color: #ffe6e6;
            color: #d93025;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 13px;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h1 class="brand">HyperRack</h1>
        <p class="title">Login Admin</p>

        @if(session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

      <form action="{{ route('admin.login.submit') }}" method="POST">
    @csrf
    
    <input type="text" name="nama" class="form-control" placeholder="Nama" value="{{ old('nama') }}" required>
    
    <input type="password" name="password" class="form-control" placeholder="Password" required>
    
    <button type="submit" class="btn-masuk">masuk</button>
</form>
    </div>

</body>
</html>





