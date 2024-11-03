<?php
// Database connection
// Database connection
$servername = "localhost"; // Change if necessary
$username = "murtruyv_Aluma_Hub"; // Default XAMPP username
$password = "AHnNUFQZqngYCH5PuwB3"; 
$dbname = "murtruyv_Aluma_Hub"; // Updated database name

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $graduation_year = $_POST['graduation_year'];
    $occupation = $_POST['occupation'];
    $company = $_POST['company'];
    $specialization = $_POST['specialization'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        die("Error: Passwords do not match.");
    }

    // Hash the password
    $password_hashed = password_hash($password, PASSWORD_BCRYPT);

    // Generate a random unique numeric ID
    do {
        $unique_id = rand(1000000000, 9999999999); // Generates a random 10-digit number
        // Check if the unique_id already exists
        $result = $conn->query("SELECT COUNT(*) FROM users WHERE unique_id = $unique_id");
        $row = $result->fetch_row();
    } while ($row[0] > 0); // Repeat until a unique ID is found

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (unique_id, first_name, last_name, email, graduation_year, occupation, company, specialization, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssissss", $unique_id, $first_name, $last_name, $email, $graduation_year, $occupation, $company, $specialization, $password_hashed);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New record created successfully. Unique ID: " . $unique_id; // Display the generated unique ID
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>
