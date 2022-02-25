<?php

/**
 * 
 "TXETXQ
        
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
        
      4.TE 6033698549316-CH MAYUR/D IG0H*ACB 1931/21OCT*I"
 */

function parseTicket($content,$bookingDate)
{
    $time = strtotime($bookingDate);
    $bookingDate = date('Y-m-d',$time);
    $bYear = date('Y',$time);;
    $eticketRows=array();
    $output = array();
    $output['sabre_output'] = $content;
    $output['error_message'] = null;
    if (strlen($content) > 1) {
        $content = trim($content);
        $rows = explode("\n", $content);
        $output["isConfirmed"] = false;
        $output = getBookingReference($rows,$output);
        if ($output['booking_reference'] != null) {
            //$output = getBookingDate($rows,$output);
            $output["booking_date_string"] = date('Y-m-d', strtotime($bookingDate));
            $eticketRows = getETickets($rows);
            if($eticketRows != null && count($eticketRows)>0){
                $output = getPassengers($rows,$output,$eticketRows);
                $output["isConfirmed"] = true;
            }else{
                $output = getPassengers($rows,$output,null);
            }
            if ($output['error_message'] == null) {
                $output = getRoutes($rows,$bYear,$bookingDate,$output);
            }
        }
    }
    return $output;
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
                $bReference = $row;
                if ($bReference != null) {
                    //print("Booking reference is <b>" . $bReference . "</b><br>");
                    $output['booking_reference'] = $bReference;
                } else {
                    $output['booking_reference'] = null;
                    $output['error_message'] = "Invalid booking reference";
                    //echo "<p style='color:brown;'>" . "************ Invalid Booking reference </p>";
                }
            }else{
                
            }
            $rowNumber ++;
        }
    }
    return $output;
    
}

function getPassengers($rows,$output,$eticketRows){
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
                                $passengers[] = str_replace($i.".1", "", trim($temp[0]));
                                //echo "<p style='color:brown;'>" . "************ " . $i . " passenger name " . $temp[0] . "</p>";
                                $totalPassengers ++;
                            }
                        } else if (startsWith(trim($temp[1]), $i . ".1")) {
                            if ($temp[0]) {
                                $passengers[] = str_replace($i.".1", "", trim($temp[1]));
                                //echo "<p style='color:blue;'>" . "************ " . $i . " passenger name " . $temp[1] . "</p>";
                                $totalPassengers ++;
                            }
                        }
                    }
                } else if (startsWith(trim($row), $i . ".1")) {
                    //echo "<p style='color:red;'>" . "************ " . $i . " passenger row " . $row . "</p>";
                    $row=str_replace($i.".1", "", trim($row));
                    $passengers[] = $row;
                    
                    
                    $totalPassengers ++;
                }else if (startsWith(trim($row), $i . ".I/1")) {
                    //echo "<p style='color:yellow;background:black;'>" . "************ " . $i . " passenger row " . $row . "</p>";
                    $row=str_replace($i.".I/1", "", trim($row));
                    $passengers[] = $row;
                    $totalPassengers ++;
                }
            }
        }
    }
    if (count($passengers) > 0 && count($passengers) == $totalPassengers) {
        $passengersList=array();
        for ($i = 0; $i < count($passengers); $i++) {
            if($eticketRows != null && count($eticketRows)>0 && $eticketRows[$i] != null){
                $passengersList[]= getPrefixFirstName($passengers[$i],$eticketRows[$i]);
            }else{
                $passengersList[]= getPrefixFirstName($passengers[$i],null);
            }
            
        }
        $output['passengers']=$passengersList;
        $output['totalPassengers'] = $totalPassengers;
        $output['error_message'] = null;
    } else {
        $output['error_message'] = "Invalid passengers";
    }
    
    return $output;
}

function getETickets($rows){
    $etickets=array();
    foreach ($rows as $row){
        $pattern = "/\d{1}.[A-Z]{2}[ ]{1,}\d{1,}-[A-Z]{2}/";
        $matches = array();
        preg_match($pattern, $row, $matches);
        if($matches!=null && count($matches)>0 && $matches[0] !=null){
            $etkts=explode(" ", $matches[0]);
            //print_r($etkts);
            if($etkts!=null && count($etkts)>0 && $etkts[1] !=null){                
                $etickets[]=$etkts[1];
            }
        }
    }
    return $etickets;    
}

