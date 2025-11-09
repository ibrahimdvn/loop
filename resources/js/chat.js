// Live Chat System
class ChatManager {
    constructor() {
        this.userId = window.Laravel?.user?.id;
        this.currentChatUserId = null;
        this.messageInput = null;
        this.messagesContainer = null;
        this.init();
    }

    init() {
        if (!this.userId) return;

        // Setup Echo listener for incoming messages
        this.setupMessageListener();

        // Setup chat UI if on chat page
        if (document.querySelector('.chat-container')) {
            this.setupChatUI();
        }
    }

    setupMessageListener() {
        if (typeof window.Echo === 'undefined') return;

        // Listen for incoming messages
        window.Echo.private(`chat.${this.userId}`)
            .listen('.message.sent', (e) => {
                this.handleIncomingMessage(e);
            });
    }

    setupChatUI() {
        this.messageInput = document.querySelector('#message-input');
        this.messagesContainer = document.querySelector('#messages-container');
        
        // Get current chat user ID from page
        const chatUserElement = document.querySelector('[data-chat-user-id]');
        if (chatUserElement) {
            this.currentChatUserId = chatUserElement.dataset.chatUserId;
        }

        // Setup send button
        const sendButton = document.querySelector('#send-message');
        if (sendButton) {
            sendButton.addEventListener('click', () => this.sendMessage());
        }

        // Setup enter key to send
        if (this.messageInput) {
            this.messageInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.sendMessage();
                }
            });
        }

        // Auto-scroll to bottom
        this.scrollToBottom();
    }

    async sendMessage() {
        const message = this.messageInput?.value.trim();
        if (!message || !this.currentChatUserId) return;

        try {
            const response = await fetch('/messages', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    receiver_id: this.currentChatUserId,
                    message: message
                })
            });

            const data = await response.json();

            if (data.success) {
                // Clear input
                this.messageInput.value = '';

                // Add message to UI (sender side)
                this.addMessageToUI({
                    id: data.message.id,
                    sender_id: this.userId,
                    message: message,
                    created_at: new Date().toISOString()
                }, true);

                // Scroll to bottom
                this.scrollToBottom();
            }
        } catch (error) {
            console.error('Error sending message:', error);
            this.showError('Mesaj gönderilemedi');
        }
    }

    handleIncomingMessage(messageData) {
        // If we're on the chat page with this user, add message to UI
        if (this.currentChatUserId == messageData.sender_id) {
            this.addMessageToUI(messageData, false);
            this.scrollToBottom();
        }

        // Show notification
        this.showNotification(messageData);

        // Update conversation list badge
        this.updateConversationBadge(messageData.sender_id);
    }

    addMessageToUI(messageData, isSender) {
        if (!this.messagesContainer) return;

        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${isSender ? 'sent' : 'received'}`;
        messageDiv.innerHTML = `
            <div class="message-content">
                <div class="message-text">${this.escapeHtml(messageData.message)}</div>
                <div class="message-time">${this.formatTime(messageData.created_at)}</div>
            </div>
        `;

        this.messagesContainer.appendChild(messageDiv);
    }

    scrollToBottom() {
        if (this.messagesContainer) {
            this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight;
        }
    }

    showNotification(messageData) {
        // Show browser notification if supported
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification(`${messageData.sender_name} mesaj gönderdi`, {
                body: messageData.message.substring(0, 100),
                icon: messageData.sender_avatar || '/images/default-avatar.png',
                tag: `message-${messageData.id}`
            });
        }

        // Show in-app notification
        this.showToast(`${messageData.sender_name}: ${messageData.message.substring(0, 50)}...`);
    }

    showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.textContent = message;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #667eea;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            animation: slideIn 0.3s ease;
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    showError(message) {
        const toast = document.createElement('div');
        toast.className = 'toast-error';
        toast.textContent = message;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #ef4444;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
        `;

        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    updateConversationBadge(userId) {
        const badge = document.querySelector(`[data-conversation-user="${userId}"] .unread-badge`);
        if (badge) {
            const count = parseInt(badge.textContent) || 0;
            badge.textContent = count + 1;
            badge.style.display = 'block';
        }
    }

    formatTime(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        const diff = now - date;

        // Less than 1 minute
        if (diff < 60000) {
            return 'Şimdi';
        }

        // Less than 1 hour
        if (diff < 3600000) {
            const minutes = Math.floor(diff / 60000);
            return `${minutes} dakika önce`;
        }

        // Less than 24 hours
        if (diff < 86400000) {
            const hours = Math.floor(diff / 3600000);
            return `${hours} saat önce`;
        }

        // Format as date
        return date.toLocaleDateString('tr-TR', {
            day: 'numeric',
            month: 'short',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Request notification permission
    static requestNotificationPermission() {
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    }
}

// Initialize chat manager when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.chatManager = new ChatManager();
        ChatManager.requestNotificationPermission();
    });
} else {
    window.chatManager = new ChatManager();
    ChatManager.requestNotificationPermission();
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    .message {
        margin-bottom: 10px;
        display: flex;
    }

    .message.sent {
        justify-content: flex-end;
    }

    .message.received {
        justify-content: flex-start;
    }

    .message-content {
        max-width: 70%;
        padding: 10px 15px;
        border-radius: 18px;
    }

    .message.sent .message-content {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .message.received .message-content {
        background: #f3f4f6;
        color: #1f2937;
    }

    .message-text {
        word-wrap: break-word;
    }

    .message-time {
        font-size: 11px;
        opacity: 0.7;
        margin-top: 5px;
    }
`;
document.head.appendChild(style);

