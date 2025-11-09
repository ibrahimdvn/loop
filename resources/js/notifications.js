// Real-time Notifications System
class NotificationManager {
    constructor() {
        this.init();
    }

    init() {
        if (typeof window.Echo !== 'undefined') {
            this.setupNotificationChannel();
            this.setupPostChannels();
        }
    }

    setupNotificationChannel() {
        // Private channel for user notifications
        window.Echo.private(`user.${window.Laravel.user.id}`)
            .listen('NotificationSent', (e) => {
                this.showNotification(e);
                this.updateNotificationBadge();
            });
    }

    setupPostChannels() {
        // Listen for likes and comments on posts
        document.querySelectorAll('[data-post-id]').forEach(element => {
            const postId = element.getAttribute('data-post-id');
            
            window.Echo.channel(`post.${postId}`)
                .listen('PostLiked', (e) => {
                    this.updateLikeCount(postId, e.likes_count);
                })
                .listen('CommentAdded', (e) => {
                    this.addCommentToPost(postId, e);
                });
        });
    }

    showNotification(data) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'notification-toast';
        notification.innerHTML = `
            <div class="notification-content">
                <img src="${data.from_user.avatar}" alt="${data.from_user.name}" class="notification-avatar">
                <div class="notification-text">
                    <strong>${data.from_user.name}</strong> ${data.message}
                </div>
                <button class="notification-close" onclick="this.parentElement.parentElement.remove()">×</button>
            </div>
        `;

        // Add to notification container
        let container = document.getElementById('notification-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'notification-container';
            container.className = 'notification-container';
            document.body.appendChild(container);
        }

        container.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);

        // Add click handler for post notifications
        if (data.post_id) {
            notification.addEventListener('click', () => {
                window.location.href = `/posts/${data.post_id}`;
            });
        }
    }

    updateNotificationBadge() {
        const badge = document.querySelector('.notification-badge');
        if (badge) {
            const currentCount = parseInt(badge.textContent) || 0;
            badge.textContent = currentCount + 1;
            badge.style.display = 'block';
        }
    }

    updateLikeCount(postId, count) {
        const likeButton = document.querySelector(`[data-post-id="${postId}"] .like-button`);
        const likeCount = document.querySelector(`[data-post-id="${postId}"] .like-count`);
        
        if (likeCount) {
            likeCount.textContent = count;
        }
    }

    addCommentToPost(postId, commentData) {
        const commentsContainer = document.querySelector(`[data-post-id="${postId}"] .comments-container`);
        if (commentsContainer) {
            const commentElement = document.createElement('div');
            commentElement.className = 'comment-item';
            commentElement.innerHTML = `
                <img src="${commentData.user.avatar}" alt="${commentData.user.name}" class="comment-avatar">
                <div class="comment-content">
                    <strong>${commentData.user.name}</strong>
                    <span>${commentData.content}</span>
                    <small>${this.formatTime(commentData.created_at)}</small>
                </div>
            `;
            commentsContainer.appendChild(commentElement);
        }
    }

    formatTime(timestamp) {
        const now = new Date();
        const time = new Date(timestamp);
        const diff = now - time;
        
        if (diff < 60000) return 'şimdi';
        if (diff < 3600000) return `${Math.floor(diff / 60000)} dk önce`;
        if (diff < 86400000) return `${Math.floor(diff / 3600000)} sa önce`;
        return `${Math.floor(diff / 86400000)} gün önce`;
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    if (window.Laravel && window.Laravel.user) {
        new NotificationManager();
    }
});

// CSS for notifications
const notificationStyles = `
    .notification-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
    }

    .notification-toast {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        margin-bottom: 10px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .notification-toast:hover {
        transform: translateX(-5px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    }

    .notification-content {
        display: flex;
        align-items: center;
        padding: 12px;
        position: relative;
    }

    .notification-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 12px;
        object-fit: cover;
    }

    .notification-text {
        flex: 1;
        font-size: 14px;
        line-height: 1.4;
    }

    .notification-close {
        position: absolute;
        top: 8px;
        right: 8px;
        background: none;
        border: none;
        font-size: 18px;
        cursor: pointer;
        color: #999;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .notification-close:hover {
        color: #333;
    }

    .notification-badge {
        background: #ff4757;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        top: -5px;
        right: -5px;
    }
`;

// Inject styles
const styleSheet = document.createElement('style');
styleSheet.textContent = notificationStyles;
document.head.appendChild(styleSheet);
