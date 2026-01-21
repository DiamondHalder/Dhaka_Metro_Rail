<?php
include('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = $_POST['name'];
    $phone   = $_POST['phone'];
    $email   = $_POST['email'];
    $gender  = $_POST['gender'];
    $dob     = $_POST['dob'];
    $nid     = $_POST['nid'];
    $address = $_POST['address']; 

    $sql = "INSERT INTO Passenger (name, gender, dob, nid, phone, email, address, registered_at) 
            VALUES (:name, :gender, TO_DATE(:dob, 'YYYY-MM-DD'), :nid, :phone, :email, :address, CURRENT_TIMESTAMP)";
    
    $stid = oci_parse($conn, $sql);
    
    oci_bind_by_name($stid, ":name", $name);
    oci_bind_by_name($stid, ":gender", $gender);
    oci_bind_by_name($stid, ":dob", $dob);
    oci_bind_by_name($stid, ":nid", $nid);
    oci_bind_by_name($stid, ":phone", $phone);
    oci_bind_by_name($stid, ":email", $email);
    oci_bind_by_name($stid, ":address", $address);
    
    $result = oci_execute($stid);
    
    if ($result) {
        echo "<script>alert('Registration Successful! Use NID as password.'); window.location='../login.php';</script>";
    } else {
        $e = oci_error($stid);
        echo "Error: " . $e['message'];
    }
    oci_free_statement($stid);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Passenger Registration - Metro Rail</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-900 flex items-center justify-center min-h-screen py-10">
    <div class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-lg">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Registration</h2>
            <p class="text-gray-400 text-sm">Provide all information for Passenger account</p>
        </div>

        <form action="register.php" method="POST" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold uppercase text-gray-400">Full Name</label>
                    <input type="text" name="name" required class="w-full border-b-2 py-1 outline-none focus:border-blue-800">
                </div>
                <div>
                    <label class="text-xs font-bold uppercase text-gray-400">Phone</label>
                    <input type="text" name="phone" required class="w-full border-b-2 py-1 outline-none focus:border-blue-800">
                </div>
            </div>

            <div>
                <label class="text-xs font-bold uppercase text-gray-400">Email Address</label>
                <input type="email" name="email" required class="w-full border-b-2 py-1 outline-none focus:border-blue-800">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold uppercase text-gray-400">Gender</label>
                    <select name="gender" class="w-full border-b-2 py-1 outline-none bg-white focus:border-blue-800">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold uppercase text-gray-400">Date of Birth</label>
                    <input type="date" name="dob" required class="w-full border-b-2 py-1 outline-none focus:border-blue-800">
                </div>
            </div>

            <div>
                <label class="text-xs font-bold uppercase text-gray-400">NID (Will be your Password)</label>
                <input type="text" name="nid" required class="w-full border-b-2 py-1 outline-none focus:border-blue-800">
            </div>

            <div>
                <label class="text-xs font-bold uppercase text-gray-400">Full Address</label>
                <input type="text" name="address" required class="w-full border-b-2 py-1 outline-none focus:border-blue-800">
            </div>

            <button type="submit" class="w-full bg-blue-800 text-white font-bold py-3 rounded-xl hover:shadow-lg transition mt-6">Create Account</button>
        </form>
        
        <div class="text-center mt-6">
            <a href="../login.php" class="text-blue-800 text-sm font-bold hover:underline">Already registered? Login here</a>
        </div>
    </div>
</body>
</html>