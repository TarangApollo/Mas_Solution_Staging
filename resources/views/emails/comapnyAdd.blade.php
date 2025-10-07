<?php

	$root = $_SERVER['DOCUMENT_ROOT'];
    //$file = file_get_contents("https://getdemo.in/mas_solutions/mailers/welcome-company.html", "r");
    $file = file_get_contents($root."/mailers/welcome-company.html", "r");
    $file = str_replace("#email",$user['email'] ,$file);
    $file = str_replace("#password",$password,$file);
    $name = $user['first_name'];
    if(trim($user['last_name']) != '')
    {
        $name =  $name . ' ' .trim($user['last_name']);
    }
    $file = str_replace("#name",$name,$file);
    $file = str_replace("#companyName",$Company,$file);
    /*if($data['instaLink'] != ""){
        $file = str_replace("#instaLinkDisplay#", '' ,$file);  
        $file = str_replace("#instaLink#",$data['instaLink'] ,$file);
    } else {
        $file = str_replace("#instaLinkDisplay#", 'none' ,$file);
    }*/

    /*if($data['twitterLink'] != ""){
        $file = str_replace("#twitterLinkDisplay#", '' ,$file);  
        $file = str_replace("#twitterLink#",$data['twitterLink'] ,$file);
    } else {
        $file = str_replace("#twitterLinkDisplay#", 'none' ,$file);  
    }

    if($data['facebookLink'] != ""){
        $file = str_replace("#facebookLinkDisplay#", '' ,$file);  
        $file = str_replace("#facebookLink#",$data['facebookLink'] ,$file);
    } else {
        $file = str_replace("#facebookLinkDisplay#", 'none' ,$file);
    }

    if($data['linkedinLink'] != ""){
        $file = str_replace("#linkedinLinkDisplay#", '' ,$file);  
        $file = str_replace("#linkedinLink#",$data['linkedinLink'] ,$file);
    } else {
        $file = str_replace("#linkedinLinkDisplay#", 'none' ,$file);
    }*/
    $instaLink = "";
    if(isset($data['instaLink']) && $data['instaLink'] != ""){
        $instaLink = $data['instaLink'];
    } else {
        $instaLink = "https://www.instagram.com";
    }

    $file = str_replace('#instaLinkDisplay#', '', $file);
    $file = str_replace('#instaLink#', $instaLink, $file);
    
    $twitterLink = "";
    if ($data['twitterLink'] != '') {
        $twitterLink = $data['twitterLink'];
    } else {
        $twitterLink = "https://x.com";
    }
    $file = str_replace('#twitterLinkDisplay#', '', $file);
    $file = str_replace('#twitterLink#', $twitterLink, $file);
        
    $facebookLink = "";
    if ($data['facebookLink'] != '') {
        $facebookLink = $data['facebookLink'];
    } else {
        $facebookLink = "https://www.facebook.com";
    }
    $file = str_replace('#facebookLinkDisplay#', '', $file);
    $file = str_replace('#facebookLink#', $facebookLink, $file);

    $linkedinLink = "";
    if ($data['linkedinLink'] != '') {
        $linkedinLink = $data['linkedinLink'];
    } else {
        $linkedinLink = "https://in.linkedin.com";
    }
    $file = str_replace('#linkedinLinkDisplay#', '', $file);
    $file = str_replace('#linkedinLink#', $linkedinLink, $file);
      
    echo $file;

 ?>
