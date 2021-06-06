<?php
session_start();
if(isset($_POST['confirm'])){
    if($_POST['confirm']){
        $_SESSION['permission_age'] = true;
    }
}
