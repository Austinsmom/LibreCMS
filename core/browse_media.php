<?php
$id=filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
$t=filter_input(INPUT_GET,'t',FILTER_SANITIZE_STRING);
$c=filter_input(INPUT_GET,'c',FILTER_SANITIZE_STRING);?>
<div class="modal-header clearfix">
	<button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4>Browse Media</h4>
</div>
<div class="modal-body" style="max-height:450px;overflow-y:auto;">
<ul id="media">
<?php $path='../media/';
	$upload_dir='../media/';
	$handle=opendir($upload_dir);
	while($file=readdir($handle)){
		if(!is_dir($upload_dir.$file)&&!is_link($upload_dir.$file))$docs[]=$file;
	}
	if(isset($docs)){
		sort($docs);
		$i=0;
		foreach($docs as$key=>$file){
			if($file=='.gitkeep')continue;?>
	<li id="l_<?php echo$i;?>">
<?php		$finfo=new finfo(FILEINFO_MIME_TYPE);
			$type=$finfo->file('../media/'.$file);
			if($type=='image/pjpeg'||$type=='image/jpeg'||$type=='image/bmp'||$type=='image/gif'||$type=='image/png')
				echo'<a title="'.$file.'" href="#" onclick="addMedia(\''.$file.'\');return false;"><img src="media/'.$file.'" class="img-thumbnail"></a><div class="title">'.$file.'</div>';
			else continue;?>
	</li>
<?php 	}
	}?>
</ul>
</div>
<div class="modal-footer"></div>
<script>/*<![CDATA[*/
	function addMedia(file){
		$('#block').css({'display':'block'});
		$.ajax({
			type:"GET",
			url:"core/update.php",
			data:{
				id:'<?php echo$id;?>',
				t:'<?php echo$t;?>',
				c:'<?php echo$c;?>',
				da:file
			}
		}).done(function(msg){
			$('#<?php echo$c;?>').html('<a href="media/'+file+'" data-featherlight-gallery><img src="media/'+file+'"></a>');
			$('#block').css({'display':'none'});
		})
	}
/*]]>*/</script>