<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Merriweather:wght@700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<style>
    :root {
        --ink: #102a43;
        --ink-soft: #486581;
        --brand: #0f766e;
        --brand-deep: #0b5d57;
        --accent: #f59e0b;
        --paper: #f7fbff;
        --card: #ffffff;
        --line: #dce8f5;
        --shadow: 0 16px 40px rgba(16, 42, 67, 0.12);
        --radius: 16px;
    }

    * {
        box-sizing: border-box;
    }

    html, body {
        margin: 0;
        padding: 0;
        color: var(--ink);
        background: radial-gradient(circle at 15% -10%, #d7f5ef 0%, transparent 35%),
                    radial-gradient(circle at 100% 5%, #feeecf 0%, transparent 32%),
                    var(--paper);
        font-family: "Hind Siliguri", sans-serif;
        scroll-behavior: smooth;
    }

    a {
        text-decoration: none;
        color: inherit;
    }

    .container {
        width: min(1150px, 92%);
        margin: 0 auto;
    }

    .site-header {
        position: sticky;
        top: 0;
        z-index: 100;
        backdrop-filter: blur(8px);
        background: rgba(247, 251, 255, 0.9);
        border-bottom: 1px solid var(--line);
    }

    .header-wrap {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.85rem 0;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        font-weight: 700;
    }

    .logo-mark {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(145deg, #0f766e, #14b8a6);
        color: white;
        display: grid;
        place-items: center;
        box-shadow: var(--shadow);
    }

    .nav-links {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: var(--ink-soft);
        font-weight: 600;
        flex-wrap: wrap;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.7rem 1rem;
        border-radius: 12px;
        border: 1px solid transparent;
        font-weight: 700;
        transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
    }

    .btn-brand {
        background: var(--brand);
        color: #fff;
        box-shadow: 0 10px 20px rgba(15, 118, 110, 0.25);
    }

    .btn-light {
        border-color: var(--line);
        color: var(--ink);
        background: #fff;
    }

    .hero {
        padding: 4.2rem 0 2.2rem;
    }

    .hero-grid {
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 2rem;
        align-items: center;
    }

    .hero h1 {
        margin: 0;
        font-family: "Merriweather", serif;
        font-size: clamp(1.9rem, 4vw, 3rem);
        line-height: 1.2;
    }

    .hero p {
        margin: 1rem 0 1.5rem;
        color: var(--ink-soft);
        font-size: 1.05rem;
        max-width: 57ch;
    }

    .hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .chip {
        font-weight: 600;
        border: 1px solid var(--line);
        padding: 0.4rem 0.7rem;
        border-radius: 999px;
        background: #fff;
        color: var(--ink-soft);
    }

    .hero-card {
        background: linear-gradient(165deg, #073b4c, #0f766e);
        color: #fff;
        border-radius: 20px;
        padding: 1.4rem;
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
    }

    .hero-card::before {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        top: -80px;
        right: -60px;
    }

    .hero-card h3 {
        margin-top: 0;
        font-size: 1.15rem;
    }

    .hero-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.9rem;
        margin-top: 1rem;
    }

    .hero-stat {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        padding: 0.75rem;
    }

    .hero-stat strong {
        display: block;
        font-size: 1.2rem;
    }

    section {
        padding: 1.8rem 0;
    }

    .section-head {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .section-head h2 {
        margin: 0;
        font-family: "Merriweather", serif;
        font-size: clamp(1.3rem, 2.2vw, 1.95rem);
    }

    .section-head p {
        margin: 0;
        color: var(--ink-soft);
        max-width: 56ch;
    }

    .search-box {
        background: var(--card);
        border: 1px solid var(--line);
        box-shadow: var(--shadow);
        border-radius: var(--radius);
        padding: 1rem;
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(4, minmax(0, 1fr)) auto;
    }

    .two-col-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .form-panel {
        display: grid;
        gap: 0.9rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.85rem;
    }

    .results-shell {
        margin-top: 1rem;
    }

    .results-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
    }

    .result-card {
        display: grid;
        gap: 0.75rem;
    }

    .result-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
    }

    .result-top h3 {
        margin-top: 0.35rem;
    }

    .result-meta {
        font-size: 0.9rem;
        color: var(--ink-soft);
        font-weight: 600;
        white-space: nowrap;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.22rem 0.55rem;
        border-radius: 999px;
        background: #e0f2fe;
        color: #075985;
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: 0.02em;
    }

    .result-details {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        color: var(--ink-soft);
        font-size: 0.92rem;
    }

    .timeline {
        display: grid;
        gap: 0.85rem;
    }

    .timeline-item {
        display: grid;
        grid-template-columns: 18px 1fr;
        gap: 0.6rem;
        align-items: start;
    }

    .timeline-item b {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        margin-top: 0.25rem;
        background: var(--brand);
        box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.12);
    }

    .timeline-item span {
        color: var(--ink-soft);
    }

    .receipt-banner {
        margin-top: 1rem;
        border-left: 4px solid var(--brand);
    }

    .muted {
        color: var(--ink-soft);
    }

    .field {
        display: grid;
        gap: 0.35rem;
    }

    label {
        font-size: 0.92rem;
        color: var(--ink-soft);
        font-weight: 600;
    }

    input, select, textarea {
        width: 100%;
        border: 1px solid var(--line);
        border-radius: 10px;
        padding: 0.68rem 0.78rem;
        outline: none;
        font: inherit;
        background: #fff;
    }

    input:focus, select:focus, textarea:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.15);
    }

    .services-grid,
    .notices-grid,
    .contact-grid {
        display: grid;
        gap: 1rem;
    }

    .services-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    .notices-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .card {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 1rem;
        box-shadow: var(--shadow);
    }

    .service-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: linear-gradient(140deg, #dff7f3, #cffafe);
        display: grid;
        place-items: center;
        font-weight: 800;
        color: var(--brand-deep);
        margin-bottom: 0.75rem;
    }

    .card h3, .card h4 {
        margin: 0 0 0.35rem;
    }

    .card p {
        margin: 0;
        color: var(--ink-soft);
    }

    .notice-tag {
        display: inline-block;
        font-size: 0.75rem;
        font-weight: 700;
        color: #92400e;
        background: #fef3c7;
        border: 1px solid #fcd34d;
        border-radius: 999px;
        padding: 0.2rem 0.55rem;
        margin-bottom: 0.5rem;
    }

    #landMap {
        width: 100%;
        height: 320px;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid var(--line);
    }

    .workflow {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 0.9rem;
    }

    .step {
        position: relative;
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 14px;
        padding: 0.9rem;
    }

    .step b {
        display: inline-grid;
        place-items: center;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: var(--brand);
        color: #fff;
        margin-bottom: 0.5rem;
    }

    .step p {
        margin: 0;
        color: var(--ink-soft);
        font-size: 0.95rem;
    }

    .contact-grid {
        grid-template-columns: 1fr 1fr;
    }

    .contact-list {
        display: grid;
        gap: 0.7rem;
        color: var(--ink-soft);
    }

    .reveal {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .reveal.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .site-footer {
        margin-top: 1.5rem;
        border-top: 1px solid var(--line);
        padding: 1rem 0 1.5rem;
        color: var(--ink-soft);
        text-align: center;
    }

    @media (max-width: 1060px) {
        .services-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .workflow {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .search-box {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .results-grid,
        .two-col-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 820px) {
        .hero-grid,
        .contact-grid,
        .notices-grid {
            grid-template-columns: 1fr;
        }

        .header-wrap {
            align-items: flex-start;
            flex-direction: column;
        }

        .hero {
            padding-top: 2.8rem;
        }
    }

    @media (max-width: 620px) {
        .services-grid,
        .workflow,
        .search-box {
            grid-template-columns: 1fr;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .nav-links {
            width: 100%;
            justify-content: space-between;
        }
    }
</style>
