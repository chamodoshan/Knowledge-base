<?php
include 'connection.php';
if (isset($_POST['ask'])) {
$ques=$_POST["ques"];

	$sql ="SELECT*FROM faq";
	$result=$conn->query($sql);
		while($row= $result->fetch_assoc()){
			if($row['Question']==$ques){
				echo "Answer:".$row['Answer'];
			} else {
				echo "invalide !!!";
			}

			if ($row['Keywords']== ) {
				# code...
			}
		}

	/*$sql2 = "SELECT*FROM faq WHERE Question LIKE '%+ques+%'";
	$result2=$conn->query($sql2);
		while($row= $result2->fetch_assoc()){
				echo "Answer:".$row['Answer'];
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