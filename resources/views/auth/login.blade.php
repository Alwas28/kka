<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Akademik UM Kendari</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --maroon-dark: #800000;
            --maroon-main: #A52A2A;
            --maroon-light: #C41E3A;
            --maroon-lighter: #E8B4B8;
            --gray-dark: #1f2937;
            --gray-light: #f3f4f6;
            --gray-border: #e5e7eb;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --success: #10b981;
            --error: #ef4444;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--maroon-dark) 0%, var(--maroon-main) 100%);
            background-image: url('img/bg.png');
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            position: relative;
            overflow: auto;
            padding: 20px;
        }

        body::before {
            content: '';
            position: fixed;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            top: 0;
            left: 0;
            z-index: 0;
        }

        .login-container {
            display: flex;
            width: 100%;
            max-width: 1200px;
            max-height: 90vh;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            z-index: 1;
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* LEFT SIDE - INFO */
        .login-left {
            flex: 1;
            background: linear-gradient(135deg, var(--maroon-dark) 0%, var(--maroon-main) 100%);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(232, 180, 184, 0.1);
            border-radius: 50%;
            top: -50px;
            right: -50px;
        }

        .login-left::after {
            content: '';
            position: absolute;
            width: 150px;
            height: 150px;
            background: rgba(232, 180, 184, 0.08);
            border-radius: 50%;
            bottom: 20px;
            left: -30px;
        }

        .login-left-content {
            position: relative;
            z-index: 1;
        }

        .login-logo {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .login-left h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .login-left p {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .login-features {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .login-feature {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .login-feature-icon {
            width: 40px;
            height: 40px;
            background: rgba(232, 180, 184, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .login-feature-text h3 {
            font-size: 13px;
            font-weight: 700;
            margin: 0 0 3px 0;
        }

        .login-feature-text p {
            font-size: 12px;
            opacity: 0.8;
            margin: 0;
        }

        /* RIGHT SIDE - FORM */
        .login-right {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
            max-height: 90vh;
        }

        .login-right::-webkit-scrollbar {
            width: 6px;
        }

        .login-right::-webkit-scrollbar-track {
            background: #f3f4f6;
        }

        .login-right::-webkit-scrollbar-thumb {
            background: var(--maroon-main);
            border-radius: 3px;
        }

        .login-right::-webkit-scrollbar-thumb:hover {
            background: var(--maroon-dark);
        }

        .login-form-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .login-form-header p {
            font-size: 13px;
            color: var(--text-secondary);
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .form-group-wrapper {
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 12px 14px;
            padding-left: 40px;
            border: 1px solid var(--gray-border);
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--maroon-main);
            box-shadow: 0 0 0 3px rgba(165, 42, 42, 0.1);
            background-color: #fafafa;
        }

        .form-group-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 16px;
            pointer-events: none;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 16px;
            transition: all 0.2s ease;
        }

        .password-toggle:hover {
            color: var(--maroon-main);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 13px;
        }

        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }

        .form-checkbox input {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: var(--maroon-main);
        }

        .form-options a {
            color: var(--maroon-main);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .form-options a:hover {
            color: var(--maroon-dark);
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 12px 20px;
            background: linear-gradient(135deg, var(--maroon-light) 0%, var(--maroon-main) 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: left 0.3s ease;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(165, 42, 42, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-divider {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            color: var(--text-secondary);
            font-size: 12px;
        }

        .login-divider::before,
        .login-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--gray-border);
        }

        .social-login {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        .social-btn {
            flex: 1;
            padding: 10px;
            border: 1px solid var(--gray-border);
            background: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
            color: var(--text-secondary);
        }

        .social-btn:hover {
            border-color: var(--maroon-main);
            color: var(--maroon-main);
            background: rgba(165, 42, 42, 0.05);
        }

        .login-footer {
            text-align: center;
            font-size: 13px;
            color: var(--text-secondary);
        }

        .login-footer a {
            color: var(--maroon-main);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .login-footer a:hover {
            color: var(--maroon-dark);
            text-decoration: underline;
        }

        /* LOADING STATE */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-login.loading::after {
            content: '';
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ERROR STATE */
        .form-error {
            font-size: 12px;
            color: var(--error);
            margin-top: 5px;
            display: none;
        }

        .form-group.error input {
            border-color: var(--error);
        }

        .form-group.error .form-error {
            display: block;
        }

        /* SUCCESS MESSAGE */
        .success-message {
            padding: 12px 15px;
            background-color: rgba(16, 185, 129, 0.1);
            border-left: 4px solid var(--success);
            border-radius: 6px;
            color: #065f46;
            font-size: 13px;
            margin-bottom: 20px;
            display: none;
            animation: slideDown 0.3s ease;
        }

        .success-message.show {
            display: block;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .login-container {
                flex-direction: column;
                max-width: 100%;
                max-height: none;
                border-radius: 0;
            }

            .login-left {
                padding: 40px 30px;
                min-height: 250px;
            }

            .login-left h1 {
                font-size: 24px;
            }

            .login-right {
                padding: 40px 30px;
                max-height: none;
            }

            .login-form-header h2 {
                font-size: 22px;
            }

            .login-features {
                display: none;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 0;
            }

            .login-container {
                margin: 0;
                border-radius: 0;
                max-height: 100vh;
            }

            .login-left {
                padding: 30px 20px;
            }

            .login-right {
                padding: 30px 20px;
            }

            .login-left h1 {
                font-size: 20px;
            }

            .login-form-header h2 {
                font-size: 18px;
            }

            .social-login {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- LEFT SIDE - INFO -->
        <div class="login-left">
            <div class="login-left-content">
                <div class="login-logo">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h1>Selamat Datang</h1>
                <p>Sistem Informasi Kuliah Kerja Amaliah Universitas Muhammadiyah Kendari.</p>

                <div class="login-features">
                    <div class="login-feature">
                        <div class="login-feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="login-feature-text">
                            <h3>Aman & Terpercaya</h3>
                            <p>Enkripsi tingkat enterprise</p>
                        </div>
                    </div>

                    <div class="login-feature">
                        <div class="login-feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="login-feature-text">
                            <h3>Akses 24/7</h3>
                            <p>Kapan saja, di mana saja</p>
                        </div>
                    </div>

                    <div class="login-feature">
                        <div class="login-feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="login-feature-text">
                            <h3>Multi-Device</h3>
                            <p>Desktop, tablet, & mobile</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE - FORM -->
        <div class="login-right">
            <div class="login-form-header">
                <h2>Masuk</h2>
                <p>Gunakan kredensial Anda untuk melanjutkan</p>
            </div>

            <div class="success-message" id="successMessage">
                <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                Login berhasil! Mengalihkan...
            </div>

            <form method="POST" action="{{ route('login') }}">
            @csrf
            {{-- <form id="loginForm" onsubmit="handleLogin(event)"> --}}
                <div class="form-group">
                    <label for="email">Username/Email Anda</label>
                    <div class="form-group-wrapper">
                        <i class="fas fa-user form-group-icon"></i>
                        <input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Masukkan Email atau Username Anda" required>
                    </div>
                    <div class="form-error">Email atau NIM tidak valid</div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="form-group-wrapper">
                        <i class="fas fa-lock form-group-icon"></i>
                        <input placeholder="Masukkan Password Anda" type="password" name="password" required autocomplete="current-password" required>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="form-error">Password tidak boleh kosong</div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Masuk</span>
                </button>

                <div class="login-divider">Atau lanjutkan dengan</div>

                <div class="social-login">
                    <button type="button" class="social-btn" title="Google" onclick="handleSocialLogin('google')">
                        <i class="fab fa-google"></i>
                    </button>
                    <button type="button" class="social-btn" title="Microsoft" onclick="handleSocialLogin('microsoft')">
                        <i class="fab fa-microsoft"></i>
                    </button>
                    <button type="button" class="social-btn" title="GitHub" onclick="handleSocialLogin('github')">
                        <i class="fab fa-github"></i>
                    </button>
                </div>
            </form>

            <div class="login-footer">
                Belum punya akun? <a href="{{ route('mahasiswa.register') }}">Daftar di sini</a>
            </div>
        </div>
    </div>

    @include('components.toast')

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.querySelector('.password-toggle i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.classList.remove('fa-eye');
                toggleBtn.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleBtn.classList.remove('fa-eye-slash');
                toggleBtn.classList.add('fa-eye');
            }
        }

        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$|^\d{10,}$/;
            return emailRegex.test(email);
        }

        function handleLogin(event) {
            event.preventDefault();

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const emailGroup = document.getElementById('email').parentElement.parentElement;
            const passwordGroup = document.getElementById('password').parentElement.parentElement;
            const loginBtn = document.querySelector('.btn-login');

            // Reset errors
            emailGroup.classList.remove('error');
            passwordGroup.classList.remove('error');

            // Validation
            let isValid = true;

            if (!validateEmail(email)) {
                emailGroup.classList.add('error');
                isValid = false;
            }

            if (password.length < 6) {
                passwordGroup.classList.add('error');
                isValid = false;
            }

            if (!isValid) return;

            // Show loading state
            loginBtn.classList.add('loading');
            loginBtn.innerHTML = '<span>Memproses...</span>';
            loginBtn.disabled = true;

            // Simulate API call
            setTimeout(() => {
                // Show success message
                document.getElementById('successMessage').classList.add('show');

                // Redirect after 1.5 seconds
                setTimeout(() => {
                    window.location.href = 'index.html';
                }, 1500);
            }, 2000);
        }

        function handleSocialLogin(provider) {
            alert(`Login dengan ${provider} belum diimplementasikan`);
        }

        // Password field validation
        document.getElementById('password').addEventListener('input', function() {
            if (this.value.length >= 6) {
                this.parentElement.parentElement.classList.remove('error');
            }
        });

        // Email field validation
        document.getElementById('email').addEventListener('input', function() {
            if (validateEmail(this.value)) {
                this.parentElement.parentElement.classList.remove('error');
            }
        });

        // Demo login
        window.addEventListener('load', function() {
            document.getElementById('email').value = 'admin@umkendari.ac.id';
            document.getElementById('password').value = 'admin123';
        });
    </script>
</body>
</html>