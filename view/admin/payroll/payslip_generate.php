<?php
	include 'session.php';
	include_once '../../../vendor/autoload.php';
    
    $timezone = 'Asia/Dhaka';
    date_default_timezone_set($timezone);

	$range = $_POST['date_range'];
	$ex = explode(' - ', $range);
	$from = date('Y-m-d', strtotime($ex[0]));
	$to = date('Y-m-d', strtotime($ex[1]));

	
	$from_title = date('M d, Y', strtotime($ex[0]));
	$to_title = date('M d, Y', strtotime($ex[1]));

    $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
    $pdf->SetCreator(PDF_CREATOR);  
    $pdf->SetTitle('Payslip: '.$from_title.' - '.$to_title);  
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
    $contents = '';

	$sql = "SELECT *, SUM(num_hr) AS total_hr, employees.employee_id AS employee FROM attendance LEFT JOIN employees ON employees.id=attendance.employee_id  WHERE date BETWEEN '$from' AND '$to' GROUP BY attendance.employee_id ORDER BY  employees.firstname ASC";

	$query = $conn->query($sql);
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

		$contents .= '
			<h2 align="center">Ehsan Software</h2>
			<h4 align="center">'.$from_title." - ".$to_title.'</h4>
			<table cellspacing="0" cellpadding="3">  
    	       	<tr>  
            		<td width="25%" align="right">Employee Name: </td>
                 	<td width="25%"><b>'.$row['firstname']." ".$row['lastname'].'</b></td>
				 	<td width="25%" align="right">Rate per Hour: </td>
                 	<td width="25%" align="right">'.number_format($rate, 2).'</td>
    	    	</tr>
    	    	<tr>
    	    		<td width="25%" align="right">Employee ID: </td>
				 	<td width="25%">'.$row['employee'].'</td>   
				 	<td width="25%" align="right">Total Hours: </td>
				 	<td width="25%" align="right">'.number_format($row['total_hr'], 2).'</td> 
    	    	</tr>
    	    	<tr> 
    	    		<td></td> 
    	    		<td></td>
				 	<td width="25%" align="right"><b>Gross Pay: </b></td>
				 	<td width="25%" align="right"><b>'.number_format(($rate*$row['total_hr']), 2).'</b></td> 
    	    	</tr>
    	    	
    	    	
    	    	<tr> 
    	    		<td></td> 
    	    		<td></td>
				 	<td width="25%" align="right"><b>Net Pay:</b></td>
				 	<td width="25%" align="right"><b>'.number_format($net, 2).'</b></td> 
    	    	</tr>
    	    </table>
    	    <br><hr>
		';
	}
    $pdf->writeHTML($contents);  
    $pdf->Output('employee_payslip.pdf', 'I');

?>