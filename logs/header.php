<?php
   session_start();
if (isset($_SESSION['valid']) && $_SESSION['valid'] == true) {
    echo "<br /><span style='padding-left: 5%;'> Welcome to the member's area, " . $_SESSION['username'] . "!";
    echo "\t" . " | <a href = 'logout.php' tite = 'Logout'> Logout </a>| </span>";
} else {
    echo "Please log in first to see this page.";
    $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';

    header("Location: " . $protocol. '://' . getenv('HTTP_HOST') . "/logs/login.php");
}
?>