<?php
session_start();
session_regenerate_id(true);

if (isset($_SESSION['user'])) {
  unset($_SESSION['user']);
}
header('Location: ../login/');
