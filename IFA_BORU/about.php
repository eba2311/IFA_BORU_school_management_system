<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - IFA BORU AMURU Secondary School</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --accent-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --gold-gradient: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #f6d365;
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

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center;
        }

        .content-text {
            font-size: 1.1rem;
            color: var(--text-light);
            line-height: 1.8;
        }

        .content-text h3 {
            font-family: 'Playfair Display', serif;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            font-size: 2rem;
            font-weight: 600;
        }

        .content-text p {
            margin-bottom: 1.5rem;
        }

        .content-visual {
            background: var(--primary-gradient);
            height: 400px;
            border-radius: var(--border-radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
            box-shadow: var(--shadow-lg);
        }

        /* Mission Vision Values */
        .mvv-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 3rem;
        }

        .mvv-card {
            background: white;
            padding: 3rem 2.5rem;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .mvv-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
            transform: scaleX(0);
            transition: var(--transition);
        }

        .mvv-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .mvv-card:hover::before {
            transform: scaleX(1);
        }

        .mvv-icon {
            width: 100px;
            height: 100px;
            background: var(--primary-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            margin: 0 auto 2rem;
        }

        .mvv-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
        }

        .mvv-card p {
            color: var(--text-light);
            line-height: 1.7;
            font-size: 1.05rem;
        }

        /* Leadership Team */
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2.5rem;
        }

        .team-member {
            background: white;
            padding: 2.5rem 2rem;
            border-radius: var(--border-radius-lg);
            text-align: center;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .team-member:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .team-avatar {
            width: 120px;
            height: 120px;
            background: var(--primary-gradient);
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            box-shadow: var(--shadow-md);
        }

        .team-member h4 {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .team-member p {
            color: var(--primary-color);
            font-weight: 500;
            font-size: 1rem;
        }

        /* Facilities */
        .facilities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .facility-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .facility-item:hover {
            transform: translateX(5px);
            box-shadow: var(--shadow-md);
        }

        .facility-icon {
            width: 60px;
            height: 60px;
            background: var(--accent-gradient);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            flex-shrink: 0;
        }

        .facility-content h4 {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .facility-content p {
            color: var(--text-light);
            font-size: 0.95rem;
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

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 1rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            margin: 0 0.5rem;
        }

        .btn-primary {
            background: var(--gold-gradient);
            color: var(--text-dark);
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
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
            .content-grid {
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
            
            .mvv-grid {
                grid-template-columns: 1fr;
            }
            
            .team-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .nav-container {
                padding: 1rem;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .team-grid {
                grid-template-columns: 1fr;
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
                    <li><a href="about.php" class="active">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
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
            <div class="page-badge">Our Story</div>
            <h1 class="page-title">About IFA BORU AMURU</h1>
            <p class="page-subtitle">
                Discover our rich history, unwavering mission, and commitment to educational 
                excellence that has shaped generations of leaders in Ethiopia.
            </p>
        </div>
    </section>

    <!-- School History -->
    <section class="content-section">
        <div class="container">
            <div class="content-grid">
                <div class="content-text fade-in-up">
                    <h3>Our Distinguished History</h3>
                    <p>IFA BORU AMURU Secondary School was established with a visionary commitment to provide transformative education to the youth of Ethiopia. Since our founding, we have maintained an unwavering dedication to academic excellence and holistic character development.</p>
                    <p>Our institution has evolved from humble beginnings into one of the most respected secondary schools in the region, consistently serving students in grades 9-12 with comprehensive educational programs that prepare them for success in higher education and beyond.</p>
                    <p>We take immense pride in our distinguished alumni who have gone on to pursue advanced degrees at prestigious universities and have become influential leaders, innovators, and change-makers in their respective fields, contributing meaningfully to Ethiopian society and the global community.</p>
                </div>
                <div class="content-visual">
                    <div>
                        <div style="font-size: 4rem; margin-bottom: 1rem;">🏛️</div>
                        <div>Legacy of Excellence</div>
                        <div style="font-size: 1rem; opacity: 0.8; margin-top: 0.5rem;">Building Futures Since Foundation</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission, Vision, Values -->
    <section class="content-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">Our Foundation</div>
                <h2 class="section-title">Mission, Vision & Values</h2>
                <p class="section-subtitle">
                    The core principles that guide our educational philosophy and drive our commitment to excellence.
                </p>
            </div>
            <div class="mvv-grid">
                <div class="mvv-card">
                    <div class="mvv-icon">🎯</div>
                    <h3>Our Mission</h3>
                    <p>To provide exceptional secondary education that prepares students for higher learning and responsible global citizenship, fostering academic excellence, critical thinking, and moral development through innovative teaching methodologies and comprehensive support systems.</p>
                </div>
                <div class="mvv-card">
                    <div class="mvv-icon">👁️</div>
                    <h3>Our Vision</h3>
                    <p>To be the premier secondary school in Ethiopia, internationally recognized for academic excellence, innovative educational practices, and producing well-rounded graduates who become transformational leaders contributing to national development and global progress.</p>
                </div>
                <div class="mvv-card">
                    <div class="mvv-icon">⭐</div>
                    <h3>Our Values</h3>
                    <p>Excellence, Integrity, Respect, Innovation, Collaboration, and Community Service form the cornerstone of our educational philosophy, guiding every aspect of our pursuit of academic distinction and character development.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Academic Excellence -->
    <section class="content-section">
        <div class="container">
            <div class="content-grid">
                <div class="content-visual">
                    <div>
                        <div style="font-size: 4rem; margin-bottom: 1rem;">📚</div>
                        <div>Academic Excellence</div>
                        <div style="font-size: 1rem; opacity: 0.8; margin-top: 0.5rem;">Comprehensive Education Programs</div>
                    </div>
                </div>
                <div class="content-text">
                    <h3>Comprehensive Academic Programs</h3>
                    <p>Our rigorous curriculum spans grades 9-12, meticulously designed to prepare students for the Ethiopian Higher Education Entrance Examination and international academic standards. We maintain the highest educational benchmarks while fostering creativity and critical thinking.</p>
                    <p>We offer an extensive range of subjects including Advanced Mathematics, Natural Sciences, Languages, Social Studies, and Technical disciplines, ensuring students receive a well-rounded education that opens doors to diverse career pathways and higher education opportunities.</p>
                    <p>Our distinguished faculty employs cutting-edge teaching methodologies, integrating modern technology and innovative pedagogical approaches to enhance learning outcomes, student engagement, and academic achievement across all disciplines.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Leadership Team -->
    <section class="content-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">Leadership</div>
                <h2 class="section-title">Our Leadership Team</h2>
                <p class="section-subtitle">
                    Experienced educators and administrators dedicated to student success and institutional excellence.
                </p>
            </div>
            <div class="team-grid">
                <div class="team-member">
                    <div class="team-avatar">👨‍💼</div>
                    <h4>School Principal</h4>
                    <p>Educational Leadership & Administration</p>
                </div>
                <div class="team-member">
                    <div class="team-avatar">👩‍🏫</div>
                    <h4>Vice Principal</h4>
                    <p>Academic Affairs & Curriculum</p>
                </div>
                <div class="team-member">
                    <div class="team-avatar">👨‍🏫</div>
                    <h4>Academic Director</h4>
                    <p>Curriculum Development & Standards</p>
                </div>
                <div class="team-member">
                    <div class="team-avatar">👩‍💼</div>
                    <h4>Student Affairs Director</h4>
                    <p>Student Services & Support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Facilities -->
    <section class="content-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">Infrastructure</div>
                <h2 class="section-title">World-Class Facilities</h2>
                <p class="section-subtitle">
                    State-of-the-art infrastructure designed to support comprehensive learning and development.
                </p>
            </div>
            <div class="facilities-grid">
                <div class="facility-item">
                    <div class="facility-icon">🏫</div>
                    <div class="facility-content">
                        <h4>Modern Classrooms</h4>
                        <p>Technology-equipped learning spaces with contemporary furniture and advanced teaching aids</p>
                    </div>
                </div>
                <div class="facility-item">
                    <div class="facility-icon">🔬</div>
                    <div class="facility-content">
                        <h4>Science Laboratories</h4>
                        <p>Fully equipped labs for Physics, Chemistry, and Biology with modern equipment and safety systems</p>
                    </div>
                </div>
                <div class="facility-item">
                    <div class="facility-icon">💻</div>
                    <div class="facility-content">
                        <h4>Computer Laboratory</h4>
                        <p>High-speed internet connectivity and modern computers for digital literacy and programming</p>
                    </div>
                </div>
                <div class="facility-item">
                    <div class="facility-icon">📖</div>
                    <div class="facility-content">
                        <h4>Comprehensive Library</h4>
                        <p>Extensive collection of books, digital resources, and quiet study spaces for research</p>
                    </div>
                </div>
                <div class="facility-item">
                    <div class="facility-icon">⚽</div>
                    <div class="facility-content">
                        <h4>Sports Facilities</h4>
                        <p>Athletic fields and courts for physical education, sports programs, and recreational activities</p>
                    </div>
                </div>
                <div class="facility-item">
                    <div class="facility-icon">🏢</div>
                    <div class="facility-content">
                        <h4>Administrative Offices</h4>
                        <p>Modern management systems and comfortable spaces for student and parent services</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2>Join Our Distinguished Community</h2>
                <p>Experience the excellence of IFA BORU AMURU Secondary School and become part of our legacy of academic achievement and leadership development.</p>
                <div>
                    <a href="contact.php" class="btn btn-primary">
                        <span>📞</span>
                        Contact Us Today
                    </a>
                    <a href="index.php" class="btn btn-secondary">
                        <span>🎓</span>
                        Access Portal
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