<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploadanje datoteke</title>
</head>
<body>
    <?php
        //pokretanje sesije
        session_start();
        $file_name = $_FILES['file']['name'];
        //lokacija za spremanje
        $location = "uploads/" . $file_name;
        $file_extension = pathinfo($location, PATHINFO_EXTENSION);
        //koje ekstenzije su dozvoljene
        $extensions = array("jpg", "jpeg", "png", "pdf");
   
        if (!in_array(strtolower($file_extension), $extensions)) {
            echo "<p>Pogresan format datoteke: $file_extension.</p>";
            die();
        }
        //dohvacanje sadrzaja za eknripciju
        $data_content = file_get_contents($_FILES['file']['tmp_name']);
        $encryption_key = md5('encription key');
        $cipher = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($cipher);
        $options = 0;
        //inicijalizacijski vektor za enkripciju

        $encryption_iv = random_bytes($iv_length);
        //kriptiraj podatke sa openssl
        $data_encrypt = openssl_encrypt($data_content, $cipher, $encryption_key, $options, $encryption_iv);
        //spremi podatke
        $_SESSION['podaci'] = base64_encode($data_encrypt);
        $_SESSION['iv'] = $encryption_iv;
        //ako direktorij ne postoji, stvori ga
        if (!is_dir("uploads/")) {
            if (!mkdir("uploads/", 0777, true)) {
                die("<p>Nije moguce kreirati direktorij $dir.</p>");
            }
        }
        //file name on server
        $file_name_server = "uploads/$file_name.txt";
        //pisanje kriptiranog dokumenta na server
        file_put_contents($file_name_server, $_SESSION['podaci']);
        //ispis o uspjesnosti uplodanja datoteke
        echo "Datoteka je uploadana uspjesno!";
    ?>
    <br />
     <form action="dohvati.php" method="post">
      <input type="submit" name="submit" value="Fetch" />
    </form>
</body>
</html>
