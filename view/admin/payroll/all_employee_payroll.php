<?php

include 'session.php';

// Load autoloader (using Composer)
include_once '../../../vendor/autoload.php';


	function generateRow($from, $to, $conn){
		$contents = '';
	 	
	$sql = "SELECT *, SUM(num_hr) AS total_hr, employees.employee_id AS employee FROM attendance LEFT JOIN employees ON employees.id=attendance.employee_id  WHERE date BETWEEN '$from' AND '$to' GROUP BY attendance.employee_id ORDER BY  employees.firstname ASC";

		$query = $conn->query($sql);
		$total = 0;
		while($row = $query->fetch_assoc()){
            
              if($row['employee'] == 171101){
                  $rate = 10;
              }elseif($row['employee'] == 181102){
                  $rate = 20;
              }elseif($row['employee'] == 181103){
                  $rate = 30;
              }elseif($row['employee'] == 181104){
                  $rate = 40;
              }else{
                  $rate = 100;
              }

			$gross = $rate * $row['total_hr'];
      		$net = $gross;

			$total += $net;
			$contents .= '
			<tr>
				<td>'.$row['firstname'].' '.$row['lastname'].'</td>
				<td>'.$row['employee_id'].'</td>
				<td align="right">'.number_format($net, 2).'</td>
			</tr>
			';
		}

		$contents .= '
			<tr>
				<td colspan="2" align="right"><b>Total</b></td>
				<td align="right"><b>'.number_format($total, 2).'</b></td>
			</tr>
		';
		return $contents;
	}
		
	$range = $_POST['date_range'];
	$ex = explode(' - ', $range);
	$from = date('Y-m-d', strtotime($ex[0]));
	$to = date('Y-m-d', strtotime($ex[1]));

	

	$from_title = date('M d, Y', strtotime($ex[0]));
	$to_title = date('M d, Y', strtotime($ex[1]));

	//require_once('../tcpdf/tcpdf.php');  
    $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
    $pdf->SetCreator(PDF_CREATOR);  
    $pdf->SetTitle('Payroll: '.$from_title.' - '.$to_title);  
    $pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
    $pdf->SetDefaultMonospacedFont('helvetica');  
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
    $pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);  
    $pdf->setPrintHeader(false);  
    $pdf->setPrintFooter(false);  
    $pdf->SetAutoPageBreak(TRUE, 10);  
    $pdf->SetFont('helvetica', '', 11);  
    $pdf->AddPage();  
    $content = '';  
    $content .= '
      	<h2 align="center">Ehsan Software</h2>
      	<h4 align="center">'.$from_title." - ".$to_title.'</h4>
      	<table border="1" cellspacing="0" cellpadding="3">  
           <tr>  
           		<th width="40%" align="center"><b>Employee Name</b></th>
                <th width="30%" align="center"><b>Employee ID</b></th>
				<th width="30%" align="center"><b>Total Amount</b></th> 
           </tr>  
      ';  
    $content .= generateRow($from, $to, $conn);  
    $content .= '</table>';  
    $pdf->writeHTML($content);  
    $pdf->Output('all_employee_payroll.pdf', 'I');

?>