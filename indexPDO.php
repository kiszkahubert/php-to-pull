<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body>
<h2>Formularz zamówienia</h2>
<form action="indexPDO.php" method="post">
    <label for="nazwisko">Nazwisko: </label><input type="text" id="nazwisko" name="nazwisko"><br>
    <label for="wiek">Wiek: </label><input id="wiek" type="number" name="wiek"><br>
    <label for="państwo">Państwo: </label>
    <select id="państwo" size="1" name="państwo">
        <option value="Polska">Polska</option>
        <option value="Stany Zjednoczone">Stany Zjednoczone</option>
        <option value="Wielka Brytania">Wielka Brytania</option>
    </select><br>
    <label for="email">Adres e-mail: </label><input id="email" type="email" name="e-mail"><br>
    <h3>Zamawiam tutorial z języka:</h3>
    <?php
    use klasy\BazaPDO;
    $jezyki = ["C", "CPP", "Java", "C#", "HTML", "CSS", "XML", "PHP", "JavaScript"];
    foreach ($jezyki as $jezyk) {
        echo "<label><input type='checkbox' name='jezyki[]' value='$jezyk'>$jezyk </label>";
    }
    ?>
    <h3>Sposób zapłaty:</h3>
    <label><input type="radio" name="platnosc" value="eurocard" checked> eurocard</label>
    <label><input type="radio" name="platnosc" value="visa"> visa</label>
    <label><input type="radio" name="platnosc" value="przelew_bankowy"> przelew bankowy</label>
    <br><br>
    <input type="submit" value="Wyczyść" name="submit">
    <input type="submit" value="Zapisz" name="submit">
    <input type="submit" value="Pokaż" name="submit">
    <input type="submit" value="PHP" name="submit">
    <input type="submit" value="CPP" name="submit">
    <input type="submit" value="Java" name="submit">
    <input type="submit" value="Statystyki" name="submit">
</form>
<?php
include_once "funkcjePDO.php";
include_once "klasy/BazaPDO.php";
$bd = new BazaPDO("localhost", "root", "", "klienci");
if (filter_input(INPUT_POST, "submit")) {
    $akcja = filter_input(INPUT_POST, "submit");
    switch ($akcja) {
        case "Zapisz":
            dodajdoBD($bd);
            break;
        case "Pokaż":
            echo $bd->select("select Nazwisko, Zamowienie 
            from klienci", ["Nazwisko", "Zamowienie"]);
            break;
        case "Wyczyść":
            echo $bd->delete("DELETE FROM klienci");
            break;
        case "PHP":
            echo $bd->select("SELECT Nazwisko, Zamowienie FROM klienci WHERE Zamowienie REGEXP 'PHP'", ["Nazwisko", "Zamowienie"]);
            break;
        case "CPP":
            echo $bd->select("SELECT Nazwisko, Zamowienie FROM klienci WHERE Zamowienie REGEXP 'CPP'", ["Nazwisko", "Zamowienie"]);
            break;
        case "Java":
            echo $bd->select("SELECT Nazwisko, Zamowienie FROM klienci WHERE Zamowienie REGEXP 'Java'", ["Nazwisko", "Zamowienie"]);
            break;
        case "Statystyki":
            statystyki($bd);
            break;
    }
}
?>
</body>
</html>
