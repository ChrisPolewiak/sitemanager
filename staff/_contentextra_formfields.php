<?
	if($result=content_extra_fetch_by_object("content_user")){
		while($row=$result->fetch(PDO::FETCH_ASSOC)){
			$content_extra[]=$row;
		}
		foreach($content_extra AS $fielddata){

			switch ($fielddata["content_extra__input"]) {
				case "text": case "textarea": case "checkbox":
				
					echo sm_inputfield(
						$fielddata["content_extra__input"],
						$fielddata["content_extra__info"],
						"",
						"dane_".$fielddata["content_extra__object"]."_extra_".$fielddata["content_extra__dbname"],
						"dane[".$fielddata["content_extra__object"]."_extra_".$fielddata["content_extra__dbname"]."]",
						$dane[ $fielddata["content_extra__object"]."_extra_".$fielddata["content_extra__dbname"] ],
						"block-level",
						$disabled=false,
						$validation=false,
						$prepend=false,
						$append=false,
						$rows=1);
					break;

				case "select":

					$inputfield_options = array();
					$inputfield_options[] = "---";
					if($result=content_extralist_fetch_by_content_extra($fielddata["content_extra__id"], "value")){
						while($row=$result->fetch(PDO::FETCH_ASSOC)){
							$inputfield_options[ $row["content_extralist__value"] ] = $row["content_extralist__name"];
						}
					}

					echo sm_inputfield(
						"select",
						$fielddata["content_extra__info"],
						"",
						"dane_".$fielddata["content_extra__object"]."_extra_".$fielddata["content_extra__dbname"],
						"dane[".$fielddata["content_extra__object"]."_extra_".$fielddata["content_extra__dbname"]."]",
						$dane[ $fielddata["content_extra__object"]."_extra_".$fielddata["content_extra__dbname"] ],
						"block-level",
						$disabled=false,
						$validation=false,
						$prepend=false,
						$append=false,
						$rows=1,
						$inputfield_options );
					break;

				case "relation":

					$inputfield_options = array();
					$inputfield_options[] = "---";
					eval("\$result=".$fielddata["content_extra__relationfunction"]."();");
					if($result) {
						while($row=$result->fetch(PDO::FETCH_ASSOC)){
							$inputfield_options[ $row["id_".$fielddata["content_extra__relationtable"]] ] = $row[$fielddata["content_extra__relationname"]];
						}
					}

					echo sm_inputfield(
						"select",
						$fielddata["content_extra__info"],
						"",
						"dane_".$fielddata["content_extra__object"]."_extra_".$fielddata["content_extra__dbname"],
						"dane[".$fielddata["content_extra__object"]."_extra_".$fielddata["content_extra__dbname"]."]",
						$dane[ $fielddata["content_extra__object"]."_extra_".$fielddata["content_extra__dbname"] ],
						"block-level",
						$disabled=false,
						$validation=false,
						$prepend=false,
						$append=false,
						$rows=1,
						$inputfield_options );
					break;

			}
		}
	}
?>
