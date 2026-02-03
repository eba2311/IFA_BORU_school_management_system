# 🎓 IFA BORU AMURU School Management System (SMS)

**A fully functional Student Management System for IFA BORU AMURU Secondary School built with PHP, MySQL, HTML5, CSS3, and JavaScript.**

---

## 📋 Table of Contents

1. [Project Overview](#project-overview)
2. [Features](#features)
3. [System Requirements](#system-requirements)
4. [Installation Guide](#installation-guide)
5. [Database Setup](#database-setup)
6. [User Roles & Privileges](#user-roles--privileges)
7. [File Structure](#file-structure)
8. [Usage Guide](#usage-guide)
9. [Security Features](#security-features)
10. [API Documentation](#api-documentation)
11. [Troubleshooting](#troubleshooting)

---

## 🎯 Project Overview

The **Student Management System (SMS)** is a comprehensive web-based application designed specifically for **IFA BORU AMURU Secondary School** to manage students, teachers, classes, subjects, and academic grades efficiently.

### Key Objectives:
✅ Reduce manual record keeping  
✅ Improve grade accuracy  
✅ Fast access to student data  
✅ Secure academic records  
✅ Support digital education in Ethiopia 🇪🇹  

**Technology Stack:**
- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Frontend:** HTML5, CSS3, JavaScript
- **Database Library:** PDO (PHP Data Objects)
- **Security:** Password hashing with bcrypt

---

## ✨ Features

### 🔐 Authentication & Security
- Secure login system for Admin, Teachers, and Students
- Password hashing using bcrypt algorithm
- Session-based authentication
- Role-based access control (RBAC)
- Input validation and SQL injection prevention
- Session timeout management

### 👨‍💼 Admin Panel Features
- **Student Management:** Add, edit, delete, and search students
- **Teacher Management:** Add, edit, delete, and manage teachers
- **Subject Management:** Create and manage subjects
- **Class Management:** Assign teachers to subjects and sections
- **Reports:** View comprehensive reports on students, grades, and teachers
- **Settings:** System configuration and settings management
- **Audit Logs:** Track all system activities

### 👨‍🏫 Teacher Portal Features
- **View Assigned Classes:** See all classes assigned to the teacher
- **View Students:** View students in each class
- **Enter Grades:** Input assignment, test, mid-exam, and final exam scores
- **Grade Management:** Edit grades before submission
- **View Performance:** See class performance overview

### 👨‍🎓 Student Portal Features
- **View Profile:** Personal information display
- **View Grades:** See all grades across subjects
- **Calculate GPA:** View average score
- **Print Grades:** Generate and print grade reports
- **View Academic History:** See grades by academic year and semester

---

## 🔧 System Requirements

**Server Requirements:**
- Apache 2.4 or Nginx
- PHP 7.4 or higher
- MySQL 5.7 or MariaDB 10.3+
- PDO extension enabled

**Client Requirements:**
- Modern web browser (Chrome, Firefox, Safari, Edge)
- JavaScript enabled
- Minimum screen resolution: 1024x768px

**Installation:**
```bash
# Clone the repository
git clone https://github.com/YOUR_USERNAME/ifa-boru-school-management-system.git

# Navigate to project directory
cd ifa-boru-school-management-system

# Copy to web server directory (e.g., htdocs for XAMPP)
cp -r . /var/www/html/ifa-boru-sms/
```

---

## 📦 Installation Guide

### Step 1: Download/Clone the Project
```bash
git clone https://github.com/YOUR_USERNAME/ifa-boru-school-management-system.git
cd ifa-boru-school-management-system
```

### Step 2: Configure Database Connection
Edit `config/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
define('DB_NAME', 'ifa_boru_sms');
```

### Step 3: Create Database
```bash
# Navigate to database folder
cd database

# Import schema.sql into your MySQL
mysql -u root -p ifa_boru_sms < schema.sql
```

### Step 4: Set File Permissions
```bash
chmod -R 755 ./
chmod -R 777 ./uploads/
```

### Step 5: Access the Application
Open browser and navigate to:
```
http://localhost/ifa-boru-sms/
```

---

## 🗄️ Database Setup

### Database Schema Overview

**Main Tables:**
1. `admins` - System administrators
2. `teachers` - Teacher information and credentials
3. `students` - Student information
4. `grades` - Grade levels (9-12)
5. `sections` - Class sections (A, B, C)
6. `subjects` - Subject information
7. `classes` - Teacher + Subject + Section combinations
8. `student_grades` - Student marks and grades
9. `settings` - System configuration
10. `audit_logs` - System activity logs

### Creating Database

Run the SQL schema file:
```bash
mysql -u root -p < database/schema.sql
```

### Sample Data

The schema includes:
- Default admin account (username: `admin`, email: `admin@ifaboru.edu.et`)
- 4 grades (9-12)
- 3 sections per grade
- 8 sample subjects (Math, English, Physics, etc.)

---

## 👥 User Roles & Privileges

### 1️⃣ ADMIN (Super Administrator)

**Login Details:**
- Username: `admin`
- Email: `admin@ifaboru.edu.et`
- Password: (Change after first login)

**Privileges:**
- ✅ Manage all students (Add, Edit, Delete)
- ✅ Manage all teachers (Add, Edit, Delete)
- ✅ Manage subjects and classes
- ✅ View comprehensive reports
- ✅ Configure system settings
- ✅ View audit logs

**Dashboard Access:**
- `/admin/dashboard.php` - Admin dashboard
- `/admin/students.php` - Student management
- `/admin/teachers.php` - Teacher management
- `/admin/subjects.php` - Subject management
- `/admin/classes.php` - Class management
- `/admin/reports.php` - Reports & analytics
- `/admin/settings.php` - System settings

### 2️⃣ TEACHER (Limited Access)

**Login:**
- Use username/email created by admin
- Password provided by admin

**Privileges:**
- ✅ View assigned classes
- ✅ View students in classes
- ✅ Enter student grades
- ✅ Edit grades (before deadline)
- ⛔ Cannot delete students or teachers
- ⛔ Cannot delete grades

**Dashboard Access:**
- `/teacher/dashboard.php` - Teacher dashboard
- `/teacher/classes.php` - View classes
- `/teacher/grades.php` - Enter/edit grades

### 3️⃣ STUDENT (View-Only Access)

**Login:**
- Student Code: (provided by school)
- Date of Birth: (used as password)

**Privileges:**
- ✅ View personal profile
- ✅ View grades and scores
- ✅ View academic history
- ✅ Print grade reports
- ⛔ Cannot edit any data

**Dashboard Access:**
- `/student/dashboard.php` - Student dashboard
- `/student/profile.php` - Student profile
- `/student/grades.php` - View grades

---

## 📁 File Structure

```
ifa-boru-school-management-system/
│
├── index.php                          # Main login page
│
├── config/
│   ├── config.php                    # Configuration file (DB credentials)
│   └── Database.php                  # Database connection class
│
├── includes/
│   ├── Auth.php                      # Authentication & session management
│   ├── Validator.php                 # Input validation functions
│   ├── StudentManager.php            # Student CRUD operations
│   └── TeacherManager.php            # Teacher CRUD operations
│
├── admin/
│   ├── header.php                    # Admin header/navigation
│   ├── footer.php                    # Admin footer
│   ├── dashboard.php                 # Admin dashboard
│   ├── students.php                  # Student list & management
│   ├── add_student.php               # Add new student
│   ├── edit_student.php              # Edit student
│   ├── delete_student.php            # Delete student
│   ├── teachers.php                  # Teacher list & management
│   ├── add_teacher.php               # Add new teacher
│   ├── edit_teacher.php              # Edit teacher
│   ├── delete_teacher.php            # Delete teacher
│   ├── subjects.php                  # Subject management
│   ├── classes.php                   # Class management
│   ├── reports.php                   # Reports & analytics
│   ├── settings.php                  # System settings
│   ├── logout.php                    # Admin logout
│   └── get_sections.php              # AJAX endpoint
│
├── teacher/
│   ├── header.php                    # Teacher header/navigation
│   ├── footer.php                    # Teacher footer
│   ├── dashboard.php                 # Teacher dashboard
│   ├── classes.php                   # My classes
│   ├── grades.php                    # Enter grades
│   └── logout.php                    # Teacher logout
│
├── student/
│   ├── header.php                    # Student header/navigation
│   ├── footer.php                    # Student footer
│   ├── dashboard.php                 # Student dashboard
│   ├── profile.php                   # Student profile
│   ├── grades.php                    # View grades
│   └── logout.php                    # Student logout
│
├── assets/
│   ├── css/
│   │   └── style.css                # Stylesheet
│   └── js/
│       └── script.js                # JavaScript functions
│
├── database/
│   └── schema.sql                    # Database schema
│
├── uploads/
│   └── students/                     # Student photos directory
│
└── README.md                          # This file
```

---

## 🚀 Usage Guide

### For Admin

#### 1. Adding a New Student
1. Go to Admin Dashboard → Students
2. Click "➕ Add New Student"
3. Fill in student details:
   - Full Name
   - Date of Birth
   - Gender
   - Grade & Section
   - Parent Information
4. Click "✅ Add Student"

#### 2. Adding a New Teacher
1. Go to Admin Dashboard → Teachers
2. Click "➕ Add New Teacher"
3. Fill in teacher details:
   - Full Name
   - Email
   - Username
   - Password (strong password required)
4. Click "✅ Add Teacher"

#### 3. Creating a Class
1. Go to Admin Dashboard → Classes
2. Click "➕ Create New Class"
3. Select:
   - Teacher
   - Subject
   - Section/Grade
   - Academic Year
   - Semester
4. Click "➕ Create Class"

#### 4. Viewing Reports
1. Go to Admin Dashboard → Reports
2. Select report type:
   - Overview (Statistics)
   - Student Report
   - Grades Report
   - Teacher Report
3. Click "🖨️ Print Report" to print

### For Teacher

#### 1. Viewing Classes
1. Login as teacher
2. Go to "🎓 My Classes"
3. View all assigned classes with student count

#### 2. Entering Grades
1. Go to "📝 Enter Grades"
2. Select academic year and semester
3. For each student, enter:
   - Assignment score (0-10)
   - Test score (0-10)
   - Mid Exam (0-20)
   - Final Exam (0-60)
4. Total score and grade letter are calculated automatically
5. Click "💾 Save Grades"

### For Student

#### 1. Viewing Profile
1. Login as student
2. Click "👤 My Profile"
3. View all personal and academic information

#### 2. Viewing Grades
1. Go to "📝 My Grades"
2. View all grades by subject
3. See average score calculation
4. Click "🖨️ Print Grades" to print report

---

## 🔒 Security Features

### 1. Password Security
- Passwords hashed using bcrypt (PHP's `password_hash()`)
- Minimum 8 characters required
- Recommendation: Mix of uppercase, lowercase, numbers, special characters

### 2. Session Management
- Session timeout after 30 minutes of inactivity
- Secure session storage
- Automatic logout on timeout

### 3. Input Validation
- All user inputs sanitized
- SQL injection prevention using prepared statements (PDO)
- Email validation
- Phone number validation
- Date validation

### 4. Access Control
- Role-based access control (RBAC)
- Login authentication required for all pages
- Teachers can only access their assigned classes
- Students can only view their own information

### 5. Database Security
- PDO parameterized queries
- Proper foreign key constraints
- Audit logs for grade changes

### 6. File Upload Security
- File type validation
- File size limits
- Secure upload directory

---

## 📚 API Documentation

### AJAX Endpoints

#### Get Sections by Grade
**Endpoint:** `admin/get_sections.php`
**Method:** GET
**Parameters:**
- `grade_id` (int) - Grade ID

**Response:**
```json
[
    {
        "section_id": 1,
        "grade_id": 1,
        "section_name": "A"
    }
]
```

---

## 🐛 Troubleshooting

### Issue: "Database Connection Error"
**Solution:**
1. Check database credentials in `config/config.php`
2. Verify MySQL is running
3. Ensure database exists: `ifa_boru_sms`
4. Check user permissions

### Issue: "Login Page Not Loading"
**Solution:**
1. Check if PHP is installed and enabled
2. Verify Apache/Nginx is running
3. Check file permissions (should be 755)
4. Review error logs

### Issue: "Grades Not Saving"
**Solution:**
1. Verify student and class exist
2. Check date format (YYYY-MM-DD)
3. Ensure all required fields are filled
4. Check database table `student_grades`

### Issue: "Students Not Appearing in Class"
**Solution:**
1. Verify student is assigned to correct section
2. Check section-grade relationship
3. Ensure student status is "Active"

---

## 📝 Default Login Credentials

| Role | Username | Email | Password |
|------|----------|-------|----------|
| Admin | admin | admin@ifaboru.edu.et | (See config) |
| Teacher | (Created by Admin) | - | (Created by Admin) |
| Student | (Student Code) | - | (Date of Birth) |

**⚠️ IMPORTANT: Change admin password immediately after first login!**

---

## 🔄 Default Admin Account Password

The default password is hashed. To change it:

1. Login to admin panel
2. Go to Settings
3. Create a new admin account with strong password
4. Delete the old account

Or use this PHP command:
```php
<?php
$new_password = password_hash('your_new_password', PASSWORD_BCRYPT, ['cost' => 10]);
echo $new_password; // Copy this hash
?>
```

Update in database:
```sql
UPDATE admins SET password = '[HASHED_PASSWORD]' WHERE admin_id = 1;
```

---

## 📄 Grading Scale

| Grade | Score Range | Description |
|-------|-------------|-------------|
| A | 90-100 | Excellent |
| B | 80-89 | Good |
| C | 70-79 | Satisfactory |
| D | 60-69 | Pass |
| F | 0-59 | Fail |

---

## 🎓 System Workflow

```
1. Admin creates Teachers & Students
   ↓
2. Admin assigns Teachers to Subjects & Classes
   ↓
3. Teacher logs in and views assigned classes
   ↓
4. Teacher enters student grades
   ↓
5. Admin reviews grades and generates reports
   ↓
6. Student logs in and views their grades
   ↓
7. Student can print grade report
```

---

## 📞 Support & Contact

**School:** IFA BORU AMURU Secondary School  
**Location:** Ethiopia 🇪🇹  
**Academic Year:** 2026  

For technical issues or feature requests, please contact the system administrator.

---

## 📜 License

This project is developed for IFA BORU AMURU Secondary School.

---

## ✅ Checklist for Deployment

- [ ] Configure database credentials in `config/config.php`
- [ ] Create MySQL database and import schema
- [ ] Set file permissions (755 for files, 777 for uploads)
- [ ] Change admin password
- [ ] Test all user roles (Admin, Teacher, Student)
- [ ] Verify database backups are working
- [ ] Check email notifications (if enabled)
- [ ] Test grade entry and report generation
- [ ] Verify SSL/HTTPS (for production)

---

**Version:** 1.0.0  
**Last Updated:** January 31, 2026  
**Developer:** GitHub Copilot  
**Status:** ✅ Production Ready