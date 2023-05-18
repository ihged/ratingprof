<?php
session_start(); // Start the session

// Assuming you have established a connection to the MySQL database
$conn = mysqli_connect("localhost", "root", "", "ratingprof");

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit();
}

// Retrieve the class ID of the logged-in user from the database
$userEmail = $_SESSION['email'];
$query = "SELECT teachers.* FROM teachers INNER JOIN teacherclasses ON teachers.id = teacherclasses.teacher_id WHERE teacherclasses.class_id = ( SELECT class_id FROM students WHERE email = ? )";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();

// Retrieve the list of teachers already rated by the logged-in user
$queryRated = "SELECT teacher_id FROM ratings WHERE student_id = ?";
$stmtRated = $conn->prepare($queryRated);
$stmtRated->bind_param("s", $_SESSION["sid"]);
$stmtRated->execute();
$resultRated = $stmtRated->get_result();
$ratedTeachers = array();

while ($rowRated = mysqli_fetch_assoc($resultRated)) {
    $ratedTeachers[] = $rowRated['teacher_id'];
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Rate Teachers</title>
    <!-- Add necessary CSS styles -->
    <style>
        .teacher {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Rate Teachers</h1>

    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        $teacherID = $row['id'];
        $teacherName = $row['name'];

        // Check if the teacher has already been rated
        if (in_array($teacherID, $ratedTeachers)) {
            continue; // Skip this teacher if already rated
        }
        ?>

        <div class="teacher">
            <h3><?php echo $teacherName; ?></h3>
            <form method="POST" action="submit_rating.php">
                <input type="hidden" name="teacher_id" value="<?php echo $teacherID; ?>">
                <label>Conoscenza del contenuto:</label>
                <input type="number" name="q1" min="1" max="10" required>
                <label>Capacità di comunicazione</label>
                <input type="number" name="q2" min="1" max="10" required>
                <label>Metodologia didattica:</label>
                <input type="number" name="q3" min="1" max="10" required>
                <label>Disponibilità all'ascolto:</label>
                <input type="number" name="q4" min="1" max="10" required>
                <label>Capacità di gestione della classe:</label>
                <input type="number" name="q5" min="1" max="10" required>
                <input type="submit" value="Submit Rating">
            </form>
        </div>

        <?php
    }
    ?>
    <h1>sid:<?php echo $_SESSION["sid"]; ?></h1>
    <div class="legenda">
    Conoscenza del contenuto: Questo punto riguarda la competenza dell'insegnante nel dominio del contenuto che sta insegnando. Valuta la sua profondità di conoscenza, accuratezza e abilità nel trasmettere le informazioni in modo chiaro e comprensibile.

    Capacità di comunicazione: Questo punto riguarda la capacità dell'insegnante di comunicare efficacemente con gli studenti. Valuta la sua capacità di esporre le informazioni in modo coinvolgente, di rispondere alle domande degli studenti in modo chiaro e di utilizzare un linguaggio appropriato al livello degli studenti.

    Metodologia didattica: Questo punto riguarda gli approcci e le strategie didattiche utilizzate dall'insegnante per facilitare l'apprendimento degli studenti. Valuta l'efficacia dei metodi utilizzati, l'uso di materiali didattici appropriati e l'adattabilità alle diverse esigenze degli studenti.

    Disponibilità all'ascolto: Questo punto riguarda la predisposizione dell'insegnante ad ascoltare e prendere in considerazione le opinioni, i dubbi e le preoccupazioni degli studenti. Valuta la sua apertura alla comunicazione bidirezionale, la disponibilità a fornire supporto e assistenza individualizzata e la capacità di creare un ambiente inclusivo e accogliente.

    Capacità di gestione della classe: Questo punto riguarda la capacità dell'insegnante di gestire efficacemente la classe, mantenendo un ambiente di apprendimento positivo e organizzato. Valuta la sua capacità di gestire il tempo, mantenere l'ordine, favorire la partecipazione attiva degli studenti e gestire eventuali situazioni di conflitto.
    </div>   
    <!-- Add necessary JavaScript code -->
</body>
</html>
