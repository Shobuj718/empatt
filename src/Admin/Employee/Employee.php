<?php 

namespace App\Admin\Employee;

if(!isset($_SESSION)){
	session_start();
}

use App\Connection;
use PDO;
use PDOException;

class Employee extends Connection
{

	public function set($data = array()){

		if(array_key_exists('id', $data)){
			$this->id = $data['id'];
		}
		if(array_key_exists('firstname', $data)){
			$this->firstname = $data['firstname'];
		}
		if(array_key_exists('lastname', $data)){
			$this->lastname = $data['lastname'];
		}
		if(array_key_exists('address', $data)){
			$this->address = $data['address'];
		}
		if(array_key_exists('contact', $data)){
			$this->contact = $data['contact'];
		}
		if(array_key_exists('birthdate', $data)){
			$this->birthdate = $data['birthdate'];
		}
		if(array_key_exists('gender', $data)){
			$this->gender = $data['gender'];
		}
		if(array_key_exists('position', $data)){
			$this->position = $data['position'];
		}
		if(array_key_exists('schedule', $data)){
			$this->schedule = $data['schedule'];
		}
		if(array_key_exists('employee_id', $data)){
			$this->employee_id = $data['employee_id'];
		}
		if(array_key_exists('image', $data)){
			$this->image = $data['image'];
		}
		if(array_key_exists('name', $data)){
			$this->name = $data['name'];
		}
		if(array_key_exists('description', $data)){
			$this->description = $data['description'];
		}
		if(array_key_exists('rate', $data)){
			$this->rate = $data['rate'];
		}
		if(array_key_exists('employee_id', $data)){
			$this->employee_id = $data['employee_id'];
		}


		return $this;
	}

	 //select data from database
    public function time_in_last($query){
        $result =$this->con->prepare($query);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }

	//insert employee data 
	public function insert_employee(){
		try {

			$stmt = $this->con->prepare("insert into employees(employee_id, firstname, lastname, address, birthdate, contact_info, gender, position_id, schedule_id, photo, created_on ) values(:employee_id, :firstname, :lastname, :address, :birthdate, :contact_info, :gender, :position_id, :schedule_id, :photo, NOW() ) ");

			$stmt->bindValue(':employee_id', $this->employee_id, PDO::PARAM_STR);
			$stmt->bindValue(':firstname', $this->firstname, PDO::PARAM_STR);
			$stmt->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
			$stmt->bindValue(':address', $this->address, PDO::PARAM_STR);
			$stmt->bindValue(':birthdate', $this->birthdate, PDO::PARAM_STR);
			$stmt->bindValue(':contact_info', $this->contact, PDO::PARAM_STR);
			$stmt->bindValue(':gender', $this->gender, PDO::PARAM_STR);
			$stmt->bindValue(':position_id', $this->position, PDO::PARAM_STR);
			$stmt->bindValue(':schedule_id', $this->schedule, PDO::PARAM_STR);
			$stmt->bindValue(':photo', $this->image, PDO::PARAM_STR);
			$stmt->execute();

			if($stmt){
				$_SESSION['success'] = "Employee Added Successfully";
				echo "<script>window.location='index.php'</script>";
			}

			
		} catch (PDOException $e) {
			echo "Error: ".$e->getMessage."<br>";
			die();
		}
	}



	//insert position data 
	public function insert_position(){
		try {

			$stmt = $this->con->prepare("insert into position(name, employee_id, description, rate ) values(:name, :employee_id, :description, :rate ) ");

			$stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
			$stmt->bindValue(':employee_id', $this->employee_id);
			$stmt->bindValue(':description', $this->description, PDO::PARAM_STR);
			$stmt->bindValue(':rate', $this->rate);
			$stmt->execute();

			if($stmt){
				$_SESSION['success'] = "Position insert Successfully";
				echo "<script>window.location='index.php'</script>";
			}
			
		} catch (PDOException $e) {
			echo "Error: ".$e->getMessage."<br>";
			die();
		}
	}


