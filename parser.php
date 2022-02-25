<?php
include 'includes/cons.php';

?>
<!doctype html>
<html lang="en">
<?php echo getHead("Import", "", "")?>

<?php

function InStr($haystack, $needle)
{
    $pos = strpos($haystack, $needle);
    if ($pos !== false) {
        return $pos;
    } else {
        return - 1;
    }
}

function startsWith($haystack, $needle)
{
    return $needle === "" || strpos($haystack, $needle) === 0;
}

function endsWith($haystack, $needle)
{
    return $needle === "" || substr($haystack, - strlen($needle)) === $needle;
}

function getOriginalCellFromRow($row, $delimitter)
{
    $cells = explode($delimitter, $row);
    $origCells = array();
    $origNo = 0;
    foreach ($cells as $cell) {
        if (strlen($cell) > 0) {
            $origCells[$origNo] = $cell;
            $origNo ++;
        }
    }
    return $origCells;
}

function parser()
{
    $content = "



RP/BSLC22179/BSLC22179            NK/SU   6JAN20/1224Z   SV74L2
  1.NADARAJAH/UTHAYAKUMARAN MR
  2  WY 154 U 31JAN 5*ZRHMCT HK1  2135 0705  01FEB  E  WY/HYHBOU
  3  WY 383 U 01FEB 6*MCTCMB HK1  0900 2030  01FEB  E  WY/HYHBOU
  4  WY 372 O 24FEB 1*CMBMCT HK1  0910 1200  24FEB  E  WY/HYHBOU
  5  WY 153 O 24FEB 1*MCTZRH HK1  1445 1905  24FEB  E  WY/HYHBOU
  6 AP BSL +41 61 272 2301 - KAYATHRI TRAVELS L. KANAGARATNAM -
       A
  7 AP BSL +41 61 272 2301 - KAYATHRI TRAVELS L. KANAGARATNAM -
       A
  8 TK OK06JAN/BSLC22129//ETWY
  9 SSR OTHS 1A REF IATA PRVD PAX EMAIL N MBL CTC IN SSR CTCE OR
       CTCM
 10 SSR OTHS 1A REF IATA UPDATE SSR CTCR IF PAX REFUSING TO PRVD
       CTC
 11 SSR OTHS 1A ADTK BY 1658 10JAN20 BSL LT ELSE WY WILL XXL
 12 RM WY PREIS 700.75, BITTE, TICKET AUSSTELLEN. VIELEN DANK.
 13 FA PAX 910-9484011912/ETWY/06JAN20/BSLC22129/81211885/S2-5
 14 FB PAX 0000000000 TTP OK ETICKET/S2-5
 15 FE PAX 2PC TOTAL 40KG/ VALIDONWY/S2-5
 16 FG PAX 0000000000 IEV1A098G/S2-5
 17 FM PAX *F*0.00/S2-5
)>


