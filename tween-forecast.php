    #!/usr/bin/php
    <?php
     
    //---get forecast for the next hours-------------------------
    //---see the wunderground webpage for other weather information download
    //---uselang:xx for language of your country
    //---change hourly to other values for other weather information
    //---change country and city
    $json_string = file_get_contents("http://api.wunderground.com/api/xxxxxxxxxxxxxxx/hourly/lang:DL/q/germany/Solingen.json");
    $parsed_json = json_decode($json_string, true);
     
     
    //---for debugging and development of own forecast combination
    //---this file will show the complete array of a weather forecast
    /**
    $wetterdatei = "/home/pi/domoticz/scripts/wetter_json.txt";
    $wd = fopen ($wetterdatei, "w+");
    fwrite ($wd, print_R($parsed_json, TRUE));
    fclose ($wd);
    */
     
    //--------- Weather in xh ------------------------------------
    //---look at the wetter_json.txt file about the structure
    $parsed_json = $parsed_json['hourly_forecast'][0]['FCTTIME'];
    //print_r($parsed_json);
     
    $stunde = $parsed_json['hour_padded'];
    $min = $parsed_json['min'];
    $tag = $parsed_json['mday_padded'];
    $monat = $parsed_json['mon_abbrev'];
    $jahr = $parsed_json['year'];
    echo $stunde.":".$min." ".$tag.".".$monat.".".$jahr."\n";
     
    $parsed_json = json_decode($json_string, true);
    $parsed_json = $parsed_json['hourly_forecast'][0]['temp'];
    $temp = $parsed_json['metric'];
    echo "Temp ".$temp."C"."\n";
     
    $parsed_json = json_decode($json_string, true);
    $parsed_json = $parsed_json['hourly_forecast'][0]['wspd'];
    $windspeed = $parsed_json['metric'];
    echo "Wind ".$windspeed."km/h"."\n";
     
    $parsed_json = json_decode($json_string, true);
    $parsed_json = $parsed_json['hourly_forecast'][0]['wdir'];
    $winddir = $parsed_json['dir'];
    echo "Windricht. ".$winddir."\n";
     
    $parsed_json = json_decode($json_string, true);
    $parsed_json = $parsed_json['hourly_forecast'][0];
    $humy = $parsed_json['humidity'];
    echo "Luftf. ".$humy."%"."\n";
     
    $parsed_json = json_decode($json_string, true);
    $parsed_json = $parsed_json['hourly_forecast'][0]['qpf'];
    $regen = $parsed_json['metric'];
    echo "Regen ".$regen."mm"."\n";
     
    $parsed_json = json_decode($json_string, true);
    $parsed_json = $parsed_json['hourly_forecast'][0]['snow'];
    $snow = $parsed_json['metric'];
    echo "Schnee ".$snow."mm"."\n";
     
    $parsed_json = json_decode($json_string, true);
    $parsed_json = $parsed_json['hourly_forecast'][0];
    $zustand = $parsed_json['condition'];
    echo $zustand."\n";
     
    $parsed_json = json_decode($json_string, true);
    $parsed_json = $parsed_json['hourly_forecast'][0];
    $regenwar = $parsed_json['pop'];
    echo "Regenrisiko ".$regenwar."%"."\n";
     
    $parsed_json = json_decode($json_string, true);
    $parsed_json = $parsed_json['hourly_forecast'][0]['mslp'];
    $druck = $parsed_json['metric'];
    echo "Luftdruck ".$druck."hPa"."\n";
     
    #$twitterwetter = "fuer ".$stunde.":".$min." ".$tag.".".$monat.".".$jahr."\n".$zustand."\n"."Temp ".$temp."C"."\n"."Luftf. ".$humy."%"."\n"."Regenrisiko ".$regenwar."% "."\n"."Regen ".$regen."mm"."\n"."Schnee ".$snow."mm"."\n"."Wind ".$windspeed."km/h"." aus ".$winddir." "."\n"."Luftdruck ".$druck."hPa";
    include('tweet.php');
    
    $twitterwetter = "fuer ".$stunde.":".$min." ".$tag.".".$monat.".".$jahr." ".$zustand." "."Temp ".$temp."C"." Luftf. ".$humy."%"." "."Regenrisiko ".$regenwar."% "."       "."     Regen ".$regen."mm"." "."Schnee ".$snow."mm"." "."Wind ".$windspeed."km/h"." aus ".$winddir." "."Luftdruck ".$druck."hPa";include('tweet.php'); 
    ?>