<?

// SEARCH

$object = $_GET["object"];
$resulttype = $_GET["resulttype"];
$query = $_GET["q"];

switch($resulttype){
	case "json": case "xml": case "pipe": case "comma":
		continue;
	default:
		exit;
}

switch($object){
	case "content_user":
		if($result = content_user_search("name", $query)) {
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$data[$row["content_user__id"]] = $row["content_user__username"];
			}
		}
		break;
	default:
		$fname = $object."_search";
		if(function_exists($fname)){
			eval( "\$result = $fname( \$query ); " );
			if($result) {
				while($row=$result->fetch(PDO::FETCH_ASSOC)){
					$data[ $row["id"] ] = $row["value"];
				}
			}
		}
		break;
}

if($data) {
	switch($resulttype) {
		case "pipe":
			foreach($data AS $k=>$v) {
				echo "$v|$k\n";
			}
		case "json":
			$result=array();
			foreach($data AS $k=>$v) {
				$result[] = array(
					"id" => $k,
					"name" => $v,
				);
			}
			echo json_encode($result);
			break;
	}
}
?>
