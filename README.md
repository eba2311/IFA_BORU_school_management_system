# IFA_BORU_school_management_system


# 🎓 IFA BORU AMURU School Management System

A comprehensive web-based School Management System built with PHP, MySQL, HTML5, CSS3, and JavaScript for IFA BORU AMURU Secondary School in Ethiopia.

![School Management System](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

## 📋 Table of Contents

- [Features](#-features)
- [Demo](#-demo)
- [Installation](#-installation)
- [Usage](#-usage)
- [System Requirements](#-system-requirements)
- [File Structure](#-file-structure)
- [User Roles](#-user-roles)
- [Screenshots](#-screenshots)
- [Contributing](#-contributing)
- [License](#-license)
- [Support](#-support)

## ✨ Features

### 🔐 Multi-Role Authentication System
- **Admin Portal** - Complete system administration
- **Teacher Portal** - Grade management and class oversight
- **Student Portal** - Grade viewing and profile management

### 👨‍💼 Admin Features
- ✅ **Student Management** - Add, edit, delete, and search students
- ✅ **Teacher Management** - Manage teacher accounts and assignments
- ✅ **Subject Management** - Create and organize subjects
- ✅ **Class Management** - Assign teachers to subjects and sections
- ✅ **Grade Management** - Oversee all academic grades
- ✅ **Reports & Analytics** - Comprehensive reporting system
- ✅ **Student Rankings** - Performance rankings with class averages
- ✅ **System Settings** - Configure school parameters

### �‍🏫 Teacher Features
- ✅ **Class Overview** - View assigned classes and students
- ✅ **Grade Entry** - Enter and manage student grades
- ✅ **Student Management** - View student information
- ✅ **Performance Tracking** - Monitor class performance

### 👨‍🎓 Student Features
- ✅ **Grade Viewing** - View all academic grades
- ✅ **Profile Management** - View personal information
- ✅ **Performance Analytics** - Track academic progress
- ✅ **Print Reports** - Generate grade reports

### 🌐 Public Website
- ✅ **Home Page** - School information and portal access
- ✅ **About Page** - School history and mission
- ✅ **Contact Page** - Contact information and location

## 🚀 Demo

### Default Login Credentials

**Admin Access:**
- Username: `admin`
- Password: `admin123`

**Teacher Access:**
- Username: `sarahsmith`
- Password: `teacher123`

**Student Access:**
- Student Code: `STU20260001`
- Date of Birth: `2005-01-15`

## � Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or MariaDB 10.3+
- Apache/Nginx web server
- PDO extension enabled

### Quick Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/ifa-boru-school-management-system.git
   cd ifa-boru-school-management-system
   ```

2. **Configure database**
   ```bash
   # Copy to your web server directory
   cp -r . /var/www/html/ifa-boru-sms/
   
   # Edit database configuration
   nano config/config.php
   ```

3. **Set up database**
   ```sql
   CREATE DATABASE ifa_boru_sms;
   mysql -u root -p ifa_boru_sms < database/schema.sql
   ```

4. **Configure permissions**
   ```bash
   chmod -R 755 ./
   chmod -R 777 ./uploads/
   ```

5. **Access the system**
   ```
   http://localhost/ifa-boru-sms/
   ```

### Alternative Installation Methods

#### Method 1: Installation Wizard
```
http://localhost/ifa-boru-sms/install.php
```

#### Method 2: Quick Setup Scripts
```bash
# Setup admin account
http://localhost/ifa-boru-sms/setup_admin.php

# Create teacher account
http://localhost/ifa-boru-sms/create_teacher.php

# Create student account
http://localhost/ifa-boru-sms/create_student.php
```

## 🎯 Usage

### For Administrators
1. Login with admin credentials
2. Add teachers and students
3. Create subjects and classes
4. Assign teachers to classes
5. Monitor grades and generate reports

### For Teachers
1. Login with teacher credentials
2. View assigned classes
3. Enter student grades
4. Monitor class performance

### For Students
1. Login with student code and date of birth
2. View grades and academic progress
3. Print grade reports

## 💻 System Requirements

### Server Requirements
- **PHP:** 7.4+ with PDO extension
- **Database:** MySQL 5.7+ or MariaDB 10.3+
- **Web Server:** Apache 2.4+ or Nginx
- **Memory:** 512MB RAM minimum
- **Storage:** 100MB disk space

### Client Requirements
- **Browser:** Chrome, Firefox, Safari, Edge (latest versions)
- **JavaScript:** Enabled
- **Screen Resolution:** 1024x768 minimum
- **Internet Connection:** Required for initial setup

## 📁 File Structure

```
ifa-boru-school-management-system/
├── 📁 admin/                    # Admin panel files
│   ├── dashboard.php           # Admin dashboard
│   ├── students.php           # Student management
│   ├── teachers.php           # Teacher management
│   ├── subjects.php           # Subject management
│   ├── classes.php            # Class management
│   ├── reports.php            # Reports system
│   ├── rankings.php           # Student rankings
│   └── settings.php           # System settings
├── 📁 teacher/                  # Teacher portal files
│   ├── dashboard.php          # Teacher dashboard
│   ├── classes.php            # My classes
│   └── grades.php             # Grade entry
├── 📁 student/                  # Student portal files
│   ├── dashboard.php          # Student dashboard
│   ├── profile.php            # Student profile
│   └── grades.php             # View grades
├── 📁 includes/                 # PHP classes and functions
│   ├── Auth.php               # Authentication system
│   ├── StudentManager.php     # Student operations
│   ├── TeacherManager.php     # Teacher operations
│   └── Validator.php          # Input validation
├── 📁 config/                   # Configuration files
│   ├── config.php             # Database configuration
│   └── Database.php           # Database connection
├── 📁 database/                 # Database files
│   └── schema.sql             # Database schema
├── 📁 assets/                   # Static assets
│   ├── css/                   # Stylesheets
│   └── js/                    # JavaScript files
├── index.php                   # Main login page
├── home.php                    # Public home page
├── about.php                   # About page
├── contact.php                 # Contact page
└── install.php                 # Installation wizard
```

## 👥 User Roles

### 🔴 Administrator
- **Full System Access**
- Manage students, teachers, subjects, classes
- Generate reports and analytics
- Configure system settings
- View student rankings

### 🟡 Teacher
- **Limited Access**
- View assigned classes
- Enter and edit grades
- View student information
- Monitor class performance

### 🟢 Student
- **View-Only Access**
- View personal grades
- View profile information
- Print grade reports
- Track academic progress

## 📸 Screenshots

### Admin Dashboard
![Admin Dashboard](https://via.placeholder.com/800x400/667eea/ffffff?text=Admin+Dashboard)

### Teacher Portal
![Teacher Portal](https://via.placeholder.com/800x400/28a745/ffffff?text=Teacher+Portal)

### Student Portal
![Student Portal](https://via.placeholder.com/800x400/17a2b8/ffffff?text=Student+Portal)

### Student Rankings
![Student Rankings](https://via.placeholder.com/800x400/ffc107/333333?text=Student+Rankings)

## 🛠️ Technology Stack

- **Backend:** PHP 7.4+ with PDO
- **Database:** MySQL 5.7+ / MariaDB 10.3+
- **Frontend:** HTML5, CSS3, JavaScript
- **Security:** bcrypt password hashing, prepared statements
- **Architecture:** MVC pattern with object-oriented PHP

## 🔒 Security Features

- ✅ **Password Hashing** - bcrypt encryption
- ✅ **SQL Injection Prevention** - PDO prepared statements
- ✅ **Session Management** - Secure session handling
- ✅ **Input Validation** - Server-side validation
- ✅ **Role-Based Access Control** - User permission system
- ✅ **CSRF Protection** - Cross-site request forgery prevention

## 📊 Database Schema

### Main Tables
- `admins` - System administrators
- `teachers` - Teacher information and credentials
- `students` - Student records
- `grades` - Grade levels (9-12)
- `sections` - Class sections (A, B, C)
- `subjects` - Subject information
- `classes` - Teacher + Subject + Section assignments
- `student_grades` - Academic grades and scores
- `settings` - System configuration
- `audit_logs` - System activity logs

## 🎓 Academic Features

### Grading System
- **Assignment:** 10 points
- **Test:** 10 points
- **Mid Exam:** 20 points
- **Final Exam:** 60 points
- **Total:** 100 points

### Grade Scale
- **A:** 90-100 (Excellent)
- **B:** 80-89 (Good)
- **C:** 70-79 (Satisfactory)
- **D:** 60-69 (Pass)
- **F:** 0-59 (Fail)

## 🌍 Localization

- **Primary Language:** English
- **Region:** Ethiopia 🇪🇹
- **Academic Year:** 2026
- **Grade Levels:** 9, 10, 11, 12
- **Phone Format:** Ethiopian (+251)

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. **Fork the repository**
2. **Create a feature branch**
   ```bash
   git checkout -b feature/amazing-feature
   ```
3. **Commit your changes**
   ```bash
   git commit -m 'Add amazing feature'
   ```
4. **Push to the branch**
   ```bash
   git push origin feature/amazing-feature
   ```
5. **Open a Pull Request**

### Development Guidelines
- Follow PSR-4 autoloading standards
- Use meaningful commit messages
- Add comments for complex logic
- Test all functionality before submitting
- Maintain responsive design principles

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

### Documentation
- [Installation Guide](docs/installation.md)
- [User Manual](docs/user-manual.md)
- [API Documentation](docs/api.md)
- [Troubleshooting](docs/troubleshooting.md)


### Common Issues
- **Database Connection:** Check config/config.php settings
- **Permission Errors:** Ensure proper file permissions (755/777)
- **Login Issues:** Verify user credentials and database data
- **Missing Features:** Check if all files are uploaded correctly

## 🏆 Acknowledgments

- **School:** IFA BORU AMURU Secondary School
- **Location:** Ethiopia 🇪🇹
- **Academic Year:** 2026
- **Development:** Built with ❤️ for education

