<?php include_once 'view/front/includes/header.php'; ?>

<style>
  .login-page{
     background-image: url("assets/front/imges/3.jpg");
    
     opacity: 0.8;
     hover:0.1;
      }
        .login-box{
          font-weight:0;
          background-color:#fff;
      }
      .login-box-body{
          background-color:#fff;
      }
</style>

<body class="hold-transition login-page">
<div class="login-box">
  	<div class="login-logo">
  		<p style="color:#000;" id="date"></p>
      <p id="time" class="bold"></p>
  	</div>
  
  	<div class="login-box-body">
    	<h4 style="color:blue;font-size:25px;" class="login-box-msg">Enter Employee ID</h4>
      <?php 

   /*  date_default_timezone_set("Asia/Dhaka");
      $attend_time  =  date("h:i:a");
      $attend_date  = date('Y-m-d');
      $year         = date('Y');

      echo date('G');
      $hour = date('G');
      $min = date('i');
      echo $hour." ".$min;
*/

      //$sd = NOW();
      //echo $sd;
       ?>
    	<form id="attendance">
          <div class="form-group">
            <select class="form-control" name="status">
              <option value="in">Time In</option>
              <option value="out">Time Out</option>
            </select>
          </div>
      		<div class="form-group has-feedback">
        		<input type="text" class="form-control input-lg" id="employee" name="employee" placeholder="Enter employee id..." required>
        		<span class="glyphicon glyphicon-calendar form-control-feedback"></span>
      		</div>
      		<div class="row">
    			<div class="col-xs-4">
          			<button type="submit" class="btn btn-primary btn-block btn-flat" name="signin"><i class="fa fa-sign-in"></i> Sign In</button>
        		</div>
      		</div>
    	</form>
  	</div>
		<div class="alert alert-success alert-dismissible mt20 text-center" style="display:none;">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <span class="result"><i class="icon fa fa-check"></i> <span class="message"></span></span>
    </div>
		<div class="alert alert-danger alert-dismissible mt20 text-center" style="display:none;">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <span class="result"><i class="icon fa fa-warning"></i> <span class="message"></span></span>
    </div>
  		
</div>

<script type="text/javascript">
$(function() {
  var interval = setInterval(function() {
    var momentNow = moment();
    $('#date').html(momentNow.format('dddd').substring(0,3).toUpperCase() + ' - ' + momentNow.format('MMMM DD, YYYY'));  
    $('#time').html(momentNow.format('hh:mm:ss A'));
  }, 100);

  $('#attendance').submit(function(e){
    e.preventDefault();
    var attendance = $(this).serialize();
    $.ajax({
      type: 'POST',
      url: 'view/front/attend/attendance.php',
      data: attendance,
      dataType: 'json',
      success: function(response){
        if(response.error){
          $('.alert').hide();
          $('.alert-danger').show();
          $('.message').html(response.message);
        }
        else{
          $('.alert').hide();
          $('.alert-success').show();
          $('.message').html(response.message);
          $('#employee').val('');
        }
      }
    });
  });
    
});
</script>

<?php
	
  include_once 'view/front/includes/footer.php'; 

 ?>
