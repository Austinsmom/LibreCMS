<?php
if($args[0]=='add'){
	$type=filter_input(INPUT_GET,'type',FILTER_SANITIZE_STRING);
	$q=$db->prepare("INSERT INTO login (options,rank,active,ti) VALUES ('00000000','0','1',:ti)");
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
	$r=$q->fetch(PDO::FETCH_ASSOC);?>
<div class="page-toolbar">
	<div class="btn-group pull-right">
		<a class="btn btn-success" href="<?php echo URL;?>admin/accounts"<?php if($config['options']{4}==1)echo' data-toggle="tooltip" data-placement="left" title="';lang('tooltip','back');echo'"';?>><i class="libre libre-back"></i></a>
	</div>
</div>
<div class="panel panel-default">
	<div id="covertop">
		<div class="badger badger-left text-shadow-depth-1" data-status="<?php if($r['active']==1)echo'active';else echo'inactive';?>" data-contenttype="<?php lang('rank',$r['rank']);?>"></div>
		<div id="coverimg">
			<img class="cover" src="<?php if($r['cover']!=''&&file_exists('media/'.$r['cover']))echo'media/'.$r['cover'];elseif(file_exists('media/'.$r['coverURL']))echo'media/'.$r['coverURL'];elseif($r['coverURL']!='')echo$r['coverURL'];?>">
			<h3 class="name text-shadow-depth-1"><?php if($r['name']!='')echo$r['name'];else echo$r['username'];?></h3>
		</div>
		<img class="avatar img-thumbnail shadow-depth-1" src="<?php if($r['gravatar']!='')echo$r['gravatar'];elseif($r['avatar']!=''&&file_exists('media/avatar/'.$r['avatar']))echo'media/avatar/'.$r['avatar'];else echo$noavatar;?>">
	</div>
	<div class="panel-body">
		<div class="form-group">
			<label for="ti" class="control-label col-xs-4 col-sm-3 col-md-3 col-lg-2"><?php lang('label','created');?></label>
			<div class="input-group col-xs-8 col-sm-9 col-md-9 col-lg-10">
				<input type="text" id="ti" class="form-control textinput" value="<?php echo date($config['dateFormat'],$r['ti']);?>" readonly>
			</div>
		</div>
		<div class="form-group">
			<label for="username" class="control-label col-xs-4 col-sm-3 col-md-3 col-lg-2"><?php lang('label','username');?></label>
			<div class="input-group col-xs-8 col-sm-9 col-md-9 col-lg-10">
				<input type="text" id="username" class="form-control textinput" value="<?php echo$r['username'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="username" placeholder="<?php lang('placeholder','username');?>">
			</div>
		</div>
		<div class="form-group">
			<label for="password" class="control-label col-xs-4 col-sm-3 col-md-3 col-lg-2"><?php lang('label','password');?></label>
			<div class="input-group col-xs-8 col-sm-9 col-md-9 col-lg-10">
				<input type="password" id="password" class="form-control textinput" value="<?php echo$r['password'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="password" placeholder="<?php lang('placeholder','password');?>">
			</div>
		</div>
		<div class="form-group">
			<label for="rank" class="control-label col-xs-4 col-sm-3 col-md-3 col-lg-2"><?php lang('label','rank');?></label>
			<div class="input-group col-xs-8 col-sm-9 col-md-9 col-lg-10">
<?php if($r['rank']<1000){?>
				<select id="rank" class="form-control" onchange="update('<?php echo$r['id'];?>','login','rank',$(this).val());">
					<option value="0"<?php if($r['rank']==0)echo' selected';?>><?php lang('rank','visitor');?></option>
					<option value="100"<?php if($r['rank']==100)echo' selected';?>><?php lang('rank','subscriber');?></option>
					<option value="200"<?php if($r['rank']==200)echo' selected';?>><?php lang('rank','member');?></option>
					<option value="300"<?php if($r['rank']==300)echo' selected';?>><?php lang('rank','client');?></option>
					<option value="400"<?php if($r['rank']==400)echo' selected';?>><?php lang('rank','contributor');?></option>
					<option value="500"<?php if($r['rank']==500)echo' selected';?>><?php lang('rank','author');?></option>
					<option value="600"<?php if($r['rank']==600)echo' selected';?>><?php lang('rank','editor');?></option>
					<option value="700"<?php if($r['rank']==700)echo' selected';?>><?php lang('rank','moderator');?></option>
					<option value="800"<?php if($r['rank']==800)echo' selected';?>><?php lang('rank','manager');?></option>
					<option value="900"<?php if($r['rank']==900)echo' selected';?>><?php lang('rank','administrator');?></option>
<?php	if($_SESSION['rank']==1000){?>
					<option value="1000"<?php if($r['rank']==1000)echo' selected';?>><?php lang('rank','developer');?></option>
<?php	}?>
				</select>
<?php }else{
		lang('rank','developer');
	}?>
			</div>
		</div>
		<div class="form-group">
			<label for="language" class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2"><?php lang('label','language');?></label>
			<div class="input-group col-xs-8 col-sm-9 col-md-9 col-lg-10">
				<select id="language" class="form-control" onchange="update('<?php echo$r['id'];?>','login','language',$(this).val());">
<?php	$languages=parse_ini_file('core/lang/languages.ini');
		foreach($languages as$lang){
			$l=explode(':',$lang);?>
					<option value="<?php echo$l[0];?>"<?php if($user['language']==$l[0])echo' selected';?>><?php echo$l[1];?></option>
<?php	}?>
				</select>
			</div>
		</div>
<?php if($r['rank']<1000){?>
		<div class="well">
			<h4><?php lang('title','account_permissions');?></h4>
			<div class="form-group">
				<div class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2 text-right">
					<input type="checkbox" id="options0" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="options" data-dbb="0"<?php if($r['options']{0}==1)echo' checked';?>><label for="options0">
				</div>
				<label for="options0" div class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
					<strong><?php lang('accounts','options0');?></strong>
				</label>
			</div>
			<div class="form-group">
				<div class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2 text-right">
					<input type="checkbox" id="options1" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="options" data-dbb="1"<?php if($r['options']{1}==1)echo' checked';?>><label for="options1">
				</div>
				<label for="options1" class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
					<strong><?php lang('accounts','options1');?></strong>
				</label>
			</div>
			<div class="form-group">
				<div class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2 text-right">
					<input type="checkbox" id="options2" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="options" data-dbb="2"<?php if($r['options']{2}==1)echo' checked';?>><label for="options2">
				</div>
				<label for="options2" class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
					<strong><?php lang('accounts','options2');?></strong>
				</label>
			</div>
			<div class="form-group">
				<div class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2 text-right">
					<input type="checkbox" id="options3" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="options" data-dbb="3"<?php if($r['options']{3}==1)echo' checked';?>><label for="options3">
				</div>
				<label for="options3" class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
					<strong><?php lang('accounts','options3');?></strong>
				</label>
			</div>
			<div class="form-group">
				<div class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2 text-right">
					<input type="checkbox" id="options4" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="options" data-dbb="4"<?php if($r['options']{4}==1)echo' checked';?>><label for="options4">
				</div>
				<label for="options4" div class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
					<strong><?php lang('accounts','options4');?></strong>
				</label>
			</div>
			<div class="form-group">
				<div class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2 text-right">
					<input type="checkbox" id="options5" data-dbid="'.$r['id'].'" data-dbt="login" data-dbc="options" data-dbb="5"<?php if($r['options']{5}==1)echo' checked';?>><label for="options5">
				</div>
				<label for="options5" class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
					<strong><?php lang('accounts','options5');?></strong>
				</label>
			</div>
			<div class="form-group">
				<div for="options6" class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2 text-right">
					<input type="checkbox" id="options6" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="options" data-dbb="6"<?php if($r['options']{6}==1)echo' checked';?>><label for="options6">
				</div>
				<label for="options6" class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
					<strong><?php lang('accounts','options6');?></strong>
				</label>
			</div>
			<div class="form-group">
				<div class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2 text-right">
					<input type="checkbox" id="options7" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="options" data-dbb="7"<?php if($r['options']{7}==1)echo' checked';?>><label for="options7">
				</div>
				<label for="options7" div class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
					<strong><?php lang('accounts','options7');?></strong>
				</label>
			</div>
		</div>
<?php }?>
		<fieldset class="control-fieldset">
			<legend class="control-legend"><?php lang('title','cover/avatar');?></legend>
			<div class="form-group">
				<label for="cover" class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2"><?php lang('label','cover');?></label>
				<div class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
					<div class="input-group-addon"><i class="libre libre-link"></i></div>
					<input type="text" id="coverURL" class="form-control" value="<?php echo$r['coverURL'];?>" onchange="coverUpdate('<?php echo$r['id'];?>','login','coverURL',$(this).val());" placeholder="<?php lang('placeholder','cover');?>">
					<div class="input-group-btn">
						<a class="btn btn-info" data-toggle="modal" data-target="#media" href="core/edit_image.php?id=<?php echo$r['id'];?>&t=login&c=coverURL"><i class="libre libre-edit"></i></a>
						<button class="btn btn-danger" onclick="coverUpdate('<?php echo$r['id'];?>','login','coverURL','');"><i class="libre libre-trash"></i></button>
					</div>
				</div>
				<div class="help-block col-xs-7 col-sm-9 col-md-9 col-lg-10 pull-right">
					<?php lang('info','cover_0');?>
				</div>
			</div>
			<div class="form-group">
				<div class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10 pull-right">
					<input type="text" id="cover" class="form-control hidden-xs" value="<?php echo$r['cover'];?>" disabled>
					<div class="input-group-btn">
						<form method="post" target="sp" enctype="multipart/form-data" action="core/add_data.php">
							<input type="hidden" name="id" value="<?php echo$r['id'];?>">
							<input type="hidden" name="act" value="add_cover">
							<input type="hidden" name="t" value="login">
							<input type="hidden" name="c" value="cover">
							<div class="btn btn-info btn-file">
								<span class="libre-stack">
									<i class="libre libre-stack-1x libre-desktop"></i>
									<i class="libre libre-stack-1x libre-action text-info"></i>
									<i class="libre libre-stack-action libre-action-select"></i>
								</span>
								<input type="file" name="fu"<?php if($user['options']{1}==0)echo' disabled';?>>
							</div>
							<button class="btn btn-success<?php if($user['options']{1}==0)echo' disabled';?> hidden-xs" onclick="$('#block').css({'display':'block'});"><i class="libre libre-upload"></i></button>
						</form>
					</div>
					<div class="input-group-btn">
						<a class="btn btn-info" data-toggle="modal" data-target="#media" href="core/browse_media.php?id=<?php echo$r['id'];?>&t=login&c=cover">
							<span class="libre-stack">
								<i class="libre libre-stack-1x libre-picture"></i>
								<i class="libre libre-stack-1x libre-action text-info"></i>
								<i class="libre libre-stack-action libre-action-select"></i>
							</span>
						</a>
						<a class="btn btn-info" data-toggle="modal" data-target="#media" href="core/edit_image.php?id=<?php echo$r['id'];?>&t=login&c=cover"><i class="libre libre-edit"></i></a>
						<button class="btn btn-danger" onclick="coverUpdate('<?php echo$r['id'];?>','login','cover','');"><i class="libre libre-trash"></i></button>
					</div>
				</div>
			</div>
			<div class="help-block col-xs-7 col-sm-9 col-md-9 col-lg-10 pull-right">
				<?php lang('info','cover_1');?>
			</div>
			<div class="clearfix"></div>
			<div class="well col-xs-12 col-sm-10 pull-right">
				<h4><?php lang('title','image_attribution');?></h4>
				<div class="form-group">
					<label for="attributionImageTitle" class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2"><?php lang('label','title');?></label>
					<div class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
						<input type="text" id="attributionImageTitle" class="form-control textinput" value="<?php echo$r['attributionImageTitle'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="attributionImageTitle" placeholder="<?php lang('placeholder','title');?>">
					</div>
				</div>
				<div class="form-group">
					<label for="attributionImageName" class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2"><?php lang('label','name');?></label>
					<div class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
						<input type="text" id="attributionImageName" class="form-control textinput" value="<?php echo$r['attributionImageName'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="attributionImageName" placeholder="<?php lang('placeholder','name');?>">
					</div>
				</div>
				<div class="form-group">
					<label for="attributionImageURL" class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2"><?php lang('label','url');?></label>
					<div class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
						<input type="text" id="attributionImageURL" class="form-control textinput" value="<?php echo$r['attributionImageURL'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="attributionImageURL" placeholder="<?php lang('placeholder','url');?>">
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="form-group">
				<label for="avatar" class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2"><?php lang('label','avatar');?></label>
				<div class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
					<input type="text" class="form-control hidden-xs" value="<?php echo$r['avatar'];?>" disabled>
					<div class="input-group-btn">
						<form target="sp" method="post" enctype="multipart/form-data" action="core/add_data.php">
							<input type="hidden" name="id" value="<?php echo$r['id'];?>">
							<input type="hidden" name="act" value="add_avatar">
							<div class="btn btn-info btn-file">
								<span class="libre-stack">
									<i class="libre libre-stack-1x libre-desktop"></i>
									<i class="libre libre-stack-1x libre-action text-info"></i>
									<i class="libre libre-stack-action libre-action-select"></i>
								</span>
								<input type="file" name="fu"<?php if($user['options']{1}==0)echo' disabled';?>>
							</div>
							<button class="btn btn-success" type="submit"><i class="libre libre-upload"></i></button>
						</form>
					</div>
					<div class="input-group-btn">
						<button class="btn btn-danger" onclick="imageUpdate('<?php echo$r['id'];?>','login','avatar','');"><i class="libre libre-trash"></i></button>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="gravatar" class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2"><?php lang('label','gravatar');?></label>
				<div class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
					<input type="text" id="gravatar" class="form-control textinput" value="<?php echo$r['gravatar'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="gravatar" placeholder="<?php lang('placeholder','gravatar');?>">
				</div>
				<div class="help-block col-xs-7 col-sm-9 col-md-9 col-lg-10 pull-right">
					<a target="_blank" href="http://www.gravatar.com/">Gravatar</a><?php lang('info','gravatar');?>
				</div>
			</div>
		</fieldset>
		<div class="form-group">
			<label for="email" class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2"><?php lang('label','email');?></label>
			<div class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
				<input type="text" id="email" class="form-control textinput" value="<?php echo$r['email'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="email" placeholder="<?php lang('placeholder','email');?>">
			</div>
		</div>
		<div class="form-group">
			<label for="name" class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2"><?php lang('label','name');?></label>
			<div class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
				<input type="text" id="name" class="form-control textinput" value="<?php echo$r['name'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="name" placeholder="<?php lang('placeholder','name');?>">
			</div>
		</div>
		<div class="form-group">
			<label for="url" class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2"><?php lang('label','url');?></label>
			<div class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
				<input type="text" id="url" class="form-control textinput" value="<?php echo$r['url'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="url" placeholder="<?php lang('placeholder','url');?>">
			</div>
		</div>
		<div class="form-group">
			<label for="business" class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2"><?php lang('label','business');?></label>
			<div class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
				<input type="text" id="business" class="form-control textinput" value="<?php echo$r['business'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="business" placeholder="<?php lang('placeholder','business');?>">
			</div>
		</div>
		<div class="form-group">
			<label for="phone" class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2"><?php lang('label','phone');?></label>
			<div class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
				<input type="text" id="phone" class="form-control textinput" value="<?php echo$r['phone'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="phone" placeholder="<?php lang('placeholder','phone');?>">
			</div>
		</div>
		<div class="form-group">
			<label for="mobile" class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2"><?php lang('label','mobile');?></label>
			<div class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
				<input type="text" id="mobile" class="form-control textinput" value="<?php echo$r['mobile'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="mobile" placeholder="<?php lang('placeholder','mobile');?>">
			</div>
		</div>
		<div class="form-group">
			<label for="address" class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2"><?php lang('label','address');?></label>
			<div class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
				<input type="text" id="address" class="form-control textinput" name="address" value="<?php echo$r['address'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="address" placeholder="<?php lang('placeholder','address');?>">
			</div>
		</div>
		<div class="form-group">
			<label for="suburb" class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2"><?php lang('label','suburb');?></label>
			<div class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
				<input type="text" id="suburb" class="form-control textinput" name="suburb" value="<?php echo$r['suburb'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="suburb" placeholder="<?php lang('placeholder','suburb');?>">
			</div>
		</div>
		<div class="form-group">
			<label for="city" class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2"><?php lang('label','city');?></label>
			<div class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
				<input type="text" id="city" class="form-control textinput" name="city" value="<?php echo$r['city'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="city" placeholder="<?php lang('placeholder','city');?>">
			</div>
		</div>
		<div class="form-group">
			<label for="state" class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2"><?php lang('label','state');?></label>
			<div class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
				<input type="text" id="state" class="form-control textinput" name="state" value="<?php echo$r['state'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="state" placeholder="<?php lang('placeholder','state');?>">
			</div>
		</div>
		<div class="form-group">
			<label for="postcode" class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2"><?php lang('label','postcode');?></label>
			<div class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
				<input type="text" id="postcode" class="form-control textinput" name="postcode" value="<?php if($r['postcode']!=0)echo$r['postcode'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="login" data-dbc="postcode" placeholder="<?php lang('placeholder','postcode');?>">
			</div>
		</div>
		<div class="form-group">
			<label for="order_notes" class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2"><?php lang('label','about');?></label>
			<div class="input-group col-xs-7 col-sm-9 col-md-9 col-lg-10">
				<form method="post" target="sp" action="core/update.php">
					<input type="hidden" name="id" value="<?php echo$r['id'];?>">
					<input type="hidden" name="t" value="login">
					<input type="hidden" name="c" value="notes">
					<textarea id="notes" class="form-control summernote" name="da"><?php echo$r['notes'];?></textarea>
				</form>
			</div>
		</div>
		<div class="well">
			<h4><?php lang('title','social_networking');?></h4>
			<div class="form-group">
				<label class="control-label hidden-xs col-sm-3 col-md-3 col-lg-2">&nbsp;</label>
				<form target="sp" method="post" action="core/add_data.php">
					<input type="hidden" name="user" value="<?php echo$r['id'];?>">
					<input type="hidden" name="act" value="add_social">
					<div class="input-group col-xs-12 col-sm-9 col-md-9 col-lg-10">
						<span class="input-group-addon"><?php lang('label','network');?></span>
						<select class="form-control libre" name="icon">
							<option value=""><?php lang('none');?></option>
							<option value="500px">&#xe704; 500px</option>
							<option value="amazon">&#xe705; Amazon</option>
							<option value="behance">&#xe707; Behance</option>
							<option value="bitcoin">&#xe708; Bitcoin</option>
							<option value="blogger">&#xe709; Blogger</option>
							<option value="buffer">&#xe70a; Buffer</option>
							<option value="cargo">&#xe70b; Cargo</option>
							<option value="codepen">&#xe70c; Codepen</option>
							<option value="coroflot">&#xe70d; Coroflot</option>
							<option value="creatica">&#xe70e; Creatica</option>
							<option value="delicious">&#xe70f; Delcicious</option>
							<option value="deviantart">&#xe710; DeviantArt</option>
							<option value="diaspora">&#xe711; Diaspora</option>
							<option value="digg">&#xe712; Digg</option>
							<option value="dribbble">&#xe713; Dribbble</option>
							<option value="dropbox">&#xe714; Dropbox</option>
							<option value="envato">&#xe716; Envato</option>
							<option value="exposure">&#xe717; Exposure</option>
							<option value="facebook">&#xe718; Facebook</option>
							<option value="feedburner">&#xe719; Feedburner</option>
							<option value="flickr">&#xe71a; Flickr</option>
							<option value="forrst">&#xe71b; Forrst</option>
							<option value="github">&#xe71c; GitHub</option>
							<option value="google-plus">&#xe71e; Google+</option>
							<option value="gravatar">&#xe71f; Gravatar</option>
							<option value="hackernews">&#xe720; Hackernews</option>
							<option value="icq">&#xe721; ICQ</option>
							<option value="instagram">&#xe722; Instagram</option>
							<option value="kickstarter">&#xe723; Kickstarter</option>
							<option value="last-fm">&#xe724; Last FM</option>
							<option value="lego">&#xe725; Lego</option>
							<option value="linkedin">&#xe726; Linkedin</option>
							<option value="livejournal">&#xe727; LiveJournal</option>
							<option value="lynda">&#xe728; Lynda</option>
							<option value="massroots">&#xe72a; Massroots</option>
							<option value="medium">&#xe72b; Medium</option>
							<option value="netlify">&#xe72c; Netlify</option>
							<option value="ovh">&#xe72d; OVH</option>
							<option value="paypal">&#xe72e; Paypal</option>
							<option value="periscope">&#xe72f; Periscope</option>
							<option value="picasa">&#xe730; Picasa</option>
							<option value="pinterest">&#xe731; Pinterest</option>
							<option value="play-store">&#xe732; Play Store</option>
							<option value="quora">&#xe733; Quora</option>
							<option value="redbubble">&#xe734; Red Bubble</option>
							<option value="reddit">&#xe735; Reddit</option>
							<option value="sharethis">&#xe737; Sharethis</option>
							<option value="skype">&#xe738; Skype</option>
							<option value="snapchat">&#xe739; Snapchat</option>
							<option value="soundcloud">&#xe73a; Soundcloud</option>
							<option value="stackoverflow">&#xe73b; Stackoverflow</option>
							<option value="steam">&#xe73c; Steam</option>
							<option value="stumbleupon">&#xe73d; StumbleUpon</option>
							<option value="tsu">&#xe73f; TSU</option>
							<option value="tumblr">&#xe740; Tumblr</option>
							<option value="twitch">&#xe741; Twitch</option>
							<option value="twitter">&#xe742; Twitter</option>
							<option value="ubiquiti">&#xe743; Ubiquiti</option>
							<option value="unsplash">&#xe744; Unsplash</option>
							<option value="vimeo">&#xe745; Vimeo</option>
							<option value="vine">&#xe746; Vine</option>
							<option value="whatsapp">&#xe747; Whatsapp</option>
							<option value="wikipedia">&#xe748; Wikipedia</option>
							<option value="windows-store">&#xe749; Windows Store</option>
							<option value="xbox-live">&#xe74a; Xbox Live</option>
							<option value="yahoo">&#xe74b; Yahoo</option>
							<option value="yelp">&#xe74c; Yelp</option>
							<option value="youtube">&#xe74d; YouTube</option>
							<option value="zerply">&#xe74e; Zerply</option>
							<option value="zune">&#xe74f; Zune</option>
						</select>
						<div class="input-group-addon"><?php lang('label','url');?></div>
						<input type="text" class="form-control" name="url" value="" placeholder="<?php lang('placeholder','url');?>">
						<div class="input-group-btn">
							<button class="btn btn-success"><i class="libre libre-plus"></i></button>
						</div>
					</div>
				</form>
			</div>
			<div id="social">
<?php $ss=$db->prepare("SELECT * FROM choices WHERE contentType='social' AND uid=:uid ORDER BY icon ASC");
	$ss->execute(array(':uid'=>$r['id']));
	while($rs=$ss->fetch(PDO::FETCH_ASSOC)){?>
				<div id="l_<?php echo$rs['id'];?>" class="form-group">
					<label class="control-label hidden-xs col-sm-3 col-md-3 col-lg-2">&nbsp;</label>
					<div class="input-group col-xs-12 col-sm-9 col-md-9 col-lg-10">
						<div class="input-group-addon">
							<span class="libre-stack"><i class="libre libre-square-rounded libre-stack-1x"></i><i class="libre libre-social-<?php echo$rs['icon'];?> libre-stack-1x text-white"></i></span><span class="hidden-xs">&nbsp;&nbsp;<?php echo ucfirst($rs['icon']);?></span></div>
						<input type="text" class="form-control" value="<?php echo$rs['url'];?>" onchange="update('<?php echo$rs['id'];?>','social','url',$(this).val());" placeholder="<?php lang('placeholder','url');?>">
						<div class="input-group-btn">
							<form target="sp" action="core/purge.php">
								<input type="hidden" name="id" value="<?php echo$rs['id'];?>">
								<input type="hidden" name="t" value="choices">
								<button class="btn btn-danger"><i class="libre libre-trash visible-xs"></i><span class="hidden-xs"><?php lang('button','delete');?></span></button>
							</form>
						</div>
					</div>
				</div>
<?php }?>
			</div>
		</div>
	</div>
</div>
<?php }else{?>
<div class="page-toolbar">
<?php	if($args[1]!=''){?>
	<ol class="breadcrumb col-xs-6">
		<li><a href="<?php echo URL;?>admin/accounts"><?php lang('Accounts');?></a></li>
		<li><?php echo ucfirst(lang('rank',$args[1]));?></li>
	</ol>
<?php	}
		if($user['layoutAccounts']=='')$user['layoutAccounts']=$config['layoutAccounts'];
		if($user['layoutContent']=='')$user['layoutContent']=$config['layoutContent'];?>
	<div class="pull-right">
		<div class="btn-group">
			<div class="btn-group" data-toggle="buttons">
				<label class="btn btn-default<?php if($user['layoutAccounts']=='card'){echo' active';}?>"<?php if($config['options']{4}==1){echo' data-toggle="tooltip" data-placement="left" title="';lang('tooltip','layout_card');echo'"';}?>><input type="radio" name="options" id="option1" autocomplete="off" onchange="update('<?php echo$user['id'];?>','login','layoutAccounts','card');"<?php if($user['layoutAccounts']=='card'){echo' checked';}?>><i class="libre libre-layout-blocks"></i></label>
				<label class="btn btn-default<?php if($user['layoutAccounts']=='list'){echo' active';}?>"<?php if($config['options']{4}==1){echo' data-toggle="tooltip" data-placement="left" title="';lang('tooltip','layout_list');echo'"';}?>><input type="radio" name="options" id="option2" autocomplete="off" onchange="update('<?php echo$user['id'];?>','login','layoutAccounts','list');"<?php if($user['layoutAccounts']=='list'){echo' checked';}?>><i class="libre libre-layout-list"></i></label>
			</div>
		</div>
		<div class="btn-group">
			<button class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="libre libre-view"></i></button>
			<ul class="dropdown-menu pull-right">
				<li><a href="<?php echo URL.'admin/accounts';?>">All</a></li>
				<li><a href="<?php echo URL.'admin/accounts/type/visitor';?>"><?php lang('rank','visitor');?></a></li>
				<li><a href="<?php echo URL.'admin/accounts/type/subscriber';?>"><?php lang('rank','subscriber');?></a></li>
				<li><a href="<?php echo URL.'admin/accounts/type/member';?>"><?php lang('rank','member');?></a></li>
				<li><a href="<?php echo URL.'admin/accounts/type/client';?>"><?php lang('rank','client');?></a></li>
				<li><a href="<?php echo URL.'admin/accounts/type/contributor';?>"><?php lang('rank','contributor');?></a></li>
				<li><a href="<?php echo URL.'admin/accounts/type/author';?>"><?php lang('rank','author');?></a></li>
				<li><a href="<?php echo URL.'admin/accounts/type/editor';?>"><?php lang('rank','editor');?></a></li>
				<li><a href="<?php echo URL.'admin/accounts/type/moderator';?>"><?php lang('rank','moderator');?></a></li>
				<li><a href="<?php echo URL.'admin/accounts/type/manager';?>"><?php lang('rank','manager');?></a></li>
				<li><a href="<?php echo URL.'admin/accounts/type/administrator';?>"><?php lang('rank','administrator');?></a></li>
				<li><a href="<?php echo URL.'admin/accounts/type/developer';?>"><?php lang('rank','developer');?></a></li>
			</ul>
		</div>
<?php if($user['rank']==1000||$user['options']{0}==1){?>
		<div class="btn-group">
			<a class="btn btn-success" href="<?php echo URL;?>admin/accounts/add"><i class="libre libre-add"></i></a>
		</div>
<?php }?>
	</div>
</div>
<?php if($args[0]=='type'){
		if(isset($args[1])){
			$rank=0;
			if($args[1]=='subscriber')$rank=100;
			if($args[1]=='member')$rank=200;
			if($args[1]=='client')$rank==300;
			if($args[1]=='contributor')$rank=400;
			if($args[1]=='author')$rank=500;
			if($args[1]=='editor')$rank=600;
			if($args[1]=='moderator')$rank=700;
			if($args[1]=='manager')$rank=800;
			if($args[1]=='administrator')$rank=900;
			if($args[1]=='developer')$rank=1000;
		}
		$s=$db->prepare("SELECT * FROM login WHERE rank=:rank ORDER BY ti ASC");
		$s->execute(array(':rank'=>$rank));
	}else{
		$s=$db->prepare("SELECT * FROM login WHERE rank<:rank ORDER BY ti ASC");
		$s->execute(array(':rank'=>$_SESSION['rank']+1));
	}?>
<div id="listtype" class="<?php echo$user['layoutAccounts'];?>">
<?php while($r=$s->fetch(PDO::FETCH_ASSOC)){?>
	<div id="l_<?php echo$r['id'];?>" class="item">
		<div class="panel panel-default shadow-depth-1">
			<div class="badger badger-left text-shadow-depth-1" data-status="<?php if($r['active']==1)echo'active';else echo'inactive';?>" data-contenttype="<?php lang('rank',$r['rank']);?>"></div>
			<div class="panel-image">
				<img src="<?php if($r['cover']!=''&&file_exists('media/'.$r['cover']))echo'media/'.$r['cover'];elseif($r['coverURL']!=''&&file_exists('media/'.$r['coverURL']))echo'media/'.$r['coverURL'];elseif($r['coverURL']!='')echo$r['coverURL'];?>">
				<img class="avatar img-thumbnail shadow-depth-1" src="<?php if($r['gravatar']!='')echo$r['gravatar'];elseif($r['avatar']!=''&&file_exists('media/avatar/'.$r['avatar']))echo'media/avatar/'.$r['avatar'];else echo$noavatar;?>">
			</div>
			<h4 class="panel-title account"><?php if($r['name']!='')echo$r['name'];else echo$r['username'];if($r['business']!='')echo'<br><small>'.$r['business'].'</small>';?></h4>
			<div id="controls_<?php echo$r['id'];?>" class="btn-group panel-controls shadow-depth-1">
				<a class="btn btn-info btn-sm" href="admin/accounts/edit/<?php echo$r['id'];?>"<?php if($config['options']{4}==1)echo' data-toggle="tooltip" title="';lang('tooltip','edit');echo'"';?>><i class="libre libre-edit"></i></a>
<?php				if($user['rank']==1000||$user['options']{0}==1){?>
				<button class="btn btn-warning btn-sm<?php if($r['status']!='delete')echo' hidden';?>" onclick="updateButtons('<?php echo$r['id'];?>','login','status','unpublished')"<?php if($config['options']{4}==1)echo' data-toggle="tooltip" title="';lang('tooltip','restore');echo'"';?>><i class="libre libre-restore"></i></button>
				<button class="btn btn-danger btn-sm<?php if($r['status']=='delete')echo' hidden';?>" onclick="updateButtons('<?php echo$r['id'];?>','login','status','delete')"<?php if($config['options']{4}==1)echo' data-toggle="tooltip" title="';lang('tooltip','delete');echo'"';?>><i class="libre libre-trash"></i></button>
				<button class="btn btn-danger btn-sm<?php if($r['status']!='delete')echo' hidden';?>" onclick="purge('<?php echo$r['id'];?>','login')"<?php if($config['options']{4}==1)echo' data-toggle="tooltip" title="';lang('tooltip','purge');echo'"';?>><i class="libre libre-purge"></i></button>
<?php		}?>
			</div>
		</div>
	</div>
<?php	}
	}?>
</div>
