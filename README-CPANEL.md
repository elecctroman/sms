# SMS Onay Platformu (cPanel/FTP Kurulumu)

Bu proje, tamamen paylaşımlı cPanel hosting ortamlarında Composer veya npm gerektirmeden çalışacak şekilde tasarlandı. Tüm bağımlılıklar tek klasör içinde hazırdır ve yalnızca FTP ile yükleme yapılarak kurulum tamamlanabilir.

## Sistem Gereksinimleri
- PHP 8.1 veya üzeri (ionCube Loader v13 ile uyumlu)
- Apache + mod_rewrite
- MariaDB 10.6+
- PHP eklentileri: `curl`, `pdo_mysql`, `intl`, `zip`
- `zlib.output_compression=On`, `allow_url_fopen=On`
- Geçerli SSL sertifikası (uygulama HTTPS'e zorlar)

## Hızlı Kurulum Adımları
1. Depodaki tüm dosyaları FTP ile hosting hesabınıza yükleyin. Kök dizine (.htaccess ve index.php dahil) yüklediğinizde istekler otomatik olarak `public/` klasörüne yönlendirilir; ayrıca alt klasöre taşımanıza gerek yoktur. Eğer domain kökünü doğrudan `public/` klasörüne işaret edecek şekilde yapılandırabiliyorsanız bu da geçerlidir.
2. `config/config.php` dosyasını metin editörüyle açarak temel uygulama ve veritabanı ayarlarını düzenleyin (veya kurulum sihirbazını kullanın).
3. Tarayıcıda `https://alanadiniz.com/install/install.php` adresine gidin.
4. Sistem gereksinimleri kontrolünü geçtikten sonra veritabanı ve SMTP e-posta bilgilerinizi girin ve kurulumu tamamlayın. Kurulum sonunda `install` klasörü otomatik olarak yeniden adlandırılır.
5. Yönetici oturumu için varsayılan bilgiler: **admin@site.com / Admin@123!** (giriş yaptıktan sonra değiştirmeniz önerilir).

### Manuel Kurulum (Kurulum sihirbazı kullanmadan)
1. `config/config.php` dosyasını açın ve veritabanı bilgilerinizi girin.
2. phpMyAdmin veya mysql CLI kullanarak `database/schema.sql` ve `database/seed.sql` dosyalarını sırasıyla çalıştırın.
3. `install` klasörünü silin veya yeniden adlandırın.

## Dizin Yapısı
```
public/            # Webroot (index.php, .htaccess, assets, uploads)
app/
  Core/            # Autoloader, router, view, güvenlik sınıfları
  Controllers/     # Site, Admin ve API controller'ları
  Middlewares/
  Models/          # Temel modeller (PDO)
  Repositories/    # Veri erişim katmanı
  Services/        # İş mantığı + tedarikçi adapter'ları
  Lib/             # Basit yardımcı sınıflar (cache, mail, validation)
  Views/           # PHP tabanlı temalar (landing + admin)
config/config.php  # Uygulama ayarları
storage/           # Cache, log ve session dosyaları (yazılabilir olmalı)
database/          # schema.sql + seed.sql
install/           # Kurulum sihirbazı
cli/               # cPanel cron betikleri
```

## Cron Örnekleri (cPanel > Cron Jobs)
```
*/2 * * * * /usr/bin/php -q /home/USER/public_html/cli/cron_orders_poll.php
0 * * * * /usr/bin/php -q /home/USER/public_html/cli/cron_sync_suppliers.php
15 3 * * * /usr/bin/php -q /home/USER/public_html/cli/cron_stats_rebuild.php
```
`/home/USER/public_html` yolunu kendi hosting hesabınıza göre uyarlayın.

## Güvenlik Notları
- Uygulama varsayılan olarak HTTPS'e yönlendirir ve HSTS başlığı gönderir.
- Tüm formlar CSRF koruması altındadır.
- PDO Prepared Statements kullanılarak SQL enjeksiyonu riskleri azaltılır.
- `public/uploads/.htaccess` ile PHP yürütülmesi engellenmiştir.
- 2FA adımı e-posta OTP ile sağlanır; varsayılan olarak SMTP ile gönderim yapılır (isterseniz `config/config.php` içindeki `mail.driver` değerini `mail` olarak değiştirebilirsiniz).

## Güncelleme / Dağıtım
1. Yeni sürümü lokalinizde hazırlayın.
2. Sadece değişen dosyaları FTP ile aktarın (bakım modu gerekmez).
3. Gerekirse `database/` altındaki yeni SQL'leri manuel uygulayın.

## Sorun Giderme
- **Veritabanı hatası uyarısı:** Kurulumdan sonra "Veritabanı tabloları eksik" veya "Veritabanına bağlanılamadı" uyarısı görürseniz `config/config.php` içindeki veritabanı kullanıcı adı/şifresini kontrol edin. Tablo eksikliğinde `database/schema.sql` ve `database/seed.sql` dosyalarını phpMyAdmin üzerinden çalıştırın.
- **E-posta OTP gönderilmiyor:** Kurulum sihirbazında SMTP alanlarını eksiksiz doldurduğunuzdan emin olun. Paylaşımlı hosting sağlayıcınız `mail()` fonksiyonunu devre dışı bıraktıysa uygulama otomatik olarak SMTP moduna geçer; `config/config.php` dosyasındaki `mail.smtp.*` alanlarının gerçek sunucu bilgileriyle dolu olması gerekir.
- **Beyaz sayfa / hata:** `storage/logs/app.log` dosyasını kontrol edin.
- **Oturum sorunları:** `storage/sessions` dizininin yazılabilir olduğundan emin olun.
- **HTTPS yönlendirmesi çalışmıyor:** Apache `AllowOverride All` ve mod_rewrite ayarlarını doğrulayın.

## Lisans
Bu proje ticari kullanım için özelleştirilebilir. ionCube ile şifreleme uyumluluğu korunmuştur (strict types, global yan etkiler yoktur).
