<?php
include 'session.php';

// Load autoloader (using Composer)
include_once '../../../vendor/autoload.php';


// $range = $_POST['date_range'];
// $ex = explode(' - ', $range);
// $from = date('Y-m-d', strtotime($ex[0]));
// $to = date('Y-m-d', strtotime($ex[1]));

$from = date('01-m-Y');
$to   = date('d-m-Y', strtotime('last day of this month'));

$from_title = date('01-m-Y');
$to_title = date('d-m-Y', strtotime('last day of this month'));

// $from_title = date('M d, Y', strtotime($ex[0]));
// $to_title = date('M d, Y', strtotime($ex[1]));


$money = 56.23654;

$sql = "SELECT *, SUM(num_hr) AS total_hr FROM attendance WHERE date BETWEEN '$from' AND '$to' ";

$query = $conn->query($sql);
$row = $query->fetch_assoc();
                            
//$gross = $row['rate'] * $row['total_hr'];
    

$pdf = new TCPDF();                 // create TCPDF object with default constructor args
$pdf->AddPage();                    // pretty self-explanatory
$content = '';  
    $content .= '
      <h2 align="center">Ehsan Software Employee Salary Info</h2>
      <h4 align="center">'.$from_title." - ".$to_title.'</h4>
      <table cellspacing="0" cellpadding="3">  
              <tr>  
                <td width="25%" align="right">Employee Name: </td>
                  <td width="25%"><b>Shobuj Islam</b></td>
          <td width="25%" align="right">Rate per Hour: </td>
                  <td width="25%" align="right">'.number_format($money, 2).'</td>
            </tr>
            <tr>
              <td width="25%" align="right">Employee ID: </td>
          <td width="25%">171101</td>   
          <td width="25%" align="right">Total Hours: </td>
          <td width="25%" align="right">'.number_format($money, 2).'</td> 
            </tr>
            <tr> 
              <td></td> 
              <td></td>
          <td width="25%" align="right"><b>Gross Pay: </b></td>
          <td width="25%" align="right"><b>'.number_format($money, 2).'</b></td> 
            </tr>
            <tr> 
              <td></td> 
              <td></td>
          <td width="25%" align="right">Deduction: </td>
          <td width="25%" align="right">'.number_format($money, 2).'</td> 
            </tr>
            <tr> 
              <td></td> 
              <td></td>
          <td width="25%" align="right">Cash Advance: </td>
          <td width="25%" align="right">'.number_format($money, 2).'</td> 
            </tr>
            <tr> 
              <td></td> 
              <td></td>
          <td width="25%" align="right"><b>Total Deduction:</b></td>
          <td width="25%" align="right"><b>'.number_format($money, 2).'</b></td> 
            </tr>
            <tr> 
              <td></td> 
              <td></td>
          <td width="25%" align="right"><b>Net Pay:</b></td>
          <td width="25%" align="right"><b>'.number_format($money, 2).'</b></td> 
            </tr>
          </table>
          <br><hr>
    ';
    $pdf->writeHTML($content);  
$pdf->Output('hello_world.pdf');    // send the file inline to the browser (default).

?>