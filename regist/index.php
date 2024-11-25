<?php
session_start();
session_regenerate_id(true);

//セッションが空でなければ、前回のデータ取得
if (isset($_SESSION['regist'])) {
    unset($_SESSION['regist']);
}
header('Location: input.php');