";
    if (strlen($content) > 1) {
        $bDate = null;
        $content = trim($content);
        $rows = explode("\n", $content);
        $totalPassengers = 0;
        $rowNumber = 1;
        $airwaysName = null;
        $fromPoints = array();
        $toPoints = array();
        $startingFrom = null;
        $finalDestination = null;

        foreach ($rows as $row) {
            if (strlen($row) > 1) {
                echo $row . "<br>";
                /* parsing booking reference and booking date row */
                if (startsWith($row, "RP") && $bDate == null) {
                    $cells = getOriginalCellFromRow($row, " ");
                    $bdCells = getOriginalCellFromRow($cells[2], "/");
                    date_default_timezone_set('Europe/Zurich');
                    $dateTmp = new DateTime($bdCells[0]);
                    $bDate = date_format($dateTmp, 'd M Y');
                    $bYear = date_format($dateTmp, 'Y');
                    $bDate = date('Y-m-d', strtotime($bDate));
                    $bReference = $cells[3];
                    echo "<p style='color:red;'>" . "************ booking date " . $bDate . " and booking reference " . $bReference . " year " . $bYear . "</p>";
                }

                for ($i = 1; $i <= 6; $i ++) {
                    if (InStr(trim($row), "   ") > 0) {
                        $temp = explode("   ", $row);
                        if ($temp != null && count($temp) > 1) {
                            if (startsWith(trim($temp[0]), $i . ".")) {
                                echo "<p style='color:blue;'>" . "************ " . $i . " passenger name " . $temp[0] . "</p>";
                                $totalPassengers ++;
                            } else if (startsWith(trim($temp[1]), $i . ".")) {
                                echo "<p style='color:blue;'>" . "************ " . $i . " passenger name " . $temp[1] . "</p>";
                                $totalPassengers ++;
                            }
                        }
                    } else if (startsWith(trim($row), $i . ".")) {
                        echo "<p style='color:blue;'>" . "************ " . $i . " passenger row " . $row . "</p>";
                        $totalPassengers ++;
                    }
                }
                $row = str_replace("*", " ", $row);
                $pattern = "/\d{2}[A-Z]{3}[ ]{1,}\d{1}.[A-Z]{6}[ ]{1,}\w{3}[ ]{1,}\d{4}[ ]{1,}\d{4}[ ]{1,}\d{2}[A-Z]{3}[ ]{1,}E[ ]{1,}/";
                $matches2 = array();
                if (preg_match($pattern, $row, $matches2)) {
                    print("match found mame, " . $matches2[0] . "<br>");
                    $matches1 = explode(" ", $matches2[0]);
                    $matches = array();
                    foreach ($matches1 as $z) {
                        if ($z != null && strlen($z) > 0) {
                            $matches[] = $z;
                        }
                    }
                    $startDate = $matches[0] . $bYear . " " . $matches[4];

                    $startDate = date_format(new DateTime($startDate), 'd M Y H:i');
                    $startDate = date('Y-m-d, l H:i', strtotime($startDate));
                    if ($startDate > $bDate) {
                        $from = substr($matches[2], 0, 3);
                        $to = substr($matches[2], 3, 6);
                        $from = getSingleValue("select concat(country, ' > ',city, ' (', code, ') ')c1 from all_airports where code='" . $from . "'");
                        $to = getSingleValue("select concat(country, ' > ',city,' (', code, ') ')c1 from all_airports where code='" . $to . "'");
                        if ($from != null && $to != null) {
                            $fromPoints[] = $from;
                            $toPoints[] = $to;
                            $landDate = $matches[6] . $bYear . " " . $matches[5];
                            $landDate = date_format(new DateTime($landDate), 'd M Y H:i');
                            $landDate = date('Y-m-d, l H:i', strtotime($landDate));

                            if ($landDate > $startDate) {

                                echo "<p style='color:blue;'>" . " start date " . $startDate . " |  from " . $from . " |  to " . $to . " | land date " . $landDate . " </p>";
                                $pattern1 = "/\d{1}[ ]{1,}[A-Z]{2}/";
                                $air_matches = array();
                                // print("before air match ".$row);
                                if (preg_match($pattern1, $row, $air_matches)) {
                                    $airwaysCode1 = explode(" ", $air_matches[0]);
                                    foreach ($airwaysCode1 as $z) {
                                        if ($z != null && strlen($z) > 0) {
                                            $airwaysCode[] = $z;
                                        }
                                    }
                                    // print("<br> ".$airwaysCode[1]);
                                    $airwaysName = getSingleValue("SELECT name FROM `all_airlines` where code='" . $airwaysCode[1] . "'");
                                    // print("air match found >> ".$airwaysName."<br>");
                                } else {
                                    echo "air Match not found.";
                                }
                                //
                            }
                        }
                    }
                } else {
                    // print("matched not found");
                }

                $rowNumber ++;
            }
        }
        $totalFroms = count($fromPoints);
        $totalTOs = count($toPoints);
        if ($totalFroms > 1 && $totalTOs > 1) {
            $startingFrom = $fromPoints[0];
            $endTo = $toPoints[$totalTOs - 1];
            if ($startingFrom == $endTo) {
                print("round trip >> starting from " . $startingFrom . " and ending at " . $endTo . " destination is " . $toPoints[($totalTOs / 2) - 1]);
            } else {
                print("one way >> starting from " . $startingFrom . " destination is " . $endTo);
            }
        }

        if ($totalPassengers > 0) {
            echo "<p style='color:green;'>" . "************ total no of passengers " . $totalPassengers . "</p>";
        }
        echo "airways name " . $airwaysName . " <br>";
    }
}

/**
 * old sabre format
 * 15th Oct
 */

