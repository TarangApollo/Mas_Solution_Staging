<?php

	$root = $_SERVER['DOCUMENT_ROOT'];
    $file = file_get_contents($root."/mailers/welcome-user.html", "r");
    //$file = file_get_contents("https://getdemo.in/mas_solutions/mailers/welcome-user.html", "r");
    $file = str_replace("#email",$user['email'] ,$file);
    $file = str_replace("#password",$password,$file);
    $name = $user['first_name'];
    if(trim($user['last_name']) != '')
    {
        $name =  $name . ' ' .trim($user['last_name']);
    }
    $file = str_replace("#name",$name,$file);
    //$file = str_replace("#companyName",$Company,$file);

    if($data['Logo'] != ""){
        $file = str_replace("#strLogo#",$data['Logo'] ,$file);
    } else {
        $file = str_replace("#strLogo#","https://massolutions.thenexus.co.in/global/assets/images/logo.png",$file);
    }

    if($data['instaLink'] != ""){
        $file = str_replace("#instaLinkDisplay#", '' ,$file);  
        $file = str_replace("#instaLink#",$data['instaLink'] ,$file);
    } else {
        $file = str_replace("#instaLinkDisplay#", 'none' ,$file);
    }

    if($data['twitterLink'] != ""){
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
    }
    
    $file = str_replace("#companyName",$data['strCompanyName'],$file);
    $address = "";
    if($data['Address1'] != ""){
        $address .= $data['Address1'].",";
    }
    if($data['address2'] != ""){
        $address .= $data['address2'].",";
    }
    if($data['strCity'] != ""){
        $address .= $data['strCity'].",";
    }
    if($data['strState'] != ""){
        $address .= $data['strState'].",";
    }
    if($data['strCountry'] != ""){
        $address .= $data['strCountry'].",";
    }
    $address = rtrim(",",trim($address));
    
    if($data['supportEmail'] != ""){
        $file = str_replace("#supportEmail",$data['supportEmail'] ,$file);
    } else {
        $file = str_replace("#supportEmail","support@masolutions.co.uk" ,$file);
    }
    $file = str_replace("#companyAddress",$address,$file);
    echo $file;
 ?>
