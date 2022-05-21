<?php

function read_csv($file)
{
  $row      = 0;
  $csvArray = array();
  if( ( $handle = fopen($file, "r") ) !== FALSE ) {
    while( ( $data = fgetcsv($handle, 0, ";") ) !== FALSE ) {
      $num = count($data);
      for( $c = 0; $c < $num; $c++ ) {
        $csvArray[$row][] = $data[$c];
      }
      $row++;
    }
  }
  if( !empty( $csvArray ) ) {
    return array_splice($csvArray, 1);
  } else {
    return false;
  }
}

function rrmdir($src) {
    $dir = opendir($src);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            $full = $src . '/' . $file;
            if ( is_dir($full) ) {
                rrmdir($full);
            }
            else {
                unlink($full);
            }
        }
    }
    closedir($dir);
    rmdir($src);
}


function assign_number($string){
    if ($string == "ACTU") {
        return 0;
    }
    elseif ($string == "HPDA") {
        return 1;
    }
    elseif ($string == "BI") {
        return 2;
    }
    elseif ($string == "CS") {
        return 3;
    }
    elseif ($string == "DS") {
        return 4;
    }
    elseif ($string == "FT") {
        return 5;
    }
    elseif ($string == "IAC") {
        return 6;
    }
    elseif ($string == "IAP") {
        return 7;
    }
    elseif ($string == "ICC") {
        return 8;
    }
    elseif ($string == "INEM") {
        return 9;
    }
    elseif ($string == "MMF") {
        return 10;
    }
    elseif ($string == "VISUA") {
        return 11;
    }
    else{
        return 0;
    }
}


////////////////    Creation "Base de données"   ////////////////

$csvData1 = read_csv("../../backend/db/choixEtudiantsParcours1.csv");
$csvData2 = read_csv("../../backend/db/choixEtudiantsParcours2.csv");
$csvData3 = read_csv("../../backend/db/choixEtudiantsParcours3.csv");


if (is_dir("../../backend/db/placesFinales")){
    rrmdir("../../backend/db/placesFinales");
    mkdir("../../backend/db/placesFinales", 0777, true);
}
else {
    mkdir("../../backend/db/placesFinales", 0777, true);
}


$ACTU = fopen("../../backend/db//placesFinales/ACTU.csv", "w+");
$HPDA = fopen("../../backend/db//placesFinales/HPDA.csv", "w+");
$BI = fopen("../../backend/db//placesFinales/BI.csv", "w+");
$CS = fopen("../../backend/db//placesFinales/CS.csv", "w+");
$DS = fopen("../../backend/db//placesFinales/DS.csv", "w+");
$FT = fopen("../../backend/db//placesFinales/FT.csv", "w+");
$IAC = fopen("../../backend/db//placesFinales/IAC.csv", "w+");
$IAP = fopen("../../backend/db//placesFinales/IAP.csv", "w+");
$ICC = fopen("../../backend/db//placesFinales/ICC.csv", "w+");
$INEM = fopen("../../backend/db//placesFinales/INEM.csv", "w+");
$MMF = fopen("../../backend/db//placesFinales/MMF.csv", "w+");
$VISUA = fopen("../../backend/db//placesFinales/VISUA.csv", "w+");


$places = read_csv("../../backend/db/nbPLacesParcours.csv");


///////////////   Attribuer les places aux parcours 1   //////////////////////////////

foreach ($csvData1 as $key => $row) {
    $sorted = false;
    $choice = 5;

    while(!$sorted){
        $tmp = assign_number(str_replace(' ','',$row[$choice]));
        if ($places[$tmp][1] > 0){
            fputcsv(${str_replace(' ','',$row[$choice])}, $row);
            $places[$tmp][1]--;
            $sorted = true;
        }
        else{
            $choice++;
            if ($choice == 13){
                $sorted = true;
            }
        }
    }
}


///////////////   Attribuer les places aux parcours 2   //////////////////////////////

foreach ($csvData2 as $key => $row) {
    $sorted = false;
    $choice = 5;

    while(!$sorted){
        $tmp = assign_number(str_replace(' ','',$row[$choice]));
        if ($places[$tmp][3] > 0){
            fputcsv(${str_replace(' ','',$row[$choice])}, $row);
            $places[$tmp][3]--;
            $sorted = true;
        }
        else{
            $choice++;
            if ($choice == 7){
                $sorted = true;
            }
        }
    }
}

///////////////   Attribuer les places aux parcours 3   //////////////////////////////

foreach ($csvData3 as $key => $row) {
    $sorted = false;
    $choice = 5;

    while(!$sorted){
        $tmp = assign_number(str_replace(' ','',$row[$choice]));
        if ($places[$tmp][2] > 0){
            fputcsv(${str_replace(' ','',$row[$choice])}, $row);
            $places[$tmp][2]--;
            $sorted = true;
        }
        else{
            $choice++;
            if ($choice == 11){
                $sorted = true;
            }
        }
    }
}



//header("Location: http://localhost:8080/");


?>