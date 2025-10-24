<?php
function errorRedirect($destination = null, $message = null){
    if(empty($destination)){
        $destination = $_SERVER['PHP_SELF'];
    }
    header("Location: " . $destination . "?code=" . urlencode("error") . "?message=" . urlencode($message));
    exit;
}
?>