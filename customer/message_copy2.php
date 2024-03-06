<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messaging System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: space-around;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            color: #333;
            padding: 20px;
            border-radius: 10px;
            width: 45%;
            transition: transform 0.3s ease-in-out;
        }

        .container:hover {
            transform: scale(1.05);
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #518fce;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #3a6da4;
        }

        .messages-container {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 10px;
            
        }

        .sender  {
            background-color: #518fce;
            color: #fff;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
            max-width: 75%;
            align-self: flex-end;
            overflow: hidden; /* Ensure content doesn't overflow */
            
        }

        .receiver {
            background-color: #518fce;
            color: #fff;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
            max-width: 75%;
            align-self: flex-end;
            overflow: hidden; /* Ensure content doesn't overflow */
        }

        .sender img,
        .receiver img {
            max-width: 100%; /* Ensure images don't exceed container width */
            height: auto; /* Maintain aspect ratio */
            margin-top: 10px; /* Add spacing between messages and images */
        }

        .sender-name {
            font-weight: bold;
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <div class="container" id="container1">
        <h2>Messaging System - SENDER</h2>
        <form action="sendMessage.php" method="post" enctype="multipart/form-data">
            <?php
            include("../include/config.php");
            session_start();
            $senderID = $_SESSION['CustomerID'];
            echo '<input type="hidden" name="senderID" value="' . $senderID . '">';
            ?>

            <label for="receiverType">Choose Receiver Type:</label>
            <select name="receiverType" id="receiverType">
                <option value="admin">Admin</option>
                <option value="photographer">Photographer</option>
                <option value="customer">Customer</option>
            </select>
            <br>

            <label for="receiverID">Choose Receiver:</label>
            <select name="receiverID" id="receiverID"></select>
            <br>

            <label for="image">Upload Image:</label>
            <input type="file" name="image" id="image">
            <br>

            <label for="message">Message:</label>
            <textarea name="message" id="message" rows="4" cols="50"></textarea>
            <br>
            <input type="submit" value="Send Message">
        </form>
    </div>
    <div class="container" id="container2">
    <h2>Messaging System - RECEIVER</h2>

    <label for="receiverType2">Choose Receiver Type:</label>
    <select name="receiverType2" id="receiverType2">
        <option value="" disabled selected>Receiver Type</option>
        <option value="admin">Admin</option>
        <option value="photographer">Photographer</option>
        <option value="customer">Customer</option>
    </select>
    <br>

    <label for="receiverID2">Choose Receiver:</label>
    <select name="receiverID2" id="receiverID2">
        <option value="" disabled selected>Receiver</option>
    </select>
    
    <div class="messages-container" id="messagesContainer2"></div>
</div>

    <script>
        function fetchUsers(selectedType, receiverDropdown) {
            receiverDropdown.innerHTML = '';

            fetch(`fetchUsers.php?type=${selectedType}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(user => {
                        const option = document.createElement('option');
                        option.value = user.ID;
                        option.text = user.Name;
                        receiverDropdown.add(option);
                    });

                    if (selectedType === 'other') {
                        const otherOption = document.createElement('option');
                        otherOption.value = 'custom';
                        otherOption.text = 'Other (Enter Custom ID)';
                        receiverDropdown.add(otherOption);
                    }
                });
        }

        function displayMessages(receiverID, messagesContainer) {
    const senderID = <?php echo json_encode($_SESSION['CustomerID']); ?>;
    fetch(`fetchMessages.php?senderID=${senderID}&receiverID=${receiverID}`)
        .then(response => response.json())
        .then(messages => {
            messagesContainer.innerHTML = '';

            messages.forEach(message => {
                const messageDiv = document.createElement('div');
                const senderClass = (message.SenderID == senderID) ? 'sender' : 'receiver';

                messageDiv.classList.add(senderClass);

                // Use conditional statements to set senderName and receiverName
                let senderName = '';
                let receiverName = '';

                if (message.SenderID == senderID) {
                    senderName = 'You';
                    receiverName = message.ReceiverName;  // Use the actual receiver name
                } else {
                    senderName = message.SenderName;  // Use the actual sender name
                    receiverName = 'You';
                }

                messageDiv.innerHTML = `<span class="sender-name">${senderName} to ${receiverName}:</span> ${message.Body}`;

                if (message.img_message !== null) {
                    const imageElement = document.createElement('img');
                    imageElement.src = `../uploads/${message.img_message}`;
                    imageElement.alt = 'Image';
                    messageDiv.appendChild(imageElement);
                }

                messagesContainer.appendChild(messageDiv);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            });
        });
}

        document.getElementById('receiverType').addEventListener('change', function () {
            const selectedType = this.value;
            const receiverDropdown = document.getElementById('receiverID');
            fetchUsers(selectedType, receiverDropdown);
        });

        fetchUsers('admin', document.getElementById('receiverID'));
        fetchUsers('photographer', document.getElementById('receiverID'));
        fetchUsers('customer', document.getElementById('receiverID'));

        document.getElementById('receiverType2').addEventListener('change', function () {
    const selectedType = this.value;
    const receiverDropdown = document.getElementById('receiverID2');

    // Enable or disable the receiverID2 dropdown based on the selected type
    receiverDropdown.disabled = (selectedType === '');

    // Fetch users if a valid type is selected
    if (selectedType !== '') {
        fetchUsers(selectedType, receiverDropdown);
    } else {
        // If the type is not selected, clear the receiverID2 dropdown
        receiverDropdown.innerHTML = '<option value="" disabled selected>Receiver</option>';
        // Clear the messagesContainer2
        document.getElementById('messagesContainer2').innerHTML = '';
    }
});

document.getElementById('receiverID2').addEventListener('change', function () {
    const selectedReceiverID = this.value;
    const messagesContainer2 = document.getElementById('messagesContainer2');

    // Check if a valid option (not the default "Receiver") is selected
    if (selectedReceiverID !== "") {
        displayMessages(selectedReceiverID, messagesContainer2);
    }
});

    </script>
</body>

</html>
