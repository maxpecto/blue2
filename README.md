# Motosiklet Kiralama Yönetim Sistemi

Laravel ve Filament PHP kullanılarak geliştirilmiş motosiklet kiralama ve taksitli satış yönetim sistemi.

## Özellikler

- 📊 **Dashboard**: Toplam istatistikler, gecikmiş ödemeler, yaklaşan servisler
- 🏍️ **Motosiklet Yönetimi**: Motosiklet ekleme, düzenleme, kilometre takibi
- 📝 **Sözleşme Yönetimi**: Kiralama ve taksitli satış sözleşmeleri, PDF oluşturma
- 💰 **Ödeme Takibi**: Periyodik ödeme hesaplama, gecikmiş ödeme uyarıları
- 🔧 **Bakım Yönetimi**: Proaktif bakım takibi, servis kuralları
- 👥 **Kullanıcı Yönetimi**: Yatırımcılar, kuryeler, sistem kullanıcıları
- 📦 **Yedek Parça Yönetimi**: Stok takibi, otomatik düşürme

## Kurulum

### Gereksinimler

- PHP 8.1+
- Composer
- MySQL/PostgreSQL
- Node.js & NPM

### Adımlar

1. **Projeyi klonlayın:**
```bash
git clone https://github.com/maxpecto/blue2.git
cd blue2
```

2. **Bağımlılıkları yükleyin:**
```bash
composer install
npm install
```

3. **Ortam dosyasını ayarlayın:**
```bash
cp .env.example .env
```

4. **Veritabanı ayarlarını yapın:**
`.env` dosyasında veritabanı bilgilerinizi güncelleyin:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=motosiklet_yonetim
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Uygulama anahtarını oluşturun:**
```bash
php artisan key:generate
```

6. **Veritabanını migrate edin ve test verilerini yükleyin:**
```bash
php artisan migrate:fresh --seed
```

7. **Filament admin kullanıcısı oluşturun:**
```bash
php artisan make:filament-user
```

8. **Asset'leri derleyin:**
```bash
npm run build
```

9. **Uygulamayı çalıştırın:**
```bash
php artisan serve
```

Uygulama `http://localhost:8000` adresinde çalışacaktır.
Admin paneline `http://localhost:8000/admin` adresinden erişebilirsiniz.

## Test Verileri

`TestVerisiSeeder` ile aşağıdaki test verileri yüklenir:

- **Yatırımcılar**: 3 adet örnek yatırımcı
- **Kuryeler**: 5 adet örnek kurye  
- **Motosikletler**: 10 adet farklı marka/model motosiklet
- **Sözleşmeler**: Kiralama ve taksitli satış örnekleri
- **Bakım Kuralları**: Kilometre bazlı servis kuralları
- **Yedek Parçalar**: Çeşitli yedek parça örnekleri

## Kullanım

### Ana Modüller

1. **Dashboard**: Genel sistem durumu ve önemli bildirimler
2. **Motosikletler**: Araç filosu yönetimi
3. **Sözleşmeler**: Kiralama/satış işlemleri
4. **Ödemeler**: Taksit takibi ve ödeme yönetimi
5. **Bakım**: Servis planlaması ve takibi
6. **Kullanıcılar**: Yatırımcı ve kurye yönetimi
7. **Servis**: Toplu kilometre güncelleme

### Önemli Özellikler

- **Otomatik Ödeme Hesaplama**: Toplam tutar ve vade sayısına göre taksit hesaplama
- **Proaktif Bakım**: Kilometre bazlı otomatik servis hatırlatmaları
- **PDF Sözleşme**: Sözleşmelerin PDF formatında çıktısı
- **Toplu Güncelleme**: Tüm motosikletlerin kilometrelerini toplu güncelleme

## Teknolojiler

- **Backend**: Laravel 11
- **Admin Panel**: Filament PHP 3.x
- **Veritabanı**: MySQL/PostgreSQL
- **PDF**: DomPDF
- **Frontend**: Blade Templates, Alpine.js
- **Styling**: Tailwind CSS

## Lisans

Bu proje MIT lisansı altında lisanslanmıştır.
