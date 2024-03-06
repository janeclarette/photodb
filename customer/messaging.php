<?php
session_start(); // Start the session

// Example login logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    // Assuming you have a form with a 'username' field
    $username = $_POST['username'];
    
    // Retrieve user ID from the database based on the username
    $user_id = getUserIDFromDatabase($username);

    // Store the user ID in the session
    $_SESSION['user_id'] = $user_id;

    // Redirect to the home page or wherever you want
    header('Location: /home.php');
    exit();
}

// Example send message logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    // Retrieve the user ID from the session
    $user_id = $_SESSION['user_id'];

    // Get other message details from the form
    $message_type = $_POST['message_type'];
    $subject = $_POST['subject'];
    $body = $_POST['body'];

    // Insert the message into the database with the logged-in user's ID
    insertMessageIntoDatabase($user_id, $message_type, $subject, $body);

    // Redirect to the messages page or wherever you want
    header('Location: /messages.php');
    exit();
}
?>

<!-- index.html -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Sender</title>
</head>
<body>
    <form action="process_message.php" method="post">
        <label for="user_type">Select User Type:</label>
        <select id="user_type" name="user_type" onchange="populateNames()">
            <option value="admin">Admin</option>
            <option value="photographer">Photographer</option>
            <option value="customer">Customer</option>
        </select>

        <label for="user_name">Select User Name:</label>
        <select id="user_name" name="user_name"></select>

        <input type="submit" value="Send Message">
    </form>

    <script src="script.js"></script>
</body>
</html>

function populateNames() {
    var userType = document.getElementById("user_type").value;
    var userDropdown = document.getElementById("user_name");

    // Clear previous options
    userDropdown.innerHTML = "";

    // Static data for demonstration purposes
    var names = [];
    if (userType === "admin") {
        names = ["Admin1", "Admin2", "Admin3"];
    } else if (userType === "photographer") {
        names = ["Photographer1", "Photographer2", "Photographer3"];
    } else if (userType === "customer") {
        names = ["Customer1", "Customer2", "Customer3"];
    }

    // Populate the second dropdown with the retrieved names
    for (var i = 0; i < names.length; i++) {
        var option = document.createElement("option");
        option.value = names[i];
        option.text = names[i];
        userDropdown.add(option);
    }
}


<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userType = $_POST['user_type'];
    $userName = $_POST['user_name'];

    // Use $userType and $userName as needed (e.g., save to the database)
    echo "Message sent to $userName ($userType)";
}
?>
