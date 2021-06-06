<?php
include "includes/header.php";
include "includes/navigation.php";


showCart();

if (isset($_GET['option'])) {
    switch ($_GET['option']) {
        case 'shop':
            include "shop.php";
            break;
        case 'cart':
            include "cart.php";
            break;
        case 'checkout1':
            include "checkout1.php";
            break;
        case 'confirm_order':
            include "confirm-order.php";
            break;
        case 'detail':
            include "detail.php";
            break;
        case 'contact':
            include "contact.php";
            break;
        case 'about':
            include "about.php";
            break;
        case 'verified':
            include "verified_order.php";
            break;
        case 'faq':
            include "faq.php";
            break;
        case 'track_order':
            include "track_order.php";
            break;
        case 'terms':
            include "terms.php";
            break;
        case 'privacy_policy':
            include "privacy-policy.php";
            break;
        default:
            include "home.php";
    }
} else {
    include "home.php";
}

include "includes/footer.php";
