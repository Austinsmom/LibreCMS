<div>
	<form method="post" target="sp" enctype="multipart/form-data" action="core/add_data.php">
		<div class="form-group">
			<div class="input-group">
				<input type="file" name="file[]" class="form-control" multiple>
				<input type="hidden" name="act" value="add_media">
				<div class="input-group-btn">
					<button class="btn btn-default" onclick="$('#block').css({'display':'block'});">Upload</button>
				</div>
			</div>
		</div>
	</form>
</div>
<div class="table-responsive">
<?php $path='media/';
	$upload_dir='media/';
	$handle=opendir($upload_dir);
	while($file=readdir($handle)){
		if(!is_dir($upload_dir.$file)&&!is_link($upload_dir.$file))$docs[]=$file;
	}
	if(isset($docs)){
		sort($docs);
		$i=0;
		foreach($docs as$key=>$file){
			if($file=='.gitkeep')continue;?>
	<div id="l_<?php echo$i;?>" class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
		<div class="panel panel-default">
			<div class="panel-image">
<?php		$finfo=new finfo(FILEINFO_MIME_TYPE);
			$type=$finfo->file('media/'.$file);
			if($type=='application/msword'||$type=='application/vnd.openxmlformats-officedocument.wordprocessingml.document'||$type=='application/vnd.openxmlformats-officedocument.wordprocessingml.template'||$type=='application/rtf'||$type=='application/x-rtf'||$type=='text/richtext'||$type=='application/rtf'||$type=='text/richtext')echo'<i class="libre libre-file-pdf libre-8x"></i>';
			elseif($type=='audio/mpeg3'||$type=='audio/x-mpeg-3'||$type=='audio/mpeg')echo'<i class="libre libre-file-audio libre-8x"></i>';
			elseif($type=='application/x-troff-msvideo'||$type=='video/avi'||$type=='video/msvideo'||$type=='video/x-msvideo'||$type=='video/mp4'||$type=='video/mpeg'||$type=='video/x-ms-wmv')echo'<i class="libre libre-file-video libre-8x"></i>';
			elseif($type=='application/pdf')echo'<i class="libre libre-file-pdf libre-8x"></i>';
			elseif($type=='application/x-rar-compressed'||$type=='application/x-compressed'||$type=='application/x-zip-compressed'||$type=='application/zip'||$type=='multipart/x-zip'||$type=='application/gnutar'||$type=='application/x-compressed')echo'<i class="libre libre-file-archive libre-8x"></i>';
			elseif($type=='text/plain')echo'<i class="libre libre-file-text libre-8x"></i>';
			elseif($type=='image/pjpeg'||$type=='image/jpeg'||$type=='image/bmp'||$type=='image/gif'||$type=='image/png')echo'<a class="panel-image" title="'.$file.'" href="media/'.$file.'" data-featherlight-gallery><img src="media/'.$file.'"></a>';
			else echo'<i class="libre libre-file libre-8x"></i>';?>
				<h4 class="panel-title color-white"><small><?php echo $file;?></small></h4>
			</div>
			<div class="panel-footer text-right">
				<button class="btn btn-default btn-xs" onclick="removeMedia('<?php echo$i;$i++;?>','<?php echo str_replace(' ','~',$file);?>')"><i class="libre libre-trash color-danger"></i></button>
			</div>
		</div>
	</div>
<?php 	}
	}?>
</div>
