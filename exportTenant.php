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
    $tenant = getFetchArray("select t.*,a.apartment_name,a.rent, a.advance, h.house_name, h.address from tenants t, apartments a, houses h where a.house_id=h.id and t.apartment_id=a.id and t.id = " . $id);
    $trans = getFetchArray("select * from payments where tenant_id = " . $id . " order by id desc");

    $html = '
<html>
	<head>' . prepareHead() . '
	</head>
	<body>';
    $html .= prepareBody($tenant, $trans, $id);
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
        'margin_left' => 20,
        'margin_right' => 15,
        'margin_top' => 48,
        'margin_bottom' => 25,
        'margin_header' => 10,
        'margin_footer' => 10
    ]);

    $mpdf->SetProtection(array(
        'print'
    ));
    $mpdf->SetTitle("Saran Solutions - Tenant");
    $mpdf->SetAuthor("Saran Solutions");
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

function prepareBody($tenant, $trans, $id)
{
    $html = '
    <!--mpdf
    '.prepareHeaderAndFooter().'
    <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
    <sethtmlpagefooter name="myfooter" value="on" />
    mpdf-->
    <div style="text-align: right">Exported on: ' . date("d-m-Y") . '</div>
    <br>
    <table width="100%" style="border-collapse: collapse;font-family: sans;" cellpadding="5">
    <tr>
    <td colspan="2" style="text-align:left;font-weight:bold;background-color:#f2f2f2">
    Tenant details
    </td>
    </tr>
    <tr>
    <td>Name</td>
    <td>' . $tenant[0]['first_name'] . ' ' . $tenant[0]['last_name'] . '</td>
    </tr>
    <tr>
    <td>Mobile 1</td>
    <td>' . $tenant[0]['mobile_no_1'] . '</td>
    </tr>
    <tr>
    <td>Mobile 2</td>
    <td>' . $tenant[0]['mobile_no_2'] . '</td>
    </tr>
    <tr>
    <td>Occupied Since</td>
    <td>' . $tenant[0]['occupied_since'] . '</td>
    </tr>
    <tr>
    <td>Aadhar Number</td>
    <td>' . $tenant[0]['aadhar_card_no'] . '</td>
    </tr>
    <tr>
    <td>Occupation</td>
    <td>' . $tenant[0]['occupation'] . '</td>
    </tr>
    <tr>
    <td colspan="2" style="text-align:left;font-weight:bold;background-color:#f2f2f2">
    House details
    </td>
    </tr>
    <tr>
    <td>Apartment Name</td>
    <td>' . $tenant[0]['apartment_name'] . '</td>
    </tr>
    <tr>
    <td>Rent </td>
    <td>' . $tenant[0]['rent'] . ' &#x20b9;</td>
    </tr>
    <tr>
    <td>Advance </td>
    <td>' . $tenant[0]['advance'] . ' &#x20b9;</td>
    </tr>
    <tr>
    <td>House Name</td>
    <td>' . $tenant[0]['house_name'] . '</td>
    </tr>
    <tr>
    <td>Address</td>
    <td>' . $tenant[0]['address'] . '</td>
    </tr>
    </table>
    
    
    <br>
    <div style="font-size: 12pt;text-align: left;text-weight:bold;">Transaction details</div>';
    
    if($trans!=null){
        $html .= '<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
    <thead>
    <tr>
    <td>Amount</td>
    <td>Paid Date</td>
    <td>#</td>
    <td>Details</td>
    <td>Balance</td>
    <td>#</td>
    </tr>
    </thead>
    <tbody>
    <!-- ITEMS HERE -->
    ';
        foreach ($trans as $tran) {
            $actionSym = "-";
            $rowStyle = 'style="background-color: #ccffcc;"';
            $isFullyPaid = '<span style="color:green;">&#9786;</span>';
            if ($tran['fully_paid'] != "Yes" && $tran['action'] != "Added") {
                $isFullyPaid = '<span style="color:red;;">&#9785;</span>';
            }
            if ($tran['action'] == "Added") {
                $rowStyle = 'style="background-color: #cceeff;"';
                $actionSym = "+";
                $isFullyPaid = '';
            }
            $html .= '
				<tr ' . $rowStyle . '>
					<td>' . $tran['amount'] . ' &#x20b9;</td>
					<td>' . date("d-m-Y", strtotime($tran['paid_date'])) . '</td>
                    <td>' . $actionSym . '</td>
					<td>' . $tran['payment_details'] . '</td>
					<td>' . $tran['balance'] . '</td>
					<td>' . $isFullyPaid . '</td>
				</tr>';
        }
        $html .= '</tbody>
		</table>';
        
    }else{
        $html .= "No rows found";
    }
    
    return $html;
}

function prepareHeaderAndFooter(){
    return '
<htmlpageheader name="myheader">
	<table width="100%" cellpadding="5">
		<tr>
			<td width="100%" style="color:#0000BB;">
				<span style="font-weight: bold; font-size: 14pt;">Saran Solutions</span>
				<br />25, Keelaputhur Mainroad<br />Palakkarai<br />Trichy - 620001<br />
				<span style="font-family:dejavusanscondensed;">&#9742;</span> +91 74 88 444 76<br/>
				<span style="font-family:dejavusanscondensed;">&#174;</span>www.saransolutions.ch
            </td>
		</tr>
	</table>
</htmlpageheader>
<htmlpagefooter name="myfooter">
	<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
    Page {PAGENO} of {nb}
	</div>
	<div style=" font-weight:bold;font-size: 7pt; text-align: center; padding-top: 3mm; ">
    Saran Solutions
	</div>
</htmlpagefooter>';
}