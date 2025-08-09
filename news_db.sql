-- news_db.sql
-- Create database and tables with sample data

CREATE DATABASE IF NOT EXISTS news_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE news_db;

-- users
CREATE TABLE IF NOT EXISTS users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100),
  email VARCHAR(150) UNIQUE,
  password VARCHAR(255),
  role ENUM('user','admin') DEFAULT 'user'
);

-- categories
CREATE TABLE IF NOT EXISTS categories (
  category_id INT AUTO_INCREMENT PRIMARY KEY,
  category_name VARCHAR(100) NOT NULL
);

-- articles
CREATE TABLE IF NOT EXISTS articles (
  article_id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255),
  content TEXT,
  image_url VARCHAR(500),
  category_id INT,
  author_id INT,
  published_date DATETIME,
  FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL,
  FOREIGN KEY (author_id) REFERENCES users(user_id) ON DELETE SET NULL
);

-- comments
CREATE TABLE IF NOT EXISTS comments (
  comment_id INT AUTO_INCREMENT PRIMARY KEY,
  article_id INT,
  user_id INT,
  comment_text TEXT,
  timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (article_id) REFERENCES articles(article_id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
);

-- sample categories
INSERT INTO categories (category_name) VALUES ('Politics'),('Technology'),('Sports'),('Entertainment'),('World');

-- sample users (passwords are plaintext placeholders; replace with hashed in real import)
INSERT INTO users (username, email, password, role) VALUES
('Admin User','admin@example.com','$2y$10$e0NRqjH/hashedpasswordexample', 'admin'),
('Author','author@example.com','$2y$10$e0NRqjH/hashedpasswordexample', 'user');

-- sample articles
INSERT INTO articles (title, content, image_url, category_id, author_id, published_date) VALUES
('Local Elections See Record Turnout','Content of the article goes here. This is sample text for the local elections article.','https://picsum.photos/seed/politics/800/400',1,2,NOW()),
('New Smartphone Launches This Week','Details about the smartphone launch and specs...','https://picsum.photos/seed/tech/800/400',2,2,NOW()),
('Championship Match Ends in Draw','Match details and reactions...','https://picsum.photos/seed/sports/800/400',3,2,NOW());

-- sample comments
INSERT INTO comments (article_id, user_id, comment_text) VALUES
(1,2,'Great coverage!'),
(2,2,'Excited about the new phone.');
