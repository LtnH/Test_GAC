<head>
    <meta charset="UTF-8">
    <title>Formulaire d'envoi</title>
</head>
<body>
<!-- formulaire d'envoie du fichier a l'applicatiom -->
<form method="post" action="/Application.php" enctype="multipart/form-data">
    <h2>Formulaire d'envoi</h2>
    <h5>incérez si desous le fichier à traité. </h5>
    <p>
        <input type="file" name="file"  accept=".csv" /><br />
        <input type="submit" VALUE="Valider" />
    </p>
</form>
</body>