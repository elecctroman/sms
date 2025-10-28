# SMS Onay Platformu

Tamamen FTP ile kurulabilen, Composer/Node gerektirmeyen SMS onay yazılımı. Ayrıntılı adımlar için [README-CPANEL.md](README-CPANEL.md) dosyasına bakın.

## Öne Çıkanlar
- Hafif PSR-4 otomatik yükleyici, router ve PHP tabanlı view katmanı.
- Yönetim paneli ve landing için hazır Bootstrap 5 temaları (CDN üzerinden).
- Dummy tedarikçi adapter'ları (NessaDemo, ProviderX) ile uçtan uca sipariş senaryosu.
- Kurulum sihirbazı (`install/install.php`) ile 5 dakikada devreye alınır.
- Dosya tabanlı cache, yazılabilir storage dizinleri ve CSRF/2FA destekli güvenlik modeli.

## Hızlı Başlangıç
1. Dosyaları FTP ile sunucuya yükleyin. Kök dizine bıraktığınız `.htaccess` ve `index.php` dosyaları tüm istekleri otomatik olarak `public/` klasörüne yönlendirir.
2. `https://alanadiniz.com/install/install.php` adresini ziyaret edip veritabanı ve SMTP e-posta ayarlarını girerek sihirbazı tamamlayın.
3. Yönetici paneline `admin@site.com / Admin@123!` bilgileriyle giriş yapın.

## Cron Örnekleri
```
*/2 * * * * /usr/bin/php -q /home/USER/public_html/cli/cron_orders_poll.php
0 * * * * /usr/bin/php -q /home/USER/public_html/cli/cron_sync_suppliers.php
15 3 * * * /usr/bin/php -q /home/USER/public_html/cli/cron_stats_rebuild.php
```

## Dizinler
- `public/`: Web kökü ve minify edilmiş varlıklar.
- `app/`: Core katman, controller'lar, servisler, repository'ler ve görünümler.
- `config/config.php`: Uygulama ve veritabanı ayarları.
- `storage/`: Cache, log ve session dosyaları.
- `install/`: Kurulum sihirbazı (tamamlandıktan sonra otomatik olarak devre dışı kalır).

## Güvenlik
- HTTPS zorunlu yönlendirme + HSTS.
- Tüm formlar için CSRF token'ları.
- Upload klasörü için PHP engellemesi.
- E-posta OTP ile iki aşamalı doğrulama (SMTP veya paylaşımlı hosting `mail()` desteği ile çalışır).

## Lisans
Örnek proje; ionCube v13 şifrelemesiyle uyumlu olacak şekilde hazırlanmıştır.
