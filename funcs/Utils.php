<?php

function verifyUser()
{
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user = getFetchArray("select * from bookies where username=" . cheSNull($username))[0];
    $result = password_verify($password, $user['password']);
    // $id = getSingleValue("select id from users where username=".cheSNull($username)." and password = ".cheSNull($password));
    if ($result == 1) {
        // print(" username ".$username." and password ".$password." and id is ".$id);
        // $role = getSingleValue("select role from users where id=".$id);
        $_SESSION['uid'] = $user['id'];
        $_SESSION['user'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: index.php');
    } else {
        include_once 'funcs/login.php';
        displayLoginPage("Invalid entry");
    }
}

function sendMail()
{
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $mobile_no = $_POST['mobile_no'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    ini_set("SMTP", SMTP_HOST);
    ini_set("smtp_port", SMTP_PORT);
    ini_set("sendmail_from", MAIL_FROM_ADDRESS);

    $toAddress = MAIL_TO_ADDRESS;
    $today = date("d-m-Y H:i:s");
    $subject = "[Enquiry]:: " . $first_name . " " . $last_name;
    $content = "<html><head><style>
table {
  border-collapse: collapse;
}
        
table, th, td {
  border-bottom: 1px solid #ddd;
}
th{
    background-color: #4CAF50;
    color: white;
    border-right: 1px solid white;
    font-weight: bold;
}
th, td {
  padding: 15px;
  text-align: left;
  font-size:14px;
  font-family: 'Lucida Console', Courier, monospace;
}
</style></head><body>
<table>
	<tr>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Date</th>
		<th>Mobile Number</th>
		<th>Email</th>
        <th>Message</th>
	</tr>
	<tr>
		<td>" . $first_name . "</td>
		<td>" . $last_name . "</td>
		<td>" . $today . "</td>
        <td>" . $mobile_no . "</td>
		<td>" . $email . "</td>
		<td>" . $message . "</td>
	</tr>
</table></body></html>";

    $headers = "From: ";
    $headers = "From: " . strip_tags(MAIL_FROM_ADDRESS) . "\r\n";
    $headers .= "Reply-To: " . strip_tags(MAIL_FROM_ADDRESS) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    mail($toAddress, $subject, $content, $headers);
    // echo "Check your email now....&lt;BR/>";
}

function getAllIATAAirports()
{
    $rows = getFetchArray("select city,country,code from all_airports");
    $data = "";
    foreach ($rows as $row) {
        $data .= '<option value="' . $row['code'] . '">' . $row['country'] . ' > ' . $row['city'] . '(' . $row['code'] . ') </option>';
    }
    return $data;
}

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

function parser($content)
{
    /*
     * $content="
     *
     *
     *
     * RP/BSLC22179/BSLC22179 NK/SU 6JAN20/1224Z SV74L2
     * 1.NADARAJAH/UTHAYAKUMARAN MR
     * 2 WY 154 U 31JAN 5*ZRHMCT HK1 2135 0705 01FEB E WY/HYHBOU
     * 3 WY 383 U 01FEB 6*MCTCMB HK1 0900 2030 01FEB E WY/HYHBOU
     * 4 WY 372 O 24FEB 1*CMBMCT HK1 0910 1200 24FEB E WY/HYHBOU
     * 5 WY 153 O 24FEB 1*MCTZRH HK1 1445 1905 24FEB E WY/HYHBOU
     * 6 AP BSL +41 61 272 2301 - KAYATHRI TRAVELS L. KANAGARATNAM -
     * A
     * 7 AP BSL +41 61 272 2301 - KAYATHRI TRAVELS L. KANAGARATNAM -
     * A
     * 8 TK OK06JAN/BSLC22129//ETWY
     * 9 SSR OTHS 1A REF IATA PRVD PAX EMAIL N MBL CTC IN SSR CTCE OR
     * CTCM
     * 10 SSR OTHS 1A REF IATA UPDATE SSR CTCR IF PAX REFUSING TO PRVD
     * CTC
     * 11 SSR OTHS 1A ADTK BY 1658 10JAN20 BSL LT ELSE WY WILL XXL
     * 12 RM WY PREIS 700.75, BITTE, TICKET AUSSTELLEN. VIELEN DANK.
     * 13 FA PAX 910-9484011912/ETWY/06JAN20/BSLC22129/81211885/S2-5
     * 14 FB PAX 0000000000 TTP OK ETICKET/S2-5
     * 15 FE PAX 2PC TOTAL 40KG/ VALIDONWY/S2-5
     * 16 FG PAX 0000000000 IEV1A098G/S2-5
     * 17 FM PAX *F*0.00/S2-5
     * )>
     *
     *
     * ";
     */
    $output=array();
    $output['error_message']=null;
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
        $output["isConfirmed"]=false;
        $etickets=array();
        $passengers=array();
        $routes=array();
        foreach ($rows as $row) {
            if (strlen($row) > 1) {
                //echo $row . "<br>";
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
                    //echo "<p style='color:red;'>" . "************ booking date " . $bDate . " and booking reference " . $bReference . " year " . $bYear . "</p>";
                    $output['booking_reference']=$bReference;
                    $output['booking_date']=$bDate;
                }
                
                for ($i = 1; $i <= 6; $i ++) {
                    if (InStr(trim($row), "   ") > 0) {
                        $temp = explode("   ", $row);
                        if ($temp != null && count($temp) > 1) {
                            if (startsWith(trim($temp[0]), $i . ".")) {
                                if($temp[0]){
                                    $passengers[]=$temp[0];
                                    //echo "<p style='color:brown;'>" . "************ " . $i . " passenger name " . $temp[0] . "</p>";
                                    $totalPassengers ++;
                                }                                
                            } else if (startsWith(trim($temp[1]), $i . ".")) {
                                if($temp[0]){
                                    $passengers[]=$temp[1];
                                    //echo "<p style='color:blue;'>" . "************ " . $i . " passenger name " . $temp[1] . "</p>";
                                    $totalPassengers ++;
                                }
                            }
                        }
                    } else if (startsWith(trim($row), $i . ".")) {
                        //echo "<p style='color:blue;'>" . "************ " . $i . " passenger row " . $row . "</p>";
                        $passengers[]=$row;
                        $totalPassengers ++;
                    }
                }
                
                //$row = str_replace("*", " ", $row);
                $pattern = "/\d{2}[A-Z]{3}[ ]{1,}\d{1}\*[A-Z]{6}[ ]{1,}\w{3}[ ]{1,}\d{4}[ ]{1,}\d{4}[ ]{1,}\d{2}[A-Z]{3}[ ]{1,}E[ ]{1,}/";
                $matches2 = array();
                
                if (preg_match($pattern, $row, $matches2)) {
                    
                    $matches1 = explode(" ", $matches2[0]);
                    
                    $matches = array();
                    foreach ($matches1 as $z) {
                        if ($z != null && strlen($z) > 0) {
                            $matches[] = $z;
                        }
                    }
                    
                    $startDate = $matches[0] . $bYear . " " . $matches[3];
                    
                    $startDate = date_format(new DateTime($startDate), 'd M Y H:i');
                    $startDate = date('Y-m-d, l H:i', strtotime($startDate));
                    //echo " start 123 ".$startDate;
                    //echo "<br>";
                    if ($startDate > $bDate) {
                        
                        $fromto = substr($matches[1], 2, 6);
                        
                        $from=substr($fromto, 0, 3);
                        
                        $to = substr($fromto, 3, 5);
                        //$from = getSingleValue("select concat(country, ' > ',city, ' (', code, ') ')c1 from all_airports where code='" . $from . "'");
                        $from = getSingleValue("select code from all_airports where code='" . $from . "'");
                        $to = getSingleValue("select code from all_airports where code='" . $to . "'");
                        
                        if ($from != null && $to != null) {
                            
                            $fromPoints[] = $from;
                            $toPoints[] = $to;
                            
                            $landDate = $matches[5] . $bYear . " " . $matches[4];
                            
                            $landDate = date_format(new DateTime($landDate), 'd M Y H:i');
                            $landDate = date('Y-m-d, l H:i', strtotime($landDate));
                            //echo " land123 ".$landDate." start ".$startDate;
                            //echo "<br>";
                            if ($landDate > $startDate) {
                                
                                $routes[]=array("startDate"=>$startDate,"from"=>$from,"to"=>$to,"landDate"=>$landDate);
                                //echo "<p style='color:blue;'>" . " start date " . $startDate . " |  from " . $from . " |  to " . $to . " | land date " . $landDate . " </p>";
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
                                    //echo "air Match not found.";
                                }
                                //
                            }
                        }
                        
                    }
                    
                } else {
                    // print("matched not found");
                }
                $pattern = "/FA PAX\s{1,}\w{1,}-\w{1,}/";
                if(preg_match($pattern,trim($row),$matches)){
                    //print_r($matches);
                    if(InStr($matches[0], " ")>0){
                        $t1=explode(" ", $matches[0]);
                        if(count($t1)==3){
                            //print(" ticket number ".$t1[2]."<br>");
                            $etickets[]=$t1[2];
                            $output["isConfirmed"]=true;
                        }
                    }
                }
                
                $rowNumber ++;
            }
        }
        $output['routes']=$routes;
        $output['airwaysName']=$airwaysName;
        
        $totalFroms = count($fromPoints);
        $totalTOs = count($toPoints);
        if ($totalFroms > 1 && $totalTOs > 1) {
            $startingFrom = $fromPoints[0];
            
            
            $endTo = $toPoints[$totalTOs - 1];
            if ($startingFrom == $endTo) {
                $output['tripType']="rountTrip";
                $output['startingFrom']=$startingFrom;
                $output['finalDestination']=$toPoints[($totalTOs / 2) - 1];
                //print("round trip >> starting from " . $startingFrom . " and ending at " . $endTo . " destination is " . $toPoints[($totalTOs / 2) - 1]);
            } else {
                $output['tripType']="oneway";
                $output['startingFrom']=$startingFrom;
                $output['finalDestination']=$endTo;
                //print("one way >> starting from " . $startingFrom . " destination is " . $endTo);
            }
        }
        $output['totalPassengers']=$totalPassengers;
        $passengersList=array();
        $i=0;
        foreach ($passengers as $passenger){
            $f=array();
            if(preg_match("/\sMRS|MISS|MR|CHD|INF/", $passenger,$f)){
                $prefix=trim($f[0]);
                $extra="";
                if($prefix=="CHD"){
                    $t1=explode("(", $passenger);
                    $passenger=$t1[0];
                    $extra=str_replace(")", "", $t1[1]);
                    $extra=str_replace("CHD/", "", $extra);
                }
            }
            $passenger=preg_replace("/\sMRS|MISS|MR|\(CHD\W{1,}\)|MASTER/", "", $passenger);
            $passenger=trim($passenger);
            
            $temp=explode("/", $passenger);
            $first_name = preg_replace("/^\d./", "", $temp[0]);
            $f=null;
            $f=array();
            
            $f["prefix"]=$prefix;
            $f["first_name"]=$first_name;
            $f["last_name"]=$temp[1];
            $f["extra"]=$extra;
            if(count($etickets)>0){
                $f["eTicketNumber"]=$etickets[$i];
            }else{$f["eTicketNumber"]="";}
            $passengersList[]=$f;
            
            
            $i++;
        }
        $output['passengers']=$passengersList;
        $output['sabre_output']=$content;

        /* if ($totalPassengers > 0) {
            echo "<p style='color:green;'>" . "************ total no of passengers " . $totalPassengers . "</p>";
        } */
        //echo "airways name " . $airwaysName . " <br>";
        return $output;
    }
}

function testRegex()
{
    $content = "4  QR 663 W 28JAN 2*CMBDOH HK3  0425 0710  28JAN  E  QR/NWKVJE";
    $pattern = "/\d{1}[ ]{1,}[A-Z]{2}/";
    $matches = array();
    if (preg_match($pattern, $content, $matches)) {
        print($matches[0] . "<br>");
    } else {
        echo "Match not found.";
    }
}

function getAllCustomersForSelect(){
    $list="";
    $rows=getFetchArray("select * from customers");
    foreach ($rows as $row){
        $list.="<option value=".$row["id"].">".$row["first_name"]." ".$row["last_name"]." ".$row["city"]." ".$row["mobile"]."</option>";
    }
    return $list;
}

function getAllAirportsForEdit($code){
    
    $list="";
    $rows=getFetchArray("select * from all_airports");
    foreach ($rows as $row){
        if($row['code']==$code){
            $list.="<option value=".$row["code"]." checked>".$row["country"].">".$row["city"]." (".$row["code"].")</option>";
        }
    }
    foreach ($rows as $row){
        $list.="<option value=".$row["code"].">".$row["country"].">".$row["city"]." ".$row["code"]."</option>";
    }
    return $list;
}

?>
