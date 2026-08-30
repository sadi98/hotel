<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Management')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background:
                radial-gradient(circle at top left,
                    rgba(14, 165, 183, .2),
                    transparent 35%),
                #eef5f8;
            color: #172033;
            font-family: Arial, sans-serif;
        }

        .management-auth {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        .management-auth-brand {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: clamp(35px, 6vw, 75px);
            background: linear-gradient(145deg,
                    #071827,
                    #075985,
                    #0ea5b7);
            color: #fff;
        }

        .management-auth-logo {
            font-size: 18px;
            font-weight: bold;
        }

        .management-auth-logo small {
            display: block;
            margin-top: 6px;
            color: #a5f3fc;
            font-size: 10px;
            letter-spacing: 1px;
        }

        .management-auth-brand h1 {
            max-width: 530px;
            margin: 0 0 18px;
            font-size: clamp(38px, 5vw, 65px);
            line-height: 1.05;
        }

        .management-auth-brand p {
            max-width: 450px;
            color: rgba(255, 255, 255, .7);
            line-height: 1.7;
        }

        .management-auth-content {
            display: grid;
            padding: 25px;
            place-items: center;
        }

        .management-auth-card {
            width: min(100%, 460px);
            padding: clamp(25px, 5vw, 45px);
            border: 1px solid #d7e6ec;
            border-radius: 24px;
            background: rgba(255, 255, 255, .95);
            box-shadow: 0 25px 65px rgba(7, 65, 96, .14);
        }

        .management-auth-icon {
            display: grid;
            width: 54px;
            height: 54px;
            margin-bottom: 18px;
            border-radius: 17px;
            background: #e7f7fa;
            color: #075985;
            font-size: 22px;
            place-items: center;
        }

        .management-auth-card h2 {
            margin: 0;
            font-size: 29px;
        }

        .management-auth-description {
            margin: 10px 0 24px;
            color: #6a7b8e;
            font-size: 13px;
            line-height: 1.7;
        }

        .management-auth-form {
            display: grid;
            gap: 17px;
        }

        .management-auth-field {
            display: grid;
            gap: 7px;
        }

        .management-auth-field label {
            font-size: 11px;
            font-weight: bold;
        }

        .management-auth-input-wrap {
            position: relative;
        }

        .management-auth-input-wrap>i {
            position: absolute;
            top: 50%;
            left: 14px;
            color: #748699;
            transform: translateY(-50%);
        }

        .management-auth-input {
            width: 100%;
            height: 50px;
            padding: 0 44px;
            border: 1px solid #d7e3e9;
            border-radius: 14px;
            outline: none;
            background: #f9fbfc;
        }

        .management-auth-input:focus {
            border-color: #0ea5b7;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(14, 165, 183, .12);
        }

        .management-auth-toggle {
            position: absolute;
            top: 50%;
            right: 12px;
            border: 0;
            background: transparent;
            cursor: pointer;
            transform: translateY(-50%);
        }

        .management-auth-error {
            color: #dc3545;
            font-size: 10px;
        }

        .management-auth-success {
            margin-bottom: 18px;
            padding: 12px;
            border: 1px solid #bce7d5;
            border-radius: 12px;
            background: #effbf6;
            color: #18794e;
            font-size: 11px;
        }

        .management-auth-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            font-size: 11px;
        }

        .management-auth-link {
            color: #075985;
            font-weight: bold;
            text-decoration: none;
        }

        .management-auth-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 50px;
            border: 0;
            border-radius: 14px;
            background: linear-gradient(135deg,
                    #075985,
                    #0ea5b7);
            color: #fff;
            font-weight: bold;
            cursor: pointer;
        }

        .management-auth-back {
            display: inline-block;
            margin-bottom: 22px;
            color: #075985;
            font-size: 11px;
            font-weight: bold;
            text-decoration: none;
        }

        @media (max-width: 850px) {
            .management-auth {
                display: block;
            }

            .management-auth-brand {
                padding: 24px;
            }

            .management-auth-brand-content,
            .management-auth-footer {
                display: none;
            }

            .management-auth-content {
                min-height: calc(100vh - 75px);
                padding: 18px;
            }
        }

        @media (max-width: 480px) {
            .management-auth-card {
                padding: 25px 20px;
                border-radius: 19px;
            }
        }
    </style>

    @stack('style')
</head>

<body>
    <main class="management-auth">
        <aside class="management-auth-brand">
            <div class="management-auth-logo">
                Kayu Manis Restaurant

                <small>AYAKA SUITES JAKARTA</small>
            </div>

            <div class="management-auth-brand-content">
                <h1>
                    Hospitality begins behind the scenes.
                </h1>

                <p>
                    Secure management access for authorized
                    administrators and restaurant staff.
                </p>
            </div>

            <div class="management-auth-footer">
                &copy; {{ date('Y') }} Ayaka Suites
            </div>
        </aside>

        <section class="management-auth-content">
            <div class="management-auth-card">
                @if (session('success'))
                    <div class="management-auth-success">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </section>
    </main>

    <script>
        document.addEventListener(
            'click',
            function(event) {
                var button = event.target.closest(
                    '[data-password-toggle]'
                );

                if (!button) {
                    return;
                }

                var input = document.getElementById(
                    button.dataset.passwordToggle
                );

                input.type = input.type === 'password' ?
                    'text' :
                    'password';
            }
        );
    </script>

    @stack('script')
</body>

</html>
