<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="z3.css">
    <title>Zadatak 3</title>
</head>
<body>
    <?php
        $xml = simplexml_load_file("LV2.xml");
        $content = "
        <div class='container'>";
        //izlistavanje podataka
        foreach ($xml->record as $osoba) {
            $id = $osoba->id;
            $ime = $osoba->ime;
            $prezime = $osoba->prezime;
            $email = $osoba->email;
            $spol = $osoba->spol;
            $slika = $osoba->slika;
            $zivotopis = $osoba->zivotopis;

            $content .=
                    "<div class='osoba'>
                        <img class='slika' src=$slika alt='Slika osobe'>
                        <h1 class='ime_prezime'>$ime $prezime</h1>
                        <p class='email'>$email</p>
                        <p class='zivotopis'>$zivotopis</p>
                     </div>";
        }

        echo $content;
    ?>
</body>
</html>