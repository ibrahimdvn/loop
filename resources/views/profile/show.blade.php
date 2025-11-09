<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profil</title>
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
            max-width: 900px;
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
            margin-bottom: 4px;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 16px;
        }

        /* Profile Header */
        .profile-header {
            background: var(--bg-secondary);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            padding: 32px;
            margin-bottom: 24px;
            text-align: center;
        }

        .profile-avatar-container {
            position: relative;
            display: inline-block;
            margin-bottom: 20px;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .profile-avatar.default {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            font-weight: 600;
        }

        .avatar-upload-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #0095f6;
            color: white;
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .avatar-upload-btn:hover {
            background: #1877f2;
            transform: scale(1.05);
        }

        .hidden-input {
            display: none;
        }

        .profile-name {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-align: center;
        }

        .verified-badge {
            width: 20px;
            height: 20px;
            background: #1da1f2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .verified-badge svg {
            width: 12px;
            height: 12px;
            fill: white;
        }

        .profile-username {
            color: var(--text-secondary);
            font-size: 16px;
            margin-bottom: 12px;
        }

        .profile-bio {
            color: var(--text-primary);
            font-size: 15px;
            line-height: 1.5;
            margin-bottom: 20px;
            padding: 12px 16px;
            background: var(--bg-tertiary);
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .profile-stats {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 24px;
            padding: 0 20px;
        }

        .stat-item {
            text-align: center;
            flex: 1;
            min-width: 0;
        }

        .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            display: block;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .profile-actions {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 16px;
            padding: 0 20px;
        }

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
            background: #0095f6;
            color: white;
        }

        .btn-primary:hover {
            background: #1877f2;
        }

        .btn-secondary {
            background: var(--bg-tertiary);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: var(--hover-bg);
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        /* Posts Section */
        .posts-section {
            background: var(--bg-secondary);
            border-radius: 12px;
            border: 1px solid var(--border-color);
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
        }

        .posts-container {
            padding: 0;
        }

        .post-item {
            padding: 24px;
            border-bottom: 1px solid var(--border-color);
        }

        .post-item:last-child {
            border-bottom: none;
        }

        .post-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            position: relative;
        }

        .post-delete-btn {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            opacity: 0.6;
        }

        .post-delete-btn:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            opacity: 1;
            transform: translateY(-50%) scale(1.1);
        }

        .post-delete-btn:active {
            transform: translateY(-50%) scale(0.95);
        }

        .post-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .post-avatar.default {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            font-weight: 600;
        }

        .post-user-info {
            flex: 1;
        }

        .post-user-name {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 15px;
        }

        .post-time {
            color: var(--text-secondary);
            font-size: 13px;
        }

        .post-content {
            margin-bottom: 16px;
        }

        .post-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .post-text {
            color: var(--text-primary);
            line-height: 1.5;
        }

        /* Post Engagement Styles */
        .post-engagement {
            padding: 16px 20px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .engagement-buttons {
            display: flex;
            gap: 20px;
        }

        .engagement-btn {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 20px;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .engagement-btn:hover {
            background: var(--hover-bg);
            color: var(--text-primary);
        }

        .engagement-btn svg {
            fill: currentColor;
        }

        .engagement-count {
            font-size: 14px;
            font-weight: 500;
            color: currentColor;
        }

        .engagement-btn.liked {
            color: #e91e63 !important;
        }

        .engagement-btn.liked svg {
            fill: #e91e63 !important;
        }

        /* Yorum Sistemi */
        .comments-section {
            border-top: 1px solid var(--border-color);
            background: var(--bg-secondary);
            padding: 16px 20px;
        }

        .comments-list {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 16px;
        }

        .comment-item {
            display: flex;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-color);
            position: relative;
        }

        .comment-item:last-child {
            border-bottom: none;
        }

        .comment-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .comment-avatar.default {
            background: var(--accent-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }

        .comment-content {
            flex: 1;
        }

        .comment-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
        }

        .comment-author {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
        }

        .comment-time {
            color: var(--text-secondary);
            font-size: 12px;
        }

        .comment-text {
            color: var(--text-primary);
            font-size: 14px;
            line-height: 1.4;
        }

        .comment-delete-btn {
            position: absolute;
            right: 0;
            top: 12px;
            background: none;
            border: none;
            color: #ff4444;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            opacity: 0;
            transition: all 0.2s ease;
        }

        .comment-item:hover .comment-delete-btn {
            opacity: 1;
        }

        .comment-delete-btn:hover {
            background: rgba(255, 68, 68, 0.1);
        }

        .comment-delete-btn svg {
            width: 14px;
            height: 14px;
            fill: currentColor;
        }

        .comment-form {
            margin-top: 16px;
        }

        .comment-input-group {
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 8px 16px;
        }

        .comment-input {
            flex: 1;
            border: none;
            background: none;
            color: var(--text-primary);
            font-size: 14px;
            outline: none;
            padding: 8px 0;
            max-height: 100px;
            overflow: hidden;
            resize: none;
        }

        .comment-input::placeholder {
            color: var(--text-secondary);
        }

        .comment-submit-btn {
            background: var(--accent-color);
            border: none;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .comment-submit-btn:hover {
            background: var(--accent-hover);
            transform: scale(1.05);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 12px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-secondary);
            border-radius: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--accent-color), var(--accent-hover));
            border-radius: 6px;
            border: 2px solid var(--bg-secondary);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, var(--accent-hover), var(--accent-color));
        }

        ::-webkit-scrollbar-corner {
            background: var(--bg-secondary);
        }

        /* Firefox scrollbar */
        html {
            scrollbar-width: thin;
            scrollbar-color: var(--accent-color) var(--bg-secondary);
        }

        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--accent-color);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .back-to-top:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .back-to-top svg {
            width: 24px;
            height: 24px;
            fill: currentColor;
        }

        .back-to-top.show {
            display: flex;
            animation: fadeInUp 0.3s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .post-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 12px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
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

        .success-message {
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.3);
            color: var(--text-primary);
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
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

            .profile-header {
                padding: 24px 20px;
            }

            .profile-stats {
                gap: 12px;
                padding: 0 10px;
            }

            .stat-item {
                flex: 1;
            }

            .stat-number {
                font-size: 20px;
            }

            .stat-label {
                font-size: 12px;
            }

            .profile-actions {
                flex-direction: column;
                gap: 12px;
                padding: 0 10px;
            }

            .page-title {
                font-size: 24px;
            }

            .profile-name {
                font-size: 28px;
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
            background: var(--accent-color);
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
                <a href="{{ route('notifications.index') }}" class="nav-btn" title="Bildirimler" style="position: relative;">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                    </svg>
                    <span id="notificationBadge" class="notification-badge" style="display: none;"></span>
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
                <a href="{{ route('profile.show') }}" class="nav-btn active" title="Profil">
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar_url }}" alt="Profil" style="width: 20px; height: 20px; border-radius: 50%; object-fit: cover;">
                    @else
                        <div style="width: 20px; height: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px; font-weight: 600;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
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
                <h1 class="page-title">{{ $isOwnProfile ? 'Profilim' : $user->name }}</h1>
                <p class="page-subtitle">{{ $isOwnProfile ? 'Kişisel profil sayfanız' : 'Kullanıcı profili' }}</p>
            </div>
        </div>

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar-container">
                @if($user->avatar)
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="profile-avatar">
                @else
                    <div class="profile-avatar default">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                
                @if($isOwnProfile)
                    <form id="avatarForm" method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" style="display: inline;">
                        @csrf
                        <input type="file" id="avatarInput" name="avatar" class="hidden-input" accept="image/*" onchange="document.getElementById('avatarForm').submit()">
                        <button type="button" class="avatar-upload-btn" onclick="document.getElementById('avatarInput').click()" title="Profil resmi değiştir">
                            <svg viewBox="0 0 24 24" style="width: 16px; height: 16px; fill: currentColor;">
                                <path d="M9 2L7.17 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2h-3.17L15 2H9zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
                            </svg>
                        </button>
                    </form>
                @endif
            </div>

            <h2 class="profile-name">
                {{ $user->name }}
                @if($user->is_verified)
                    <div class="verified-badge" title="Doğrulanmış Hesap">
                        <svg viewBox="0 0 24 24">
                            <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
                        </svg>
                    </div>
                @endif
            </h2>
            <p class="profile-username">{{ '@' . ($user->username ?? strtolower(str_replace(' ', '', $user->name))) }}</p>
            
            @if($user->bio)
                <div class="profile-bio">
                    {{ $user->bio }}
                </div>
            @endif

            <div class="profile-stats">
                <div class="stat-item">
                    <span class="stat-number">{{ $stats['posts_count'] }}</span>
                    <span class="stat-label">Gönderi</span>
                </div>
                <div class="stat-item" style="cursor: pointer;" onclick="window.location.href='{{ route('profile.followers', $user->id) }}'">
                    <span class="stat-number">{{ $stats['followers_count'] }}</span>
                    <span class="stat-label">Takipçi</span>
                </div>
                <div class="stat-item" style="cursor: pointer;" onclick="window.location.href='{{ route('profile.following', $user->id) }}'">
                    <span class="stat-number">{{ $stats['following_count'] }}</span>
                    <span class="stat-label">Takip</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $user->created_at->format('Y') }}</span>
                    <span class="stat-label">Katılım</span>
                </div>
            </div>

            <div class="profile-actions">
                @if($isOwnProfile)
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <a href="{{ route('settings.index') }}" class="btn btn-primary">
                            <svg class="icon" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                <path d="M19.14,12.94c0.04-0.3,0.06-0.61,0.06-0.94c0-0.32-0.02-0.64-0.07-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.61 l-1.92-3.32c-0.12-0.22-0.37-0.29-0.59-0.22l-2.39,0.96c-0.5-0.38-1.03-0.7-1.62-0.94L14.4,2.81c-0.04-0.24-0.24-0.41-0.48-0.41 h-3.84c-0.24,0-0.43,0.17-0.47,0.41L9.25,5.35C8.66,5.59,8.12,5.92,7.63,6.29L5.24,5.33c-0.22-0.08-0.47,0-0.59,0.22L2.74,8.87 C2.62,9.08,2.66,9.34,2.86,9.48l2.03,1.58C4.84,11.36,4.8,11.69,4.8,12s0.02,0.64,0.07,0.94l-2.03,1.58 c-0.18,0.14-0.23,0.41-0.12,0.61l1.92,3.32c0.12,0.22,0.37,0.29,0.59,0.22l2.39-0.96c0.5,0.38,1.03,0.7,1.62,0.94l0.36,2.54 c0.05,0.24,0.24,0.41,0.48,0.41h3.84c0.24,0,0.44-0.17,0.47-0.41l0.36-2.54c0.59-0.24,1.13-0.56,1.62-0.94l2.39,0.96 c0.22,0.08,0.47,0,0.59-0.22l1.92-3.32c0.12-0.22,0.07-0.47-0.12-0.61L19.14,12.94z M12,15.6c-1.98,0-3.6-1.62-3.6-3.6 s1.62-3.6,3.6-3.6s3.6,1.62,3.6,3.6S13.98,15.6,12,15.6z"/>
                            </svg>
                            Profili Düzenle
                        </a>
                        
                        <button class="btn btn-secondary" onclick="togglePreviewMode()" id="previewBtn">
                            <svg class="icon" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                            </svg>
                            <span id="previewBtnText">Önizle</span>
                        </button>
                    </div>
                    @if($user->avatar)
                        <form method="POST" action="{{ route('profile.avatar.remove') }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-secondary" onclick="return confirmDelete(event, 'Profil resminizi kaldırmak istediğinizden emin misiniz?')">
                                <svg class="icon" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                    <path d="M6,19c0,1.1,0.9,2,2,2h8c1.1,0,2-0.9,2-2V7H6V19z M19,4h-3.5l-1-1h-5l-1,1H5v2h14V4z"/>
                                </svg>
                                Resmi Kaldır
                            </button>
                        </form>
                    @endif
                @else
                    @if($isFollowing)
                        <button class="btn btn-secondary follow-btn" onclick="toggleFollow({{ $user->id }}, false)">
                            <svg class="icon" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1L13.5 2.5L16.17 5.17L10.5 10.84L11.91 12.25L15.83 8.34L17.5 10H19V9H21ZM1 21V19L7 13L8.5 14.5L5.83 17.17L11.5 22.84L10.09 24.25L6.17 20.34L4.5 22H3V21H1Z"/>
                            </svg>
                            Takip Ediliyor
                        </button>
                    @else
                        <button class="btn btn-primary follow-btn" onclick="toggleFollow({{ $user->id }}, true)">
                            <svg class="icon" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                <path d="M19 13h-2v2h-2v-2h-2v-2h2V9h2v2h2v2z"/>
                            </svg>
                            Takip Et
                        </button>
                    @endif
                    <a href="{{ route('messages.show', $user->id) }}" class="btn btn-secondary">
                        <svg class="icon" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                        Mesaj
                    </a>
                @endif
            </div>
        </div>

        <!-- Posts Section -->
        <div class="posts-section">
            <div class="section-header">
                <h3 class="section-title">{{ $isOwnProfile ? 'Gönderilerim' : 'Gönderileri' }} ({{ $posts->total() }})</h3>
            </div>
            
            <div class="posts-container">
                @if($posts->count() > 0)
                    @foreach($posts as $post)
                        <div class="post-item" data-post-id="{{ $post->id }}">
                            <div class="post-header">
                                <a href="{{ route('profile.show', $post->user->id) }}" style="text-decoration: none; color: inherit;">
                                    @if($post->user->avatar)
                                        <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" class="post-avatar">
                                    @else
                                        <div class="post-avatar default">
                                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </a>
                                
                                <div class="post-user-info">
                                    <a href="{{ route('profile.show', $post->user->id) }}" class="post-user-name" style="text-decoration: none; color: inherit; cursor: pointer; font-weight: 600;">
                                        {{ $post->user->name }}
                                    </a>
                                    <div class="post-time">{{ $post->created_at->diffForHumans() }}</div>
                                </div>

                                @if($post->user_id === Auth::id())
                                    <button class="post-delete-btn" onclick="deletePost({{ $post->id }})" title="Gönderiyi Sil">
                                        <svg viewBox="0 0 24 24" style="width: 18px; height: 18px; fill: currentColor;">
                                            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>

                            <div class="post-content">
                                <h3 class="post-title">{{ $post->title }}</h3>
                                
                                @if($post->image)
                                    <img src="{{ $post->getImageUrl('medium') }}" 
                                         alt="{{ $post->title }}" 
                                         class="post-image"
                                         loading="lazy">
                                @endif
                                
                                <p class="post-text">{{ $post->content }}</p>
                            </div>

                            <!-- Post Engagement -->
                            <div class="post-engagement">
                                <div class="engagement-buttons">
                                    <button class="engagement-btn {{ $post->isLikedBy(Auth::user()) ? 'liked' : '' }}" data-post-id="{{ $post->id }}" onclick="toggleLike(this)">
                                        <svg class="icon" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                        <span class="engagement-count">{{ $post->likes->count() }}</span>
                                    </button>
                                    <button class="engagement-btn" onclick="toggleComments({{ $post->id }})">
                                        <svg class="icon" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                                            <path d="M20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h4l4 4 4-4h4c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                                        </svg>
                                        <span class="engagement-count">{{ $post->comments->count() }}</span>
                                    </button>
                                </div>
                                <button class="engagement-btn" onclick="showShareModal({{ $post->id }})">
                                    <svg class="icon" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                                        <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.50-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/>
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Yorum Alanı -->
                            <div class="comments-section" id="comments-{{ $post->id }}" style="display: none;">
                                <div class="comments-list" id="comments-list-{{ $post->id }}">
                                    <!-- Yorumlar buraya yüklenecek -->
                                </div>
                                
                                <!-- Yorum Formu -->
                                <form class="comment-form" onsubmit="submitComment(event, {{ $post->id }})">
                                    @csrf
                                    <div class="comment-input-group">
                                        @if(Auth::user()->avatar)
                                            <img src="{{ url('/media/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="comment-avatar">
                                        @else
                                            <div class="comment-avatar default">
                                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <input type="text" class="comment-input" placeholder="Yorum yazın..." required>
                                        <button type="submit" class="comment-submit-btn">
                                            <svg viewBox="0 0 24 24" style="width: 16px; height: 16px; fill: currentColor;">
                                                <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    @if($posts->hasPages())
                        <div style="padding: 20px; display: flex; justify-content: center;">
                            {{ $posts->links() }}
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg viewBox="0 0 24 24" style="width: 48px; height: 48px; color: var(--text-primary); fill: currentColor; opacity: 0.5;">
                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                            </svg>
                        </div>
                        <h3 class="empty-title">{{ $isOwnProfile ? 'Henüz gönderi paylaşmadınız' : 'Henüz gönderi yok' }}</h3>
                        <p class="empty-text">{{ $isOwnProfile ? 'İlk gönderinizi paylaşarak başlayın!' : 'Bu kullanıcı henüz gönderi paylaşmamış.' }}</p>
                        @if($isOwnProfile)
                            <a href="{{ route('home') }}" class="btn btn-primary" style="margin-top: 16px;">
                                İlk Gönderimi Paylaş
                            </a>
                        @endif
                    </div>
                @endif
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
                // Dark mode: Ay göster (next click will go to light)
                if (moonIcon) moonIcon.style.display = 'block';
                if (sunIcon) sunIcon.style.display = 'none';
                if (themeToggle) {
                    themeToggle.style.color = 'white';
                    themeToggle.querySelector('svg').style.fill = 'white';
                }
            } else {
                // Light mode: Güneş göster (next click will go to dark)
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
            initBackToTop();
        });

        // Back to Top Button
        function initBackToTop() {
            const backToTopBtn = document.getElementById('backToTop');
            
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTopBtn.classList.add('show');
                } else {
                    backToTopBtn.classList.remove('show');
                }
            });
            
            backToTopBtn.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        // Like functionality
        function toggleLike(button) {
            const postId = button.getAttribute('data-post-id');
            const countSpan = button.querySelector('.engagement-count');
            
            fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    countSpan.textContent = data.likes_count;
                    if (data.liked) {
                        button.classList.add('liked');
                    } else {
                        button.classList.remove('liked');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // Share modal functionality
        function showShareModal(postId) {
            const postUrl = `${window.location.origin}/posts/${postId}`;
            
            const modal = document.createElement('div');
            modal.className = 'share-modal';
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
            `;
            
            modal.innerHTML = `
                <div style="
                    background: var(--bg-primary);
                    border-radius: 16px;
                    padding: 24px;
                    max-width: 400px;
                    width: 90%;
                    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
                ">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                        <h3 style="margin: 0; color: var(--text-primary);">Paylaş</h3>
                        <button onclick="this.closest('.share-modal').remove()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--text-secondary);">×</button>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <button class="share-option-btn" onclick="shareToInstagram('${postUrl}')" style="display: flex; align-items: center; gap: 12px; padding: 16px; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 12px; color: var(--text-primary); cursor: pointer; transition: all 0.2s ease;">
                            <svg viewBox="0 0 24 24" style="width: 24px; height: 24px; fill: var(--text-primary);">
                                <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/>
                            </svg>
                            <span>Paylaş</span>
                        </button>
                        <button class="share-option-btn" onclick="copyLink('${postUrl}')" style="display: flex; align-items: center; gap: 12px; padding: 16px; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 12px; color: var(--text-primary); cursor: pointer; transition: all 0.2s ease;">
                            <svg viewBox="0 0 24 24" style="width: 24px; height: 24px; fill: var(--text-primary);">
                                <path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
                            </svg>
                            <span>Bağlantıyı Kopyala</span>
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.remove();
                }
            });
        }

        async function shareToInstagram(url) {
            // Modern Web Share API kullan
            if (navigator.share) {
                try {
                    await navigator.share({
                        title: 'Loop\'tan Gönderi',
                        text: 'Bu gönderiyi Loop\'ta gördüm!',
                        url: url
                    });
                    showToast('Paylaşım seçenekleri açıldı', 'success');
                } catch (error) {
                    if (error.name !== 'AbortError') {
                        // Fallback: Link kopyala
                        copyLink(url);
                    }
                }
            } else {
                // Web Share API desteklenmiyor, Instagram'a yönlendir
                copyLink(url);
                showToast('Link kopyalandı! Instagram\'da paylaşabilirsiniz.', 'info');
                // Instagram uygulamasını aç (mobil cihazlarda çalışır)
                setTimeout(() => {
                    window.open('instagram://story-camera', '_blank');
                }, 1000);
            }
        }

        function copyLink(url) {
            navigator.clipboard.writeText(url).then(function() {
                showToast('Bağlantı kopyalandı!', 'success');
            }).catch(function() {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = url;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showToast('Bağlantı kopyalandı!', 'success');
            });
        }

        // Yorum sistemi fonksiyonları
        function toggleComments(postId) {
            const commentsSection = document.getElementById(`comments-${postId}`);
            const isVisible = commentsSection.style.display !== 'none';
            
            if (isVisible) {
                commentsSection.style.display = 'none';
            } else {
                commentsSection.style.display = 'block';
                loadComments(postId);
            }
        }

        function loadComments(postId) {
            const commentsList = document.getElementById(`comments-list-${postId}`);
            
            fetch(`/posts/${postId}/comments`)
                .then(response => response.json())
                .then(data => {
                    commentsList.innerHTML = '';
                    data.comments.forEach(comment => {
                        const commentElement = createCommentElement(comment);
                        commentsList.appendChild(commentElement);
                    });
                })
                .catch(error => {
                    console.error('Error loading comments:', error);
                });
        }

        function createCommentElement(comment) {
            const commentDiv = document.createElement('div');
            commentDiv.className = 'comment-item';
            commentDiv.setAttribute('data-comment-id', comment.id);
            
            const avatarHtml = comment.user.avatar 
                ? `<img src="/media/${comment.user.avatar}" alt="${comment.user.name}" class="comment-avatar">`
                : `<div class="comment-avatar default">${comment.user.name.charAt(0).toUpperCase()}</div>`;
            
            const deleteButton = comment.user.id === {{ Auth::id() }} 
                ? `<button class="comment-delete-btn" onclick="deleteComment(${comment.id})" title="Yorumu sil">
                     <svg viewBox="0 0 24 24">
                         <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" fill="currentColor"/>
                     </svg>
                   </button>`
                : '';
            
            commentDiv.innerHTML = `
                ${avatarHtml}
                <div class="comment-content">
                    <div class="comment-header">
                        <span class="comment-author">${comment.user.name}</span>
                        <span class="comment-time">${getTimeAgo(comment.created_at)}</span>
                    </div>
                    <div class="comment-text">${comment.content}</div>
                </div>
                ${deleteButton}
            `;
            
            return commentDiv;
        }

        function getTimeAgo(dateString) {
            const now = new Date();
            const commentDate = new Date(dateString);
            const diffInSeconds = Math.floor((now - commentDate) / 1000);
            
            if (diffInSeconds < 60) return 'şimdi';
            if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} dakika önce`;
            if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} saat önce`;
            return `${Math.floor(diffInSeconds / 86400)} gün önce`;
        }

        function submitComment(event, postId) {
            event.preventDefault();
            
            const form = event.target;
            const input = form.querySelector('.comment-input');
            const content = input.value.trim();
            
            if (!content) return;
            
            fetch(`/posts/${postId}/comments`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    content: content
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    input.value = '';
                    loadComments(postId);
                    
                    // Yorum sayısını güncelle
                    const commentBtn = document.querySelector(`button[onclick="toggleComments(${postId})"]`);
                    const countSpan = commentBtn.querySelector('.engagement-count');
                    countSpan.textContent = data.comments_count;
                }
            })
            .catch(error => {
                console.error('Error submitting comment:', error);
            });
        }

        function deleteComment(commentId) {
            showConfirm('Bu yorumu silmek istediğinizden emin misiniz?', 'Yorumu Sil', () => {
                fetch(`/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const commentElement = document.querySelector(`[data-comment-id="${commentId}"]`);
                        if (commentElement) {
                            // Animate removal
                            commentElement.style.transition = 'all 0.3s ease';
                            commentElement.style.opacity = '0';
                            commentElement.style.transform = 'translateX(-10px)';
                            
                            setTimeout(() => {
                                commentElement.remove();
                            }, 300);
                        }
                        
                        // Update comment count using returned value
                        if (data.post_id) {
                            const commentBtn = document.querySelector(`button[onclick="toggleComments(${data.post_id})"]`);
                            if (commentBtn) {
                                const countSpan = commentBtn.querySelector('.engagement-count');
                                if (countSpan) {
                                    countSpan.textContent = data.comments_count || 0;
                                }
                            }
                        }
                        
                        showToast('Yorum silindi', 'success');
                    } else {
                        showToast('Yorum silinemedi', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error deleting comment:', error);
                    showToast('Bir hata oluştu', 'error');
                });
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

        function confirmDelete(event, message = 'Silmek istediğinizden emin misiniz?') {
            event.preventDefault();
            showConfirm(message, 'Sil', () => {
                event.target.closest('form').submit();
            });
            return false;
        }

        // Follow/Unfollow functionality
        async function toggleFollow(userId, shouldFollow) {
            const followBtn = document.querySelector('.follow-btn');
            const followersCountEl = document.querySelector('.stat-item:nth-child(2) .stat-number');
            
            try {
                const method = shouldFollow ? 'POST' : 'DELETE';
                const response = await fetch(`/follow/${userId}`, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Update button
                    if (shouldFollow) {
                        followBtn.innerHTML = `
                            <svg class="icon" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1L13.5 2.5L16.17 5.17L10.5 10.84L11.91 12.25L15.83 8.34L17.5 10H19V9H21ZM1 21V19L7 13L8.5 14.5L5.83 17.17L11.5 22.84L10.09 24.25L6.17 20.34L4.5 22H3V21H1Z"/>
                            </svg>
                            Takip Ediliyor
                        `;
                        followBtn.className = 'btn btn-secondary follow-btn';
                        followBtn.onclick = () => toggleFollow(userId, false);
                    } else {
                        followBtn.innerHTML = `
                            <svg class="icon" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                <path d="M19 13h-2v2h-2v-2h-2v-2h2V9h2v2h2v2z"/>
                            </svg>
                            Takip Et
                        `;
                        followBtn.className = 'btn btn-primary follow-btn';
                        followBtn.onclick = () => toggleFollow(userId, true);
                    }

                    // Update followers count
                    followersCountEl.textContent = data.followers_count;
                } else {
                    alert(data.error || 'Bir hata oluştu');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Bir hata oluştu');
            }
        }

        // Modal Functions
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
                { text: 'Sil', class: 'modal-btn-danger', action: onConfirm }
            ]);
        }

        // Preview mode toggle
        let isPreviewMode = false;

        function togglePreviewMode() {
            isPreviewMode = !isPreviewMode;
            const previewBtn = document.getElementById('previewBtn');
            const previewBtnText = document.getElementById('previewBtnText');
            const profileActions = document.querySelector('.profile-actions');
            
            if (isPreviewMode) {
                // Enter preview mode (show as other users see)
                previewBtnText.textContent = 'Normal Görünüm';
                previewBtn.classList.remove('btn-secondary');
                previewBtn.classList.add('btn-primary');
                
                // Hide edit buttons and show follow button instead
                profileActions.innerHTML = `
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <button class="btn btn-secondary" onclick="togglePreviewMode()" id="previewBtn">
                            <svg class="icon" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                            </svg>
                            <span id="previewBtnText">Normal Görünüm</span>
                        </button>
                        <button class="btn btn-primary follow-btn" disabled>
                            <svg class="icon" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                            </svg>
                            Takip Et (Önizleme)
                        </button>
                        <button class="btn btn-secondary" disabled>
                            <svg class="icon" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
                            </svg>
                            Mesaj Gönder (Önizleme)
                        </button>
                    </div>
                `;
                
                // Show preview badge
                const profileHeader = document.querySelector('.profile-header');
                if (!document.getElementById('previewBadge')) {
                    const badge = document.createElement('div');
                    badge.id = 'previewBadge';
                    badge.style.cssText = `
                        position: fixed;
                        top: 80px;
                        right: 20px;
                        background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
                        color: white;
                        padding: 12px 20px;
                        border-radius: 8px;
                        font-weight: 600;
                        font-size: 14px;
                        z-index: 1000;
                        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                        animation: slideIn 0.3s ease;
                    `;
                    badge.innerHTML = '👁️ Önizleme Modu';
                    document.body.appendChild(badge);
                }
                
            } else {
                // Exit preview mode (restore normal view)
                location.reload();
            }
        }

        // Delete post function
        function deletePost(postId) {
            showConfirm('Bu gönderiyi silmek istediğinizden emin misiniz?', 'Gönderiyi Sil', async () => {
                try {
                    const response = await fetch(`/posts/${postId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        // Remove post from DOM with animation
                        const postElement = document.querySelector(`[data-post-id="${postId}"]`);
                        if (postElement) {
                            postElement.style.transition = 'all 0.3s ease';
                            postElement.style.opacity = '0';
                            postElement.style.transform = 'translateX(-20px)';
                            
                            setTimeout(() => {
                                postElement.remove();
                                
                                // Check if there are no more posts
                                const postsContainer = document.querySelector('.posts-container');
                                const remainingPosts = postsContainer.querySelectorAll('.post-item');
                                if (remainingPosts.length === 0) {
                                    postsContainer.innerHTML = `
                                        <div class="empty-state" style="text-align: center; padding: 60px 20px; color: var(--text-secondary);">
                                            <svg viewBox="0 0 24 24" style="width: 64px; height: 64px; margin-bottom: 16px; opacity: 0.3; fill: currentColor;">
                                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z"/>
                                            </svg>
                                            <h3 style="font-size: 18px; margin-bottom: 8px;">Henüz gönderi yok</h3>
                                            <p style="font-size: 14px;">İlk gönderiyi paylaşarak başla!</p>
                                        </div>
                                    `;
                                }
                                
                                // Update post count
                                const postCountElement = document.querySelector('.section-title');
                                if (postCountElement) {
                                    const currentCount = parseInt(postCountElement.textContent.match(/\d+/)[0]) || 0;
                                    const newCount = Math.max(0, currentCount - 1);
                                    postCountElement.textContent = `${document.querySelector('.profile-name')?.textContent === '{{ Auth::user()->name }}' ? 'Gönderilerim' : 'Gönderileri'} (${newCount})`;
                                }
                            }, 300);
                        }
                        
                        // Show success message
                        showToast('Gönderi başarıyla silindi', 'success');
                    } else {
                        showToast(data.message || 'Gönderi silinirken bir hata oluştu', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast('Bir hata oluştu', 'error');
                }
            });
        }

        // Show toast notification
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
                color: white;
                padding: 16px 24px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 10000;
                animation: slideIn 0.3s ease;
                font-size: 14px;
                font-weight: 500;
            `;
            toast.textContent = message;
            
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
            `;
            document.head.appendChild(style);
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
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

    <!-- Back to Top Button -->
    <button id="backToTop" class="back-to-top" title="Yukarı Çık">
        <svg viewBox="0 0 24 24">
            <path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"/>
        </svg>
    </button>

    <script>
        // Update notification badge
        function updateNotificationBadge() {
            fetch('/api/notifications/unread-count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notificationBadge');
                    if (data.count > 0) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error fetching notification count:', error));
        }

        // Update badge on page load
        updateNotificationBadge();

        // Update badge every 30 seconds
        setInterval(updateNotificationBadge, 30000);

        // Listen for like/comment events to update badge
        document.addEventListener('click', function(e) {
            const likeButton = e.target.closest('[onclick*="toggleLike"]');
            const commentForm = e.target.closest('form');
            
            if (likeButton || (commentForm && commentForm.querySelector('[name="content"]'))) {
                setTimeout(updateNotificationBadge, 1000);
            }
        });
    </script>

</body>
</html>
