<?php
session_start();
require 'db_config.php';

// Fetch all users
$users_result = $conn->query("SELECT id, username FROM users");
$users = [];
while ($row = $users_result->fetch_assoc()) {
    $users[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SHEESH!</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background: url('bg.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #fff;
      backdrop-filter: blur(5px);
    }

    .container {
      max-width: 650px;
      margin: 60px auto;
      background: rgba(0, 0, 0, 0.6);
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
    }

    h2 {
      text-align: center;
      margin-bottom: 10px;
      color: #00ffd5;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .subtext {
      text-align: center;
      font-size: 16px;
      margin-bottom: 15px;
      color: #ffffffcc;
    }

    #messages {
      border: 1px solid #00ffd5;
      background: rgba(255, 255, 255, 0.1);
      padding: 15px;
      height: 250px;
      overflow-y: auto;
      border-radius: 8px;
      margin-bottom: 15px;
    }

    #messages p {
      margin: 8px 0;
      padding: 6px;
      background: rgba(0, 0, 0, 0.4);
      border-left: 3px solid #00ffd5;
      border-radius: 4px;
    }

    textarea, select {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.9);
      color: #000;
      font-size: 16px;
      margin-bottom: 10px;
    }

    button {
      background-color: #00ffd5;
      color: #000;
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      width: 100%;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #00c0a1;
    }

    #feedback {
      display: none;
      text-align: center;
      margin-top: 15px;
      color: #00ff88;
      font-weight: bold;
    }

    .user-select {
      margin: 15px 0;
    }
  </style>
</head>
<body>

<div class="container">
  <h2><ion-icon name="skull-sharp"></ion-icon>SHEESH!</h2>

  <div class="subtext">
    <button onclick="window.location.href='login.php'">Go to Login</button>
  </div>

  <div class="user-select">
    <select id="userSelect" required>
      <option value="">Choose a user to send a message</option>
      <?php foreach ($users as $user): ?>
        <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div id="messages"></div>

  <textarea id="message" placeholder="Type your anonymous message..."></textarea>
  <button id="send">Submit</button>

  <div id="feedback">✅ Message submitted!</div>
</div>

<script>
  $("#send").click(function () {
    const message = $("#message").val().trim();
    const userId = $("#userSelect").val();

    if (!userId) {
      alert("Please select a user.");
      return;
    }

    if (message !== "") {
      $.post('submit_messages.php', { message: message, user_id: userId }, function (response) {
        if (response.includes("Message submitted!")) {
          $("#feedback").text("✅ Message submitted!").fadeIn();
          $("#message").val("");
        } else {
          $("#feedback").text("❌ " + response).fadeIn();
        }
      }).fail(function () {
        $("#feedback").text("❌ Request failed").fadeIn();
      });
    }
  });
</script>

</body>
</html>
