<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $user->name }}'in Hikayesi</title>
    <link rel="icon" type="image/png" href="{{ asset('loop.png') }}">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #000;
            color: #fff;
            overflow: hidden;
            height: 100vh;
        }

        .story-viewer {
            width: 100%;
            height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .story-progress-bars {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            padding: 8px 12px;
            display: flex;
            gap: 4px;
            z-index: 100;
        }

        .progress-bar {
            flex: 1;
            height: 2px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 1px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: #fff;
            width: 0%;
            transition: width 0.1s linear;
        }

        .story-header {
            position: absolute;
            top: 12px;
            left: 12px;
            right: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 100;
            margin-top: 12px;
        }

        .story-user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .story-user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
        }

        .story-user-details {
            display: flex;
            flex-direction: column;
        }

        .story-username {
            font-weight: 600;
            font-size: 14px;
            text-shadow: 0 1px 3px rgba(0,0,0,0.5);
        }

        .story-time {
            font-size: 12px;
            color: rgba(255,255,255,0.7);
            text-shadow: 0 1px 3px rgba(0,0,0,0.5);
        }

        .story-actions {
            display: flex;
            gap: 12px;
        }

        .story-action-btn {
            background: none;
            border: none;
            color: #fff;
            cursor: pointer;
            font-size: 24px;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s ease;
        }

        .story-action-btn:hover {
            transform: scale(1.1);
        }

        .story-content {
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
        }

        .story-media {
            max-width: 100%;
            max-height: 100vh;
            object-fit: contain;
        }

        .story-navigation {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 50%;
            cursor: pointer;
            z-index: 50;
        }

        .story-nav-prev {
            left: 0;
        }

        .story-nav-next {
            right: 0;
        }

        .story-close {
            position: absolute;
            top: 16px;
            right: 16px;
            background: rgba(0,0,0,0.5);
            border: none;
            color: #fff;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 24px;
            z-index: 101;
            backdrop-filter: blur(10px);
        }

        .story-viewers {
            position: absolute;
            bottom: 20px;
            left: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #fff;
            font-size: 14px;
            cursor: pointer;
            z-index: 100;
        }

        .story-viewers svg {
            width: 20px;
            height: 20px;
            fill: #fff;
        }

        /* Story Like Button */
        .story-like-btn {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: none;
            color: #fff;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 100;
            transition: all 0.3s ease;
        }

        .story-like-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        .story-like-btn.liked {
            background: rgba(255, 59, 92, 0.3);
        }

        .story-like-btn svg {
            width: 32px;
            height: 32px;
            fill: #fff;
            transition: all 0.3s ease;
        }

        .story-like-btn.liked svg {
            fill: #ff3b5c;
            animation: likeHeart 0.4s ease;
        }

        @keyframes likeHeart {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.3); }
        }

        /* Navigation Arrows */
        .story-nav-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: none;
            color: #fff;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 101;
            transition: all 0.2s ease;
            opacity: 0.7;
        }

        .story-nav-arrow:hover {
            opacity: 1;
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-50%) scale(1.1);
        }

        .story-nav-arrow.left {
            left: 20px;
        }

        .story-nav-arrow.right {
            right: 20px;
        }

        .story-nav-arrow svg {
            width: 24px;
            height: 24px;
            fill: #fff;
        }

        /* Loading */
        .story-loading {
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

        /* Viewers Panel */
        .viewers-panel {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(26, 26, 26, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 20px 20px 0 0;
            transform: translateY(100%);
            transition: transform 0.3s ease;
            z-index: 200;
            max-height: 70vh;
            display: flex;
            flex-direction: column;
        }

        .viewers-panel.open {
            transform: translateY(0);
        }

        .viewers-panel-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            z-index: 199;
        }

        .viewers-panel-overlay.open {
            opacity: 1;
            pointer-events: all;
        }

        .viewers-header {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .viewers-header h3 {
            font-size: 16px;
            font-weight: 600;
            color: #fff;
        }

        .viewers-close {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            width: 32px;
            height: 32px;
        }

        .viewers-list {
            flex: 1;
            overflow-y: auto;
            padding: 12px 0;
        }

        .viewer-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            transition: background 0.2s ease;
        }

        .viewer-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .viewer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .viewer-info {
            flex: 1;
        }

        .viewer-name {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 2px;
        }

        .viewer-time {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.5);
        }

        .no-viewers {
            text-align: center;
            padding: 40px 20px;
            color: rgba(255, 255, 255, 0.5);
        }

        .no-viewers svg {
            width: 48px;
            height: 48px;
            fill: rgba(255, 255, 255, 0.3);
            margin-bottom: 12px;
        }
    </style>
