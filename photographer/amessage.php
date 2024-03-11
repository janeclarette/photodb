<?php
// Include necessary files and establish a database connection
include("../include/config.php");

// Function to sanitize user input
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Start the session
session_start();

// Check if CustomerID is set in the session
if (!isset($_SESSION['PhotographerID'])) {
    echo "Customer ID not set in the session.";
    exit();
}

// Function to fetch messages for a specific user
function fetchMessages($conn, $PhotographerID, $adminID) {
    $query = "SELECT m.*, 
                     CASE 
                         WHEN m.SenderID = $PhotographerID THEN 'align-right'
                         ELSE 'align-left'
                     END AS alignmentClass,
                     CASE 
                         WHEN m.SenderID = $PhotographerID THEN 'photographer'
                         ELSE 'admin'
                     END AS MessageType,
                     CASE 
                         WHEN m.SenderID = $PhotographerID THEN p.Name
                         ELSE a.Name
                     END AS SenderName
              FROM messages m 
              LEFT JOIN photographers p ON m.SenderID = p.PhotographerID
              LEFT JOIN admin a ON m.SenderID = a.AdminID
              WHERE ((m.ReceiverID = $adminID AND m.MessageType = 'admin' AND m.SenderID = $PhotographerID) 
                     OR (m.ReceiverID = $PhotographerID AND m.MessageType = 'admin' AND m.SenderID = $adminID))
              ORDER BY m.SentDateTime ASC"; // Ensure messages are ordered by datetime

    // Execute the query
    $result = mysqli_query($conn, $query);

    // Check for query execution success
    if (!$result) {
        echo "Error: " . mysqli_error($conn);
        exit();
    }

    $messageCounter = 0;
    // Display messages
    while ($row = mysqli_fetch_assoc($result)) {
        $messageType = ($row['SenderID'] == $adminID) ? 'admin' : 'customer';
        $alignmentClass = ($messageType == 'admin') ? 'align-left' : 'align-right';
        
        // Apply alternating classes for styling
        echo "<div class='message $alignmentClass'><strong>{$row['SenderName']}:</strong><br><div class='timestamp'>
        {$row['SentDateTime']}</div><br><div class='text'>{$row['Body']}</div>";
        
              // Check if there's an image associated with the message
              if (!empty($row['img_message'])) {
                echo "<img src='../uploads/{$row['img_message']}' alt='Image'>";
            }
    
            echo "</div>";
            
            // Increment message counter
            $messageCounter++;

    }
}



// Fetch the logged-in customer's ID
$PhotographerID = $_SESSION['PhotographerID'];

// Fetch list of admin users
$queryAdmin = "SELECT AdminID AS ID, Name FROM admin";
$resultAdmin = mysqli_query($conn, $queryAdmin);
if (!$resultAdmin) {
    echo "Error: " . mysqli_error($conn);
    exit();
}
$admins = $resultAdmin;

// HTML structure starts here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Copy the CSS styles from the first code snippet */
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <!-- Display admin users -->
            <h2>Admins</h2>
            <ul>
                <?php while ($row = mysqli_fetch_assoc($admins)) { ?>
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
                fetchMessages($conn, $PhotographerID, $otherID);
                ?>

<?php
            if (isset($_POST['send_reply'])) {
                $replyMessage = sanitize($_POST['reply_message']);
                $senderID = $PhotographerID;
                $recipientID = $otherID;
                $messageType = 'admin'; // For messages sent to admin

                // Image upload handling
                $imagePath = '';
                if ($_FILES['image']['size'] > 0) {
                    $targetDirectory = "../uploads/"; // Directory where images will be stored
                    $targetFile = $targetDirectory . basename($_FILES['image']['name']);
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                        $imagePath = $targetFile;
                    } else {
                        echo "Error uploading image.";
                    }
                }

                // Assuming SentDateTime is automatically generated by the database
                $query = "INSERT INTO messages (SenderID, ReceiverID, MessageType, Body, img_message) 
                        VALUES ('$senderID', '$recipientID', '$messageType', '$replyMessage', '$imagePath')";
                $result = mysqli_query($conn, $query);
                if ($result) {
                    // Refresh the page to show the updated messages
                    echo "<meta http-equiv='refresh' content='0'>";
                } else {
                    echo "Error: Failed to send reply.";
                }
            }
        }
            ?>
        </div>
    </div>
    <!-- Reply form -->
    <div class="mess">
        <form method="post" enctype="multipart/form-data">
            <textarea name="reply_message" rows="5" cols="115" required></textarea><br><br>
            <input type="file" name="image" accept="image/*"> <!-- Input field for image -->
            <input type="submit" name="send_reply" value="Send Reply" class="reply">
        </form>
    </div>
</body>
</html>


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
        .message img {
    max-width: 100%; /* Ensure the image does not exceed its container width */
    height: auto; /* Maintain aspect ratio */
    margin-top: 10px; /* Adjust margin as needed */
    border-radius: 5px; /* Add rounded corners if desired */
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