<?php
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:index.php');
};

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $tables = htmlspecialchars($_POST['tables']);
    $parking = isset($_POST['parking']) ? 'Yes' : 'No';
    $date = htmlspecialchars(trim($_POST['date']));
    $adults = htmlspecialchars($_POST['adults']);

    

    // Database connection parameters
    $dsn = 'mysql:host=localhost;dbname=gallery';
    $username = 'root'; // Change this to your database username
    $password = ''; // Change this to your database password

    try {
        // Create a PDO instance
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert data into the database
        $sql = "INSERT INTO bookings (name, email, tables, parking, date, adults)
                VALUES (:name, :email, :tables, :parking,:date,:adults)";
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':tables', $tables);
        $stmt->bindParam(':parking', $parking);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':adults', $adults);
        

        // Execute the statement
        if ($stmt->execute()) {
            $message[] = 'reservation successfully';
            header('location:index.php');
            
            
      
            
        } else {
            echo "Error: Unable to save reservation.";
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
} else {
    echo "<p>Invalid request method.</p>";
}
?>
