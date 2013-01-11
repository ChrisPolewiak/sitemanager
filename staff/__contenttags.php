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
									<?=sm_inputfield( "textarea", "Słowa kluczowe", "wprowadź słowa kluczowe oddzielając je przecinkami", "dane_content_tags_tags", "dane_content_tags[tags]", $dane_content_tags["tags"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=3);?>
								</fieldset>
