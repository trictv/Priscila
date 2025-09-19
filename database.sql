-- Criando o banco de dados (opcional, pode ser criado manualmente)
CREATE DATABASE IF NOT EXISTS beefit_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE beefit_db;

-- Tabela para configurações gerais do site
CREATE TABLE settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(50) NOT NULL UNIQUE,
  setting_value TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserindo configurações iniciais que serão gerenciadas pelo admin
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('site_logo', 'path/to/default_logo.png'),
('site_favicon', 'path/to/default_favicon.ico'),
('title_color', '#000000'),
('button_color', '#FFA500'),
('social_instagram', 'https://instagram.com/beefit'),
('social_facebook', 'https://facebook.com/beefit'),
('whatsapp_number', '5517999999999'); -- Número para o botão de redirecionamento

-- Tabela para os banners da home page
CREATE TABLE banners (
  id INT AUTO_INCREMENT PRIMARY KEY,
  image_path VARCHAR(255) NOT NULL,
  link_url VARCHAR(255),
  title VARCHAR(100),
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de categorias de produtos
CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(120) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela principal de produtos
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT,
  name VARCHAR(255) NOT NULL,
  reference_code VARCHAR(50) UNIQUE,
  description TEXT,
  price DECIMAL(10, 2) NOT NULL,
  sale_price DECIMAL(10, 2),
  is_featured BOOLEAN DEFAULT FALSE, -- Para aparecer na home
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela para múltiplas imagens por produto
CREATE TABLE product_images (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  is_primary BOOLEAN DEFAULT FALSE, -- A imagem principal da galeria
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela para os tamanhos disponíveis dos produtos (relação muitos-para-muitos)
CREATE TABLE product_sizes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  size VARCHAR(20) NOT NULL,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  UNIQUE(product_id, size)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela para o usuário administrador
CREATE TABLE admin_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL, -- A senha deve ser armazenada com hash (ex: bcrypt)
  last_login TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserindo um usuário admin padrão (senha: 'admin') - A IA deve gerar um hash seguro para a senha
-- Exemplo usando um hash bcrypt para 'admin'
INSERT INTO `admin_users` (`username`, `password`) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
