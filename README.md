# Library Project (PHP + MySQL + HTML/CSS_JS) - Updated
## Features added in this update
- Subscription model: users must purchase a subscription (monthly/yearly) to borrow books. Admins bypass this check.
- User roles: 'admin' and 'user' (admin user created with email admin@example.com and password 'admin123' - change after import).
- Subscription purchase page but no pyment option  (subscription.php).
- Admin dashboard with subscription stats.
## Setup
1. Place the `library-project-updated` folder in your webserver root (e.g., /opt/lampp/htdocs).
2. Import `db_init.sql` into MySQL to create database and sample data.
3. Edit `public/config.php` if your DB credentials differ.
4. Open `public/index.php` in browser and login (use admin@example.com / admin123 for admin).
5. Register new users and purchase subscription to borrow books.
