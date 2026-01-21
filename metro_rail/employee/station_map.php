<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Station Network - Metro Rail</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="p-8">
        <a href="dashboard.php" class="text-blue-800 font-bold mb-4 inline-block">← Back to Dashboard</a>
        <h2 class="text-3xl font-bold text-slate-800 mb-6">Metro Rail Network Map</h2>
        
        <div class="bg-white p-10 rounded-3xl shadow-lg border border-gray-200">
            <div class="relative flex items-center justify-between">
                <div class="absolute h-2 bg-blue-600 w-full top-1/2 transform -translate-y-1/2 z-0"></div>
                
                <?php 
                $demo_stations = ["Uttara North", "Pallabi", "Mirpur 10", "Agargaon", "Farmgate", "Motijheel"];
                foreach($demo_stations as $st): 
                ?>
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-6 h-6 bg-white border-4 border-blue-900 rounded-full mb-2"></div>
                    <span class="text-xs font-bold text-slate-700"><?php echo $st; ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-20 grid grid-cols-2 gap-8">
                <div class="p-4 bg-green-50 border-l-4 border-green-500 rounded">
                    <p class="font-bold text-green-800">Operational Lines: 1 (MRT Line 6)</p>
                    <p class="text-sm">Total Stations Active: 16</p>
                </div>
                <div class="p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
                    <p class="font-bold text-blue-800">Upcoming Stations: 4</p>
                    <p class="text-sm">Kamalapur extension under construction.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>