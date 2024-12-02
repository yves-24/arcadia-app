<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>php et mysql</title>
</head>
<body>
   $serveur = "localhost";
   $login = "root";
   $pass = "root";

   $connexion = new PDO("mysql:host=$serveur;dbname=test", $login, $pass);
      ?>
</body>
</html>