</head>
<body>
    <div class="story-viewer">
        <!-- Progress Bars -->
        <div class="story-progress-bars">
            @foreach($stories as $index => $story)
                <div class="progress-bar" data-story-index="{{ $index }}">
                    <div class="progress-fill"></div>
                </div>
            @endforeach
        </div>

        <!-- Close Button -->
        <button class="story-close" onclick="closeStory()">&times;</button>

        <!-- Navigation Arrows -->
        <button class="story-nav-arrow left" onclick="previousStory()" id="prevArrow" style="display: none;">
            <svg viewBox="0 0 24 24">
                <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
            </svg>
        </button>
        <button class="story-nav-arrow right" onclick="nextStory()" id="nextArrow">
            <svg viewBox="0 0 24 24">
                <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
            </svg>
        </button>

        <!-- Header -->
        <div class="story-header">
            <div class="story-user-info">
                <img src="{{ $user->avatar ? url('/media/' . $user->avatar) : asset('loop.png') }}" 
                     alt="{{ $user->name }}" 
                     class="story-user-avatar">
                <div class="story-user-details">
                    <span class="story-username">{{ $user->username ?? $user->name }}</span>
                    <span class="story-time" id="storyTime"></span>
                </div>
            </div>
        </div>

        <!-- Story Content -->
        <div class="story-content" id="storyContent">
            <div class="story-loading"></div>
        </div>

        <!-- Navigation Areas -->
        <div class="story-navigation story-nav-prev" onclick="previousStory()"></div>
        <div class="story-navigation story-nav-next" onclick="nextStory()"></div>

        <!-- Like Button -->
        <button class="story-like-btn" id="storyLikeBtn" onclick="likeStory()">
            <svg viewBox="0 0 24 24">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
        </button>

        <!-- Viewers (Only show for own stories) -->
        @if($user->id === Auth::id())
            <div class="story-viewers" onclick="openViewersPanel()">
                <svg viewBox="0 0 24 24">
                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                </svg>
                <span id="viewersCount">0</span>
            </div>
        @endif
    </div>

    <!-- Viewers Panel Overlay -->
    <div class="viewers-panel-overlay" id="viewersOverlay" onclick="closeViewersPanel()"></div>

    <!-- Viewers Panel -->
    <div class="viewers-panel" id="viewersPanel">
        <div class="viewers-header">
            <h3>Görüntüleyenler</h3>
            <button class="viewers-close" onclick="closeViewersPanel()">&times;</button>
        </div>
        <div class="viewers-list" id="viewersList">
            <!-- Viewers will be loaded here -->
        </div>
    </div>

    <script>
        const stories = @json($stories);
        const likedStoryIdsFromServer = @json($likedStoryIds ?? []);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let currentIndex = 0;
        let progressInterval = null;
        let startTime = null;

        function loadStory(index) {
            if (index < 0 || index >= stories.length) {
                closeStory();
                return;
            }

            currentIndex = index;
            const story = stories[index];
            const storyContent = document.getElementById('storyContent');
            
            // Update arrow visibility
            updateArrows(index);
            
            // Update like button state
            updateLikeButton();
            
            // Clear previous content
            storyContent.innerHTML = '<div class="story-loading"></div>';
            
            // Update progress bars
            updateProgressBars(index);
            
            // Update time
            updateStoryTime(story);
            
            // Load media
            if (story.media_type === 'image') {
                const img = document.createElement('img');
                img.src = `/media/${story.media_path}`;
                img.className = 'story-media';
                img.onload = () => {
                    storyContent.innerHTML = '';
                    storyContent.appendChild(img);
                    startProgress(story.duration || 5);
                };
            } else if (story.media_type === 'video') {
                const video = document.createElement('video');
                video.src = `/media/${story.media_path}`;
                video.className = 'story-media';
                video.autoplay = true;
                video.muted = false;
                video.onloadeddata = () => {
                    storyContent.innerHTML = '';
                    storyContent.appendChild(video);
                    startProgress(video.duration);
                };
                video.onended = () => {
                    nextStory();
                };
            }
        }

        function startProgress(duration) {
            clearInterval(progressInterval);
            const progressBar = document.querySelector(`.progress-bar[data-story-index="${currentIndex}"] .progress-fill`);
            
            let progress = 0;
            const increment = 100 / (duration * 10); // Update every 100ms
            startTime = Date.now();
            
            progressInterval = setInterval(() => {
                progress += increment;
                if (progress >= 100) {
                    progress = 100;
                    progressBar.style.width = '100%';
                    clearInterval(progressInterval);
                    nextStory();
                } else {
                    progressBar.style.width = progress + '%';
                }
            }, 100);
        }

        function updateProgressBars(index) {
            document.querySelectorAll('.progress-bar').forEach((bar, i) => {
                const fill = bar.querySelector('.progress-fill');
                if (i < index) {
                    fill.style.width = '100%';
                } else if (i === index) {
                    fill.style.width = '0%';
                } else {
                    fill.style.width = '0%';
                }
            });
        }

        function updateStoryTime(story) {
            const storyTime = document.getElementById('storyTime');
            const createdAt = new Date(story.created_at);
            const now = new Date();
            const diff = Math.floor((now - createdAt) / 1000);
            
            if (diff < 60) storyTime.textContent = 'Az önce';
            else if (diff < 3600) storyTime.textContent = Math.floor(diff / 60) + ' dakika önce';
            else storyTime.textContent = Math.floor(diff / 3600) + ' saat önce';
        }

        function updateArrows(index) {
            const prevArrow = document.getElementById('prevArrow');
            const nextArrow = document.getElementById('nextArrow');
            
            // Show/hide arrows based on position
            if (index === 0) {
                prevArrow.style.display = 'none';
            } else {
                prevArrow.style.display = 'flex';
            }
            
            if (index === stories.length - 1) {
                nextArrow.style.display = 'none';
            } else {
                nextArrow.style.display = 'flex';
            }
        }

        function previousStory() {
            if (currentIndex > 0) {
                loadStory(currentIndex - 1);
            }
        }

        function nextStory() {
            if (currentIndex < stories.length - 1) {
                loadStory(currentIndex + 1);
            } else {
                closeStory();
            }
        }

        function closeStory() {
            window.location.href = '{{ route("home") }}';
        }

        function openViewersPanel() {
            const story = stories[currentIndex];
            const overlay = document.getElementById('viewersOverlay');
            const panel = document.getElementById('viewersPanel');
            const viewersList = document.getElementById('viewersList');
            
            // Pause story
            clearInterval(progressInterval);
            
            // Show panel
            overlay.classList.add('open');
            panel.classList.add('open');
            
            // Load viewers
            viewersList.innerHTML = '<div style="text-align: center; padding: 20px; color: rgba(255,255,255,0.5);">Yükleniyor...</div>';
            
            fetch(`/stories/${story.id}/viewers`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.viewers.length > 0) {
                        viewersList.innerHTML = data.viewers.map(viewer => `
                            <div class="viewer-item">
                                <img src="${viewer.user.avatar ? '/media/' + viewer.user.avatar : '/loop.png'}" 
                                     alt="${viewer.user.name}" 
                                     class="viewer-avatar">
                                <div class="viewer-info">
                                    <div class="viewer-name">${viewer.user.username || viewer.user.name}</div>
                                    <div class="viewer-time">${formatViewTime(viewer.viewed_at)}</div>
                                </div>
                            </div>
                        `).join('');
                        
                        document.getElementById('viewersCount').textContent = data.total_views;
                    } else {
                        viewersList.innerHTML = `
                            <div class="no-viewers">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                </svg>
                                <p>Henüz kimse görüntülemedi</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading viewers:', error);
                    viewersList.innerHTML = '<div style="text-align: center; padding: 20px; color: rgba(255,255,255,0.5);">Hata oluştu</div>';
                });
        }

        function closeViewersPanel() {
            const overlay = document.getElementById('viewersOverlay');
            const panel = document.getElementById('viewersPanel');
            
            overlay.classList.remove('open');
            panel.classList.remove('open');
            
            // Resume story
            if (currentIndex < stories.length) {
                const story = stories[currentIndex];
                const remainingTime = story.duration - ((Date.now() - startTime) / 1000);
                if (remainingTime > 0) {
                    startProgress(remainingTime);
                }
            }
        }

        function formatViewTime(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = Math.floor((now - date) / 1000);
            
            if (diff < 60) return 'Az önce';
            if (diff < 3600) return Math.floor(diff / 60) + ' dakika önce';
            if (diff < 86400) return Math.floor(diff / 3600) + ' saat önce';
            return Math.floor(diff / 86400) + ' gün önce';
        }

        // Like Story - Track for each story separately
        // Initialize with already liked stories from server
        let likedStories = new Set(likedStoryIdsFromServer);
        
        function likeStory() {
            const story = stories[currentIndex];
            const likeBtn = document.getElementById('storyLikeBtn');
            
            // Check if this specific story is already liked
            if (likedStories.has(story.id)) return;
            
            likedStories.add(story.id);
            likeBtn.classList.add('liked');
            
            // Send like notification
            fetch(`/api/stories/${story.id}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Story liked successfully');
                }
            })
            .catch(error => {
                console.error('Error liking story:', error);
                // Revert on error
                likedStories.delete(story.id);
                likeBtn.classList.remove('liked');
            });
        }

        // Update like button state when story changes
        function updateLikeButton() {
            const story = stories[currentIndex];
            const likeBtn = document.getElementById('storyLikeBtn');
            
            if (likedStories.has(story.id)) {
                likeBtn.classList.add('liked');
            } else {
                likeBtn.classList.remove('liked');
            }
        }

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') previousStory();
            if (e.key === 'ArrowRight') nextStory();
            if (e.key === 'Escape') closeStory();
        });

        // Pause on hold (like Instagram)
        let isPaused = false;
        let pausedTime = 0;

        document.querySelector('.story-viewer').addEventListener('pointerdown', (e) => {
            // Don't pause if clicking on navigation areas or buttons
            if (e.target.closest('.story-nav-prev') || 
                e.target.closest('.story-nav-next') || 
                e.target.closest('.story-close') ||
                e.target.closest('.story-viewers')) return;
            
            isPaused = true;
            pausedTime = Date.now();
            clearInterval(progressInterval);
        });

        document.querySelector('.story-viewer').addEventListener('pointerup', (e) => {
            // Don't resume if clicking on navigation areas or buttons
            if (e.target.closest('.story-nav-prev') || 
                e.target.closest('.story-nav-next') || 
                e.target.closest('.story-close') ||
                e.target.closest('.story-viewers')) return;
                
            if (isPaused && currentIndex < stories.length) {
                isPaused = false;
                const story = stories[currentIndex];
                const elapsedBeforePause = (pausedTime - startTime) / 1000;
                const remainingTime = story.duration - elapsedBeforePause;
                
                if (remainingTime > 0) {
                    startTime = Date.now() - (elapsedBeforePause * 1000);
                    startProgress(remainingTime);
                }
            }
        });

        // Load first story
        loadStory(0);
    </script>
</body>
</html>

