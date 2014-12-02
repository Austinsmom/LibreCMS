<?php
$content.='<main id="content" class="col-md-12"><div class="panel panel-default"><div class="panel-body">';
if($user['rank']>699){
	if($args[0]=='add'){
		$type=filter_input(INPUT_GET,'type',FILTER_SANITIZE_STRING);
		$q=$db->prepare("INSERT INTO login (rank,active,ti) VALUES ('0','1',:ti)");
		$q->execute(array(':ti'=>$ti));
		$args[1]=$db->lastInsertId();
		$show="User ".$args[1];
		$q=$db->prepare("UPDATE login SET username=:username WHERE id=:id");
		$q->execute(array(':username'=>$show,':id'=>$args[1]));
		$args[0]='edit';
	}
	if($args[0]=='edit'){
		$q=$db->prepare("SELECT * FROM login WHERE id=:id");
		$q->execute(array(':id'=>$args[1]));
		$r=$q->fetch(PDO::FETCH_ASSOC);
		$content.='<div class="form-group"><label for="ti" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4">Created</label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><input type="text" id="ti" class="form-control textinput" value="'.date($config['dateFormat'],$r['ti']).'" readonly></div></div><div class="form-group"><label for="username" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4">Username</label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><input type="text" id="username" class="form-control textinput" value="'.$r['username'].'" data-dbid="'.$r['id'].'" data-dbt="login" data-dbc="username" placeholder="Enter a Username..."><div id="usernamesave" class="input-group-btn hidden"><button class="btn btn-danger">Save</button></div></div></div><div class="form-group"><label for="password" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4">Password</label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><input type="password" id="password" class="form-control textinput" value="'.$r['password'].'" data-dbid="'.$r['id'].'" data-dbt="login" data-dbc="password" placeholder="Enter a Password..."><div id="passwordsave" class="input-group-btn hidden"><button class="btn btn-danger">Save</button></div></div></div><div class="form-group"><label for="rank" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4">Rank</label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8">';
		if($r['rank']<1000){
            $content.='<select id="rank" class="form-control" onchange="update(\''.$r['id'].'\',\'login\',\'rank\',$(this).val());"><option value="0"';
            if($r['rank']==0){$content.=' selected';}
            $content.='>Visitor</option><option value="100"';
            if($r['rank']==100){$content.=' selected';}
            $content.='>Subscriber</option><option value="200"';
            if($r['rank']==200){$content.=' selected';}
            $content.='>Member</option><option value="300"';
            if($r['rank']==300){$content.=' selected';}
            $content.='>Client</option><option value="400"';
            if($r['rank']==400){$content.=' selected';}
            $content.='>Contributor</option><option value="500"';
            if($r['rank']==500){$content.=' selected';}
            $content.='>Moderator</option><option value="600"';
            if($r['rank']==600){$content.=' selected';}
            $content.='>Author</option><option value="700"';
            if($r['rank']==700){$content.=' selected';}
            $content.='>Editor</option><option value="800"';
            if($r['rank']==800){$content.=' selected';}
            $content.='>Manager</option><option value="900"';
            if($r['rank']==900){$content.=' selected';}
            $content.='>Administrator</option>';
			if($user['rank']==1000){
				$content.='<option value="1000"';if($r['rank']==1000){$content.=' selected';}
                $content.='>Developer</option>';
			}
            $content.='</select>';
		}else{
			$content.='Developer';
		}
				$content.='</div>';
			$content.='</div>';
		if($r['rank']<1000){
            $content.='<div class="well"><h4>Editing Permissions</h4><div class="form-group"><label for="options0" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4 text-right"><input type="checkbox" id="options0" data-dbid="'.$r['id'].'" data-dbt="login" data-dbc="options" data-dbb="0"';
            if($r['options']{0}==1){$content.=' checked';}
            $content.='></label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><strong>Add Content</strong></div></div><div class="form-group"><label for="options1" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4 text-right"><input type="checkbox" id="options1" data-dbid="'.$r['id'].'" data-dbt="login" data-dbc="options" data-dbb="1"';
            if($r['options']{1}==1){$content.=' checked';}
            $content.='></label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><strong>SEO Viewing/Editing</strong></div></div><div class="form-group"><label for="options2" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4 text-right"><input type="checkbox" id="options2" data-dbid="'.$r['id'].'" data-dbt="login" data-dbc="options" data-dbb="2"';
            if($r['options']{2}==1){$content.=' checked';}
            $content.='></label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><strong>Message Viewing/Editing</strong></div></div><div class="form-group"><label for="options3" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4 text-right"><input type="checkbox" id="options3" data-dbid="'.$r['id'].'" data-dbt="login" data-dbc="options" data-dbb="3"';
            if($r['options']{3}==1){$content.=' checked';}
            $content.='></label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><strong>Orders Viewing/Editing</strong></div></div><div class="form-group"><label for="options4" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4 text-right"><input type="checkbox" id="options4" data-dbid="'.$r['id'].'" data-dbt="login" data-dbc="options" data-dbb="4"';
            if($r['options']{4}==1){$content.=' checked';}
            $content.='></label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><strong>Administration Viewing/Editing</strong></div></div><div class="form-group"><label for="options5" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4 text-right"><input type="checkbox" id="options5" data-dbid="'.$r['id'].'" data-dbt="login" data-dbc="options" data-dbb="5"';
            if($r['options']{5}==1){$content.=' checked';}
            $content.='></label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><strong>User Accounts Viewing/Editing</strong></div></div></div>';
        }
        $content.='<div class="well"><form target="sp" method="post" enctype="multipart/form-data" action="includes/add_data.php"><input type="hidden" name="id" value="'.$r['id'].'"><input type="hidden" name="act" value="add_avatar"><div class="form-group"><label for="avatar" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4">Avatar</label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><input type="file" name="fu" class="form-control" data-icon="false"><div class="input-group-btn"><button class="btn btn-default" type="submit">Upload</button></div></div></div></form><div class="form-group"><label for="gravatar" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4">Gravatar</label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><input type="text" id="gravatar" class="form-control textinput" value="'.$r['gravatar'].'" data-dbid="'.$r['id'].'" data-dbt="login" data-dbc="gravatar" placeholder="Enter Gravatar Link..."><div id="gravatarsave" class="input-group-btn hidden"><button class="btn btn-danger">Save</button></div></div></div><div class="form-group"><label class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4">&nbsp;</label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><div class="alert alert-info"><a target="_blank" href="http://www.gravatar.com/">Gravatar</a> Link will override any image uploaded as your Avatar.</div></div></div></div><div class="form-group"><label for="email" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4">Email</label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><input type="text" id="email" class="form-control textinput" value="'.$r['email'].'" data-dbid="'.$r['id'].'" data-dbt="login" data-dbc="email" placeholder="Enter an Email..."><div id="emailsave" class="input-group-btn hidden"><button class="btn btn-danger">Save</button></div></div></div><div class="form-group"><label for="name" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4">Name</label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><input type="text" id="name" class="form-control textinput" value="'.$r['name'].'" data-dbid="'.$r['id'].'" data-dbt="login" data-dbc="name" placeholder="Enter a Name..."><div id="namesave" class="input-group-btn hidden"><button class="btn btn-danger">Save</button></div></div></div><div class="form-group"><label for="url" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4">URL</label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><input type="text" id="url" class="form-control textinput" value="'.$r['url'].'" data-dbid="'.$r['id'].'" data-dbt="login" data-dbc="url" placeholder="Enter a URL..."><div id="urlsave" class="input-group-btn hidden"><button class="btn btn-danger">Save</button></div></div></div><div class="form-group"><label for="business" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4">Business</label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><input type="text" id="business" class="form-control textinput" value="'.$r['business'].'" data-dbid="'.$r['id'].'" data-dbt="login" data-dbc="business" placeholder="Enter a Business..."><div id="businesssave" class="input-group-btn hidden"><button class="btn btn-danger">Save</button></div></div></div><div class="form-group"><label for="phone" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4">Phone</label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><input type="text" id="phone" class="form-control textinput" value="'.$r['phone'].'" data-dbid="'.$r['id'].'" data-dbt="login" data-dbc="phone" placeholder="Enter a Phone Number..."><div id="phonesave" class="input-group-btn hidden"><button class="btn btn-danger">Save</button></div></div></div><div class="form-group"><label for="mobile" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4">Mobile</label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><input type="text" id="mobile" class="form-control textinput" value="'.$r['mobile'].'" data-dbid="'.$r['id'].'" data-dbt="login" data-dbc="mobile" placeholder="Enter a Mobile Number..."><div id="mobilesave" class="input-group-btn hidden"><button class="btn btn-danger">Save</button></div></div></div><div class="form-group"><label for="address" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4">Address</label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><input type="text" id="address" class="form-control textinput" name="address" value="'.$r['address'].'" data-dbid="'.$r['id'].'" data-dbt="login" data-dbc="address" placeholder="Enter an Address..."><div id="addresssave" class="input-group-btn hidden"><button class="btn btn-danger">Save</button></div></div></div><div class="form-group"><label for="suburb" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4">Suburb</label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><input type="text" id="suburb" class="form-control textinput" name="suburb" value="'.$r['suburb'].'" data-dbid="'.$r['id'].'" data-dbt="login" data-dbc="suburb" placeholder="Enter a Suburb..."><div id="suburbsave" class="input-group-btn hidden"><button class="btn btn-danger">Save</button></div></div></div><div class="form-group"><label for="city" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4">City</label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><input type="text" id="city" class="form-control textinput" name="city" value="'.$r['city'].'" data-dbid="'.$r['id'].'" data-dbt="login" data-dbc="city" placeholder="Enter a City..."><div id="citysave" class="input-group-btn hidden"><button class="btn btn-danger">Save</button></div></div></div><div class="form-group"><label for="state" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4">State</label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><input type="text" id="state" class="form-control textinput" name="state" value="'.$r['state'].'" data-dbid="'.$r['id'].'" data-dbt="login" data-dbc="state" placeholder="Enter a State..."><div id="statesave" class="input-group-btn hidden"><button class="btn btn-danger">Save</button></div></div></div><div class="form-group"><label for="postcode" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4">Postcode</label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><input type="text" id="postcode" class="form-control textinput" name="postcode" value="';
        if($r['postcode']!=0){$content.=$r['postcode'];}
        $content.='" data-dbid="'.$r['id'].'" data-dbt="login" data-dbc="postcode" placeholder="Enter a Postcode..."><div id="postcodesave" class="input-group-btn hidden"><button class="btn btn-danger">Save</button></div></div></div><div class="form-group"><label for="order_notes" class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4">About</label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><form method="post" target="sp" action="includes/update.php"><input type="hidden" name="id" value="'.$r['id'].'"><input type="hidden" name="t" value="login"><input type="hidden" name="c" value="notes"><textarea id="notes" class="form-control summernote" name="da">'.$r['notes'].'</textarea></form></div></div><div class="well"><h4>Social Networking</h4><div class="form-group"><label class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4">&nbsp;</label><form target="sp" method="post" action="includes/add_data.php"><input type="hidden" name="user" value="'.$r['id'].'"><input type="hidden" name="act" value="add_social"><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><span class="input-group-addon">Network</span><select class="form-control" name="icon"><option value="">None</option><option value="500px">500px</option><option value="behance-square">Behance</option><option value="blogger">Blogger</option><option value="delicious">Delcicious</option><option value="deviantart">DeviantArt</option><option value="dribble">Dribble</option><option value="facebook-square">Facebook</option><option value="flickr">Flickr</option><option value="forrst">Forrst</option><option value="github-square">GitHub</option><option value="google-plus-square">Google+</option><option value="instagram">Instagram</option><option value="lastfm-square">LastFM</option><option value="linkedin-square">Linkedin</option><option value="livejournal">LiveJournal</option><option value="myspace">MySpace</option><option value="pied-piper">Pied Piper</option><option value="pinterest-square">Pinterest</option><option value="skype">Skype</option><option value="stack-overflow">StackOverflow</option><option value="stumbleupon-circle">StumbleUpon</option><option value="tumblr-square">Tumblr</option><option value="twitter-square">Twitter</option><option value="vimeo-square">Vimeo</option><option value="youtube-square">YouTube</option></select><div class="input-group-addon">URL</div><input type="text" class="form-control" name="url" value="" placeholder="Enter a URL..."><div class="input-group-btn"><button class="btn btn-success">Add</button></div></div></form></div><div id="social">';
        $ss=$db->prepare("SELECT * FROM choices WHERE uid=:uid ORDER BY icon ASC");
		$ss->execute(array(':uid'=>$r['id']));
		while($rs=$ss->fetch(PDO::FETCH_ASSOC)){
			$content.='<div id="l_'.$rs['id'].'" class="form-group"><label class="control-label col-lg-1 col-md-2 col-sm-2 col-xs-4">&nbsp;</label><div class="input-group col-lg-11 col-md-10 col-sm-10 col-xs-8"><div class="input-group-addon">Network</div><div class="input-group-addon"><i class="fa fa-'.$rs['icon'].'"></i></div><div class="input-group-addon">URL</div><input type="text" class="form-control" value="'.$rs['url'].'" onchange="update(\''.$rs['id'].'\',\'social\',\'url\',$(this).val());" placeholder="Enter a URL..."><div class="input-group-btn"><form target="sp" action="includes/purge.php"><input type="hidden" name="id" value="'.$rs['id'].'"><input type="hidden" name="t" value="choices"><button class="btn btn-danger"><i class="fa fa-trash"></i></button></form></div></div></div>';
		}
        $content.='</div></div>';
	}else{
		$content.='<div class="table-responsive"><table class="table table-condensed sort_table"><thead><tr><th>Username</th><th>Name</th><th>Email</th><th class="col-sm-2">Rank</th><th class="col-sm-3"><div class="input-group" data-tooltip data-original-title="Enter text to filter out unwanted Accounts"><input type="text" class="form-control filter" placeholder="Enter Text to Filter..."><div class="input-group-addon">Filter</div></div></th><th class="hidden"></th><th class="hidden"></th></tr></thead><tbody id="sort">';
		$s=$db->prepare("SELECT * FROM login WHERE rank<:rank ORDER BY ti ASC");
		$s->execute(array(':rank'=>$user['rank']+1));
		while($r=$s->fetch(PDO::FETCH_ASSOC)){
			if($user['rank']>900&&$r['status']=='delete')continue;
            $content.='<tr id="l_'.$r['id'].'" data-id="'.$r['id'].'" class="handle placeholder';
            if($r['status']=='delete'){$content.=' danger';}
            $content.='"><td>'.$r['username'].'</td><td>'.$r['name'].'</td><td><a href="mailto:'.$r['email'].'">'.$r['email'].'</a></td><td>'.$r['rank'].'</td><td id="controls_'.$r['id'].'" class="text-right"><a class="btn btn-primary btn-xs';
            if($r['status']=='delete'){$content.=' hidden';}
            $content.='" href="admin/accounts/edit/'.$r['id'].'">View</a> <button class="btn btn-primary btn-xs';
            if($r['status']!='delete'){$content.=' hidden';}
            $content.='" onclick="updateButtons(\''.$r['id'].'\',\'login\',\'status\',\'\')">Restore</button> <button class="btn btn-danger btn-xs';
            if($r['status']=='delete'){$content.=' hidden';}
            $content.='" onclick="updateButtons(\''.$r['id'].'\',\'login\',\'status\',\'delete\')">Delete</button> ';
			if($user['rank']>900){
				$content.='<button class="btn btn-warning btn-xs';if($r['status']!='delete'){$content.=' hidden';}$content.='" onclick="purge(\''.$r['id'].'\',\'login\')">Purge</button>';
			}
			$content.='</td><td class="hidden">'.$r['notes'].'</td></tr>';
		}
        $content.='</tbody></table></div>';
	}
}else{
	include'includes/noaccess.php';
}
$content.='</div></div></main>';
