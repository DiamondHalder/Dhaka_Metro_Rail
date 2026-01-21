### 🚀 Project Demo
![Metro Rail Walkthrough](projectScreenshots.gif)


# 🚉 Dhaka Metro Rail Management System

A dynamic and feature-rich Metro Rail Management System built using **PHP**, **Oracle 10g**, and **Tailwind CSS**. This project provides a complete solution for passenger ticket booking and administrative sales reporting.

---

### 🚀 Project Demo
<p align="center">
  <img src="projectScreenshots.gif" width="700" alt="Metro Rail Demo Walkthrough">
</p>

---

### ✨ Key Features

- **Dual Authentication:** Separate secure login portals for both **Passengers** and **Employees**.
- **Real-time Fare Calculation:** Automatically fetches the ticket price from the Oracle database based on selected source and destination using **AJAX**.
- **Secure Payment Simulation:** Features a 5-second processing buffer to simulate a realistic payment gateway experience.
- **E-Ticket Generation:** Instantly generates a digital ticket upon successful purchase with an option to **Download as PDF**.
- **Travel History:** Passengers can track all their previous journeys, dates, and fare details in one place.
- **Admin Dashboard:** Employees can monitor total sales, view passenger lists, and generate detailed revenue reports.
- **Robust Database Backup:** Uses Oracle 10g Export utility to ensure data integrity and easy restoration.

---

### 🛠️ Tech Stack

- **Frontend:** HTML5, Tailwind CSS, JavaScript (AJAX)
- **Backend:** PHP (using `oci8` extension for Oracle connectivity)
- **Database:** Oracle 10g Express Edition
- **Reporting:** PDF Generation Library (e.g., FPDF/DomPDF)

---

### 📸 Screenshots

<details>
  <summary><b>👁️ Click to view Project Gallery</b></summary>
  
  <p align="center">
    <img src="screenshots/index.png" width="400" alt="Landing Page">
    <img src="screenshots/login.png" width="400" alt="Login Page">
    <br>
    <img src="screenshots/dashboard.png" width="400" alt="Passenger Dashboard">
    <img src="screenshots/ticket.png" width="400" alt="PDF Ticket Sample">
  </p>
</details>

---

### ⚙️ How to Setup

1. **Database Setup:** - Create a user in your Oracle 10g instance.
   - Import the provided `.sql` or `.dmp` backup file to set up the 16 relational tables.
2. **PHP Configuration:** - Open your `php.ini` file and enable the extension by removing the semicolon: `extension=oci8`.
3. **Connection:** - Update your database credentials (Username/Password) in the `config/db.php` file.
4. **Run Project:** - Place the project folder in your XAMPP `htdocs` directory.
   - Access it via `localhost/Dhaka_Metro` in your web browser.

---

### 📺 Video Walkthrough
[Watch the full demonstration on YouTube](YOUR_YOUTUBE_LINK_HERE)

---

Developed with ❤️ by **Diamond Halder**
