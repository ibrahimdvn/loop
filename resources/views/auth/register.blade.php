<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kayıt Ol - Loop</title>
    <link rel="icon" type="image/png" href="{{ asset('loop.png') }}">
    
    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #6b6b6b;
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .auth-container {
            width: 100%;
            max-width: 450px;
            margin: 20px;
        }

        .auth-card {
            background: white;
            border-radius: 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .auth-header {
            background: white;
            color: #333;
            padding: 40px 30px 20px;
            text-align: center;
            border-bottom: 1px solid #f0f0f0;
        }

        .auth-header h1 {
            font-size: 24px;
            font-weight: 300;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }

        .auth-header p {
            color: #6b6b6b;
            font-size: 14px;
            font-weight: 400;
        }

        .auth-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 400;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-input {
            width: 100%;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 0;
            font-size: 14px;
            background: white;
            color: #333;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #333;
        }

        .form-input.error {
            border-color: #dc3545;
        }

        .password-strength {
            margin-top: 5px;
            font-size: 12px;
        }

        .strength-weak { color: #dc3545; }
        .strength-medium { color: #ffc107; }
        .strength-strong { color: #28a745; }

        .submit-btn {
            width: 100%;
            background: #333;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 0;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .submit-btn:hover {
            background: #555;
        }

        .submit-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .auth-links {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }

        .auth-links a {
            color: #6b6b6b;
            text-decoration: none;
            font-weight: 400;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .auth-links a:hover {
            color: #333;
        }

        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }

        .back-link {
            position: absolute;
            top: 20px;
            left: 20px;
            color: #6b6b6b;
            text-decoration: none;
            font-weight: 400;
            padding: 10px 15px;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .back-link:hover {
            color: #333;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        .password-match {
            font-size: 12px;
            margin-top: 5px;
        }

        .match-success { color: #28a745; }
        .match-error { color: #dc3545; }

        /* Animation Classes */
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-in-up.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .scale-in {
            opacity: 0;
            transform: scale(0.9);
            transition: all 0.5s ease;
        }

        .scale-in.animate {
            opacity: 1;
            transform: scale(1);
        }

        .slide-down {
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.4s ease;
        }

        .slide-down.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .stagger-1 { transition-delay: 0.1s; }
        .stagger-2 { transition-delay: 0.2s; }
        .stagger-3 { transition-delay: 0.3s; }
        .stagger-4 { transition-delay: 0.4s; }
        .stagger-5 { transition-delay: 0.5s; }

        /* Form Input Focus Animation */
        .form-input:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Button Hover Animation */
        .submit-btn {
            position: relative;
            overflow: hidden;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        /* Back Link Hover */
        .back-link {
            transform: translateX(0);
            transition: all 0.3s ease;
        }

        .back-link:hover {
            transform: translateX(5px);
        }

        /* Logo Styles */
        .auth-logo {
            text-align: center;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .auth-logo img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .auth-logo span {
            font-size: 28px;
            font-weight: 600;
            color: #333;
            letter-spacing: 0.5px;
        }

        .auth-logo:hover img {
            transform: scale(1.05) rotate(5deg);
        }

        @media (max-width: 480px) {
            .auth-container {
                margin: 10px;
            }
            
            .auth-header {
                padding: 25px 20px;
            }
            
            .auth-body {
                padding: 25px 20px;
            }
            
            .back-link {
                position: relative;
                top: auto;
                left: auto;
                display: inline-block;
                margin-bottom: 20px;
                background: rgba(255, 255, 255, 0.9);
                color: #333;
            }
        }
    </style>
</head>
<body>

    <a href="{{ url('/') }}" class="back-link slide-down">← Anasayfaya Dön</a>
    
    <div class="auth-container scale-in">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo scale-in">
                    <img src="{{ asset('loop.png') }}" alt="Loop Logo">
                    <span>Loop</span>
                </div>
                <h1 class="fade-in-up">Create Account</h1>
                <p class="fade-in-up stagger-1">Loop topluluğuna katılın</p>
            </div>
            
            <div class="auth-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                    <div class="form-group fade-in-up stagger-2">
                        <label for="name" class="form-label">Ad Soyad</label>
                        <input id="name" type="text" name="name" 
                               class="form-input @error('name') error @enderror" 
                               value="{{ old('name') }}" 
                               placeholder="Adınız ve soyadınız"
                               required autocomplete="name" autofocus>
                                @error('name')
                            <div class="error-message">{{ $message }}</div>
                                @enderror
                        </div>

                    <div class="form-group fade-in-up stagger-3">
                        <label for="email" class="form-label">E-posta Adresi</label>
                        <input id="email" type="email" name="email" 
                               class="form-input @error('email') error @enderror" 
                               value="{{ old('email') }}" 
                               placeholder="E-posta adresinizi girin"
                               required autocomplete="email">
                                @error('email')
                            <div class="error-message">{{ $message }}</div>
                                @enderror
                        </div>

                    <div class="form-group fade-in-up stagger-4">
                        <label for="password" class="form-label">Şifre</label>
                        <input id="password" type="password" name="password" 
                               class="form-input @error('password') error @enderror" 
                               placeholder="En az 8 karakter"
                               required autocomplete="new-password">
                        <div id="password-strength" class="password-strength"></div>
                                @error('password')
                            <div class="error-message">{{ $message }}</div>
                                @enderror
                        </div>

                    <div class="form-group fade-in-up stagger-5">
                        <label for="password-confirm" class="form-label">Şifre Tekrar</label>
                        <input id="password-confirm" type="password" name="password_confirmation" 
                               class="form-input" 
                               placeholder="Şifrenizi tekrar girin"
                               required autocomplete="new-password">
                        <div id="password-match" class="password-match"></div>
                        </div>

                    <button type="submit" class="submit-btn fade-in-up stagger-5" id="submit-btn">Kayıt Ol</button>
                    </form>

                <div class="auth-links">
                    <p>Zaten hesabınız var mı? <a href="{{ route('login') }}">Giriş yapın</a></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize animations on page load
        document.addEventListener('DOMContentLoaded', () => {
            // Trigger animations with delays
            setTimeout(() => {
                document.querySelectorAll('.slide-down').forEach(el => {
                    el.classList.add('animate');
                });
            }, 100);

            setTimeout(() => {
                document.querySelectorAll('.scale-in').forEach(el => {
                    el.classList.add('animate');
                });
            }, 200);

            setTimeout(() => {
                document.querySelectorAll('.fade-in-up').forEach(el => {
                    el.classList.add('animate');
                });
            }, 400);
        });

        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password-confirm');
        const passwordStrength = document.getElementById('password-strength');
        const passwordMatch = document.getElementById('password-match');
        const submitBtn = document.getElementById('submit-btn');

        // Password strength checker
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = checkPasswordStrength(password);
            
            passwordStrength.textContent = strength.text;
            passwordStrength.className = 'password-strength ' + strength.class;
            
            validateForm();
        });

        // Password match checker
        passwordConfirmInput.addEventListener('input', function() {
            const password = passwordInput.value;
            const confirmPassword = this.value;
            
            if (confirmPassword === '') {
                passwordMatch.textContent = '';
                passwordMatch.className = 'password-match';
            } else if (password === confirmPassword) {
                passwordMatch.textContent = '✓ Şifreler eşleşiyor';
                passwordMatch.className = 'password-match match-success';
            } else {
                passwordMatch.textContent = '✗ Şifreler eşleşmiyor';
                passwordMatch.className = 'password-match match-error';
            }
            
            validateForm();
        });

        function checkPasswordStrength(password) {
            if (password.length < 4) {
                return { text: 'Çok zayıf', class: 'strength-weak' };
            } else if (password.length < 8) {
                return { text: 'Zayıf', class: 'strength-weak' };
            } else if (password.length < 12 && /(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(password)) {
                return { text: 'Orta', class: 'strength-medium' };
            } else if (password.length >= 12 && /(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])/.test(password)) {
                return { text: 'Güçlü', class: 'strength-strong' };
            } else if (password.length >= 8) {
                return { text: 'Orta', class: 'strength-medium' };
            }
            return { text: '', class: '' };
        }

        function validateForm() {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = passwordInput.value;
            const confirmPassword = passwordConfirmInput.value;
            
            const isValid = name !== '' && 
                           email !== '' && 
                           password.length >= 8 && 
                           password === confirmPassword;
            
            submitBtn.disabled = !isValid;
        }

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = passwordInput.value;
            const confirmPassword = passwordConfirmInput.value;
            
            if (!name || !email || !password || !confirmPassword) {
                e.preventDefault();
                alert('Lütfen tüm alanları doldurun.');
                return;
            }
            
            if (password.length < 8) {
                e.preventDefault();
                alert('Şifre en az 8 karakter olmalıdır.');
                return;
            }
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Şifreler eşleşmiyor.');
                return;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Lütfen geçerli bir e-posta adresi girin.');
                return;
            }
        });

        // Remove error styling on input
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('error');
                const errorMsg = this.parentNode.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.style.display = 'none';
                }
                validateForm();
            });
        });

        // Initial validation
        validateForm();
    </script>
</body>
</html>