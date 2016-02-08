<?php
if(file_exists(THEME.DS.'side_menu.html')){
	$sideTemp=file_get_contents(THEME.DS.'side_menu.html');
	preg_match('/<item>([\w\W]*?)<\/item>/',$sideTemp,$matches);
	$outside=$matches[1];
	$show='';
	$contentType=$view;
	preg_match('/<heading>([\w\W]*?)<\/heading>/',$outside,$matches);
	if($matches[1]!=''){
		$heading=$matches[1];
		$heading=str_replace('<print viewlink>',URL.$view,$heading);
		if($view=='article'||$view=='service')$suffix='s';else$suffix='';
		$heading=str_replace('<print view>',ucfirst($view).$suffix,$heading);
	}else$heading='';
	if(stristr($sideTemp,'<settings')){
		preg_match('/<settings items="(.*?)" contenttype="(.*?)">/',$outside,$matches);
		if(isset($matches[1])){
			if($matches[1]=='all'||$matches[1]=='')$show='';
			elseif($matches[1]=='limit')$show=' LIMIT '.$config['showItems'];
			else$show=' LIMIT '.$matches[1];
		}else$show='';
		if(isset($matches[2])){
			if($matches[2]=='current')$contentType=strtolower($view.'%');
			if($matches[2]=='all'||$matches[2]==''){$contentType='';$heading='';}
		}else$contentType='';
	}
	preg_match('/<items>([\w\W]*?)<\/items>/',$outside,$matches);
	$insides=$matches[1];
	$s=$db->prepare("SELECT * FROM content WHERE contentType LIKE :contentType ORDER BY ti DESC $show");
	$s->execute(array(':contentType'=>$contentType.'%'));
	$output='';
	while($r=$s->fetch(PDO::FETCH_ASSOC)){
		if($r['contentType']=='gallery'){
			preg_match('/<media>([\w\W]*?)<\/media>/',$insides,$matches);
			$inside=$matches[1];
		}else$inside=preg_replace('/<media>([\w\W]*?)<\/media>/','',$insides,1);
		$items=$inside;
		$items=str_replace('<print content=thumb>',URL.'media/'.$r['thumb'],$items);
		$items=str_replace('<print link>',URL.$r['contentType'].'/'.str_replace(' ','-',$r['title']),$items);
		$items=str_replace('<print content=schematype>',$r['schemaType'],$items);
		$items=str_replace('<print metaDate',date('Y-m-d',$r['ti']),$items);
		$items=str_replace('<print content="title">',$r['title'],$items);
		$time=date($config['dateFormat'],$r['ti']);
		if($r['contentType']=='events'||$r['contentType']=='news'){
			if($r['tis']!=0){
				$sDay=date('dS',$r['tis']);
				$sMonth=date('M',$r['tis']);
				$sTime=date('H:i',$r['tis']);
				$time=$sDay.' '.$sMonth.' '.$sTime;
				if($r['tie']!=0){
					$eDay=date('dS',$rm['tie']);
					$eMonth=date('M',$rm['tie']);
					$eTime=date('H:i',$rm['tie']);
					$time.=' &rarr; <time><small>';
					if($sDay!=$eDay)$time.=$eDay.' ';
					if($sMonth!=$eMonth)$time.=$eMonth.' ';
					if($sTime!=$eTime)$time.=$eTime;
					$time.='</small></time>';
				}
			}
		}
		$items=str_replace('<print time>',$time,$items);
		$items=str_replace('<print content="caption">',$r['caption'],$items);
		$output.=$items;
	}
	$outside=preg_replace('~<heading>.*?<\/heading>~is',$heading,$outside,1);
	$outside=preg_replace('~<items>.*?<\/items>~is',$output,$outside,1);
	$outside=preg_replace('~<settings.*?>~is','',$outside,1);
	$sideTemp=preg_replace('~<item>.*?<\/item>~is',$outside,$sideTemp,1);
}else$sideTemp='';
$content.=$sideTemp;
