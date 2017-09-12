<!DOCTYPE html>
<html>
<head>
</head>
<body>

<h1>FAQ</h1>
<div  class="form" align="center">
	<form method="POST" action="test2.Php">
	<input type="text" class="text" name="ques" placeholder="Question"/></br>
	<button type="submit" class="button" name="ask">Ask</button>
	</form>
</div>

</body>
</html>
<?php
include 'connection.php';
/**
* 
*/
class DataReader
{
	public $que, $result;
	public function __construct($ques){
		$connection =new connection;
		$cc = $connection->check();	
		$this->que = $ques;
		$sql = "SELECT * FROM qanda";
		$this->result = $cc->query($sql);
	}

	public function display(){
		while ($row = $this->result->fetch_assoc()) {
				if($row['question']==$this->que){
					echo $row['answer'];
				}
			}
	}
}

if (isset($_POST['ques'])) {
$obj = new DataReader($_POST['ques']);
$obj->display();
}

/**
* 
*/
class Keywords 
{
	public $que, $result, $words, $num ,$cc;
	public function __construct($ques){
		$this->words = explode(" ", $ques);
		$connection =new connection;
		$this->cc = $connection->check();	
		$this->que = $ques;
		$sql = "SELECT * FROM keyword";
		$this->result = $this->cc->query($sql);
	}

	public function CheckKeyword(){
		while ($row = $this->result->fetch_assoc()) {
			if ($row['keyword']==$this->words[0]) {
				$this->num = $row['k_id'];
			}
		}
		return $this->num;
	}

	public function QuestionId(){
		$sql = "SELECT * FROM weight";
		$this->result = $this->cc->query($sql);
		while ($row = $this->result->fetch_assoc()) {
			if ($row['k_id']==$this->CheckKeyword()) {
				$this->num = $row['q_id'];
			}
		}
		return $this->num;
	}

	public function Question(){
		$sql = "SELECT * FROM qanda";
		$this->result = $this->cc->query($sql);
		while ($row = $this->result->fetch_assoc()) {
			if ($row['q_id']==$this->QuestionId()) {
				echo $row['answer'];
			}
		}
	}
}
if (isset($_POST['ques'])) {
$obj1 = new Keywords($_POST['ques']);
$obj1->Question();
}
?>

