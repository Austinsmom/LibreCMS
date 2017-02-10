<?php
require'core'.DS.'db.php';
$config=$this->getconfig($db);
$theme=parse_ini_file(THEME.DS.'theme.ini',TRUE);
$ti=time();
$show='';
$html='';
$head='';
$content='';
$foot='';
if(isset($_GET['amp'])&&$_GET['amp']=='amped')$amp=DS.'amp';else$amp='';
$css=THEME.DS.'css'.DS;
$favicon=FAVICON;
$shareImage=FAVICON;
$noimage=NOIMAGE;
$noavatar=NOAVATAR;
$sp=$db->prepare("SELECT * FROM menu WHERE contentType=:contentType");
$sp->execute(array(':contentType'=>$view));
$page=$sp->fetch(PDO::FETCH_ASSOC);
$pu=$db->prepare("UPDATE menu SET views=views+1 WHERE id=:id");
$pu->execute(array(':id'=>$page['id']));
if(isset($act)&&$act=='logout')require'core'.DS.'login.php';
require'core'.DS.'cart_quantity.php';
if($_SESSION['rank']>699)$status="%";else$status="published";
$content='';
if($config['maintenance']{0}==1&&(isset($_SESSION['rank'])&&$_SESSION['rank']<400)){
  if(file_exists(THEME.$amp.DS.'maintenance.html'))
    $template=file_get_contents(THEME.$amp.DS.'maintenance.html');
  else{
  	require'core'.DS.'layout'.DS.'maintenance.php';
    die();
  }
}elseif(file_exists(THEME.$amp.DS.$view.'.html'))
  $template=file_get_contents(THEME.$amp.DS.$view.'.html');
elseif(file_exists(THEME.$amp.DS.'default.html'))
  $template=file_get_contents(THEME.$amp.DS.'default.html');
else
  $template=file_get_contents(THEME.$amp.DS.'content.html');
