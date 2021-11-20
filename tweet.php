    #!/usr/bin/php
<?php
    $username = urlencode("eposta");
    $password = urlencode("şifre");
     
    $tw = "/home/pi/domoticz/scripts/twitterwetter.txt";
    $wd = fopen ($tw, "r");
    $twitterwetter = fread ($wd, filesize($tw));
    fclose ($wd);
     
    $tweet = "$twitterwetter";
     
    $laenge = strlen($tweet);
    echo $laenge;
     
    #EXTRA OPTIONS
    $uagent = "Mozilla/5.0"; #user agent (fake a browser)
    $sleeptime = 0; #add pause between requests
     
    $host = fopen("cookie.txt", "w"); #create a temp. cookie file
    $ch = curl_init();
    curl_setopt_array($ch, array(//CURLOPT_MUTE => TRUE, //-s
    CURLOPT_COOKIE => "cookie.txt", //-b
    CURLOPT_COOKIEJAR => "cookie.txt", //-c
    CURLOPT_FOLLOWLOCATION => TRUE, //-L
    CURLOPT_SSLVERSION => 3, //--sslv3
    CURLOPT_USERAGENT => $uagent, //-A
    CURLOPT_RETURNTRANSFER => TRUE
    ));
     
    #GRAB LOGIN TOKENS
    echo "[+] Fetching twitter.com...\n";
    sleep($sleeptime);
     
    curl_setopt_array($ch, array(CURLOPT_URL => "https://mobile.twitter.com/session/new",
    ));
    $initpage = curl_exec($ch);
     
    preg_match("/<input.*authenticity_token.*value=\"(.*?)\".* \/>/i", $initpage, $matches);
    $token = $matches[1];
     
     
    #LOGIN
    echo "[+] Submitting the login form...\n";
    sleep($sleeptime);
     
    curl_setopt_array($ch, array(CURLOPT_URL => "https://mobile.twitter.com/session",
    CURLOPT_POSTFIELDS => "authenticity_token=$token&username=$username&password=$password",
    CURLOPT_POST => TRUE
    ));
    $loginpage = curl_exec($ch);
     
     
    #GRAB COMPOSE TWEET TOKENS
    echo "[+] Getting compose tweet page...\n";
    sleep($sleeptime);
     
    curl_setopt_array($ch, array(CURLOPT_URL => "https://mobile.twitter.com/compose/tweet",
    CURLOPT_POSTFIELDS => "",
    CURLOPT_POST => FALSE
    ));
    $composepage = curl_exec($ch);
     
    preg_match("/<input.*authenticity_token.*value=\"(.*?)\".*\/>/i", $composepage, $matches);
    $tweettoken = $matches[1];
     
     
    #TWEET
    echo "[+] Posting a new tweet: $tweet...\n";
    sleep($sleeptime);
     
    curl_setopt_array($ch, array(CURLOPT_URL => "https://mobile.twitter.com/",
    CURLOPT_POSTFIELDS => "authenticity_token=$tweettoken&tweet[text]=$tweet&tweet[display_coordinates]=false",
    CURLOPT_POST => TRUE
    ));
    $update = curl_exec($ch);
     
     
    #GRAB LOGOUT TOKENS
    curl_setopt_array($ch, array(CURLOPT_URL => "https://mobile.twitter.com/account",
    CURLOPT_POSTFIELDS => "",
    CURLOPT_POST => FALSE
    ));
    $logoutpage = curl_exec($ch);
     
    preg_match("/<input.*authenticity_token.*value=\"(.*?)\".* \/>/i", $logoutpage, $matches);
    $logouttoken = $matches[1];
     
    #LOGOUT
    echo "[+] Logging out...\n";
    sleep($sleeptime);
     
    curl_setopt_array($ch, array(CURLOPT_URL => "https://mobile.twitter.com/session/destroy",
    CURLOPT_POSTFIELDS => "authenticity_token=$logouttoken",
    CURLOPT_POST => TRUE
    ));
    $logout = curl_exec($ch);
     
     
    unlink("cookie.txt");
    curl_close($ch);
    exit(0);
    ?>