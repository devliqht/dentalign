<div align="center">
  <img src="public/cover.png" alt="Dentalign Cover" width="800"/>

# Dentalign
  
  **A Modern Clinic Management System**
  
  [![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net/)
  [![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
  [![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://javascript.com/)
  [![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com/)
  [![Apache](https://img.shields.io/badge/Apache-D22128?style=for-the-badge&logo=apache&logoColor=white)](https://apache.org/)
  
</div>

## 📋 Overview

Dentalign is a comprehensive clinic management system designed specifically for dental practices. Built with modern web technologies and following MVC architecture principles, it provides a streamlined solution for managing appointments, patient records, treatments, and administrative tasks.

## ✨ Features

- **Patient Management**: Complete patient record system with treatment history
- **Appointment Scheduling**: Advanced calendar system with conflict detection
- **Treatment Planning**: Digital dental charts and treatment documentation
- **Payment Processing**: Integrated billing and payment tracking
- **Staff Management**: Role-based access control for different user types
- **Reporting**: Comprehensive analytics and reporting tools
- **Responsive Design**: Mobile-friendly interface built with Tailwind CSS

## 🚀 Tech Stack

### Backend
- **PHP**
- **MySQL**
- **Apache**

### Frontend
- **HTML5/CSS3**
- **Tailwind CSS**
- **JavaScript**

### Development Tools
- **Bun**
- **XAMPP**
- **Git**

## Screenshots

<img src="public/screenshots/ss1.png" alt="Dashboard Overview" width="800"/>

<img src="public/screenshots/ss2.png" alt="Patient Records" width="800"/>

<img src="public/screenshots/ss3.png" alt="Appointment Calendar" width="800"/>

<img src="public/screenshots/ss4.png" alt="Treatment Plans" width="800"/>

<img src="public/screenshots/ss5.png" alt="Dental Chart" width="800"/>

<img src="public/screenshots/ss6.png" alt="Payment Management" width="800"/>

<img src="public/screenshots/ss7.png" alt="Staff Dashboard" width="800"/>

<img src="public/screenshots/ss8.png" alt="Patient Portal" width="800"/>

<img src="public/screenshots/ss9.png" alt="Appointment Booking" width="800"/>

<img src="public/screenshots/ss10.png" alt="Analytics Dashboard" width="800"/>

<img src="public/screenshots/ss11.png" alt="Mobile Interface" width="800"/>

## 📦 Installation

### Prerequisites

Before you begin, ensure you have the following installed:
- [XAMPP](https://www.apachefriends.org/download.html) (Apache + MySQL + PHP)
- [Bun](https://bun.sh/) (JavaScript runtime)
- [Git](https://git-scm.com/)

### Getting Started

1. **Clone the repository**
   ```bash
   git clone https://github.com/devliqht/dentalign.git
   cd dentalign
   ```

2. **Install dependencies**
```bash
bun install
```

3. **Set up the database**
   - Start XAMPP and ensure Apache and MySQL services are running
   - Create a new database named `dentalign`
   - Import the SQL file located in `storage/dentalign_070425.sql`

4. **Configure the application**
   - Update database connection settings in `config/DB_Connect.php`
   - Adjust base URL in `config/Base_Url.php` if needed

5. **Start the development server**
```bash
   # Start Tailwind CSS watcher
bun run dev
```

6. **Access the application**
   - Open your browser and navigate to `http://localhost/dentalign`
   - Or use the path where you placed the project in your XAMPP htdocs directory

## 🛠️ Development

### Code Formatting
```bash
bun run prettier
```

### Project Structure
```
dentalign/
├── app/
│   ├── controllers/     # Application controllers
│   ├── models/         # Data models
│   ├── views/          # View templates
│   └── middleware/     # Authentication middleware
├── config/             # Configuration files
├── public/             # Static assets
├── routes/             # Route definitions
└── storage/            # Database files and migrations
```

## 📱 Usage

1. **Login**: Access the system with your credentials
2. **Dashboard**: View overview of appointments and tasks
3. **Patient Management**: Add, edit, and view patient records
4. **Scheduling**: Book and manage appointments
5. **Treatments**: Document dental procedures and treatments
6. **Billing**: Process payments and generate invoices

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

