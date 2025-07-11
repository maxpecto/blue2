<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Müqavilə - {{ $contract->sozlesme_no }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .container { width: 100%; margin: 0 auto; }
        .header, .footer { text-align: center; }
        .header h1 { margin: 0; }
        .content { margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px;}
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left;}
        th { background-color: #f2f2f2; }
        .signatures { margin-top: 50px; }
        .signature-box { float: left; width: 45%; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>İCARƏ MÜQAVİLƏSİ № {{ $contract->sozlesme_no }}</h1>
            <p>Tarix: {{ $contract->baslangic_tarihi }}</p>
        </div>

        <div class="content">
            <p>
                Bir tərəfdən <strong>"Şirkət Adı"</strong> (bundan sonra İcarəyə Götürən adlandırılacaq), digər tərəfdən aşağıda məlumatları verilmiş
                <strong>{{ $contract->courier->ad }} {{ $contract->courier->soyad }}</strong> (bundan sonra İcarəçi adlandırılacaq) arasında aşağıdakı şərtlərlə bu müqavilə bağlanmışdır.
            </p>

            <h3>1. Müqavilənin Predmeti</h3>
            <p>
                İcarəyə Götürən aşağıda göstəriciləri qeyd olunan nəqliyyat vasitəsini İcarəçiyə müvəqqəti istifadə üçün verir.
            </p>
            <table>
                <tr><th>Dövlət Nömrə Nişanı</th><td>{{ $contract->motorcycle->plaka }}</td></tr>
                <tr><th>Marka / Model</th><td>{{ $contract->motorcycle->marka }} / {{ $contract->motorcycle->model }}</td></tr>
                <tr><th>Şassi Nömrəsi</th><td>{{ $contract->motorcycle->sase_no }}</td></tr>
                <tr><th>Mühərrik Nömrəsi</th><td>{{ $contract->motorcycle->motor_no }}</td></tr>
            </table>

            <h3>2. Ödəniş Şərtləri</h3>
            <table>
                <tr><th>Müqavilə Tipi</th><td>{{ $contract->type == 'kiralama' ? 'İcarə' : 'Taksitli Satış' }}</td></tr>
                <tr><th>Ümumi Məbləğ</th><td>{{ $contract->toplam_tutar }} AZN</td></tr>
                <tr><th>Periodik Ödəniş</th><td>{{ $contract->aylik_odeme }} AZN</td></tr>
                <tr><th>Ödəmə Periyodu</th><td>{{ $contract->odeme_periyodu }} gündən bir</td></tr>
                <tr><th>Müqavilənin Müddəti</th><td>{{ $contract->baslangic_tarihi }} - {{ $contract->bitis_tarihi }}</td></tr>
            </table>
            
            <h3>3. Tərəflərin Məsuliyyəti</h3>
            <p>
                ... (Buraya standart məsuliyyət bəndləri əlavə edilə bilər) ...
            </p>
        </div>

        <div class="signatures">
            <div class="signature-box">
                <p><strong>İcarəyə Götürən:</strong></p>
                <br><br>
                <p>____________________</p>
                <p>(İmza, Möhür)</p>
            </div>
            <div class="signature-box" style="float: right;">
                <p><strong>İcarəçi:</strong></p>
                <p>Ad, Soyad: {{ $contract->courier->ad }} {{ $contract->courier->soyad }}</p>
                <p>Ş.V. FIN: {{ $contract->courier->fin_kodu }}</p>
                <br>
                <p>____________________</p>
                <p>(İmza)</p>
            </div>
        </div>
    </div>
</body>
</html> 