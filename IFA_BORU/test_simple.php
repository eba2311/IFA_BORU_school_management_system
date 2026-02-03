<?php
echo "PHP is working!";
echo "<br>";
echo "Current directory: " . __DIR__;
echo "<br>";
echo "File exists check:";
echo "<br>";
if (file_exists('home.php')) {
    echo "✅ home.php exists";
} else {
    echo "❌ home.php not found";
}
echo "<br>";
if (file_exists('about.php')) {
    echo "✅ about.php exists";
} else {
    echo "❌ about.php not found";
}
echo "<br>";
if (file_exists('index.php')) {
    echo "✅ index.php exists";
} else {
    echo "❌ index.php not found";
}
?>