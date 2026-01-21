<?php

ob_start(); 
session_start();
include('config/db.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $role = $_POST['role'];
    $id_email = $_POST['id_email'];
    $pass = $_POST['pass'];

    if (isset($conn) && $conn) {
        if ($role == 'Passenger') {
            $sql = "SELECT * FROM Passenger WHERE (email = :id OR TO_CHAR(passenger_id) = :id) AND nid = :nid";
            $stid = oci_parse($conn, $sql);
            oci_bind_by_name($stid, ":id", $id_email);
            oci_bind_by_name($stid, ":nid", $pass);
        } else if ($role == 'Employee') {
            $sql = "SELECT * FROM Emp WHERE TO_CHAR(emp_id) = :id AND TO_CHAR(salary) = :pass";
            $stid = oci_parse($conn, $sql);
            oci_bind_by_name($stid, ":id", $id_email);
            oci_bind_by_name($stid, ":pass", $pass);
        }

        if (isset($stid)) {
            $res = oci_execute($stid);
            if ($res) {
                $row = oci_fetch_array($stid, OCI_ASSOC);
                if ($row) {
                   
                    $_SESSION['user_id'] = ($role == 'Passenger') ? $row['PASSENGER_ID'] : $row['EMP_ID'];
                    $_SESSION['role'] = $role;
                    $_SESSION['name'] = $row['NAME'];

                   
                    if ($role == 'Passenger') {
                        header("Location: passenger/dashboard.php");
                    } else {
                        header("Location: employee/dashboard.php");
                    }
                    exit;
                } else {
                    $error = "Invalid Credentials!";
                }
            }
            oci_free_statement($stid);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Metro Rail Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-900 flex items-center justify-center h-screen">
    <div class="bg-white p-10 rounded-[2.5rem] shadow-2xl w-full max-w-sm">
        <h2 class="text-2xl font-black text-center text-gray-800 mb-8 tracking-tighter italic uppercase">Metro Login</h2>
        
        <form method="POST" class="space-y-5" autocomplete="off">
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Role</label>
                <select name="role" id="roleSelect" onchange="updateUI()" class="w-full border-b-2 py-2 outline-none cursor-pointer font-bold text-gray-700" required>
                    <option value="" disabled selected>Enter your Role</option>
                    <option value="Passenger">Passenger</option>
                    <option value="Employee">Employee</option>
                </select>
            </div>
            
            <div>
                <input type="text" name="id_email" id="idInput" placeholder="Email or ID" 
                       class="w-full border-b-2 py-2 outline-none" required value="" autocomplete="off">
            </div>
            
            <div>
                <input type="password" name="pass" id="passInput" placeholder="Password" 
                       class="w-full border-b-2 py-2 outline-none" required value="" autocomplete="new-password">
            </div>

            <?php if ($error) echo "<p class='text-red-500 text-xs text-center font-bold uppercase'>$error</p>"; ?>
            
            <button type="submit" class="w-full bg-blue-800 text-white font-black py-4 rounded-2xl hover:bg-blue-700 transition duration-300 shadow-lg uppercase tracking-widest">
                Login
            </button>
        </form>

        <div id="regContainer" class="mt-8 text-center text-xs">
            <span class="text-gray-400 font-medium">New traveler?</span>
            <a href="auth/register.php" class="text-blue-800 font-black ml-1 hover:underline uppercase">Register Now</a>
        </div>
    </div>

    

    <script>
        function updateUI() {
            const role = document.getElementById('roleSelect').value;
            const regContainer = document.getElementById('regContainer');
            const idInput = document.getElementById('idInput');
            const passInput = document.getElementById('passInput');

            
            idInput.value = "";
            passInput.value = "";

            if (role === 'Employee') {
                regContainer.style.display = 'none';
                idInput.placeholder = "Employee Email/ID";
                passInput.placeholder = "Salary (Pass)";
            } else {
                regContainer.style.display = 'block';
                idInput.placeholder = "Email or ID";
                passInput.placeholder = "NID (Password)";
            }
        }

        
        window.onload = function() {
            document.getElementById('idInput').value = "";
            document.getElementById('passInput').value = "";
        };
    </script>
</body>
</html>