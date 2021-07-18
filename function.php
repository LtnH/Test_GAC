<?php
function Send_db($row, $connect) {  //fonction d'envoie sur la BD
    $row[3] = str_replace("/", "-", $row[3]); //formatage des dates
    $row[3] = date("Y-m-d", strtotime($row[3]));
    if (Erreur_Tab($row) == true) {  //destion d'erreur dans le fichier
        if (strpos($row[7], 'appel') !== FALSE) {  // stokage des donné en fonction de leur type
            $sql = "INSERT into table_appel (date,hour,type,dur_ap) 
values ('" . $row[3] . "','" . $row[4] . "','appel','" . $row[5] . "')";
            $result = mysqli_query($connect, $sql);
            return $result;
        }
        else if (strpos($row[7], 'sms') !== FALSE) {
            $sql = "INSERT into table_appel (date,hour,type)
 values ('" . $row[3] . "','" . $row[4] . "','sms')";
            $result = mysqli_query($connect, $sql);
            return $result;
        }
        else if (strpos($row[7], 'connexion') !== FALSE) {
            $sql = "INSERT into table_appel (date,hour,type,tot_data)
 values ('" . $row[3] . "','" . $row[4] . "','data','" . $row[6] . "')";
            $result = mysqli_query($connect, $sql);
            return $result;
        }
    }
}

function Erreur_Tab($row) //test de la validité des dates
{
    try {
        if (strtotime($row[3]) == FALSE)
            throw new Exception;
        else if (strtotime($row[4]) == false)
            throw new Exception;
        else if ((strpos($row[7], 'appel') !== FALSE) && (strtotime($row[5]) == FALSE))
            throw new Exception;
        else if ((strpos($row[7], 'connexion') !== FALSE) && (strpos($row[6],':') !== FALSE))
            throw new Exception;
    } catch (Exception $e) {
        return false;
    }
    return true;
}

function Total_heure($connect) { //calcul des heure total
    $result = mysqli_query($connect,"SELECT SUM(TIME_TO_SEC(dur_ap)) FROM table_appel 
WHERE date >= '2012-02-15' AND type = 'appel'"); //récupéation du total en seconde
    $total_sec = mysqli_fetch_array($result);
    $total_sec = $total_sec[0];
    //convertion manuel en H:M:S car la fonction SEC_TO_TIME de sql ne le permettais pas
    $h = intval(abs($total_sec / 3600));
    $total_sec = $total_sec - ($h*3600);
    $m = intval(abs($total_sec / 60));
    $total_sec = $total_sec - ($m * 60);
    if ($h <10)
        $h = "0".$h;
    if ($m < 10)
        $m = "0".$m;
    if ($total_sec < 10)
        $total_sec = "0".$total_sec;
    $total_h = $h.":".$m.":".$total_sec;
    return ($total_h);
}

function Top_data($connet) { //récupération des meilleurs data
    $result = mysqli_query($connet,"SELECT tot_data FROM `table_appel` 
WHERE (hour < '8:00' OR hour > '18:00') AND type = 'data' ORDER BY tot_data DESC");
    while ($total_data[] = mysqli_fetch_array($result)) {}
    for ($i = 0; $i != 10; $i++) //on ne récupère que les dix première
        $top_data[] = $total_data[$i][0];
    return $top_data;
}

function Total_sms($connect) { //récupération du total des sms
    $result = mysqli_query($connect,"SELECT COUNT(type) FROM table_appel WHERE type = 'sms'");
    $sms = mysqli_fetch_array($result);
    $total_sms = $sms[0];
    return ($total_sms);
}