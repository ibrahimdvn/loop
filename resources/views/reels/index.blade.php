<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Akış</title>
    <link rel="icon" type="image/png" href="{{ asset('loop.png') }}">
    
    <style>
        :root {
            --bg-primary: #000000;
            --text-primary: #ffffff;
            --text-secondary: #a8a8a8;
            --border-color: rgba(255, 255, 255, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            overflow: hidden;
            height: 100vh;
        }

        /* Reels Container */
        .reels-container {
            height: 100vh;
            overflow-y: scroll;
            scroll-snap-type: y mandatory;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .reels-container::-webkit-scrollbar {
            display: none;
        }

        /* Reel Item */
        .reel-item {
            height: 100vh;
            width: 100%;
            scroll-snap-align: start;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
        }

        .reel-video {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #000;
        }

        /* Overlay Controls */
        .reel-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, transparent 20%, transparent 80%, rgba(0,0,0,0.5) 100%);
            pointer-events: none;
        }

        /* Top Bar */
        .reel-top-bar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10;
            pointer-events: all;
        }

        .reel-logo {
            font-size: 24px;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            text-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }

        .reel-close-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: #fff;
            font-size: 24px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            backdrop-filter: blur(10px);
            transition: background 0.2s ease;
        }

        .reel-close-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        /* Volume Button */
        .reel-volume-btn {
            position: absolute;
            top: 16px;
            right: 70px;
            background: rgba(255,255,255,0.2);
            border: none;
            color: #fff;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            backdrop-filter: blur(10px);
            transition: background 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .reel-volume-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        .reel-volume-btn svg {
            width: 24px;
            height: 24px;
            fill: #fff;
        }

        /* Video Progress Bar */
        .reel-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: rgba(255, 255, 255, 0.3);
            z-index: 10;
        }

        .reel-progress-fill {
            height: 100%;
            background: #fff;
            width: 0%;
            transition: width 0.1s linear;
        }

        /* Double Tap Indicators */
        .reel-tap-indicator {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            animation: tapPulse 0.4s ease;
        }

        .reel-tap-indicator.left {
            left: 40px;
        }

        .reel-tap-indicator.right {
            right: 40px;
        }

        .reel-tap-indicator.show {
            display: flex;
        }

        .reel-tap-indicator svg {
            width: 40px;
            height: 40px;
            fill: #000;
        }

        .reel-tap-text {
            position: absolute;
            bottom: -30px;
            font-size: 14px;
            font-weight: 600;
            color: #000;
            white-space: nowrap;
        }

        @keyframes tapPulse {
            0% {
                transform: translateY(-50%) scale(0.8);
                opacity: 0;
            }
            50% {
                transform: translateY(-50%) scale(1.1);
                opacity: 1;
            }
            100% {
                transform: translateY(-50%) scale(1);
                opacity: 0;
            }
        }

        /* Bottom Info */
        .reel-info {
            position: absolute;
            bottom: 80px;
            left: 20px;
            right: 80px;
            z-index: 10;
            pointer-events: all;
        }

        .reel-user {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .reel-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
        }

        .reel-username {
            font-weight: 600;
            font-size: 14px;
            color: #fff;
            text-decoration: none;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }

        .reel-follow-btn {
            background: transparent;
            border: 1px solid #fff;
            color: #fff;
            padding: 4px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .reel-follow-btn:hover {
            background: #fff;
            color: #000;
        }

        .reel-caption {
            font-size: 14px;
            line-height: 1.5;
            color: #fff;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
            max-height: 80px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        /* Side Actions */
        .reel-actions {
            position: absolute;
            right: 20px;
            bottom: 100px;
            display: flex;
            flex-direction: column;
            gap: 24px;
            align-items: center;
            z-index: 10;
            pointer-events: all;
        }

        .reel-action-btn {
            background: transparent;
            border: none;
            color: #fff;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            transition: transform 0.2s ease;
        }

        .reel-action-btn:hover {
            transform: scale(1.1);
        }

        .reel-action-btn svg {
            width: 32px;
            height: 32px;
            fill: #fff;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));
        }

        .reel-action-btn.liked svg {
            fill: #ff3b5c;
        }

        .reel-action-count {
            font-size: 12px;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }

        /* Play/Pause Button */
        .reel-play-pause {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.9);
            border: none;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 100;
            transition: all 0.2s ease;
        }

        .reel-play-pause.show {
            display: flex;
        }

        .reel-play-pause svg {
            width: 40px;
            height: 40px;
            fill: #000;
        }

        /* Loading Spinner */
        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 50px;
            height: 50px;
            border: 3px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }

        /* No Content Message */
        .no-reels {
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
            color: var(--text-secondary);
        }

        .no-reels svg {
            width: 80px;
            height: 80px;
            fill: var(--text-secondary);
            opacity: 0.5;
        }

        .no-reels h2 {
            font-size: 24px;
            font-weight: 600;
        }

        .no-reels p {
            font-size: 16px;
        }

        .no-reels a {
            margin-top: 20px;
            padding: 12px 32px;
            background: #0095f6;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background 0.2s ease;
        }

        .no-reels a:hover {
            background: #1877f2;
        }

        /* Comments Panel */
        .comments-panel {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--bg-primary);
            border-radius: 20px 20px 0 0;
            transform: translateY(100%);
            transition: transform 0.3s ease;
            z-index: 1000;
            max-height: 80vh;
            display: flex;
            flex-direction: column;
        }

        .comments-panel.open {
            transform: translateY(0);
        }

        .comments-panel-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            z-index: 999;
        }

        .comments-panel-overlay.open {
            opacity: 1;
            pointer-events: all;
        }

        .comments-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .comments-header h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .comments-close {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .comments-list {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }

        .comment-item {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        .comment-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .comment-content {
            flex: 1;
        }

        .comment-username {
            font-weight: 600;
            font-size: 14px;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .comment-text {
            font-size: 14px;
            color: var(--text-primary);
            line-height: 1.4;
            word-wrap: break-word;
        }

        .comment-time {
            font-size: 12px;
            color: var(--text-secondary);
            margin-top: 4px;
        }

        .no-comments {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-secondary);
        }

        .no-comments svg {
            width: 60px;
            height: 60px;
            fill: var(--text-secondary);
            opacity: 0.3;
            margin-bottom: 12px;
        }

        .comments-input-wrapper {
            padding: 16px 20px;
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .comments-input-wrapper img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

        .comments-input-form {
            flex: 1;
            display: flex;
            gap: 8px;
        }

        .comments-input {
            flex: 1;
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 10px 16px;
            color: var(--text-primary);
            font-size: 14px;
            outline: none;
        }

        .comments-input::placeholder {
            color: var(--text-secondary);
        }

        .comments-submit {
            background: transparent;
            border: none;
            color: #0095f6;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            padding: 0 12px;
            opacity: 0.5;
            transition: opacity 0.2s ease;
        }

        .comments-submit.active {
            opacity: 1;
        }

        /* Confirm Modal */
        .confirm-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            animation: fadeIn 0.2s ease;
        }

        .confirm-modal-overlay.show {
            display: flex;
        }

        .confirm-modal {
            background: var(--bg-secondary);
            border-radius: 16px;
            max-width: 400px;
            width: 90%;
            overflow: hidden;
            animation: slideUp 0.3s ease;
        }

        .confirm-modal-content {
            padding: 32px 24px 24px;
            text-align: center;
        }

        .confirm-modal-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .confirm-modal-message {
            font-size: 14px;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        .confirm-modal-buttons {
            border-top: 1px solid var(--border-color);
            display: flex;
        }

        .confirm-modal-button {
            flex: 1;
            padding: 14px;
            background: none;
            border: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .confirm-modal-button:first-child {
            border-right: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .confirm-modal-button:first-child:hover {
            background: var(--hover-bg);
        }

        .confirm-modal-button:last-child {
            color: #ff3b5c;
        }

        .confirm-modal-button:last-child:hover {
            background: rgba(255, 59, 92, 0.1);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                transform: scale(0.95) translateY(20px);
                opacity: 0;
            }
            to {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .reel-info {
                bottom: 60px;
                right: 60px;
            }

            .reel-actions {
                bottom: 80px;
                right: 16px;
                gap: 20px;
            }

            .comments-panel {
                max-height: 90vh;
            }
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="reel-top-bar">
        <a href="{{ route('home') }}" class="reel-logo">Loop</a>
        <button class="reel-volume-btn" id="volumeBtn" onclick="toggleVolume()">
            <svg id="volumeIcon" viewBox="0 0 24 24">
                <path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/>
            </svg>
        </button>
        <button class="reel-close-btn" onclick="window.location.href='{{ route('home') }}'">&times;</button>
    </div>

    @if($reels->count() > 0)
        <!-- Reels Container -->
        <div class="reels-container" id="reelsContainer">
            @foreach($reels as $reel)
             <div class="reel-item" data-reel-id="{{ $reel->id }}">
                 <!-- Video -->
                 @if($reel->video || $reel->video_url)
                     <video 
                         class="reel-video" 
                         loop 
                         playsinline
                         crossorigin="anonymous"
                         data-src="{{ $reel->getVideoUrl() }}"
                     >
                         <source src="{{ $reel->getVideoUrl() }}" type="video/mp4">
                     </video>
                 @endif

                <!-- Loading Spinner -->
                <div class="loading-spinner"></div>

                <!-- Play/Pause Button -->
                <button class="reel-play-pause">
                    <svg viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </button>

                <!-- Double Tap Indicators -->
                <div class="reel-tap-indicator left">
                    <svg viewBox="0 0 24 24">
                        <path d="M11.67 3.87L9.9 2.1 0 12l9.9 9.9 1.77-1.77L3.54 12z"/>
                        <path d="M22.24 12l-9.9-9.9-1.77 1.77L18.7 12l-8.13 8.13 1.77 1.77z"/>
                    </svg>
                    <span class="reel-tap-text">-10s</span>
                </div>
                <div class="reel-tap-indicator right">
                    <svg viewBox="0 0 24 24">
                        <path d="M5.88 4.12L13.76 12l-7.88 7.88L8 22l10-10L8 2z"/>
                        <path d="M1.66 12l7.88-7.88L11.66 2 1.66 12l10 10-2.12 2.12z"/>
                    </svg>
                    <span class="reel-tap-text">+10s</span>
                </div>

                <!-- Progress Bar -->
                <div class="reel-progress">
                    <div class="reel-progress-fill"></div>
                </div>

                <!-- Overlay -->
                <div class="reel-overlay"></div>

                <!-- User Info -->
                <div class="reel-info">
                    <div class="reel-user">
                        <img src="{{ $reel->user->avatar_url }}" alt="{{ $reel->user->name }}" class="reel-avatar">
                        <a href="{{ route('profile.show', $reel->user->id) }}" class="reel-username">{{ $reel->user->username ?? $reel->user->name }}</a>
                        @if($reel->user->id !== Auth::id())
                            <button class="reel-follow-btn" onclick="toggleFollow({{ $reel->user->id }}, this)">
                                Takip Et
                            </button>
                        @endif
                    </div>
                    @if($reel->content)
                        <div class="reel-caption">{{ $reel->content }}</div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="reel-actions">
                    <!-- Like -->
                    <button class="reel-action-btn {{ $reel->likes->where('user_id', Auth::id())->count() > 0 ? 'liked' : '' }}" onclick="toggleLike({{ $reel->id }}, this)">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                        <span class="reel-action-count">{{ $reel->likes_count }}</span>
                    </button>

                    <!-- Comment -->
                    <button class="reel-action-btn" onclick="openComments({{ $reel->id }})">
                        <svg viewBox="0 0 24 24">
                            <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                        </svg>
                        <span class="reel-action-count" id="comment-count-{{ $reel->id }}">{{ $reel->comments_count }}</span>
                    </button>

                    <!-- Share -->
                    <button class="reel-action-btn" onclick="shareReel('{{ url('/posts/' . $reel->id) }}')">
                        <svg viewBox="0 0 24 24">
                            <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/>
                        </svg>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <!-- No Reels -->
        <div class="no-reels">
            <svg viewBox="0 0 24 24">
                <path d="M10 8v8l5-4-5-4zm9-5H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z"/>
            </svg>
            <h2>Henüz video yok</h2>
            <p>Video içeren gönderiler burada görünecek</p>
            <a href="{{ route('home') }}">Ana Sayfaya Dön</a>
        </div>
    @endif

    <!-- Confirm Modal -->
    <div class="confirm-modal-overlay" id="confirmModal">
        <div class="confirm-modal">
            <div class="confirm-modal-content">
                <div class="confirm-modal-title">Yorumu Sil</div>
                <div class="confirm-modal-message">Bu yorumu silmek istediğinizden emin misiniz?</div>
            </div>
            <div class="confirm-modal-buttons">
                <button class="confirm-modal-button" onclick="closeConfirmModal()">İptal</button>
                <button class="confirm-modal-button" onclick="confirmDeleteComment()">Sil</button>
            </div>
        </div>
    </div>

    <!-- Comments Panel Overlay -->
    <div class="comments-panel-overlay" id="commentsOverlay" onclick="closeComments()"></div>

    <!-- Comments Panel -->
    <div class="comments-panel" id="commentsPanel">
        <div class="comments-header">
            <h3>Yorumlar</h3>
            <button class="comments-close" onclick="closeComments()">&times;</button>
        </div>
        <div class="comments-list" id="commentsList">
            <!-- Comments will be loaded here -->
        </div>
        <div class="comments-input-wrapper">
            <img src="{{ Auth::user()->avatar ? url('/media/' . Auth::user()->avatar) : asset('loop.png') }}" alt="{{ Auth::user()->name }}">
            <form class="comments-input-form" onsubmit="submitComment(event)">
                <input 
                    type="text" 
                    class="comments-input" 
                    id="commentInput" 
                    placeholder="Yorum ekle..."
                    oninput="toggleSubmitButton()"
                >
                <button type="submit" class="comments-submit" id="commentSubmit">Gönder</button>
            </form>
        </div>
    </div>

    <script>
        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Reels Container
        const reelsContainer = document.getElementById('reelsContainer');
        let currentReelIndex = 0;
        let isLoading = false;
        let hasMore = {{ $reels->hasMorePages() ? 'true' : 'false' }};
        let nextPage = 2;

        // Initialize first video
        window.addEventListener('load', () => {
            playCurrentReel();
        });

        // Scroll Handler - Video değişimi
        if (reelsContainer) {
            reelsContainer.addEventListener('scroll', () => {
                const reels = document.querySelectorAll('.reel-item');
                const containerHeight = reelsContainer.clientHeight;
                const scrollTop = reelsContainer.scrollTop;

                reels.forEach((reel, index) => {
                    const reelTop = reel.offsetTop;
                    const reelBottom = reelTop + reel.offsetHeight;

                    if (scrollTop >= reelTop - 100 && scrollTop < reelBottom - 100) {
                        if (currentReelIndex !== index) {
                            pauseAllVideos();
                            currentReelIndex = index;
                            playCurrentReel();
                        }
                    }
                });

                // Infinite scroll - Load more
                if (scrollTop + containerHeight >= reelsContainer.scrollHeight - 500) {
                    if (!isLoading && hasMore) {
                        loadMoreReels();
                    }
                }
            });
        }

        function playCurrentReel() {
            const reels = document.querySelectorAll('.reel-item');
            const currentReel = reels[currentReelIndex];
            if (!currentReel) return;

            const video = currentReel.querySelector('video');
            const spinner = currentReel.querySelector('.loading-spinner');
            const progressFill = currentReel.querySelector('.reel-progress-fill');

            if (video) {
                video.muted = isMuted; // Set mute state
                
                // Update progress bar
                video.addEventListener('timeupdate', () => {
                    const progress = (video.currentTime / video.duration) * 100;
                    progressFill.style.width = progress + '%';
                });
                
                video.addEventListener('loadeddata', () => {
                    spinner.style.display = 'none';
                    video.play().catch(() => {
                        // Auto-play blocked, show play button
                        showPlayButton(currentReel);
                    });
                });

                video.load();
            }
        }

        function pauseAllVideos() {
            document.querySelectorAll('.reel-video').forEach(video => {
                video.pause();
            });
        }

        function showPlayButton(reel) {
            const playBtn = reel.querySelector('.reel-play-pause');
            playBtn.classList.add('show');
        }

        // Global mute state
        let isMuted = true;
        let lastTap = 0;
        let tapTimeout = null;

        // Click/Double tap handling
        document.addEventListener('click', (e) => {
            const reel = e.target.closest('.reel-item');
            if (!reel || e.target.closest('.reel-action-btn') || e.target.closest('.reel-follow-btn') || e.target.closest('.reel-volume-btn') || e.target.closest('.reel-close-btn')) return;

            const currentTime = new Date().getTime();
            const tapLength = currentTime - lastTap;
            
            // Double tap detection (within 300ms)
            if (tapLength < 300 && tapLength > 0) {
                // Double tap detected
                clearTimeout(tapTimeout);
                handleDoubleTap(e, reel);
                lastTap = 0;
            } else {
                // Single tap
                lastTap = currentTime;
                tapTimeout = setTimeout(() => {
                    handleSingleTap(reel);
                }, 300);
            }
        });

        function handleSingleTap(reel) {
            const video = reel.querySelector('video');
            
            // Toggle mute instead of play/pause
            video.muted = !video.muted;
            isMuted = video.muted;
            updateVolumeIcon();
        }

        function handleDoubleTap(e, reel) {
            const video = reel.querySelector('video');
            const reelRect = reel.getBoundingClientRect();
            const clickX = e.clientX - reelRect.left;
            const reelWidth = reelRect.width;
            
            // Determine if tap was on left or right side
            if (clickX < reelWidth / 2) {
                // Left side - rewind 10 seconds
                video.currentTime = Math.max(0, video.currentTime - 10);
                showTapIndicator(reel, 'left');
            } else {
                // Right side - forward 10 seconds
                video.currentTime = Math.min(video.duration, video.currentTime + 10);
                showTapIndicator(reel, 'right');
            }
        }

        function showTapIndicator(reel, side) {
            const indicator = reel.querySelector(`.reel-tap-indicator.${side}`);
            indicator.classList.add('show');
            setTimeout(() => {
                indicator.classList.remove('show');
            }, 400);
        }

        // Toggle volume for all videos
        function toggleVolume() {
            isMuted = !isMuted;
            document.querySelectorAll('.reel-video').forEach(video => {
                video.muted = isMuted;
            });
            updateVolumeIcon();
        }

        // Update volume icon
        function updateVolumeIcon() {
            const volumeIcon = document.getElementById('volumeIcon');
            if (isMuted) {
                // Muted icon
                volumeIcon.innerHTML = '<path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/>';
            } else {
                // Unmuted icon
                volumeIcon.innerHTML = '<path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>';
            }
        }

        // Like Toggle
        function toggleLike(postId, button) {
            fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.classList.toggle('liked');
                    const countSpan = button.querySelector('.reel-action-count');
                    countSpan.textContent = data.likes_count;
                }
            });
        }

        // Follow Toggle
        function toggleFollow(userId, button) {
            const isFollowing = button.textContent.trim() === 'Takip Ediliyor';
            const method = isFollowing ? 'DELETE' : 'POST';

            fetch(`/follow/${userId}`, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.textContent = isFollowing ? 'Takip Et' : 'Takip Ediliyor';
                    button.style.background = isFollowing ? 'transparent' : '#fff';
                    button.style.color = isFollowing ? '#fff' : '#000';
                }
            });
        }

        // Share Reel
        async function shareReel(url) {
            if (navigator.share) {
                try {
                    await navigator.share({
                        title: 'Loop\'tan Video',
                        text: 'Bu videoyu Loop\'ta gördüm!',
                        url: url
                    });
                } catch (error) {
                    if (error.name !== 'AbortError') {
                        copyToClipboard(url);
                    }
                }
            } else {
                copyToClipboard(url);
            }
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Bağlantı kopyalandı!');
            });
        }

        // Comments System
        let currentPostId = null;

        function openComments(postId) {
            currentPostId = postId;
            const overlay = document.getElementById('commentsOverlay');
            const panel = document.getElementById('commentsPanel');
            
            overlay.classList.add('open');
            panel.classList.add('open');
            
            // Pause current video
            const currentReel = document.querySelector(`.reel-item[data-reel-id="${postId}"]`);
            if (currentReel) {
                const video = currentReel.querySelector('video');
                if (video) video.pause();
            }
            
            // Load comments
            loadComments(postId);
        }

        function closeComments() {
            const overlay = document.getElementById('commentsOverlay');
            const panel = document.getElementById('commentsPanel');
            
            overlay.classList.remove('open');
            panel.classList.remove('open');
            
            // Resume video
            if (currentPostId) {
                const currentReel = document.querySelector(`.reel-item[data-reel-id="${currentPostId}"]`);
                if (currentReel) {
                    const video = currentReel.querySelector('video');
                    if (video) video.play().catch(() => {});
                }
            }
            
            currentPostId = null;
        }

        function loadComments(postId) {
            const commentsList = document.getElementById('commentsList');
            commentsList.innerHTML = '<div style="text-align: center; padding: 20px; color: var(--text-secondary);">Yükleniyor...</div>';
            
            fetch(`/posts/${postId}/comments`)
                .then(response => response.json())
                .then(data => {
                    console.log('Comments API Response:', data);
                    if (data.comments && data.comments.length > 0) {
                        commentsList.innerHTML = data.comments.map(comment => {
                            const avatarUrl = comment.user.avatar ? `/media/${comment.user.avatar.replace(/^\//, '')}` : '/loop.png';
                            const isOwner = comment.user_id === {{ Auth::id() }};
                            
                            console.log('Comment:', comment.id, 'Owner:', isOwner, 'User ID:', comment.user_id, 'Auth ID:', {{ Auth::id() }});
                            
                            return `
                                <div class="comment-item">
                                    <img src="${avatarUrl}" alt="${comment.user.name}" class="comment-avatar">
                                    <div class="comment-content">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <div class="comment-username">${comment.user.username || comment.user.name}</div>
                                            ${isOwner ? `
                                                <button onclick="deleteComment(${comment.id})" style="background: none; border: none; color: #ff3b5c; font-size: 12px; cursor: pointer; padding: 4px 8px;" data-comment-id="${comment.id}">
                                                    <svg viewBox="0 0 24 24" style="width: 16px; height: 16px; fill: currentColor;">
                                                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                                    </svg>
                                                </button>
                                            ` : ''}
                                        </div>
                                        <div class="comment-text">${escapeHtml(comment.content)}</div>
                                        <div class="comment-time">${formatTime(comment.created_at)}</div>
                                    </div>
                                </div>
                            `;
                        }).join('');
                    } else {
                        commentsList.innerHTML = `
                            <div class="no-comments">
                                <svg viewBox="0 0 24 24">
                                    <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                                </svg>
                                <p>Henüz yorum yok</p>
                                <p style="font-size: 12px; margin-top: 8px;">İlk yorumu sen yap!</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading comments:', error);
                    commentsList.innerHTML = '<div style="text-align: center; padding: 20px; color: var(--text-secondary);">Yorumlar yüklenemedi</div>';
                });
        }

        function toggleSubmitButton() {
            const input = document.getElementById('commentInput');
            const submit = document.getElementById('commentSubmit');
            
            if (input.value.trim().length > 0) {
                submit.classList.add('active');
            } else {
                submit.classList.remove('active');
            }
        }

        function submitComment(event) {
            event.preventDefault();
            
            const input = document.getElementById('commentInput');
            const content = input.value.trim();
            
            if (!content || !currentPostId) return;
            
            const submit = document.getElementById('commentSubmit');
            submit.disabled = true;
            submit.textContent = 'Gönderiliyor...';
            
            fetch(`/posts/${currentPostId}/comments`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ content: content })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    input.value = '';
                    toggleSubmitButton();
                    loadComments(currentPostId);
                    
                    // Update comment count
                    const countElement = document.getElementById(`comment-count-${currentPostId}`);
                    if (countElement) {
                        countElement.textContent = parseInt(countElement.textContent) + 1;
                    }
                }
                submit.disabled = false;
                submit.textContent = 'Gönder';
            })
            .catch(error => {
                console.error('Error posting comment:', error);
                alert('Yorum gönderilemedi. Lütfen tekrar deneyin.');
                submit.disabled = false;
                submit.textContent = 'Gönder';
            });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function formatTime(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = Math.floor((now - date) / 1000); // seconds
            
            if (diff < 60) return 'Az önce';
            if (diff < 3600) return Math.floor(diff / 60) + ' dakika önce';
            if (diff < 86400) return Math.floor(diff / 3600) + ' saat önce';
            if (diff < 604800) return Math.floor(diff / 86400) + ' gün önce';
            return Math.floor(diff / 604800) + ' hafta önce';
        }

        // Confirm Modal
        let commentToDelete = null;

        function showConfirmModal() {
            const modal = document.getElementById('confirmModal');
            modal.classList.add('show');
        }

        function closeConfirmModal() {
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('show');
            commentToDelete = null;
        }

        function deleteComment(commentId) {
            commentToDelete = commentId;
            showConfirmModal();
        }

        function confirmDeleteComment() {
            if (!commentToDelete) {
                console.error('No comment to delete');
                return;
            }
            
            const commentId = commentToDelete; // Store the ID before closing modal
            console.log('Deleting comment:', commentId);
            closeConfirmModal();
            
            fetch(`/comments/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Delete response:', data);
                if (data.success) {
                    showToast('Yorum silindi', 'success');
                    // Reload comments
                    loadComments(currentPostId);
                    
                    // Update comment count
                    const countElement = document.getElementById(`comment-count-${currentPostId}`);
                    if (countElement && data.comments_count !== undefined) {
                        countElement.textContent = data.comments_count;
                    }
                } else {
                    showToast(data.message || 'Yorum silinemedi', 'error');
                }
            })
            .catch(error => {
                console.error('Error deleting comment:', error);
                showToast('Yorum silinemedi: ' + error.message, 'error');
            });
        }

        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                top: 80px;
                left: 50%;
                transform: translateX(-50%);
                background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
                color: white;
                padding: 12px 24px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                z-index: 10001;
                animation: slideDown 0.3s ease;
                font-size: 14px;
                font-weight: 500;
            `;
            toast.textContent = message;
            
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideDown {
                    from {
                        transform: translateX(-50%) translateY(-20px);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(-50%) translateY(0);
                        opacity: 1;
                    }
                }
            `;
            
            document.head.appendChild(style);
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideDown 0.3s ease reverse';
                setTimeout(() => {
                    document.body.removeChild(toast);
                    document.head.removeChild(style);
                }, 300);
            }, 2000);
        }

        // Load More Reels
        function loadMoreReels() {
            if (isLoading || !hasMore) return;

            isLoading = true;

            fetch(`/api/reels/load-more?page=${nextPage}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.reels.length > 0) {
                        data.reels.forEach(reel => {
                            appendReel(reel);
                        });
                        hasMore = data.hasMore;
                        nextPage = data.nextPage;
                    }
                    isLoading = false;
                })
                .catch(() => {
                    isLoading = false;
                });
        }

        function appendReel(reel) {
            // Dinamik olarak yeni reel ekle
            const reelHTML = `
                <div class="reel-item" data-reel-id="${reel.id}">
                    <video class="reel-video" loop playsinline data-src="${reel.video_url}">
                        <source src="${reel.video_url}" type="video/mp4">
                    </video>
                    <div class="loading-spinner"></div>
                    <button class="reel-play-pause">
                        <svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </button>
                    <div class="reel-overlay"></div>
                    <div class="reel-info">
                        <div class="reel-user">
                            <img src="${reel.user.avatar_url}" alt="${reel.user.name}" class="reel-avatar">
                            <a href="/profile/${reel.user.id}" class="reel-username">${reel.user.username || reel.user.name}</a>
                        </div>
                        ${reel.content ? `<div class="reel-caption">${reel.content}</div>` : ''}
                    </div>
                    <div class="reel-actions">
                        <button class="reel-action-btn" onclick="toggleLike(${reel.id}, this)">
                            <svg viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            <span class="reel-action-count">${reel.likes_count}</span>
                        </button>
                        <button class="reel-action-btn" onclick="alert('Yorum özelliği yakında!')">
                            <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                            <span class="reel-action-count">${reel.comments_count}</span>
                        </button>
                        <button class="reel-action-btn" onclick="shareReel('/posts/${reel.id}')">
                            <svg viewBox="0 0 24 24"><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg>
                        </button>
                    </div>
                </div>
            `;
            reelsContainer.insertAdjacentHTML('beforeend', reelHTML);
        }
    </script>
</body>
</html>


