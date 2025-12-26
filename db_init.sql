-- db_init.sql
CREATE DATABASE IF NOT EXISTS library_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE library_db;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(20) NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS books (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  author VARCHAR(255) NOT NULL,
  isbn VARCHAR(50),
  publisher VARCHAR(255),
  year YEAR,
  copies INT DEFAULT 1,
  cover_url VARCHAR(500),
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS borrows (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  book_id INT NOT NULL,
  borrow_date DATE NOT NULL,
  due_date DATE NOT NULL,
  return_date DATE DEFAULT NULL,
  status ENUM('borrowed','returned','overdue') DEFAULT 'borrowed',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS subscriptions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  plan_type ENUM('weekly','monthly','yearly') NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  status ENUM('active','expired') DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- sample admin user (password: admin123) bcrypt hash placeholder (change after import)
INSERT INTO users (name,email,password,role) VALUES ('Admin User','admin@example.com','$2y$10$e0NRmQhYxwHjvQ9c6f9nV.3qF7Fz2sGQeIu1z1q9eZb2QY8pQ/4fK','admin');

-- sample books (15)
INSERT INTO books (title, author, isbn, publisher, year, copies, cover_url, description) VALUES
('To Kill a Mockingbird','Harper Lee','9780061120084','Harper Perennial Modern Classics',1960,3,'https://covers.openlibrary.org/b/isbn/9780061120084-L.jpg','Classic novel'),
('1984','George Orwell','9780451524935','Signet Classics',1949,4,'https://covers.openlibrary.org/b/isbn/9780451524935-L.jpg','Dystopian novel'),
('Pride and Prejudice','Jane Austen','9780141439518','Penguin Classics',1813,2,'https://covers.openlibrary.org/b/isbn/9780141439518-L.jpg','Classic romance'),
('The Great Gatsby','F. Scott Fitzgerald','9780743273565','Scribner',1925,3,'https://covers.openlibrary.org/b/isbn/9780743273565-L.jpg','Jazz Age novel'),
('Moby-Dick','Herman Melville','9780142437247','Penguin Classics',1851,1,'https://covers.openlibrary.org/b/isbn/9780142437247-L.jpg','Epic sea tale'),
('Brave New World','Aldous Huxley','9780060850524','Harper Perennial',1932,2,'https://covers.openlibrary.org/b/isbn/9780060850524-L.jpg','Futuristic novel'),
('The Catcher in the Rye','J.D. Salinger','9780316769488','Little, Brown and Company',1951,2,'https://covers.openlibrary.org/b/isbn/9780316769488-L.jpg','Teen angst'),
('Sapiens','Yuval Noah Harari','9780062316097','Harper',2011,3,'https://covers.openlibrary.org/b/isbn/9780062316097-L.jpg','History of humankind'),
('The Hobbit','J.R.R. Tolkien','9780547928227','Houghton Mifflin Harcourt',1937,3,'https://covers.openlibrary.org/b/isbn/9780547928227-L.jpg','Fantasy'),
('The Alchemist','Paulo Coelho','9780061122415','HarperOne',1988,4,'https://covers.openlibrary.org/b/isbn/9780061122415-L.jpg','Fable about following dreams'),
('Harry Potter and the Sorcerer''s Stone','J.K. Rowling','9780590353427','Scholastic',1997,5,'https://covers.openlibrary.org/b/isbn/9780590353427-L.jpg','Fantasy adventure'),
('The Da Vinci Code','Dan Brown','9780307474278','Doubleday',2003,2,'https://covers.openlibrary.org/b/isbn/9780307474278-L.jpg','Mystery thriller'),
('The Road','Cormac McCarthy','9780307387899','Alfred A. Knopf',2006,1,'https://covers.openlibrary.org/b/isbn/9780307387899-L.jpg','Post-apocalyptic journey'),
('Thinking, Fast and Slow','Daniel Kahneman','9780374533557','Farrar, Straus and Giroux',2011,2,'https://covers.openlibrary.org/b/isbn/9780374533557-L.jpg','Cognitive psychology'),
('Crime and Punishment','Fyodor Dostoevsky','9780140449136','Penguin Classics',1866,2,'https://covers.openlibrary.org/b/isbn/9780140449136-L.jpg','Psychological novel');
