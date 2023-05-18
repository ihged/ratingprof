<?php
session_start(); // Start the session

// Assuming you have established a connection to the MySQL database
$conn = mysqli_connect("localhost", "root", "", "ratingprof");

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit();
}

// Get the logged-in student's email
$userId = $_SESSION['sid'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted form data
    $teacherID = $_POST['teacher_id'];
    $q1 = $_POST['q1'];
    $q2 = $_POST['q2'];
    $q3 = $_POST['q3'];
    $q4 = $_POST['q4'];
    $q5 = $_POST['q5'];
    // Repeat the above lines for other qualities

    // Insert the rating into the database
    $query = "INSERT INTO ratings (student_id, teacher_id, knowledge_rating, communication_rating, methodology_rating, listening_rating, support_rating) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("siiiiii", $userId, $teacherID, $q1, $q2, $q3, $q4, $q5);
    // Bind the parameters for other qualities
    $stmt->execute();

    // Redirect back to the rating page
    header("Location: ratingpage.php");
    exit();
}
?>
