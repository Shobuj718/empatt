<?php 
namespace App\Admin\Login;

if(!isset($_SESSION)){
	session_start();
}

use App\Connection;
use PDO;
use PDOException;

class Login extends Connection
{
	public function set($data = array()){
		if(array_key_exists('username', $data)){
			$this->username = $data['username'];
		}
		if(array_key_exists('password', $data)){
			$this->password = $data['password'];
		}
		return $this;
	}

	// admin login 
	public function adminLogin(){
		try {

			$password = $this->password;

			$stmt = $this->con->prepare("select * from admin where username=:username ");
			$stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
			//$stmt->bindValue(':password', $password, PDO::PARAM_STR);
			$stmt->execute();
			

			if($stmt->rowCount() > 0){
				$row = $stmt->fetch(PDO::FETCH_ASSOC);

				if(password_verify($password, $row['password'])){
					$_SESSION['username'] = $row['username'];
					$_SESSION['password'] = $row['password'];
					$_SESSION['admin']    = $row['id'];
					$_SESSION['type']     = $row['type'];

					header('location: http://localhost/empattend/view/admin/index.php');
				}

				else{
					$_SESSION['error'] = "password does not match !!!";
					header('location: http://localhost/empattend/view/admin/login/index.php');
				}
				
			}
			else{
				$_SESSION['error'] = "username  does not match !!!";
				header('location: http://localhost/empattend/view/admin/login/index.php');
			}
			
			/*if($stmt->rowCount() < 1){
				$_SESSION['error'] = "Cannot find account with the username";
			}
			else{
				$pass = md5($this->password);
				$row = $stmt->fetchAll(PDO::PARAM_STR);
				if($pass == $row['password']){
					$_SESSION['admin'] = $row['id'];
				}
				else{
					$_SESSION['error'] = "Incorrect Password";
				}
			}*/
			
		} catch (PDOException $e) {
			echo "Error ".$e->getMessage()."<br>";
			die();
		}
	}

	public function admin_login(){
		try {

			$stmt = $this->con->prepare( "SELECT * FROM admin WHERE id = '".$_SESSION['admin']."'");
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_ASSOC);
			
		} catch (PDOException $e) {
			echo "Error: ".$e->getMessage()."<br>";
			die();
		}
	}
}