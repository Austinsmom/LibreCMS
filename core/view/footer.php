<?php
if($_SESSION['rank']>0)$link='<a href="?act=logout">Logout</a>';
else{
	if($config['options']{3}==1)$link_x=' or Sign Up';
	else{
		$link_x='';
		$html=preg_replace('~<block signup>.*?<\/block signup>~is','',$html,1);
	}
	if($config['options']{2}==1)$link='<a href="login/">Login'.$link_x.'</a>';else$link='<a href="login/">Login'.$link_x.'</a>';
}
$theme=parse_ini_file(THEME.DS.'theme.ini',true);
$html=str_replace('<print theme="title">',$theme['title'],$html);
$html=str_replace('<print theme="creator">',$theme['creator'],$html);
$html=str_replace('<print theme=creator_url>',$theme['creator_url'],$html);
if(isset($_SESSION['rank'])&&$_SESSION['rank']>899)$html=str_replace('<administration>','<a target="_blank" href="'.$settings['system']['admin'].'">Administration</a>',$html);else$html=str_replace('<administration>','',$html);
$html=str_replace('<print year>',date('Y'),$html);
$html=str_replace('<print config="business">',$config['business'],$html);
if($config['abn']!='')$config['abn']='ABN '.$config['abn'];
$html=str_replace('<print config="abn">',$config['abn'],$html);
$html=str_replace('<login>',$link,$html);
if(stristr($html,'<address')){
	if($config['business']!='')$business=$config['business'];else$business='';
	$html=str_replace('<print config="business">',$business,$html);
	if($config['address']!='')$address=$config['address'].', ';else$address='';
	$html=str_replace('<print config="address">',$address,$html);
	if($config['suburb']!='')$suburb=$config['suburb'].', ';else$suburb='';
	$html=str_replace('<print config="suburb">',$suburb,$html);
	if($config['postcode']!=0)$postcode=$config['postcode'].', ';else$postcode='';
	$html=str_replace('<print config="postcode">',$postcode,$html);
	if($config['country']!='')$country=$config['country'];else$country='';
	$html=str_replace('<print config="country">',$country,$html);
	if($config['email']!='')$email='<a href="contactus/">'.$config['email'].'</a>';else$email='';
	$html=str_replace('<print config="email">',$email,$html);
	if($config['phone']!='')$phone='<a href="tel:'.$config['phone'].'">'.$config['phone'].'</a>';else$phone='';
	$html=str_replace('<print config="phone">',$phone,$html);
	if($config['mobile']!='')$mobile='<span class="mobile"><a href="tel:'.$config['mobile'].'">'.$config['mobile'].'</a></span>';else$mobile='';
	$html=str_replace('<print config="mobile">',$mobile,$html);
}
if(stristr($html,'<subjectText>')){
	$s=$db->prepare("SELECT * FROM choices WHERE contentType='subject' ORDER BY title ASC");
	$s->execute();
	if($s->rowCount()>0){
		$html=preg_replace('~<subjectText>.*?<\/subjectText>~is','',$html,1);
		$html=str_replace('<subjectSelect>','',$html);
		$html=str_replace('</subjectSelect>','',$html);
		$options='';
		while($r=$s->fetch(PDO::FETCH_ASSOC))$options.='<option value="'.$r['id'].'" role="option">'.$r['title'].'</option>';
		$html=str_replace('<subjectOptions>',$options,$html);
	}else{
		$html=preg_replace('~<subjectSelect>.*?<\/subjectSelect>~is','',$html,1);
		$html=str_replace('<subjectText>','',$html);
		$html=str_replace('</subjectText>','',$html);
	}
}
if(stristr($html,'<buildMenu')){
	$s=$db->query("SELECT * FROM menu WHERE menu='footer' AND active='1' ORDER BY ord ASC");
	preg_match('/<buildMenu>([\w\W]*?)<\/buildMenu>/',$html,$matches);
	$htmlMenu=$matches[1];
	$menu='';
	while($r=$s->fetch(PDO::FETCH_ASSOC)){
		$buildMenu=$htmlMenu;
		if($view==$r['contentType']||$view==$r['contentType'].'s')$buildMenu=str_replace('<print active=menu>',' active',$buildMenu);else$buildMenu=str_replace('<print active=menu>','',$buildMenu);
		if($r['contentType']!='index'){
			if(isset($r['url'][0])&&$r['url'][0]=='#')$buildMenu=str_replace('<print menu=url>',URL.$r['url'],$buildMenu);
			elseif(filter_var($r['url'],FILTER_VALIDATE_URL))$buildMenu=str_replace('<print menu=url>',$r['url'],$buildMenu);
			else$buildMenu=str_replace('<print menu=url>',URL.$r['contentType'],$buildMenu);
			$buildMenu=str_replace('<print rel=contentType>',strtolower($r['contentType']),$buildMenu);
		}else{
			$buildMenu=str_replace('<print menu=url>',URL,$buildMenu);
			$buildMenu=str_replace('<print rel=contentType>','home',$buildMenu);
		}
		$buildMenu=str_replace('<print menu="title">',$r['title'],$buildMenu);
		if($r['contentType']=='cart')$buildMenu=str_replace('<menuCart>',$cart,$buildMenu);else$buildMenu=str_replace('<menuCart>','',$buildMenu);
		if($r['contentType']=='cart')$buildMenu=str_replace('<menuCart>',$cart,$buildMenu);else$buildMenu=str_replace('<menuCart>','',$buildMenu);
		$menu.=$buildMenu;
	}
	$html=str_replace('<buildMenu>',$menu.'<buildMenu>',$html);
	$html=preg_replace('~<buildMenu>.*?<\/buildMenu>~is','',$html,1);
}
if(stristr($html,'<buildSocial')){
	preg_match('/<buildSocial>([\w\W]*?)<\/buildSocial>/',$html,$matches);
	$htmlSocial=$matches[1];
	$socialItems='';
	$s=$db->query("SELECT * FROM choices WHERE contentType='social' AND uid=0 ORDER BY icon ASC");
	if($s->rowCount()>0){
		while($r=$s->fetch(PDO::FETCH_ASSOC)){
			$buildSocial=$htmlSocial;
			$buildSocial=str_replace('<print sociallink>',$r['url'],$buildSocial);
			$buildSocial=str_replace('<print socialicon>',frontsvg('social-'.$r['icon']),$buildSocial);
			$socialItems.=$buildSocial;
		}
	}else$socialItems='';
	$html=preg_replace('~<buildSocial>.*?<\/buildSocial>~is',$socialItems,$html,1);
	if($config['options']{9}==1){
		$html=str_replace('<rss>','',$html);
		$html=str_replace('</rss>','',$html);
		if($page['contentType']=='article'||$page['contentType']=='portfolio'||$page['contentType']=='event'||$page['contentType']=='news'||$page['contentType']=='inventory'||$page['contentType']=='service')$html=str_replace('<print rsslink>','rss/'.$page['contentType'],$html);else$html=str_replace('<print rsslink>','rss',$html);
		$html=str_replace('<print rssicon>',frontsvg('social-rss'),$html);
	}else$html=preg_replace('~<rss>.*?<\/rss>~is','',$html,1);
}
$content.=$html;
