<?php
// selfcare/handle-login.php
require('db.php');
session_start();

// Handle POST request only
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Sanitize inputs
  $username = sanitizeInput($_POST['username']);
  $password = sanitizeInput($_POST['password']);

  // Use prepared statements to prevent SQL injection
  $stmt = $con->prepare("SELECT id, username, portalloginpassword FROM userinfo WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  // dump $result for debugging
  // var_dump($result); // Uncomment for debugging purposes
  // exit();

  if ($result && $result->num_rows === 1) {
    $user = $result->fetch_assoc();
    // dump $user for debugging
    // var_dump($user); // Uncomment for debugging purposes
    // exit();
    // Compare passwords
    if ($user['portalloginpassword'] === md5($password)) {
      $_SESSION['username'] = $user['username'];
      $_SESSION['userid'] = $user['id'];

      // Redirect to dashboard
      header("Location: dash.php");
      exit();
    }
  }

  // Login failed: store error in session and redirect
  $_SESSION['login_error'] = "Invalid username or password.";
  header("Location: login.php");
  exit();
} else {
  // Redirect if not POST
  header("Location: login.php");
  exit();
}

// Basic sanitation
function sanitizeInput($data)
{
  return htmlspecialchars(trim(stripslashes($data)));
}
