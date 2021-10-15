<?php

require_once __DIR__ . '/../vendor/autoload.php';
include 'includes/cons.php';

if(isset($_GET['id']) && strlen($_GET['id']) !=0 ){
    $result=array();
    $result['id']=$_GET['id'];
    $order=getFetchArray("select * from orders where id =".$_GET['id'])[0];
    $customers=getFetchArray("select * from customers where id=".$order["customer_id"])[0];
    $customer=$customers["first_name"]." ".$customers["last_name"]."<br>".$customers["address"]."<br>".$customers["city"].$customers["zip"]."<br>".$customers["mobile"]."<br>";
    $result["customer"]=$customer;
    $pnames=getFetchArray("select * from passengers where parent_id = ".$order["id"]);
    $names="";
    foreach ($pnames as $pname){
        $names.=$pname["prefix"]."."." ".$pname["first_name"]." ".$pname["last_name"]."<br>";
    }
    $result['pnames'] = $names;
    //$invoice=getInvoiceDetailsForTicketPDF($result['inv_id']);
    $result['breference']=$order['reference'];
    $result['cancel_charge']=$order['cancel_charge'];
    $result['baggage']=$order['baggage'];
    $result['image']='<p style="padding:0.3em;margin:0%;"><img src="images/electicket.png" /></p>';
    
    printPDF($result);
}

function printPDF($result){
    
    #$mpdf=new mPDF('win-1252','A4','','',10,10,22,1,5,1);
    $mpdf = new \Mpdf\Mpdf([
        'margin_left' => 20,
        'margin_right' => 15,
        'margin_top' => 48,
        'margin_bottom' => 25,
        'margin_header' => 10,
        'margin_footer' => 10
    ]);
    
    $html = '
<html>
'.PDF_HEAD_TEMPLATE.'
<body>
<!--mpdf
<htmlpageheader name="myheader">
<table width="100%" style="margin-left:78%;"><tr>
<td style="float:right;width:50%;">
			<span style="float:right;font-size:14pt;">
				<img src="'.MAIN_LOGO_PATH.'" style="float:right;"/>
			</span>
		</td>
</tr></table>
</htmlpageheader>
    
<htmlpagefooter name="myfooter">
	<div>
		<p style="margin-left:10%;width:75%;font-size: 9pt;text-align:center;font-style:italic; padding-top: 1mm; ">'.FOOT_MSG.'</p>
	</div>
	'.PDF_FOOTER_SARAN_SOLUTIONS.'
</htmlpagefooter>
	    
	    
<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->
	    
'.$result['image'].'
    
<table style="width:100%;line-height:120%;">
	<tr>
		<td style="width:50%;">
		<p>Passenger Name</p><br>
		'.$result['pnames'].'
		</td>
		<td style="width:12%;">
		</td>
		<td style="width:38%;">
		<br>
		<!-- Customer Details -->
		<p	>
			'.$result['customer'].'
		</p>
		<!-- Customer Details -->
		</td>
	</tr>
</table>
<br>
			    
<br><br><br>
Booking Reference : '.$result['breference'].'
'.getRouteDetailsByInvId($result['inv_id']).'
    
'.getTicketFooterPDF($result['cancel_charge'], $result['baggage']).'
    
</body>
</html>
';
    //$mpdf->SetJS('this.print();');
    $mpdf->WriteHTML($html);
    
    $mpdf->SetProtection(array('print'));
    $mpdf->SetTitle("Acme Trading Co. - Invoice");
    $mpdf->SetAuthor("Acme Trading Co.");
    $mpdf->SetWatermarkText("Paid");
    $mpdf->showWatermarkText = true;
    $mpdf->watermark_font = 'DejaVuSansCondensed';
    $mpdf->watermarkTextAlpha = 0.1;
    $mpdf->SetDisplayMode('fullpage');
    
    
    #$mpdf->Output("KT_FLIGHT_TKT_".$result['inv_id'].".pdf", 'I'); exit;
    $mpdf->Output();
    exit;
}

function getRouteDetailsByInvId($id){
    
    $data=null;
    $rowCount=1;
    $rows=getFetchArray("select * from routes where parent_id=".$id);
    if(count($rows) == 0){return;}
    foreach ($rows as $result)
    {
        
        $origin_country= $result['origin'];
        $desti_country= $result['destination'];
        
        
        $d_time= $result['d_time'];
        $a_time= $result['a_time'];
        $a_date= $result['a_date'];
        $a_date = date('d F', strtotime($a_date));
        //$a_date = date('d M Y', strtotime($a_date));
        
        $data =$data.
        getDepartureArriavalDates($d_date, $a_date, $d_time, $a_time).
        
        "<table style='width:100%;'>
<tr style='padding:0.3em'>
		<td style='width:20%;height:5em;background:#EDEDF3;padding-left:1em;'>
			<p>
				<font style='font-weight:bold;'>
				".$al_full_name."
				</font>
				<br>
				".$al_name." ".$al_number."
			</p>
		</td>
		<td style='width:40%;background:#EDEDF3;padding-left:1em;'>
			<p>
				<font style='font-size:14px;'>".$origin."</font><br>
					".$origin_city.",<br>
					".$origin_country."
			</p>
					    
		</td>
		<td style='width:40%;background:#EDEDF3;padding-left:1em;'>
			<p><font style='font-size:14px;'>".$desti."</font><br>
				".$desti_city.",<br> ".$desti_country."
			</p>
				    
		</td>
	</tr>
</table>
<br>
		";
        $rowCount++;
    }
    return $data;
}