<?php 
include 'includes/cons.php';

?>
<!doctype html>
<html lang="en">
<?php echo getHead("Import", "", "")?>

<?php 
function importAirports(){
    
    $filename="C:\\Users\\tgdnasu3\\home\\ecPhp\\anisch_ticket\\docs\\t+airport-codes.csv";
    $count=0;
    $handle = fopen($filename, "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $cells = explode(";", $line);
            if ($cells[1]!=null){
                $selecsql="select id from all_airports where code='".trim($cells[1])."'";
                echo $selecsql;
                $id=getSingleValue($selecsql);
                echo " > id > ".$id." > ".strlen((string)$id);
                if (strlen((string)$id)==0){
                    $sql= "INSERT INTO `all_airports` (`id`, `city`, `country`, `code`) VALUES (NULL, '".trim($cells[0])."', '".trim($cells[3])."', '".trim($cells[1])."')";
                    echo $count." > Done > ".insertSQL($sql)." > ".$sql;
                }else{
                    echo $count." > Exists > ".$id." > ".$line;
                    
                }
                $count++;
                echo "<br>";
                
            }
        }
        fclose($handle);
    } else {
        // error opening the file.
        echo "error";
    }
}

function importAirlines(){
    $filename="C:\\Users\\tgdnasu3\\home\\ecPhp\\anisch_ticket\\docs\\all_airlines.csv";
    $count=0;
    $handle = fopen($filename, "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $cells = explode(";", $line);
            if ($cells[1]!=null){
                $selecsql="select id from all_airlines where code='".trim($cells[1])."'";
                echo $selecsql;
                $id=getSingleValue($selecsql);
                echo " > id > ".$id." > ".strlen((string)$id);
                if (strlen((string)$id)==0){
                    $sql= "INSERT INTO `all_airlines` (`id`, `name`, `code`, `code_no`, `icao`, `country`) VALUES (NULL, '".trim($cells[0])."', '".trim($cells[1])."', ".cheNull(trim($cells[2])).", '".trim($cells[3])."', '".trim($cells[4])."')";
                    echo $count." > Done > ".insertSQL($sql)." > ".$sql;
                }else{
                    echo $count." > Exists > ".$id." > ".$line;                    
                }
                $count++;
                echo "<br>";
            }
        }
        fclose($handle);
    } else {
        // error opening the file.
        echo "error";
    }
}

?>


<body>
<?php
//****************************remove this line for execution
//echo importAirports();

?>
</body>
</html><!--  -->

