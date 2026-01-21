<?php
session_start();
include('../config/db.php');

// 1. Session Check
if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$pid = $_SESSION['user_id'];


$sql = "SELECT 
            T.TICKET_ID, 
            S1.STATION_NAME AS SOURCE_STATION, 
            S2.STATION_NAME AS DEST_STATION, 
            F.PRICE, 
            TO_CHAR(T.PURCHASE_TIME, 'DD-MON-YYYY HH:MI AM') AS P_TIME
        FROM Ticket T
        JOIN Schedule SCH ON T.SCHEDULE_ID = SCH.SCHEDULE_ID
        JOIN Route R ON SCH.ROUTE_ID = R.ROUTE_ID
        JOIN Station S1 ON R.FROM_STATION_ID = S1.STATION_ID
        JOIN Station S2 ON R.TO_STATION_ID = S2.STATION_ID
        JOIN Fare F ON T.FARE_ID = F.FARE_ID
        WHERE T.PASSENGER_ID = :pid
        ORDER BY T.PURCHASE_TIME DESC";

$stid = oci_parse($conn, $sql);
oci_bind_by_name($stid, ":pid", $pid);
$success = oci_execute($stid);

if (!$success) {
    $e = oci_error($stid);
    die("Database Error: " . $e['message']); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Travel History - Metro Rail</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-10">
    <div class="max-w-5xl mx-auto bg-white p-8 rounded-3xl shadow-xl border">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-black text-blue-900 uppercase italic">📜 Travel History</h2>
            <a href="dashboard.php" class="bg-blue-100 text-blue-800 px-4 py-2 rounded-xl font-bold hover:bg-blue-200 transition">← Back to Booking</a>
        </div>
        
        <div class="overflow-hidden rounded-2xl border border-gray-100">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-blue-900 text-white">
                        <th class="p-4 text-xs font-bold uppercase tracking-wider">Ticket ID</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-wider">Route Details</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-wider">Fare Paid</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-wider">Purchase Time</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-wider text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php 
                    $has_data = false;
                    while ($row = oci_fetch_array($stid, OCI_ASSOC)): 
                        $has_data = true;
                    ?>
                    <tr class="hover:bg-blue-50/50 transition-colors">
                        <td class="p-4 font-mono font-bold text-gray-700">#<?php echo $row['TICKET_ID']; ?></td>
                        <td class="p-4">
                            <div class="flex items-center space-x-2">
                                <span class="font-bold text-blue-900"><?php echo $row['SOURCE_STATION']; ?></span>
                                <span class="text-gray-400">→</span>
                                <span class="font-bold text-blue-900"><?php echo $row['DEST_STATION']; ?></span>
                            </div>
                        </td>
                        <td class="p-4 font-black text-green-600 text-lg">৳<?php echo number_format($row['PRICE'], 2); ?></td>
                        <td class="p-4 text-gray-500 text-sm font-medium"><?php echo $row['P_TIME']; ?></td>
                        <td class="p-4 text-center">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter">Confirmed</span>
                        </td>
                    </tr>
                    <?php endwhile; 

                    if (!$has_data): ?>
                    <tr>
                        <td colspan="5" class="p-10 text-center text-gray-400 font-medium">
                            No travel history found. Start your journey today!
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>