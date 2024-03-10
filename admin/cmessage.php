<?php
// Include necessary files and establish a database connection
include("../include/config.php");

// Function to sanitize user input
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Start the session
session_start();

// Check if AdminID is set in the session
if (!isset($_SESSION['AdminID'])) {
    echo "Admin ID not set in the session.";
    exit();
}

// Function to fetch messages sent by customers to the logged-in admin
function fetchMessages($conn, $adminID, $userID, $adminName) {
    $query = "SELECT m.*, IF(m.SenderID = $adminID, 'admin', 'customer') AS MessageType,
                        CASE 
                            WHEN m.SenderID = $adminID THEN 'align-right'
                            ELSE 'align-left'
                        END AS alignmentClass,
                        CASE 
                            WHEN m.SenderID = $adminID THEN '$adminName'
                            ELSE c.Name
                        END AS SenderName
              FROM messages m 
              LEFT JOIN customers c ON m.SenderID = c.CustomerID
              WHERE (m.ReceiverID = $adminID AND m.MessageType = 'customer' AND m.SenderID = $userID)
              OR (m.SenderID = $adminID AND m.MessageType = 'admin' AND m.ReceiverID = $userID)
              ORDER BY m.SentDateTime ASC"; // Ensure messages are ordered by datetime

    // Execute the query
    $result = mysqli_query($conn, $query);

    // Check for query execution success
    if (!$result) {
        echo "Error: " . mysqli_error($conn);
        exit();
    }

    // Track message counter for alternating styles
    $messageCounter = 0;

    // Display messages
    while ($row = mysqli_fetch_assoc($result)) {
        // Apply alternating classes for styling
        echo "<div class='message {$row['alignmentClass']}'><strong>{$row['SenderName']}:</strong><br><div class='timestamp'>{$row['SentDateTime']}</div><br><div class='text'>{$row['Body']}</div></div>";
        
        // Increment message counter
        $messageCounter++;
    }
}

// Fetch the logged-in admin's name
$adminID = $_SESSION['AdminID'];
$query = "SELECT Name FROM admin WHERE AdminID = $adminID";
$result = mysqli_query($conn, $query);
if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit();
}
$row = mysqli_fetch_assoc($result);
$adminName = $row['Name'];

// Fetch list of customers who messaged the admin
$query = "SELECT DISTINCT m.SenderID AS ID, c.Name
          FROM messages m
          LEFT JOIN customers c ON m.SenderID = c.CustomerID
          WHERE m.ReceiverID = $adminID AND m.MessageType = 'customer'";
$result = mysqli_query($conn, $query);
if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit();
}
$customers = $result;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body{
            font-family: 'serif';
        }
        /* Copy the CSS styles from the previous code */
        .container {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            height: 600px;
            margin-top: 200px;
            margin-left: 25px
        }

        .sidebar {
            flex-basis: 30%;
            padding: 20px;
            background-color: #f0f0f0;
        }

        .main-content {
            flex-basis: 65%;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            overflow-y: auto; /* Add scroll bar when content overflows */
        }
        .user-tab {
            background-color: #ccc;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .user-tab a {
            text-decoration: none;
            color: #333;
        }
        .message {
            margin-bottom: 10px;
            padding: 5px;
            border-radius: 8px;
            max-width: 70%;
        }

        .align-left {
            float: left;
            clear: both;
            background-color: #f0f0f0;
            width: 500px;
            font-size: 1.3rem;
        }

        .align-right {
            float: right;
            clear: both;
            background-color: #e0f0e0;
            width: 500px;
            text-align: right;
            font-size: 1.3rem;
        }

        .timestamp {
            text-align: center;
            margin-top: 5px; /* Adjust margin as needed */
        }

        .mess {
            margin-left: 420px;
        }

        .reply {
            width: 150px;
            height: 30px;
            margin-left: 660px;
            background-color: #e0f0e0;
            font-size: 1.2rem;
            font-family: 'serif';
        }

        .text {
            font-size: 1.3rem;
            margin-right: 15px;
            margin-left: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Customers</h2>
            <ul>
                <!-- Display customers who messaged the admin -->
                <?php while ($row = mysqli_fetch_assoc($customers)) { ?>
                    <div class="user-tab">
                        <a href="?other_id=<?php echo $row['ID']; ?>"><?php echo $row['Name']; ?></a>
                    </div>
                <?php } ?>
            </ul>
        </div>
        <div class="main-content">
            <?php
            if (isset($_GET['other_id'])) {
                // Display messages for the selected user
                $otherID = sanitize($_GET['other_id']);
                fetchMessages($conn, $adminID, $otherID, $adminName);
                ?>

                <?php
                if (isset($_POST['send_reply'])) {
                    $replyMessage = sanitize($_POST['reply_message']);
                    $senderID = $adminID;
                    $recipientID = $otherID;
                    $messageType = 'admin'; // For messages sent to admin
                    // Assuming SentDateTime is automatically generated by the database
                    $query = "INSERT INTO messages (SenderID, ReceiverID, MessageType, Body) 
                            VALUES ('$senderID', '$recipientID', '$messageType', '$replyMessage')";
                    $result = mysqli_query($conn, $query);
                    if ($result) {
                        // Refresh the page to show the updated messages
                        echo "<meta http-equiv='refresh' content='0'>";
                    } else {
                        echo "Error: Failed to send reply.";
                    }
                }

            } else {
                echo "<p>Select a user from the left sidebar to view messages.</p>";
            }
            ?>
        </div>
    </div>
    <!-- Reply form -->
    <div class="mess">
        <form method="post">
            <textarea name="reply_message" rows="5" cols="115" required></textarea><br><br>
            <input type="submit" name="send_reply" value="Send Reply" class="reply">
        </form>
    </div>
</body>
</html>
