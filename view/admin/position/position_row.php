<?php 
	
	include_once '../../../vendor/autoload.php';

	use App\Admin\Employee\Employee;

	$employee = new Employee();
	$id = $_POST['id'];

	$data = $employee->edit_position($id);
	echo json_encode($data);
	







/*
<?php 
	include '../payroll/session.php';

	if(isset($_POST['id'])){
		$id = $_POST['id'];

		$sql = "SELECT * FROM position WHERE id = '$id'";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		echo json_encode($row);
	}
?>*/