<?php
// Include the database connection
include("../include/config.php");

// Start the session
session_start();

// Check if the user is logged in
if (isset($_SESSION['CustomerID']) && isset($_GET['receiverID'])) {
    // Get the logged-in user's ID from the session
    $loggedInUserID = $_SESSION['CustomerID'];
    // Get the receiver ID from the URL parameter
    $receiverID = $_GET['receiverID'];

    // Initialize an empty array to store the messages
    $messages = array();

    // Perform a query to fetch messages along with sender and receiver names
    $query = "SELECT M.MessageID, M.SenderID, M.ReceiverID, M.Body, M.img_message, 
              CASE
                WHEN M.MessageType = 'customer' THEN C.Name
                WHEN M.MessageType = 'photographer' THEN P.Name
                WHEN M.MessageType = 'admin' THEN A.Name
                ELSE 'Unknown'
              END AS SenderName,
              CASE
                WHEN M.MessageType = 'customer' AND M.ReceiverID = C.CustomerID THEN C.Name
                WHEN M.MessageType = 'photographer' AND M.ReceiverID = P.PhotographerID THEN P.Name
                WHEN M.MessageType = 'admin' AND M.ReceiverID = A.AdminID THEN A.Name
                ELSE 'Unknown'
              END AS ReceiverName
              FROM Messages M
              LEFT JOIN Customers C ON M.SenderID = C.CustomerID AND M.MessageType = 'customer'
              LEFT JOIN Photographers P ON M.SenderID = P.PhotographerID AND M.MessageType = 'photographer'
              LEFT JOIN Admin A ON M.SenderID = A.AdminID AND M.MessageType = 'admin'
              WHERE (M.SenderID = ? AND M.ReceiverID = ?) OR (M.SenderID = ? AND M.ReceiverID = ?)
              ORDER BY M.MessageID DESC";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "iiii", $loggedInUserID, $receiverID, $receiverID, $loggedInUserID);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Get the result
            $result = mysqli_stmt_get_result($stmt);

            // Fetch data and add it to the messages array
            while ($row = mysqli_fetch_assoc($result)) {
                $messages[] = $row;
            }

            // Close the result set
            mysqli_free_result($result);
        } else {
            // Handle the execution error
            die("Error executing the query: " . mysqli_error($conn));
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        // Handle the prepared statement error
        die("Error preparing the statement: " . mysqli_error($conn));
    }

    // Output the messages as JSON
    header('Content-Type: application/json');

    // Iterate through messages and prepend local path to img_message
    foreach ($messages as &$message) {
        // Check if img_message is not null before appending to the JSON array
        if ($message['img_message'] !== null) {
            $message['img_message'] = '../uploads/' . $message['img_message'];
        }
    }

    echo json_encode($messages);

    // Close the database connection
    mysqli_close($conn);
} else {
    // Redirect or handle the case where the user is not logged in or receiverID is not provided
    header("Location: login.php");
    exit();
}
?>
