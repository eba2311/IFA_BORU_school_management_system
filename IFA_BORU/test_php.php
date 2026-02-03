<?php
echo "<h1>PHP Test</h1>";
echo "<p>If you can see this, PHP is working!</p>";
echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";

// Test if we can access the home page
if (file_exists('home.php')) {
    echo "<p>✅ home.php file exists</p>";
} else {
    echo "<p>❌ home.php file not found</p>";
}

if (file_exists('about.php')) {
    echo "<p>✅ about.php file exists</p>";
} else {
    echo "<p>❌ about.php file not found</p>";
}

if (file_exists('contact.php')) {
    echo "<p>✅ contact.php file exists</p>";
} else {
    echo "<p>❌ contact.php file not found</p>";
}

echo "<hr>";
echo "<h2>Quick Links:</h2>";
echo "<p><a href='home.php'>Try Home Page</a></p>";
echo "<p><a href='about.php'>Try About Page</a></p>";
echo "<p><a href='contact.php'>Try Contact Page</a></p>";
echo "<p><a href='index.php'>Try Portal Login</a></p>";
?>