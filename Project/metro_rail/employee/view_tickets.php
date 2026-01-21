<?php
session_start();
include('../config/db.php');


$sql = "SELECT T.TICKET_ID, P.NAME AS PASSENGER_NAME, 
               S1.STATION_NAME AS FROM_STATION, S2.STATION_NAME AS TO_STATION, 
               F.PRICE, T.PURCHASE_TIME
        FROM Ticket T
        JOIN Passenger P ON T.PASSENGER_ID = P.PASSENGER_ID
        JOIN Fare F ON T.FARE_ID = F.FARE_ID
        JOIN Schedule SC ON T.SCHEDULE_ID = SC.SCHEDULE_ID
        JOIN Route R ON SC.ROUTE_ID = R.ROUTE_ID
        JOIN Station S1 ON R.FROM_STATION_ID = S1.STATION_ID
        JOIN Station S2 ON R.TO_STATION_ID = S2.STATION_ID
        ORDER BY T.PURCHASE_TIME DESC";

$stid = oci_parse($conn, $sql);


if (!oci_execute($stid)) {
    $e = oci_error($stid);
    echo "Query Error: " . $e['message']; 
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ticket Sales Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10 font-sans">
    <div class="max-w-7xl mx-auto bg-white p-8 rounded-2xl shadow-lg border border-gray-200">
        <a href="../employee/dashboard.php" class="text-blue-600 font-bold mb-6 inline-block hover:underline">← Back to Dashboard</a>
        
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Sold Ticket Details</h2>
            <span class="bg-green-100 text-green-700 px-4 py-1 rounded-full text-xs font-bold uppercase">Live Data</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-widest font-bold border-b">
                        <th class="p-4">Ticket ID</th>
                        <th class="p-4">Passenger Name</th>
                        <th class="p-4">Route</th>
                        <th class="p-4">Fare</th>
                        <th class="p-4">Purchase Time</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 divide-y divide-gray-100">
                    <?php while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)): ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4 font-mono font-bold text-blue-600">#<?php echo $row['TICKET_ID']; ?></td>
                        <td class="p-4 font-semibold"><?php echo $row['PASSENGER_NAME']; ?></td>
                        <td class="p-4 text-sm">
                            <span class="text-slate-900 font-medium"><?php echo $row['FROM_STATION']; ?></span> 
                            <span class="text-gray-400 mx-2">→</span> 
                            <span class="text-slate-900 font-medium"><?php echo $row['TO_STATION']; ?></span>
                        </td>
                        <td class="p-4 font-black text-slate-900">৳ <?php echo number_format($row['PRICE'], 2); ?></td>
                        <td class="p-4 text-xs text-gray-500"><?php echo $row['PURCHASE_TIME']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php oci_free_statement($stid); ?>