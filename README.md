# 🌐 Web Programming Midterm Project - Character Management App

This project is a web application developed to fulfill the **Web Programming Midterm Exam (UTS)** assignment.
The application implements a **character management system** with authentication features and separate dashboards for users and admins.

---

## 📌 Description

This application allows users to:

* Register and log in
* Access dashboards based on roles (user/admin)
* Manage character data (CRUD)

This project combines:

* Modern frontend (Vite + JavaScript)
* Backend API using PHP
* Database for data storage

---

## 🚀 Main Features

### 🔐 Authentication

* User & admin login
* Account registration
* Logout

### 👤 User

* View character list
* View character details

### 🛠️ Admin

* Admin dashboard
* Add new characters
* Edit character data
* Delete character data

---

## 🛠️ Technologies Used

### Frontend

* HTML5
* CSS3
* JavaScript (Vite build system)

### Backend

* PHP (simple REST API)

### Database

* MySQL (via `db.php`)

---

## 📂 Project Structure

```
UTSPemweb/
│── index.html              # Frontend entry point
│── package.json            # Dependencies & Vite config
│── vite.config.ts          # Vite configuration
│
├── api/                    # Backend API (PHP)
│   ├── auth.php            # Login & registration
│   ├── characters.php      # Character CRUD
│   ├── db.php              # Database connection
│   └── migrate.php         # Database setup
│
├── dist/                   # Built frontend output
│
├── backup/                 # Legacy version (native PHP)
│
└── README.md
```

---

## ▶️ How to Run

### 1. Clone Repository

```bash
git clone https://github.com/DelfanDZW/UTSPemweb.git
cd UTSPemweb
```

---

### 2. Run Backend (PHP Server)

To make authentication (login/register) work, you must run the PHP backend server.

Open a new terminal and run:

```bash
php -S localhost:8000
```

This will start the backend API at:

```
http://localhost:8000
```

---

### 3. Setup Database

* Configure your database in `api/db.php`
* Import database manually or run:

```bash
php api/migrate.php
```

---

### 4. Run Frontend (Vite)

Install dependencies:

```bash
npm install
```

Run development server:

```bash
npm run dev
```

Frontend will run on:

```
http://localhost:5173
```

---

### ⚠️ Important Notes

* Registration & login **will NOT work** if PHP server is not running
* Make sure API URL in frontend points to:

  ```
  http://localhost:8000/api/
  ```
* If you get CORS errors:

  * Allow CORS in PHP
  * Or ensure both servers are correctly connected

---

### 💡 Alternative (XAMPP / Laragon)

You can also run backend using:

* XAMPP → `htdocs`
* Laragon → `www`

But using:

```bash
php -S localhost:8000
```

is the simplest method


## 👨‍💻 Author

* **Delfan DZW**
* GitHub: [https://github.com/DelfanDZW](https://github.com/DelfanDZW)

---

## 📚 Notes

* The `backup/` folder contains an older version using native PHP (without modern frontend)
* The `dist/` folder contains the Vite build output (ready for deployment)

---

## 📄 License

This project is intended for academic and learning purposes.
