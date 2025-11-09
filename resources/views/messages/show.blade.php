<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $otherUser->name }} ile Mesajlaşma - Loop</title>
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
            color: #1a1a1a;
            background: #fafafa;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Top Navigation */
        .top-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            z-index: 1000;
            padding: 12px 0;
            flex-shrink: 0;
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
            color: #262626;
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
            background: #f5f5f5;
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

        /* Chat Container */
        .chat-container {
            max-width: 800px;
            margin: 0 auto;
            height: calc(100vh - 80px);
            display: flex;
            flex-direction: column;
            background: white;
            border-left: 1px solid #dbdbdb;
            border-right: 1px solid #dbdbdb;
        }

        /* Chat Header */
        .chat-header {
            padding: 20px 24px;
            border-bottom: 1px solid #dbdbdb;
            display: flex;
            align-items: center;
            gap: 16px;
            background: white;
            flex-shrink: 0;
        }

        .back-btn {
            background: none;
            border: none;
            font-size: 24px;
            color: #262626;
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

        .chat-user-info {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chat-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .chat-user-name {
            font-weight: 600;
            color: #262626;
            font-size: 16px;
        }

        /* Messages Area */
        .messages-area {
            flex: 1;
            overflow-y: auto;
            padding: 20px 24px;
            background: #f8f9fa;
        }

        .message {
            margin-bottom: 16px;
            display: flex;
            align-items: flex-end;
            gap: 8px;
        }

        .message.sent {
            flex-direction: row-reverse;
        }

        .message-avatar {
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
            flex-shrink: 0;
        }

        .message-content {
            max-width: 70%;
            background: white;
            padding: 12px 16px;
            border-radius: 18px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .message.sent .message-content {
            background: #0095f6;
            color: white;
        }

        .message-text {
            font-size: 15px;
            line-height: 1.4;
            word-wrap: break-word;
        }

        .message-time {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 4px;
        }

        .message:not(.sent) .message-time {
            color: #8e8e8e;
        }

        /* Message Input */
        .message-input-area {
            padding: 20px 24px;
            background: white;
            border-top: 1px solid #dbdbdb;
            flex-shrink: 0;
        }

        .message-form {
            display: flex;
            gap: 12px;
            align-items: flex-end;
        }

        .message-input {
            flex: 1;
            min-height: 44px;
            max-height: 120px;
            padding: 12px 16px;
            border: 1px solid #dbdbdb;
            border-radius: 22px;
            font-size: 15px;
            font-family: inherit;
            resize: none;
            outline: none;
            transition: border-color 0.2s ease;
        }

        .message-input:focus {
            border-color: #0095f6;
        }

        .send-btn {
            background: #0095f6;
            color: white;
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .send-btn:hover {
            background: #1877f2;
        }

        .send-btn:disabled {
            background: #dbdbdb;
            cursor: not-allowed;
        }

        .empty-chat {
            text-align: center;
            padding: 40px 20px;
            color: #8e8e8e;
        }

        .empty-chat-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-chat-text {
            font-size: 16px;
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
            .chat-container {
                border-left: none;
                border-right: none;
            }

            .nav-container {
                padding: 0 16px;
            }

            .chat-header {
                padding: 16px 20px;
            }

            .messages-area {
                padding: 16px 20px;
            }

            .message-input-area {
                padding: 16px 20px;
            }

            .message-content {
                max-width: 85%;
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
                <a href="{{ route('messages.index') }}" class="nav-btn" title="Mesajlar" style="color: #0095f6; background: rgba(0, 149, 246, 0.1); border-radius: 8px;">
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

    <!-- Chat Container -->
    <div class="chat-container">
        <!-- Chat Header -->
        <div class="chat-header">
            <button class="back-btn" onclick="history.back()" title="Geri">
                <svg viewBox="0 0 24 24" style="width: 20px; height: 20px; fill: currentColor;">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
            </button>
            <div class="chat-user-info">
                <div class="chat-avatar">
                    {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                </div>
                <div class="chat-user-name">{{ $otherUser->name }}</div>
            </div>
        </div>

        <!-- Messages Area -->
        <div class="messages-area" id="messagesArea">
            @if($messages->count() > 0)
                @foreach($messages as $message)
                    <div class="message {{ $message->sender_id == Auth::id() ? 'sent' : '' }}">
                        <div class="message-avatar">
                            {{ strtoupper(substr($message->sender->name, 0, 1)) }}
                        </div>
                        <div class="message-content">
                            <div class="message-text">{{ $message->message }}</div>
                            <div class="message-time">{{ $message->created_at->format('H:i') }}</div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-chat">
                    <div class="empty-chat-icon">
                        <svg viewBox="0 0 24 24" style="width: 32px; height: 32px; color: var(--text-primary); fill: currentColor; opacity: 0.7;">
                            <path d="M20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h4l4 4 4-4h4c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
                        </svg>
                    </div>
                    <div class="empty-chat-text">Henüz mesajınız yok. İlk mesajı gönderin!</div>
                </div>
            @endif
        </div>

        <!-- Message Input -->
        <div class="message-input-area">
            <form class="message-form" id="messageForm">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $otherUser->id }}">
                <textarea 
                    class="message-input" 
                    id="messageInput"
                    name="message" 
                    placeholder="Mesajınızı yazın..."
                    rows="1"
                    required></textarea>
                <button type="submit" class="send-btn" id="sendBtn">
                    <svg viewBox="0 0 24 24" style="width: 20px; height: 20px; fill: currentColor;">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <script>
        const messageForm = document.getElementById('messageForm');
        const messageInput = document.getElementById('messageInput');
        const messagesArea = document.getElementById('messagesArea');
        const sendBtn = document.getElementById('sendBtn');

        // Auto-resize textarea
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });

        // Send message on Enter (not Shift+Enter)
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                messageForm.dispatchEvent(new Event('submit'));
            }
        });

        // Scroll to bottom
        function scrollToBottom() {
            messagesArea.scrollTop = messagesArea.scrollHeight;
        }

        // Send message
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message) return;

            sendBtn.disabled = true;
            
            fetch('{{ route("messages.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    receiver_id: {{ $otherUser->id }},
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear input
                    messageInput.value = '';
                    messageInput.style.height = 'auto';
                    
                    // Add message to chat
                    const messageHtml = `
                        <div class="message sent">
                            <div class="message-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                            <div class="message-content">
                                <div class="message-text">${message}</div>
                                <div class="message-time">${new Date().toLocaleTimeString('tr-TR', {hour: '2-digit', minute: '2-digit'})}</div>
                            </div>
                        </div>
                    `;
                    
                    // Remove empty state if exists
                    const emptyChat = messagesArea.querySelector('.empty-chat');
                    if (emptyChat) {
                        emptyChat.remove();
                    }
                    
                    messagesArea.insertAdjacentHTML('beforeend', messageHtml);
                    scrollToBottom();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Mesaj gönderilirken bir hata oluştu.');
            })
            .finally(() => {
                sendBtn.disabled = false;
                messageInput.focus();
            });
        });

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

        // Initial scroll to bottom
        document.addEventListener('DOMContentLoaded', function() {
            scrollToBottom();
            messageInput.focus();
            initTheme();
        });
    </script>

</body>
</html>
