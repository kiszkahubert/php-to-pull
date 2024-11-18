<?php
function walidacja(){
    $args = [
        'nazwisko' => [
            'filter' => FILTER_VALIDATE_REGEXP,
            'options' => ['regexp' => '/^[A-Z]{1}[a-ząęłńśćźżó-]{1,25}$/']
        ],
        'państwo' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'jezyki' => [
            'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'flags' => FILTER_REQUIRE_ARRAY],
        'wiek' => [
            'filter' => FILTER_VALIDATE_INT,
            'options' => ['min_range' => 13, 'max_range' => 120]
        ],
        'e-mail' => FILTER_VALIDATE_EMAIL,
        'platnosc' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
    ];
    $dane = filter_input_array(INPUT_POST, $args);
    $errors = "";
    foreach ($dane as $key => $val) {
        if ($val === false or $val === null) {
            $errors .= $key . " ";
        }
    }
    if ($errors === ""){
        dopliku("dane.txt", $dane);
    } else{
        echo "<br/>Niepoprawne dane: " . $errors;
    }
}
function dodajdoBD($bd){
    $args = [
        'nazwisko' => [
            'filter' => FILTER_VALIDATE_REGEXP,
            'options' => ['regexp' => '/^[A-Z]{1}[a-ząęłńśćźżó-]{1,25}$/']
        ],
        'państwo' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'jezyki' => [
            'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'flags' => FILTER_REQUIRE_ARRAY],
        'wiek' => [
            'filter' => FILTER_VALIDATE_INT,
            'options' => ['min_range' => 13, 'max_range' => 120]
        ],
        'e-mail' => FILTER_VALIDATE_EMAIL,
        'platnosc' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
    ];
    $dane = filter_input_array(INPUT_POST, $args);
    $errors = "";
    foreach ($dane as $key => $val) {
        if ($val === false or $val === null) {
            $errors .= $key . " ";
        }
    }
    if ($errors === "") {
        $nazwisko = $dane['nazwisko'];
        $wiek = $dane['wiek'];
        $panstwo = $dane['państwo'];
        $email = $dane['e-mail'];
        $zamowienie = implode(',', $dane['jezyki']);
        $platnosc = $dane['platnosc'];
        $sql = "INSERT INTO klienci (Nazwisko, Wiek, Panstwo, Email, Zamowienie, Platnosc) VALUES ('$nazwisko', '$wiek', '$panstwo', '$email', '$zamowienie', '$platnosc')";
        if ($bd->insert($sql)) {
            echo "<p>Pomyślnie dodano zamówienie do bazy.</p>";
        } else {
            echo "<p>Błąd podczas dodawania zamówienia do bazy.</p>";
        }
    } else{
        echo "<br/>Niepoprawne dane: " . $errors;
    }
}
function pokaz_zamowienie($tut) {
    $file = "dane.txt";
    if(file_exists($file)) {
        $zawartosc = file($file);
        foreach ($zawartosc as $linia) {
            if (strpos($linia, $tut) !== false) {
                echo "<br>" . htmlspecialchars($linia);
            }
        }
    } else{
        echo "<br><b>Plik z danymi nie istnieje.</b>";
    }
}
function wyczysc(){
    $plik = "dane.txt";
    if (file_exists($plik)) {
        $file = fopen($plik, "w");
        if ($file) {
            fclose($file);
        } else {
            echo "<br><b>Nie udało się otworzyć pliku do czyszczenia.</b>";
        }
    } else {
        echo "<br><b>Plik z danymi nie istnieje.</b>";
    }
}
function pokaz_statystyki(){
    $file = "dane.txt";
    if (!file_exists($file)) {
        echo "<br><b>Plik z danymi nie istnieje.</b>";
        return;
    }
    $zawartosc = file($file);
    $liczba_wszystkich = 0;
    $liczba_ponizej_18 = 0;
    $liczba_powyzej_49 = 0;
    foreach ($zawartosc as $linia) {
        $linia = trim($linia);
        $dane = explode(" ", $linia);
        if (count($dane) >= 2) {
            foreach ($dane as $i => $wartosc) {
                if (is_numeric($wartosc)) {
                    $wiek = intval($wartosc);
                    $liczba_wszystkich++;
                    if ($wiek < 18) {
                        $liczba_ponizej_18++;
                    }
                    if ($wiek >= 50) {
                        $liczba_powyzej_49++;
                    }
                    break;
                }
            }
        }
    }
    echo "<p>Liczba wszystkich zamówień: " . $liczba_wszystkich . "</p>";
    echo "<p>Liczba zamówień od osób w wieku < 18 lat: " . $liczba_ponizej_18 . "</p>";
    echo "<p>Liczba zamówień od osób w wieku >=50 lat: " . $liczba_powyzej_49 . "</p>";
}
function statystyki($bd){
    $sql_total = "SELECT COUNT(*) total FROM klienci";
    $sql_below_18 = "SELECT COUNT(*) AS below_18 FROM klienci WHERE Wiek < 18";
    $sql_above_50 = "SELECT COUNT(*) AS above_50 FROM klienci WHERE Wiek >= 50";
    $res_total = $bd->getMySqli()->query($sql_total);
    $res_below_18 = $bd->getMySqli()->query($sql_below_18);
    $res_above_50 = $bd->getMySqli()->query($sql_above_50);
    if ($res_total && $res_below_18 && $res_above_50) {
        $total = $res_total->fetch_assoc()['total'];
        $below_18 = $res_below_18->fetch_assoc()['below_18'];
        $above_50 = $res_above_50->fetch_assoc()['above_50'];
        echo "<p>Liczba wszystkich użytkowników: $total</p>";
        echo "<p>Liczba użytkowników poniżej 18 lat: $below_18</p>";
        echo "<p>Liczba użytkowników w wieku 50 lat i powyżej: $above_50</p>";
    } else{
        echo "<p>Błąd przy pobieraniu danych</p>";
    }
}
