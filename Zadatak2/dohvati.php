<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dohvacanje kriptiranih dokumenata</title>
</head>
<body>
    <?php
        //pokretanje sesije
        session_start();
        //ako direktorij ne postoji, ispisi poruku o nedostatku datoteka za dekriptiranje
        if (!is_dir("uploads/")) {
            echo "<p>Nema prisutnih datoteka za dekriptiranje.</p>";
            die();
        }
        //provjera je li datoteka tekstualna po njezinoj ekstenziji txt
        $checkIfFileIsText = function ($file) {
            return (pathinfo($file, PATHINFO_EXTENSION) === 'txt');
        };
        //izlistavamo datoteke i direktorije unutar putanje
        $files = array_diff(scandir("uploads/"), array('..', '.'));
        //filtriranje ako su tekstualne datoteke
        $files = array_filter($files, $checkIfFileIsText);
        //ako postoje datoteke, izlistaj ih
        if (count($files) === 0) {
            echo "<p>Ne postoje datoteke za dekripciju</p>";
        } else {
            foreach ($files as $file) {
                //dohvacanje imena datoteke bez .txt nastavka
                $file_name_without_extension = substr($file, 0, strlen($file) - 4);
                //ispis datoteka
                echo "<p> <a href=\"preuzmi.php?file=$file_name_without_extension\">$file_name_without_extension</a></p>";
            }
        }
    ?>
</body>
</html>