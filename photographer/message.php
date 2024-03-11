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

    // Display messages
    while ($row = mysqli_fetch_assoc($result)) {
        $messageType = ($row['SenderID'] == $userID) ? 'sent' : 'received';
        $alignmentClass = ($messageType == 'sent') ? 'align-right' : 'align-left';
        
        // Apply alternating classes for styling
        echo "<div class='message $alignmentClass'>";
        echo "<strong>{$row['SenderName']}:</strong><br>";
        echo "<div class='timestamp'>{$row['SentDateTime']}</div><br>";
        echo "<div class='text'>{$row['Body']}</div>";

        // Check if there's an image associated with the message
        if (!empty($row['img_message'])) {
            echo "<img src='../uploads/{$row['img_message']}' alt='Image'>";
        }

        echo "</div>";
    }
}




// Function to fetch list of users (photographers or customers) who messaged the current user
function fetchUsers($conn, $currentUserID) {
    $userType = isset($_SESSION['PhotographerID']) ? "customers" : "photographers";
    $userIDColumn = isset($_SESSION['PhotographerID']) ? "CustomerID" : "PhotographerID";
    $query = "SELECT DISTINCT u.$userIDColumn AS ID, u.Name
              FROM messages m
              INNER JOIN $userType u ON u.$userIDColumn = m.SenderID
              WHERE m.ReceiverID = $currentUserID";

    $result = mysqli_query($conn, $query);

    // Check for query execution success
    if (!$result) {
        echo "Error: " . mysqli_error($conn);
        exit();
    }

    return $result;
}

$userID = isset($_SESSION['PhotographerID']) ? $_SESSION['PhotographerID'] : $_SESSION['CustomerID'];

// Fetch list of users (photographers or customers) who messaged the current user
$usersResult = fetchUsers($conn, $userID);

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
        /* Copy the CSS styles from customermessage.php */
        .container {
            display: flex;
            justify-content: space-between;
            max-width: 100%;
            height: 600px;
            margin-top: 20px;
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
    background-color: #9BABB8;
    width: 500px;
    text-align: right;
    font-size: 1.3rem;
}
.timestamp {
    text-align: center;
    margin-top: 5px; /* Adjust margin as needed */
}

.message img {
    max-width: 100%; /* Ensure the image does not exceed its container width */
    height: auto; /* Maintain aspect ratio */
    margin-top: 10px; /* Adjust margin as needed */
    border-radius: 5px; /* Add rounded corners if desired */
}
    .mess{
        margin-left: 850px;;
        width: 66%;
    }

    .reply {
        width: 150px;
        height: 40px;
        margin-left: 2800px;
        font-size: 1.2rem;
        font-family: 'serif';
        background-color: #9BABB8;
        margin-bottom: 30px;
        
    }
    input[type="file"] {
    margin-left: 450px; 
    margin-top: 10px;/* Adjust margin-left as needed *//* Adjust margin-top as needed */
    /* Add additional styling as needed */
}
    .text {
        font-size: 1.3rem;
        margin-right: 15px;
        margin-left: 15px;
    }
    textarea[name="reply_message"] {
    margin-left: 450px; /* Adjust margin-left as needed */
    width: calc(100% - 40px); /* Set width to fill container minus left and right margin */
    padding: 10px; /* Add padding as needed */
    font-size: 1.3rem; /* Adjust font size as needed */
}

    h2 {
    font-size: 4rem;
    margin-top: 50px;
    margin-left: 50px;
    font-family: 'Satisfy';
}
    </style>
</head>
<body>
<h2><?php echo isset($_SESSION['PhotographerID']) ? "Customers" : "Photographers"; ?></h2>
    <div class="container">
        <div class="sidebar">
            
            <ul>
                <?php while ($row = mysqli_fetch_assoc($usersResult)) { ?>
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
                fetchMessages($conn, $userID, $otherID);
                ?>

                
                </div>
                <?php
if (isset($_POST['send_reply'])) {
    echo "Form submitted<br>"; // Debugging output for form submission

    $replyMessage = sanitize($_POST['reply_message']);
    $senderID = $userID;
    $recipientID = $otherID;
    $messageType = isset($_SESSION['PhotographerID']) ? "photographer" : "customer";
    
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

    // Insert message into database
    $query = "INSERT INTO messages (SenderID, ReceiverID, MessageType, Body, img_message) 
              VALUES ('$senderID', '$recipientID', '$messageType', '$replyMessage', '$imagePath')";
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo "Message inserted into the database<br>"; // Debugging output for database insertion
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
    <div class = "mess">

    <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="other_id" value="<?php echo $otherID; ?>">
    <textarea name="reply_message" rows="7" cols="248" required></textarea><br>
    <input type="file" name="image" accept="image/*"> <!-- Input field for image -->
    <input type="submit" class="reply" name="send_reply" value="Send Reply">


    </div></form>

</body>
</html>