$newDom=new DOMDocument();
@$newDom->loadHTML($template);
$tag=$newDom->getElementsByTagName('block');
foreach($tag as$tag1){
  $include=$tag1->getAttribute('include');
  $inbed=$tag1->getAttribute('inbed');
  if($include!=''){
    $include=rtrim($include,'.html');
    if(file_exists(THEME.$amp.DS.$include.'.html'))
      $html=file_get_contents(THEME.$amp.DS.$include.'.html');
    else
      $html='';
    require'view'.DS.$include.'.php';
    $req=$include;
  }
  if($inbed!=''){
    preg_match('/<block inbed="'.$inbed.'">([\w\W]*?)<\/block>/',$template,$matches);
    $html=isset($matches[1])?$matches[1]:'';
    if($view=='cart')$inbed='cart';
    if($view=='sitemap')$inbed='sitemap';
    if($view=='settings')$inbed='settings';
    require'view'.DS.$inbed.'.php';
    $req=$inbed;
  }
}
if(stristr($head,'<print meta=metaRobots>')){
  if(!isset($metaRobots))$metaRobots=empty($page['metaRobots'])?'index,follow':$page['metaRobots'];
  $head=str_replace('<print meta=metaRobots>',$metaRobots,$head);
}
if(stristr($head,'<print meta=seoTitle>')){
  if($view=='index')
    $seoTitle=empty($page['seoTitle'])?$config['seoTitle']:$page['seoTitle'];
	else{
    if(!isset($seoTitle)||$seoTitle=='')$seoTitle=empty($page['seoTitle'])?ucfirst($view).' - '.$config['seoTitle']:$page['seoTitle'].' - '.$config['seoTitle'];
  }
  $head=str_replace('<print meta=seoTitle>',$seoTitle,$head);
}
if(stristr($head,'<print meta=seoCaption>')){
  if(!isset($seoCaption)||$seoCaption=='')$seoCaption=empty($page['seoCaption'])?$config['seoCaption']:$page['seoCaption'];
  if(!isset($seoDescription)||$seoDescription=='')$seoDescription=empty($page['seoDescription'])?$config['seoDescription']:$page['seoDescription'];
  if($view=='index'&&$seoDescription!='')$head=str_replace('<print meta=seoCaption>',$seoDescription,$head);
	else$head=str_replace('<print meta=seoCaption>',$seoCaption,$head);
}
if(stristr($head,'<print meta=seoKeywords>')){
  if(isset($args[1])&&$args[1]!=''&&isset($r['seoKeywords']))$seoKeywords=$r['seoKeywords'];elseif(!isset($seoKeywords)||$seoKeywords=='')$seoKeywords=empty($page['seoKeywords'])?$config['seoKeywords']:$page['seoKeywords'];
  $head=str_replace('<print meta=seoKeywords>',$seoKeywords,$head);
}
if(stristr($head,'<print meta=dateAtom>')){
  if(!isset($contentTime)){
    if($page['eti']>$config['ti'])$contentTime=$page['eti'];else$contentTime=$config['ti'];
	}
	$head=str_replace('<print meta=dateAtom>',date(DATE_ATOM,$contentTime),$head);
}
if(stristr($head,'<print meta=canonical>')){
  if(!isset($canonical)||$canonical==''){
    if($view=='index')$canonical=URL;else$canonical=URL.$view.'/';
  }
  $head=str_replace('<print meta=canonical>',$canonical,$head);
}
if(stristr($head,'<print meta=url>'))$head=str_replace('<print meta=url>',URL,$head);
if(stristr($head,'<print meta=view>'))$head=str_replace('<print meta=view>',$view,$head);
if(stristr($head,'<print meta=rss>')){
  if($args[0]!=''||$args[0]!='index'||$args[0]!='bookings'||$args[0]!='contactus'||$args[0]!='cart'||$args[0]!='proofs'||$args[0]!='settings'||$args[0]!='accounts')$rss=URL.'rss/'.$view;else$rss=URL.'rss/';$head=str_replace('<print meta=rss>',$rss,$head);
}
if(stristr($head,'<print meta=ogType>')){
  if($view=='inventory')$head=str_replace('<print meta=ogType>','product',$head);else$head=str_replace('<print meta=ogType>',$view,$head);
}
if(stristr($head,'<print meta=shareImage>'))$head=str_replace('<print meta=shareImage>',$shareImage,$head);
if(stristr($head,'<print meta=favicon>'))$head=str_replace('<print meta=favicon>',FAVICON,$head);
if(stristr($head,'<print microid>'))$head=str_replace('<print microid>',microid($config['email'],$canonical),$head);
if(stristr($head,'<print meta=author>')){
  if(isset($r['name'])&&$r['name']!='')$head=str_replace('<print meta=author>',$r['name'],$head);elseif(isset($config['business'])&&$config['business']!='')$head=str_replace('<print meta=author>',$config['business'],$head);else$head=str_replace('<print meta=author>',$config['seoTitle'],$head);
}
if(stristr($head,'<print theme>'))$head=str_replace('<print theme>',THEME,$head);
if(stristr($head,'<print google_verification>'))$head=str_replace('<print google_verification>',$config['ga_verification'],$head);
if(stristr($head,'<!-- Google Analytics -->'))$head=str_replace('<!-- Google Analytics -->','<script>/*<![CDATA[*/'.htmlspecialchars_decode($config['ga_tracking'],ENT_QUOTES).'/*]]>*/</script>',$head);
if(isset($_GET['theme'])&&file_exists('layout'.DS.$_GET['theme'])){
  $doc=new DOMDocument;
  @$doc->loadHTML($content);
  foreach($doc->getElementsByTagName('a')as$link)$link->setAttribute('href',$link->getAttribute('href').'?theme='.$_GET['theme']);
  $content=preg_replace('/^<!DOCTYPE.+?>/','',str_replace(array('<html>','</html>','<body>','</body>'),array('','','',''),$doc->saveHTML())).'</body></html>';
}
if(isset($_GET['amp'])&&$_GET['amp']=='amped'){
  $doc=new DOMDocument;
  @$doc->loadHTML($content);
  foreach($doc->getElementsByTagName('a')as$link)$link->setAttribute('href',rtrim($link->getAttribute('href'),'/').'?amp=amped');
  $content=preg_replace('/^<!DOCTYPE.+?>/','',str_replace(array('<html>','</html>','<body>','</body>'),array('','','',''),$doc->saveHTML())).'</body></html>';
	$content=preg_replace('/(<[^>]*) style=("[^"]+"|\'[^\']+\')([^>]*>)/i', '$1$3',$content);
}
if(isset($_SESSION['rank'])&&$_SESSION['rank']>899&&$config['development']==1){
  $content.='<div style="text-align:right;padding:10px;">Process Time: '.elapsed_time().'</div>';
}
if(MINIFY==1)print minify($head.$content);else print$head.$content;
$current_page=PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
if(!isset($_SESSION['current_page'])||(isset($_SESSION['current_page'])&&$_SESSION['current_page']!=$current_page)){
  $s=$db->prepare("INSERT INTO tracker (pid,urlDest,urlFrom,userAgent,ip,browser,os,sid,ti) VALUES (:pid,:urlDest,:urlFrom,:userAgent,:ip,:browser,:os,:sid,:ti)");
  $hr=isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
  $s->execute(array(':pid'=>$page['id'],':urlDest'=>$current_page,':urlFrom'=>$hr,':userAgent'=>$_SERVER['HTTP_USER_AGENT'],':ip'=>$_SERVER["REMOTE_ADDR"],':browser'=>getBrowser(),':os'=>getOS(),':sid'=>session_id(),':ti'=>time()));
  $_SESSION['current_page']=$current_page;
}
function getOS(){
  $user_agent=$_SERVER['HTTP_USER_AGENT'];
  $os_platform="Unknown OS Platform";
  $os_array=array('/windows nt 10|windows nt 6.3|windows nt 6.2|windows nt 6.1|windows nt 6.0|windows nt 5.2|windows nt 5.1|windows xp|windows nt 5.0|windows me|win98|win95|win16/i'=>'Windows','/macintosh|mac os x|mac_powerpc/i'=>'Mac','/linux/i'=>'Linux','/ubuntu/i'=>'Ubuntu','/iphone/i'=>'iPhone','/ipod/i'=>'iPod','/ipad/i'=>'iPad','/android/i'=>'Android','/blackberry/i'=>'BlackBerry','/webos/i'=>'Mobile');
  foreach($os_array as$regex=>$value){
    if(preg_match($regex,$user_agent)){
      $os_platform=$value;
    }
  }
  return$os_platform;
}
function getBrowser(){
  $user_agent=$_SERVER['HTTP_USER_AGENT'];
  $browser="Unknown Browser";
  $browser_array=array('/msie/i'=>'Explorer','/firefox/i'=>'Firefox','/safari/i'=>'Safari','/chrome/i'=>'Chrome','/edge/i'=>'Edge','/opera/i'=>'Opera','/netscape/i'=>'Netscape','/maxthon/i'=>'Maxthon','/konqueror/i'=>'Konqueror','/mobile/i'=>'Mobile');
  foreach($browser_array as$regex=>$value){
    if(preg_match($regex,$user_agent)){
      $browser=$value;
    }
  }
  return$browser;
}
