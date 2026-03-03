# 🌐 ByteBlog

ByteBlog is a clean, functional PHP-based blogging platform. It allows users to create accounts, manage profiles, and publish articles with image support.

---

## ✨ Features
* **User System:** Secure Registration, Login, and Logout.
* **Profile Management:** Users can edit their profiles (`Editprof.php`).
* **Content Creation:** Create, view, and manage blog posts.
* **Comments:** Interactive comment section for every article.
* **Email Support:** Integrated with PHPMailer for automated notifications.
* **Dashboard:** A central hub for users to manage their content.

---

## 📸 Screenshots
*(Tip: Add a screenshot of your homepage here to make it look even better!)*

---

## 🛠️ Project Structure
* `/assets` - CSS, JavaScript, and site-wide images.
* `/config` - Database connection and system settings.
* `/includes` - Reusable PHP snippets (header, footer, functions).
* `/posts` - Logic related to blog entries.
* `/PHPmailer` - Handles email sending functionality.

---

## 🚀 How to Install
1. **Clone the project:** Download these files into your local server folder (like `htdocs` for XAMPP).
   
2. **Setup the Database:**
   * Open PHPMyAdmin.
   * Create a new database named `byteblog`.
   * Import the `.sql` file (if provided) or create the necessary tables.

3. **Configure:**
   * Open `config/config.php` and enter your database credentials (host, username, password).

4. **Run:**
   * Open your browser and go to `localhost/ByteBlog/index.php`.

---

## 📝 Technologies Used
* **Backend:** PHP
* **Database:** MySQL
* **Email:** PHPMailer
* **Frontend:** HTML5, CSS3

---

## 📄 License
This project is open-source and free to use.