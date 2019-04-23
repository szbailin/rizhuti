<?php
// Require theme functions
require get_stylesheet_directory() . '/inc/functions-theme.php';


function rizhuti_unlock_url($txt, $key)
{
    $txt   = urldecode($txt);
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
    $ch    = $txt[0];
    $nh    = strpos($chars, $ch);
    $mdKey = md5($key . $ch);
    $mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
    $txt   = substr($txt, 1);
    $tmp   = '';
    $i     = 0;
    $j     = 0;
    $k     = 0;
    for ($i = 0; $i < strlen($txt); $i++) {
        $k = $k == strlen($mdKey) ? 0 : $k;
        $j = strpos($chars, $txt[$i]) - $nh - ord($mdKey[$k++]);
        while ($j < 0) {
            $j += 64;
        }
        $tmp .= $chars[$j];
    }
    return base64_decode($tmp);
}

// Customize your functions
 