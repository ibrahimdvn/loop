<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Loop - Sosyal Medya Platformu</title>
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
            color: #6b6b6b;
            background: #f5f5f5;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .header {
            background: white;
            padding: 20px 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            text-decoration: none;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-img {
            width: 32px;
            height: 32px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .logo:hover .logo-img {
            transform: scale(1.05);
        }

        .auth-links {
            display: flex;
            gap: 20px;
        }

        .auth-links a {
            color: #6b6b6b;
            text-decoration: none;
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 4px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .auth-links a:hover {
            color: #333;
            background: rgba(0,0,0,0.05);
        }

        .hero {
            background: white;
            padding: 120px 0;
            text-align: center;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 300;
            margin-bottom: 20px;
            color: #333;
            letter-spacing: -1px;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: #6b6b6b;
            margin-bottom: 50px;
            font-weight: 400;
        }

        .hero p {
            font-size: 1rem;
            margin-bottom: 40px;
            color: #6b6b6b;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.8;
        }

        .cta-button {
            display: inline-block;
            background: #333;
            color: white;
            padding: 15px 30px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .cta-button:hover {
            background: #555;
        }

        .features {
            background: #f5f5f5;
            padding: 80px 0;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin-top: 50px;
        }

        .feature {
            text-align: center;
            padding: 40px 30px;
            background: white;
            border-radius: 0;
            transition: all 0.3s ease;
        }

        .feature:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            background: #333;
            border-radius: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            color: white;
            font-size: 20px;
        }

        .feature h3 {
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: #333;
            font-weight: 500;
        }

        .feature p {
            color: #6b6b6b;
            line-height: 1.6;
            font-size: 14px;
        }

        .section-title {
            text-align: center;
            font-size: 2rem;
            color: #333;
            margin-bottom: 20px;
            font-weight: 300;
        }

        .section-subtitle {
            text-align: center;
            color: #6b6b6b;
            font-size: 1rem;
            max-width: 500px;
            margin: 0 auto;
            line-height: 1.6;
        }


        .footer {
            background: #333;
            color: #6b6b6b;
            text-align: center;
            padding: 40px 0;
            font-size: 14px;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .footer-logo-img {
            width: 20px;
            height: 20px;
            object-fit: contain;
            filter: brightness(0) invert(1);
            transition: all 0.3s ease;
        }

        .footer-logo:hover .footer-logo-img {
            transform: scale(1.1);
        }

        .footer-logo span {
            font-size: 14px;
            font-weight: 400;
            color: white;
        }

        /* Animation Classes */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .fade-in.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .slide-in-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: all 0.6s ease;
        }

        .slide-in-left.animate {
            opacity: 1;
            transform: translateX(0);
        }

        .slide-in-right {
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.6s ease;
        }

        .slide-in-right.animate {
            opacity: 1;
            transform: translateX(0);
        }

        .scale-in {
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.6s ease;
        }

        .scale-in.animate {
            opacity: 1;
            transform: scale(1);
        }

        .stagger-delay-1 { transition-delay: 0.1s; }
        .stagger-delay-2 { transition-delay: 0.2s; }
        .stagger-delay-3 { transition-delay: 0.3s; }
        .stagger-delay-4 { transition-delay: 0.4s; }
        .stagger-delay-5 { transition-delay: 0.5s; }
        .stagger-delay-6 { transition-delay: 0.6s; }

        /* Header Animation */
        .header {
            transform: translateY(-100%);
            animation: slideDownHeader 0.8s ease forwards;
        }

        @keyframes slideDownHeader {
            to {
                transform: translateY(0);
            }
        }

        /* Button Hover Animation */
        .cta-button {
            position: relative;
            overflow: hidden;
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .cta-button:hover::before {
            left: 100%;
        }

        /* Navigation Link Hover */
        .auth-links a {
            position: relative;
        }

        .auth-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: #333;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .auth-links a:hover::after {
            width: 100%;
        }

        /* Feature Card Hover */
        .feature {
            transform: translateY(0);
            transition: all 0.4s ease;
        }

        .feature:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            transition: all 0.3s ease;
        }

        .feature:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero p {
                font-size: 1.1rem;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .nav {
                flex-direction: column;
                gap: 15px;
            }
            
            .header {
                position: relative;
                padding: 20px 0;
            }
            
            .hero {
                padding: 60px 0 40px;
            }
            }

            /* Custom Scrollbar */
            ::-webkit-scrollbar {
                width: 12px;
            }

            ::-webkit-scrollbar-track {
                background: #f5f5f5;
                border-radius: 6px;
            }

            ::-webkit-scrollbar-thumb {
                background: linear-gradient(180deg, #007bff, #0056b3);
                border-radius: 6px;
                border: 2px solid #f5f5f5;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: linear-gradient(180deg, #0056b3, #007bff);
            }

            ::-webkit-scrollbar-corner {
                background: #f5f5f5;
            }

            /* Firefox scrollbar */
            html {
                scrollbar-width: thin;
                scrollbar-color: #007bff #f5f5f5;
            }

            /* Back to Top Button */
            .back-to-top {
                position: fixed;
                bottom: 30px;
                right: 30px;
                width: 50px;
                height: 50px;
                background: #007bff;
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
                background: #0056b3;
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
        </style>
    </head>
<body>

    <header class="header">
        <div class="container">
            <nav class="nav">
                <a href="{{ url('/') }}" class="logo">
                    <img src="{{ asset('loop.png') }}" alt="Loop Logo" class="logo-img fade-in-up">
                    <span class="fade-in-up stagger-delay-1">Loop</span>
                </a>
                
            @if (Route::has('login'))
                    <div class="auth-links">
                    @auth
                            <a href="{{ url('/home') }}">
                                <svg viewBox="0 0 24 24" style="width: 16px; height: 16px; fill: currentColor;">
                                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                                </svg>
                                Anasayfa
                            </a>
                    @else
                            <a href="{{ route('login') }}">
                                <svg viewBox="0 0 24 24" style="width: 16px; height: 16px; fill: currentColor;">
                                    <path d="M11 7L9.6 8.4l2.6 2.6H2v2h10.2l-2.6 2.6L11 17l5-5-5-5zm9 12h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-8v2h8v14z"/>
                                </svg>
                                Giriş Yap
                            </a>
                        @if (Route::has('register'))
                                <a href="{{ route('register') }}">
                                    <svg viewBox="0 0 24 24" style="width: 16px; height: 16px; fill: currentColor;">
                                        <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                    </svg>
                                    Kayıt Ol
                                </a>
                        @endif
                    @endauth
                </div>
            @endif
            </nav>
                </div>
    </header>

    <main>
        <section class="hero">
            <div class="container">
                <h1 class="fade-in">Sosyal Çevrenizle Bağlantıda Kalın</h1>
                <p class="fade-in stagger-delay-2">Düşüncelerinizi paylaşın, arkadaşlarınızla mesajlaşın ve ilham verici içerikleri keşfedin. Loop. ile sosyal medya deneyiminizi yaşayın.</p>
                @guest
                    <a href="{{ route('register') }}" class="cta-button scale-in stagger-delay-4">Bize Katıl</a>
                @else
                    <a href="{{ url('/home') }}" class="cta-button scale-in stagger-delay-4">Loop'a Git</a>
                @endguest
                            </div>
        </section>

        <section class="features">
            <div class="container">
                <h2 class="section-title fade-in">Neden Loop?</h2>
                <p class="section-subtitle fade-in stagger-delay-1">Modern sosyal medya deneyimi için ihtiyacınız olan her şey bir arada</p>
                
                <div class="features-grid">
                    <div class="feature fade-in stagger-delay-1">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" style="width: 24px; height: 24px; fill: currentColor;">
                                <path d="M20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h4l4 4 4-4h4c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
                            </svg>
                        </div>
                        <h3>Anlık Mesajlaşma</h3>
                        <p>Arkadaşlarınızla gerçek zamanlı mesajlaşın. Hızlı, güvenli ve kullanıcı dostu chat deneyimi.</p>
                            </div>

                    <div class="feature">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" style="width: 24px; height: 24px; fill: currentColor;">
                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                            </svg>
                        </div>
                        <h3>İçerik Paylaşımı</h3>
                        <p>Düşüncelerinizi, fotoğraflarınızı ve deneyimlerinizi kolayca paylaşın. Sosyal çevrenizle bağlantıda kalın.</p>
                            </div>

                    <div class="feature">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" style="width: 24px; height: 24px; fill: currentColor;">
                                <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                            </svg>
                        </div>
                        <h3>Akıllı Bildirimler</h3>
                        <p>Önemli güncellemeleri kaçırmayın. Kişiselleştirilmiş bildirim sistemi ile her şeyden haberdar olun.</p>
                            </div>

                    <div class="feature">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" style="width: 24px; height: 24px; fill: currentColor;">
                                <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A1.5 1.5 0 0 0 18.54 8H16c-.8 0-1.54.37-2 1l-3 4v2h2l2.54-3.4L16.5 18H20zm-8.5 2A2.5 2.5 0 0 1 9 17.5c0-.65.25-1.25.65-1.7L12 13.5l2.35 2.3c.4.45.65 1.05.65 1.7A2.5 2.5 0 0 1 12.5 20zm-1-8.5A2.5 2.5 0 0 1 9 9c0-1.38 1.12-2.5 2.5-2.5S14 7.62 14 9a2.5 2.5 0 0 1-2.5 2.5z"/>
                            </svg>
                        </div>
                        <h3>Profil Yönetimi</h3>
                        <p>Kişisel profilinizi oluşturun, fotoğraflarınızı yükleyin ve dijital kimliğinizi yansıtın.</p>
                </div>

                    <div class="feature">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" style="width: 24px; height: 24px; fill: currentColor;">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                        <h3>Keşfet</h3>
                        <p>Trend konuları takip edin, yeni insanlarla tanışın ve ilgi alanlarınıza uygun içerikleri keşfedin.</p>
                    </div>

                    <div class="feature">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" style="width: 24px; height: 24px; fill: currentColor;">
                                <path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9c.83 0 1.5-.67 1.5-1.5 0-.39-.15-.74-.39-1.01-.23-.26-.38-.61-.38-.99 0-.83.67-1.5 1.5-1.5H16c2.76 0 5-2.24 5-5 0-4.42-4.03-8-9-8zm-5.5 9c-.83 0-1.5-.67-1.5-1.5S5.67 9 6.5 9 8 9.67 8 10.5 7.33 12 6.5 12zm3-4C8.67 8 8 7.33 8 6.5S8.67 5 9.5 5s1.5.67 1.5 1.5S10.33 8 9.5 8zm5 0c-.83 0-1.5-.67-1.5-1.5S13.67 5 14.5 5s1.5.67 1.5 1.5S15.33 8 14.5 8zm3 4c-.83 0-1.5-.67-1.5-1.5S16.67 9 17.5 9s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                            </svg>
                        </div>
                        <h3>Minimal Tasarım</h3>
                        <p>Sade, şık ve kullanıcı odaklı arayüz. İçerik odaklı deneyim için optimize edilmiş tasarım.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-logo">
                <img src="{{ asset('loop.png') }}" alt="Loop Logo" class="footer-logo-img">
                <span>&copy; 2025 Loop - Sosyal Medya Platformu. Tüm hakları saklıdır.</span>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for internal links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });


        // Intersection Observer for Animations
        const animateOnScroll = () => {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate');
                    }
                });
            }, observerOptions);

            // Observe all animation elements
            document.querySelectorAll('.fade-in, .slide-in-left, .slide-in-right, .scale-in').forEach(el => {
                observer.observe(el);
            });
        };

        // Initialize animations on page load
        document.addEventListener('DOMContentLoaded', () => {
            // Trigger hero animations immediately
            setTimeout(() => {
                document.querySelectorAll('.hero .fade-in, .hero .scale-in').forEach(el => {
                    el.classList.add('animate');
                });
            }, 300);

            // Initialize scroll animations
            animateOnScroll();
        });

        // Header background opacity on scroll
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.header');
            if (window.scrollY > 50) {
                header.style.background = 'rgba(255, 255, 255, 0.98)';
            } else {
                header.style.background = 'rgba(255, 255, 255, 0.95)';
            }
        });

        // Add animation to features on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.feature').forEach(feature => {
            feature.style.opacity = '0';
            feature.style.transform = 'translateY(30px)';
            feature.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(feature);
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

        // Initialize back to top on page load
        document.addEventListener('DOMContentLoaded', function() {
            initBackToTop();
        });
    </script>

    <!-- Back to Top Button -->
    <button id="backToTop" class="back-to-top" title="Yukarı Çık">
        <svg viewBox="0 0 24 24">
            <path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"/>
        </svg>
    </button>
    </body>
</html>