/*
 * function testRegex(){
 * $content=trim("
 * RP/BSLC22179/BSLC22179 SG/SU 9MAR20/1145Z T68R6L
 * 1.KUMARAIAH/SIVAKUMARAN MR
 * 2.SIVAKUMARAN/YATHURAN(CHD/28DEC11)
 * 3 QR 096 N 09MAR 1*ZRHDOH HK2 1605 2355 09MAR E QR/T68R6L
 * 4 QR 664 N 10MAR 2*DOHCMB HK2 0200 0920 10MAR E QR/T68R6L
 * 5 QR 655 W 30MAR 1*CMBDOH HK2 2115 2355 30MAR E QR/T68R6L
 * 6 QR 093 W 31MAR 2*DOHZRH HK2 0150 0715 31MAR E QR/T68R6L
 * 7 AP BSL +41 61 272 2301 - KAYATHRI TRAVELS L. KANAGARATNAM -
 * A
 * 8 TK PAX OK09MAR/BSLC22129//ETQR/S3-6/P1
 * 9 TK OK09MAR/BSLC22129//ETQR
 * 10 SSR CHLD QR HK1 28DEC11/P2
 * 11 RM QR PREIS 1X794.- UND 1X731.-, BITTE, TICKET AUSSTELLEN.
 * VIELEN DANK.
 * 12 FA PAX 157-3861033601/ETQR/09MAR20/BSLC22129/81211885
 * /S3-6/P1
 * 13 FA PAX 157-3861033602/ETQR/09MAR20/BSLC22129/81211885
 * /S3-6/P2
 * 14 FB PAX 0000000000 TTP OK ETICKET/S3-6/P1
 * 15 FB PAX 0000000001 TTP OK ETICKET/S3-6/P2
 * 16 FE PAX /C1-4 NON END/CHNG PENALTIES AS PER RULE/S3-6/P1
 * 17 FE PAX /C1-4 NON END/CHNG PENALTIES AS PER RULE/S3-6/P2
 * )>
 * ");
 * $matches=array();
 * $pattern = "/FA PAX\s{1,}\w{1,}-\w{1,}/";
 * $rows=explode("\n", $content);
 * foreach($rows as $row){
 * $row=trim($row);
 * if(preg_match($pattern,$row,$matches)){
 * if(InStr($matches[0], " ")>0){
 * $t1=explode(" ", $matches[0]);
 * if(count($t1)==3){
 * print(" ticket number ".$t1[2]."<br>");
 * }
 * }
 * }
 * }
 *
 * }
 */
/* function getBookingReference($row)
{
    if (strlen(trim($row)) == 6) {
        $bReference = $row;
        return $bReference;
    } else {
        return null;
    }
} */

