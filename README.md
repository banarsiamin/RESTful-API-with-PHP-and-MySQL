# RESTful API with PHP, MySQL, and JWT Authentication

**Author:** Amin Khan Banarsi  
**Version:** 1.0.0

## 📝 Table of Contents
- [Project Overview](#-project-overview)
- [Features](#-features)
- [Installation](#-installation)
- [API Endpoints](#-api-endpoints)
- [Database Schema](#2-setup-steps)
- [Authentication Flow](#-jwt-authentication-flow)
- [Project Structure](#-project-structure)
- [Testing the API](#-testing-the-api)
- [Troubleshooting](#-troubleshooting)
- [License](#-license)
- [Contact](#-contact)

## 🌟 Project Overview

This project is a RESTful API built with PHP and MySQL, featuring:

✅ User Authentication (Register, Login, Logout)  
✅ JWT (JSON Web Token) Authentication (Custom Implementation)  
✅ User Roles (Admin & Regular User)  
✅ CRUD Operations for Posts  
✅ Admin Privileges (Manage Users & Posts)

## 🚀 Features

### 1. User Authentication
- Register new users (`/auth → action=register`)
- Login with JWT token generation (`/auth → action=login`)
- Protected routes using JWT

### 2. User Management
- Admin can view, add, edit, and delete users (`/users`)
- Regular users can update their own profile

### 3. Post Management
- Users can create, read, update, and delete posts (`/posts`)
- Admins can manage all posts

### 4. Custom JWT Implementation
- Secure token-based authentication
- Token expiration & validation

## 💻 Installation

### 1. Prerequisites
- PHP 7.4+
- MySQL 5.7+
- Apache/Nginx

### 2. Setup Steps
1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/RESTful-API-with-PHP-and-MySQL
   cd RESTful-API-with-PHP-and-MySQL
   
2. Database Setup
   Run the following SQL script:
   ```bash
   CREATE DATABASE rest_api;
    USE rest_api;

    CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL,
        user_type ENUM('user', 'admin') DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    CREATE TABLE posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    );

3. Configuration
   Update api/config/Database.php with your MySQL credentials:
   ```bash
      private $host = "localhost";
      private $db_name = "rest_api";
      private $username = "root"; // Your MySQL username
      private $password = "";     // Your MySQL password

4. Run the API
   - Place the project in your web server (e.g., localhost/RESTful-API-with-PHP-and-MySQL).
   - Use Postman or cURL to test endpoints.

## 📡 API Endpoints
    | Endpoint | Method | Description                    | Required Fields                            |
    |----------|--------|--------------------------------|--------------------------------------------|
    | /auth    | POST   | Register a new user            | action=register, username, password, email |
    | /auth    | POST   | Login &amp; get JWT token      | action=login, username, password           |
    | /users   | GET    | Get all users (Admin only)     | Authorization: Bearer &lt;JWT&gt;          |
    | /users   | POST   | Create a new user (Admin only) | username, password, email                  |
    | /users   | PUT    | Update user profile            | username, email, password                  |
    | /users   | DELETE | Delete a user (Admin only)     | id                                         |
    | /posts   | GET    | Get all posts                  | Authorization: Bearer &lt;JWT&gt;          |
    | /posts   | POST   | Create a new post              | title, content                             |
    | /posts   | PUT    | Update a post                  | id, title, content                         |
    | /posts   | DELETE | Delete a post                  | id                                         |


## 🔒 JWT Authentication Flow
    -1.Register → /auth (action=register)
    -2.Login → /auth (action=login) → Returns JWT
    -3.Access Protected Routes → Include Authorization: Bearer <JWT>

## 📂 Project Structure
    RESTful-API-with-PHP-and-MySQL/
    ├── api/
    │   ├── config/
    │   │   └── Database.php
    │   ├── controllers/
    │   │   ├── AuthController.php
    │   │   ├── UserController.php
    │   │   └── PostController.php
    │   ├── models/
    │   │   ├── User.php
    │   │   └── Post.php
    │   └── utils/
    │       └── JwtHandler.php
    ├── index.php
    ├── .htaccess
    └── README.md
## 🧪 Testing the API
### Register a User
    curl -X POST http://localhost/RESTful-API-with-PHP-and-MySQL/auth \
    -H "Content-Type: application/json" \
    -d '{"action":"register","username":"testuser","password":"test123","email":"test@example.com"}'
### Login
    curl -X POST http://localhost/RESTful-API-with-PHP-and-MySQL/auth \
    -H "Content-Type: application/json" \
    -d '{"action":"login","username":"testuser","password":"test123"}'
### Create Post (with JWT)
    curl -X POST http://localhost/RESTful-API-with-PHP-and-MySQL/posts \
    -H "Content-Type: application/json" \
    -H "Authorization: Bearer your.jwt.token" \
    -d '{"title":"First Post","content":"Hello World!"}'

## 🚨 Troubleshooting
    - 403 Forbidden → Check JWT token & user permissions.
    - 500 Server Error → Verify MySQL connection & table structure.
    - 404 Not Found → Ensure .htaccess is properly configured.
## 📜 License 
    This project is open-source under the MIT License.

## 📧 Contact
    Author: Amin Khan Banarsi
    Email: [banarsiamin[at]gmail.com]
    GitHub: [https://github.com/banarsiamin/]

## 🌟 Happy Coding! 🚀

This README includes:
1. Clear section headings with emojis
2. Proper code blocks with syntax highlighting
3. Well-organized tables for endpoints
4. Concise installation instructions
5. Practical usage examples
6. Visual project structure
7. Comprehensive troubleshooting guide
8. Proper license and contact information

The formatting is clean and professional, making it easy for developers to understand and use your API.
