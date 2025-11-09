<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Loop</title>
    <link rel="icon" type="image/png" href="{{ asset('loop.png') }}">
    
    <style>
        :root {
            --bg-primary: #fafafa;
            --bg-secondary: #ffffff;
            --bg-tertiary: #f8f9fa;
            --text-primary: #1a1a1a;
            --text-secondary: #8e8e8e;
            --text-muted: #c7c7c7;
            --border-color: rgba(0, 0, 0, 0.08);
            --hover-bg: #f5f5f5;
            --shadow: rgba(0, 0, 0, 0.1);
            --accent-color: #0095f6;
            --accent-hover: #1877f2;
            --nav-bg: rgba(255, 255, 255, 0.95);
        }

        [data-theme="dark"] {
            --bg-primary: #000000;
            --bg-secondary: #1a1a1a;
            --bg-tertiary: #262626;
            --text-primary: #ffffff;
            --text-secondary: #a8a8a8;
            --text-muted: #737373;
            --border-color: rgba(255, 255, 255, 0.15);
            --hover-bg: #2a2a2a;
            --shadow: rgba(0, 0, 0, 0.3);
            --accent-color: #0095f6;
            --accent-hover: #1877f2;
            --nav-bg: rgba(26, 26, 26, 0.95);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            background: var(--bg-primary);
            min-height: 100vh;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Top Navigation */
        .top-nav {
            position: sticky;
            top: 0;
            background: var(--nav-bg);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            z-index: 1000;
            padding: 12px 0;
            transition: background-color 0.3s ease;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 300;
            color: var(--text-primary);
            text-decoration: none;
            letter-spacing: -0.5px;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-img {
            width: 28px;
            height: 28px;
            object-fit: contain;
            transition: transform 0.3s ease, filter 0.3s ease;
            filter: brightness(0) invert(0);
        }

        [data-theme="dark"] .logo-img {
            filter: brightness(0) invert(1);
        }

        .logo:hover .logo-img {
            transform: scale(1.05);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-btn {
            background: none;
            border: none;
            font-size: 20px;
            color: var(--text-primary);
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-btn:hover {
            background: var(--hover-bg);
        }

        .nav-btn.active {
            background: rgba(0, 149, 246, 0.2) !important;
            color: #0095f6 !important;
            border-radius: 8px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-avatar {
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 12px;
            cursor: pointer;
        }

        /* Main Content */
        .main-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 24px 20px;
        }

        .page-header {
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .back-btn {
            background: none;
            border: none;
            font-size: 24px;
            color: var(--text-primary);
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
        }

        .back-btn:hover {
            background: var(--hover-bg);
        }

        .page-info {
            flex: 1;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 16px;
        }

        /* Settings Sections */
        .settings-section {
            background: var(--bg-secondary);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            margin-bottom: 24px;
            overflow: hidden;
        }

        .section-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-tertiary);
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .section-description {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .section-content {
            padding: 24px;
        }

        /* Forms */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 15px;
            font-family: inherit;
            transition: border-color 0.2s ease;
            background: var(--bg-tertiary);
            color: var(--text-primary);
        }

        .form-input::placeholder {
            color: var(--text-secondary);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(0, 149, 246, 0.1);
        }

        .form-input.error {
            border-color: #dc3545;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 4px;
        }

        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        /* Buttons */
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--accent-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }

        /* Danger Zone */
        .danger-zone {
            border: 1px solid #dc3545;
            background: var(--bg-secondary);
        }

        .danger-zone .section-header {
            background: #dc3545;
            color: white;
            border-bottom-color: #c82333;
        }

        .danger-zone .section-title {
            color: white;
        }

        .danger-zone .section-description {
            color: rgba(255, 255, 255, 0.8);
        }

        .danger-warning {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: var(--text-primary);
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        /* SVG Icons */
        .icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            fill: currentColor;
            vertical-align: middle;
        }

        /* Custom Modal Styles */
        .custom-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 10000;
            animation: fadeIn 0.2s ease;
        }

        .custom-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: var(--bg-secondary);
            border-radius: 16px;
            padding: 24px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            transform: scale(0.9);
            animation: modalSlideIn 0.2s ease forwards;
        }

        .modal-header {
            margin-bottom: 16px;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .modal-message {
            font-size: 16px;
            color: var(--text-secondary);
            line-height: 1.4;
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
        }

        .modal-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .modal-btn-primary {
            background: #0095f6;
            color: white;
        }

        .modal-btn-primary:hover {
            background: #1877f2;
        }

        .modal-btn-danger {
            background: #dc3545;
            color: white;
        }

        .modal-btn-danger:hover {
            background: #c82333;
        }

        .modal-btn-secondary {
            background: var(--bg-tertiary);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .modal-btn-secondary:hover {
            background: var(--hover-bg);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes modalSlideIn {
            from { 
                transform: scale(0.9) translateY(-20px);
                opacity: 0;
            }
            to { 
                transform: scale(1) translateY(0);
                opacity: 1;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                padding: 16px 12px;
            }

            .nav-container {
                padding: 0 16px;
            }

            .section-content {
                padding: 20px 16px;
            }

            .page-title {
                font-size: 24px;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                justify-content: center;
            }
        }

        .theme-toggle {
            position: relative;
            color: black !important;
        }

        .theme-toggle .icon {
            color: black !important;
            fill: black !important;
        }

        .theme-toggle svg {
            fill: black !important;
        }

        .theme-toggle path {
            fill: black !important;
        }

        /* Gündüz temasında toggle butonunu siyah yap */
        [data-theme="light"] .theme-toggle {
            color: black !important;
        }

        [data-theme="light"] .theme-toggle svg {
            fill: black !important;
        }

        [data-theme="light"] .theme-toggle path {
            fill: black !important;
        }

        [data-theme="light"] .theme-toggle .icon {
            color: black !important;
            fill: black !important;
        }

        .theme-toggle .moon-icon {
            display: none;
        }

        [data-theme="dark"] .theme-toggle .sun-icon {
            display: none !important;
        }

        [data-theme="dark"] .theme-toggle .moon-icon {
            display: block !important;
        }

        /* Karanlık temada tema butonunun rengini beyaz yap */
        [data-theme="dark"] .theme-toggle {
            color: white !important;
        }

        [data-theme="dark"] .theme-toggle svg {
            fill: white !important;
        }

        [data-theme="dark"] .theme-toggle path {
            fill: white !important;
        }
    </style>
</head>
<body>
    <!-- Top Navigation -->
    <nav class="top-nav">
        <div class="nav-container">
            <a href="{{ url('/') }}" class="logo">
                <img src="{{ asset('loop.png') }}" alt="Loop Logo" class="logo-img">
                <span>Loop</span>
            </a>
            
            <div class="nav-actions">
                <a href="{{ route('home') }}" class="nav-btn" title="Ana Sayfa">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M12 2.1l9 8.1v11.8h-6v-6h-6v6H3V10.2L12 2.1z"/>
                    </svg>
                </a>
                <a href="{{ route('notifications.index') }}" class="nav-btn" title="Bildirimler">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                    </svg>
                </a>
                <a href="{{ route('messages.index') }}" class="nav-btn" title="Mesajlar">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                </a>
                <button class="nav-btn theme-toggle" title="Tema Değiştir" onclick="toggleTheme()">
                    <svg class="icon theme-icon" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                        <path class="sun-icon" d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58c-.39-.39-1.03-.39-1.41 0-.39.39-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0s.39-1.03 0-1.41L5.99 4.58zm12.37 12.37c-.39-.39-1.03-.39-1.41 0-.39.39-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0 .39-.39.39-1.03 0-1.41l-1.06-1.06zm1.06-10.96c.39-.39.39-1.03 0-1.41-.39-.39-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06zM7.05 18.36c.39-.39.39-1.03 0-1.41-.39-.39-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06z"/>
                        <path class="moon-icon" style="display: none;" d="M9 2c-1.05 0-2.05.16-3 .46 4.06 1.27 7 5.06 7 9.54 0 4.48-2.94 8.27-7 9.54.95.3 1.95.46 3 .46 5.52 0 10-4.48 10-10S14.52 2 9 2z"/>
                    </svg>
                </button>
                <a href="{{ route('settings.index') }}" class="nav-btn active" title="Ayarlar">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M19.14,12.94c0.04-0.3,0.06-0.61,0.06-0.94c0-0.32-0.02-0.64-0.07-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.61 l-1.92-3.32c-0.12-0.22-0.37-0.29-0.59-0.22l-2.39,0.96c-0.5-0.38-1.03-0.7-1.62-0.94L14.4,2.81c-0.04-0.24-0.24-0.41-0.48-0.41 h-3.84c-0.24,0-0.43,0.17-0.47,0.41L9.25,5.35C8.66,5.59,8.12,5.92,7.63,6.29L5.24,5.33c-0.22-0.08-0.47,0-0.59,0.22L2.74,8.87 C2.62,9.08,2.66,9.34,2.86,9.48l2.03,1.58C4.84,11.36,4.8,11.69,4.8,12s0.02,0.64,0.07,0.94l-2.03,1.58 c-0.18,0.14-0.23,0.41-0.12,0.61l1.92,3.32c0.12,0.22,0.37,0.29,0.59,0.22l2.39-0.96c0.5,0.38,1.03,0.7,1.62,0.94l0.36,2.54 c0.05,0.24,0.24,0.41,0.48,0.41h3.84c0.24,0,0.44-0.17,0.47-0.41l0.36-2.54c0.59-0.24,1.13-0.56,1.62-0.94l2.39,0.96 c0.22,0.08,0.47,0,0.59-0.22l1.92-3.32c0.12-0.22,0.07-0.47-0.12-0.61L19.14,12.94z M12,15.6c-1.98,0-3.6-1.62-3.6-3.6 s1.62-3.6,3.6-3.6s3.6,1.62,3.6,3.6S13.98,15.6,12,15.6z"/>
                    </svg>
                </a>
                <a href="{{ route('profile.show') }}" class="user-avatar" title="{{ Auth::user()->name }}">
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar_url }}" alt="Profil" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover;">
                    @else
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    @endif
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-container">
        <div class="page-header">
            <button class="back-btn" onclick="history.back()" title="Geri">
                <svg viewBox="0 0 24 24" style="width: 20px; height: 20px; fill: currentColor;">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
            </button>
            <div class="page-info">
                <h1 class="page-title">Ayarlar</h1>
                <p class="page-subtitle">Hesabınızı ve tercihlerinizi yönetin</p>
            </div>
        </div>

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <!-- Profile Settings -->
        <div class="settings-section">
            <div class="section-header">
                <h2 class="section-title">Profil Bilgileri</h2>
                <p class="section-description">Ad ve e-posta adresinizi güncelleyin</p>
            </div>
            <div class="section-content">
                <form method="POST" action="{{ route('settings.profile') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name" class="form-label">Ad Soyad</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                               value="{{ old('name', Auth::user()->name) }}" 
                               required>
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="username" class="form-label">Kullanıcı Adı</label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); font-size: 14px;">@</span>
                            <input type="text" 
                                   id="username" 
                                   name="username" 
                                   class="form-input {{ $errors->has('username') ? 'error' : '' }}"
                                   style="padding-left: 28px;"
                                   value="{{ old('username', Auth::user()->username) }}" 
                                   pattern="[a-zA-Z0-9_]+"
                                   title="Sadece harf, rakam ve alt çizgi kullanabilirsiniz"
                                   required>
                        </div>
                        <small style="color: var(--text-secondary); font-size: 12px; display: block; margin-top: 4px;">
                            Sadece harf, rakam ve alt çizgi (_) kullanabilirsiniz
                        </small>
                        @error('username')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">E-posta Adresi</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                               value="{{ old('email', Auth::user()->email) }}" 
                               required>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="bio" class="form-label">Biyografi</label>
                        <textarea id="bio" 
                                  name="bio" 
                                  class="form-input {{ $errors->has('bio') ? 'error' : '' }}"
                                  rows="4"
                                  placeholder="Kendiniz hakkında kısa bir açıklama yazın...">{{ old('bio', Auth::user()->bio) }}</textarea>
                        @error('bio')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <svg class="icon" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
                            </svg>
                            Profili Güncelle
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Password Settings -->
        <div class="settings-section">
            <div class="section-header">
                <h2 class="section-title">Şifre Değiştir</h2>
                <p class="section-description">Hesabınızın güvenliği için güçlü bir şifre kullanın</p>
            </div>
            <div class="section-content">
                <form method="POST" action="{{ route('settings.password') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="current_password" class="form-label">Mevcut Şifre</label>
                        <input type="password" 
                               id="current_password" 
                               name="current_password" 
                               class="form-input {{ $errors->has('current_password') ? 'error' : '' }}"
                               required>
                        @error('current_password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Yeni Şifre</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                               required>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Yeni Şifre (Tekrar)</label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="form-input"
                               required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <svg class="icon" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                <path d="M18,8h-1V6c0-2.76-2.24-5-5-5S7,3.24,7,6v2H6c-1.1,0-2,0.9-2,2v10c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V10 C20,8.9,19.1,8,18,8z M12,17c-1.1,0-2-0.9-2-2s0.9-2,2-2s2,0.9,2,2S13.1,17,12,17z M15.1,8H8.9V6c0-1.71,1.39-3.1,3.1-3.1 s3.1,1.39,3.1,3.1V8z"/>
                            </svg>
                            Şifreyi Güncelle
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="settings-section danger-zone">
            <div class="section-header">
                <h2 class="section-title">Tehlikeli Bölge</h2>
                <p class="section-description">Bu işlemler geri alınamaz</p>
            </div>
            <div class="section-content">
                <div class="danger-warning">
                    <strong>Uyarı:</strong> Hesabınızı sildiğinizde tüm verileriniz kalıcı olarak silinecektir. Bu işlem geri alınamaz.
                </div>

                <form method="POST" action="{{ route('settings.delete-account') }}" onsubmit="return confirmDelete(event)">
                    @csrf
                    @method('DELETE')
                    
                    <div class="form-group">
                        <label for="delete_password" class="form-label">Hesabı silmek için şifrenizi girin</label>
                        <input type="password" 
                               id="delete_password" 
                               name="password" 
                               class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                               placeholder="Şifrenizi girin..."
                               required>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-danger">
                            <svg class="icon" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                <path d="M6,19c0,1.1,0.9,2,2,2h8c1.1,0,2-0.9,2-2V7H6V19z M19,4h-3.5l-1-1h-5l-1,1H5v2h14V4z"/>
                            </svg>
                            Hesabı Kalıcı Olarak Sil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Custom Modal -->
    <div id="customModal" class="custom-modal">
        <div class="modal-content">
            <div class="modal-header">
                <div id="modalTitle" class="modal-title"></div>
                <div id="modalMessage" class="modal-message"></div>
            </div>
            <div class="modal-actions" id="modalActions"></div>
        </div>
    </div>

    <script>
        function confirmDelete(event) {
            event.preventDefault();
            showConfirm('Hesabınızı kalıcı olarak silmek istediğinizden emin misiniz? Bu işlem geri alınamaz ve tüm verileriniz silinecektir.', 'Hesabı Sil', () => {
                event.target.closest('form').submit();
            });
            return false;
        }
    </script>
    <script>
        // Theme management
        function initTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);
        }

        function toggleTheme() {
            const currentTheme = localStorage.getItem('theme') || 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            // Update theme icon
            updateThemeIcon(newTheme);
            
            // Sync theme across all tabs/pages
            window.dispatchEvent(new CustomEvent('themeChanged', { detail: newTheme }));
        }

        function updateThemeIcon(theme) {
            const sunIcon = document.querySelector('.sun-icon');
            const moonIcon = document.querySelector('.moon-icon');
            const themeToggle = document.querySelector('.theme-toggle');
            
            if (theme === 'dark') {
                // Dark mode: Ay göster
                if (moonIcon) moonIcon.style.display = 'block';
                if (sunIcon) sunIcon.style.display = 'none';
                if (themeToggle) {
                    themeToggle.style.color = 'white';
                    themeToggle.querySelector('svg').style.fill = 'white';
                }
            } else {
                // Light mode: Güneş göster
                if (sunIcon) sunIcon.style.display = 'block';
                if (moonIcon) moonIcon.style.display = 'none';
                if (themeToggle) {
                    themeToggle.style.color = 'black';
                    themeToggle.querySelector('svg').style.fill = 'black';
                }
            }
        }

        // Listen for theme changes from other tabs/pages
        window.addEventListener('storage', function(e) {
            if (e.key === 'theme') {
                document.documentElement.setAttribute('data-theme', e.newValue);
            }
        });

        // Listen for theme changes from same tab
        window.addEventListener('themeChanged', function(e) {
            document.documentElement.setAttribute('data-theme', e.detail);
        });

        // Initialize theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            initTheme();
        });

        // Custom Modal Functions
        function showModal(title, message, buttons = []) {
            const modal = document.getElementById('customModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const modalActions = document.getElementById('modalActions');
            
            modalTitle.textContent = title;
            modalMessage.textContent = message;
            modalActions.innerHTML = '';
            
            buttons.forEach(button => {
                const btn = document.createElement('button');
                btn.className = `modal-btn ${button.class || 'modal-btn-secondary'}`;
                btn.textContent = button.text;
                btn.onclick = () => {
                    hideModal();
                    if (button.action) button.action();
                };
                modalActions.appendChild(btn);
            });
            
            modal.classList.add('show');
            
            // Close on backdrop click
            modal.onclick = (e) => {
                if (e.target === modal) hideModal();
            };
        }

        function hideModal() {
            const modal = document.getElementById('customModal');
            modal.classList.remove('show');
        }

        function showConfirm(message, title = 'Onay', onConfirm = null) {
            showModal(title, message, [
                { text: 'İptal', class: 'modal-btn-secondary' },
                { text: 'Onayla', class: 'modal-btn-danger', action: onConfirm }
            ]);
        }
    </script>

</body>
</html>
