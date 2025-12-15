# BlogCMS — frontend & backend
A secure, role-based blog management platform with full CRUD functionality and user authentication.

Developed as part of the **Backend Development** brief at BlogCMS Corp.

---

## Table of Contents
- [Project Overview](#project-overview)
- [Educational Objectives](#educational-objectives)
- [Application Structure](#application-structure)
- [User Roles & Permissions](#user-roles--permissions)
- [Main Features](#main-features)
- [Security Implementation](#security-implementation)
- [Technologies Used](#technologies-used)
- [Development Workflow](#development-workflow)
- [Database Architecture](#database-architecture)
- [Installation Guide](#installation-guide)
- [License](#license)

---

## Project Overview
**BlogCMS** is a robust content management system that enables companies and individuals to manage their blog content efficiently and securely.

The goal of this project is to provide a complete backend solution with an intuitive administrative dashboard for content creation, user management, and moderation. It implements **strict role-based access control**, **secure authentication**, and **comprehensive CRUD operations** for all content types.

The focus is on **secure PHP development**, **database management with PDO**, **session handling**, and **user permission systems**.

---

## Educational Objectives
- Master **PHP 8 Procedural Programming** for backend logic.
- Implement **Secure Authentication** with bcrypt password hashing.
- Build **Complete CRUD Operations** for articles, categories, comments, and users.
- Develop **Role-Based Access Control** (RBAC) systems.
- Use **PDO with Prepared Statements** to prevent SQL injection.
- Implement **XSS Protection** using htmlspecialchars.
- Manage **PHP Sessions** securely for user authentication.
- Create a **Responsive Admin Dashboard** with modern CSS frameworks.

---

## Application Structure
The platform is organized into distinct sections based on user roles:

| Section | Description |
| :--- | :--- |
| **PUBLIC AREA** | Article listing, single article view, comment section for visitors. |
| **LOGIN SYSTEM** | Secure authentication page with role validation. |
| **ADMIN DASHBOARD** | Statistics overview, user management, moderation panel. |
| **AUTHOR PANEL** | Article management interface for content creators. |

**Core Modules**:
- **Authentication System**: Login/logout with session management.
- **Article Management**: Full CRUD for blog posts.
- **Category System**: Organize content by topics.
- **Comment Moderation**: Review and manage user comments.
- **User Management**: Admin control over accounts and roles.

---

## User Roles & Permissions

### Administrator
**Full System Access** - Complete control over all platform features.
- ✅ View dashboard statistics
- ✅ Create, edit, delete categories
- ✅ Moderate all comments (approve/delete)
- ✅ Manage all users (create/edit/delete)
- ✅ Access all articles from all authors
- ✅ Full content moderation capabilities

### Author (Éditeur)
**Content Creation & Management** - Control over personal content.
- ✅ View published articles
- ✅ Create new articles
- ✅ Edit own articles
- ✅ Delete own articles
- ✅ Post comments on articles
- ❌ Cannot manage other users' content
- ❌ Cannot access admin dashboard

### Visitor (Utilisateur)
**Public Access** - Read and interact with published content.
- ✅ View all published articles
- ✅ Post comments on articles
- ❌ Cannot create or edit articles
- ❌ Cannot access admin areas

---

## Main Features

### 1. **Secure Authentication System**
- **Login Page**: Form validation with error handling.
- **Session Management**: Secure PHP sessions with timeout handling.
- **Password Security**: bcrypt hashing for all passwords.
- **Role Verification**: Middleware to check user permissions on each page.

### 2. **Admin Dashboard**
- **Statistics Overview**: 
    - Total number of articles
    - Total registered users
    - Total comments
    - Pending comments count
- **Category Management**: Full CRUD operations for organizing content.
- **User Management**: Create, edit, and delete user accounts with role assignment.
- **Comment Moderation**: Approve or delete comments from a centralized panel.

### 3. **Article Management (CRUD)**
- **Create Articles**: Rich form with title, content, category selection, and featured image URL.
- **Edit Articles**: Update existing content with pre-filled forms.
- **Delete Articles**: Confirmation system before removal.
- **View Articles**: Public-facing article pages with formatted content.
- **Article Listing**: Display all articles with filters by category or author.

### 4. **Category System**
- **Create Categories**: Simple form for new categories (name, description).
- **Edit Categories**: Update category information.
- **Delete Categories**: Remove categories (with orphan article handling).
- **Category Assignment**: Tag articles with one or multiple categories.

### 5. **Comment System**
- **Post Comments**: All authenticated users can comment on articles.
- **Moderation Queue**: Admins review comments before publication (optional).
- **Delete Comments**: Remove inappropriate or spam comments.
- **Comment Display**: Show approved comments on article pages.

---

## Security Implementation

### Authentication & Authorization
```php
// Secure password hashing
password_hash($password, PASSWORD_BCRYPT)

// Session security
session_regenerate_id(true)

// Role-based access control
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}
```

### SQL Injection Prevention
```php
// PDO Prepared Statements
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute(['email' => $email]);
```

### XSS Protection
```php
// Output escaping
echo htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8');
```

### Form Validation
- **Server-side validation** for all inputs.
- **CSRF protection** (optional enhancement).
- **Input sanitization** before database operations.

---

## Technologies Used

### Backend
- **PHP 8** — Procedural programming for server-side logic.
- **MySQL** — Relational database for data storage.
- **PDO** — Database abstraction layer with prepared statements.

### Frontend
- **HTML5** — Semantic markup for accessibility.
- **CSS3** — Custom styling with responsive design.
- **tailwind** — CSS framework for rapid UI development.
- **JavaScript (Vanilla)** — Form validation and interactive elements.

### Security
- **bcrypt** — Password hashing algorithm.
- **PHP Sessions** — User state management.
- **htmlspecialchars** — XSS attack prevention.

### Tools
- **Git & GitHub** — Version control.
- **XAMPP/MAMP** — Local development environment.
- **phpMyAdmin** — Database management.
- **Visual Studio Code** — Development IDE.

---

## Development Workflow
**Duration**: 5 days  
**Methodology**: Agile / Individual Sprint

**Process**:
1. **Day 1: Foundation & Setup**
    * Database schema implementation (from Brief 6).
    * Project structure and file organization.
    * Authentication system (login/logout).
    * Session management implementation.

2. **Day 2: Core CRUD Operations**
    * Article CRUD (Create, Read, Update, Delete).
    * Category CRUD operations.
    * Author panel interface.
    * Form validation and error handling.

3. **Day 3: Admin Features**
    * Admin dashboard with statistics.
    * User management system (CRUD for users).
    * Comment moderation panel.
    * Role-based access control enforcement.

4. **Day 4: Public Interface & Comments**
    * Public article listing page.
    * Single article view page.
    * Comment posting system.
    * Comment display and moderation workflow.

5. **Day 5: Security & Polish**
    * Complete security audit (XSS, SQL injection tests).
    * Input validation refinement.
    * Responsive design implementation.
    * Code cleanup and documentation.

---

## Database Architecture

### Core Tables
**users** — Store user accounts and authentication data.
- `id` (Primary Key)
- `username` (Unique)
- `email` (Unique)
- `password` (bcrypt hash)
- `role` (admin/author/visitor)
- `created_at`

**articles** — Blog post content.
- `id` (Primary Key)
- `title`
- `content` (TEXT)
- `author_id` (Foreign Key → users)
- `category_id` (Foreign Key → categories)
- `image_url`
- `created_at`
- `updated_at`

**categories** — Content organization.
- `id` (Primary Key)
- `name` (Unique)
- `description`

**comments** — User feedback on articles.
- `id` (Primary Key)
- `article_id` (Foreign Key → articles)
- `user_id` (Foreign Key → users)
- `content` (TEXT)
- `status` (pending/approved/rejected)
- `created_at`

---

## Installation Guide

### Prerequisites
- PHP 8.0 or higher
- MySQL 5.7 or higher
- Apache server (XAMPP/MAMP/WAMP)

### Setup Steps
1. **Clone the repository**
   ```bash
   git clone https://github.com/kara7z/BlogCMS-website.git
   cd blogcms
   ```

2. **Database Configuration**
   - Import the SQL schema from `database/schema.sql`
   - Update database credentials in `config/database.php`

3. **Configure Database Connection**
   ```php
   // config/database.php
   $host = 'localhost';
   $dbname = 'blogcms';
   $username = 'root';
   $password = '';
   ```

4. **Start Local Server**
   - Start Apache and MySQL in XAMPP/MAMP
   - Navigate to `http://localhost/blogcms`

5. **Default Admin Login**
   - Email: `admin@blogcms.com`
   - Password: `admin123`
   - **⚠️ Change immediately after first login**

---

## License
This project was developed for educational purposes as part of the Backend Development curriculum.

© 2025 **BlogCMS**. All rights reserved.

---

**Create. Manage. Publish.**  
*BlogCMS — Empowering content creators with secure, efficient tools.*