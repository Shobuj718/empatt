<?php 

include_once '../includes/header.php'; 
include_once '../../../vendor/autoload.php';

use App\Admin\Employee\Employee;

$employee = new Employee();

$data = $employee->show_attend_leave_time();

?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php //include 'includes/navbar.php'; ?>
  <?php //include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Employee Working hour and amount details
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Attendance</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
            <?php  
                if($_SESSION['type'] == 'administrator'){
            ?>
              <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> New</a>
              <?php } else { ?>
                <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Not Allow</a>
                <?php } ?>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <th class="hidden"></th>
                  <th>Date</th>
                  <th>Employee ID</th>
                  <th>Name</th>
                  <th>Time In</th>
                  <th>Time Out</th>
                  <th>Total Hour</th>
                  <th>Total Amount</th>
                  <th>Action</th>
                </thead>
                <tbody>
                  <?php
                    /*$sql = "SELECT *, employees.employee_id AS empid, attendance.id AS attid FROM attendance LEFT JOIN employees ON employees.id=attendance.employee_id ORDER BY attendance.date DESC, attendance.time_in DESC";
                    $query = $conn->query($sql);
                    while($row = $query->fetch_assoc()){*/
                      foreach($data as $row){
                      $status = ($row['status']==1)?'<span class="label label-warning pull-right">ontime</span>':'<span class="label label-danger pull-right">late</span>';
                      //if($row['status'] ==)
                      $data = $row['num_hr'];
                      echo "
                        <tr>
                          <td class='hidden'></td>
                          <td>".date('M d, Y', strtotime($row['date']))."</td>
                          <td>".$row['empid']."</td>
                          <td>".$row['firstname'].' '.$row['lastname']."</td>
                          <td>".date('h:i A', strtotime($row['time_in'])).$status."</td>
                          <td>".date('h:i A', strtotime($row['time_out']))."</td>
                          <td>".$row['num_hr']."</td>
                          <td>".number_format($data * 10, 2)."</td>
                          ";
                           
                          ?>

                        <?php  
                          if($_SESSION['type'] == 'administrator'){
                      ?>
                          <td>
                            <a class='btn btn-success btn-sm btn-flat' href="view/admin/payroll/payslip.php?pay=<?php echo $row['employee_id']; ?>" target="_blank"  >Single pay slip</a>
                          </td>

                          <?php } else { ?>

                             <td>
                            <button class='btn btn-success btn-sm btn-flat edit' ><i class='fa fa-edit'></i> Single pay slip</button>
                          </td>
                        </tr>
                    <?php } }  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>   
  </div>
    
<?php include_once '../includes/footer.php'; ?>
  <?php //include 'includes/attendance_modal.php'; ?>
</div>
<?php //include 'includes/scripts.php'; ?>
<script>
$(function(){
  $('.edit').click(function(e){
    e.preventDefault();
    $('#edit').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });

  $('.delete').click(function(e){
    e.preventDefault();
    $('#delete').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });
});

function getRow(id){
  $.ajax({
    type: 'POST',
    url: 'attendance_row.php',
    data: {id:id},
    dataType: 'json',
    success: function(response){
      $('#datepicker_edit').val(response.date);
      $('#attendance_date').html(response.date);
      $('#edit_time_in').val(response.time_in);
      $('#edit_time_out').val(response.time_out);
      $('#attid').val(response.attid);
      $('#employee_name').html(response.firstname+' '+response.lastname);
      $('#del_attid').val(response.attid);
      $('#del_employee_name').html(response.firstname+' '+response.lastname);
    }
  });
}
</script>










<?php
// Load autoloader (using Composer)

/*
include_once '../../../vendor/autoload.php';

$from_title = date('M d, Y');
$to_title = date('M d, Y');
$money = 56.23654;

$pdf = new TCPDF();                 
$pdf->AddPage();                    // pretty self-explanatory
$content = '';  
    $content .= '
      <h2 align="center">Ehsan Software Employee Salary Info</h2>
      	<h4 align="center">'.$from_title." - ".$to_title.'</h4>
      	<table border="1" cellspacing="0" cellpadding="3">  
           <tr>  
           		<th width="30%" align="center"><b>Employee Name</b></th>
                <th width="30%" align="center"><b>Employee ID</b></th>
				<th width="20%" align="center"><b>Total Hour</b></th> 
				<th width="20%" align="center"><b>Total Money</b></th> 
           </tr>  
           <tr>  
           		<td width="30%" align="center">Shobuj Mia</td>
                <td width="30%" align="center">171101</td>
				<td width="20%" align="center">3 Hours</td> 
				<td width="20%" align="center">1000 tk.</td> 
           </tr>
           <tr>  
           		<td width="30%" align="center">Shohel Rana</td>
                <td width="30%" align="center">171101</td>
				<td width="20%" align="center">3 Hours</td> 
				<td width="20%" align="center">1000 tk.</td> 
           </tr>  
      
			<tr>
				<td colspan="2" align="right"><b>Total</b></td>
				<td align="right"><b>'.number_format($money, 3)." hours ".'</b></td>
				<td align="right"><b>'.number_format($money, 2)." tk. ".'</b></td>
			</tr>
			
		';
    $content .= '</table>';  
    $pdf->writeHTML($content);  
$pdf->Output('hello_world.pdf');    // send the file inline to the browser (default).


*/
?>