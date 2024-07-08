<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - User Details</title>
    <script src="https://cdn.emailjs.com/dist/email.min.js"></script>
    <style>
        /* Reset some default styles */
        body, h1 {
            margin: 0;
            padding: 0;
        }

        /* Apply some styling to the header */
        header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px 0;
        }

        /* Style the main content area */
        main {
            padding: 20px;
        }

        /* Style the table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Style the footer */
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
        }

        /* Apply styles to links */
        a {
            text-decoration: none;
            color: #007BFF;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Add styles for buttons */
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Panel - User Details</h1>
    </header>
    <main>
        <h1>Displaying User Details</h1>
        <?php
        require 'connect.php'; // Include the database connection script

        // Function to delete a row
        function deleteRow($conn, $id) {
            $sql = "DELETE FROM info WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $id);

                if (mysqli_stmt_execute($stmt)) {
                    echo "Row deleted successfully. <a href='display_data.php'>Go back</a>";
                } else {
                    echo "Error deleting row: " . mysqli_error($conn);
                }

                mysqli_stmt_close($stmt);
            } else {
                echo "Error in preparing the statement: " . mysqli_error($conn);
            }
        }

        // Check if an ID is provided in the URL for deletion
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = $_GET['id'];
            deleteRow($conn, $id);
        }

        // Fetch data from the database
        $sql = "SELECT * FROM info"; // Change 'info' to your table name
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr><th>Full Name</th><th>Email</th><th>Phone Number</th><th>Date</th><th>Vehicle Type</th><th>Vehicle Name</th><th>Vehicle Number</th><th>Charging Type</th><th>Time</th><th>Actions</th></tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["full_name"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["phone_number"] . "</td>";
                echo "<td>" . $row["date"] . "</td>";
                echo "<td>" . $row["vehicle_type"] . "</td>";
                echo "<td>" . $row["vehicle_name"] . "</td>";
                echo "<td>" . $row["vehicle_number"] . "</td>";
                echo "<td>" . $row["charging_type"] . "</td>";
                echo "<td>" . $row["time"] . "</td>";
                echo "<td>
                        <a href='?id=".$row['id']."'>Delete</a>
                        <button onclick='acceptRequest(".$row['id'].", \"".$row['email']."\")'>Accept</button>
                        <button onclick='rejectRequest(".$row['id'].", \"".$row['email']."\")'>Reject</button>
                      </td>"; // Create accept and reject buttons
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "No data found";
        }

        mysqli_close($conn);
        ?>
        <script>
            // JavaScript code for handling actions remains unchanged
            // ...
        </script>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Your Company Name</p>
    </footer>
    <script>
        emailjs.init("m_Q-mg4rwYB8ovW9d"); // Replace with your User ID
    </script>

    <script>
        function acceptRequest(id, email, full_name, phone_number, date, vehicle_type, vehicle_name, vehicle_number, charging_type, time) {
            // Handle the accept action using JavaScript or make an AJAX call
            alert("Accepted request for ID: " + id);

            // Send an email using EmailJS
            var templateId = "template_kc2wcxs"; // Replace with your EmailJS template ID

            emailjs.send("service_cj7hox8", templateId, {
                to_email: email , 
                // to_full_name: full_name ,
                // to_phone_number: phone_number,
                //to_date: date ,
                // to_vehicle_type: vehicle_type,
                //  to_vehicle_name: vehicle_name,
                //    to_vehicle_number: vehicle_number,
                //   to_charging_type: charging_type,
                //    to_time: time, // This should match the template variable in your EmailJS template
                // Add other template variables here as needed
            }).then(function(response) {
                console.log("Email sent successfully!", response);
            }, function(error) {
                console.error("Email sending failed:", error);
            });

        }

        function rejectRequest(id, email, date) {
            // Handle the accept action using JavaScript or make an AJAX call
            alert("Rejected request for ID: " + id);

            // Send an email using EmailJS
            var templateId = "template_byxojah"; // Replace with your EmailJS template ID

            emailjs.send("service_cj7hox8", templateId, {
                to_email: email,
                to_date: date, // This should match the template variable in your EmailJS template
                // Add other template variables here as needed
            }).then(function(response) {
                console.log("Email sent successfully!", response);
            }, function(error) {
                console.error("Email sending failed:", error);
            });
        }

    </script>
</body>
</html>
