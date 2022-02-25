<?php
include 'includes/cons.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $html = prepareContent($id);
    //echo $html;
    printPDFInBrowser($html);
}

// require composer autoload
function prepareContent($id)
{
    $order=getFetchArray("select * from orders where id=".$id);
    $trans = getFetchArray("select * from routes where parent_id = " . $id . " order by id");
    $passengers = getFetchArray("select * from passengers where parent_id = " . $id . " order by id");
    $customer = getFetchArray("select * from customers where id = " . $order[0]["customer_id"]);
    $html = '
<html>
	<head>' . prepareHead() . '
	</head>
	<body>';
    $html .= prepareBody($order, $trans, $id,$passengers, $customer);
    $html .= '</body>
</html>
';
    return $html;
}

function printPDFInBrowser($html)
{
    require_once __DIR__ . '/mpdf/autoload.php';
    $mpdf = new \Mpdf\Mpdf(); // Create new mPDF Document

    // Beginning Buffer to save PHP variables and HTML tags
    ob_start();

    $mpdf = new \Mpdf\Mpdf([
        'margin_left' => 10,
        'margin_right' => 10,
        'margin_top' => 40,
        'margin_bottom' => 10,
        'margin_header' => 5,
        'margin_footer' => 5
    ]);

    $mpdf->SetProtection(array(
        'print'
    ));
    $mpdf->SetTitle(MAIN_TITLE);
    $mpdf->SetAuthor(MAIN_TITLE);
    $mpdf->SetWatermarkText("Active");
    $mpdf->showWatermarkText = false;
    $mpdf->watermark_font = 'DejaVuSansCondensed';
    $mpdf->watermarkTextAlpha = 0.1;
    $mpdf->SetDisplayMode('fullpage');

    $mpdf->WriteHTML($html);
    $mpdf->Output();
}

function prepareHead()
{
    return '<style>
body {font-family: sans-serif;
	font-size: 10pt;
}
p {	margin: 0pt; }
table.items {
	border: 0.1mm solid #000000;
}
td { vertical-align: top; }
.items td {
	border-left: 0.1mm solid #000000;
	border-right: 0.1mm solid #000000;
}
table thead td { background-color: #EEEEEE;
	text-align: center;
	border: 0.1mm solid #000000;
	font-variant: small-caps;
}
table tr td{
    border: 0.1mm solid #f2f2f2;
}
            
.items td.blanktotal {
	background-color: #EEEEEE;
	border: 0.1mm solid #000000;
	background-color: #FFFFFF;
	border: 0mm none #000000;
	border-top: 0.1mm solid #000000;
	border-right: 0.1mm solid #000000;
}
.items td.totals {
	text-align: right;
	border: 0.1mm solid #000000;
}
.items td.cost {
	text-align: "." center;
}
    </style>
';
}

