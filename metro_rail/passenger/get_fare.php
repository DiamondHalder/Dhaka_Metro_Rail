<?php
include('../config/db.php');

if (isset($_POST['from_id']) && isset($_POST['to_id'])) {
    $from = $_POST['from_id'];
    $to = $_POST['to_id'];

    
    $sql = "SELECT f.PRICE FROM Route r 
            JOIN Fare f ON r.FARE_ID = f.FARE_ID 
            WHERE r.FROM_STATION_ID = :f AND r.TO_STATION_ID = :t";

    $stid = oci_parse($conn, $sql);
    oci_bind_by_name($stid, ":f", $from);
    oci_bind_by_name($stid, ":t", $to);
    oci_execute($stid);

    $row = oci_fetch_array($stid, OCI_ASSOC);
    
    
    if ($row) {
        echo $row['PRICE'];
    } else {
        echo "0";
    }
}
?>