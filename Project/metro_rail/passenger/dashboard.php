<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_name = $_SESSION['name'];


$station_sql = "SELECT station_id, station_name FROM Station ORDER BY station_id ASC";
$stid = oci_parse($conn, $station_sql);
oci_execute($stid);

$stations = [];
while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
    $stations[] = ['ID' => $row['STATION_ID'], 'NAME' => $row['STATION_NAME']];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Passenger Dashboard - Metro Rail</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        .loader {
            border-top-color: #1e3a8a;
            animation: spinner 1.5s linear infinite;
        }
        @keyframes spinner {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden font-sans">

    <aside class="w-64 bg-blue-900 text-white flex flex-col shadow-2xl">
        <div class="p-6 text-2xl font-black italic border-b border-blue-800 tracking-tighter text-blue-300">METRO<span class="text-white">RAIL</span></div>
        <nav class="flex-grow p-4 space-y-2 mt-4">
            <a href="dashboard.php" class="block p-3 bg-blue-800 rounded-xl font-bold border-l-4 border-white shadow-lg">🎫 Book Ticket</a>
            <a href="travel_history.php" class="block p-3 hover:bg-blue-800 rounded-xl opacity-70 transition">📜 Travel History</a>
            <a href="../login.php" class="block p-3 hover:bg-red-800 rounded-xl mt-10 text-red-200 transition">🚪 Logout</a>
        </nav>
    </aside>

    <div class="flex-grow flex flex-col overflow-y-auto">
        <header class="bg-white shadow-sm p-6 flex justify-between items-center px-10">
            <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight">Passenger Portal</h2>
            <div class="font-black text-blue-900 italic flex items-center space-x-2">
                <span class="text-xs text-gray-400 not-italic">WELCOME,</span> 
                <span><?php echo $user_name; ?></span>
            </div>
        </header>

        <main class="p-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-[2rem] shadow-xl border border-white">
                <h3 class="text-xl font-black text-blue-900 mb-8 uppercase tracking-widest flex items-center gap-2">
                    <span class="bg-blue-900 text-white p-1 rounded">📍</span> Plan Your Journey
                </h3>
                <form id="bookingForm" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Source Station</label>
                        <select id="fromStation" name="from_station" class="w-full border-2 border-gray-50 p-4 rounded-2xl bg-gray-50 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-800 transition-all font-bold text-gray-700" required>
                            <option value="" disabled selected>Choose Start Point</option>
                            <?php foreach ($stations as $s): ?>
                                <option value="<?php echo $s['ID']; ?>"><?php echo $s['NAME']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Destination Station</label>
                        <select id="toStation" name="to_station" class="w-full border-2 border-gray-50 p-4 rounded-2xl bg-gray-50 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-800 transition-all font-bold text-gray-700" required>
                            <option value="" disabled selected>Choose End Point</option>
                            <?php foreach ($stations as $s): ?>
                                <option value="<?php echo $s['ID']; ?>"><?php echo $s['NAME']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <button id="buyTicketBtn" type="button" class="w-full bg-blue-900 text-white font-black py-5 rounded-2xl hover:bg-blue-800 transition-all uppercase tracking-widest shadow-xl flex items-center justify-center gap-2 group">
                        Confirm & Buy Ticket
                    </button>
                </form>
            </div>

            <div id="summaryCard" class="bg-white p-8 rounded-[2rem] shadow-xl border-4 border-dashed border-blue-50 flex flex-col justify-between">
                <div>
                    <h3 class="text-xl font-black text-gray-800 mb-8 uppercase tracking-widest text-center italic">Digital Ticket Info</h3>
                    <div class="space-y-6">
                        <div class="flex justify-between items-center bg-gray-50 p-4 rounded-2xl">
                            <span class="text-[10px] font-black text-gray-400 uppercase">From</span>
                            <span id="summaryFrom" class="font-black text-blue-900">Not Selected</span>
                        </div>
                        <div class="flex justify-between items-center bg-gray-50 p-4 rounded-2xl">
                            <span class="text-[10px] font-black text-gray-400 uppercase">To</span>
                            <span id="summaryTo" class="font-black text-blue-900">Not Selected</span>
                        </div>
                        <div class="flex justify-between items-center border-t-2 border-blue-100 pt-6">
                            <span class="text-sm font-black text-gray-600 uppercase">Fare Amount</span>
                            <span id="summaryFare" class="text-3xl font-black text-green-600">৳ 0.00</span>
                        </div>
                    </div>
                </div>
                <div class="mt-8 p-4 bg-yellow-50 rounded-2xl text-center text-[10px] text-yellow-700 font-black uppercase tracking-widest italic">
                    ⚠ Stay Safe!
                </div>
            </div>
        </main>
    </div>

    <div id="successModal" class="fixed inset-0 bg-blue-900/60 backdrop-blur-md hidden items-center justify-center z-50 p-4">
        <div class="bg-white p-1 rounded-[2.5rem] shadow-2xl w-full max-w-sm">
            <div id="ticket-to-print" class="bg-white p-8 rounded-[2rem] text-center">
                <div class="border-b-2 border-dashed border-gray-200 pb-4 mb-4">
                    <h1 class="text-2xl font-black italic text-blue-900">METRO TICKET</h1>
                    <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest">Dhaka Metro Rail Authority</p>
                </div>

                <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">✓</div>
                
                <h3 class="text-xl font-black text-gray-800 mb-6 uppercase">Payment Success</h3>
                
                <div class="text-left space-y-3 bg-slate-50 p-5 rounded-2xl mb-6">
                    <div class="flex justify-between"><span class="text-[10px] text-gray-400 font-bold">PASSENGER:</span><span class="text-xs font-black"><?php echo $user_name; ?></span></div>
                    <div class="flex justify-between"><span class="text-[10px] text-gray-400 font-bold">FROM:</span><span id="pdfFrom" class="text-xs font-black">---</span></div>
                    <div class="flex justify-between"><span class="text-[10px] text-gray-400 font-bold">TO:</span><span id="pdfTo" class="text-xs font-black">---</span></div>
                    <div class="flex justify-between border-t border-gray-200 pt-2"><span class="text-[10px] text-gray-400 font-bold">FARE:</span><span id="pdfFare" class="text-sm font-black text-green-600">---</span></div>
                </div>

                <p class="text-[8px] text-gray-400 mb-6 italic">Valid for 1 hour from purchase time.</p>
            </div>

            <div class="px-8 pb-8 space-y-3">
                <button id="downloadBtn" class="w-full bg-green-600 text-white py-4 rounded-xl font-black uppercase tracking-widest shadow-lg hover:bg-green-700 flex items-center justify-center gap-2">
                    📥 Download PDF
                </button>
                <button onclick="location.reload()" class="w-full bg-blue-900 text-white py-4 rounded-xl font-black uppercase tracking-widest hover:bg-blue-800 transition">
                    Done
                </button>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            function updateSummary() {
                let fromId = $('#fromStation').val();
                let toId = $('#toStation').val();
                let fromName = $("#fromStation option:selected").text();
                let toName = $("#toStation option:selected").text();

                if (fromId && toId) {
                    $('#summaryFrom').text(fromName);
                    $('#summaryTo').text(toName);

                    $.ajax({
                        url: 'get_fare.php',
                        method: 'POST',
                        data: { from_id: fromId, to_id: toId },
                        success: function(response) {
                            let fare = parseFloat(response).toFixed(2);
                            $('#summaryFare').text('৳ ' + fare);
                        }
                    });
                }
            }

            $('#fromStation, #toStation').change(updateSummary);

            $('#buyTicketBtn').click(function() {
                let from = $('#fromStation').val();
                let to = $('#toStation').val();
                let fromName = $("#fromStation option:selected").text();
                let toName = $("#toStation option:selected").text();
                let fareText = $('#summaryFare').text();
                let fareNum = fareText.replace('৳ ', '');

                if (from && to && fareNum !== "0.00") {
                    // Start 5-second buffer (Loading)
                    let btn = $(this);
                    let originalText = btn.html();
                    btn.prop('disabled', true).html('<div class="loader w-5 h-5 border-4 border-white rounded-full mr-3"></div> Processing Payment...');

                    setTimeout(function() {
                        $.ajax({
                            url: 'book_ticket.php',
                            method: 'POST',
                            data: { from_id: from, to_id: to, amount: fareNum },
                            success: function(res) {
                                if (res.trim() === "Success") {
                                    // Set data for PDF Modal
                                    $('#pdfFrom').text(fromName);
                                    $('#pdfTo').text(toName);
                                    $('#pdfFare').text(fareText);
                                    
                                    $('#successModal').removeClass('hidden').addClass('flex');
                                } else {
                                    alert("Booking Failed: " + res);
                                }
                                btn.prop('disabled', false).html(originalText);
                            }
                        });
                    }, 5000); // 5 Seconds Buffer
                } else {
                    alert("Please select valid stations!");
                }
            });

            // PDF Download Logic
            $('#downloadBtn').click(function() {
                const element = document.getElementById('ticket-to-print');
                const opt = {
                    margin: 0.5,
                    filename: 'Metro_Ticket_<?php echo time(); ?>.pdf',
                    image: { type: 'jpeg', quality: 1 },
                    html2canvas: { scale: 3, letterRendering: true },
                    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                };
                html2pdf().set(opt).from(element).save();
            });
        });
    </script>
</body>
</html>