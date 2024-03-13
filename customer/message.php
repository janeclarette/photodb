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
        echo "<div class='message $alignmentClass'>";
        echo "<strong>{$row['SenderName']}:</strong><br>";
        echo "<div class='timestamp'>{$row['SentDateTime']}</div><br>";
        echo "<div class='text'>{$row['Body']}</div>";

        // Check if there's an image associated with the message
        if (!empty($row['img_message'])) {
            echo "<img src='../uploads/{$row['img_message']}' alt='Image'>";
        }

        echo "</div>";
        
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
<h2><?php echo isset($_SESSION['PhotographerID']) ? "Customers" : "Photographers"; ?></h2>
    <div class="container">
        <div class="sidebar">
            
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
        // Reset the internal pointer of $usersResult
        mysqli_data_seek($usersResult, 0);
        // Get the name of the photographer
        $photographerName = ""; // Initialize the variable
        while ($row = mysqli_fetch_assoc($usersResult)) {
            if ($otherID == $row['PhotographerID']) {
                $photographerName = $row['Name'];
                break; // Exit the loop once the name is found
            }
        }
        // Display the name of the photographer
        echo "<div class='photographer-name-container'><h3>$photographerName</h3></div>";
        
        // Fetch and display messages
        fetchMessages($conn, $userID, $otherID);
    }
    ?>

                <!-- Reply form -->


                <?php
if (isset($_POST['send_reply'])) {
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
        // Refresh the page to show the updated messages
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error: Failed to send reply.";
    }
}
            
            ?>
        </div>
    </div>
    <div class="mess">
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="other_id" value="<?php echo $otherID; ?>">
        <textarea name="reply_message" rows="5" cols="110" required></textarea>
        <input type="file" name="image" accept="image/*"> <!-- Input field for image -->
        <input type="submit" class="reply" name="send_reply" value="Send Reply">
    </form>
</div>


                    
</body>
</html>
<style>
/* Container styles */
body {
   

   background-image: url('../uploads/cover.jpg');  /* Set the path to your cover image */
   background-size: cover;
   background-position: center bottom; /* Lower the background image */

}
.container {
    display: flex;
    justify-content: space-between;
    max-width: 90%; /* Adjusted max-width */
    height: 70vh; /* Adjusted height */
    margin: 20px auto; /* Centered horizontally with some margin */
    position: relative; /* Added position relative */
    background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent white background */
    border: 2px solid rgba(255,255,255, 10);
    backdrop-filter: blur(10px); /* Apply a blur effect behind the container */
    padding: 30px 40px;
}

/* Reply form styles */
.mess {
    position: relative; /* Changed position to absolute */
    bottom: 10px; /* Adjusted position from the bottom */
    right: 400px; /* Adjusted position from the right */
    margin: 20px auto;
    margin-left: 940px;
    width: 58%;
    background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent white background */
    border: 2px solid rgba(255,255,255, 10);
    backdrop-filter: blur(10px); /* Apply a blur effect behind the container */
    padding: 20px 30px;
}

.reply {
    width: 150px;
    height: 40px;
    margin-top: 20px; /* Adjusted margin from the top */
    font-size: 1.2rem;
    font-family: 'serif';
    background-color: #333;
}

.photographer-name-container {
    position: relative;
    top: 0px; /* Adjust as needed */
    left: 50%;
    transform: translateX(-50%);
    border: 2px solid rgba(255,255,255, 10);
    padding: 10px; /* Add padding as needed */
    border-radius: 5px; /* Add border radius as needed */
    text-align: center; /* Center the text */

}

.photographer-name-container h3 {
    margin: 0;
    color: #333; /* Add your desired text color here */
    
}


/* Sidebar styles */
.sidebar {
    flex-basis: 30%;
    padding: 20px;
    border: 2px solid rgba(255,255,255, 10);
}

.user-tab {
    background-color: rgba(75, 192, 192, 8);    
    padding: 15px;
    backdrop-filter: blur(15);
    margin-bottom: 20px;
    border-radius: 5px;    
}

.user-tab a {
    text-decoration: none;
    color: #fff;
}

/* Main content styles */
.main-content {
    flex-basis: 65%;
    padding: 20px;
    background-color: rgba(75, 192, 192, 20);
    backdrop-filter: blur(15);
    border: 1px solid #ddd;
    overflow-y: auto;
    margin-bottom: 0;
}

/* Message styles */
.message {
    margin-top: 20px;
    padding: 0px;
    border-radius: 5px;
}

.message img {
    max-width: 100%;
    height: auto;
    margin-top: 10px;
    border-radius: 5px;
}

.align-left {
    float: left;
    clear: both;
    background-color: #fff;
    width: 400px;
    font-size: 1.3rem;
}

.align-right {
    float: right;
    clear: both;
    background-color: #fff;
    width: 400px;
    text-align: right;
    font-size: 1.3rem;
}

.timestamp.align-left {
    text-align: right;
    margin-top: 5px;
}

.text {
    font-size: 1.3rem;
    margin-right: 15px;
    margin-left: 15px;
}

h2 {
    font-size: 3rem;
    margin-top: 50px;
    margin-left: 50px;
    font-family: 'Satisfy';
}
 
</style>
