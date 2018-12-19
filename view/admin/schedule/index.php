<?php 
  include_once '../includes/header.php';
  include_once '../../../vendor/autoload.php';

  use App\Admin\Schedule\Schedule;

  $schedule = new Schedule();
  $data = $schedule->show_schedule();

 ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Schedules
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Employees</li>
        <li class="active">Schedules</li>
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
                  <th>Time In</th>
                  <th>Time Out</th>
                  <th>Action</th>
                </thead>
                <tbody>
                  <?php
                   /* $sql = "SELECT * FROM schedules";
                    $query = $conn->query($sql);
                    while($row = $query->fetch_assoc()){*/
                      foreach($data as $row){
                      echo "
                        <tr>
                          <td>".date('h:i A', strtotime($row['time_in']))."</td>
                          <td>".date('h:i A', strtotime($row['time_out']))."</td>
                         ";
                           
                          ?>

                        <?php  
                          if($_SESSION['type'] == 'administrator'){
                      ?>
                          <td>
                            <button class='btn btn-success btn-sm btn-flat edit' data-id='".$row['attid']."'><i class='fa fa-edit'></i> Edit</button>
                            <button class='btn btn-danger btn-sm btn-flat delete' data-id='".$row['attid']."'><i class='fa fa-trash'></i> Delete</button>
                          </td>

                          <?php } else { ?>

                             <td>
                            <button class='btn btn-success btn-sm btn-flat edit' ><i class='fa fa-edit'></i> Not Allow</button>
                            <button class='btn btn-danger btn-sm btn-flat delete' ><i class='fa fa-trash'></i> Not Allow</button>
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
    
  <?php include '../includes/footer.php'; ?>
  <?php //include 'includes/schedule_modal.php'; ?>
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
    url: 'schedule_row.php',
    data: {id:id},
    dataType: 'json',
    success: function(response){
      $('#timeid').val(response.id);
      $('#edit_time_in').val(response.time_in);
      $('#edit_time_out').val(response.time_out);
      $('#del_timeid').val(response.id);
      $('#del_schedule').html(response.time_in+' - '+response.time_out);
    }
  });
}
</script>
