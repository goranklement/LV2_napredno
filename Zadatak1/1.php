<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup</title>
</head>

<body>
    <?php
    $db_name = "dokumenti";

    $dir = "backup/$db_name";

    //ako direktorij ne postoji, stvori ga
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777, true)) {
            die("<p>Ne mozemo stvoriti direktorij $dir.</p></body></html>");
        }
    }
    $time = time();

    //spajanje na bazu podataka
    $dbc = @mysqli_connect("localhost", "root", "", $db_name) or die("<p>Ne mozemo se spojiti na bazu podataka $db_name.</p></body></html>");

    //izvodenje upita na bazi podataka 
    $r = mysqli_query($dbc, 'SHOW TABLES');

    $getColumnName = function ($columnName) {
        return $columnName->name;
    };

    //radi se backup ako postoji barem jedna tablica
    if (mysqli_num_rows($r) > 0) {
        echo "<p>Backup za bazu podataka '$db_name'.</p>";

        //dohvacanje ime svake tablice
        while (list($table) = mysqli_fetch_array($r, MYSQLI_NUM)) {

            $q = "SELECT * FROM $table";
            $columns = array_map($getColumnName, $dbc->query($q)->fetch_fields());
            $r2 = mysqli_query($dbc, $q);

            if (mysqli_num_rows($r2) > 0) {
                //ime datoteke u obliku naziva tablice i vremena
                //otvaranje tekstualne datoteke
                if ($fp = fopen("$dir/{$table}_{$time}.txt", "w9")) {

                    while ($rows = mysqli_fetch_array($r2, MYSQLI_NUM)) {
                        $text = "INSERT INTO $table (";

                        for ($index = 0; $index < count($columns); $index++) {
                            //ako je sljedeci broj indexa razlicit od velicine polja $columns
                            //onda se atributi odvajaju zarezima
                            if ($index + 1 != count($columns)) {
                                $text .= "$columns[$index], ";
                            } else {
                                //inace se zadnji element dodaje bez zareza pored sebe
                                $text .= "$columns[$index]";
                            }
                        }

                        //na kraju se zatvara zagrada od naredbe INSERT INTO i otvara se zagrada od VALUES
                        $text .= ") VALUES (";

                        for ($index = 0; $index < count($rows); $index++) {
                            //ako je sljedeci broj indexa razlicit od velicine polja $rows
                            //onda se vrijednosti odvajaju zarezima
                            if ($index + 1 != count($rows)) {
                                $text .= "'$rows[$index]', ";
                            } else {
                                //inace se zadnji element dodaje bez zareza pored sebe
                                $text .= "'$rows[$index]'";
                            }
                        }

                        //na kraju se zatvara zagrada od VALUES, stavlja se tocka-zarez i prelazi u novi red
                        $text .= ");\n";

                        fwrite($fp, $text);
                    }
                    fclose($fp);

                    echo "<p>Tablica '$table' je pohranjena.</p>";

                    //sazimanje dobivene datoteke
                    //otvaranje datoteke
                    if ($fp = gzopen("$dir/{$table}_{$time}.sql.gz", 'w9')) {
                        //citanje sadrzaja datoteke u nizu
                        $content = file_get_contents("backup/dokumenti/{$table}_{$time}.txt");
                        //pisanje u datoteku
                        gzwrite($fp, $content);
                        //zatvaranje datoteke
                        gzclose($fp);

                        echo "<p>Tablica '$table' je sazeta.</p>";
                    } else {
                        echo "<p>Dogodila se pogreska tijekom sazimanja tablice '$table'.</p>";
                    }
                } else {
                    echo "<p>Datoteka $dir/{$table}_{$time}.txt se ne moze otvoriti.</p>";
                    break;
                }
            }
        }
    } else {
        echo "<p>Baza $db_name ne sadrzi nikakve tablice.</p>";
    }
    ?>
</body>

</html>