/* function getBookingDate($rows,$output){
    $dateTmp = new DateTime();
    //$today = date_format($dateTmp, 'd M Y');
    //print_r($today);
    $year = date_format($dateTmp, 'Y');
    
    //$today = date('Y-m-d', strtotime($today));
    $bookingYear=0;
    $bookingDate=null;
    $matches = array();
    foreach ($rows as $row){
        $pattern = "/\d{1}.[A-Z]{1}-\d{2}[A-Z]{3}-/";
        preg_match($pattern, $row, $matches);
        if($matches!=null && count($matches)>0 && $matches[0] !=null){
            $temp=explode("-", $matches[0]);
            if($temp!=null && count($temp)>1 && $temp[1] !=null){
                //print($temp[1]);
                $bookingDate=new DateTime($temp[1] . $year);
                print_r($bookingDate);
                if($bookingDate >= $dateTmp){
                    //print("booking date is greater ");
                    $bookingYear=$year;
                    //print("booking year ".$bookingYear);
                }else{
                    //print("booking date is smaller ");
                    $bookingYear=(int)$year+1;
                    $bookingDate=new DateTime($temp[1] . $bookingYear);
                }
                if($bookingDate >= $dateTmp){
                    $output["booking_date"]=$bookingDate;
                    $bookingDate = date_format($bookingDate, 'd M Y');
                    $output["booking_date_string"] = date('Y-m-d', strtotime($bookingDate));
                    $output["booking_year"]=$bookingYear;
                }
                
                break;           
            }
        }
    }
    
    
    
    
    //print("booking date string ".$output["booking_date_string"]);
    return $output;
}
 */
function getPrefixFirstName($row,$eticketRows){
    $nam=array();
    $names=explode(" ", $row);
    //print($eticketRows);
    //print_r($names);
    //print("<br>");
    
    if(count($names)>0 && $names[0]!=null && $names[1]!=null){
        if($names[1]=="MR" || $names[1]=="MRS" || $names[1]=="MISS"|| $names[1]=="CHD"){
            $nam=explode("/", $names[0]);
            return array("prefix"=>$names[1],"last_name"=>$nam[0],"first_name"=>$nam[1],"extra"=>"","eTicketNumber"=>$eticketRows);
        }
    }
}


function getRoutes($rows,$bYear,$bDate,$output){
    $routes=array();
    $fromPoints=array();
    $toPoints=array();
    $routeRows=array();
    foreach ($rows as $row) {
        if (strlen($row) > 1) {
            $pattern = "/\d{2}[A-Z]{3}[ ]{1,}\d{1}[ ][A-Z]{6}([ ]{1,}|\*)HK\d{1}[ ]{1,}\d{4}[ ]{1,}\d{4}[ ]{1,}(\d{2}[A-Z]{3}[ ]{1,}\d{1}|(.*?))/";
            $matches = array();
            preg_match($pattern, $row, $matches);
            if($matches!=null){
                
                $routeRows[]=$row;
                //print_r($matches);
                //print("<br>");
                $r2=array();
                $temp = str_replace("*", " ", $matches[0]); 
                $r1=explode(" ", trim($temp));
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
                        $bYear=(int)$bYear+1;
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
                    //print($startDateString." - ".$from." - ".$to." - ".$endDateString);
                    //print("<br>");
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
                    
                    //$from = getSingleValue("select concat(country, ' > ',city, ' (', code, ') ')c1 from all_airports where code='" . $from . "'");
                    //$to = getSingleValue("select concat(country, ' > ',city,' (', code, ') ')c1 from all_airports where code='" . $to . "'");
                    $startDateString = date('Y-m-d, l H:i', strtotime($startDateString));
                    $endDateString = date('Y-m-d, l H:i', strtotime($endDateString));
                    //print($startDateString." - ".$from." - ".$to." - ".$endDateString);
                    //print("<br>");
                    $routes[]=array("startDate"=>$startDateString,"from"=>$from,"to"=>$to,"landDate"=>$endDateString);
                }else{
                    
                }
               
            }
            
        }
    }
    $output = getStartingFrom($fromPoints, $toPoints,$output);
    $output=getAirwaysName($routeRows,$output);
    $output["routes"]=$routes;
    $output["fromPoints"]=$fromPoints;
    $output["toPoints"]=$toPoints;
    return $output;
}

function getAirwaysName($routeRows,$output){
    foreach ($routeRows as $row){
        $pattern="/\d{1}[ ]{1,}[A-Z]{2}/";
        $matches = array();
        preg_match($pattern, $row, $matches);
        if($matches!=null && count($matches)>0 && $matches[0] !=null){
            $temp=explode(" ", $matches[0]);
            if($temp[1]!=null){
                $airwaysName = getSingleValue("SELECT name FROM `all_airlines` where code='" . $temp[1] . "'");
                if($airwaysName!=null){
                    //print($airwaysName);
                    $output["airwaysName"]=$airwaysName;
                    break;
                }
            }
        }
    }
    return $output;
}

function getStartingFrom($fromPoints, $toPoints,$output){
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
    return $output;
}