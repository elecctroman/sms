INSERT INTO users (name, email, pass_hash, phone, role, status, created_at, updated_at)
VALUES ('Admin', 'admin@site.com', '$2y$12$T8MOS2858ppUIPU4trNXc.4vf2/MzlDY6P3oUSy.xYxR/umtJdlpq', '+905555555555', 'owner', 'active', NOW(), NOW());

INSERT INTO wallets (user_id, balance_decimal, currency, created_at, updated_at)
VALUES (1, 250.0000, 'TRY', NOW(), NOW());

INSERT INTO suppliers (name, base_url, api_key, api_secret, enabled, currency, price_markup_percent, timeout_sec, cancel_timeout_sec, created_at, updated_at)
VALUES
('Nessa Demo', 'https://demo-nessa.local', 'nessa_key', 'nessa_secret', 1, 'USD', 10.00, 30, 120, NOW(), NOW()),
('Provider X', 'https://providerx.local', 'providerx_key', 'providerx_secret', 1, 'EUR', 15.00, 30, 120, NOW(), NOW());

INSERT INTO countries (iso2, name, enabled)
VALUES ('TR', 'Türkiye', 1), ('US', 'Amerika Birleşik Devletleri', 1), ('DE', 'Almanya', 1);

INSERT INTO operators (country_id, name, external_code, enabled)
VALUES (1, 'Turkcell', 'TCELL', 1), (1, 'Vodafone', 'VOD', 1), (2, 'Verizon', 'VZN', 1);

INSERT INTO services (name, slug, icon, color, favorite_count, enabled)
VALUES
('WhatsApp', 'whatsapp', 'whatsapp', '#25D366', 120, 1),
('Telegram', 'telegram', 'telegram', '#229ED9', 100, 1),
('Instagram', 'instagram', 'instagram', '#E1306C', 95, 1),
('Google', 'google', 'google', '#4285F4', 80, 1);

INSERT INTO service_prices (service_id, country_id, operator_id, buy_price, sell_price, avg_delivery_seconds, stock, supplier_id, last_sync_at)
VALUES
(1, 1, 1, 0.25, 0.55, 120, 30, 1, NOW()),
(2, 1, 2, 0.30, 0.60, 160, 20, 1, NOW()),
(3, 2, NULL, 0.50, 0.95, 220, 15, 2, NOW());

INSERT INTO announcements (title, body, published_at, active)
VALUES ('Yeni Tema Hazır', 'Yönetim paneli için koyu tema yayında.', NOW(), 1);

INSERT INTO blog_posts (title, slug, body_html, cover_image, published_at, seo_title, seo_description, active)
VALUES ('SMS Onay ile Büyüyün', 'sms-onay-ile-buyuyun', '<p>Sanal numaralarla global müşterilere ulaşın.</p>', NULL, NOW(), 'SMS Onay ile Büyüyün', 'Sanal numara ile güvenli doğrulama', 1);

INSERT INTO tickets (user_id, subject, status, priority, created_at, updated_at)
VALUES (1, 'Demo talebi', 'open', 'normal', NOW(), NOW());

INSERT INTO ticket_messages (ticket_id, user_id, message, attachments, created_at)
VALUES (1, 1, 'Merhaba, demo hesabı açabilir misiniz?', '[]', NOW());

INSERT INTO settings (`key`, `value`)
VALUES ('theme', JSON_OBJECT('site_title', 'SMS Onay Platformu', 'primary_color', '#4f46e5'))
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);
