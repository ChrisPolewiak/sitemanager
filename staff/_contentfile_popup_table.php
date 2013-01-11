<?

	foreach($CONTENT_CATEGORY_LONGNAME AS $k=>$v) {
		$v = preg_replace( "/(@@@)/is", " / ", $v);
		$_content_category[ $k ] = $v;
	}

	$params = array(
		"button_back" => 0,
		"button_addnew" => 0,
		"dbname" => "content_file",
		"function_fetch" => "content_file_fetch_all()",
		"mainkey" => "content_file__id",
		"title" => __("core", "Lista plików"),
		"sScrollY" => "500px",
		"columns" => array(
			array( "title"=>__("core", "Tumbnail"), "width"=>"25", "value"=>"%%[image]{content_file__id}%%", "order"=>1 ),
			array( "title"=>__("core", "Nazwa pliku"), "width"=>"200", "value"=>"%%{content_file__filename}%%", "order"=>1 ),
			array( "title"=>__("core", "Kategoria"), "width"=>"200", "value"=>"%%{content_category__id}%%", "order"=>1, "valuesmatch"=>$_content_category ),
			array( "title"=>__("core", "Rozmiar"), "width"=>"100", "align"=>"right", "value"=>"%%{content_file__filesize} b%%" ),
			array( "title"=>__("core", "Data dodania"), "width"=>"100", "align"=>"right", "value"=>"%%[date]{record_create_date}%%" ),
			array( "title"=>__("core", "Typ pliku"), "width"=>"100", "align"=>"center", "value"=>"%%{content_file__filetype}%%", "order"=>1 ),
                ),
		"row_per_page_default" => 10,
		"action-doubleclick" => false,
		"actions" => array(
			"edit" => array(
				"display" => 0,
			),
			"delete" => array(
				"display" => 0,
			),
			"select1" => array(
				"display" => 0,
				"class" => "image-select",
				"icon" => "plus",
			),
			"select2" => array(
				"display" => 0,
				"class" => "image-select",
				"icon" => "plus",
			),
		),

        );
	include("_datatable_list5.php");
?>
<script>
$().ready(function() {
	$('#dataTable tbody tr').css({cursor:'pointer'});

	$('#dataTable tbody tr').unbind();
	$('#dataTable tbody tr').bind('click',function() {
		id = this.id;
		url = '?ajax=1&action=fileinfo&id='+id;
		$.ajax({
			url: url,
			dataType: "json",
			success: function(data) {
				if(data) {
					$('#dataTable tbody tr').removeClass('success');
					$('#dataTable tbody tr#'+id+'').addClass('success');
					$('.sm-fileinfo').show();
					$('.sm-fileselected').show();
					$('.sm-fileinfo .sm-filethumbnail').html('<img src="/cacheimg?id='+id+'&w=230&h=230" class="img-polaroid">');
					$('.sm-fileinfo .sm-filename').html(data.content_file__filename);
					$('.sm-fileinfo #content_file__id').val(id);

					if( ! $('.sm-fileselected tr#selected-'+id+' td').length) {
						str  = '<tr id="selected-' + id + '">';
						str += '<td>' + data.content_file__filename + '</td>';
						str += '<td><a href="#" class="action-selected-remove"><i class="icon-remove"></i></a>';
						str += '<input type="hidden" name="filesselected[]" value="' + id + '"></td>';
						str += '</tr>\n';
						$('.sm-fileselected .table').append( str );
					}
				
				}
			},
			error: function(data) {
				alert('error:'+data);
			}
		});
	});
});
</script>
