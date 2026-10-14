<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sertifikat — KelasQu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/kelasqu.css') }}" rel="stylesheet">
</head>
<body class="certificate-page">
    <div class="certificate-toolbar"><a href="{{ route('user.certificates') }}" class="btn btn-outline-soft"><i class="bi bi-arrow-left"></i> Kembali</a><button class="btn btn-primary profile-save" onclick="window.print()"><i class="bi bi-printer"></i> Cetak / Simpan PDF</button></div>
    <main class="certificate-paper">
        <div class="certificate-border">
            <span class="certificate-brand">Kelas<span>Qu</span></span>
            <span class="section-kicker">SERTIFIKAT PENYELESAIAN KELAS</span>
            <h1>Sertifikat</h1>
            <p class="certificate-intro">Dengan bangga diberikan kepada</p>
            <div class="certificate-student">{{ $certificate->enrollment->user->name }}</div>
            <p class="certificate-intro">atas keberhasilannya menyelesaikan kelas</p>
            <h2>{{ $certificate->enrollment->course->title }}</h2>
            <p class="certificate-description">{{ $certificate->enrollment->course->description }}</p>
            <div class="certificate-seal"><i class="bi bi-award"></i></div>
            <div class="certificate-bottom"><div><span>{{ $certificate->issued_at->format('d F Y') }}</span><small>TANGGAL DITERBITKAN</small></div><div><span>{{ strtoupper(substr($certificate->code, 0, 12)) }}</span><small>KODE SERTIFIKAT</small></div></div>
            <div class="certificate-verification">Sertifikat terverifikasi · KelasQu</div>
        </div>
    </main>
</body>
</html>
