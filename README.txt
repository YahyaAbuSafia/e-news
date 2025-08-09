News Website (e-news) - Basic setup instructions

Files included:
- db.php (DB connection)
- index.php, article.php, categories.php, category.php
- search.php
- login.php, register.php
- admin.php (simple admin CRUD)
- styles.css
- news_db.sql (database schema + sample data)

Steps to run locally:
1. Install PHP and MySQL (e.g., XAMPP, MAMP, LAMPP).
2. Place the e-news folder in your web server root (e.g., htdocs).
3. Import 'news_db.sql' into MySQL (via phpMyAdmin or mysql CLI).
4. Edit 'db.php' with your DB credentials if not using defaults (root with no password).
5. In the database users table, the admin user password is a placeholder. To create a usable admin:
   - Register a new account via register.php, then update role in DB:
     UPDATE users SET role='admin' WHERE email='your-email';
6. Open browser: http://localhost/e-news/index.php

Notes:
- This is a minimal, educational project to satisfy the assignment requirements.
- Improve security (CSRF, input validation, file uploads) before production use.
