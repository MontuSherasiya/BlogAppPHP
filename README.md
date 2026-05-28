# ✍️ MegaBlog — Modern Blogging Platform

![PHP](https://img.shields.io/badge/PHP-8%2B-7A86B6?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-00758F?logo=mysql&logoColor=white)
![XAMPP](https://img.shields.io/badge/XAMPP-Server-FB7A24?logo=apache&logoColor=white)
![Status](https://img.shields.io/badge/Status-Active-6C63FF)

A full-stack blogging platform **Core PHP + MySQL**, 
and also in **React.js + Appwrite** `https://github.com/MontuSherasiya/MegaBlog`. 
Create, manage, and explore blog posts with a clean and intuitive interface — no frameworks required.

---

## 📸 Preview

### 🏠 Home Page
> Displays all active blog posts in a responsive card grid with featured images, author name, date, and a "Read More" button.

### 🔐 Auth Pages
> Clean signup and login forms with validation, error messages, and password hashing.

### 📝 Add / Edit Post
> Form with title, auto-generated slug, content textarea, image upload, and status toggle (Active / Inactive).

### 📚 My Posts Dashboard
> Table view of all your posts with View, Edit, and Delete actions per row.

---

## 📖 Project Description

**MegaBlog** is a modern full-stack blogging platform that allows users to create, manage, and explore blog content with a clean and intuitive interface.

The application provides a complete blogging experience — from user authentication to content creation — built using **Core PHP** for the backend and **MySQL** as the database. Users can securely sign up, log in, and perform full **CRUD (Create, Read, Update, Delete)** operations on blog posts. Each post includes a title, slug, content, featured image, and status control.

---

## ✨ Features

- 🔐 Secure Authentication (Signup / Login / Logout)
- 📝 Create, Edit & Delete Blog Posts (Full CRUD)
- 🖼️ Featured Image Upload (JPEG, PNG, GIF, WebP — max 5MB)
- 🔗 Auto Slug Generation from Post Title
- 👁️ Active / Inactive Post Status Control
- 🔒 Protected Routes — login required to create or manage posts
- 👤 Owner-only Edit & Delete — users can only modify their own posts
- 📱 Fully Responsive Design
- 🚫 404 Handling for missing posts

---

## 🛠️ Tech Stack

| Category  | Technology         | Purpose                          |
|-----------|--------------------|----------------------------------|
| Frontend  | HTML5 + CSS3       | Responsive UI, no framework      |
| Backend   | Core PHP 8+        | Sessions, routing, file uploads  |
| Database  | MySQL / MariaDB    | Users & posts with foreign keys  |
| Server    | Apache (XAMPP)     | Local development server         |
| Auth      | PHP Sessions       | Bcrypt password hashing          |
| Storage   | Local Filesystem   | Featured image uploads           |

---

## 📂 Project Structure

```
megablog/
│
├── config/
│   └── db.php                 # DB connection + BASE path constant
│
├── includes/
│   ├── auth.php               # Login, signup, logout, session helpers
│   ├── posts.php              # All post CRUD functions + image upload
│   ├── header.php             # Shared navigation bar
│   └── footer.php             # Shared footer
│
├── assets/
│   └── css/
│       └── style.css          # All styles — variables, grid, cards, forms
│
├── uploads/                   # Uploaded images (auto-created on first upload)
│
├── index.php                  # Home — all active posts grid
├── signup.php                 # User registration
├── login.php                  # User login
├── logout.php                 # Destroys session, redirects to login
├── add-post.php               # Create new post (protected)
├── edit-post.php              # Edit post — owner only (protected)
├── delete-post.php            # Delete post — owner only (protected)
├── all-posts.php              # My posts dashboard (protected)
├── post.php                   # Single post detail view
└── megablog_setup.sql         # Run once to create DB tables
```

---

## ⚙️ Setup & Installation

### ✅ Requirements

- [XAMPP](https://www.apachefriends.org) (Apache + MySQL)
- Web browser

---

### 🪟 Step 1 — Install & Start XAMPP

1. Download XAMPP from: https://www.apachefriends.org
2. Install with default settings
3. Open **XAMPP Control Panel**
4. Click **Start** next to **Apache** and **MySQL**

> 💡 Check the **Port(s)** column — Apache may be on port `80`, `8080`, or `7080` depending on your system.

---

### 📁 Step 2 — Copy Project Files

Extract the ZIP and copy the `megablog` folder to:

```
C:\xampp\htdocs\megablog\
```

---

### 🗄️ Step 3 — Create the Database

1. Open your browser and go to:
   ```
   http://localhost/phpmyadmin
   ```
   *(replace `localhost` with `localhost:8080` or `localhost:7080` based on your Apache port)*

2. Click **New** in the left sidebar
3. Enter database name: `megablog` → click **Create**
4. Click the `megablog` database → go to the **SQL** tab
5. Open `megablog_setup.sql` in Notepad, **copy all**, paste into the SQL box → click **Go**

You should see the `users` and `posts` tables created successfully.

---

### 🔧 Step 4 — Configure Database Credentials

Open `config/db.php` and update if needed:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');          // XAMPP default is empty
define('DB_NAME', 'megablog');
define('BASE',    '/megablog'); // Change if your folder name is different
```

---

### 🚀 Step 5 — Open the Site

Go to your browser and visit:

```
http://localhost/megablog
```

> Use your actual Apache port if not 80:
> - `http://localhost:8080/megablog`
> - `http://localhost:7080/megablog`

Click **Sign Up** to create your first account and start blogging! 🎉

---

## 🔌 Port Reference

| Situation                        | URL to use                            |
|----------------------------------|---------------------------------------|
| Apache on default port 80        | `http://localhost/megablog`           |
| Apache on port 8080              | `http://localhost:8080/megablog`      |
| Apache on port 7080 (IIS active) | `http://localhost:7080/megablog`      |
| phpMyAdmin                       | Same base URL + `/phpmyadmin`         |

> To find your port: open **XAMPP Control Panel** → look at the **Port(s)** column next to Apache.

---

## 🗄️ Database Schema

### users table

```sql
CREATE TABLE users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### posts table

```sql
CREATE TABLE posts (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    user_id        INT NOT NULL,
    title          VARCHAR(255) NOT NULL,
    slug           VARCHAR(255) NOT NULL UNIQUE,
    content        LONGTEXT NOT NULL,
    featured_image VARCHAR(255) DEFAULT NULL,
    status         ENUM('active','inactive') DEFAULT 'active',
    created_at     DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at     DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

---

## 📄 Pages Overview

| Page                 | File              | Access    | Description                                      |
|----------------------|-------------------|-----------|--------------------------------------------------|
| Home                 | `index.php`       | Public    | All active posts in a card grid                  |
| Sign Up              | `signup.php`      | Guest     | Registration form                                |
| Login                | `login.php`       | Guest     | Login form                                       |
| Logout               | `logout.php`      | Auth      | Destroys session, redirects                      |
| Add Post             | `add-post.php`    | Auth      | Create a new post with image upload              |
| Edit Post            | `edit-post.php`   | Owner     | Edit your own post                               |
| Delete Post          | `delete-post.php` | Owner     | Delete your own post                             |
| My Posts             | `all-posts.php`   | Auth      | Table of your posts with actions                 |
| Post Detail          | `post.php`        | Public    | Full post view; edit/delete shown to owner only  |

---

## 🔧 Troubleshooting

| Problem                        | Solution                                                                 |
|-------------------------------|--------------------------------------------------------------------------|
| DB connection failed           | Check `config/db.php` — username, password, DB name must match MySQL     |
| Table does not exist           | Run `megablog_setup.sql` in phpMyAdmin SQL tab under the `megablog` DB   |
| Page not found (404)           | Make sure folder is named `megablog` and `BASE` in `config/db.php` matches |
| Apache won't start             | Port 80 is taken by IIS — change Apache to port 8080 in `httpd.conf`    |
| Images not uploading           | Make sure `uploads/` folder exists inside `megablog/` and is writable    |
| Can't login after signup       | Check that the `users` table was created correctly in phpMyAdmin          |

---

## 🧠 What I Learned

- 🐘 Core PHP sessions, authentication, and password hashing
- 🗃️ MySQL CRUD operations with prepared statements
- 📁 File upload handling and validation in PHP
- 🏗️ Modular PHP project structure (config, includes, pages)
- 🎨 Responsive CSS layout without any framework
- 🔒 Route protection and owner-based access control

---

## 🚀 Future Improvements

- 💬 Comments system on posts
- ❤️ Like / Bookmark feature
- 🔍 Search and filter posts by keyword
- 🏷️ Tags and categories
- 👤 User profile page
- 🌐 Deployment to live server with custom domain
- 📧 Email notifications
- 🔑 Forgot password / reset password flow

---

<p align="center">
  Made with ❤️ using Core PHP & MySQL
</p>