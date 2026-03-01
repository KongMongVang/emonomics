<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Economics Dashboard</title>
    <link rel="icon" type="image/png" href="/emo-logo.png">
    <link href="/build/assets/app.css" rel="stylesheet">
    <style>
        body {
            font-family: 'ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', 'Liberation Mono', 'Courier New', monospace;
            background: #f8fafc;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            max-width: 400px;
            /* margin: 80px auto; */
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            padding: 2.5rem 2rem 2rem 2rem;
            text-align: center;
        }
        .logo {
            height: 64px;
            width: auto;
            margin-bottom: 2rem;
            object-fit: contain;
        }
        .btn-main {
            display: inline-block;
            background: #22223b;
            color: #fff;
            font-weight: 600;
            padding: 0.85rem 2.2rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 1.1rem;
            transition: background 0.2s;
            margin: 0 0.5rem;
        }
        .btn-main:hover {
            background: #4a4e69;
        }
        .btn-group {
            margin-top: 2.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="/emo-logo.png" alt="Economics Logo" class="logo">
        <div class="btn-group">
            <a href="/login" class="btn-main">Login</a>
            <a href="/register" class="btn-main">Register</a>
        </div>
    </div>
</body>
</html>