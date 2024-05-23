<?php
$servername = "127.0.0.1";
$dbname = "dating";
$dbusername = "root";
$dbpassword = ""; // Add your database password if you have one

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $email = htmlspecialchars($_POST['email']);
    $gender = htmlspecialchars($_POST['gender']);
    $dob = htmlspecialchars($_POST['dob']);
    $university = htmlspecialchars($_POST['university']);
    $major = htmlspecialchars($_POST['major']);
    $bio = htmlspecialchars($_POST['bio']);

    // Validate date of birth
    if (!$dob || !DateTime::createFromFormat('Y-m-d', $dob)) {
        echo "<p>Invalid or missing date of birth.</p>";
    } else {
        $now = new DateTime();
        $dobo = new DateTime($dob);
        $age = $now->diff($dobo)->format('%y');

        // Insert user data into the database using prepared statement
        $sql = "INSERT INTO users (username, password, email, gender, dob, university, major, bio)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $hasedPassword = md5($password);
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $username, $hasedPassword, $email, $gender, $dob, $university, $major, $bio);
        
        // Execute the prepared statement
        if (@$stmt->execute()) {
            echo "User registered successfully!";
            header("Location: myprofile.html");
        } else {
            echo "Error: " . $stmt->error;
        }

    }
}

// Close the database connection
$conn->close();
?>
