<?php 

echo "<pre>";
var_dump($_POST);
//die();

include_once '../../../vendor/autoload.php';

use App\Admin\Employee\Employee;

$employee = new Employee();


$employee->set($_POST);
$employee->insert_position();

/*echo "<pre>";
var_dump($employee);*/


