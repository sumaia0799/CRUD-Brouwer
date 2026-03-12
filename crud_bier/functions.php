<?php
// auteur: Sumaia Hashimi
// functie: algemene functies tbv hergebruik

include_once "config.php";

function connectDb(){
    $servername = SERVERNAME;
    $username = USERNAME;
    $password = PASSWORD;
    $dbname = DATABASE;
   
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $conn;
    } 
    catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

function crudMain(){

    $txt = "
    <h1>Crud Bier</h1>
    <nav>
        <a href='insert.php'>Toevoegen nieuw bier</a>
    </nav><br>";
    echo $txt;

    $result = getData(CRUD_TABLE);

    printCrudTabel($result);
}

function getData($table){
    $conn = connectDb();

    $sql = "SELECT * FROM $table";
    $query = $conn->prepare($sql);
    $query->execute();
    $result = $query->fetchAll();

    return $result;
}

function getRecord($id){
    $conn = connectDb();

    $sql = "SELECT * FROM " . CRUD_TABLE . " WHERE biercode = :id";
    $query = $conn->prepare($sql);
    $query->execute([':id' => $id]);
    $result = $query->fetch();

    return $result;
}

function getBrouwers(){
    $conn = connectDb();

    $sql = "SELECT * FROM brouwer";
    $query = $conn->prepare($sql);
    $query->execute();
    $result = $query->fetchAll();

    return $result;
}

function printCrudTabel($result){
    if(empty($result)){
        echo "Geen gegevens gevonden";
        return;
    }

    $table = "<table>";

    $headers = array_keys($result[0]);
    $table .= "<tr>";
    foreach($headers as $header){
        $table .= "<th>" . $header . "</th>";   
    }

    $table .= "<th colspan='2'>Actie</th>";
    $table .= "</tr>";

    foreach ($result as $row) {
        $table .= "<tr>";

        foreach ($row as $cell) {
            $table .= "<td>" . $cell . "</td>";  
        }
        
        $table .= "<td>
            <form method='post' action='update.php?id=$row[biercode]'>       
                <button>Wzg</button>	 
            </form></td>";

        $table .= "<td>
            <form method='post' action='delete.php?id=$row[biercode]'>       
                <button>Verwijder</button>	 
            </form></td>";

        $table .= "</tr>";
    }

    $table .= "</table>";

    echo $table;
}

function updateRecord($row){
    $conn = connectDb();

    $sql = "UPDATE " . CRUD_TABLE . "
        SET 
            naam = :naam, 
            soort = :soort, 
            stijl = :stijl,
            alcohol = :alcohol,
            brouwcode = :brouwcode
        WHERE biercode = :biercode
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':naam' => $row['naam'],
        ':soort' => $row['soort'],
        ':stijl' => $row['stijl'],
        ':alcohol' => $row['alcohol'],
        ':brouwcode' => $row['brouwcode'],
        ':biercode' => $row['biercode']
    ]);

    $retVal = ($stmt->rowCount() == 1) ? true : false;
    return $retVal;
}

function insertRecord($post){
    $conn = connectDb();

    $nieuweBiercode = getNewBiercode();

    $sql = "
        INSERT INTO " . CRUD_TABLE . " (biercode, naam, soort, stijl, alcohol, brouwcode)
        VALUES (:biercode, :naam, :soort, :stijl, :alcohol, :brouwcode) 
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':biercode' => $nieuweBiercode,
        ':naam' => $post['naam'],
        ':soort' => $post['soort'],
        ':stijl' => $post['stijl'],
        ':alcohol' => $post['alcohol'],
        ':brouwcode' => $post['brouwcode']
    ]);

    $retVal = ($stmt->rowCount() == 1) ? true : false;
    return $retVal;  
}

function deleteRecord($id){
    $conn = connectDb();
    
    $sql = "
    DELETE FROM " . CRUD_TABLE . 
    " WHERE biercode = :id";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    $retVal = ($stmt->rowCount() == 1) ? true : false;
    return $retVal;
}
function getNewBiercode(){
    $conn = connectDb();

    $sql = "SELECT MAX(biercode) AS maxcode FROM bier";
    $query = $conn->prepare($sql);
    $query->execute();
    $result = $query->fetch();

    return $result['maxcode'] + 1;
}
?>