	//edit employee
	public function edit_employee($id){
		try {

			$stmt = $this->con->prepare("SELECT *, employees.id as empid FROM employees LEFT JOIN position ON position.id=employees.position_id LEFT JOIN schedules ON schedules.id=employees.schedule_id WHERE employees.id = '$id'");
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_ASSOC);
			//echo json_encode($stmt);
			
		} catch (PDOException $e) {
			echo "Error: ".$e->getMessage()."<br>";
			die();
		}
	}
	//edit position
	public function edit_position($id){
		try {

			$stmt = $this->con->prepare("SELECT * FROM position WHERE id = '$id'");
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_ASSOC);
			//echo json_encode($stmt);
			
		} catch (PDOException $e) {
			echo "Error: ".$e->getMessage()."<br>";
			die();
		}
	}

	//update employee
	public function employee_update(){
		try {

			$stmt = $this->con->prepare("update employees set 
				firstname=:firstname,
				lastname=:lastname,
				address=:address,
				birthdate=:birthdate,
				contact_info=:contact_info,
				gender=:gender,
				position_id=:position_id,
				schedule_id=:schedule_id
				where id=:id
				");
			$stmt->bindValue(':firstname', $this->firstname, PDO::PARAM_STR);
			$stmt->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
			$stmt->bindValue(':address', $this->address, PDO::PARAM_STR);
			$stmt->bindValue(':birthdate', $this->birthdate, PDO::PARAM_STR);
			$stmt->bindValue(':contact_info', $this->contact, PDO::PARAM_STR);
			$stmt->bindValue(':gender', $this->gender, PDO::PARAM_STR);
			$stmt->bindValue(':position_id', $this->position, PDO::PARAM_STR);
			$stmt->bindValue(':schedule_id', $this->schedule, PDO::PARAM_STR);
			$stmt->bindValue(':id', $this->id, PDO::PARAM_STR);
			$stmt->execute();

			if($stmt){
				$_SESSION['success'] = "Employee Updated Successfully";
				echo "<script>window.location='index.php'</script>";
			}
			
		} catch (PDOException $e) {
			echo "Error: ".$e->getMessage()."<br>";
			die();
		}
	}
//update position
	public function position_update(){
		try {

			$stmt = $this->con->prepare("update position set 
				name=:name,
				description=:description,
				rate=:rate,
				employee_id=:employee_id
				where id=:id
				");
			$stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
			$stmt->bindValue(':description', $this->description, PDO::PARAM_STR);
			$stmt->bindValue(':rate', $this->rate, PDO::PARAM_STR);
			$stmt->bindValue(':employee_id', $this->employee_id, PDO::PARAM_STR);
			$stmt->bindValue(':id', $this->id, PDO::PARAM_STR);
			$stmt->execute();

			if($stmt){
				$_SESSION['success'] = "Position Updated Successfully";
				echo "<script>window.location='index.php'</script>";
			}
			else{
				$_SESSION['error'] = "Error Occured ";
			}
			
		} catch (PDOException $e) {
			echo "Error: ".$e->getMessage()."<br>";
			die();
		}
	}

	// employee photo update....
	public function employee_photo_update(){
		try {

			$stmt = $this->con->prepare("update employees set 
				photo=:photo
				where id=:id
				");
			$stmt->bindValue(':photo', $this->image, PDO::PARAM_STR);
			$stmt->bindValue(':id', $this->id, PDO::PARAM_STR);
			$stmt->execute();
			if($stmt){
				$_SESSION['success'] = "Employee Image Updated Successfully";
				echo "<script>window.location='index.php'</script>";
			}
			
		} catch (PDOException $e) {
			echo "Error: ".$e->getMessage()."<br>";
			die();
		}
	}

	//count employee number
	public function count_employee(){
		try {

			$stmt = $this->con->prepare("select * from employees where delete_status=1 ");
			$stmt->execute();
			$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $stmt->rowCount();
			
		} catch (PDOException $e) {
			echo "Error: ".$e->getMessage()."<br>";
			die();
		}
	}

	//total attend today 
	public function today_attend($today){
		try {

			$stmt = $this->con->prepare("select * from attendance where date=:date ");
			$stmt->bindValue(':date', $today, PDO::PARAM_STR);
			$stmt->execute();
			$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $stmt->rowCount();
			
		} catch (PDOException $e) {
			echo "Error: ".$e->getMessage()."<br>";
			die();
		}
	}
	//total attend today 
	public function today_ontime_attend($today){
		try {

			$stmt = $this->con->prepare("select * from attendance where date=:date and status=1 ");
			$stmt->bindValue(':date', $today, PDO::PARAM_STR);
			$stmt->execute();
			$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $stmt->rowCount();
			
		} catch (PDOException $e) {
			echo "Error: ".$e->getMessage()."<br>";
			die();
		}
	}
	//total attend today 
	public function today_late_attend($today){
		try {

			$stmt = $this->con->prepare("select * from attendance where date=:date and status=0 ");
			$stmt->bindValue(':date', $today, PDO::PARAM_STR);
			$stmt->execute();
			$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $stmt->rowCount();
			
		} catch (PDOException $e) {
			echo "Error: ".$e->getMessage()."<br>";
			die();
		}
	}

	//select all employee
	public function employeeList(){
		try {

			$stmt = $this->con->prepare("SELECT *, employees.id AS empid, employees.employee_id as employee_id FROM employees LEFT JOIN position ON position.id=employees.position_id LEFT JOIN schedules ON schedules.id=employees.schedule_id where delete_status=1 ");
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (PDOException $e) {
			echo "Error :".$e->getMessage()."<br>";
			die();
		}
	}

	// select all position 
	public function positionList(){
		try {

			$stmt = $this->con->prepare("select * from position");
			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (PDOException $e) {
			echo "Error: ".$e->getMessage()."<br>";
			die();
		}
	}

	//select all schedule
	public function scheduleList(){
		try {

			$stmt = $this->con->prepare("select * from schedules");
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (PDOException $e) {
			echo "Error: ".$e->getMessage()."<br>";
			die();
		}
	}

	// employee entry and leave time show function
	public function show_attend_leave_time(){
		try {

			$stmt = $this->con->prepare("SELECT *, employees.employee_id AS empid, attendance.id AS attid FROM attendance LEFT JOIN employees ON employees.id=attendance.employee_id ORDER BY attendance.date DESC, attendance.time_in DESC");
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (PDOException $e) {
			echo "Error: ".$e-getMessage()."<br>";
			die();
		}
	}

	//for chart view....
	public function count_ontime_chart($m, $and){
		try {
			$stmt = $this->con->prepare("SELECT * FROM attendance WHERE MONTH(date) = '$m' AND status = 1 $and");
			$stmt->execute();
			$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $stmt->rowCount();
			
		} catch (PDOException $e) {
			echo "Error: ".$e->getMessage()."<br>";
			die();
		}
	}
	//for chart view....
	public function count_late_chart($m,$and){
		try {
			$stmt = $this->con->prepare("SELECT * FROM attendance WHERE MONTH(date) = '$m' AND status = 0 $and");
			$stmt->execute();
			$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $stmt->rowCount();
			
		} catch (PDOException $e) {
			echo "Error: ".$e->getMessage()."<br>";
			die();
		}
	}


}