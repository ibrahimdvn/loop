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

        .messages-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 16px 20px;
            background: var(--bg-secondary);
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .messages-count {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .new-message-btn {
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
        }

        .new-message-btn:hover {
            background: var(--accent-hover);
        }

        .conversations-list {
            display: flex;
            flex-direction: column;
            gap: 1px;
            background: var(--bg-secondary);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .conversation-item {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .conversation-item:last-child {
            border-bottom: none;
        }

        .conversation-item:hover {
            background: var(--bg-tertiary);
        }

        .conversation-avatar {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
            flex-shrink: 0;
        }

        .conversation-content {
            flex: 1;
            min-width: 0;
        }

        .conversation-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 4px;
        }

        .conversation-name {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 15px;
        }

        .conversation-time {
            color: var(--text-secondary);
            font-size: 12px;
            flex-shrink: 0;
            margin-left: 8px;
        }

        .conversation-preview {
            color: var(--text-secondary);
            font-size: 14px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .conversation-item.unread .conversation-name {
            color: var(--accent-color);
        }

        .conversation-item.unread .conversation-preview {
            color: var(--text-primary);
            font-weight: 500;
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
            margin-bottom: 20px;
        }

        /* SVG Icons */
        .icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            fill: currentColor;
            vertical-align: middle;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                padding: 16px 12px;
            }

            .nav-container {
                padding: 0 16px;
            }

            .conversations-list {
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
                <a href="{{ route('notifications.index') }}" class="nav-btn" title="Bildirimler">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                    </svg>
                </a>
                <a href="{{ route('messages.index') }}" class="nav-btn active" title="Mesajlar">
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
                <h1 class="page-title">Mesajlar</h1>
                <p class="page-subtitle">Konuşmalarınızı yönetin</p>
            </div>
        </div>

        @if($conversations->count() > 0)
            <div class="messages-actions">
                <div class="messages-count">
                    {{ $conversations->count() }} konuşma
                </div>
                <a href="{{ route('messages.users') }}" class="new-message-btn">
                    Yeni Mesaj
                </a>
            </div>

            <div class="conversations-list">
                @foreach($conversations as $conversation)
                    @php
                        $otherUserId = $conversation->sender_id == Auth::id() 
                            ? $conversation->receiver_id 
                            : $conversation->sender_id;
                        $otherUser = \App\Models\User::find($otherUserId);
                    @endphp
                    
                    <a href="{{ route('messages.show', $otherUserId) }}" class="conversation-item {{ !$conversation->read && $conversation->receiver_id == Auth::id() ? 'unread' : '' }}">
                        <div class="conversation-avatar">
                            {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                        </div>
                        
                        <div class="conversation-content">
                            <div class="conversation-header">
                                <div class="conversation-name">{{ $otherUser->name }}</div>
                                <div class="conversation-time">{{ \Carbon\Carbon::parse($conversation->created_at)->diffForHumans() }}</div>
                            </div>
                            <div class="conversation-preview">
                                {{ Str::limit($conversation->message, 50) }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <svg viewBox="0 0 24 24" style="width: 48px; height: 48px; color: var(--text-primary); fill: currentColor; opacity: 0.5;">
                        <path d="M20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h4l4 4 4-4h4c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
                    </svg>
                </div>
                <h3 class="empty-title">Henüz mesajınız yok</h3>
                <p class="empty-text">Yeni bir konuşma başlatın</p>
                <a href="{{ route('messages.users') }}" class="new-message-btn">
                    Yeni Mesaj Gönder
                </a>
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
    </script>

</body>
</html>
