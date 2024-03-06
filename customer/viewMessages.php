<?php
// Include the database connection
include("../include/config.php");

// Start the session
session_start();

// Check if the user is logged in
if (isset($_SESSION['CustomerID'])) {
    // Get the logged-in user's ID from the session
    $loggedInUserID = $_SESSION['CustomerID'];

    // Fetch messages for the logged-in customer as a receiver
    $query = "SELECT M.SenderID, M.Body, M.img_message,
        CASE
          WHEN M.MessageType = 'customer' THEN C.Name
          WHEN M.MessageType = 'photographer' THEN P.Name
          WHEN M.MessageType = 'admin' THEN A.Name
          ELSE 'Unknown'
        END AS SenderName
      FROM Messages M
      LEFT JOIN Customers C ON M.SenderID = C.CustomerID AND M.MessageType = 'customer'
      LEFT JOIN Photographers P ON M.SenderID = P.PhotographerID AND M.MessageType = 'photographer'
      LEFT JOIN Admin A ON M.SenderID = A.AdminID AND M.MessageType = 'admin'
      WHERE M.ReceiverID = ? AND M.MessageType IN ('customer', 'photographer', 'admin')
      ORDER BY M.MessageID ASC";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Bind the parameter
        mysqli_stmt_bind_param($stmt, "i", $loggedInUserID);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Get the result
            $result = mysqli_stmt_get_result($stmt);

            // Display the messages in a chat-like interface
            echo '<html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>View Messages</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                margin: 0;
                                padding: 20px;
                                background-color: #f7f7f7;
                            }
                            .chat-container {
                                max-width: 600px;
                                margin: 20px auto;
                                border: 1px solid #ccc;
                                border-radius: 10px;
                                overflow: hidden;
                            }
                            .chat-messages {
                                max-height: 400px;
                                overflow-y: auto;
                                padding: 10px;
                            }
                            .message {
                                margin-bottom: 10px;
                            }
                            .message img {
                                max-width: 100%;
                                height: auto;
                                border-radius: 5px;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="chat-container">
                            <div class="chat-messages">';

            // Fetch data and display messages
            while ($row = mysqli_fetch_assoc($result)) {
                $messageBody = htmlspecialchars($row['Body']);
                $senderName = htmlspecialchars($row['SenderName']);
                $imgMessage = isset($row['img_message']) ? '<img src="../uploads/' . $row['img_message'] . '" alt="Image">' : '';

                echo '<div class="message">
                        <strong>' . $senderName . ':</strong>
                        <p>' . $messageBody . '</p>
                        ' . $imgMessage . '
                    </div>';
            }

            // Close the result set and the statement
            mysqli_free_result($result);
            mysqli_stmt_close($stmt);

            // Display the closing HTML tags
            echo '      </div>
                        </div>
                    </body>
                </html>';
        } else {
            // Handle the execution error
            die("Error executing the query: " . mysqli_error($conn));
        }
    } else {
        // Handle the prepared statement error
        die("Error preparing the statement: " . mysqli_error($conn));
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    // Redirect or handle the case where the user is not logged in
    header("Location: login.php");
    exit();
}
?>
