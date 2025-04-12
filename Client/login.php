<!-- filepath: c:\xampp\htdocs\projects\anon\login.php -->
<?php
session_start();
require 'db_config.php';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: view_messages.php");
        exit();
    } else {
        $loginError = "Invalid username or password.";
    }
}

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        $registerSuccess = "Registration successful! Please log in.";
    } else {
        $registerError = "Registration failed. Username or email might already exist.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>VeiL | Homepage</title>

  <!-- Icons -->
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

  <style>
    html {
      scroll-behavior: smooth;
    }

    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: url('https://png.pngtree.com/thumb_back/fh260/background/20230411/pngtree-unrecognizable-hacker-portrait-adult-anonymity-photo-image_2311844.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #fff;
    }

    header {
      background: rgba(0, 0, 0, 0.7);
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .navigation a, .navigation button {
      color: #fff;
      text-decoration: none;
      margin-left: 15px;
      font-weight: bold;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      background: transparent;
      border: none;
      cursor: pointer;
      padding: 8px 12px;
    }

    .navigation button {
      background: #04AA6D;
      border-radius: 5px;
    }

    .hidden {
      display: none !important;
    }

    .modal {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.7);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .modal-content {
      background: #222;
      padding: 20px;
      border-radius: 10px;
      width: 90%;
      max-width: 500px;
      color: white;
    }

    .modal-content h3 {
      margin-top: 0;
    }

    .modal-content input[type="text"],
    .modal-content input[type="email"],
    .modal-content input[type="password"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
      border: none;
    }

    .modal-content button {
      padding: 10px 20px;
      background: #04AA6D;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .modal-footer {
      display: flex;
      justify-content: space-between;
      font-size: 14px;
      margin-top: 10px;
    }
  </style>
</head>
<body>

  <header>
    <h2>VeiL</h2>
    <nav class="navigation">
      <a href="index.php"><ion-icon name="home-outline"></ion-icon>Home</a>
      <button id="servicesBtn"><ion-icon name="briefcase-outline"></ion-icon>Services</button>
      <button id="contactBtn"><ion-icon name="call-outline"></ion-icon>Contact</button>

      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="view_messages.php"><ion-icon name="chatbubbles-outline"></ion-icon>View Messages</a>
        <a href="logout.php"><ion-icon name="log-out-outline"></ion-icon>Logout</a>
      <?php else: ?>
        <button id="loginBtn"><ion-icon name="log-in-outline"></ion-icon>Login</button>
        <button id="registerBtn"><ion-icon name="person-add-outline"></ion-icon>Register</button>
      <?php endif; ?>
    </nav>
  </header>

  <!-- Login Modal -->
  <div id="loginModal" class="modal hidden">
    <div class="modal-content">
      <h3>Login</h3>
      <?php if (isset($loginError)): ?>
        <p style="color: red;"><?php echo $loginError; ?></p>
      <?php endif; ?>
      <form method="post">
        <input type="hidden" name="action" value="login">
        Username: <input name="username" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit">Login</button>
      </form>
    </div>
  </div>

  <!-- Register Modal -->
  <div id="registerModal" class="modal hidden">
    <div class="modal-content">
      <h3>Register</h3>
      <?php if (isset($registerError)): ?>
        <p style="color: red;"><?php echo $registerError; ?></p>
      <?php elseif (isset($registerSuccess)): ?>
        <p style="color: green;"><?php echo $registerSuccess; ?></p>
      <?php endif; ?>
      <form method="post">
        <input type="hidden" name="action" value="register">
        Username: <input name="username" required><br>
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit">Register</button>
      </form>
    </div>
  </div>

  <!-- Services Modal -->
  <div id="servicesModal" class="modal hidden">
    <div class="modal-content">
      <h3>Our Services</h3>
      <ul>
        <li>✅ Anonymous message submissions</li>
        <li>✅ Message filtering by IP (admin)</li>
        <li>✅ Secure user login and registration</li>
        <li>✅ Admin dashboard for moderation</li>
      </ul>
    </div>
  </div>

  <!-- Contact Modal -->
  <div id="contactModal" class="modal hidden">
    <div class="modal-content">
      <h3>Contact Us</h3>
      <ul>
        <li>📧 Email: support@veilproject.com</li>
        <li>📞 Phone: +1 800 123 4567</li>
        <li>📍 Address: 123 Internet City, Lalaland</li>
      </ul>
    </div>
  </div>

  <script>
    const loginBtn = document.getElementById('loginBtn');
    const registerBtn = document.getElementById('registerBtn');
    const servicesBtn = document.getElementById('servicesBtn');
    const contactBtn = document.getElementById('contactBtn');

    const loginModal = document.getElementById('loginModal');
    const registerModal = document.getElementById('registerModal');
    const servicesModal = document.getElementById('servicesModal');
    const contactModal = document.getElementById('contactModal');

    loginBtn?.addEventListener('click', () => {
      loginModal.classList.remove('hidden');
    });

    registerBtn?.addEventListener('click', () => {
      registerModal.classList.remove('hidden');
    });

    servicesBtn?.addEventListener('click', () => {
      servicesModal.classList.remove('hidden');
    });

    contactBtn?.addEventListener('click', () => {
      contactModal.classList.remove('hidden');
    });

    // Close modal on click outside
    window.addEventListener('click', e => {
      if (e.target.classList.contains('modal')) {
        loginModal.classList.add('hidden');
        registerModal.classList.add('hidden');
        servicesModal.classList.add('hidden');
        contactModal.classList.add('hidden');
      }
    });
  </script>
</body>
</html>
