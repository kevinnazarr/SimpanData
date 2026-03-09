<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card - {{ $peserta->nama }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&display=swap" rel="stylesheet">
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
            background-image: url('{{ asset("storage/images/template-id-card.png") }}');
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            box-sizing: border-box;
            background-color: #ffffff;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .photo-wrapper {
            position: absolute;
            top: 20.5%; 
            left: 50%;
            transform: translateX(-50%);
            width: 28mm; 
            height: 28mm;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 2mm solid transparent; 
            background-clip: padding-box;
        }
        
        .photo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .initial-placeholder {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
            color: #d32f2f;
            font-size: 32pt; 
            font-weight: 800;
        }

        .name-label {
            position: absolute;
            top: 62%;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 11pt;
            font-weight: 800;
            color: #000000;
            text-transform: uppercase;
            padding: 0 3mm;
            box-sizing: border-box;
            line-height: 1.1;
        }

        .school-label {
            position: absolute;
            top: 67%;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 7.5pt;
            font-weight: 700;
            color: #000000;
            text-transform: uppercase;
            padding: 0 4mm;
            box-sizing: border-box;
        }

        .id-label {
            position: absolute;
            top: 76%;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 8pt;
            font-weight: 500;
            color: #000000;
        }

        .date-label {
            position: absolute;
            top: 83.3%;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 6.5pt;
            font-weight: 700;
            color: #000000;
        }
    </style>
</head>
<body>
    <div class="id-card-container">
        
        <div class="photo-wrapper">
            @if($peserta->foto)
                <img src="{{ asset('storage/' . $peserta->foto) }}" alt="Foto">
            @else
                <div class="initial-placeholder">
                    {{ strtoupper(substr($peserta->nama, 0, 1)) }}
                </div>
            @endif
        </div>

        <div class="name-label">{{ $peserta->nama }}</div>
        <div class="school-label">{{ $peserta->asal_sekolah_universitas }}</div>
        <div class="id-label">ID: {{ $peserta->id }}</div>
            
        <div class="date-label">
            {{ \Carbon\Carbon::parse($peserta->tanggal_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($peserta->tanggal_selesai)->format('d/m/Y') }}
        </div>

    </div>
</body>
</html>
