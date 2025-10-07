<?php

	$root = $_SERVER['DOCUMENT_ROOT'];
    //$file = file_get_contents("https://getdemo.in/mas_solutions/mailers/welcome-company.html", "r");
    $file = file_get_contents($root."/mailers/forgot-pw.html", "r");

    if($user['Logo'] != ""){
        $file = str_replace("#strLogo#",env('APP_URL').'/WlLogo/'.$user['Logo'] ,$file);
    } else {
        $file = str_replace("#strLogo#","https://massolutions.thenexus.co.in/global/assets/images/logo.png",$file);
    }

    /*if($user['instaLink'] != ""){
        $file = str_replace("#instaLinkDisplay#", '' ,$file);
        $file = str_replace("#instaLink#",$user['instaLink'] ,$file);
    } else {
        $file = str_replace("#instaLinkDisplay#", 'none' ,$file);
    }

    if($user['twitterLink'] != ""){
        $file = str_replace("#twitterLinkDisplay#", '' ,$file);
        $file = str_replace("#twitterLink#",$user['twitterLink'] ,$file);
    } else {
        $file = str_replace("#twitterLinkDisplay#", 'none' ,$file);
    }*/
    $instaLink = "";
    if(isset($user['instaLink']) && $user['instaLink'] != ""){
        $instaLink = $user['instaLink'];
    } else {
        $instaLink = "https://www.instagram.com";
    }

    $file = str_replace('#instaLinkDisplay#', '', $file);
    $file = str_replace('#instaLink#', $instaLink, $file);
    
    $twitterLink = "";
    if ($user['twitterLink'] != '') {
        $twitterLink = $user['twitterLink'];
    } else {
        $twitterLink = "https://x.com";
    }
    $file = str_replace('#twitterLinkDisplay#', '', $file);
    $file = str_replace('#twitterLink#', $twitterLink, $file);
        
    
      

    $file = str_replace("#CustomerName#",$user['name'] ,$file);
    $file = str_replace("#resetpasswordLink#",$user['resetpasswordlink'],$file);
    $file = str_replace("#ContactNo#",$user['ContactNo'] ,$file);
    if($user['supportEmail'] != ""){
        $file = str_replace("#supportEmail#",$user['supportEmail'] ,$file);
    } else {
        $file = str_replace("#supportEmail#","support@masolutions.co.uk" ,$file);
    }
    echo $file;

 ?>
