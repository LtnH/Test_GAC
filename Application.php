<head>
    <meta charset="ANSI">
    <title>Résultat du traitement</title>
</head>
<?php
?>
<?php
include ('connection.php');
include ('function.php');
//var_dump($_FILES);
if (isset($_FILES['testee']) AND $_FILES['testee']['error'] == 0) {
    $infofile = pathinfo($_FILES['testee']['name']);
    $ext_file = $infofile['extension'];
    $ext_alow = array('csv');
    if (in_array($ext_file, $ext_alow)) {
        echo "\n" . 'le fichier est valide';
        $filename = $_FILES['testee']['tmp_name'];
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 1000, ";")) !== FALSE)
            {
                $info = Send_db($row, $connect);
            }
        }
        fclose($handle);
        $total_app = Total_heure($connect);
        $top_data = top_data($connect);
        $total_sms = Total_sms($connect);
        echo "<pre>";
        echo "Le total d'heure d'appel après le 15/02/2012 est ";
        print_r($total_app);
        echo "\n\nLe top 10 des data facturés en dehors de 8h00-18h00 :\n";
        for ($i = 1; $i != 11; $i++) {
            echo "\n".$i.". ";
            print_r($top_data[$i-1]);
        }
        echo "\n\nQuantité total de sms envoyés : ";
        print_r($total_sms);
    }
    else
        echo "extention invalide!";
}
else
    echo "c'est un echec try again !";
?>
