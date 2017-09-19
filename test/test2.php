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
	public $que, $result, $words, $cc;
	public $num = [];
	public $qId = [];
	//public $weights = [] [];
	public function __construct($ques){
		$this->words = explode(" ", $ques);
		$connection =new connection;
		$this->cc = $connection->check();	
		$this->que = $ques;
		for ($i=0; $i < sizeof($this->words) ; $i++) { 
			$sql = "SELECT k_id, keyword FROM keyword WHERE (keyword LIKE '%{$this->words[$i]}%')";
			$this->result = $this->cc->query($sql);
			$this->CheckKeyword();
		}
		
	}

	public function CheckKeyword(){
		while ($row = $this->result->fetch_assoc()) {
				array_push($this->num, $row['k_id']);
		}
		return $this->num;
	}

	public function QuestionId(){
		$sql1 = "SELECT * FROM weight";
		$result1 = $this->cc->query($sql1);
		$num_array = $this->CheckKeyword();
		while ($row = $result1->fetch_assoc()) {
			foreach ($num_array as $kId) {
				if ($row['k_id']==$kId) {
					array_push($this->qId, $row['q_id']);
					//array_push($this->weights, $row['weight'])
					//echo $this->num;
				}
			}
			
		}
		return $this->qId;
	}

	public function Question(){
		$sql2 = "SELECT * FROM qanda";
		$result2 = $this->cc->query($sql2);
		$count = array_count_values($this->QuestionId()); 
		$value = array_search(max($count), $count);
		while ($row = $result2->fetch_assoc()) {
			if ($row['q_Id']== $value) {
				echo $row['answer'];
				//echo "--".$row['q_Id']."--";
			//	echo $row['question'];
			}
		}
	}
}
if (isset($_POST['ques'])) {
$obj1 = new Keywords($_POST['ques']);
//$obj1->Question();
$obj1->Question();

/*foreach ($obj1->CheckKeyword() as $key) {
	echo $key;
}*/
/*echo "<br>";
foreach ($obj1->QuestionId() as $key) {
	echo $key;
}*/

}

/**
* 
*/
/*class weights extends Keywords
{
	function array(){
		$sql = "SELECT * FROM weight";
		$result = $this->cc->query($sql);
		$num_array = $Keywords->CheckKeyword();
		while ($row = $result->fetch_assoc()) {
			foreach ($num_array as $kId) {
				if ($row['k_id']==$kId) {
					$array = array(array($row['q_id'], $row['weight']));
					//array_push($this->qId, $row['q_id']);
					//array_push($this->weights, $row['weight'])
					//echo $this->num;
				}
			}
			
		}
		return $array;
	}

	function max(){
		$max = -9999999; //will hold max val
		$found_item = null; //will hold item with max val;
		$arr = $this->array();
		foreach($arr as $k=>$v)
		{
			for ($i=0; $i < sizeof($v) ; $i++) { 

				if($v[$i]>$max){

       			$max = $v[$i];
       			$found_item = $v;
    		}
			}
   		
		}

		echo "max value is".$max."<br>";
		print_r($found_item); 
	}
}

$obj2 = new weights();
$obj2->max();*/

?>



