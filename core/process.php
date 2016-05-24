<?php require'core'.DS.'db.php';
$config=$this->getconfig($db);
$theme=parse_ini_file(THEME.DS.'theme.ini',TRUE);
$ti=time();
$show='';
$html='';
$head='';
$content='';
$foot='';
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
if(isset($act)&&$act=='logout')require'core/login.php';
require'core'.DS.'cart_quantity.php';
if($_SESSION['rank']>699)$status="%";else$status="published";
$content='';
if($config['maintenance']{0}==1&&(isset($_SESSION['rank'])&&$_SESSION['rank']<400)){
    if(file_exists(THEME.DS.'maintenance.html'))$template=file_get_contents(THEME.DS.'maintenance.html');else{
        include'core'.DS.'layout'.DS.'maintenance.php';
        die();
    }
}elseif(file_exists(THEME.DS.$view.'.html'))
    $template=file_get_contents(THEME.DS.$view.'.html');
elseif(file_exists(THEME.DS.'default.html'))
    $template=file_get_contents(THEME.DS.'default.html');
else$template=file_get_contents(THEME.DS.'content.html');
$newDom=new DOMDocument();
@$newDom->loadHTML($template);
$tag=$newDom->getElementsByTagName('block');
foreach($tag as$tag1){
    $include=$tag1->getAttribute('include');
    $inbed=$tag1->getAttribute('inbed');
    if($include!=''){
        $include=rtrim($include,'.html');
        if(file_exists(THEME.DS.$include.'.html'))
            $html=file_get_contents(THEME.DS.$include.'.html');
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
if(stristr($head,'<print meta=seoTitle>')){
    if($view=='index')
        $seoTitle=empty($page['seoTitle'])?$config['seoTitle']:$page['seoTitle'];
        else{
            if(!isset($seoTitle)||$seoTitle=='')$seoTitle=empty($page['seoTitle'])?ucfirst($view).' - '.$config['seoTitle']:$page['seoTitle'].' - '.$config['seoTitle'];
        }
        $head=str_replace('<print meta=seoTitle>',$seoTitle,$head);
}
if(stristr($head,'<print meta=seoCaption>')){
    if(!isset($seoCaption)||$seoCaption=='')
        $seoCaption=empty($page['seoCaption'])?$config['seoCaption']:$page['seoCaption'];
    if(!isset($seoDescription)||$seoDescription=='')
        $seoDescription=empty($page['seoDescription'])?$config['seoDescription']:$page['seoDescription'];
    if($view=='index'&&$seoDescription!='')
        $head=str_replace('<print meta=seoCaption>',$seoDescription,$head);
    else
        $head=str_replace('<print meta=seoCaption>',$seoCaption,$head);
}
if(stristr($head,'<print meta=seoKeywords>')){
    if(isset($args[1])&&$args[1]!=''&&isset($r['keywords']))
        $seoKeywords=$r['keywords'];
    elseif(!isset($seoKeywords)||$seoKeywords=='')
        $seoKeywords=empty($page['seoKeywords'])?$config['seoKeywords']:$page['seoKeywords'];
    $head=str_replace('<print meta=seoKeywords>',$seoKeywords,$head);
}
if(stristr($head,'<print meta=dateAtom>')){
    if(!isset($contentTime)){
        if($page['eti']>$config['ti'])
            $contentTime=$page['eti'];
        else
            $contentTime=$config['ti'];
    }
    $head=str_replace('<print meta=dateAtom>',date(DATE_ATOM,$contentTime),$head);
}
if(stristr($head,'<print meta=canonical>')){
    if(!isset($canonical)||$canonical=='')
        $canonical=URL.$view.'/';$head=str_replace('<print meta=canonical>',$canonical,$head);
}
if(stristr($head,'<print meta=url>'))
    $head=str_replace('<print meta=url>',URL,$head);
if(stristr($head,'<print meta=view>'))
    $head=str_replace('<print meta=view>',$view,$head);
if(stristr($head,'<print meta=rss>')){
    if($args[0]!=''||$args[0]!='index'||$args[0]!='bookings'||$args[0]!='contactus'||$args[0]!='cart'||$args[0]!='proofs'||$args[0]!='settings'||$args[0]!='accounts')
        $rss=URL.'rss/'.$view;else$rss=URL.'rss/';
    $head=str_replace('<print meta=rss>',$rss,$head);
}
if(stristr($head,'<print meta=shareImage>'))
    $head=str_replace('<print meta=shareImage>',$shareImage,$head);
if(stristr($head,'<print meta=favicon>'))
    $head=str_replace('<print meta=favicon>',FAVICON,$head);
if(stristr($head,'<print theme>'))
    $head=str_replace('<print theme>',THEME,$head);
print$head.$content;
