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
            max-width: 600px;
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
            background: #f5f5f5;
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

        .notifications-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 16px 20px;
            background: var(--bg-secondary);
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .notifications-count {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .notification-actions-buttons {
            display: flex;
            gap: 12px;
        }

        .action-btn {
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .mark-all-read {
            background: #0095f6;
            color: white;
        }

        .mark-all-read:hover {
            background: #1877f2;
        }

        .delete-all {
            background: #dc3545;
            color: white;
        }

        .delete-all:hover {
            background: #c82333;
        }

        .notifications-list {
            display: flex;
            flex-direction: column;
            gap: 1px;
            background: var(--bg-secondary);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .notification-item {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: flex-start;
            gap: 16px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item:hover {
            background: var(--hover-bg);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px var(--shadow);
        }

        /* Karanlık temada hover durumunda yazı rengini değiştir */
        [data-theme="dark"] .notification-item:hover .notification-message {
            color:rgb(226, 223, 223);
        }

        [data-theme="dark"] .notification-item:hover .notification-time {
            color: #777777;
        }

        .notification-item.unread {
            background: var(--bg-tertiary);
            border-left: 4px solid var(--accent-color);
        }

        /* Notification Badge */
        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ff3b5c;
            color: white;
            border-radius: 10px;
            padding: 2px 6px;
            font-size: 11px;
            font-weight: 600;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--nav-bg);
            animation: badgePulse 2s infinite;
        }

        @keyframes badgePulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .notification-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
            border: 2px solid var(--border-color);
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .notification-icon.like {
            background: #ffe6e6;
        }

        .notification-icon.comment {
            background: #e6f3ff;
        }

        .notification-icon.follow {
            background: #e6ffe6;
        }

        .notification-icon.post {
            background: #fff3e6;
        }

        .notification-content {
            flex: 1;
        }

        .notification-message {
            color: var(--text-primary);
            font-size: 15px;
            line-height: 1.4;
            margin-bottom: 4px;
        }

        .notification-time {
            color: var(--text-secondary);
            font-size: 13px;
        }

        .notification-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 12px;
            margin-right: 8px;
            flex-shrink: 0;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: var(--bg-secondary);
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.3;
        }

        .empty-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .empty-text {
            color: var(--text-secondary);
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

            .notifications-list {
                border-radius: 0;
                border-left: none;
                border-right: none;
            }

            .page-title {
                font-size: 24px;
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
                <a href="{{ route('notifications.index') }}" class="nav-btn active" title="Bildirimler">
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
                <a href="{{ route('settings.index') }}" class="nav-btn" title="Ayarlar">
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
                <h1 class="page-title">Bildirimler</h1>
                <p class="page-subtitle">Son aktivitelerinizi takip edin</p>
            </div>
        </div>

        @if($notifications->count() > 0)
            <div class="notifications-actions">
                <div class="notifications-count">
                    {{ $notifications->total() }} bildirim
                </div>
                <div class="notification-actions-buttons">
                    <button class="action-btn mark-all-read" onclick="markAllAsRead()">
                        Tümünü Okundu İşaretle
                    </button>
                    <button class="action-btn delete-all" onclick="deleteAllNotifications()">
                        Hepsini Sil
                    </button>
                </div>
            </div>

            <div class="notifications-list">
                @foreach($notifications as $notification)
                    <div class="notification-item {{ !$notification->read ? 'unread' : '' }}" 
                         onclick="markAsRead({{ $notification->id }})">
                        
                        @if($notification->notifiable)
                            <img src="{{ $notification->notifiable->avatar ? url('/media/' . $notification->notifiable->avatar) : asset('loop.png') }}" 
                                 alt="{{ $notification->notifiable->name }}" 
                                 class="notification-avatar">
                        @else
                        <div class="notification-icon {{ $notification->type }}">
                            @switch($notification->type)
                                @case('like')
                                    <svg class="icon" viewBox="0 0 24 24" style="color: var(--text-primary); fill: currentColor;">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                    @break
                                @case('comment')
                                    <svg class="icon" viewBox="0 0 24 24" style="color: var(--text-primary); fill: currentColor;">
                                        <path d="M20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h4l4 4 4-4h4c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
                                    </svg>
                                    @break
                                @case('follow')
                                    <svg class="icon" viewBox="0 0 24 24" style="color: var(--text-primary); fill: currentColor;">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                    </svg>
                                    @break
                                @case('post')
                                    <svg class="icon" viewBox="0 0 24 24" style="color: var(--text-primary); fill: currentColor;">
                                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                    </svg>
                                    @break
                                @default
                                    <svg class="icon" viewBox="0 0 24 24" style="color: var(--text-primary); fill: currentColor;">
                                        <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                                    </svg>
                            @endswitch
                        </div>
                        @endif

                        <div class="notification-content">
                            <div class="notification-message">
                                @if($notification->fromUser)
                                    <strong>{{ $notification->fromUser->name }}</strong>
                                @endif
                                {{ $notification->message }}
                            </div>
                            <div class="notification-time">
                                {{ $notification->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($notifications->hasPages())
                <div style="margin-top: 20px; display: flex; justify-content: center;">
                    {{ $notifications->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <svg viewBox="0 0 24 24" style="width: 48px; height: 48px; color: var(--text-primary); fill: currentColor; opacity: 0.5;">
                        <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                    </svg>
                </div>
                <h3 class="empty-title">Henüz bildirim yok</h3>
                <p class="empty-text">Aktiviteleriniz burada görünecek</p>
            </div>
        @endif
    </div>

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

        function markAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Bildirim öğesini okundu olarak işaretle
                    const item = document.querySelector(`[onclick="markAsRead(${notificationId})"]`);
                    if (item) {
                        item.classList.remove('unread');
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function markAllAsRead() {
            fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Tüm bildirimleri okundu olarak işaretle
                    document.querySelectorAll('.notification-item.unread').forEach(item => {
                        item.classList.remove('unread');
                    });
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function deleteAllNotifications() {
            showConfirm('Tüm bildirimleri silmek istediğinizden emin misiniz?', 'Bildirimleri Sil', () => {
                fetch('/notifications/delete-all', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Sayfayı yenile
                        window.location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }

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

</body>
</html>