function prepareBody($tenant, $trans, $id,$passengers,$customer)
{
    $html = '
    <!--mpdf
    '.prepareHeaderAndFooter().'
    <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
    <sethtmlpagefooter name="myfooter" value="on" />
    mpdf-->
    <table style="width:100%;border:none;font-family: sans;" cellpadding="5">
        <tr>
            <td style="width:50%;border:none">
                <table style="width:100%;border-collapse: collapse;font-family: sans;" cellpadding="5">
                    <tr>
                        <td colspan="2" style="text-align:left;font-weight:bold;background-color:#f2f2f2">
                            Invoice details
                        </td>
                    </tr>
                    <tr>
                        <td>Booking Date</td>
                        <td>' . $tenant[0]['bdate'] . '</td>
                    </tr>
                    <tr>
                        <td>Booking Reference</td>
                        <td>' . $tenant[0]['reference'] . '</td>
                    </tr>
                    <tr>
                        <td>Airlines</td>
                        <td>' . $tenant[0]['airlines'] . '</td>
                    </tr>
                    -<tr>
                        <td>Baggage <small> in Kg</small></td>
                        <td>' . $tenant[0]['baggage'] . '</td>
                    </tr>
                    <tr>
                        <td>Cancel Charge <small> in CHF</small></td>
                        <td>' . $tenant[0]['cancel_charge'] . '</td>
                    </tr>
                </table>
            </td>
            <td style="width:50%;border:none;padding-left:10%;padding-top:5%;">
                <div style="font-style: normal;">
                    ' . $customer[0]['first_name'] .' '. $customer[0]['last_name'] . ' <br>
                    ' . $customer[0]['address'] . '<br>
                    ' . $customer[0]['zip'] .' '. $customer[0]['city'] . '<br>
                    ' . $customer[0]['mobile'] . '<br>
                </div>
            </td>
        </tr>
    </table>
    
    <br>
    ';
    
    if($trans!=null){
        $html .= '<p><strong>Flight Details</strong></p>
    <table class="items" width="100%" style="font-size: 9pt;font-weight:bold; border-collapse: collapse; " cellpadding="8">
    <thead>
    <tr>
    <td>Start Date</td>
    <td>From</td>
    <td>To</td>
    <td>Land Date</td>
    </tr>
    </thead>
    <tbody>
    <!-- ITEMS HERE -->
    ';
        foreach ($trans as $tran) {
            $html .= '
				<tr>
					<td><img src="images/flight1.png">' . date('d-m-Y, l H:i', strtotime($tran["start_date"])) . '</td>
					<td>' . getSingleValue("select concat(city,' (',code,' )' ) from all_airports where code='".$tran['origin']."' ") . '</td>
                    <td>' . getSingleValue("select concat(city,' (',code,' )' ) from all_airports where code='".$tran['destination']."' ") . '</td>
					<td>' . date('d-m-Y, l H:i', strtotime($tran["land_date"])) . '</td>					
				</tr>';
        }
        $html .= '</tbody>
		</table><br>';
        
        $html .= '<p><strong>Passenger Details</strong></p><table width="100%" style="margin-top:10px;font-size: 9pt; border-collapse: collapse; " cellpadding="8">
    <thead>
    <tr>
    <td>Prefix</td>
    <td>Name</td>
    <td>#</td>
    <td>E-Ticket</td>
    <td>Price</td>
    </tr>
    </thead>
    <tbody>
    <!-- ITEMS HERE -->
    ';
        foreach ($passengers as $passenger) {
            $html .= '
				<tr>
					<td>' . $passenger['prefix'] . '</td>
					<td>' . $passenger['first_name']." ".$passenger['last_name'] . '</td>
                    <td>' . $passenger['extra'] . '</td>
					<td>' . $passenger['e_ticket_number'] . '</td>
                    <td>' . ($passenger['price'] + $passenger['ticket_charge'] + $passenger['visa_charge']) . '</td>
				</tr>';
        }
        $html .= '</tbody>
		</table>';
        
    }else{
        $html .= "No rows found";
    }
    
    $html .='<table width="100%" style="margin-top:10px;border-collapse: collapse;font-family: sans;" cellpadding="5">
    <tr>
        <td colspan="2" style="text-align:left;font-weight:bold;background-color:#f2f2f2">
            Payment details </td>
    </tr>
    <tr>
    <td>Total Amount</td>
    <td>'.$tenant[0]['total_price'].' <small>CHF</small></td>
    </tr>
    <tr>
    <td>Total Paid</td>
    <td>'.$tenant[0]['total_paid'].' <small>CHF</small></td>
    </tr>';
    
    
    if($tenant[0]['total_balance']==0){
        $html .=
        '<tr>
    <td>is Fully Paid ?</td>
    <td>Yes</td>
    </tr>';
    }else{
        $html .='<tr>
        <td>Payment Status</td>
        </td>
        <td>Unpaid</td>
        </tr>';
        $html .='<tr>
        <td>Balance</td>
        <td>'.$tenant[0]["total_balance"].'</td>
        </tr>';
    }
    $html .='</table>';
    
    
    
    return $html;
}

function prepareHeaderAndFooter(){
    return '
<htmlpageheader name="myheader">
	<table width="100%" cellpadding="5">
		<tr>
			<td width="30%" style="color:#0000BB;border:none">
				<span style="font-weight: bold; font-size: 14pt;"><font style="color:#e60000;">'.MAIN_TITLE_PART1.'</font> <font style="color:black;">'.MAIN_TITLE_PART2.'</font> </span>
				<br /><font style="color:#333333;">'.MAIN_ADDRESS_1.'<br />'.MAIN_ADDRESS_2.'</font><br />
				<font style="color:#333333;"><span style="font-family:dejavusanscondensed;">&#9742;</span> '.MAIN_CONTACT_NUMBER_1.'</font><br/>
                <font style="color:#333333;"><span style="font-family:dejavusanscondensed;">&#9742;</span> '.MAIN_CONTACT_NUMBER_2.'</font><br/>
				<font style="color:#333333;">'.MAIN_EMAIL.'</font><br/>
				<font style="color:#333333;"><span style="font-family:dejavusanscondensed;">&#174;</span>'.MAIN_WEBSITE_ADDRESS.'</font>
            </td>
            <td width="70%" style="border:none"><img style="float:right;margin-left:25%;" class="img-fluid" src="images/6.png">
            </td>
		</tr>
	</table>
</htmlpageheader>
<htmlpagefooter name="myfooter">
	<div style="border-top: 1px solid #000000; text-align: center; padding-top: 3mm; ">
        <span><font style="font-size:10pt;color:#e60000;">'.MAIN_TITLE_PART1.'</font> <font style="font-size:9pt;color:black;">'.MAIN_TITLE_PART2.'</font></span> 
        <span style="font-family:dejavusanscondensed;font-size:8pt">| '.MAIN_ADDRESS_1.' '.MAIN_ADDRESS_2.' |  &#9742; '.MAIN_CONTACT_NUMBER_1.' &#9742; '.MAIN_CONTACT_NUMBER_2.'
	</div>	
</htmlpagefooter>';
}