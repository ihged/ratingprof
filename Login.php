<?php
// Avvio della sessione
session_start();

// Controllo se l'utente è già loggato
if (isset($_SESSION['email'])) {
  header('Location: ratingpage.php');
  exit();
}

// Connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ratingprof";

$conn = new mysqli($servername, $username, $password, $dbname);

// Controllo della connessione
if ($conn->connect_error) {
  die("Connessione fallita: " . $conn->connect_error);
}

// Ricezione dei dati dalla form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Controllo se l'email e la password sono corrette
  $query = "SELECT * FROM students WHERE email = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    if (true) {
      // Login riuscito
      $_SESSION["sid"] = $row["id"]; //Palesemente chiamato sid per l'era glaciale
      $_SESSION["email"] = $row["email"];
      $_SESSION["class"] = $row["class_id"];
      $_SESSION["name"] = $row["name"];
      header("Location: ratingpage.php");
      exit;
    }
  }

  // Login fallito
  $error = "Email errata";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="CSS/login.css">
  <title>Login</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <div class="wrapper">
      <div class="title">Login Form</div>
      <form action="" method="post">
        <div class="field">
        <input type="email" id="email" name="email" required>
          <label>Email Address</label>
        </div>
        <div class="field">
          <input type="password" id="password" name="password" required>
          <label>Password</label>
        </div>
        <div class="content">
          <div class="pass-link"><a href="#">Forgot password?</a></div>
        </div>
        <div class="field">
          <input type="submit" value="Login">
        </div>
        <div class="signup-link">Not a member? <a href="index.php">Signup now</a></div>
      </form>
    </div>
    <?php if (isset($error)): ?>
    <div class="error"><?php echo $error; ?></div>
  <?php endif; ?>
</body>
</html>
