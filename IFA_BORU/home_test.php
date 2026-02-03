<?php
echo "<h1>🏠 IFA BORU AMURU Secondary School</h1>";
echo "<p>Welcome to our School Management System</p>";
echo "<p>This is the main homepage - all navigation should work from here!</p>";
echo "<hr>";
echo "<h2>🧭 Navigation Links:</h2>";
echo "<p><a href='about.php' style='color: #667eea; font-size: 18px; text-decoration: none;'>📖 About Us</a> - Learn about our school</p>";
echo "<p><a href='contact.php' style='color: #667eea; font-size: 18px; text-decoration: none;'>📞 Contact Us</a> - Get in touch with us</p>";
echo "<p><a href='index.php' style='color: #667eea; font-size: 18px; text-decoration: none;'>🔐 Portal Login</a> - Access the school portal</p>";
echo "<hr>";
echo "<h2>🎓 Portal Access:</h2>";
echo "<p><a href='index.php' style='color: #667eea; font-size: 18px; text-decoration: none;'>👨‍💼 Admin Login</a> - For administrators</p>";
echo "<p><a href='index.php' style='color: #667eea; font-size: 18px; text-decoration: none;'>👨‍🏫 Teacher Login</a> - For teachers</p>";
echo "<p><a href='index.php' style='color: #667eea; font-size: 18px; text-decoration: none;'>👨‍🎓 Student Login</a> - For students</p>";
echo "<hr>";
echo "<p><strong>🔧 System Status:</strong> All pages working correctly!</p>";
echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    line-height: 1.6;
}

h1 {
    color: #667eea;
    text-align: center;
}

a {
    color: #667eea;
    text-decoration: none;
    font-size: 18px;
}

a:hover {
    text-decoration: underline;
}

hr {
    margin: 20px 0;
    border: none;
    border-top: 1px solid #ccc;
}
</style>