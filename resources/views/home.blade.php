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
            --bg-primary: #f5f5f5;
            --bg-secondary: #ffffff;
            --bg-tertiary: #ffffff;
            --text-primary: #333333;
            --text-secondary: #6b6b6b;
            --text-muted: #6b6b6b;
            --border-color: #e0e0e0;
            --hover-bg: rgba(0,0,0,0.05);
            --shadow: 0 2px 10px rgba(0,0,0,0.1);
            --accent-color: #333333;
            --accent-hover: #555555;
            --nav-bg: #ffffff;
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
            font-weight: 400;
        }

        /* Top Navigation */
        .top-nav {
            position: sticky;
            top: 0;
            background: var(--nav-bg);
            border-bottom: 1px solid var(--border-color);
            z-index: 1000;
            padding: 12px 0;
            transition: background-color 0.3s ease;
            box-shadow: var(--shadow);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            display: grid;
            grid-template-columns: 200px 1fr 200px;
            align-items: center;
            gap: 20px;
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

        /* Footer Logo */
        .footer-logo-section {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--text-secondary);
        }

        .footer-logo-img {
            width: 16px;
            height: 16px;
            object-fit: contain;
            opacity: 0.7;
            transition: all 0.3s ease;
            filter: brightness(0) invert(0);
        }

        [data-theme="dark"] .footer-logo-img {
            filter: brightness(0) invert(1);
        }

        .footer-logo-section:hover .footer-logo-img {
            opacity: 1;
            transform: scale(1.1);
        }

        .search-box {
            flex: 1;
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 8px 16px 8px 40px;
            border: 2px solid var(--border-color);
            border-radius: 20px;
            background: var(--bg-tertiary);
            color: var(--text-primary);
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
            transform: scale(1);
        }

        .search-input:focus {
            background: var(--bg-secondary);
            border-color: var(--accent-color);
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(0,149,246,0.2);
        }

        .search-box.searching .search-input {
            animation: searchPulse 1.5s infinite;
        }

        @keyframes searchPulse {
            0%, 100% { 
                border-color: var(--accent-color);
                box-shadow: 0 0 0 0 rgba(0,149,246,0.4);
            }
            50% { 
                border-color: var(--accent-color);
                box-shadow: 0 0 0 8px rgba(0,149,246,0);
            }
        }

        .search-input::placeholder {
            color: var(--text-secondary);
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .search-box.searching .search-icon {
            color: var(--accent-color);
            animation: searchIconSpin 1s linear infinite;
        }

        @keyframes searchIconSpin {
            from { transform: translateY(-50%) rotate(0deg); }
            to { transform: translateY(-50%) rotate(360deg); }
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

        /* Stories Section */
        .stories-container {
            background: var(--bg-secondary);
            border-radius: 12px;
            margin-bottom: 20px;
            padding: 16px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .stories-scroll {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            overflow-y: hidden;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding: 4px;
        }

        .stories-scroll::-webkit-scrollbar {
            display: none;
        }

        .story-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }

        .story-item:hover {
            transform: scale(1.05);
        }

        .story-ring {
            width: 66px;
            height: 66px;
            border-radius: 50%;
            padding: 2px;
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            position: relative;
        }

        .story-ring.viewed {
            background: var(--border-color);
        }

        .add-story .story-ring {
            background: var(--border-color);
        }

        .story-avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: var(--bg-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: visible;
            border: 3px solid var(--bg-secondary);
            font-weight: 600;
            font-size: 18px;
            color: var(--text-primary);
            position: relative;
        }
        
        .story-avatar img {
            border-radius: 50%;
        }

        .story-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .add-story-icon {
            position: absolute;
            bottom: -5px;
            right: -5px;
            width: 28px;
            height: 28px;
            background: #0095f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 22px;
            font-weight: 300;
            line-height: 22px;
            padding-bottom: 2px;
            border: 4px solid var(--bg-secondary);
            z-index: 10;
        }

        .story-username {
            font-size: 12px;
            color: var(--text-primary);
            max-width: 70px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            text-align: center;
        }

        /* Search Results */
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--bg-secondary);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            margin-top: 8px;
            max-height: 400px;
            overflow-y: auto;
            z-index: 1000;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .search-results.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: all;
        }

        .search-result-item {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .search-result-item:hover {
            background: var(--hover-bg);
            transform: translateX(4px);
        }

        .search-result-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 12px;
        }

        .search-result-content {
            flex: 1;
        }

        .search-result-title {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
            margin-bottom: 4px;
        }

        .search-result-text {
            color: var(--text-secondary);
            font-size: 12px;
            line-height: 1.4;
        }

        .search-no-results {
            padding: 20px;
            text-align: center;
            color: var(--text-secondary);
            font-size: 14px;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
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

        .user-avatar {
            width: 32px;
            height: 32px;
            background: var(--bg-secondary);
            border: 2px solid var(--border-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            font-weight: 500;
            font-size: 13px;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .user-avatar:hover {
            border-color: var(--accent-color);
            transform: scale(1.05);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .logout-nav-btn {
            color: #dc3545 !important;
        }

        .logout-nav-btn:hover {
            background: rgba(220, 53, 69, 0.1) !important;
            color: #dc3545 !important;
        }

        .post-delete-btn {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .post-delete-btn:hover {
            background: rgba(220, 53, 69, 0.1);
            transform: scale(1.1);
        }

        /* Main Layout */
        .main-layout {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px;
            display: grid;
            grid-template-columns: 280px 1fr 320px;
            gap: 32px;
            align-items: start;
        }

        /* Left Sidebar */
        .left-sidebar {
            position: sticky;
            top: 100px;
        }

        .sidebar-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            transition: all 0.2s ease;
        }

        .sidebar-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px var(--shadow);
        }


        .profile-section {
            text-align: center;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 32px;
            margin: 0 auto 16px;
        }

        .profile-name {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .profile-stats {
            display: flex;
            justify-content: space-around;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--border-color);
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .stat-label {
            font-size: 12px;
            color: var(--text-secondary);
            margin-top: 2px;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-menu li {
            margin-bottom: 8px;
        }

        .nav-menu a {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 12px 16px;
            color: var(--text-primary);
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .nav-menu a:hover {
            background: var(--hover-bg);
        }

        .nav-menu a.active {
            background: rgba(0, 149, 246, 0.2) !important;
            color: #0095f6 !important;
            border-radius: 8px;
        }


        .nav-menu .icon {
            font-size: 20px;
            width: 24px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            min-height: 100vh;
        }

        /* Create Post */
        .create-post {
            background: var(--bg-secondary);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            margin-bottom: 24px;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .create-post-header {
            padding: 16px 20px;
            border-bottom: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .create-avatar {
            width: 44px;
            height: 44px;
            background: var(--bg-secondary);
            border: 2px solid var(--border-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            font-weight: 500;
            font-size: 16px;
            flex-shrink: 0;
            overflow: hidden;
        }

        .create-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .create-prompt {
            flex: 1;
            color: var(--text-secondary);
            font-size: 16px;
            cursor: default;
            user-select: none;
        }

        .create-form {
            padding: 0;
        }

        .form-row {
            padding: 0 20px;
            margin-bottom: 16px;
        }

        .form-input {
            width: 100%;
            border: none;
            outline: none;
            font-size: 16px;
            color: var(--text-primary);
            background: transparent;
            resize: none;
            font-family: inherit;
        }

        .form-input::placeholder {
            color: var(--text-secondary);
        }

        .title-input {
            font-weight: 600;
            font-size: 18px;
            padding: 16px 0;
            border-bottom: 2px solid var(--border-color);
        }

        .content-input {
            min-height: 100px;
            padding: 16px 0;
            line-height: 1.5;
        }

        .post-actions {
            padding: 16px 20px;
            border-top: 2px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .action-buttons {
            display: flex;
            gap: 16px;
        }

        .action-btn {
            background: none;
            border: none;
            color: #0095f6;
            font-size: 20px;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            background: rgba(0, 149, 246, 0.1);
        }

        .submit-btn {
            background: #1877f2 !important;
            color: #ffffff !important;
            border: none;
            padding: 8px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700 !important;
            cursor: pointer;
            transition: background 0.2s ease;
            text-shadow: none;
            opacity: 1 !important;
        }

        .submit-btn:hover {
            background: #0866ff !important;
            color: #ffffff !important;
        }

        .submit-btn:disabled {
            background: #b3d9ff !important;
            color: #ffffff !important;
            cursor: not-allowed;
            opacity: 0.6;
        }

        [data-theme="dark"] .submit-btn {
            background: #1877f2 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            opacity: 1 !important;
        }

        [data-theme="dark"] .submit-btn:hover {
            background: #0866ff !important;
            color: #ffffff !important;
        }

        /* Posts Feed */
        .posts-feed {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .post-card {
            background: var(--bg-secondary);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .post-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px var(--shadow);
        }


        .post-header {
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .author-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .author-avatar {
            width: 48px;
            height: 48px;
            background: var(--bg-secondary);
            border: 2px solid var(--border-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            font-weight: 500;
            font-size: 18px;
            flex-shrink: 0;
            overflow: hidden;
        }

        .author-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .author-details {
            display: flex;
            flex-direction: column;
        }

        .author-name {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .verified-badge-small {
            width: 16px;
            height: 16px;
            background: #1da1f2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .verified-badge-small svg {
            width: 10px;
            height: 10px;
            fill: white;
        }

        .post-time {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .post-menu-btn {
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.2s ease;
            font-size: 18px;
        }

        .post-menu-btn:hover {
            background: #f5f5f5;
        }

        .post-content {
            padding: 0 20px 16px;
        }

        .post-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .post-text {
            color: var(--text-primary);
            line-height: 1.5;
            margin-bottom: 16px;
        }

        .post-image {
            width: 100%;
            height: auto;
            max-height: 500px;
            object-fit: cover;
            border-radius: 8px;
        }

        .post-location {
            margin: 16px 0;
        }

        .location-card {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: background 0.2s ease;
        }

        .location-card:hover {
            background: #e9ecef;
        }

        .location-icon {
            width: 24px;
            height: 24px;
            color: #6c757d;
            flex-shrink: 0;
        }

        .location-info {
            flex: 1;
        }

        .location-name {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .location-address {
            font-size: 14px;
            color: var(--text-secondary);
        }

        [data-theme="dark"] .location-card {
            background: var(--bg-tertiary);
            border-color: var(--border-color);
        }

        [data-theme="dark"] .location-card:hover {
            background: var(--hover-bg);
        }

        .post-engagement {
            padding: 12px 20px;
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

        /* Yorum Sistemi */
        .comments-section {
            border-top: 1px solid var(--border-color);
            background: var(--bg-secondary);
            padding: 16px 20px;
        }

        .comments-list {
            margin-bottom: 16px;
            max-height: 300px;
            overflow-y: auto;
        }

        .comment-item {
            display: flex;
            gap: 12px;
            margin-bottom: 12px;
            padding: 8px 0;
        }

        .comment-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
            flex-shrink: 0;
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

        .comment-form {
            border-top: 1px solid var(--border-color);
            padding-top: 16px;
        }

        .comment-input-group {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .comment-input {
            flex: 1;
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 12px 16px;
            color: var(--text-primary);
            font-size: 14px;
            resize: none;
            height: 40px;
            max-height: 40px;
            overflow: hidden;
        }

        .comment-input:focus {
            outline: none;
            border-color: var(--accent-color);
        }

        .comment-submit-btn {
            background: var(--accent-color);
            color: white;
            border: none;
            border-radius: 20px;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            height: 40px;
            white-space: nowrap;
        }

        .comment-submit-btn:hover {
            background: #0056b3;
        }

        .comment-delete-btn {
            background: none;
            border: none;
            color: #ff4444;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s ease;
            opacity: 0;
        }

        .comment-delete-btn svg {
            fill: #ff4444;
        }

        .comment-item:hover .comment-delete-btn {
            opacity: 1;
        }

        .comment-delete-btn:hover {
            background: rgba(255, 68, 68, 0.1);
            color: #ff2222;
        }

        .comment-delete-btn:hover svg {
            fill: #ff2222;
        }

        .comment-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .comment-author-info {
            display: flex;
            align-items: center;
            gap: 8px;
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

        /* Toast Notification Styles */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 20000;
            pointer-events: none;
        }

        .toast {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateX(400px);
            opacity: 0;
            transition: all 0.3s ease;
            pointer-events: auto;
            min-width: 300px;
            max-width: 400px;
        }

        .toast.show {
            transform: translateX(0);
            opacity: 1;
        }

        .toast.success {
            border-left: 4px solid #28a745;
        }

        .toast.error {
            border-left: 4px solid #dc3545;
        }

        .toast.info {
            border-left: 4px solid #0095f6;
        }

        .toast-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .toast-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toast-close {
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .toast-close:hover {
            background: var(--hover-bg);
            color: var(--text-primary);
        }

        .toast-message {
            font-size: 14px;
            color: var(--text-secondary);
            line-height: 1.4;
        }

        .toast-progress {
            height: 3px;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 2px;
            margin-top: 12px;
            overflow: hidden;
        }

        .toast-progress-bar {
            height: 100%;
            background: #28a745;
            width: 100%;
            animation: toastProgress linear;
        }

        .toast.error .toast-progress-bar {
            background: #dc3545;
        }

        .toast.info .toast-progress-bar {
            background: var(--accent-color);
        }

        @keyframes toastProgress {
            from { width: 100%; }
            to { width: 0%; }
        }

        /* Right Sidebar */
        .right-sidebar {
            position: sticky;
            top: 100px;
        }

        .suggestions-card {
            margin-bottom: 16px;
        }

        .card-header {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 16px;
        }

        .suggestion-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 0;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .suggestion-item:hover {
            background: var(--hover-bg);
            padding: 8px 12px;
        }


        .suggestion-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .suggestion-avatar {
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
        }

        .suggestion-details {
            display: flex;
            flex-direction: column;
        }

        .suggestion-name {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
        }

        .suggestion-desc {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .follow-btn {
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 4px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .follow-btn:hover {
            background: #1877f2;
        }

        /* Trending Topics Styles */
        .trending-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .trending-item:last-child {
            border-bottom: none;
        }

        .trending-item:hover {
            background: var(--hover-bg);
            margin: 0 -12px;
            padding: 12px;
            border-radius: 8px;
        }

        .trending-info {
            flex: 1;
        }

        .trending-category {
            font-size: 11px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .trending-title {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
            margin-bottom: 2px;
        }

        .trending-posts {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .trending-icon {
            font-size: 16px;
            margin-left: 8px;
        }

        .trending-item {
            padding: 12px 0;
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .trending-item:hover {
            background: var(--hover-bg);
            margin: 0 -20px;
            padding-left: 20px;
            padding-right: 20px;
            border-radius: 8px;
        }


        .trending-item:last-child {
            border-bottom: none;
        }

        .trending-category {
            font-size: 12px;
            color: var(--text-secondary);
            margin-bottom: 2px;
        }

        .trending-title {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
            margin-bottom: 2px;
        }

        .trending-posts {
            font-size: 12px;
            color: var(--text-secondary);
        }

        /* Logout Button */
        .logout-form {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--border-color);
        }

        .logout-btn {
            width: 100%;
            background: var(--bg-tertiary);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: var(--hover-bg);
        }

        /* Alerts */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(76, 175, 80, 0.1);
            border: 1px solid rgba(76, 175, 80, 0.3);
            color: var(--text-primary);
        }

        .alert-error {
            background: rgba(244, 67, 54, 0.1);
            border: 1px solid rgba(244, 67, 54, 0.3);
            color: var(--text-primary);
        }

        /* Hidden File Input */
        .hidden-input {
            display: none;
        }

        .image-preview {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 12px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .main-layout {
                grid-template-columns: 1fr;
                padding: 16px;
                max-width: 600px;
            }

            .left-sidebar,
            .right-sidebar {
                display: none;
            }

            .search-box {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .nav-container {
                grid-template-columns: auto 1fr auto;
                gap: 12px;
                padding: 0 16px;
            }

            .search-box {
                max-width: none;
                margin: 0;
            }

            .nav-actions {
                gap: 12px;
            }

            .main-layout {
                padding: 12px;
            }

            .create-post,
            .post-card {
                border-radius: 0;
                border-left: none;
                border-right: none;
            }
        }

        /* SVG Icons */
        .icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            fill: currentColor;
            vertical-align: middle;
        }

        .icon-large {
            width: 24px;
            height: 24px;
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .post-card {
            animation: slideIn 0.3s ease-out;
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
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            z-index: 1000;
            opacity: 1;
            visibility: visible;
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
            opacity: 1;
            visibility: visible;
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

        /* User Search Results */
        .user-result {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .user-result:hover {
            background: var(--hover-bg);
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-avatar .default-avatar {
            width: 100%;
            height: 100%;
            background: var(--accent-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 18px;
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 2px;
        }

        .user-username {
            color: var(--text-secondary);
            font-size: 13px;
            margin-bottom: 4px;
        }

        .user-bio {
            color: var(--text-secondary);
            font-size: 12px;
            line-height: 1.3;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .user-stats {
            display: flex;
            flex-direction: column;
            gap: 2px;
            text-align: right;
        }

        .user-stats .stat {
            color: var(--text-secondary);
            font-size: 11px;
        }

        .verified-badge-small {
            width: 14px;
            height: 14px;
            background: #1da1f2;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 8px;
            font-weight: bold;
        }

        /* Info Modals */
        .info-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            animation: fadeIn 0.3s ease;
        }

        .info-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .info-modal-content {
            background: var(--bg-secondary);
            border-radius: 16px;
            padding: 32px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            transform: scale(0.9);
            animation: modalSlideIn 0.3s ease forwards;
        }

        .info-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border-color);
        }

        .info-modal-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .info-modal-close {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 24px;
            cursor: pointer;
            padding: 4px;
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .info-modal-close:hover {
            background: var(--hover-bg);
            color: var(--text-primary);
        }

        .info-modal-body {
            color: var(--text-primary);
            line-height: 1.6;
        }

        .info-modal-section {
            margin-bottom: 24px;
        }

        .info-modal-section h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 12px;
        }

        .info-modal-section p {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 12px;
        }

        .info-modal-list {
            list-style: none;
            padding: 0;
        }

        .info-modal-list li {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 8px;
            padding-left: 20px;
            position: relative;
        }

        .info-modal-list li::before {
            content: "•";
            color: var(--accent-color);
            position: absolute;
            left: 0;
        }

        .info-modal-footer {
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid var(--border-color);
            text-align: center;
        }

        .info-modal-footer p {
            font-size: 12px;
            color: var(--text-muted);
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
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset('loop.png') }}" alt="Loop Logo" class="logo-img">
                <span>Loop</span>
            </a>
            
            <div class="search-box">
                <svg class="search-icon icon" viewBox="0 0 24 24">
                    <path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"/>
                </svg>
                <input type="text" class="search-input" placeholder="Ara...">
                <div class="search-results" id="searchResults"></div>
                        </div>

            <div class="nav-actions">
                <a href="{{ route('home') }}" class="nav-btn active" title="Ana Sayfa">
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
                <a href="{{ route('profile.show') }}" class="user-avatar" title="{{ Auth::user()->name }}">
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar_url }}" alt="Profil">
                    @else
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    @endif
                </a>
                
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="nav-btn logout-nav-btn" title="Çıkış Yap" onclick="return confirmLogout(event)">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.59L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                        </svg>
                    </button>
                </form>
                </div>
            </div>
    </nav>

    <!-- Main Layout -->
    <div class="main-layout">
        <!-- Left Sidebar -->
        <aside class="left-sidebar">

            <!-- Navigation Menu -->
            <div class="sidebar-card">
                <ul class="nav-menu">
                    <li><a href="#" class="active">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M12 2.1l9 8.1v11.8h-6v-6h-6v6H3V10.2L12 2.1z"/>
                        </svg> Ana Sayfa</a></li>
                    <li><a href="{{ route('notifications.index') }}">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                        </svg> Bildirimler</a></li>
                    <li><a href="{{ route('messages.index') }}">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg> Mesajlar</a></li>
                    <li><a href="{{ route('reels.index') }}">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M10 8v8l5-4-5-4zm9-5H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z"/>
                        </svg> Akış</a></li>
                    <li><a href="{{ route('settings.index') }}">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M19.14,12.94c0.04-0.3,0.06-0.61,0.06-0.94c0-0.32-0.02-0.64-0.07-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.61 l-1.92-3.32c-0.12-0.22-0.37-0.29-0.59-0.22l-2.39,0.96c-0.5-0.38-1.03-0.7-1.62-0.94L14.4,2.81c-0.04-0.24-0.24-0.41-0.48-0.41 h-3.84c-0.24,0-0.43,0.17-0.47,0.41L9.25,5.35C8.66,5.59,8.12,5.92,7.63,6.29L5.24,5.33c-0.22-0.08-0.47,0-0.59,0.22L2.74,8.87 C2.62,9.08,2.66,9.34,2.86,9.48l2.03,1.58C4.84,11.36,4.8,11.69,4.8,12s0.02,0.64,0.07,0.94l-2.03,1.58 c-0.18,0.14-0.23,0.41-0.12,0.61l1.92,3.32c0.12,0.22,0.37,0.29,0.59,0.22l2.39-0.96c0.5,0.38,1.03,0.7,1.62,0.94l0.36,2.54 c0.05,0.24,0.24,0.41,0.48,0.41h3.84c0.24,0,0.44-0.17,0.47-0.41l0.36-2.54c0.59-0.24,1.13-0.56,1.62-0.94l2.39,0.96 c0.22,0.08,0.47,0,0.59-0.22l1.92-3.32c0.12-0.22,0.07-0.47-0.12-0.61L19.14,12.94z M12,15.6c-1.98,0-3.6-1.62-3.6-3.6 s1.62-3.6,3.6-3.6s3.6,1.62,3.6,3.6S13.98,15.6,12,15.6z"/>
                        </svg> Ayarlar</a></li>
                </ul>
        </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Stories Section -->
            <div class="stories-container">
                <div class="stories-scroll">
                    <!-- Add Your Story -->
                    <div class="story-item add-story" onclick="document.getElementById('storyUpload').click()">
                        <div class="story-ring">
                            <div class="story-avatar">
                                @if(Auth::user()->avatar)
                                    <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}">
                                @else
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                @endif
                                <div class="add-story-icon">+</div>
                            </div>
                        </div>
                        <span class="story-username">Hikaye Ekle</span>
                    </div>

                    <!-- Other Users' Stories (will be loaded via API) -->
                    <div id="storiesContainer">
                        <!-- Stories will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Hidden Story Upload Input -->
            <input type="file" id="storyUpload" accept="image/*,video/*" style="display: none;" onchange="uploadStory(this)">

            <!-- Error Messages -->
            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="list-style: none;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
    </div>
            @endif

            <!-- Create Post -->
            <div class="create-post">
                <div class="create-post-header">
                    <div class="create-avatar">
                        @if(Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}">
                        @else
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        @endif
</div>
                    <div class="create-prompt">Ne düşünüyorsun, {{ explode(' ', Auth::user()->name)[0] }}?</div>
                </div>
                
                <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="create-form">
                    @csrf
                    
                    <div class="form-row">
                        <input type="text" 
                               name="title" 
                               class="form-input title-input" 
                               placeholder="Başlık..." 
                               value="{{ old('title') }}" 
                               required>
                    </div>

                    <div class="form-row">
                        <textarea name="content" 
                                  class="form-input content-input" 
                                  placeholder="Düşüncelerini paylaş..." 
                                  required>{{ old('content') }}</textarea>
                    </div>

                    <div class="post-actions">
                        <div class="action-buttons">
                            <input type="file" id="image-upload" name="image" class="hidden-input" accept="image/*">
                            <input type="hidden" id="media-type" name="media_type" value="">
                            <input type="hidden" id="media-url" name="media_url" value="">
                            <input type="hidden" id="media-data" name="media_data" value="">
                            <button type="button" class="action-btn" onclick="document.getElementById('image-upload').click()" title="Fotoğraf ekle">
                                <svg class="icon" viewBox="0 0 24 24">
                                    <path d="M9 2L7.17 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2h-3.17L15 2H9zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
                                </svg>
                            </button>
                            <button type="button" class="action-btn" title="GIF ekle">
                                <svg class="icon" viewBox="0 0 24 24">
                                    <rect x="2" y="3" width="20" height="18" rx="2" ry="2" fill="none" stroke="currentColor" stroke-width="2"/>
                                    <text x="12" y="14" text-anchor="middle" font-size="8" font-weight="bold" fill="currentColor">GIF</text>
                                </svg>
                            </button>
                            <button type="button" class="action-btn" title="Konum">
                                <svg class="icon" viewBox="0 0 24 24">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                </svg>
                            </button>
                        </div>
                        <button type="submit" class="submit-btn" style="background: #1877f2 !important; color: #ffffff !important; font-weight: 700 !important; opacity: 1 !important;">Paylaş</button>
                    </div>

                    <div id="image-preview-container"></div>
                </form>
            </div>

            <!-- Posts Feed -->
            <div class="posts-feed">
                @forelse($posts as $post)
                    <article class="post-card">
                        <div class="post-header">
                            <div class="author-info">
                                <a href="{{ route('profile.show', $post->user->id) }}" class="author-avatar" style="text-decoration: none; color: inherit;">
                                    @if($post->user->avatar)
                                        <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}">
                                    @else
                                        {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                    @endif
                                </a>
                                <div class="author-details">
                                    <div class="author-name">
                                        <a href="{{ route('profile.show', $post->user->id) }}" style="text-decoration: none; color: inherit; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                                            {{ $post->user->name }}
                                            @if($post->user->is_verified)
                                                <div class="verified-badge-small" title="Doğrulanmış Hesap">
                                                    <svg viewBox="0 0 24 24">
                                                        <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </a>
                                    </div>
                                    <div class="post-time">{{ $post->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            
                            @if($post->user_id === Auth::id())
                                <form method="POST" action="{{ route('posts.destroy', $post) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Bu gönderiyi silmek istediğinizden emin misiniz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="post-delete-btn" title="Sil">
                                        <svg class="icon" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                            <path d="M6,19c0,1.1,0.9,2,2,2h8c1.1,0,2-0.9,2-2V7H6V19z M19,4h-3.5l-1-1h-5l-1,1H5v2h14V4z"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div class="post-content">
                            <h3 class="post-title">{{ $post->title }}</h3>
                            
                            @if($post->media_type === 'image' && $post->image)
                                <img src="{{ $post->getImageUrl('medium') }}" 
                                     alt="{{ $post->title }}" 
                                     class="post-image"
                                     loading="lazy"
                                     data-post-id="{{ $post->id }}">
                            @elseif($post->media_type === 'gif' && $post->media_url)
                                <img src="{{ $post->media_url }}" 
                                     alt="{{ $post->title }}" 
                                     class="post-image">
                            @elseif($post->media_type === 'location' && $post->media_data)
                                @php 
                                    $locationData = is_string($post->media_data) ? json_decode($post->media_data, true) : $post->media_data;
                                @endphp
                                <div class="post-location">
                                    <div class="location-card">
                                        <svg viewBox="0 0 24 24" class="location-icon">
                                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                        </svg>
                                        <div class="location-info">
                                            <div class="location-name">{{ $locationData['name'] ?? 'Konum' }}</div>
                                            <div class="location-address">{{ $locationData['address'] ?? '' }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="post-text">{{ $post->content }}</div>
                        </div>

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
                            <div class="comment-form">
                                <form class="add-comment-form" data-post-id="{{ $post->id }}">
                                    @csrf
                                    <div class="comment-input-group">
                                        <div class="comment-avatar">
                                            @if(Auth::user()->avatar)
                                                <img src="{{ url('/media/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                            @else
                                                {{ substr(Auth::user()->name, 0, 1) }}
                                            @endif
                                        </div>
                                        <textarea class="comment-input" placeholder="Yorum yazın..." required></textarea>
                                        <button type="submit" class="comment-submit-btn">Gönder</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="post-card" style="text-align: center; padding: 60px 20px;">
                        <div style="margin-bottom: 16px; opacity: 0.3;">
                            <svg class="icon" viewBox="0 0 24 24" style="width: 48px; height: 48px;">
                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                            </svg>
                        </div>
                        <h3 style="font-size: 20px; margin-bottom: 8px;">Henüz gönderi yok</h3>
                        <p style="color: var(--text-secondary);">İlk gönderiyi paylaşarak başla!</p>
                    </div>
                @endforelse
            </div>
        </main>

        <!-- Right Sidebar -->
        <aside class="right-sidebar">
            <!-- Suggestions -->
            <div class="sidebar-card suggestions-card">
                <div class="card-header">Takip edilesi hesaplar</div>
                
                @forelse($suggestedUsers as $user)
                    <div class="suggestion-item">
                        <div class="suggestion-info">
                            <div class="suggestion-avatar">
                                @if($user->avatar)
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                @else
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="suggestion-details">
                                <div class="suggestion-name">
                                    {{ $user->name }}
                                    @if($user->is_verified)
                                        <span style="color: #1da1f2; font-size: 12px;">✓</span>
                                    @endif
                                </div>
                                <div class="suggestion-desc">{{ $user->posts_count }} gönderi • {{ $user->followers_count }} takipçi</div>
                            </div>
                        </div>
                        <button class="follow-btn" onclick="toggleFollow({{ $user->id }}, true)">Takip Et</button>
                    </div>
                @empty
                    <div style="text-align: center; padding: 20px; color: var(--text-secondary); font-size: 14px;">
                        Henüz önerilecek kullanıcı yok
                    </div>
                @endforelse
            </div>

            <!-- Trending Topics -->
            <div class="sidebar-card">
                <div class="card-header">Gündem</div>
                <div id="trending-content">
                    @forelse($trendingTopics as $topic)
                        <div class="trending-item" style="cursor: pointer;" onclick="searchHashtag('{{ $topic['hashtag'] }}')">
                            <div class="trending-info">
                                <div class="trending-category">{{ $topic['category'] }}</div>
                                <div class="trending-title">#{{ $topic['hashtag'] }}</div>
                                <div class="trending-posts">{{ $topic['posts'] }}</div>
                            </div>
                            <div class="trending-icon">{{ $topic['trend'] ?? '📊' }}</div>
                        </div>
                    @empty
                        <!-- Gündem konuları yoksa boş bırak -->
                    @endforelse
                </div>
            </div>

            <!-- Footer Links -->
            <div class="sidebar-card" style="font-size: 12px; color: var(--text-secondary); line-height: 1.4;">
                <div style="margin-bottom: 12px;">
                    <a href="#" onclick="openAboutModal()" style="color: var(--text-secondary); text-decoration: none; margin-right: 12px; cursor: pointer;">Hakkında</a>
                    <a href="#" onclick="openHelpModal()" style="color: var(--text-secondary); text-decoration: none; margin-right: 12px; cursor: pointer;">Yardım</a>
                    <a href="#" onclick="openPrivacyModal()" style="color: var(--text-secondary); text-decoration: none; cursor: pointer;">Gizlilik</a>
                </div>
                <div class="footer-logo-section">
                    <img src="{{ asset('loop.png') }}" alt="Loop Logo" class="footer-logo-img">
                    <span>© 2025 Loop</span>
                </div>
            </div>
        </aside>
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

    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container"></div>

    <script>
        // Auto-resize textarea
        const contentTextarea = document.querySelector('.content-input');
        contentTextarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 200) + 'px';
        });

        // Media handling
        const imageInput = document.getElementById('image-upload');
        const imagePreviewContainer = document.getElementById('image-preview-container');
        let currentMediaType = null;
        let currentMediaData = null;

        // Image upload
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    showMediaPreview('image', e.target.result, null);
                    currentMediaType = 'image';
                    currentMediaData = null; // Fotoğraf için media_data kullanma
                };
                reader.readAsDataURL(file);
            }
        });

        // GIF button
        document.querySelector('.action-btn[title="GIF ekle"]').addEventListener('click', function() {
            showGifSelector();
        });

        // Location button
        document.querySelector('.action-btn[title="Konum"]').addEventListener('click', function() {
            getCurrentLocation();
        });

        function showMediaPreview(type, url, data) {
            imagePreviewContainer.innerHTML = '';
            
            const previewDiv = document.createElement('div');
            previewDiv.style.cssText = 'padding: 0 20px 16px; position: relative;';
            
            if (type === 'image') {
                const img = document.createElement('img');
                img.src = url;
                img.className = 'image-preview';
                previewDiv.appendChild(img);
            } else if (type === 'gif') {
                const img = document.createElement('img');
                img.src = url;
                img.className = 'image-preview';
                previewDiv.appendChild(img);
            } else if (type === 'location') {
                const locationDiv = document.createElement('div');
                locationDiv.style.cssText = 'background: #f0f0f0; padding: 16px; border-radius: 8px; display: flex; align-items: center; gap: 12px;';
                locationDiv.innerHTML = `
                    <svg viewBox="0 0 24 24" style="width: 24px; height: 24px; color: #666;">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                    <div>
                        <div style="font-weight: 600; color: #333;">${data.name}</div>
                        <div style="font-size: 14px; color: #666;">${data.address}</div>
                    </div>
                `;
                previewDiv.appendChild(locationDiv);
            }
            
            // Remove button
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.innerHTML = '×';
            removeBtn.style.cssText = 'position: absolute; top: 8px; right: 8px; background: rgba(0,0,0,0.7); color: white; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer; font-size: 16px;';
            removeBtn.onclick = function() {
                imagePreviewContainer.innerHTML = '';
                currentMediaType = null;
                currentMediaData = null;
                imageInput.value = '';
                updateHiddenInputs();
            };
            previewDiv.appendChild(removeBtn);
            
            imagePreviewContainer.appendChild(previewDiv);
            updateHiddenInputs();
        }

        function updateHiddenInputs() {
            document.getElementById('media-type').value = currentMediaType || '';
            document.getElementById('media-url').value = currentMediaData?.url || '';
            // Sadece GIF ve konum için media_data kullan, fotoğraf için kullanma
            if (currentMediaType === 'gif' || currentMediaType === 'location') {
                document.getElementById('media-data').value = currentMediaData ? JSON.stringify(currentMediaData) : '';
            } else {
                document.getElementById('media-data').value = '';
            }
        }

        function showGifSelector() {
            // Basit GIF seçici (gerçek uygulamada GIPHY API kullanılabilir)
            const gifs = [
                { url: 'https://media.giphy.com/media/3o7btPCcdNniyf0ArS/giphy.gif', title: 'Happy' },
                { url: 'https://media.giphy.com/media/26ufdipQqU2lhNA4g/giphy.gif', title: 'Laugh' },
                { url: 'https://media.giphy.com/media/l0MYt5jPR6QX5pnqM/giphy.gif', title: 'Love' },
                { url: 'https://media.giphy.com/media/3o6Zt4HU9n2J2x7Q8M/giphy.gif', title: 'Wow' },
                { url: 'https://media.giphy.com/media/26gspipWnu5Dz5WBi/giphy.gif', title: 'Sad' },
                { url: 'https://media.giphy.com/media/l0MYt5jPR6QX5pnqM/giphy.gif', title: 'Angry' }
            ];
            
            const modal = document.createElement('div');
            modal.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; display: flex; align-items: center; justify-content: center;';
            
            const modalContent = document.createElement('div');
            modalContent.style.cssText = 'background: white; padding: 20px; border-radius: 12px; max-width: 500px; width: 90%; max-height: 80%; overflow-y: auto;';
            
            modalContent.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="margin: 0;">GIF Seç</h3>
                    <button onclick="this.closest('.gif-modal').remove()" style="background: none; border: none; font-size: 24px; cursor: pointer;">×</button>
                </div>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                    ${gifs.map(gif => `
                        <div style="cursor: pointer; border-radius: 8px; overflow: hidden;" onclick="selectGif('${gif.url}', '${gif.title}')">
                            <img src="${gif.url}" style="width: 100%; height: 120px; object-fit: cover;">
                            <div style="padding: 8px; text-align: center; font-size: 12px;">${gif.title}</div>
                        </div>
                    `).join('')}
                </div>
            `;
            
            modal.className = 'gif-modal';
            modal.appendChild(modalContent);
            document.body.appendChild(modal);
            
            window.selectGif = function(url, title) {
                showMediaPreview('gif', url, { title });
                currentMediaType = 'gif';
                currentMediaData = { url, title };
                modal.remove();
            };
        }

        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        // Basit konum bilgisi (gerçek uygulamada Google Maps API kullanılabilir)
                        const locationData = {
                            name: 'Mevcut Konumum',
                            address: `${lat.toFixed(4)}, ${lng.toFixed(4)}`,
                            lat: lat,
                            lng: lng
                        };
                        
                        showMediaPreview('location', null, locationData);
                        currentMediaType = 'location';
                        currentMediaData = locationData;
                    },
                    function(error) {
                        alert('Konum alınamadı: ' + error.message);
                    }
                );
            } else {
                alert('Tarayıcınız konum özelliğini desteklemiyor.');
            }
        }

        // Form validation
        const form = document.querySelector('.create-form');
        const submitBtn = document.querySelector('.submit-btn');
        const titleInput = document.querySelector('.title-input');

        function validateForm() {
            const title = titleInput.value.trim();
            const content = contentTextarea.value.trim();
            
            if (title && content) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }

        titleInput.addEventListener('input', validateForm);
        contentTextarea.addEventListener('input', validateForm);

        // Initial validation
        validateForm();

        // Form submit event
        document.querySelector('.create-form').addEventListener('submit', function(e) {
            // Hidden input'ları güncelle
            updateHiddenInputs();
        });

        // Theme management
        function toggleTheme() {
            const currentTheme = localStorage.getItem('theme') || 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            // Update theme icon
            updateThemeIcon(newTheme);
            
            // Force update submit button color
            const submitBtn = document.querySelector('.submit-btn');
            if (submitBtn) {
                submitBtn.style.color = 'white';
            }
            
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

        // Initialize theme on page load
        function initTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            
            // Update theme icon
            updateThemeIcon(savedTheme);
            
            // Force submit button color on page load
            setTimeout(() => {
                const submitBtn = document.querySelector('.submit-btn');
                if (submitBtn) {
                    submitBtn.style.color = 'white';
                }
            }, 100);
        }

        // Listen for theme changes from other tabs/pages
        window.addEventListener('storage', function(e) {
            if (e.key === 'theme') {
                document.documentElement.setAttribute('data-theme', e.newValue);
                updateThemeIcon(e.newValue);
            }
        });

        // Listen for theme changes from same tab
        window.addEventListener('themeChanged', function(e) {
            document.documentElement.setAttribute('data-theme', e.detail);
            updateThemeIcon(e.detail);
        });

        // Initialize theme immediately
        initTheme();
        
        // Smooth scroll and animations
        document.addEventListener('DOMContentLoaded', function() {
            const posts = document.querySelectorAll('.post-card');
            posts.forEach((post, index) => {
                post.style.animationDelay = (index * 0.1) + 's';
            });
        });

        // Search functionality
        const searchInput = document.querySelector('.search-input');
        const searchBox = document.querySelector('.search-box');
        const searchResults = document.getElementById('searchResults');
        let searchTimeout;

        // Real-time search
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            if (query.length === 0) {
                hideSearchResults();
                return;
            }

            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Show searching animation
            searchBox.classList.add('searching');
            
            // Debounce search
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 500);
        });

        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchBox.contains(e.target)) {
                hideSearchResults();
            }
        });

        // Search user function
        function searchUser(username) {
            console.log('Searching for user:', username);
            
            // Show searching animation
            searchBox.classList.add('searching');
            
            // Make AJAX request to search user
            fetch(`/search/user/${encodeURIComponent(username)}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Search result:', data);
                searchBox.classList.remove('searching');
                
                if (data.success && data.user) {
                    displayUserResult(data.user);
                } else {
                    displayNoResults(data.message || 'Kullanıcı bulunamadı');
                }
            })
            .catch(error => {
                console.error('User search error:', error);
                searchBox.classList.remove('searching');
                displayNoResults('Arama sırasında hata oluştu');
            });
        }

        // Display user search result
        function displayUserResult(user) {
            const searchResults = document.getElementById('searchResults');
            
            searchResults.innerHTML = `
                <div class="search-result-item user-result" onclick="goToUserProfile(${user.id})">
                    <div class="user-avatar">
                        ${user.avatar ? 
                            `<img src="/storage/${user.avatar}" alt="${user.name}">` : 
                            `<div class="default-avatar">${user.name.charAt(0).toUpperCase()}</div>`
                        }
                    </div>
                    <div class="user-info">
                        <div class="user-name">
                            ${user.name}
                            ${user.is_verified ? '<span class="verified-badge-small">✓</span>' : ''}
                        </div>
                        <div class="user-username">@${user.username}</div>
                        ${user.bio ? `<div class="user-bio">${user.bio}</div>` : ''}
                    </div>
                    <div class="user-stats">
                        <div class="stat">${user.posts_count} gönderi</div>
                        <div class="stat">${user.followers_count} takipçi</div>
                    </div>
                </div>
            `;
            
            searchResults.style.display = 'block';
        }

        // Go to user profile
        function goToUserProfile(userId) {
            window.location.href = `/profile/${userId}`;
        }

        // Hashtag arama fonksiyonu
        function searchHashtag(hashtag) {
            searchInput.value = '#' + hashtag;
            performSearch('#' + hashtag);
        }

        // Hashtag post arama fonksiyonu
        function searchHashtagPosts(hashtag) {
            // Sayfayı yenile ve hashtag ile filtrele
            window.location.href = `/?hashtag=${encodeURIComponent(hashtag)}`;
        }

        // Perform search
        function performSearch(query) {
            // Check if query starts with @ (user search)
            if (query.startsWith('@')) {
                const username = query.substring(1).toLowerCase();
                searchUser(username);
                return;
            }
            
            // Check if query starts with # (hashtag search)
            if (query.startsWith('#')) {
                const hashtag = query.substring(1);
                searchHashtagPosts(hashtag);
                return;
            }
            
            // For non-@ queries, also search for users
            searchUser(query);
        }

        // Hide search results
        function hideSearchResults() {
            const searchResults = document.getElementById('searchResults');
            searchResults.style.display = 'none';
        }

        // Toggle like functionality
        function toggleLike(button) {
            const postId = button.getAttribute('data-post-id');
            const countSpan = button.querySelector('.engagement-count');
            const currentCount = parseInt(countSpan.textContent);
            const isLiked = button.classList.contains('liked');
            
            // Optimistic update
            if (isLiked) {
                button.classList.remove('liked');
                countSpan.textContent = Math.max(0, currentCount - 1);
            } else {
                button.classList.add('liked');
                countSpan.textContent = currentCount + 1;
            }
            
            // Send AJAX request
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
                // Revert on error
                if (isLiked) {
                    button.classList.add('liked');
                    countSpan.textContent = currentCount;
                } else {
                    button.classList.remove('liked');
                    countSpan.textContent = currentCount;
                }
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

        function showAlert(message, title = 'Bilgi') {
            showModal(title, message, [
                { text: 'Tamam', class: 'modal-btn-primary' }
            ]);
        }

        function showConfirm(message, title = 'Onay', onConfirm = null) {
            showModal(title, message, [
                { text: 'İptal', class: 'modal-btn-secondary' },
                { text: 'Onayla', class: 'modal-btn-danger', action: onConfirm }
            ]);
        }

        // Override native alert and confirm
        window.customAlert = window.alert;
        window.customConfirm = window.confirm;
        
        window.alert = function(message) {
            showAlert(message);
        };
        
        window.confirm = function(message) {
            return new Promise((resolve) => {
                showConfirm(message, 'Onay', () => resolve(true));
                // Add cancel button action
                const cancelBtn = document.querySelector('.modal-btn-secondary');
                if (cancelBtn) {
                    cancelBtn.onclick = () => {
                        hideModal();
                        resolve(false);
                    };
                }
            });
        };

        // Custom confirm functions
        function confirmLogout(event) {
            event.preventDefault();
            showConfirm('Çıkış yapmak istediğinizden emin misiniz?', 'Çıkış Yap', () => {
                event.target.closest('form').submit();
            });
            return false;
        }

        function confirmDelete(event, message = 'Silmek istediğinizden emin misiniz?') {
            event.preventDefault();
            showConfirm(message, 'Sil', () => {
                event.target.closest('form').submit();
            });
            return false;
        }

        // Toast Functions
        function showToast(message, type = 'success', duration = 4000) {
            const container = document.getElementById('toastContainer');
            const toastId = 'toast_' + Date.now();
            
            const icons = {
                success: '<svg viewBox="0 0 24 24" style="width: 16px; height: 16px; fill: currentColor;"><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/></svg>',
                error: '<svg viewBox="0 0 24 24" style="width: 16px; height: 16px; fill: currentColor;"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>',
                info: '<svg viewBox="0 0 24 24" style="width: 16px; height: 16px; fill: currentColor;"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>'
            };
            
            const titles = {
                success: 'Başarılı',
                error: 'Hata',
                info: 'Bilgi'
            };
            
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.id = toastId;
            toast.innerHTML = `
                <div class="toast-header">
                    <div class="toast-title">
                        ${icons[type]}
                        ${titles[type]}
                    </div>
                    <button class="toast-close" onclick="removeToast('${toastId}')">
                        <svg viewBox="0 0 24 24" style="width: 14px; height: 14px; fill: currentColor;">
                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                        </svg>
                    </button>
                </div>
                <div class="toast-message">${message}</div>
                <div class="toast-progress">
                    <div class="toast-progress-bar" style="animation-duration: ${duration}ms;"></div>
                </div>
            `;
            
            container.appendChild(toast);
            
            // Show toast
            setTimeout(() => {
                toast.classList.add('show');
            }, 100);
            
            // Auto remove
            setTimeout(() => {
                removeToast(toastId);
            }, duration);
        }

        function removeToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.classList.remove('show');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }
        }

        // Check for success messages from server
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ session('success') }}', 'success');
            });
        @endif

        @if(session('error'))
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ session('error') }}', 'error');
            });
        @endif

        // Yorum Sistemi
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
            fetch(`/posts/${postId}/comments`)
                .then(response => response.json())
                .then(data => {
                    const commentsList = document.getElementById(`comments-list-${postId}`);
                    commentsList.innerHTML = '';
                    
                    if (data.comments.length === 0) {
                        commentsList.innerHTML = '<div style="text-align: center; color: var(--text-secondary); padding: 20px;">Henüz yorum yok</div>';
                    } else {
                        data.comments.forEach(comment => {
                            const commentElement = createCommentElement(comment);
                            commentsList.appendChild(commentElement);
                        });
                    }
                })
                .catch(error => {
                    console.error('Yorumlar yüklenirken hata:', error);
                });
        }

        function createCommentElement(comment) {
            const commentDiv = document.createElement('div');
            commentDiv.className = 'comment-item';
            commentDiv.setAttribute('data-comment-id', comment.id);
            
            const avatar = comment.user.avatar ? 
                `<img src="/media/${comment.user.avatar}" alt="${comment.user.name}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">` :
                comment.user.name.charAt(0).toUpperCase();
            
            const timeAgo = getTimeAgo(comment.created_at);
            const isOwnComment = comment.user.id === {{ Auth::id() }};
            
            commentDiv.innerHTML = `
                <div class="comment-avatar">${avatar}</div>
                <div class="comment-content">
                    <div class="comment-header">
                        <div class="comment-author-info">
                            <span class="comment-author">${comment.user.name}</span>
                            <span class="comment-time">${timeAgo}</span>
                        </div>
                        ${isOwnComment ? `<button class="comment-delete-btn" onclick="deleteComment(${comment.id}, ${comment.post_id})" title="Yorumu Sil">
                            <svg viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" fill="currentColor"/>
                            </svg>
                        </button>` : ''}
                    </div>
                    <div class="comment-text">${comment.content}</div>
                </div>
            `;
            
            return commentDiv;
        }

        function getTimeAgo(dateString) {
            const now = new Date();
            const date = new Date(dateString);
            const diffInSeconds = Math.floor((now - date) / 1000);
            
            if (diffInSeconds < 60) return 'şimdi';
            if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} dakika önce`;
            if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} saat önce`;
            return `${Math.floor(diffInSeconds / 86400)} gün önce`;
        }

        // Yorum form submit
        document.addEventListener('submit', function(e) {
            if (e.target.classList.contains('add-comment-form')) {
                e.preventDefault();
                
                const form = e.target;
                const postId = form.dataset.postId;
                const content = form.querySelector('.comment-input').value.trim();
                
                if (!content) return;
                
                const formData = new FormData();
                formData.append('content', content);
                formData.append('_token', form.querySelector('input[name="_token"]').value);
                
                fetch(`/posts/${postId}/comments`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Yorumu listeye ekle
                        const commentsList = document.getElementById(`comments-list-${postId}`);
                        const commentElement = createCommentElement(data.comment);
                        commentsList.appendChild(commentElement);
                        
                        // Formu temizle
                        form.querySelector('.comment-input').value = '';
                        
                        // Yorum sayısını güncelle
                        const commentBtn = document.querySelector(`button[onclick="toggleComments(${postId})"]`);
                        const countSpan = commentBtn.querySelector('.engagement-count');
                        countSpan.textContent = data.comment_count;
                        
                        // Eğer "Henüz yorum yok" mesajı varsa kaldır
                        const noCommentsMsg = commentsList.querySelector('div[style*="text-align: center"]');
                        if (noCommentsMsg) {
                            noCommentsMsg.remove();
                        }
                    }
                })
                .catch(error => {
                    console.error('Yorum eklenirken hata:', error);
                    showToast('Yorum eklenirken bir hata oluştu', 'error');
                });
            }
        });

        // Paylaş Modal
        function showShareModal(postId) {
            const modal = document.createElement('div');
            modal.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; display: flex; align-items: center; justify-content: center;';
            
            const modalContent = document.createElement('div');
            modalContent.style.cssText = 'background: var(--bg-primary); padding: 30px; border-radius: 16px; max-width: 400px; width: 90%; border: 1px solid var(--border-color);';
            
            const postUrl = `${window.location.origin}/posts/${postId}`;
            
            modalContent.innerHTML = `
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
            `;
            
            modal.className = 'share-modal';
            modal.appendChild(modalContent);
            document.body.appendChild(modal);
            
            // Modal dışına tıklayınca kapat
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

        // Yorum silme fonksiyonu
        function deleteComment(commentId, postId) {
            showConfirm('Bu yorumu silmek istediğinizden emin misiniz?', 'Sil', () => {
                fetch(`/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Yorumu DOM'dan kaldır
                        const commentElement = document.querySelector(`[data-comment-id="${commentId}"]`);
                        if (commentElement) {
                            commentElement.remove();
                        }
                        
                        // Yorum sayısını güncelle
                        const commentBtn = document.querySelector(`button[onclick="toggleComments(${postId})"]`);
                        const countSpan = commentBtn.querySelector('.engagement-count');
                        countSpan.textContent = data.comment_count;
                        
                        showToast('Yorum silindi', 'success');
                    }
                })
                .catch(error => {
                    console.error('Yorum silinirken hata:', error);
                    showToast('Yorum silinirken bir hata oluştu', 'error');
                });
            });
        }

        // Back to Top Button
        function initBackToTop() {
            const backToTopBtn = document.getElementById('backToTop');
            
            if (!backToTopBtn) {
                console.error('Back to top button not found');
                return;
            }
            
            // Test için butonu her zaman görünür yap
            backToTopBtn.classList.add('show');
            
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

        // Initialize back to top on page load
        document.addEventListener('DOMContentLoaded', function() {
            initBackToTop();
        });


        // Info Modal Functions
        function openAboutModal() {
            document.getElementById('aboutModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeAboutModal() {
            document.getElementById('aboutModal').classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        function openHelpModal() {
            document.getElementById('helpModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeHelpModal() {
            document.getElementById('helpModal').classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        function openPrivacyModal() {
            document.getElementById('privacyModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closePrivacyModal() {
            document.getElementById('privacyModal').classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('info-modal')) {
                e.target.classList.remove('show');
                document.body.style.overflow = 'auto';
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const openModal = document.querySelector('.info-modal.show');
                if (openModal) {
                    openModal.classList.remove('show');
                    document.body.style.overflow = 'auto';
                }
            }
        });
        // Follow/Unfollow functionality for suggestions
        async function toggleFollow(userId, shouldFollow) {
            const followBtn = event.target;
            const suggestionItem = followBtn.closest('.suggestion-item');
            
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
                        followBtn.textContent = 'Takip Ediliyor';
                        followBtn.style.background = '#6c757d';
                        followBtn.onclick = () => toggleFollow(userId, false);
                        
                        // Remove from suggestions after a delay
                        setTimeout(() => {
                            if (suggestionItem) {
                                suggestionItem.style.opacity = '0.5';
                                suggestionItem.style.pointerEvents = 'none';
                            }
                        }, 1000);
                    } else {
                        followBtn.textContent = 'Takip Et';
                        followBtn.style.background = '#0095f6';
                        followBtn.onclick = () => toggleFollow(userId, true);
                    }
                } else {
                    alert(data.error || 'Bir hata oluştu');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Bir hata oluştu');
            }
        }
    </script>

    <!-- Info Modals -->
    <!-- About Modal -->
    <div id="aboutModal" class="info-modal">
        <div class="info-modal-content">
            <div class="info-modal-header">
                <h2 class="info-modal-title">
                    <svg viewBox="0 0 24 24" style="width: 24px; height: 24px; fill: var(--accent-color);">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                    </svg>
                    Hakkında
                </h2>
                <button class="info-modal-close" onclick="closeAboutModal()">×</button>
            </div>
            <div class="info-modal-body">
                <div class="info-modal-section">
                    <h3>Loop Nedir?</h3>
                    <p>Loop, modern sosyal medya deneyimi sunan yenilikçi bir platformdur. Kullanıcıların düşüncelerini, deneyimlerini ve yaratıcılıklarını paylaşabilecekleri güvenli bir ortam sağlar.</p>
                </div>
                <div class="info-modal-section">
                    <h3>Özellikler</h3>
                    <ul class="info-modal-list">
                        <li>Gönderi paylaşımı ve etkileşim</li>
                        <li>Gerçek zamanlı mesajlaşma</li>
                        <li>Bildirim sistemi</li>
                        <li>Profil yönetimi</li>
                        <li>Güvenli ve özel hesap ayarları</li>
                    </ul>
                </div>
                <div class="info-modal-section">
                    <h3>Misyonumuz</h3>
                    <p>İnsanları birbirine bağlamak, yaratıcılığı desteklemek ve pozitif bir topluluk oluşturmak için çalışıyoruz.</p>
                </div>
            </div>
            <div class="info-modal-footer">
                <p>© 2025 Loop - Tüm hakları saklıdır</p>
            </div>
        </div>
    </div>

    <!-- Help Modal -->
    <div id="helpModal" class="info-modal">
        <div class="info-modal-content">
            <div class="info-modal-header">
                <h2 class="info-modal-title">
                    <svg viewBox="0 0 24 24" style="width: 24px; height: 24px; fill: var(--accent-color);">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/>
                    </svg>
                    Yardım
                </h2>
                <button class="info-modal-close" onclick="closeHelpModal()">×</button>
            </div>
            <div class="info-modal-body">
                <div class="info-modal-section">
                    <h3>Sık Sorulan Sorular</h3>
                    <div class="info-modal-section">
                        <h4>Hesabımı nasıl oluştururum?</h4>
                        <p>Ana sayfadaki "Kayıt Ol" butonuna tıklayarak kolayca hesap oluşturabilirsiniz.</p>
                    </div>
                    <div class="info-modal-section">
                        <h4>Gönderi nasıl paylaşırım?</h4>
                        <p>Ana sayfadaki "Düşüncelerini paylaş..." alanına yazınızı yazın ve "Paylaş" butonuna tıklayın.</p>
                    </div>
                    <div class="info-modal-section">
                        <h4>Profil fotoğrafımı nasıl değiştiririm?</h4>
                        <p>Profil sayfanızda "Profili Düzenle" butonuna tıklayarak profil fotoğrafınızı güncelleyebilirsiniz.</p>
                    </div>
                </div>
                <div class="info-modal-section">
                    <h3>İletişim</h3>
                    <p>Daha fazla yardıma ihtiyacınız varsa bizimle iletişime geçebilirsiniz:</p>
                    <ul class="info-modal-list">
                        <li>E-posta: ibrahimcanduven1@gmail.com</li>
                        <li>Telefon: +90 (505) 065 66 31</li>
                        <li>Adres: Balıkesir, Türkiye</li>
                    </ul>
                </div>
            </div>
            <div class="info-modal-footer">
                <p>7/24 destek hizmetimizden yararlanabilirsiniz</p>
            </div>
        </div>
    </div>

    <!-- Privacy Modal -->
    <div id="privacyModal" class="info-modal">
        <div class="info-modal-content">
            <div class="info-modal-header">
                <h2 class="info-modal-title">
                    <svg viewBox="0 0 24 24" style="width: 24px; height: 24px; fill: var(--accent-color);">
                        <path d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M12,7C13.4,7 14.8,8.6 14.8,10V11H16V16H8V11H9.2V10C9.2,8.6 10.6,7 12,7M12,8.2C11.2,8.2 10.4,8.7 10.4,10V11H13.6V10C13.6,8.7 12.8,8.2 12,8.2Z"/>
                    </svg>
                    Gizlilik
                </h2>
                <button class="info-modal-close" onclick="closePrivacyModal()">×</button>
            </div>
            <div class="info-modal-body">
                <div class="info-modal-section">
                    <h3>Gizlilik Politikamız</h3>
                    <p>Loop olarak, kullanıcılarımızın gizliliğini korumak bizim için en önemli önceliktir. Bu politika, kişisel bilgilerinizin nasıl toplandığını, kullanıldığını ve korunduğunu açıklar.</p>
                </div>
                <div class="info-modal-section">
                    <h3>Toplanan Bilgiler</h3>
                    <ul class="info-modal-list">
                        <li>Hesap oluştururken verdiğiniz kişisel bilgiler</li>
                        <li>Profil fotoğrafı ve biyografi bilgileri</li>
                        <li>Paylaştığınız gönderiler ve içerikler</li>
                        <li>Platform kullanım verileri</li>
                    </ul>
                </div>
                <div class="info-modal-section">
                    <h3>Bilgilerin Kullanımı</h3>
                    <p>Toplanan bilgiler sadece aşağıdaki amaçlarla kullanılır:</p>
                    <ul class="info-modal-list">
                        <li>Hizmet kalitesini artırmak</li>
                        <li>Kişiselleştirilmiş deneyim sunmak</li>
                        <li>Güvenlik ve güvenilirlik sağlamak</li>
                        <li>Yasal yükümlülükleri yerine getirmek</li>
                    </ul>
                </div>
                <div class="info-modal-section">
                    <h3>Veri Güvenliği</h3>
                    <p>Bilgilerinizi korumak için endüstri standardı güvenlik önlemleri alıyoruz. Verileriniz şifrelenir ve güvenli sunucularda saklanır.</p>
                </div>
                <div class="info-modal-section">
                    <h3>Haklarınız</h3>
                    <p>KVKK kapsamında aşağıdaki haklara sahipsiniz:</p>
                    <ul class="info-modal-list">
                        <li>Kişisel verilerinize erişim hakkı</li>
                        <li>Yanlış bilgilerin düzeltilmesi</li>
                        <li>Verilerin silinmesi</li>
                        <li>Veri işlemeye itiraz etme</li>
                    </ul>
                </div>
            </div>
            <div class="info-modal-footer">
                <p>Son güncelleme: Ocak 2025</p>
            </div>
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

        // Update badge when a new notification arrives (you can call this from your notification system)
        window.updateNotificationBadge = updateNotificationBadge;

        // Increment badge immediately when like/comment happens
        function incrementNotificationBadge() {
            const badge = document.getElementById('notificationBadge');
            const currentCount = parseInt(badge.textContent) || 0;
            const newCount = currentCount + 1;
            
            badge.textContent = newCount > 99 ? '99+' : newCount;
            badge.style.display = 'flex';
            
            // Also update from server to ensure accuracy
            setTimeout(updateNotificationBadge, 500);
        }

        // Make it globally accessible so other functions can call it
        window.incrementNotificationBadge = incrementNotificationBadge;

        // Story System
        function loadStories() {
            fetch('/api/stories/active')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.users.length > 0) {
                        const container = document.getElementById('storiesContainer');
                        container.innerHTML = data.users.map(user => `
                            <div class="story-item" onclick="viewStory(${user.id})">
                                <div class="story-ring ${user.viewed ? 'viewed' : ''}">
                                    <div class="story-avatar">
                                        ${user.avatar 
                                            ? `<img src="/media/${user.avatar}" alt="${user.name}">`
                                            : user.name.charAt(0).toUpperCase()
                                        }
                                    </div>
                                </div>
                                <span class="story-username">${user.username || user.name}</span>
                            </div>
                        `).join('');
                    }
                })
                .catch(error => console.error('Error loading stories:', error));
        }

        function viewStory(userId) {
            window.location.href = `/stories/${userId}`;
        }

        function uploadStory(input) {
            if (!input.files || !input.files[0]) return;

            const file = input.files[0];
            const formData = new FormData();
            formData.append('media', file);
            formData.append('type', file.type.startsWith('video') ? 'video' : 'image');

            // Show loading
            const addStory = document.querySelector('.add-story');
            const originalHTML = addStory.innerHTML;
            addStory.innerHTML = '<div style="width: 66px; height: 66px; border-radius: 50%; background: var(--bg-tertiary); display: flex; align-items: center; justify-content: center;"><div style="width: 24px; height: 24px; border: 3px solid var(--accent-color); border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div></div><span class="story-username">Yükleniyor...</span>';

            fetch('/stories', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Story upload response:', data);
                if (data.success) {
                    showToast('Hikaye paylaşıldı!', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showToast(data.message || 'Hikaye yüklenemedi', 'error');
                }
                addStory.innerHTML = originalHTML;
            })
            .catch(error => {
                console.error('Error uploading story:', error);
                showToast('Bir hata oluştu: ' + error.message, 'error');
                addStory.innerHTML = originalHTML;
            });
        }

        // Toast Notification
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                top: 80px;
                right: 20px;
                background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
                color: white;
                padding: 16px 24px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                z-index: 10000;
                animation: slideIn 0.3s ease;
                font-size: 14px;
                font-weight: 500;
                max-width: 300px;
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
            `;
            
            document.head.appendChild(style);
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideIn 0.3s ease reverse';
                setTimeout(() => {
                    if (toast.parentNode) {
                        document.body.removeChild(toast);
                    }
                    if (style.parentNode) {
                        document.head.removeChild(style);
                    }
                }, 300);
            }, 3000);
        }

        // Load stories on page load
        loadStories();
    </script>

</body>
</html>