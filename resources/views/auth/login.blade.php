<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Giriş Yap - Loop</title>
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
            max-width: 400px;
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

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 25px;
        }

        .checkbox {
            width: 18px;
            height: 18px;
        }

        .checkbox-label {
            font-size: 14px;
            color: #666;
            cursor: pointer;
        }

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

        /* Form Input Focus Animation */
        .form-input {
            position: relative;
        }

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
                <h1 class="fade-in-up">Hoş Geldiniz!</h1>
                <p class="fade-in-up stagger-1">Hesabınıza giriş yapın</p>
            </div>
            
            <div class="auth-body">
                @if(session('success'))
                    <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                        {{ session('success') }}
                    </div>
                @endif
                
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                    <div class="form-group fade-in-up stagger-2">
                        <label for="email" class="form-label">E-posta Adresi</label>
                        <input id="email" type="email" name="email" 
                               class="form-input @error('email') error @enderror" 
                               value="{{ old('email') }}" 
                               placeholder="E-posta adresinizi girin"
                               required autocomplete="email" autofocus>
                                @error('email')
                            <div class="error-message">{{ $message }}</div>
                                @enderror
                        </div>

                    <div class="form-group fade-in-up stagger-3">
                        <label for="password" class="form-label">Şifre</label>
                        <input id="password" type="password" name="password" 
                               class="form-input @error('password') error @enderror" 
                               placeholder="Şifrenizi girin"
                               required autocomplete="current-password">
                                @error('password')
                            <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                    <div class="checkbox-group fade-in-up stagger-4">
                        <input type="checkbox" id="remember" name="remember" class="checkbox" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" class="checkbox-label">Beni hatırla</label>
                        </div>

                    <button type="submit" class="submit-btn fade-in-up stagger-4">Giriş Yap</button>
                </form>

                <div class="auth-links">
                    @if (Route::has('register'))
                        <p>Hesabınız yok mu? <a href="{{ route('register') }}">Kayıt olun</a></p>
                    @endif

                                @if (Route::has('password.request'))
                        <p style="margin-top: 10px;">
                            <a href="{{ route('password.request') }}">Şifrenizi mi unuttunuz?</a>
                        </p>
                                @endif
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

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            
            if (!email.value.trim() || !password.value.trim()) {
                e.preventDefault();
                alert('Lütfen tüm alanları doldurun.');
                return;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
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
            });
        });
    </script>
</body>
</html>