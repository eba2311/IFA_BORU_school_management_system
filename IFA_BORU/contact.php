<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - IFA BORU AMURU Secondary School</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --accent-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --gold-gradient: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #f6d365;
            --success-color: #11998e;
            --text-dark: #2c3e50;
            --text-light: #6c757d;
            --white: #ffffff;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
            
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 25px rgba(0,0,0,0.15);
            --shadow-xl: 0 20px 40px rgba(0,0,0,0.2);
            
            --border-radius: 12px;
            --border-radius-lg: 20px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.7;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* Header & Navigation */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            z-index: 1000;
            transition: var(--transition);
        }

        .header.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: var(--shadow-md);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: var(--primary-gradient);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .nav-menu a {
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: var(--transition);
            position: relative;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            color: var(--primary-color);
            background: rgba(102, 126, 234, 0.1);
        }

        .login-btn {
            background: var(--primary-gradient);
            color: white !important;
            padding: 0.75rem 1.5rem !important;
            border-radius: 25px;
            font-weight: 600;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            background: var(--secondary-gradient) !important;
        }

        /* Page Header */
        .page-header {
            background: var(--primary-gradient);
            color: white;
            padding: 8rem 0 6rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .page-header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 2;
        }

        .page-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .page-subtitle {
            font-size: 1.3rem;
            opacity: 0.95;
            max-width: 700px;
            margin: 0 auto;
        }

        /* Content Sections */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .content-section {
            padding: 8rem 0;
        }

        .content-section:nth-child(even) {
            background: var(--light-bg);
        }

        .section-header {
            text-align: center;
            margin-bottom: 5rem;
        }

        .section-badge {
            display: inline-block;
            background: var(--accent-gradient);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            line-height: 1.3;
        }

        .section-subtitle {
            font-size: 1.2rem;
            color: var(--text-light);
            max-width: 700px;
            margin: 0 auto;
        }

        /* Contact Grid */
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: start;
        }

        .contact-info {
            background: white;
            padding: 3rem;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
            height: fit-content;
        }

        .contact-info h3 {
            font-family: 'Playfair Display', serif;
            color: var(--text-dark);
            margin-bottom: 2rem;
            font-size: 2rem;
            font-weight: 600;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: var(--light-bg);
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .contact-item:hover {
            transform: translateX(5px);
            box-shadow: var(--shadow-sm);
        }

        .contact-icon {
            width: 60px;
            height: 60px;
            background: var(--primary-gradient);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-right: 1.5rem;
            flex-shrink: 0;
        }

        .contact-details h4 {
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .contact-details p {
            color: var(--text-light);
            line-height: 1.6;
        }

        /* Contact Form */
        .contact-form {
            background: white;
            padding: 3rem;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
        }

        .contact-form h3 {
            font-family: 'Playfair Display', serif;
            color: var(--text-dark);
            margin-bottom: 2rem;
            font-size: 2rem;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.75rem;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-family: inherit;
            transition: var(--transition);
            background: white;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 140px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 1rem 2rem;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            margin-right: 1rem;
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-secondary:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Success Message */
        .success-message {
            background: var(--success-gradient);
            color: white;
            padding: 1.25rem 1.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            display: none;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }

        .success-message.show {
            display: flex;
        }

        /* Office Hours */
        .hours-section {
            background: var(--light-bg);
        }

        .hours-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2.5rem;
        }

        .hours-card {
            background: white;
            padding: 2.5rem 2rem;
            border-radius: var(--border-radius-lg);
            text-align: center;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .hours-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .hours-icon {
            width: 80px;
            height: 80px;
            background: var(--accent-gradient);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin: 0 auto 1.5rem;
        }

        .hours-card h4 {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
        }

        .hours-list {
            list-style: none;
        }

        .hours-list li {
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .hours-list li:last-child {
            border-bottom: none;
        }

        .hours-list li span:first-child {
            font-weight: 500;
            color: var(--text-dark);
        }

        .hours-list li span:last-child {
            color: var(--text-light);
        }

        /* Map Section */
        .map-section {
            padding: 8rem 0;
        }

        .map-container {
            background: var(--primary-gradient);
            height: 500px;
            border-radius: var(--border-radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }

        .map-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="map" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23map)"/></svg>');
        }

        .map-content {
            position: relative;
            z-index: 2;
        }

        .map-content h3 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .map-content p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        /* CTA Section */
        .cta {
            padding: 8rem 0;
            background: var(--dark-gradient);
            color: white;
            text-align: center;
            position: relative;
        }

        .cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
        }

        .cta-content {
            position: relative;
            z-index: 2;
        }

        .cta h2 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .cta p {
            font-size: 1.3rem;
            margin-bottom: 2.5rem;
            opacity: 0.9;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Footer */
        .footer {
            background: var(--text-dark);
            color: white;
            padding: 4rem 0 2rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-section h3 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--accent-color);
        }

        .footer-section p,
        .footer-section a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            margin-bottom: 0.75rem;
            display: block;
            transition: var(--transition);
        }

        .footer-section a:hover {
            color: var(--accent-color);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 2rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .contact-grid {
                grid-template-columns: 1fr;
                gap: 3rem;
            }
            
            .page-title {
                font-size: 3rem;
            }
        }

        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }
            
            .page-title {
                font-size: 2.5rem;
            }
            
            .section-title {
                font-size: 2.5rem;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .hours-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .nav-container {
                padding: 1rem;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .contact-form,
            .contact-info {
                padding: 2rem;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }

        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header" id="header">
        <div class="nav-container">
            <a href="home_basic.php" class="logo">
                <div class="logo-icon">🎓</div>
                <span>IFA BORU AMURU</span>
            </a>
            <nav>
                <ul class="nav-menu">
                    <li><a href="home_basic.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php" class="active">Contact</a></li>
                </ul>
            </nav>
            <a href="index.php" class="login-btn">
                <span>🔐</span>
                Portal Access
            </a>
        </div>
    </header>

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header-content">
            <div class="page-badge">Get In Touch</div>
            <h1 class="page-title">Contact Us</h1>
            <p class="page-subtitle">
                Connect with IFA BORU AMURU Secondary School for inquiries, admissions, 
                technical support, or any assistance you may need.
            </p>
        </div>
    </section>

    <!-- Contact Information -->
    <section class="content-section">
        <div class="container">
            <div class="contact-grid">
                <div class="contact-info fade-in-up">
                    <h3>Contact Information</h3>
                    
                    <div class="contact-item">
                        <div class="contact-icon">📍</div>
                        <div class="contact-details">
                            <h4>School Address</h4>
                            <p>IFA BORU AMURU Secondary School<br>
                            Ethiopia<br>
                            Postal Code: XXXX<br>
                            East Africa</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">📞</div>
                        <div class="contact-details">
                            <h4>Phone Numbers</h4>
                            <p>Main Office: +251-XXX-XXXX<br>
                            Administration: +251-XXX-XXXX<br>
                            Student Affairs: +251-XXX-XXXX<br>
                            Emergency: +251-XXX-XXXX</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">✉️</div>
                        <div class="contact-details">
                            <h4>Email Addresses</h4>
                            <p>General Inquiries: info@ifaboru.edu.et<br>
                            Admissions: admissions@ifaboru.edu.et<br>
                            Technical Support: support@ifaboru.edu.et<br>
                            Principal: principal@ifaboru.edu.et</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">🌐</div>
                        <div class="contact-details">
                            <h4>Online Services</h4>
                            <p>Access our comprehensive school management system for students, teachers, and administrators with 24/7 availability and technical support.</p>
                        </div>
                    </div>
                </div>

                <div class="contact-form">
                    <h3>Send us a Message</h3>
                    <div class="success-message" id="successMessage">
                        <span>✅</span>
                        <span>Thank you for your message! We will get back to you within 24 hours.</span>
                    </div>
                    
                    <form id="contactForm" onsubmit="handleSubmit(event)">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">First Name *</label>
                                <input type="text" id="firstName" name="firstName" required placeholder="Enter your first name">
                            </div>
                            <div class="form-group">
                                <label for="lastName">Last Name *</label>
                                <input type="text" id="lastName" name="lastName" required placeholder="Enter your last name">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" required placeholder="your.email@example.com">
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" placeholder="+251-XXX-XXXX">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="subject">Subject *</label>
                            <select id="subject" name="subject" required>
                                <option value="">Select inquiry type</option>
                                <option value="admissions">Admissions & Enrollment</option>
                                <option value="academic">Academic Programs</option>
                                <option value="technical">Technical Support</option>
                                <option value="general">General Information</option>
                                <option value="complaint">Feedback & Complaints</option>
                                <option value="partnership">Partnership Opportunities</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" placeholder="Please provide detailed information about your inquiry. We're here to help!" required></textarea>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary">
                                <span>📤</span>
                                Send Message
                            </button>
                            <a href="index.php" class="btn btn-secondary">
                                <span>🎓</span>
                                Access Portal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Office Hours -->
    <section class="content-section hours-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">Office Hours</div>
                <h2 class="section-title">Departments & Availability</h2>
                <p class="section-subtitle">
                    Our dedicated staff is available during these hours to assist you with all your needs.
                </p>
            </div>
            <div class="hours-grid">
                <div class="hours-card">
                    <div class="hours-icon">📋</div>
                    <h4>Administration Office</h4>
                    <ul class="hours-list">
                        <li><span>Monday - Friday</span><span>8:00 AM - 5:00 PM</span></li>
                        <li><span>Saturday</span><span>8:00 AM - 12:00 PM</span></li>
                        <li><span>Sunday</span><span>Closed</span></li>
                        <li><span>Lunch Break</span><span>12:00 PM - 1:00 PM</span></li>
                    </ul>
                </div>

                <div class="hours-card">
                    <div class="hours-icon">🎓</div>
                    <h4>Student Affairs</h4>
                    <ul class="hours-list">
                        <li><span>Monday - Friday</span><span>8:00 AM - 4:00 PM</span></li>
                        <li><span>Saturday</span><span>9:00 AM - 1:00 PM</span></li>
                        <li><span>Sunday</span><span>Closed</span></li>
                        <li><span>Emergency Support</span><span>24/7 Available</span></li>
                    </ul>
                </div>

                <div class="hours-card">
                    <div class="hours-icon">💻</div>
                    <h4>Technical Support</h4>
                    <ul class="hours-list">
                        <li><span>Monday - Friday</span><span>9:00 AM - 4:00 PM</span></li>
                        <li><span>Saturday</span><span>10:00 AM - 2:00 PM</span></li>
                        <li><span>Sunday</span><span>Closed</span></li>
                        <li><span>Online Support</span><span>24/7 Available</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">Location</div>
                <h2 class="section-title">Find Our Campus</h2>
                <p class="section-subtitle">
                    Visit our beautiful campus located in the heart of Ethiopia's educational district.
                </p>
            </div>
            <div class="map-container">
                <div class="map-content">
                    <h3>📍 IFA BORU AMURU Campus</h3>
                    <p>Secondary School Campus<br>Ethiopia</p>
                    <p style="margin-top: 1.5rem; font-size: 1rem; opacity: 0.8;">
                        Interactive campus map and directions available<br>
                        GPS coordinates and public transport information provided upon request
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Join Excellence?</h2>
                <p>Take the next step in your educational journey with IFA BORU AMURU Secondary School. Access our portal for admissions, student services, and comprehensive academic support.</p>
                <div>
                    <a href="index.php" class="btn btn-primary" style="background: var(--gold-gradient); color: var(--text-dark);">
                        <span>🚀</span>
                        Access Portal Now
                    </a>
                    <a href="about.php" class="btn btn-secondary">
                        <span>📖</span>
                        Learn More About Us
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>IFA BORU AMURU Secondary School</h3>
                    <p>Excellence in Education</p>
                    <p>Building Tomorrow's Leaders</p>
                    <p>📍 Ethiopia</p>
                    <p>📞 +251-XXX-XXXX</p>
                    <p>✉️ info@ifaboru.edu.et</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <a href="home.php">Home</a>
                    <a href="about.php">About Us</a>
                    <a href="contact.php">Contact</a>
                    <a href="index.php">Portal Login</a>
                </div>
                <div class="footer-section">
                    <h3>Portal Access</h3>
                    <a href="index.php">Admin Dashboard</a>
                    <a href="index.php">Teacher Portal</a>
                    <a href="index.php">Student Portal</a>
                </div>
                <div class="footer-section">
                    <h3>Academic Year 2026</h3>
                    <p>Current Semester: 1</p>
                    <p>Grades: 9, 10, 11, 12</p>
                    <p>Sections: A, B, C</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 IFA BORU AMURU Secondary School. All rights reserved. | Designed with Excellence</p>
            </div>
        </div>
    </footer>

    <script>
        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Contact form submission
        function handleSubmit(event) {
            event.preventDefault();
            
            // Get form data
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData);
            
            // Simple validation
            if (!data.firstName || !data.lastName || !data.email || !data.subject || !data.message) {
                alert('Please fill in all required fields.');
                return;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(data.email)) {
                alert('Please enter a valid email address.');
                return;
            }
            
            // Show success message
            const successMessage = document.getElementById('successMessage');
            successMessage.classList.add('show');
            
            // Reset form
            event.target.reset();
            
            // Scroll to success message
            successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Hide success message after 8 seconds
            setTimeout(() => {
                successMessage.classList.remove('show');
            }, 8000);
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>