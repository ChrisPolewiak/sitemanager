<?
if(isset( $_POST["dane_content_tags"]["tags"])){
	// tags
   
	if($result_tags = content_tags_fetch_by_id( $__id_table, $__table )) {
		while($row_tags=$result_tags->fetch(PDO::FETCH_ASSOC)){
			$__record_create_date = $row_tags["record_create_date"];
			$__record_create_id = $row_tags["record_create_id"];
		}
	}
    
	content_tags_delete_by_id( $__id_table, $__table);
	
	$tags = split(",", $dane_content_tags["tags"]);
	if(is_array($tags)){
		foreach($tags AS $tag) {
			$tag=trim(strip_tags($tag));
			content_tags_edit( $tag, $__id_table, $__table, $__record_create_date, $__record_create_id );
		}
	}
}

if( isset($__id_table) && $__id_table>0 ) {

	if($result_tags = content_tags_fetch_by_id( $__id_table, $__table )) {
		$tags=array();
		while($row_tags=$result_tags->fetch(PDO::FETCH_ASSOC)){
			$tags[]=$row_tags["content_tags_tag"];
		}
		$dane_content_tags["tags"] = join(",",$tags);
	}
}
?>
								<div class="fieldset-title" id="ContentTags">
									<div><?=__("core", "Słowa kluczowe")?></div><i class="icon-minus"></i>
								</div>
								<fieldset class="no-legend">
									<?=sm_inputfield(array(
										"type"	=> "textarea",
										"title"	=> "Słowa kluczowe",
										"help"	=> "wprowadź słowa kluczowe oddzielając je przecinkami",
										"id"	=> "dane_content_tags_tags",
										"name"	=> "dane_content_tags[tags]",
										"value"	=> $dane_content_tags["tags"],
										"size"	=> "block-level",
										"disabled" => 0,
										"validation" => 0,
										"prepend" => 0,
										"append" => 0,
										"rows" => 3,
										"options" => "",
										"xss_secured" => true
									));?>
								</fieldset>
