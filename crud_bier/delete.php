<?php
// auteur: Sumaia Hashimi
// functie: verwijder een bier op basis van de id

include 'functions.php';

if(isset($_GET['id'])){

    if(deleteRecord($_GET['id']) == true){
        echo '<script>alert("Biercode: ' . $_GET['id'] . ' is verwijderd")</script>';
        echo "<script> location.replace('index.php'); </script>";
    } else {
        echo '<script>alert("Bier is NIET verwijderd")</script>';
    }
}
?>

