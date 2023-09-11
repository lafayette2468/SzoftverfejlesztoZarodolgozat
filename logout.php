<?php
session_start();


if(session_destroy()){
    // echo '<h2>A munkamenet törlése sikeres.</h2>';
    header('Location: home');

    
}


?>

