<?php
$t=isset($_POST['t'])?filter_input(INPUT_POST,'t',FILTER_SANITIZE_STRING):filter_input(INPUT_GET,'t',FILTER_SANITIZE_STRING);
unlink('../'.$t);
$t=str_replace('.','',$t);
$fileid=str_replace('.','',$t);
$fileid=str_replace('/','',$fileid);?>
<script>/*<![CDATA[*/
	window.top.window.$('#l_<?php echo$fileid;?>').slideUp(500,function(){$(this).remove()});
/*]]>*/</script>
