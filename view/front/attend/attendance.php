<?php

/*

	include_once '../../../vendor/autoload.php';

	use App\Admin\Empattend\Empattend;

	$empatt = new Empattend();

	$output = $empatt->set($_POST)->attendEmployee();

		echo json_encode($output);

	// echo "<pre>";
	// var_dump($output);
	//die();
*/


	if(isset($_POST['employee'])){
		$output = array('error'=>false);

		include 'conn.php';
		include 'timezone.php';

		$employee = $_POST['employee'];
		$status = $_POST['status'];

		$sql = "SELECT * FROM employees WHERE employee_id = '$employee'";
		$query = $conn->query($sql);

		if($query->num_rows > 0){
			$row = $query->fetch_assoc();
			$id = $row['id'];

			$date_now = date('Y-m-d');

			if($status == 'in'){
				/*$sql = "SELECT * FROM attendance WHERE employee_id = '$id' AND date = '$date_now' AND time_in IS NOT NULL";
				$query = $conn->query($sql);
				if($query->num_rows > 0){
					$output['error'] = true;
					$output['message'] = 'You have timed in for today';
				}
				else{*/
					//updates
					$sched = $row['schedule_id'];
					$lognow = date('H:i:s');
					$sql = "SELECT * FROM schedules WHERE id = '$sched'";
					$squery = $conn->query($sql);
					$srow = $squery->fetch_assoc();
					$logstatus = ($lognow > $srow['time_in']) ? 0 : 1;
					//
					$sql = "INSERT INTO attendance (employee_id, date, time_in, status) VALUES ('$id', '$date_now', NOW(), '$logstatus')";
					if($conn->query($sql)){
						$output['message'] = 'Time in: '.$row['firstname'].' '.$row['lastname'];
					}
					else{
						$output['error'] = true;
						$output['message'] = $conn->error;
					}
				//}
			}
			else{
				$sql = "SELECT *, attendance.id AS uid FROM attendance LEFT JOIN employees ON employees.id=attendance.employee_id WHERE attendance.employee_id = '$id' AND date = '$date_now'";
				$query = $conn->query($sql);
				if($query->num_rows < 1){
					$output['error'] = true;
					$output['message'] = 'Cannot Timeout. No time in.';
				}
				else{
					$row = $query->fetch_assoc();
					/*if($row['time_out'] != '00:00:00'){
						$output['error'] = true;
						$output['message'] = 'You have timed out for today';
					}*/
					//else{
						
						//$sql = "UPDATE attendance SET time_out = NOW() WHERE id = '".$row['uid']."'";

					$stmt = "select * from attendance where employee_id='$id' and date='$date_now' order by time_in DESC limit 1 ";
					$stmt2 = $conn->query($stmt);
					$stmt3 = $stmt2->fetch_assoc();
					$time_in_last = $stmt3['time_in'];

					$stmt = "select * from attendance where employee_id='$id' and date='$date_now' and time_in='$time_in_last' ";
					$data = $conn->query($stmt);
					$value = $data->fetch_assoc();
					$emp_id = $value['employee_id'];
					
					
					//$time_in_last = '09:34:35';
					$sql = "UPDATE attendance SET time_out = NOW() WHERE employee_id='$id' and time_in='$time_in_last' ";

						$conn->query($sql);
						/*if($row['time_out'] != '00:00:00'){
							$output['message'] = 'Time out: '.$row['firstname'].' '.$row['lastname'];*/

							//$sql = "SELECT * FROM attendance WHERE employee_id='$id' and time_in='$time_in_last' ";

							$sql = "SELECT * FROM attendance WHERE employee_id = '$emp_id' and time_in='$time_in_last' ";
							$query = $conn->query($sql);
							$urow = $query->fetch_assoc();

							$time_in = $urow['time_in'];
							$time_out = $urow['time_out'];

							/*$sql = "SELECT * FROM employees LEFT JOIN schedules ON schedules.id=employees.schedule_id WHERE employees.id = '$id'";
							$query = $conn->query($sql);
							$srow = $query->fetch_assoc();

							if($srow['time_in'] > $urow['time_in']){
								$time_in = $srow['time_in'];
							}

							if($srow['time_out'] < $urow['time_in']){
								$time_out = $srow['time_out'];
							}
*/
							$time_in = new DateTime($time_in);
							$time_out = new DateTime($time_out);
							$interval = $time_in->diff($time_out);
							$hrs = $interval->format('%h');
							$mins = $interval->format('%i');
							$mins = $mins/60;
							$int = $hrs + $mins;
							if($int > 4){
								$int = $int - 1;
							}

							$sql = "UPDATE attendance SET num_hr = '$int' WHERE employee_id='$id' and time_in='$time_in_last' ";
							//$sql = "UPDATE attendance SET SET num_hr = '$int' WHERE employee_id='$id' and time_in='$time_in_last' ";

							$conn->query($sql);
							
							if($urow['time_out'] != '00:00:00'){
								$output['message'] = 'Time out: '.$row['firstname'].' '.$row['lastname'];
						}
						else{
							$output['error'] = true;
							$output['message'] = $conn->error;
						}
					//}
					
				}
			}
		}
		else{
			$output['error'] = true;
			$output['message'] = 'Employee ID not found';
		}
		
	}
	
	echo json_encode($output);

?>