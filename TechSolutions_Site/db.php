 <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teamnewt";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


$sql="CREATE DATABASE IF NOT EXISTS teamnewt;
USE teamnewt;

CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono int(10) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    fecha_reg TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE
);";

if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();
?> 