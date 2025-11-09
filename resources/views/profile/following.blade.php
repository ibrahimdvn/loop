<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $user->name }} - Takip Edilenler</title>
    <link rel="icon" type="image/png" href="{{ asset('loop.png') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        :root {
            --bg-primary: #fafafa;
            --bg-secondary: #ffffff;
            --text-primary: #1a1a1a;
            --text-secondary: #888;
            --border-color: rgba(0, 0, 0, 0.1);
            --hover-bg: #f5f5f5;
        }

        [data-theme="dark"] {
            --bg-primary: #000000;
            --bg-secondary: #1a1a1a;
            --text-primary: #ffffff;
            --text-secondary: #888;
            --border-color: rgba(255, 255, 255, 0.1);
            --hover-bg: #222;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            padding: 20px;
            transition: background 0.3s ease, color 0.3s ease;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .back-btn {
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 24px;
            cursor: pointer;
            padding: 10px;
            border-radius: 50%;
            transition: background 0.2s;
        }

        .back-btn:hover {
            background: var(--hover-bg);
        }

        .header-title h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .header-title p {
            font-size: 14px;
            color: #888;
        }

        .user-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .user-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px;
            background: var(--bg-secondary);
            border-radius: 12px;
            transition: background 0.2s;
        }

        .user-item:hover {
            background: var(--hover-bg);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
            text-decoration: none;
            color: inherit;
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 18px;
            color: white;
        }

        .user-details {
            flex: 1;
        }

        .user-name {
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 2px;
        }

        .user-username {
            font-size: 13px;
            color: #888;
        }

        .follow-btn {
            padding: 8px 24px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #2a2a2a;
            color: #fff;
        }

        .btn-secondary:hover {
            background: #333;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #888;
        }

        .empty-state svg {
            width: 64px;
            height: 64px;
            opacity: 0.3;
            margin-bottom: 16px;
        }

        .empty-state h3 {
            font-size: 18px;
            margin-bottom: 8px;
        }

        .empty-state p {
            font-size: 14px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 30px;
        }

        .pagination a, .pagination span {
            padding: 8px 16px;
            background: #1a1a1a;
            border-radius: 8px;
            color: #fff;
            text-decoration: none;
            transition: background 0.2s;
        }

        .pagination a:hover {
            background: #2a2a2a;
        }

        .pagination .active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <button class="back-btn" onclick="window.history.back()">←</button>
            <div class="header-title">
                <h1>Takip Edilenler</h1>
                <p>{{ $user->name }}</p>
            </div>
        </div>

        <div class="user-list">
            @forelse($following as $followedUser)
                <div class="user-item">
                    <a href="{{ route('profile.show', $followedUser->id) }}" class="user-info">
                        @if($followedUser->avatar)
                            <img src="{{ url('/media/' . $followedUser->avatar) }}" alt="{{ $followedUser->name }}" class="user-avatar">
                        @else
                            <div class="user-avatar">
                                {{ strtoupper(substr($followedUser->name, 0, 1)) }}
                            </div>
                        @endif
                        
                        <div class="user-details">
                            <div class="user-name">{{ $followedUser->name }}</div>
                            <div class="user-username">@{{ $followedUser->username ?? strtolower(str_replace(' ', '', $followedUser->name)) }}</div>
                        </div>
                    </a>
                    
                    @if($followedUser->id !== Auth::id())
                        @if(Auth::user()->isFollowing($followedUser))
                            <button class="follow-btn btn-secondary" onclick="toggleFollow({{ $followedUser->id }}, false)">
                                Takip Ediliyor
                            </button>
                        @else
                            <button class="follow-btn btn-primary" onclick="toggleFollow({{ $followedUser->id }}, true)">
                                Takip Et
                            </button>
                        @endif
                    @endif
                </div>
            @empty
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                    </svg>
                    <h3>Henüz kimse takip edilmiyor</h3>
                    <p>{{ $user->name }} henüz kimseyi takip etmiyor</p>
                </div>
            @endforelse
        </div>

        @if($following->hasPages())
            <div class="pagination">
                {{ $following->links() }}
            </div>
        @endif
    </div>

    <script>
        // Load saved theme
        function initTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        }

        // Initialize theme on page load
        initTheme();

        async function toggleFollow(userId, shouldFollow) {
            const button = event.target;
            const originalText = button.textContent;
            button.disabled = true;
            button.textContent = 'Yükleniyor...';

            try {
                const method = shouldFollow ? 'POST' : 'DELETE';
                const response = await fetch(`/follow/${userId}`, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Update button state
                    if (shouldFollow) {
                        button.textContent = 'Takip Ediliyor';
                        button.className = 'follow-btn btn-secondary';
                        button.onclick = () => toggleFollow(userId, false);
                    } else {
                        button.textContent = 'Takip Et';
                        button.className = 'follow-btn btn-primary';
                        button.onclick = () => toggleFollow(userId, true);
                    }
                } else {
                    button.textContent = originalText;
                    alert(data.error || 'Bir hata oluştu');
                }
                button.disabled = false;
            } catch (error) {
                console.error('Error:', error);
                button.textContent = originalText;
                button.disabled = false;
                alert('Bir hata oluştu');
            }
        }
    </script>
</body>
</html>

