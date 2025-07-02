<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 - Page Not Found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">

    {{-- Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    {{-- Feather Icons --}}
    <script src="https://unpkg.com/feather-icons"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #000;
            color: white;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .error-container {
            text-align: center;
            max-width: 600px;
        }

        .error-code {
            font-size: 160px;
            font-weight: 700;
            color: #8c3aff;
            display: flex;
            gap: 30px;
            justify-content: center;
            align-items: center;
        }

        .error-code span {
            color: white;
        }

        .error-message {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .error-subtext {
            font-size: 14px;
            color: #999;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .back-button {
            background-color: #8c3aff;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .back-button:hover {
            background-color: #722be0;
        }

        .back-button i {
            width: 16px;
            height: 16px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">
            4<span>0</span>4
        </div>
        <div class="error-message">Oops! Page not found.</div>
        <div class="error-subtext">
            We couldn’t find the page you’re looking for. It might have been<br>
            moved or doesn’t exist anymore.
        </div>
        <a href="{{ route('dashboard') }}" class="back-button">
            Back to Homepage <i data-feather="arrow-right"></i>
        </a>
    </div>

    <script>feather.replace();</script>
</body>
</html>
