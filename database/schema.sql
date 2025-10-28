CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    pass_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(40) NOT NULL,
    role ENUM('owner','admin','support','finance','user') NOT NULL DEFAULT 'user',
    status ENUM('active','banned') NOT NULL DEFAULT 'active',
    last_login_at DATETIME NULL,
    twofa_secret VARCHAR(255) NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS wallets (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    balance_decimal DECIMAL(18,4) NOT NULL DEFAULT 0,
    currency CHAR(3) NOT NULL DEFAULT 'TRY',
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS wallet_transactions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    wallet_id INT UNSIGNED NOT NULL,
    type ENUM('deposit','withdraw','bonus','fee','correction') NOT NULL,
    amount_decimal DECIMAL(18,4) NOT NULL,
    balance_after DECIMAL(18,4) NOT NULL,
    meta JSON NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (wallet_id) REFERENCES wallets(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS suppliers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(160) NOT NULL,
    base_url VARCHAR(255) NOT NULL,
    api_key VARCHAR(255) NOT NULL,
    api_secret VARCHAR(255) NOT NULL,
    enabled TINYINT(1) NOT NULL DEFAULT 1,
    currency CHAR(3) NOT NULL DEFAULT 'USD',
    price_markup_percent DECIMAL(6,2) NOT NULL DEFAULT 0,
    timeout_sec INT NOT NULL DEFAULT 30,
    cancel_timeout_sec INT NOT NULL DEFAULT 120,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS countries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    iso2 CHAR(2) NOT NULL,
    name VARCHAR(150) NOT NULL,
    enabled TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS operators (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    country_id INT UNSIGNED NOT NULL,
    name VARCHAR(150) NOT NULL,
    external_code VARCHAR(120) NULL,
    enabled TINYINT(1) NOT NULL DEFAULT 1,
    FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS services (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(160) NOT NULL,
    icon VARCHAR(120) NULL,
    color VARCHAR(20) NULL,
    favorite_count INT NOT NULL DEFAULT 0,
    enabled TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS service_prices (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_id INT UNSIGNED NOT NULL,
    country_id INT UNSIGNED NOT NULL,
    operator_id INT UNSIGNED NULL,
    buy_price DECIMAL(18,4) NOT NULL,
    sell_price DECIMAL(18,4) NOT NULL,
    avg_delivery_seconds INT NOT NULL,
    stock INT NOT NULL,
    supplier_id INT UNSIGNED NOT NULL,
    last_sync_at DATETIME NULL,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE CASCADE,
    FOREIGN KEY (operator_id) REFERENCES operators(id) ON DELETE SET NULL,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    service_id INT UNSIGNED NOT NULL,
    country_id INT UNSIGNED NOT NULL,
    operator_id INT UNSIGNED NULL,
    supplier_id INT UNSIGNED NOT NULL,
    external_order_id VARCHAR(160) NOT NULL,
    phone_number VARCHAR(40) NOT NULL,
    status ENUM('pending','active','completed','cancelled','failed','expired') NOT NULL DEFAULT 'pending',
    charge DECIMAL(18,4) NOT NULL,
    currency CHAR(3) NOT NULL,
    received_code VARCHAR(40) NULL,
    received_at DATETIME NULL,
    expires_at DATETIME NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE CASCADE,
    FOREIGN KEY (operator_id) REFERENCES operators(id) ON DELETE SET NULL,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS rented_numbers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    number VARCHAR(40) NOT NULL,
    starts_at DATETIME NOT NULL,
    ends_at DATETIME NOT NULL,
    period ENUM('hourly','daily','weekly','monthly') NOT NULL,
    status ENUM('active','expired','cancelled') NOT NULL DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS coupons (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(80) NOT NULL UNIQUE,
    type ENUM('percent','fixed') NOT NULL,
    value DECIMAL(18,4) NOT NULL,
    target ENUM('all','paying','nonpaying') NOT NULL DEFAULT 'all',
    usage_limit INT NULL,
    used_count INT NOT NULL DEFAULT 0,
    starts_at DATETIME NULL,
    ends_at DATETIME NULL,
    active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS announcements (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(160) NOT NULL,
    body TEXT NOT NULL,
    published_at DATETIME NULL,
    active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS blog_posts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(160) NOT NULL,
    slug VARCHAR(160) NOT NULL,
    body_html MEDIUMTEXT NOT NULL,
    cover_image VARCHAR(255) NULL,
    published_at DATETIME NULL,
    seo_title VARCHAR(160) NULL,
    seo_description VARCHAR(255) NULL,
    active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tickets (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    subject VARCHAR(160) NOT NULL,
    status ENUM('open','waiting','closed') NOT NULL DEFAULT 'open',
    priority ENUM('low','normal','high') NOT NULL DEFAULT 'normal',
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ticket_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NULL,
    message TEXT NOT NULL,
    attachments JSON NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS audit_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    actor_user_id INT UNSIGNED NULL,
    action VARCHAR(160) NOT NULL,
    entity VARCHAR(160) NOT NULL,
    entity_id INT UNSIGNED NOT NULL,
    meta JSON NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (actor_user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS settings (
    `key` VARCHAR(120) NOT NULL PRIMARY KEY,
    `value` JSON NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS favorites (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    service_id INT UNSIGNED NOT NULL,
    UNIQUE KEY unique_favorite (user_id, service_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
