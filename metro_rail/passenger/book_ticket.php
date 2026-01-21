<?php
session_start();
include('../config/db.php');

if (isset($_POST['from_id']) && isset($_POST['to_id'])) {
    $pid = $_SESSION['user_id'];
    $from = $_POST['from_id'];
    $to = $_POST['to_id'];
    $amount = $_POST['amount'];

    // Updated Query
    $route_sql = "SELECT R.ROUTE_ID, R.FARE_ID, S.SCHEDULE_ID 
                  FROM Route R 
                  JOIN Schedule S ON R.ROUTE_ID = S.ROUTE_ID
                  WHERE R.FROM_STATION_ID = :f AND R.TO_STATION_ID = :t";
    
    $stid = oci_parse($conn, $route_sql);
    oci_bind_by_name($stid, ":f", $from);
    oci_bind_by_name($stid, ":t", $to);
    oci_execute($stid);
    
    
    $data = oci_fetch_array($stid, OCI_ASSOC);

    if ($data) {
        $rid = $data['ROUTE_ID'];
        $fid = $data['FARE_ID'];
        $sid = $data['SCHEDULE_ID'];

        // 1. Insert into Payment Table
        $pay_sql = "INSERT INTO Payment (method, amount, payment_time, status) 
                    VALUES ('Online', :amt, CURRENT_TIMESTAMP, 'Completed') 
                    RETURNING payment_id INTO :payid";
        
        $stid_pay = oci_parse($conn, $pay_sql);
        oci_bind_by_name($stid_pay, ":amt", $amount);
        oci_bind_by_name($stid_pay, ":payid", $new_pay_id, 32);
        oci_execute($stid_pay);

        // 2. Insert into Ticket Table
        $ticket_sql = "INSERT INTO Ticket (purchase_time, schedule_id, passenger_id, payment_id, fare_id) 
                       VALUES (CURRENT_TIMESTAMP, :sid, :pid, :payid, :fid)";
        
        $stid_ticket = oci_parse($conn, $ticket_sql);
        oci_bind_by_name($stid_ticket, ":sid", $sid);
        oci_bind_by_name($stid_ticket, ":pid", $pid);
        oci_bind_by_name($stid_ticket, ":payid", $new_pay_id);
        oci_bind_by_name($stid_ticket, ":fid", $fid);

        if (oci_execute($stid_ticket)) {
            echo "Success";
        } else {
            $e = oci_error($stid_ticket);
            echo "Database Error: " . $e['message'];
        }
        oci_free_statement($stid_ticket);
        oci_free_statement($stid_pay);
    } else {
        echo "Route not found in database! Please ensure Route and Schedule exist.";
    }
    oci_free_statement($stid);
}
?>