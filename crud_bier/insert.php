<?php
// functie: formulier en database insert bier
// auteur: Sumaia Hashimi

echo "<h1>Insert Bier</h1>";

require_once('functions.php');

$brouwers = getBrouwers();
 
if(isset($_POST) && isset($_POST['btn_ins'])){

    if(insertRecord($_POST) == true){
        echo "<script>alert('Bier is toegevoegd')</script>";
        echo "<script> location.replace('index.php'); </script>";
    } else {
        echo '<script>alert("Bier is NIET toegevoegd")</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Bier</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="post">

        <label for="naam">Naam:</label>
        <input type="text" id="naam" name="naam" required><br>

        <label for="soort">Soort:</label>
        <input type="text" id="soort" name="soort" required><br>

        <label for="stijl">Stijl:</label>
        <input type="text" id="stijl" name="stijl" required><br>

        <label for="alcohol">Alcohol:</label>
        <input type="text" id="alcohol" name="alcohol" required><br>

        <label for="brouwcode">Brouwnaam:</label>
        <select name="brouwcode" id="brouwcode" required>
            <option value="">Kies een brouwer</option>
            <?php foreach($brouwers as $brouwer){ ?>
                <option value="<?php echo $brouwer['brouwcode']; ?>">
                    <?php echo $brouwer['naam']; ?>
                </option>
            <?php } ?>
        </select><br>

        <input type="submit" name="btn_ins" value="Insert">
    </form>
    
    <br><br>
    <a href='index.php'>Home</a>
</body>
</html>