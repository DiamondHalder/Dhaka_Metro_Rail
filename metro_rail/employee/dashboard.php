<?php
session_start();
include('../config/db.php');


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Employee') {
    header("Location: ../login.php");
    exit();
}

$emp_id = $_SESSION['user_id'];


$q_tickets = oci_parse($conn, "SELECT COUNT(*) AS C FROM Ticket");
oci_execute($q_tickets);
$total_tickets = oci_fetch_array($q_tickets, OCI_ASSOC)['C'];

$q_passengers = oci_parse($conn, "SELECT COUNT(*) AS C FROM Passenger");
oci_execute($q_passengers);
$total_passengers = oci_fetch_array($q_passengers, OCI_ASSOC)['C'];

$q_rev = oci_parse($conn, "SELECT SUM(amount) AS S FROM Payment WHERE status = 'Completed'");
oci_execute($q_rev);
$revenue = oci_fetch_array($q_rev, OCI_ASSOC)['S'] ?? 0;

$q_stations_count = oci_parse($conn, "SELECT COUNT(*) AS C FROM Station");
oci_execute($q_stations_count);
$station_count = oci_fetch_array($q_stations_count, OCI_ASSOC)['C'];


$q_profile = oci_parse($conn, "SELECT name, role, shift_start, shift_end FROM Emp WHERE emp_id = :id");
oci_bind_by_name($q_profile, ":id", $emp_id);
oci_execute($q_profile);
$emp_profile = oci_fetch_array($q_profile, OCI_ASSOC);


$q_st_list = oci_parse($conn, "SELECT station_name FROM Station ORDER BY station_id ASC");
oci_execute($q_st_list);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Dashboard - Metro Rail</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans flex overflow-hidden">

    <aside class="w-72 bg-slate-900 h-screen text-white flex flex-col shadow-2xl">
        <div class="p-8 border-b border-slate-800">
            <h1 class="text-2xl font-black italic tracking-tighter text-blue-400">METRO<span class="text-white">RAIL</span></h1>
            <p class="text-[10px] text-slate-500 uppercase font-bold tracking-widest mt-1">Employee Management System</p>
        </div>

        <nav class="flex-1 p-6 space-y-2">
            <a href="dashboard.php" class="flex items-center space-x-4 p-4 rounded-xl bg-blue-600 text-white transition shadow-lg shadow-blue-900/20">
                <i class="fas fa-th-large w-5"></i>
                <span class="font-bold">Dashboard</span>
            </a>

            <a href="view_employees.php" class="flex items-center space-x-4 p-4 rounded-xl hover:bg-slate-800 text-slate-400 hover:text-white transition">
                <i class="fas fa-users w-5"></i>
                <span class="font-bold">View Employees</span>
            </a>

            <a href="view_tickets.php" class="flex items-center space-x-4 p-4 rounded-xl hover:bg-slate-800 text-slate-400 hover:text-white transition">
                <i class="fas fa-ticket-alt w-5"></i>
                <span class="font-bold">View Sold Tickets</span>
            </a>

            <div class="pt-10">
                <p class="text-[10px] text-slate-500 font-bold uppercase px-4 mb-2">Account</p>
                <a href="../login.php" class="flex items-center space-x-4 p-4 rounded-xl hover:bg-red-900/30 text-red-400 transition">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span class="font-bold">Logout</span>
                </a>
            </div>
        </nav>

        <div class="p-6 bg-slate-950">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center font-black">
                    <?php echo substr($emp_profile['NAME'] ?? 'E', 0, 1); ?>
                </div>
                <div>
                    <p class="text-xs font-bold"><?php echo $_SESSION['name']; ?></p>
                    <p class="text-[10px] text-slate-500">Employee ID: <?php echo $emp_id; ?></p>
                </div>
            </div>
        </div>
    </aside>

    <main class="flex-1 h-screen overflow-y-auto">
        <header class="bg-white border-b p-6 flex justify-between items-center sticky top-0 z-10">
            <div>
                <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight">Overview</h2>
                <p class="text-xs text-slate-400 font-medium">Welcome back, system monitoring is active.</p>
            </div>
            <div class="flex space-x-3 text-xs font-bold">
                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full uppercase">System Online</span>
            </div>
        </header>

        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Tickets Sold</p>
                    <div class="flex items-baseline space-x-2">
                        <p class="text-3xl font-black text-slate-900"><?php echo $total_tickets; ?></p>
                        <span class="text-xs text-green-500 font-bold"><i class="fas fa-arrow-up"></i> Total</span>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Total Passengers</p>
                    <p class="text-3xl font-black text-slate-900"><?php echo $total_passengers; ?></p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Station Count</p>
                    <p class="text-3xl font-black text-slate-900"><?php echo $station_count; ?></p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Total Revenue</p>
                    <p class="text-3xl font-black text-blue-600">৳<?php echo number_format($revenue, 2); ?></p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
                    <h3 class="font-black text-slate-800 border-b pb-4 mb-6 uppercase text-sm tracking-widest">My Profile</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Full Name</p>
                            <p class="font-bold text-slate-700"><?php echo $emp_profile['NAME'] ?? $_SESSION['name']; ?></p>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Role</p>
                            <p class="font-bold text-slate-700"><?php echo $emp_profile['ROLE'] ?? 'Staff'; ?></p>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Active Shift</p>
                            <div class="mt-1 inline-block bg-blue-50 text-blue-700 px-3 py-1 rounded text-xs font-bold">
                                <?php echo ($emp_profile['SHIFT_START'] ?? 'N/A') . " - " . ($emp_profile['SHIFT_END'] ?? 'N/A'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
                    <h3 class="font-black text-slate-800 border-b pb-4 mb-6 uppercase text-sm tracking-widest text-center">Active Station Network</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <?php while($st = oci_fetch_array($q_st_list, OCI_ASSOC)): ?>
                            <div class="bg-slate-50 border border-slate-100 p-3 rounded-xl text-[11px] font-bold text-center hover:bg-blue-600 hover:text-white transition-all cursor-default">
                                <i class="fas fa-train mr-1 opacity-50"></i> <?php echo $st['STATION_NAME']; ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>