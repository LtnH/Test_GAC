<head>
    <meta charset="ANSI">
    <title>Résultat du traitement</title>
</head>
<?php
include ('connection.php');
include ('function.php');
if (isset($_FILES['file']) AND $_FILES['file']['error'] == 0) { //vérrification que le fichier est bien upload
    $infofile = pathinfo($_FILES['file']['name']);
    $ext_file = $infofile['extension'];
    $ext_alow = array('csv');
    if (in_array($ext_file, $ext_alow)) { //vérrification que le fichier est du bon type
        echo "\n" . 'le fichier est valide';
        $filename = $_FILES['testee']['tmp_name'];
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 1000, ";")) !== FALSE) //upload du fichier sur la DB
            {
                $info = Send_db($row, $connect);
            }
        }
        fclose($handle);
        //obtention des réponses aux questions grâce a la DB
        $total_app = Total_heure($connect);
        $top_data = top_data($connect);
        $total_sms = Total_sms($connect);
        //affichage des réponses au test
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
    echo "echec de l'envoie du fichier!";
?>
