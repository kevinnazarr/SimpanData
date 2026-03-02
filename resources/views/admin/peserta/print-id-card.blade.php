<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card - {{ $peserta->nama }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        @page {
            size: 54mm 85.6mm;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            -webkit-print-color-adjust: exact;
        }
        .id-card-container {
            width: 54mm;
            height: 85.6mm;
            background: #ffffff;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            margin: 0 auto;
            box-shadow: 0 15px 35px rgba(16, 42, 67, 0.15);
        }

        .header-waves {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 36mm; 
            z-index: 1;
        }
        .wave-navy { fill: #102A43; }
        .wave-red { fill: #D32F2F; }

        .content-layer {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 0;
        }

        .logo-section {
            display: flex;
            justify-content: center;
            height: 8mm; 
            padding-top: 3mm; 
            margin-bottom: 1mm;
        }
        .logo-section img {
            max-height: 100%;
            width: 120px;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .photo-section {
            display: flex;
            justify-content: center;
            margin-bottom: 4mm; 
        }
        .photo-frame {
            width: 28mm; 
            height: 28mm;
            border-radius: 50%;
            background: #ffffff;
            padding: 1.25mm;
            box-shadow: 0 6px 15px rgba(16, 42, 67, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        .photo-frame::after {
            content: '';
            position: absolute;
            top: 1mm; left: 1mm; right: 1mm; bottom: 1mm;
            border: 0.5px solid rgba(16, 42, 67, 0.05);
            border-radius: 50%;
            pointer-events: none;
        }
        .photo-frame img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
        .initial-placeholder {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
            color: #102A43;
            font-size: 32pt; 
            font-weight: 800;
        }

        .info-section {
            text-align: center;
            padding: 0 4mm;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        .name-label {
            font-size: 11.5pt; 
            font-weight: 800;
            color: #102A43;
            margin-bottom: 0.5mm;
            text-transform: uppercase;
            letter-spacing: -0.2px;
            line-height: 1.15;
            max-height: 2.3em;
            overflow: hidden;
        }
        .school-label {
            font-size: 8.5pt; 
            font-weight: 500;
            color: #486581;
            margin-bottom: 2.5mm;
            line-height: 1.2;
        }
        .activity-type {
            display: inline-block;
            background: #102A43;
            color: #ffffff;
            padding: 0.8mm 3mm;
            border-radius: 4px;
            font-size: 7pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            align-self: center;
        }

        .validity-section {
            margin: auto 4mm 1.5mm 4mm;
            padding: 2.5mm 0;
            text-align: center;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #eef2f7;
            display: flex;
            flex-direction: column;
            gap: 0.5mm;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .validity-title {
            font-size: 5pt;
            font-weight: 700;
            color: #829ab1;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .validity-date {
            font-size: 8.5pt;
            font-weight: 650;
            color: #243b53;
        }

        .bottom-decor {
            height: 3mm;
            width: 210px;
            display: flex;
            align-items: stretch;
            gap: 1.5mm;
            padding: 0 0mm;
        }
        .decor-navy {
            flex: 1;
            background: #102A43;
            clip-path: polygon(0 0, 100% 0, 95% 100%, 0 100%);
        }
        .decor-red-group {
            display: flex;
            gap: 0.8mm;
            width: 15mm;
        }
        .decor-red-seg {
            flex: 1;
            background: #D32F2F;
            clip-path: polygon(25% 0, 100% 0, 75% 100%, 0 100%);
        }

        .bg-pattern {
            position: absolute;
            bottom: 3mm;
            left: 0;
            width: 100%;
            height: 70mm;
            opacity: 0.03;
            background-image: linear-gradient(#102A43 0.5px, transparent 0.5px), linear-gradient(90deg, #102A43 0.5px, transparent 0.5px);
            background-size: 3mm 3mm;
            z-index: 1;
        }

        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 12px 24px;
            background: #D32F2F;
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(211, 47, 47, 0.3);
            z-index: 100;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .print-btn:hover {
            background: #B71C1C;
            transform: translateY(-2px);
        }

        @media print {
            .print-btn { display: none; }
            body { background: none; margin: 0; padding: 0; display: block; }
            .id-card-container { box-shadow: none; border: none; margin: 0; }
        }
    </style>
</head>
<body>
    <div class="id-card-container">
        <svg class="header-waves" viewBox="0 0 54 32" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="navy-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#1a3a5a;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#102A43;stop-opacity:1" />
                </linearGradient>
            </defs>
            <path class="wave-red" d="M0 0 H54 V21 C40 21 10 31 0 25 V0Z" />
            <path fill="url(#navy-grad)" d="M0 0 H54 V18 C40 18 10 28 0 22 V0Z" />
            <path d="M0 20 C10 26 40 16 54 16" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="0.7" />
        </svg>

        <div class="bg-pattern"></div>

        <div class="content-layer">
            <div class="logo-section">
                <img src="{{ asset('storage/logo/Logo_GI.png') }}" alt="GI Logo">
            </div>

            <div class="photo-section">
                <div class="photo-frame">
                    @if($peserta->foto)
                        <img src="{{ asset('storage/' . $peserta->foto) }}" alt="Foto">
                    @else
                        <div class="initial-placeholder">
                            {{ strtoupper(substr($peserta->nama, 0, 1)) }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="info-section">
                <div class="name-label">{{ $peserta->nama }}</div>
                <div class="school-label">{{ $peserta->asal_sekolah_universitas }}</div>
                <div class="activity-type">{{ $peserta->jenis_kegiatan }}</div>
            </div>

            <div class="validity-section">
                <div class="validity-title">Masa Berlaku</div>
                <div class="validity-date">
                    {{ \Carbon\Carbon::parse($peserta->tanggal_mulai)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($peserta->tanggal_selesai)->format('d/m/Y') }}
                </div>
            </div>
        </div>
        <div class="bottom-decor">
            <div class="decor-navy"></div>
            <div class="decor-red-group">
                <div class="decor-red-seg"></div>
                <div class="decor-red-seg"></div>
            </div>
        </div>
    </div>

    <button class="print-btn" onclick="window.print()">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
        CETAK KARTU
    </button>
</body>
</html>
