# SMS Verification Platform

Kurumsal ölçekli SMS onay scripti; landing sayfası, yönetim paneli ve tedarikçi entegrasyonlarını kapsayan modüler bir PHP 8.1 projesidir.

## Özellikler

- Modüler MVC mimarisi (Controllers, Services, Repositories, Policies)
- Twig tabanlı tema sistemi (landing ve admin)
- Çoklu dil desteği (TR/EN)
- Dosya tabanlı cache ve kuyruk sürücüleri
- Dummy tedarikçi adapter’ları (NessaDemo, ProviderX)
- CLI komutları: `sync:suppliers`, `orders:poll`, `stats:rebuild`
- PHPUnit testleri, PHPStan seviye 6, PHP-CS-Fixer yapılandırması
- SCSS + TypeScript için esbuild yapı süreci

## Kurulum

### Gereksinimler

- PHP 8.1+
- Composer
- MariaDB 10.6+
- Node.js 18+ (esbuild için)
- Apache + mod_rewrite (veya uyumlu web sunucusu)

### Adımlar

1. Depoyu klonlayın ve bağımlılıkları yükleyin:

```bash
composer install
npm install
```

2. Ortam değişkenlerini ayarlayın:

```bash
cp .env.example .env
```

`.env` dosyasında veritabanı ve tedarikçi bilgilerini güncelleyin.

3. Veritabanını oluşturun ve migrate/seed çalıştırın:

```bash
mysql -u root -p -e "CREATE DATABASE sms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p sms < database/migrations/001_create_tables.sql
mysql -u root -p sms < database/seeds/demo_seed.sql
```

4. Asset’leri derleyin:

```bash
npm run build
```

5. Geliştirme için dahili PHP sunucusunu kullanabilirsiniz:

```bash
php -S 0.0.0.0:8000 -t public
```

Ardından `https://localhost:8000` adresine gidin.

### Apache Sanal Host Örneği

```apacheconf
<VirtualHost *:80>
    ServerName sms.local
    DocumentRoot /var/www/sms/public

    <Directory /var/www/sms/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

HTTPS yönlendirmesi için Let’s Encrypt sertifikası kurduktan sonra `:80` sanal host’unda 301 yönlendirmesi sağlayın.

### Cron Örnekleri

```cron
*/10 * * * * /usr/bin/php /var/www/sms/bin/console sync:suppliers >> /var/log/sms_sync.log 2>&1
*/5 * * * * /usr/bin/php /var/www/sms/bin/console orders:poll >> /var/log/sms_orders.log 2>&1
0 2 * * * /usr/bin/php /var/www/sms/bin/console stats:rebuild >> /var/log/sms_stats.log 2>&1
```

## Test & Kalite

```bash
./vendor/bin/phpunit
./vendor/bin/phpstan analyse
./vendor/bin/php-cs-fixer fix --dry-run
```

Kod kapsamı raporu oluşturmak için PHPUnit’e `--coverage-html build/coverage` parametresini ekleyin.

## IonCube Uyumlu Kodlama Notları

- Tüm PHP dosyalarında `declare(strict_types=1)` kullanılır.
- Global scope yan etkileri minimum tutulmuştur.
- Composer autoload ve PSR-4 kullanımı IonCube şifrelemesine uygundur.

## Güvenlik Notları

- Varsayılan `.htaccess` HTTPS yönlendirmesi ve HSTS başlığı içerir.
- CSRF token doğrulama altyapısı sağlanır.
- Tüm örnek SQL sorguları parametreli hazırlanmıştır.

## Lisans

Bu proje örnek amaçlıdır.
