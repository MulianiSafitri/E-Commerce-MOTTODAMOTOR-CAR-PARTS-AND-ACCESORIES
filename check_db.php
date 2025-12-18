<?php
require_once 'inc/config.php';
$result = mysqli_query($conn, "DESCRIBE transactions");
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . "\n";
}
?>