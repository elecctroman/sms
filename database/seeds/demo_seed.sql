INSERT INTO users (name, email, pass_hash, phone, role, status)
VALUES ('Demo Kullanıcı', 'demo@sms.local', '$2y$10$abcdefghijklmnopqrstuv', '+905551112233', 'admin', 'active')
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO services (name, slug, icon, color, enabled) VALUES
('WhatsApp', 'whatsapp', 'bi-whatsapp', '#25D366', 1),
('Discord', 'discord', 'bi-discord', '#5865F2', 1),
('Google', 'google', 'bi-google', '#4285F4', 1),
('Instagram', 'instagram', 'bi-instagram', '#E1306C', 1),
('Telegram', 'telegram', 'bi-telegram', '#26A5E4', 1),
('Tiktok', 'tiktok', 'bi-tiktok', '#010101', 1)
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO countries (iso2, name, enabled) VALUES
('TR', 'Türkiye', 1),
('US', 'United States', 1),
('DE', 'Germany', 1)
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO operators (country_id, name, external_code, enabled)
SELECT id, 'Turkcell', 'TURKCELL', 1 FROM countries WHERE iso2 = 'TR'
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO blog_posts (title, slug, body_html, published_at, active)
VALUES ('SMS Onay Trendleri', 'sms-onay-trendleri', '<p>Güvenli SMS onay süreçleri için ipuçları.</p>', NOW(), 1)
ON DUPLICATE KEY UPDATE title = VALUES(title);

INSERT INTO announcements (title, body, published_at, active)
VALUES ('Bakım Duyurusu', 'Planlı bakım 02:00 - 04:00 arasında yapılacaktır.', NOW(), 1)
ON DUPLICATE KEY UPDATE title = VALUES(title);
