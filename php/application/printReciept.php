<?php
// File path to the image (template image)
$imagePath = "C:\Users\Kushal Kumar B P\Desktop\print1.jpg"; // Replace with the actual path to the image

// Check if the image exists
if (!file_exists($imagePath)) {
    die("Image not found.");
}

// Load the image (use imagecreatefrompng() if it's a PNG image)
$image = imagecreatefromjpeg($imagePath);
if (!$image) {
    die("Could not load image.");
}

// Colors for the text (black in this case)
$textColor = imagecolorallocate($image, 0, 0, 0); // RGB for black color

// Font settings (using built-in GD font)
$font = 5;  // GD built-in font size (values range from 1 to 5)

// Coordinates for the text position (adjust these values for placement)
$startX = 100;  // X-coordinate for the starting point of the text
$startY = 150;  // Y-coordinate for the first member's name

// Spacing between names
$lineHeight = 20; // Adjust this value to control the spacing between lines

// Assume the members' names are passed via session or POST


// Adding names to the image
imagestring($image, $font, $startX, $startY, "Member 1: " . $member1_name, $textColor);
imagestring($image, $font, $startX, $startY + $lineHeight, "Member 2: " . $member2_name, $textColor);
imagestring($image, $font, $startX, $startY + 2 * $lineHeight, "Member 3: " . $member3_name, $textColor);
imagestring($image, $font, $startX, $startY + 3 * $lineHeight, "Member 4: " . $member4_name, $textColor);

// Generate a unique filename for the downloaded image
$filename = "receipt_" . time() . ".jpg";

// Set headers to force download
header("Content-Type: image/jpeg");
header("Content-Disposition: attachment; filename=" . $filename);

// Output the image as a downloadable file
imagejpeg($image);

// Free up memory
imagedestroy($image);
?>
