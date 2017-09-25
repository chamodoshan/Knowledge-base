<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
<h1 style="color:green;">FAQ</h1>
</head>
<body>
<div  class="form" align="center">
	<form method="POST" action="test2.Php">
	<input type="text" class="text" id="ques" name="ques" placeholder="Question"/></br>
	<button type="submit" class="button" name="ask">Ask</button>
	</form>
</div>

<script>
    function myselect(word)
    {
    	/*var str_esc = escape(word);
		var res = unescape(str_esc);*/
		//word.replace(/&nbsp/g, ' ');
        document.getElementById("ques").value = word.replace(/space/gi," ");
    }
</script>

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
	public $qIdW = [];
	public $kIdW = [];
	public $removeDup_kIdW = [];
	//public $weights = [] [];
	public function __construct($ques){
		$this->words = explode(" ", $this->removeCommonWords($ques));
		//var_dump($this->words);
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
				echo $row['keyword'];
				echo "-------------------------------------";
				echo "</br>";
		}
		/*if ($count0==1) {
		 	echo "Be more specific !!!";
		 } else {*/
		 	return $this->num;
		 //}
		
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
		$con = count($this->num);
		$con2 = count($this->words);
		$test = $this->que;
		//var_dump($con2) ;
		$sql2 = "SELECT * FROM qanda";
		$result2 = $this->cc->query($sql2);
		$count = array_count_values($this->QuestionId()); 
		$value = array_search(max($count), $count);
		while ($row = $result2->fetch_assoc()) {
			if ($row['q_Id']== $value) {
				if ($con == 1 || $con2 <= 1) {
					$pre_sql = "SELECT q_id, k_id FROM weight WHERE (k_id LIKE '%{$this->num[0]}%')";
					$pre_result = $this->cc->query($pre_sql);
					while ($row = $pre_result->fetch_assoc()) {
						array_push($this->qIdW, $row['q_id']);
					}
					for ($i=0; $i < sizeof($this->qIdW) ; $i++) { 
						$pre_sql2 = "SELECT q_id, k_id FROM weight WHERE (q_id LIKE '%{$this->qIdW[$i]}%')";
						$pre_result2 = $this->cc->query($pre_sql2);
						while ($row = $pre_result2->fetch_assoc()) {
							array_push($this->kIdW, $row['k_id']);
						}
					}
					$this->removeDup_kIdW = array_unique($this->kIdW);
					for ($j=0; $j < sizeof($this->removeDup_kIdW) ; $j++) { 
						$sql = "SELECT k_id, keyword FROM keyword WHERE (k_id LIKE '%{$this->removeDup_kIdW[$j]}%')";
						$this->result = $this->cc->query($sql);
						while ($row = $this->result->fetch_assoc()) {
							$key = $row['keyword'];
							echo $key;
							echo "</br>";
							echo"<td width=14% align=center><input type=button value=$key onclick=myselect('$key'+'space'+'$test') /></td>";
						}
					}
				}else {
					echo $row['answer'];
				}
				//echo "--".$row['q_Id']."--";
			//	echo $row['question'];
			}
		}

	}

	/*public function weight(){
		$weights = [] ;
		$id = $this->QuestionId();
		$sql = "SELECT * FROM weight";
		$result = $this->cc->query($sql);
		while ($row = $result->fetch_assoc()) {
			$we=$row['weight'];
			foreach ($id as $ida) {
				if ($row['q_id'] == $ida) {
					$total=$we*$ida;
					array_push($weights, $total);
				}
			}
		}
		return $weights;
	}*/


	public function removeCommonWords($input){

		$input2 = strtolower($input);
 
 	// EEEEEEK Stop words
	$commonWords = array(',','a','able','about','above','abroad','according','accordingly','across','actually','adj','after','afterwards','again','against','ago','ahead','ain\'t','all','allow','allows','almost','alone','along','alongside','already','also','although','always','am','amid','amidst','among','amongst','an','and','another','any','anybody','anyhow','anyone','anything','anyway','anyways','anywhere','apart','appear','appreciate','appropriate','are','aren\'t','around','as','a\'s','aside','ask','asking','associated','at','available','away','awfully','b','back','backward','backwards','be','became','because','become','becomes','becoming','been','before','beforehand','begin','behind','being','believe','below','beside','besides','best','better','between','beyond','both','brief','but','by','c','came','can','cannot','cant','can\'t','caption','cause','causes','certain','certainly','changes','clearly','c\'mon','co','co.','com','come','comes','concerning','consequently','consider','considering','contain','containing','contains','corresponding','could','couldn\'t','course','c\'s','currently','d','dare','daren\'t','definitely','described','despite','did','didn\'t','different','directly','do','does','doesn\'t','doing','done','don\'t','down','downwards','during','e','each','edu','eg','eight','eighty','either','else','elsewhere','end','ending','enough','entirely','especially','et','etc','even','ever','evermore','every','everybody','everyone','everything','everywhere','ex','exactly','example','except','f','fairly','far','farther','few','fewer','fifth','first','five','followed','following','follows','for','forever','former','formerly','forth','forward','found','four','from','further','furthermore','g','get','gets','getting','given','gives','go','goes','going','gone','got','gotten','greetings','h','had','hadn\'t','half','happens','hardly','has','hasn\'t','have','haven\'t','having','he','he\'d','he\'ll','hello','help','hence','her','here','hereafter','hereby','herein','here\'s','hereupon','hers','herself','he\'s','hi','him','himself','his','hither','hopefully','how','howbeit','however','hundred','i','i\'d','ie','if','ignored','i\'ll','i\'m','immediate','in','inasmuch','inc','inc.','indeed','indicate','indicated','indicates','inner','inside','insofar','instead','into','inward','is','isn\'t','it','it\'d','it\'ll','its','it\'s','itself','i\'ve','j','just','k','keep','keeps','kept','know','known','knows','l','last','lately','later','latter','latterly','least','less','lest','let','let\'s','like','liked','likely','likewise','little','look','looking','looks','low','lower','ltd','m','made','mainly','make','makes','many','may','maybe','mayn\'t','me','mean','meantime','meanwhile','merely','might','mightn\'t','mine','minus','miss','more','moreover','most','mostly','mr','mrs','much','must','mustn\'t','my','myself','n','name','namely','nd','near','nearly','necessary','need','needn\'t','needs','neither','never','neverf','neverless','nevertheless','new','next','nine','ninety','no','nobody','non','none','nonetheless','noone','no-one','nor','normally','not','nothing','notwithstanding','novel','now','nowhere','o','obviously','of','off','often','oh','ok','okay','old','on','once','one','ones','one\'s','only','onto','opposite','or','other','others','otherwise','ought','oughtn\'t','our','ours','ourselves','out','outside','over','overall','own','p','particular','particularly','past','per','perhaps','placed','please','plus','possible','presumably','probably','provided','provides','q','que','quite','qv','r','rather','rd','re','really','reasonably','recent','recently','regarding','regardless','regards','relatively','respectively','right','round','s','said','same','saw','say','saying','says','second','secondly','see','seeing','seem','seemed','seeming','seems','seen','self','selves','sensible','sent','serious','seriously','seven','several','shall','shan\'t','she','she\'d','she\'ll','she\'s','should','shouldn\'t','since','six','so','some','somebody','someday','somehow','someone','something','sometime','sometimes','somewhat','somewhere','soon','sorry','specified','specify','specifying','still','sub','such','sup','sure','t','take','taken','taking','tell','tends','th','than','thank','thanks','thanx','that','that\'ll','thats','that\'s','that\'ve','the','their','theirs','them','themselves','then','thence','there','thereafter','thereby','there\'d','therefore','therein','there\'ll','there\'re','theres','there\'s','thereupon','there\'ve','these','they','they\'d','they\'ll','they\'re','they\'ve','thing','things','think','third','thirty','this','thorough','thoroughly','those','though','three','through','throughout','thru','thus','till','to','together','too','took','toward','towards','tried','tries','truly','try','trying','t\'s','twice','two','u','un','under','underneath','undoing','unfortunately','unless','unlike','unlikely','until','unto','up','upon','upwards','us','use','used','useful','uses','using','usually','v','value','various','versus','very','via','viz','vs','w','want','wants','was','wasn\'t','way','we','we\'d','welcome','well','we\'ll','went','were','we\'re','weren\'t','we\'ve','what','whatever','what\'ll','what\'s','what\'ve','when','whence','whenever','where','whereafter','whereas','whereby','wherein','where\'s','whereupon','wherever','whether','which','whichever','while','whilst','whither','who','who\'d','whoever','whole','who\'ll','whom','whomever','who\'s','whose','why','will','willing','wish','with','within','without','wonder','won\'t','would','wouldn\'t','x','y','yes','yet','you','you\'d','you\'ll','your','you\'re','yours','yourself','yourselves','you\'ve','z','zero');
 
	return preg_replace('/\b('.implode('|',$commonWords).')\b/','',$input2);

}


}
if (isset($_POST['ques'])) {
$obj1 = new Keywords($_POST['ques']);
//$obj1->Question();
$obj1->Question();
/*foreach ($obj1->weight() as $key) {
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






