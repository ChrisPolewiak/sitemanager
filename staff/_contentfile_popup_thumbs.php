x<ul class="thumbnails">
<?
if($result=content_file_fetch_all()) {
	while($row=$result->fetch(PDO::FETCH_ASSOC)) {
?>
	<li class="span2">
		<div class="thumbnail" id="<?=$row["content_file__id"]?>">
			<img src="/cacheimg/id=<?=$row["content_file__id"]?>/w=150" alt="<?=$row["content_file__filename"]?>">
		</div>
	</li>
<?
	}
}
?>
</div>

<script>
$().ready(function() {
	$('.thumbnails .thumbnail').css({cursor:'pointer'});

	$('.thumbnails .thumbnail').unbind();
	$('.thumbnails .thumbnail').bind('click',function() {
		id = this.id;
		url = '?ajax=1&action=fileinfo&id='+id;
		$.ajax({
			url: url,
			dataType: "json",
			success: function(data) {
				if(data) {
					$('.thumbnails .thumbnail').removeClass('success');
					$('.thumbnails .thumbnail#'+id+'').addClass('success');
					$('.sm-fileinfo').show();
					$('.sm-fileselected').show();
					$('.sm-fileinfo .sm-filethumbnail').html('<img src="/cacheimg/id='+id+'/w=230/h=230" class="img-polaroid">');
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
