<?php

include("../include/config.php");
session_start();
$senderID = $_SESSION['PhotographerID'];
echo '<input type="hidden" name="senderID" value="' . $senderID . '">';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messaging System</title>
    <style>
         body {
        background-image: url('../uploads/b.jpg');
            font-family: 'Poppins', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            animation: fadeIn 0.5s ease-in-out;
        }

        header {
            background-color: #213555;
            color: #fff;
            padding: 15px;
            text-align: center;
            width: 100%;
            position: fixed;
            top: 0;
            z-index: 1000;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        .return-button,
        input[type="submit"] {
            flex: 1;
            background-color: #213555;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            font-weight: bold;
        }

        .return-button:hover,
        input[type="submit"]:hover {
            background-color: #3a6da4;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .title {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 20px 0;
        }

        .container {
            display: flex;
            background-color: #fff;
            color: #333;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            animation: scaleIn 0.5s ease-in-out;
            box-sizing: border-box;
            width: 80%;
            margin: auto;
            margin-top: 80px; /* Adjust the top margin as needed */
        }

        .sender-container,
        .receiver-container {
            flex: 1;
            padding: 20px;
            box-sizing: border-box;
        }

        .container:hover {
            transform: scale(1.05);
        }

        form {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: stretch;
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
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s ease-in-out;
            font-family: 'Poppins', sans-serif; /* Set the font here */
        }

        input[type="submit"],
        .return-button {
            margin-right: 10px;
        }

        .return-button {
            background-color: #213555;
        }

        .return-button:hover {
            background-color: #3a6da4;
        }

        .messages-container {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .sender,
        .receiver {
            background-color: #518fce;
            color: #fff;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
            max-width: 100%;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-in-out;
        }

        .sender img,
        .receiver img {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
            border-radius: 5px;
        }

        .sender-name {
            font-weight: bold;
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <header>
        <h1>Messaging System</h1>
    </header>
    <div class="title">MESSAGING SYSTEM</div>
    <div class="container">
        <div class="sender-container">
            <h2><center>SENDER</h2></center>
            <form action="sendMessage.php" method="post" enctype="multipart/form-data">


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
                <div>
                    <input type="submit" value="Send Message">
                    <input type="button" value="Return" onclick="window.location.href='../photographer/phdashboard.php';" class="return-button">
                    <!-- <button class="return-button" onclick="goBack()">Return</button> -->
                </div>
            </form>
        </div>

        <div class="receiver-container">
            <h2><center>RECEIVER</h2></center>

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

        function displayMessages(receiverID, receiverType, messagesContainer) {
            const senderID = <?php echo json_encode($_SESSION['PhotographerID']); ?>;
            fetch(`fetchMessages.php?senderID=${senderID}&receiverID=${receiverID}&receiverType=${receiverType}`)
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
                            receiverName = message.ReceiverName;
                        } else {
                            senderName = message.SenderName;
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
            const selectedReceiverType = document.getElementById('receiverType2').value;
            const messagesContainer2 = document.getElementById('messagesContainer2');

            // Check if a valid option (not the default "Receiver") is selected
            if (selectedReceiverID !== "") {
                displayMessages(selectedReceiverID, selectedReceiverType, messagesContainer2);
            }
        });

    </script>
</body>

</html>