function testRegex()
{
    $content = trim("TXETXQ

     1.1SIVAPATHASUNDARAM/KANISSA MRS  2.1MAYURAN/SHAMJUKTA MISS

     3.I/1MAYURAN/DENISHAGI MISS

     1 LH1203T 02NOV 2 BSLFRA HK2  1045  1145  SPM /DCLH*RUMJAN /E

    OPERATED BY LUFTH CITYLINE

     2 UL 554V 02NOV 2 FRACMB HK2  1515  0530   03NOV 3 SPM

                                                   /DCUL*RUMJAN /E

    TKT/TIME LIMIT

      1.T-21OCT-IG0H*ACB

    PHONES

      1.ZRH031 911 03 38

   PASSENGER EMAIL DATA EXISTS  *PE TO DISPLAY ALL

    INVOICED

    PRICE QUOTE RECORD EXISTS - SYSTEM

    SECURITY INFO EXISTS *P3D OR *P4D TO DISPLAY

    AA FACTS

      1.SSR ADTK 1S TO UL BY 22OCT 1553 BRN TIME ZONE OTHERWISE WIL

        L BE XLD

      2.SSR INFT LH KK1 BSLFRA1203T02NOV/MAYURAN/DENISHAGI MISS/23A¥

 

*T«

 

    TKT/TIME LIMIT

      1.T-21OCT-IG0H*ACB

      2.TE 6033698549314-CH SIVAP/K IG0H*ACB 1931/21OCT*I

      3.TE 6033698549315-CH MAYUR/S IG0H*ACB 1931/21OCT*I

      4.TE 6033698549316-CH MAYUR/D IG0H*ACB 1931/21OCT*I");

    $output = array();
    $output['error_message'] = null;
    if (strlen($content) > 1) {
        $bDate = null;
        $content = trim($content);
        $rows = explode("\n", $content);
        $totalPassengers = 0;
        $rowNumber = 1;
        $airwaysName = null;
        $fromPoints = array();
        $toPoints = array();
        $startingFrom = null;
        $output["isConfirmed"] = false;
        $etickets = array();
        
        $routes = array();
        
        $output = getBookingReference($rows,$output);

        if ($output['booking_reference'] != null) {
            $output = getPassengers($rows,$output);
            $passengers = $output['passengers'];
            $passengercount=0;
            foreach ($passengers as $passenger){
                echo $passengercount.".".$passenger."<br>";
                $passengercount++;
            }
            echo "total passengers ".$passengercount."<br>";
        }
        
        
        
        
        #$bDate = date('Y-m-d');
        #$bYear = date_format($bDate, 'Y');
        
        $dateTmp = new DateTime();
        $bDate = date_format($dateTmp, 'd M Y');
        $bYear = date_format($dateTmp, 'Y');
        $bDate = date('Y-m-d', strtotime($bDate));
        $output['bDate'] = $bDate;
        echo "booking date ".$bDate;
        echo "<br>";
        
        if ($output['error_message'] == null) {
            
        }
    }
}

function getStartDateSameDay($r2,$bYear){
    $startDate = $r2[0] . $bYear. $r2[4];
    $startDate = date_format(new DateTime($startDate), 'd M Y H:i');
    return $startDate;
}

function getEndDateSameDay($r2,$bYear){
    $startDate = $r2[0] . $bYear. $r2[5];
    $startDate = date_format(new DateTime($startDate), 'd M Y H:i');
    return $startDate;
}

function getStartDateNextDay($r2,$bYear){
    $startDate = $r2[0] . $bYear. $r2[4];
    $startDate = date_format(new DateTime($startDate), 'd M Y H:i');
    return $startDate;
}

function getEndDateNextDay($r2,$bYear){
    $startDate = $r2[6] . $bYear. $r2[5];
    $startDate = date_format(new DateTime($startDate), 'd M Y H:i');
    return $startDate;
}

function getBookingReference($rows,$output){
    $rowNumber=1;
    foreach ($rows as $row) {
        if (strlen($row) > 1) {
            // echo $row . "<br>";
            /* parsing booking reference and booking date row */
            if ($rowNumber == 1) {
                $bReference = getBookingReference($row);
                if ($bReference != null) {
                    print("Booking reference is <b>" . $bReference . "</b><br>");
                    $output['booking_reference'] = $bReference;
                } else {
                    $output['booking_reference'] = null;
                    $output['error_message'] = "Invalid booking reference";
                    echo "<p style='color:brown;'>" . "************ Invalid Booking reference </p>";
                }
            }
            $rowNumber ++;
        }
    }
}

function getPassengers($rows,$output){
    $totalPassengers=0;
    $passengers=array();
    /* now parsing passenger details */
    foreach ($rows as $row) {
        if (strlen($row) > 1) {
            for ($i = 1; $i <= 6; $i ++) {
                if (InStr(trim($row), "  ") > 0) {
                    $temp = explode("  ", trim($row));
                    //echo "i am here ".$row." ".count($temp)." -- ".print_r($temp)."<br>";
                    if ($temp != null && count($temp) > 1) {
                        if (startsWith(trim($temp[0]), $i . ".1")) {
                            if ($temp[0]) {
                                $passengers[] = str_replace($i.".1", "", $temp[0]);
                                //echo "<p style='color:brown;'>" . "************ " . $i . " passenger name " . $temp[0] . "</p>";
                                $totalPassengers ++;
                            }
                        } else if (startsWith(trim($temp[1]), $i . ".1")) {
                            if ($temp[0]) {
                                $passengers[] = str_replace($i.".1", "", $temp[1]);
                                //echo "<p style='color:blue;'>" . "************ " . $i . " passenger name " . $temp[1] . "</p>";
                                $totalPassengers ++;
                            }
                        }
                    }
                } else if (startsWith(trim($row), $i . ".1")) {
                    //echo "<p style='color:red;'>" . "************ " . $i . " passenger row " . $row . "</p>";
                    $row=str_replace($i.".1", "", $row);
                    $passengers[] = $row;
                    $totalPassengers ++;
                }else if (startsWith(trim($row), $i . ".I/1")) {
                    //echo "<p style='color:yellow;background:black;'>" . "************ " . $i . " passenger row " . $row . "</p>";
                    $row=str_replace($i.".I/1", "", $row);
                    $passengers[] = $row;
                    $totalPassengers ++;
                }
            }
        }
    }
    if (count($passengers) > 0 && count($passengers) == $totalPassengers) {
        $output['passengers'] = $passengers;
        $output['error_message'] = null;
    } else {
        $output['error_message'] = "Invalid passengers";
    }    
}


function getRoutes($rows,$bYear,$bDate,$output){
    $routes=array();
    $fromPoints=array();
    $toPoints=array();
    foreach ($rows as $row) {
        if (strlen($row) > 1) {
            $pattern = "/\d{2}[A-Z]{3}[ ]{1,}\d{1}[ ][A-Z]{6}[ ]{1,}HK\d{1}[ ]{1,}\d{4}[ ]{1,}\d{4}[ ]{1,}(\d{2}[A-Z]{3}[ ]{1,}\d{1}|(.*?))/";
            $matches = array();
            preg_match($pattern, $row, $matches);
            if($matches!=null){
                //print_r($matches);
                //print("<br>");
                $r2=array();
                $r1=explode(" ", trim($matches[0]));
                //echo count($r1)."<br>";
                for ($i = 0; $i < count($r1); $i++) {
                    
                    //echo $r1[$i]."<br>";
                    if(strlen(trim($r1[$i]))>0){
                        $r2[]=trim($r1[$i]);
                        //print(count($r2)."<br>");
                    }
                }
                /* print("here is the route row ".count($r2));
                 print("<br>");
                 print_r($r2);
                 print("<br>"); */
                if(count($r2)==6){
                    /***
                     * Start Date
                     */
                    $startDate=new DateTime($r2[0] . $bYear);
                    
                    if($startDate>$bDate){
                        $startDateString=getStartDateSameDay($r2, $bYear);
                    }else{
                        $bYear=($bYear)+1;
                        $startDateString=getStartDateSameDay($r2, $bYear);
                    }
                    
                    /***
                     * End Date
                     */
                    $endDate=new DateTime($r2[0] . $bYear);
                    if($endDate>=$startDate){
                        $endDateString=getEndDateSameDay($r2, $bYear);
                    }else{
                        $bYear=(int)$bYear+1;
                        $endDateString=getEndDateSameDay($r2, $bYear);
                    }
                    
                    /***
                     * From and To
                     */
                    $fromto = substr($r2[2], 0, 6);
                    $from = substr($fromto, 0, 3);
                    $to = substr($fromto, 3, 5);
                    
                    if($from != null && $to != null){
                        $fromPoints[] = $from;
                        $toPoints[] = $to;
                    }
                    
                    //$from = getSingleValue("select concat(country, ' > ',city, ' (', code, ') ')c1 from all_airports where code='" . $from . "'");
                    //$to = getSingleValue("select concat(country, ' > ',city,' (', code, ') ')c1 from all_airports where code='" . $to . "'");
                    $startDateString = date('Y-m-d, l H:i', strtotime($startDateString));
                    $endDateString = date('Y-m-d, l H:i', strtotime($endDateString));
                    print($startDateString." - ".$from." - ".$to." - ".$endDateString);
                    print("<br>");
                    $routes[]=array("startDate"=>$startDateString,"from"=>$from,"to"=>$to,"landDate"=>$endDateString);
                    
                }else if(count($r2)==8){
                    /***
                     * Start Date
                     */
                    
                    $startDate=new DateTime($r2[0] . $bYear);
                    
                    if($startDate>$bDate){
                        $startDateString=getStartDateNextDay($r2, $bYear);
                    }else{
                        
                        $bYear=(int)$bYear+1;
                        $startDateString=getStartDateSameDay($r2, $bYear);
                    }
                    
                    /***
                     * End Date
                     */
                    $endDate=new DateTime($r2[6] . $bYear);
                    if($endDate>$startDate){
                        $endDateString=getEndDateNextDay($r2, $bYear);
                    }else{
                        $bYear=($bYear)+1;
                        $endDateString=getEndDateNextDay($r2, $bYear);
                    }
                    
                    
                    /***
                     * From and To
                     */
                    $fromto = substr($r2[2], 0, 6);
                    $from = substr($fromto, 0, 3);
                    $to = substr($fromto, 3, 5);
                    
                    if($from != null && $to != null){
                        $fromPoints[] = $from;
                        $toPoints[] = $to;
                    }
                    
                    $from = getSingleValue("select concat(country, ' > ',city, ' (', code, ') ')c1 from all_airports where code='" . $from . "'");
                    $to = getSingleValue("select concat(country, ' > ',city,' (', code, ') ')c1 from all_airports where code='" . $to . "'");
                    $startDateString = date('Y-m-d, l H:i', strtotime($startDateString));
                    $endDateString = date('Y-m-d, l H:i', strtotime($endDateString));
                    print($startDateString." - ".$from." - ".$to." - ".$endDateString);
                    print("<br>");
                    $routes[]=array("startDate"=>$startDateString,"from"=>$from,"to"=>$to,"landDate"=>$endDateString);
                }else{
                    
                }
                $output["routes"]=$routes;
                $output["fromPoints"]=$fromPoints;
                $output["toPoints"]=$toPoints;
            }
            return $output;
        }
    }
}



?>


<body style='padding: 10px; margin: 10px; font-size: 12px;'>
<?php
// ****************************remove this line for execution
// echo parser();
echo testRegex();

?>
</body>
</html>
<!--  -->

