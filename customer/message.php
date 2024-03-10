<?php
// Include necessary files and establish a database connection
include("../include/config.php");
include("../include/header.php");


// Function to sanitize user input
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Start the session
session_start();

// Check if PhotographerID or CustomerID is set in the session
if (!isset($_SESSION['PhotographerID']) && !isset($_SESSION['CustomerID'])) {
    echo "Photographer ID or Customer ID not set in the session.";
    exit();
}

// Function to fetch list of users (photographers or customers)
function fetchUsers($conn, $currentUserID) {
    $query = "SELECT * FROM " . (isset($_SESSION['PhotographerID']) ? "customers" : "photographers");

    $result = mysqli_query($conn, $query);

    // Check for query execution success
    if (!$result) {
        echo "Error: " . mysqli_error($conn);
        exit();
    }

    return $result;
}

// Determine whether the current user is a photographer or a customer
if (isset($_SESSION['PhotographerID'])) {
    $userID = $_SESSION['PhotographerID'];
} elseif (isset($_SESSION['CustomerID'])) {
    $userID = $_SESSION['CustomerID'];
}

// Fetch list of users (photographers or customers)
$usersResult = fetchUsers($conn, $userID);
// Function to fetch messages for a specific user
// Function to fetch messages for a specific user
function fetchMessages($conn, $userID, $otherID) {
    $query = "SELECT m.*, 
                     CASE 
                        WHEN m.MessageType = 'photographer' THEN p.Name 
                        WHEN m.MessageType = 'customer' THEN c.Name 
                     END AS SenderName 
              FROM messages m 
              LEFT JOIN photographers p ON m.SenderID = p.PhotographerID AND m.MessageType = 'photographer'
              LEFT JOIN customers c ON m.SenderID = c.CustomerID AND m.MessageType = 'customer'
              WHERE (m.SenderID = $userID AND m.ReceiverID = $otherID) 
              OR (m.SenderID = $otherID AND m.ReceiverID = $userID)
              ORDER BY m.SentDateTime ASC"; // Ensure messages are ordered by datetime

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
        $messageType = ($row['SenderID'] == $userID) ? 'sent' : 'received';
        $alignmentClass = ($messageType == 'sent') ? 'align-right' : 'align-left';
        
        // Apply alternating classes for styling
        echo "<div class='message $alignmentClass'><strong>{$row['SenderName']}:</strong><br><div class='timestamp'>{$row['SentDateTime']}</div><br><div class='text'>{$row['Body']}</div></div>";
        
        // Increment message counter
        $messageCounter++;
    }
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2><?php echo isset($_SESSION['PhotographerID']) ? "Customers" : "Photographers"; ?></h2>
            <ul>
                <?php while ($row = mysqli_fetch_assoc($usersResult)) { ?>
                    <div class="user-tab">
                        <a href="?other_id=<?php echo isset($_SESSION['PhotographerID']) ? $row['CustomerID'] : $row['PhotographerID']; ?>"><?php echo $row['Name']; ?></a>
                    </div>
                <?php } ?>
            </ul>
        </div>
        <div class="main-content">
            <?php
            if (isset($_GET['other_id'])) {
                // Display messages for the selected user
                $otherID = sanitize($_GET['other_id']);
                fetchMessages($conn, $userID, $otherID);
                ?>

                <!-- Reply form -->


                <?php
                if (isset($_POST['send_reply'])) {
                    $replyMessage = sanitize($_POST['reply_message']);
                    $senderID = $userID;
                    $recipientID = $otherID;
                    $messageType = isset($_SESSION['PhotographerID']) ? "photographer" : "customer";
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
                echo "<p>No user selected.</p>";
            }
            ?>
        </div>
    </div>
    <div class= "mess">
                    <form method="post">
                        <input type="hidden" name="other_id" value="<?php echo $otherID; ?>">
                        <textarea name="reply_message" rows="5" cols="110" required></textarea>
                        <input type="submit"  class = "reply" name="send_reply" value="Send Reply">
                    </form>
                    </div>
</body>
</html>
<style>
/* Add your CSS styles here */
.container {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            height: 600px;
            margin-top: 200px;
            margin-left: 80px;
            margin-bottom: 0; /* Remove bottom margin */
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
    overflow-y: auto;
    margin-bottom: 0; /* Remove bottom margin */
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
    background-color: #9BABB8;
    width: 500px;
    text-align: right;
    font-size: 1.3rem;
}

.timestamp {
    text-align: center;
    margin-top: 5px; /* Adjust margin as needed */
}

.text {
    font-size: 1.3rem;
    margin-right: 15px;
    margin-left: 15px;
}
.mess{
        margin-left: 492px;
        width: 810px;
    }

.reply {
        width: 150px;
        height: 40px;
        margin-left: 660px;
        font-size: 1.2rem;
        font-family: 'serif';
        background-color: #9BABB8;
    }

    
</style>
