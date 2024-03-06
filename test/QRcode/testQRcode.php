<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator</title>
</head>
<body>
    <h2>QR Code Generator</h2>
        <p>Logged in as User ID: 1</p>
        <a href="logout.php">Logout</a>
        <br><br>
        <h3>Events:</h3>
        <ul>
            <!-- Suppose we have event IDs stored in an array called $events -->
            <li><a href="#" onclick="openQRModal('3')">Generate QR for Event 1</a></li>
        </ul>


    <!-- Modal -->
    <div id="qrModal" class="modal">
        <span class="close" onclick="closeQRModal()">&times;</span>
        <img id="qrImage" alt="QR Code">
    </div>

    <script>
        function openQRModal(eventId) {
            var modal = document.getElementById('qrModal');
            var qrImage = document.getElementById('qrImage');
            qrImage.src = 'generate_qr.php?event=' + eventId;
            modal.style.display = 'block';
        }

        function closeQRModal() {
            var modal = document.getElementById('qrModal');
            modal.style.display = 'none';
        }
    </script>
</body>
</html>
