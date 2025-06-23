# Employee_Management_System
Using PHP

# 🧑‍💼 Employee Management System

A simple web-based Employee Management System built using **PHP**, **MySQL**, **Bootstrap**, and **JavaScript**. It includes login & registration, employee CRUD (Create, Read, Update, Delete), department assignment, profile image support, and export functionality.

---

## 🚀 Features

- 🔐 Manager Login and Registration  
- 👤 Manager Profile with Department and Photo  
- 👥 Manage Employees (Add, Edit, Delete)  
- 📁 Export Employee Data:  
  - 📊 Export to CSV (Excel-compatible)  
  - 📄 Export to PDF using TCPDF  
- 🏢 Department-wise assignment  
- 🖼 Upload Profile Pictures  
- ✨ Clean UI using Bootstrap 5  

---

## 🗂️ Project Structure

```
employee-management/
│
├── auth/
│   ├── login.php
│   └── register.php
│
├── dashboard/
│   └── index.php          # Manager Dashboard
│
├── employees/
│   ├── employee_mng.php   # List employees
│   ├── add_emp.php
│   ├── edit.php
│   └── delete.php
│
├── export/
│   ├── export_pdf.php
│   └── export_csv.php
│
├── profile_img/           # Uploaded profile images
├── db.php                 # MySQL connection script
├── vendor/                # Composer packages (TCPDF etc.)
└── README.md
```

---

## 🛠️ Setup Instructions

### 1. Requirements

- PHP 7.4 or newer  
- MySQL Database  
- Apache/Nginx Server (XAMPP/LAMP recommended)  
- Composer (for PDF export via TCPDF)  

---

### 2. Database Setup

Run the SQL below to create the required tables:

```sql
CREATE TABLE departments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

CREATE TABLE managers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100) UNIQUE,
  password VARCHAR(255),
  department_id INT,
  profile_pic VARCHAR(255),
  FOREIGN KEY (department_id) REFERENCES departments(id)
);

CREATE TABLE employees (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100),
  salary DECIMAL(10,2),
  department_id INT,
  manager_id INT,
  profile_pic VARCHAR(255),
  FOREIGN KEY (department_id) REFERENCES departments(id),
  FOREIGN KEY (manager_id) REFERENCES managers(id)
);
```

---

### 3. Database Configuration

Edit your `db.php` file:

```php
<?php
$conn = new mysqli("localhost", "root", "", "employee_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
```

---

### 4. Install Dependencies

Install **TCPDF** for PDF export:

```bash
composer require tecnickcom/tcpdf
```

Make sure the `vendor/` folder is inside your project directory and `require '../vendor/autoload.php';` is included in `export_pdf.php`.

---

## 📤 Export Features

### Export to CSV

- **File:** `export/export_csv.php`  
- Automatically downloads a `.csv` file with employee details including department and manager info.

### Export to PDF

- **File:** `export/export_pdf.php`  
- Uses TCPDF to generate a nicely formatted employee report PDF.

---

## 📸 Profile Images

- Profile pictures are uploaded and stored in the `profile_img/` directory.  
- Make sure the folder is writable (`chmod 755 profile_img` or use folder permissions in Windows).

---

## 🔐 Manager Credentials

- Managers must register using `register.php`.  
- Each manager is assigned a department.  
- Upon login, they can only view/manage employees in their department.

---

## 📌 Security Tips

- ✅ Use `password_hash()` and `password_verify()` instead of storing plain passwords  
- ✅ Always validate and sanitize file uploads  
- ✅ Use prepared statements to prevent SQL injection (already implemented)  
- ✅ Add CSRF protection in forms for production

---

## 📦 To-Do / Improvements

- Password hashing & recovery  
- Employee search & filter  
- Department management via dashboard  
- Role-based access control  

---

## 👨‍💻 Author

Developed by **[Your Name]**  
Feel free to contribute or fork the project!

---

## 📜 License

This project is **open-source** and free to use for educational or commercial purposes.
