<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InternLog — Logbook Magang Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary: #1A1C1E;
            --secondary: #6C7278;
            --tertiary: #B8422E;
            --neutral: #F7F5F2;
            --border: rgba(26, 28, 30, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Public Sans', sans-serif;
            background: var(--neutral);
            color: var(--primary);
            line-height: 1.5;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .container {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px 16px;
            padding: 18px 0;
            border-bottom: 1px solid var(--border);
        }

        .brand {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.82rem;
            letter-spacing: 0.24em;
            text-transform: uppercase;
            color: var(--secondary);
        }

        .nav-links {
            display: flex;
            flex-wrap: wrap;
            gap: 12px 16px;
            align-items: center;
            color: var(--secondary);
            font-size: 0.95rem;
        }

        .nav-links .cta {
            background: var(--primary);
            color: var(--neutral);
            padding: 9px 14px;
        }

        .hero {
            padding: 64px 0 48px;
            border-bottom: 1px solid var(--border);
        }

        .eyebrow {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: var(--secondary);
            margin-bottom: 14px;
        }

        h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(2.4rem, 4vw, 3.35rem);
            line-height: 1.05;
            margin: 0 0 16px;
            letter-spacing: -0.02em;
        }

        .lead {
            font-size: 1rem;
            color: var(--secondary);
            max-width: 560px;
            margin-bottom: 24px;
        }

        .hero-actions {
            display: flex;
            gap: 18px;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--neutral);
            padding: 12px 18px;
            font-weight: 600;
        }

        .btn-secondary {
            color: var(--primary);
            border-bottom: 1px solid rgba(26, 28, 30, .4);
            padding-bottom: 2px;
            font-weight: 600;
        }

        .editorial-visual {
            margin-top: 36px;
            border: 1px solid var(--border);
            padding: 18px;
            background: #fff;
        }

        .visual-frame {
            min-height: 360px;
            border: 1px solid var(--border);
            background: #ece7de;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: end;
            padding: 24px;
        }

        .visual-frame::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(26, 28, 30, 0.18), rgba(26, 28, 30, 0));
            pointer-events: none;
        }

        .visual-content {
            position: relative;
            z-index: 1;
            max-width: 420px;
            background: rgba(247, 245, 242, 0.94);
            border: 1px solid var(--border);
            padding: 18px 20px;
        }

        .visual-label {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            color: var(--secondary);
            margin-bottom: 8px;
        }

        .visual-title {
            font-weight: 700;
            font-size: 1.05rem;
            margin: 0 0 6px;
        }

        .visual-copy {
            margin: 0;
            color: var(--secondary);
            font-size: 0.95rem;
        }

        .problem-strip {
            background: var(--primary);
            color: var(--neutral);
            padding: 44px 0;
        }

        .problem-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .problem-label {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            color: rgba(247, 245, 242, 0.7);
            margin-bottom: 6px;
        }

        .section {
            padding: 70px 0;
            border-bottom: 1px solid var(--border);
        }

        .section h2 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.75rem;
            margin: 0 0 24px;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .step {
            border-top: 2px solid var(--primary);
            padding-top: 12px;
        }

        .step .num {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.72rem;
            color: var(--secondary);
        }

        .grid-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .card {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 20px;
            background: var(--neutral);
        }

        .card .label {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            color: var(--secondary);
        }

        .card h3 {
            margin: 8px 0 8px;
            font-size: 1.06rem;
        }

        .card p {
            margin: 0;
            color: var(--secondary);
            font-size: 0.95rem;
        }

        .quotes {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        .quote {
            border-left: 2px solid var(--primary);
            padding-left: 16px;
        }

        .quote p {
            margin: 0 0 8px;
            color: var(--secondary);
        }

        .quote strong {
            display: block;
        }

        .cta-box {
            text-align: center;
            padding: 72px 0 24px;
        }

        .cta-box h2 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2rem;
            margin: 0 0 10px;
        }

        .cta-box p {
            color: var(--secondary);
            margin: 0 0 22px;
        }

        .btn-accent {
            background: var(--tertiary);
            color: var(--neutral);
            padding: 12px 18px;
            font-weight: 600;
        }

        footer {
            border-top: 1px solid var(--border);
            padding: 16px 0 28px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 6px 12px;
            color: var(--secondary);
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            font-family: 'Space Grotesk', sans-serif;
        }

        /* ===================== BOTTOM NAV (mobile / Flutter-style) ===================== */
        .bottom-nav {
            display: none;
        }

        @media (max-width: 900px) {

            .problem-grid,
            .steps,
            .grid-cards,
            .quotes {
                grid-template-columns: 1fr;
            }

            .visual-frame {
                min-height: 300px;
            }
        }

        @media (max-width: 640px) {
            .container {
                padding: 0 16px;
            }

            .hero {
                padding: 40px 0 32px;
            }

            .section {
                padding: 44px 0;
            }

            .nav-links {
                font-size: 0.88rem;
            }

            .hero-actions {
                gap: 12px;
            }

            .editorial-visual {
                padding: 12px;
                margin-top: 24px;
            }

            .visual-frame {
                min-height: 240px;
                padding: 16px;
                align-items: stretch;
            }

            .visual-content {
                max-width: 100%;
                padding: 14px 16px;
            }

            .problem-strip {
                padding: 32px 0;
            }

            .quote {
                padding-left: 12px;
            }

            .cta-box {
                padding: 48px 0 20px;
            }

            .cta-box h2 {
                font-size: 1.5rem;
            }

            footer {
                justify-content: flex-start;
                padding-bottom: 24px;
            }

            /* Navbar links collapse: only brand + hamburger-free minimal top bar,
               real navigation moves to the bottom tab bar */
            .navbar {
                padding: 14px 0;
            }

            .navbar .nav-links a:not(.cta) {
                display: none;
            }

            .navbar .nav-links .cta {
                padding: 8px 12px;
                font-size: 0.82rem;
            }

            /* room so content isn't hidden behind the fixed bottom nav */
            body {
                padding-bottom: calc(64px + env(safe-area-inset-bottom));
            }

            .bottom-nav {
                display: flex;
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: 50;
                background: #fff;
                border-top: 1px solid var(--border);
                padding: 6px 6px calc(6px + env(safe-area-inset-bottom));
                box-shadow: 0 -6px 18px rgba(26, 28, 30, 0.06);
            }

            .bottom-nav-item {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 3px;
                padding: 6px 2px;
                color: var(--secondary);
                font-family: 'Space Grotesk', sans-serif;
                font-size: 0.62rem;
                letter-spacing: 0.02em;
                text-transform: uppercase;
                border: none;
                background: none;
                cursor: pointer;
            }

            .bottom-nav-item svg {
                width: 21px;
                height: 21px;
                stroke: var(--secondary);
                fill: none;
                stroke-width: 1.6;
                transition: stroke .15s ease;
            }

            .bottom-nav-item.active {
                color: var(--tertiary);
            }

            .bottom-nav-item.active svg {
                stroke: var(--tertiary);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <nav class="navbar">
            <div class="brand">InternLog</div>
            <div class="nav-links">
                <a href="#features">Fitur</a>
                <a href="#how">Cara Kerja</a>
                <a href="{{ route('login') }}">Masuk</a>
                <a href="{{ route('register') }}" class="cta">Mulai Sekarang</a>
            </div>
        </nav>

        <section class="hero" id="demo">
            <div class="eyebrow">Vol. 01 — Logbook Magang Digital</div>
            <h1>Satu Catatan Harian,<br>Seribu Bukti Kerja.</h1>
            <p class="lead">InternLog mencatat progres magang mahasiswa secara harian, terverifikasi langsung oleh
                mentor — tanpa spreadsheet, tanpa laporan yang tercecer.</p>
            <div class="hero-actions">
                <a href="#" class="btn-primary">Mulai Sekarang</a>
                <a href="#how" class="btn-secondary">Pelajari Lebih Lanjut →</a>
            </div>

            <div class="editorial-visual" aria-label="Ilustrasi editorial mahasiswa magang">
                <div class="visual-frame">
                    <div class="visual-content">
                        <div class="visual-label">Dokumenter Lapangan</div>
                        <h3 class="visual-title">Mahasiswa mencatat aktivitas harian di lokasi magang.</h3>
                        <p class="visual-copy">Foto dokumenter yang memberi nuansa serius, rapi, dan terpercaya untuk
                            halaman pemasaran.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="problem-strip">
            <div class="container problem-grid">
                <div>
                    <div class="problem-label">Masalah 01</div>
                    <p>Logbook Excel gampang hilang & tidak sinkron.</p>
                </div>
                <div>
                    <div class="problem-label">Masalah 02</div>
                    <p>Mentor kesulitan memantau progres harian.</p>
                </div>
                <div>
                    <div class="problem-label">Masalah 03</div>
                    <p>Rekap akhir magang dikerjakan manual, terburu-buru.</p>
                </div>
            </div>
        </section>

        <section class="section" id="how">
            <div class="container">
                <h2>Cara Kerja</h2>
                <div class="steps">
                    <div class="step">
                        <div class="num">01</div>
                        <h3>Tulis Logbook</h3>
                        <p>Catat aktivitas harian lengkap dengan foto dokumentasi.</p>
                    </div>
                    <div class="step">
                        <div class="num">02</div>
                        <h3>Mentor Memverifikasi</h3>
                        <p>Status berubah otomatis: Pending → Approved.</p>
                    </div>
                    <div class="step">
                        <div class="num">03</div>
                        <h3>Rekap Otomatis</h3>
                        <p>Laporan akhir tersusun otomatis, tinggal unduh PDF.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="features">
            <div class="container">
                <h2>Fitur Utama</h2>
                <div class="grid-cards">
                    <div class="card">
                        <div class="label">Setiap Hari</div>
                        <h3>Catat dalam Hitungan Menit</h3>
                        <p>Tulis aktivitas harian langsung dari HP, lengkap dengan foto bukti kerja.</p>
                    </div>
                    <div class="card">
                        <div class="label">Tanpa Ribet</div>
                        <h3>Satu Tempat untuk Semua Bukti</h3>
                        <p>Foto lapangan dan laporan PDF tersimpan rapi, tidak tercecer di chat atau email.</p>
                    </div>
                    <div class="card">
                        <div class="label">Selalu Update</div>
                        <h3>Mentor Tahu Progresmu Saat Itu Juga</h3>
                        <p>Setiap catatan baru langsung diketahui mentor, tanpa perlu menunggu ditanya.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <h2>Kutipan Singkat</h2>
                <div class="quotes">
                    <blockquote class="quote">
                        <p>“Nggak perlu lagi rekap manual di akhir magang, semua sudah tercatat rapi per hari.”</p>
                        <strong>— Mahasiswa Teknik Informatika</strong>
                    </blockquote>
                    <blockquote class="quote">
                        <p>“Saya bisa memantau progres bimbingan tanpa harus menunggu laporan mingguan.”</p>
                        <strong>— Dosen Pembimbing Lapangan</strong>
                    </blockquote>
                </div>
            </div>
        </section>

        <section class="cta-box">
            <div class="container">
                <h2>Mulai catat progres magangmu hari ini.</h2>
                <p>Gratis untuk mahasiswa dan dosen pembimbing.</p>
                <a href="{{ route('register') }}" class="btn-accent">Daftar Sekarang</a>
            </div>
        </section>

        <footer>
            <span>InternLog © 2026</span>
            <span>Dibangun untuk mahasiswa & mentor</span>
        </footer>
    </div>

    <!-- ===================== Mobile bottom navigation (Flutter-style) ===================== -->
    <nav class="bottom-nav" id="bottomNav" aria-label="Navigasi utama">
        <a href="#demo" class="bottom-nav-item" data-target="demo">
            <svg viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 11.5 12 4l9 7.5M5 10v9a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1v-9" />
            </svg>
            <span>Beranda</span>
        </a>
        <a href="#features" class="bottom-nav-item" data-target="features">
            <svg viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h10" />
            </svg>
            <span>Fitur</span>
        </a>
        <a href="#how" class="bottom-nav-item" data-target="how">
            <svg viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 3v3m0 12v3M4.2 4.2l2.1 2.1m11.4 11.4 2.1 2.1M3 12h3m12 0h3M4.2 19.8l2.1-2.1m11.4-11.4 2.1-2.1" />
            </svg>
            <span>Cara Kerja</span>
        </a>
        <a href="{{ route('login') }}" class="bottom-nav-item" data-target="login">
            <svg viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm-7 8a7 7 0 0 1 14 0" />
            </svg>
            <span>Masuk</span>
        </a>
        <a href="{{ route('register') }}" class="bottom-nav-item cta-item" data-target="register">
            <svg viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
            </svg>
            <span>Daftar</span>
        </a>
    </nav>

    <script>
        // Highlight active bottom-nav tab based on current hash (Flutter BottomNavigationBar behavior)
        (function() {
            var items = document.querySelectorAll('.bottom-nav-item[data-target]');

            function setActive() {
                var hash = (window.location.hash || '#demo').replace('#', '');
                items.forEach(function(item) {
                    var isHashLink = item.getAttribute('href').startsWith('#');
                    var target = item.getAttribute('data-target');
                    item.classList.toggle('active', isHashLink && target === hash);
                });
            }

            items.forEach(function(item) {
                item.addEventListener('click', function() {
                    if (item.getAttribute('href').startsWith('#')) {
                        items.forEach(function(i) {
                            i.classList.remove('active');
                        });
                        item.classList.add('active');
                    }
                });
            });

            window.addEventListener('hashchange', setActive);
            setActive();
        })();
    </script>
</body>

</html>
