<?php
session_start();
include('../config/db.php');


$sql = "SELECT emp_id, name, gender, role, salary, hire_date, shift_start, shift_end, status, station_id FROM Emp";
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
    <title>Employee Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 p-8 font-sans">
    <div class="max-w-7xl mx-auto">
        <a href="../employee/dashboard.php" class="text-blue-600 font-bold mb-4 inline-block hover:underline">← Back to Dashboard</a>
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b bg-white">
                <h2 class="text-2xl font-black text-slate-800">EMPLOYEE LIST</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold">
                        <tr>
                            <th class="p-4">ID</th>
                            <th class="p-4">Name</th>
                            <th class="p-4">Role</th>
                            <th class="p-4">Salary</th>
                            <th class="p-4">Shift</th>
                            <th class="p-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)): ?>
                        <tr class="hover:bg-slate-50">
                            <td class="p-4 font-mono">#<?php echo $row['EMP_ID']; ?></td>
                            <td class="p-4 font-bold"><?php echo $row['NAME']; ?></td>
                            <td class="p-4 text-sm"><?php echo $row['ROLE']; ?></td>
                            <td class="p-4 text-green-600 font-bold">৳<?php echo number_format($row['SALARY']); ?></td>
                            <td class="p-4 text-xs"><?php echo $row['SHIFT_START'] . " - " . $row['SHIFT_END']; ?></td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-green-100 text-green-700">
                                    <?php echo $row['STATUS']; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
<?php oci_free_statement($stid); ?>