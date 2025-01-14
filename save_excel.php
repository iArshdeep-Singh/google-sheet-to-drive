<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    // Define directory and file name
    $directory = "excel-files";
    $fileName = "$directory/data.xlsx";

    // Create the directory if it doesn't exist
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    // Check if file exists
    $isNewFile = !file_exists($fileName);

    // Open file (append mode if exists, write mode if new)
    $file = fopen($fileName, "a");

    // If new file, write header
    if ($isNewFile) {
        fwrite($file, "Name,Email,Phone\n"); // Header row
    }

    // Append the new data
    fwrite($file, "$name,$email,$phone\n");

    // Close file
    fclose($file);

    echo "Data saved successfully! <a href='$fileName' download>Download Excel</a>";
} else {
    echo "Invalid request!";
}
