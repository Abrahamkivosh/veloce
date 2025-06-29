<?php
// Check if the user is already logged in

function isLoggedIn()
{
  return isset($_SESSION['username']) && isset($_SESSION['userid']);
}
