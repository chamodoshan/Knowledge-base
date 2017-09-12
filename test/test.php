<?php
include 'connection.php';
if (isset($_POST['ask'])) {
$ques=$_POST["ques"];

	$sql ="SELECT * FROM test
        WHERE MATCH (question,answer)
        AGAINST  ("" IN NATURAL LANGUAGE MODE)";

        //echo $sql;

	$result=$conn->query($sql);

	//var_dump($result);

	while ($row = $result->fetch_assoc()) {
			echo "id".$row["q_id"]. "Q :- " .$row["question"] . "A :-" . $row["answer"]. "<br>";
		}

	//while($row= $result->fetch_assoc()){
		//if($row['question']==$ques){
		//	echo "".$row['answer'];
		//} 
		echo $result;
	//}	
$words = explode(" ", $ques);

	
	/*$sql2 ="SELECT*FROM keyword";
	$result2=$conn->query($sql2);
	while($row= $result2->fetch_assoc()){
		for ($i=0; $i <sizeof($words) ; $i++) { 
			if($row['Keyword']==$words[$i]){
				$q = $row['k_id'];
				echo $q;
			}
		}
		
	}*/
}
?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>

<h1>FAQ</h1>
<div  class="form" align="center">
	<form method="post" action="test.Php">
	<input type="text" class="text" name="ques" placeholder="Question"  required /></br>
	<button type="submit" class="button" name="ask">Ask</button>
	</form>
</div>

</body>
</html>
