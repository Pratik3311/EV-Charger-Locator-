<?php 
require 'connect.php';

if(isset($_POST['submit'])){
    $full_name = $_POST["fullname"];
    $email = $_POST["email"];
    $phone_number = $_POST["phonenumber"];
    $date = $_POST["date"];
    $vehicle_type = $_POST["vehicle"];
    $vehicle_name = $_POST["vehiclename"];
    $vehicle_number = $_POST["vehiclenumber"];
    $charging_type = $_POST["type"];
    $time = $_POST["time"];

    // Use placeholders for the values and bind them to prevent SQL injection
    $query = "INSERT INTO info (full_name, email, phone_number, date, vehicle_type, vehicle_name, vehicle_number, charging_type, time) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssssss", $full_name, $email, $phone_number, $date, $vehicle_type, $vehicle_name, $vehicle_number, $charging_type, $time);

        if (mysqli_stmt_execute($stmt)) {
            // JavaScript code to show confirmation and redirect
            echo '<script>alert("An email will be sent to you for the slot booking"); window.location.href = "home_page.php";</script>';
        } else {
            echo "Error: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error in preparing the statement: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>



<!DOCTYPE html>
<!---Coding By CodingLab | www.codinglabweb.com--->
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <!--<title>Registration Form in HTML CSS</title>-->
    <!---Custom CSS File--->
    <link rel="stylesheet" href="form.css" />
</head>
<body>
<section class="container">
    <header>User Details</header>
    <form action="form.php" method="POST" class="form">
        <div class="input-box">
            <label>Full Name</label>
            <input type="text" placeholder="Enter full name" name="fullname" required />
        </div>

        <div class="input-box" >
            <label>Email Address</label>
            <input type="text" placeholder="Enter email address" name="email" required />
        </div>

        <div class="column">
            <div class="input-box">
                <label style>Phone Number</label>
                <input type="text" placeholder="Enter phone number" name="phonenumber" required />
            </div>
            <div class="input-box">
                <label>Date</label>
                <input type="date" placeholder="Enter date" name="date" required />
            </div>
        </div>
        <div class="vehicle-box">
            <h3>Vehicle type</h3>
            <div class="vehicle-option">
                <div class="vehicle">
                    <input type="radio" id="check-male" name="vehicle" value="2 wheeler" checked />
                    <label for="check-male">2 wheeler</label>
                </div>
                <div class="vehicle">
                    <input type="radio" id="check-female" name="vehicle"  value="3 wheeler"/>
                    <label for="check-female">3 wheeler</label>
                </div>
                <div class="vehicle">
                    <input type="radio" id="check-other" name="vehicle"  value="4 wheeler"/>
                    <label for="check-other">4 wheeler</label>
                </div>
            </div>
        </div>
        <div class="input-box address">
            <div class="column">
                <input type="text" placeholder="Enter Vehicle Name" name="vehiclename" required />
                <input type="text" placeholder="Enter Vehicle Number" name="vehiclenumber" required />
            </div>

            <div class="column">
                <div class="select-box">
                    <select name="type">
                        <option hidden>Charging type</option>
                        <option>120 volt </option>
                        <option>208-240 volt </option>
                        <option>480 volts</option>
                        <option>others</option>
                    </select>
                </div>
                Enter the time:
                <input type="text" placeholder="e.g: 9am - 3pm" name="time" required />
            </div>
            <div class="column">
                <!-- ... (You can add additional elements here if needed) ... -->
            </div>
        </div>
        <button type="submit" name="submit">Submit</button>
    </form>
</section>
</body>
</html>

