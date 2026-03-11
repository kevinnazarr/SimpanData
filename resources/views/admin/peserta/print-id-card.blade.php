@php
    $isPdf = $isPdf ?? false;

    $getImg = function($path) use ($isPdf) {
        if ($isPdf) {
            $fullPath = public_path($path);
            if (file_exists($fullPath)) {
                $type = pathinfo($fullPath, PATHINFO_EXTENSION);
                $data = file_get_contents($fullPath);
                return 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }
        return asset($path);
    };
    
    $bgUrl = $getImg('storage/background/ID-Card-Template.png');
    $photoUrl = ($peserta->user && $peserta->user->photo_profile) 
        ? $getImg('storage/' . $peserta->user->photo_profile) 
        : null;

    $words = explode(' ', trim($peserta->nama));
    $firstWord = $words[0] ?? '';
    $rest = '';
    if (count($words) > 1) {
        $secondWord = $words[1];
        $abbreviations = [];
        for ($i = 2; $i < count($words); $i++) {
            $abbreviations[] = strtoupper(substr($words[$i], 0, 1)) . '.';
        }
        $rest = $secondWord . (count($abbreviations) > 0 ? ' ' . implode(' ', $abbreviations) : '');
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card - {{ $peserta->nama }}</title>
    @if(!$isPdf)
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @endif
    <style>
        @page {
            size: 54mm 85.6mm;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: {{ $isPdf ? 'Helvetica, Arial, sans-serif' : "'Plus Jakarta Sans', sans-serif" }};
            background-color: {{ $isPdf ? 'transparent' : '#f3f4f6' }};
            -webkit-print-color-adjust: exact;
        }

        .id-card-wrapper {
            @if(!$isPdf)
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            @endif
        }

        .id-card-container {
            width: 54mm;
            height: 85.6mm;
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
            background-color: #fff;
            @if(!$isPdf)
            box-shadow: 0 15px 35px rgba(16, 42, 67, 0.15);
            margin: 0 auto;
            @endif
        }

        .background-template {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .content-layer {
            position: relative;
            width: 100%;
            height: 100%;
            z-index: 10;
        }

        .photo-section {
            position: absolute;
            top: 16.7mm;
            left: 16.7mm;
            width: 23mm;
            height: 23mm;
            text-align: center;
        }

        .photo-frame {
            width: 23mm;
            height: 23mm;
            display: block;
            border-radius: 50%;
            overflow: hidden;
            background-color: #f0f4f8;
        }

        .photo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .info-section {
            position: absolute;
            top: 46mm;
            width: 100%;
            text-align: center;
            padding: 0 4mm;
            box-sizing: border-box;
        }

        .name-container {
            margin-bottom: 4mm;
            line-height:1.2;
        }

        .name-first {
            font-size: 14pt;
            font-weight: 800;
            color: #1a1a1b;
            text-transform: uppercase;
        }

        .name-rest {
            font-size: 14pt;
            font-weight: 800;
            color: #b71c1c;
            text-transform: uppercase;
        }

        .details-container {
            width: 100%;
            margin-top: 1mm;
            text-align: left;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .details-table td {
            font-size: 7.5pt;
            padding: 0.6mm 0;
            vertical-align: top;
            color: #1a1a1b;
        }

        .label-col {
            font-weight: bold;
            width: 12mm;
            text-transform: uppercase;
        }

        .separator-col {
            width: 2mm;
            text-align: center;
            font-weight: bold;
        }

        .value-col {
            font-weight: 500;
            color: #4b5563;
        }

        .initial-placeholder {
            width: 100%; 
            height: 100%; 
            line-height: 23mm; 
            text-align: center; 
            background: #f0f4f8; 
            font-size: 24pt; 
            color: #102A43; 
            font-weight: 800; 
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div class="id-card-wrapper">
        <div class="id-card-container">
            <img src="{{ $bgUrl }}" class="background-template" alt="BG">

            <div class="content-layer">
                <div class="photo-section">
                    <div class="photo-frame">
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}" alt="Foto">
                        @else
                            <div class="initial-placeholder">
                                {{ strtoupper(substr($peserta->nama, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="info-section">
                    <div class="name-container">
                        <span class="name-first">{{ $firstWord }}</span>
                        @if($rest)
                            <span class="name-rest">{{ $rest }}</span>
                        @endif
                    </div>

                    <div class="details-container">
                        <table class="details-table">
                            <tr>
                                <td class="label-col">ID</td>
                                <td class="separator-col">:</td>
                                <td class="value-col">#{{ str_pad($peserta->id, 4, '0', STR_PAD_LEFT) }}</td>
                            </tr>
                            <tr>
                                <td class="label-col">ASAL</td>
                                <td class="separator-col">:</td>
                                <td class="value-col">{{ $peserta->asal_sekolah_universitas }}</td>
                            </tr>
                            <tr>
                                <td class="label-col">BERAKHIR</td>
                                <td class="separator-col">:</td>
                                <td class="value-col">{{ \Carbon\Carbon::parse($peserta->tanggal_selesai)->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
