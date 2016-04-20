<?php
$rank=0;
$show='categories';
if($view=='add'){
    $ti=time();
    $schema='';
    $comments=0;
    if($args[0]=='article')$schema='blogPost';
    if($args[0]=='inventory')$schema='Product';
    if($args[0]=='service')$schema='Service';
    if($args[0]=='gallery')$schema='ImageGallery';
    if($args[0]=='testimonial')$schema='Review';
    if($args[0]=='news')$schema='NewsArticle';
    if($args[0]=='event')$schema='Event';
    if($args[0]=='portfolio')$schema='CreativeWork';
    if($args[0]=='proof'){$schema='CreativeWork';$comments=1;}
    $q=$db->prepare("INSERT INTO content (options,uid,login_user,contentType,schemaType,status,active,ti,eti,pti) VALUES ('00000000',:uid,:login_user,:contentType,:schemaType,'unpublished','1',:ti,:ti,:ti)");
    if(isset($user['id']))$uid=$user['id'];else$uid=0;
    if($user['name']!='')$login_user=$user['name'];else$login_user=$user['username'];
    $q->execute(array(':contentType'=>$args[0],':uid'=>$uid,':login_user'=>$login_user,':schemaType'=>$schema,':ti'=>$ti));
    $id=$db->lastInsertId();
    $args[0]=ucfirst($args[0]).' '.$id;
    $s=$db->prepare("UPDATE content SET title=:title WHERE id=:id");
    $s->execute(array(':title'=>$args[0],':id'=>$id));
    if($view!='bookings')$show='item';
    $rank=0;
    $args[0]='edit';
    $args[1]=$id;
}
if($args[0]=='edit'){
    $s=$db->prepare("SELECT * FROM content WHERE id=:id");
    $s->execute(array(':id'=>$args[1]));
    $show='item';
}
if($show=='categories'){
    if($args[0]=='type'){
        $s=$db->prepare("SELECT * FROM content WHERE contentType=:contentType AND contentType!='message_primary' ORDER BY pin DESC,ti DESC,title ASC");
        $s->execute(array(':contentType'=>$args[1]));
    }else{
        if(isset($args[1])){
            $s=$db->prepare("SELECT * FROM content WHERE LOWER(category_1) LIKE LOWER(:category_1) AND LOWER(category_2) LIKE LOWER(:category_2) AND contentType!='message_primary' ORDER BY pin DESC,ti DESC,title ASC");
            $s->execute(array(':category_1'=>str_replace('-',' ',$args[0]),':category_2'=>str_replace('-',' ',$args[1])));
        }elseif(isset($args[0])){
            $s=$db->prepare("SELECT * FROM content WHERE LOWER(category_1) LIKE LOWER(:category_1) AND contentType!='message_primary' ORDER BY pin DESC,ti ASC,title ASC");
            $s->execute(array(':category_1'=>str_replace('-',' ',$args[0])));
        }else{
            $s=$db->prepare("SELECT * FROM content WHERE contentType!='booking' AND contentType!='message_primary' ORDER BY pin DESC,ti DESC,title ASC");
            $s->execute();
        }
    }?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h4 class="col-xs-6">
            <ol class="breadcrumb">
                <li><a href="<?php echo URL.$settings['system']['admin'].'/content';?>">Content</a></li>
                <li class="active relative">
                    <a class="dropdown-toggle" data-toggle="dropdown"><?php if(isset($args[1])&&$args[1]!='')echo ucfirst($args[1]);else echo'All';?> <i class="caret"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo URL.$settings['system']['admin'].'/content';?>">All</a></li>
                        <li><a href="<?php echo URL.$settings['system']['admin'];?>/content/type/article">Article</a></li>
                        <li><a href="<?php echo URL.$settings['system']['admin'];?>/content/type/portfolio">Portfolio</a></li>
                        <li><a href="<?php echo URL.$settings['system']['admin'];?>/content/type/events">Event</a></li>
                        <li><a href="<?php echo URL.$settings['system']['admin'];?>/content/type/news">News</a></li>
                        <li><a href="<?php echo URL.$settings['system']['admin'];?>/content/type/testimonials">Testimonial</a></li>
                        <li><a href="<?php echo URL.$settings['system']['admin'];?>/content/type/inventory">Inventory</a></li>
                        <li><a href="<?php echo URL.$settings['system']['admin'];?>/content/type/service">Service</a></li>
                        <li><a href="<?php echo URL.$settings['system']['admin'];?>/content/type/gallery">Gallery</a></li>
                        <li><a href="<?php echo URL.$settings['system']['admin'];?>/content/type/proofs">Proof</a></li>
                    </ul>
                </li>
            </ol>
        </h4>
        <div class="pull-right">
<?php if($user['rank']==1000||$user['options']{0}==1){?>
            <div class="btn-group"<?php if($config['options']{4}==1)echo' data-toggle="tooltip" data-placement="left" title="Add"';?>>
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php svg('add');?></button>
                <ul class="dropdown-menu pull-right">
                    <li><a href="<?php echo URL.$settings['system']['admin'];?>/add/article">Article</a></li>
                    <li><a href="<?php echo URL.$settings['system']['admin'];?>/add/portfolio">Portfolio</a></li>
                    <li><a href="<?php echo URL.$settings['system']['admin'];?>/add/events">Event</a></li>
                    <li><a href="<?php echo URL.$settings['system']['admin'];?>/add/news">News</a></li>
                    <li><a href="<?php echo URL.$settings['system']['admin'];?>/add/testimonials">Testimonial</a></li>
                    <li><a href="<?php echo URL.$settings['system']['admin'];?>/add/inventory">Inventory</a></li>
                    <li><a href="<?php echo URL.$settings['system']['admin'];?>/add/service">Service</a></li>
                    <li><a href="<?php echo URL.$settings['system']['admin'];?>/add/gallery">Gallery</a></li>
                    <li><a href="<?php echo URL.$settings['system']['admin'];?>/add/proofs">Proof</a></li>
                </ul>
            </div>
<?php }?>
        </div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-hover">
                <thead>
                    <tr>
                        <th class="col-xs-6">Title</th>
                        <th class="col-xs-1"></th>
                        <th class="col-xs-1 text-center">Comments</th>
                        <th class="col-xs-1 text-center">Views</th>
                        <th class="col-xs-3"></th>
                    </tr>
                </thead>
                <tbody id="listtype" class="list" data-t="menu" data-c="ord">
<?php while($r=$s->fetch(PDO::FETCH_ASSOC)){?>
                    <tr id="l_<?php echo$r['id'];?>" class="<?php if($r['status']=='delete')echo' danger';elseif($r['status']=='unpublished')echo'warning';?>">
                        <td><a href="<?php echo URL.$settings['system']['admin'].'/content/edit/'.$r['id'];?>"><?php echo$r['title'];?></a></td>
                        <td class="text-center"><?php echo ucfirst($r['contentType']);?></td>
                        <td class="text-center">
<?php if($r['contentType']=='article'||$r['contentType']=='events'||$r['contentType']=='news'||$r['contentType']=='proofs'){
    $cnt=0;
    $sc=$db->prepare("SELECT COUNT(id) as cnt FROM comments WHERE rid=:id AND status='unapproved'");
    $sc->execute(array(':id'=>$r['id']));
    $cnt=$sc->fetch(PDO::FETCH_ASSOC);?>
                            <a class="btn btn-default btn-sm" href="<?php echo URL.$settings['system']['admin'].'/content/edit/'.$r['id'];?>#comments"<?php if($config['options']{4}==1)echo' data-toggle="tooltip" title="Comments"';?>><?php svg('comments');?> <?php echo$cnt['cnt'];?></a>
<?php }?>
                        </td>
                        <td class="text-center">
                            <span class="btn btn-default btn-sm"<?php if($config['options']{4}==1)echo' data-toggle="tooltip" title="Views"';?>><?php svg('view');?> <?php echo$r['views'];?></span>
                        </td>
                        <td id="controls_<?php echo$r['id'];?>" class="text-right">
                            <a id="pin<?php echo$r['id'];?>" class="btn btn-default<?php if($r['pin']{0}==1)echo' btn-success';?>" onclick="pinToggle('<?php echo$r['id'];?>','content','pin','0')"<?php if($config['options']{4}==1)echo' data-toggle="tooltip" title="Pin"';?>><?php svg('pin');?></a>
                            <a class="btn btn-default" href="<?php echo URL.$settings['system']['admin'];?>/content/edit/<?php echo$r['id'];?>"<?php if($config['options']{4}==1)echo' data-toggle="tooltip" title="Edit"';?>><?php svg('edit');?></a>
<?php if($user['rank']==1000||$user['options']{0}==1){?>
                            <button class="btn btn-default<?php if($r['status']!='delete')echo' hidden';?>" onclick="updateButtons('<?php echo$r['id'];?>','content','status','unpublished')"<?php if($config['options']{4}==1)echo' data-toggle="tooltip" title="Restore"';?>><?php svg('restore');?></button>
                            <button class="btn btn-default trash<?php if($r['status']=='delete')echo' hidden';?>" onclick="updateButtons('<?php echo$r['id'];?>','content','status','delete')"<?php if($config['options']{4}==1)echo' data-toggle="tooltip" title="Delete"';?>><?php svg('trash');?></button>
                            <button class="btn btn-default trash<?php if($r['status']!='delete')echo' hidden';?>" onclick="purge('<?php echo$r['id'];?>','content')"<?php if($config['options']{4}==1)echo' data-toggle="tooltip" title="Purge"';?>><?php svg('purge');?></button>
                        </td>
                    </tr>
<?php }
}?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php }

if($show=='item'){
    $r=$s->fetch(PDO::FETCH_ASSOC);?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h4 class="col-xs-6">
            <ol class="breadcrumb">
                <li><a href="<?php echo URL.$settings['system']['admin'];?>/content/">Content</a></li>
                <li><a href="<?php echo URL.$settings['system']['admin'];?>/content/type/<?php echo$r['contentType'];?>"><?php echo ucfirst($r['contentType']);?></a></li>
                <li class="active relative"><?php $so=$db->prepare("SELECT * FROM content WHERE contentType LIKE :contentType AND id NOT LIKE :id ORDER BY title ASC, ti DESC");$so->execute(array(':id'=>$r['id'],':contentType'=>$r['contentType'].'%'));?><a class="dropdown-toggle" data-toggle="dropdown"><?php echo$r['title'];?><i class="caret"></i></a>
                    <ul class="dropdown-menu pull-right">
<?php while($ro=$so->fetch(PDO::FETCH_ASSOC)){?>
                        <li><a href="<?php echo URL.$settings['system']['admin'].'/content/edit/'.$ro['id'];?>"><?php echo$ro['title'];?></a></li>
<?php }?>
                    </ul>
                </li>
            </ol>
        </h4>
        <div class="pull-right">
            <div class="btn-group">
                <a class="btn btn-default" href="<?php echo URL.$settings['system']['admin'].'/content/type/'.$r['contentType'];?>"<?php if($config['options']{4}==1)echo' data-toggle="tooltip" data-placement="left" title="Back"';?>><?php svg('back');?></a>
            </div>
<?php if($user['rank']==1000||$user['options']{0}==1){?>
            <div class="btn-group"<?php if($config['options']{4}==1)echo' data-toggle="tooltip" data-placement="left" title="Add"';?>>
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php svg('add');?></button>
                <ul class="dropdown-menu pull-right">
                    <li><a href="<?php echo URL.$settings['system']['admin'];?>/add/article">Article</a></li>
                    <li><a href="<?php echo URL.$settings['system']['admin'];?>/add/portfolio">Portfolio</a></li>
                    <li><a href="<?php echo URL.$settings['system']['admin'];?>/add/events">Event</a></li>
                    <li><a href="<?php echo URL.$settings['system']['admin'];?>/add/news">News</a></li>
                    <li><a href="<?php echo URL.$settings['system']['admin'];?>/add/testimonials">Testimonial</a></li>
                    <li><a href="<?php echo URL.$settings['system']['admin'];?>/add/inventory">Inventory</a></li>
                    <li><a href="<?php echo URL.$settings['system']['admin'];?>/add/service">Service</a></li>
                    <li><a href="<?php echo URL.$settings['system']['admin'];?>/add/gallery">Gallery</a></li>
                    <li><a href="<?php echo URL.$settings['system']['admin'];?>/add/proofs">Proof</a></li>
                </ul>
            </div>
        </div>
    </div>
<?php }?>
    <div class="panel-body">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#content-content" aria-controls="content-content" role="tab" data-toggle="tab">Content</a></li>
            <li role="presentation"><a href="#content-images" aria-controls="content-images" role="tab" data-toggle="tab">Images</a></li>
            <li role="presentation"><a href="#content-comments" aria-controls="content-comments" role="tab" data-toggle="tab">Comments</a></li>
            <li role="presentation"><a href="#content-seo" aria-controls="content-seo" role="tab" data-toggle="tab">SEO</a></li>
            <li role="presentation"><a href="#content-settings" aria-controls="content-settings" role="tab" data-toggle="tab">Settings</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="content-content">
                <div id="d0" class="form-group">
                    <label for="title" class="control-label col-xs-5 col-sm-3 col-lg-2">Title</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input type="text" id="title" class="form-control textinput" value="<?php echo$r['title'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="title" data-bs="btn-danger" placeholder="Content MUST contain a title or it won't be accessible...">
                    </div>
                </div>
                <div id="d1" class="form-group">
                    <label for="ti" class="control-label col-xs-5 col-sm-3 col-lg-2">Created</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input type="text" id="ti" class="form-control" value="<?php echo date($config['dateFormat'],$r['ti']);?>" readonly>
                    </div>
                </div>
                <div id="d2" class="form-group">
                    <label for="pti" class="control-label col-xs-5 col-sm-3 col-lg-2">Published On</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input type="text" id="pti" class="form-control" data-dbid="<?php echo$r['id'];?>" value="<?php if($r['pti']>0)echo date($config['dateFormat'],$r['pti']);?>">
                    </div>
                </div>
                <div id="d3" class="form-group">
                    <label for="eti" class="control-label col-xs-5 col-sm-3 col-lg-2">Edited</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input type="text" id="eti" class="form-control" value="<?php echo date($config['dateFormat'],$r['eti']).' by '.$r['login_user'];?>" readonly>
                    </div>
                </div>
                <div id="d4" class="form-group">
                    <label for="cid" class="control-label col-xs-5 col-sm-3 col-lg-2">Client</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <select id="cid" class="form-control" onchange="update('<?php echo$r['id'];?>','content','cid',$(this).val());"<?php if($user['options']{1}==0)echo' disabled';?>>
                            <option value="0">Select Client</option>
<?php $cs=$db->query("SELECT * FROM login ORDER BY name ASC, username ASC");while($cr=$cs->fetch(PDO::FETCH_ASSOC)){?>
                            <option value="<?php echo$cr['id'];?>"<?php if($r['cid']==$cr['id'])echo' selected';?>><?php echo$cr['username'].':'.$cr['name'];?></option>
<?php }?>
                        </select>
                    </div>
                </div>
                <div id="d5" class="form-group">
                    <label for="author" class="control-label col-xs-5 col-sm-3 col-lg-2">Author</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <select id="uid" class="form-control" onchange="update('<?php echo$r['id'];?>','content','uid',$(this).val());"<?php if($user['options']{1}==0)echo' disabled';?>><?php $su=$db->query("SELECT id,username,name FROM login WHERE username!='' AND status!='delete' ORDER BY username ASC, name ASC");while($ru=$su->fetch(PDO::FETCH_ASSOC)){?><option value="<?php echo$ru['id'];?>"<?php if($ru['id']==$r['uid'])echo' selected';echo'>'.$ru['username'].':'.$ru['name'];?></option><?php }?>
                        </select>
                    </div>
                </div>
                <div id="d6" class="form-group">
                    <label for="code" class="control-label col-xs-5 col-sm-3 col-lg-2">Code</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input type="text" id="code" class="form-control textinput" value="<?php echo$r['code'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="code" placeholder="Enter a Code..."<?php if($user['options']{1}==0)echo' readonly';?>>
                    </div>
                </div>
                <div id="d7" class="form-group">
                    <label for="barcode" class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-2">Barcode</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input type="text" id="barcode" class="form-control textinput" value="<?php echo$r['barcode'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="barcode" placeholder="Enter a Barcode..."<?php if($user['options']{1}==0)echo' readonly';?>>
                    </div>
                </div>
                <div id="d8" class="form-group">
                    <label for="fccid" class="control-label col-xs-5 col-sm-3 col-lg-2">FCCID</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input type="text" id="fccid" class="form-control textinput" value="<?php echo$r['fccid'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="fccid" placeholder="Enter an FCC ID..."<?php if($user['options']{1}==0)echo' readonly';?>>
                        <div class="help-block"><a target="_blank" href="https://fccid.io/">fccid.io</a> for more information or to look up an FCC ID.</div>
                    </div>
                </div>
                <div id="d9" class="form-group">
                    <label for="brand" class="control-label col-xs-5 col-sm-3 col-lg-2">Brand</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input type="text" id="brand" class="form-control textinput" value="<?php echo$r['brand'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="brand" placeholder="Enter a Brand..."<?php if($user['options']{1}==0)echo' readonly';?>>
                    </div>
                </div>
                <div id="d10" class="form-group">
                    <label for="tis" class="control-label col-xs-5 col-sm-3 col-lg-2">Event Start</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input type="text" id="tis" class="form-control"<?php if($config['options']{4}==1){echo' data-toggle="tooltip" title="';if($r['tis']==0){echo'Select a Date/Time..."';}else{echo date($config['dateFormat'],$r['tis']).'"';}}?> value="<?php if($r['tis']!=0)echo date('Y-m-d h:m',$r['tis']);?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="tis" placeholder="Select a Date/Time..."<?php if($user['options']{1}==0)echo' readonly';?>>
                    </div>
                </div>
                <div id="d11" class="form-group">
                    <label for="tie" class="control-label col-xs-5 col-sm-3 col-lg-2">Event End</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input type="text" id="tie" class="form-control"<?php if($config['options']{4}==1){echo' data-toggle="tooltip" title="';if($r['tie']==0)echo'Select a Date/Time..."';else echo date($config['dateFormat'],$r['tie']).'"';}?> value="<?php if($r['tie']!=0)echo date('Y-m-d h:m',$r['tie']);?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="tie" placeholder="Select a Date/Time..."<?php if($user['options']{1}==0)echo' readonly';?>>
                    </div>
                </div>
                <div id="d12" class="form-group">
                    <label for="email" class="control-label col-xs-5 col-sm-3 col-lg-2">Email</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input type="text" id="email" class="form-control textinput" value="<?php echo$r['email'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="email" placeholder="Enter an Email..."<?php if($user['options']{1}==0)echo' readonly';?>>
                    </div>
                </div>
                <div id="d13" class="form-group">
                    <label for="name" class="control-label col-xs-5 col-sm-3 col-lg-2">Name</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input type="text" id="name" class="form-control textinput" value="<?php echo$r['name'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="name" placeholder="Enter a Name..."<?php if($user['options']{1}==0)echo' readonly';?>>
                    </div>
                </div>
                <div id="d14" class="form-group">
                    <label for="url" class="control-label col-xs-5 col-sm-3 col-lg-2">URL</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input type="text" id="url" class="form-control textinput" value="<?php echo$r['url'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="url" placeholder="Enter a URL..."<?php if($user['options']{1}==0)echo' readonly';?>>
                    </div>
                </div>
                <div id="d15" class="form-group">
                    <label for="business" class="control-label col-xs-5 col-sm-3 col-lg-2">Business</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input type="text" id="business" class="form-control textinput" value="<?php echo$r['business'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="business" placeholder="Enter a Business..."<?php if($user['options']{1}==0)echo' readonly';?>>
                    </div>
                </div>
                <div id="d16" class="form-group">
                    <label for="category_1" class="control-label col-xs-5 col-sm-3 col-lg-2">Category Primary</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input id="category_1" list="category_1_options" type="text" class="form-control textinput" value="<?php echo$r['category_1'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="category_1" placeholder="Enter a Category/Select from List..."<?php if($user['options']{1}==0)echo' readonly';?>>
                        <datalist id="category_1_options"><?php $s=$db->query("SELECT DISTINCT category_1 FROM content WHERE category_1!='' ORDER BY category_1 ASC");while($rs=$s->fetch(PDO::FETCH_ASSOC))echo'<option value="'.$rs['category_1'].'"/>';?></datalist>
                    </div>
                </div>
                <div id="d17" class="form-group">
                    <label for="category_2" class="control-label col-xs-5 col-sm-3 col-lg-2">Category Secondary</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input id="category_2" list="category_2_options" type="text" class="form-control textinput" value="<?php echo$r['category_2'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="category_2" placeholder="Enter a Category/Select from List..."<?php if($user['options']{1}==0)echo' readonly';?>>
                        <datalist id="category_2_options"><?php $s=$db->query("SELECT DISTINCT category_2 FROM content WHERE category_2!='' ORDER BY category_2 ASC");while($rs=$s->fetch(PDO::FETCH_ASSOC))echo'<option value="'.$rs['category_2'].'"/>';?></datalist>
                    </div>
                </div>
                <div id="d18" class="form-group">
                    <label for="cost" class="control-label col-xs-5 col-sm-3 col-lg-2">Cost</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <div class="input-group-addon">$</div>
                        <input type="text" id="cost" class="form-control textinput" value="<?php echo$r['cost'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="cost" placeholder="Enter a Cost..."<?php if($user['options']{1}==0)echo' readonly';?>>
                    </div>
                </div>
                <div id="d19" class="form-group clearfix">
                    <label class="control-label col-xs-5 col-sm-3 col-lg-2">Show Cost</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input type="checkbox" id="options0" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="options" data-dbb="0"<?php if($r['options']{0}==1)echo' checked';if($user['options']{1}==0)echo' readonly';?>>
                        <label for="options0">
                    </div>
                </div>
                <div id="d20" class="form-group">
                    <label for="quantity" class="control-label col-xs-5 col-sm-3 col-lg-2">Quantity</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input type="text" id="quantity" class="form-control textinput" value="<?php echo$r['quantity'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="quantity" placeholder="Enter a Quantity..."<?php if($user['options']{1}==0)echo' readonly';?>>
                    </div>
                </div>
                <div id="d21" class="form-group clearfix">
                    <label class="control-label col-xs-5 col-sm-3 col-lg-2">Featured</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input type="checkbox" id="featured0" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="featured" data-dbb="0"<?php if($r['featured']{0}==1)echo' checked';if($user['options']{1}==0)echo' readonly';?>>
                        <label for="featured0">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group col-xs-12">
                        <form id="summernote" method="post" target="sp" action="core/update.php">
                            <input type="hidden" name="id" value="<?php echo$r['id'];?>">
                            <input type="hidden" name="t" value="content">
                            <input type="hidden" name="c" value="notes">
                            <textarea id="notes" class="form-control summernote" name="da" readonly><?php echo$r['notes'];?></textarea>
                        </form>
                    </div>
                </div>
                <fieldset class="control-fieldset">
                    <legend class="control-legend">Content Attribution</legend>
                    <div class="form-group">
                        <label for="attributionContentName" class="control-label col-xs-5 col-sm-3 col-lg-2">Name</label>
                        <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                            <input type="text" id="attributionContentName" class="form-control textinput" value="<?php echo$r['attributionContentName'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="attributionContentName" placeholder="Enter a Name...">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="attributionContentURL" class="control-label col-xs-5 col-sm-3 col-lg-2">URL</label>
                        <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                            <input type="text" id="attributionContentURL" class="form-control textinput" value="<?php echo$r['attributionContentURL'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="attributionContentURL" placeholder="Enter a URL...">
                        </div>
                    </div>
                </fieldset>
            </div>
            <div role="tabpanel" class="tab-pane" id="content-images">
                <fieldset id="d22" class="control-fieldset">
                    <legend class="control-legend">Images</legend>
                    <div class="form-group">
                        <label for="file" class="control-label col-xs-5 col-sm-3 col-lg-2">URL</label>
                        <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                            <div class="input-group-addon"><i class="libre libre-link"></i></div>
                            <input type="text" id="fileURL" class="form-control textinput" value="<?php echo$r['fileURL'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="fileURL" placeholder="Enter a URL...">
                            <div class="input-group-btn">
                                <a class="btn btn-default" data-toggle="modal" data-target="#media" href="core/edit_image.php?id=<?php echo$r['id'];?>&t=content&c=fileURL"><?php svg('edit');?></a>
                                <button class="btn btn-default trash" onclick="imageUpdate('<?php echo$r['id'];?>','content','fileURL');"><?php svg('trash');?></button>
                            </div>
                        </div>
                        <div class="help-block col-xs-7 col-sm-9 col-lg-10 pull-right">Editing a URL Image will retreive the image to the server for Editing.</div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="input-group col-xs-7 col-sm-9 col-lg-10 pull-right">
                            <input type="text" class="form-control hidden-xs" value="<?php echo$r['file'];?>" disabled>
                            <div class="input-group-btn">
                                <form method="post" target="sp" enctype="multipart/form-data" action="core/add_data.php">
                                    <input type="hidden" name="id" value="<?php echo$r['id'];?>">
                                    <input type="hidden" name="act" value="add_image">
                                    <input type="hidden" name="t" value="content">
                                    <input type="hidden" name="c" value="file">
                                    <div class="btn btn-default btn-file">
                                        <?php svg('browse-computer');?>
                                        <input type="file" name="fu"<?php if($user['options']{1}==0)echo' disabled';?>>
                                    </div>
                                    <button class="btn btn-default<?php if($user['options']{1}==0)echo' disabled';?>" onclick="$('#block').css({'display':'block'});"><?php svg('upload');?></button>
                                </form>
                            </div>
                            <div class="input-group-btn">
                                <a class="btn btn-default" data-toggle="modal" data-target="#media" href="core/browse_media.php?id=<?php echo$r['id'];?>&t=content&c=file"><?php svg('browse-media');?></a>
                            </div>
                            <div id="file" class="input-group-addon img">
<?php if($r['file']!=''&&file_exists('media'.DS.$r['file']))
    echo'<a href="media/'.$r['file'].'" data-featherlight="image"><img src="media/'.$r['file'].'"></a>';
elseif($r['fileURL']!=''&&file_exists('media'.DS.$r['fileURL']))
    echo'<a href="media/'.$r['fileURL'].'" data-featherlight="image"><img src="media/'.$r['fileURL'].'"></a>';
elseif($r['fileURL']!='')
    echo'<a href="'.$r['fileURL'].'" data-featherlight="image"><img src="'.$r['fileURL'].'"></a>';
else echo'<img src="core/images/noimage.jpg">';?>
                            </div>
                            <div class="input-group-btn">
                                <a class="btn btn-default" data-toggle="modal" data-target="#media" href="core/edit_image.php?id=<?php echo$r['id'];?>&t=content&c=file"><?php svg('edit');?></a>
                                <button class="btn btn-default trash" onclick="imageUpdate('<?php echo$r['id'];?>','content','file');"><?php svg('trash');?></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="thumb" class="control-label col-xs-5 col-sm-3 col-lg-2">Thumbnail</label>
                        <div class="input-group col-xs-7 col-sm-9 col-lg-10 pull-right">
                            <input type="text" class="form-control hidden-xs" value="<?php echo$r['thumb'];?>" disabled>
                            <div class="input-group-btn">
                                <form method="post" target="sp" enctype="multipart/form-data" action="core/add_data.php">
                                    <input type="hidden" name="id" value="<?php echo$r['id'];?>">
                                    <input type="hidden" name="act" value="add_image">
                                    <input type="hidden" name="t" value="content">
                                    <input type="hidden" name="c" value="thumb">
                                    <div class="btn btn-default btn-file">
                                        <?php svg('browse-computer');?>
                                        <input type="file" name="fu"<?php if($user['options']{1}==0)echo' disabled';?>>
                                    </div>
                                    <button class="btn btn-default<?php if($user['options']{1}==0)echo' disabled';?>" onclick="$('#block').css({'display':'block'});"><?php svg('upload');?></button>
                                </form>
                            </div>
                            <div class="input-group-btn">
                                <a class="btn btn-default" data-toggle="modal" data-target="#media" href="core/browse_media.php?id=<?php echo$r['id'];?>&t=content&c=thumb"><?php svg('browse-media');?></a>
                            </div>
                            <div id="thumb" class="input-group-addon img">
<?php if($r['thumb']!=''&&file_exists('media'.DS.$r['thumb']))echo'<a href="media/'.$r['thumb'].'" data-featherlight="image"><img src="media/'.$r['thumb'].'"></a>';
else echo'<img src="core/images/noimage.jpg">';?>
                            </div>
                            <div class="input-group-btn">
                                <a class="btn btn-default" data-toggle="modal" data-target="#media" href="core/edit_image.php?id=<?php echo$r['id'];?>&t=content&c=thumb"><?php svg('edit');?></a>
                                <button class="btn btn-default trash" onclick="imageUpdate('<?php echo$r['id'];?>','content','thumb');"><?php svg('trash');?></button>
                            </div>
                        </div>
                        <div class="help-block col-xs-7 col-sm-9 col-lg-10 pull-right">Uploaded Images take Precedence over URL's.</div>
                    </div>
                    <fieldset class="control-fieldset">
                        <legend class="control-legend">Exif Information</legend>
                        <div class="form-group">
                            <label for="exifFilename" class="control-label col-xs-5 col-sm-3 col-lg-2">Original Filename</label>
                            <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                                <input type="text" class="form-control" value="<?php echo$r['exifFilename'];?>" placeholder="Original Filename..." readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exifCamera" class="control-label col-xs-5 col-sm-3 col-lg-2">Camera</label>
                            <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                                <input type="text" id="exifCamera" class="form-control textinput" value="<?php echo$r['exifCamera'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="exifCamera" placeholder="Enter Camera Brand...">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exifLens" class="control-label col-xs-5 col-sm-3 col-lg-2">Lens</label>
                            <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                                <input type="text" id="exifLens" class="form-control textinput" value="<?php echo$r['exifLens'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="exifLens" placeholder="Enter Lens...">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exifAperture" class="control-label col-xs-5 col-sm-3 col-lg-2">Aperture</label>
                            <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                                <input type="text" id="exifAperture" class="form-control textinput" value="<?php echo$r['exifAperture'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="exifAperture" placeholder="Enter Aperture/FStop...">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exifFocalLength" class="control-label col-xs-5 col-sm-3 col-lg-2">Focal Length</label>
                            <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                                <input type="text" id="exifFocalLength" class="form-control textinput" value="<?php echo$r['exifFocalLength'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="exifFocalLength" placeholder="Enter Focal Length...">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exifShutterSpeed" class="control-label col-xs-5 col-sm-3 col-lg-2">Shutter Speed</label>
                            <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                                <input type="text" id="exifShutterSpeed" class="form-control textinput" value="<?php echo$r['exifShutterSpeed'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="exifShutterSpeed" placeholder="Enter Shutter Speed...">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exifISO" class="control-label col-xs-5 col-sm-3 col-lg-2">ISO</label>
                            <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                                <input type="text" id="exifISO" class="form-control textinput" value="<?php echo$r['exifISO'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="exifISO" placeholder="Enter ISO...">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exifti" class="control-label col-xs-5 col-sm-3 col-lg-2">Taken</label>
                            <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                                <input type="text" id="exifti" class="form-control textinput" value="<?php if($r['exifti']!=0){echo date($config['dateFormat'],$r['exifti']);}?>" placeholder="Select the Date/Time Image was Taken..." readonly>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="control-fieldset">
                        <legend class="control-legend">Image Atrribution</legend>
                        <div class="form-group">
                            <label for="attributionImageTitle" class="control-label col-xs-5 col-sm-3 col-lg-2">Title</label>
                            <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                                <input type="text" id="attributionImageTitle" class="form-control textinput" value="<?php echo$r['attributionImageTitle'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="attributionImageTitle" placeholder="Enter a Title...">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="attributionImageName" class="control-label col-xs-5 col-sm-3 col-lg-2">Name</label>
                            <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                                <input type="text" id="attributionImageName" class="form-control textinput" value="<?php echo$r['attributionImageName'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="attributionImageName" placeholder="Enter a Name...">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="attributionImageURL" class="control-label col-xs-5 col-sm-3 col-lg-2">URL</label>
                            <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                                <input type="text" id="attributionImageURL" class="form-control textinput" value="<?php echo$r['attributionImageURL'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="attributionImageURL" placeholder="Enter a URL...">
                            </div>
                        </div>
                    </fieldset>
                </fieldset>
            </div>
            <div role="tabpanel" class="tab-pane" id="content-comments">
                <div class="form-group">
                    <label class="control-label col-xs-5 col-sm-3 col-lg-2">Comments</label>
                    <div class="input-group col-xs-7 col-md-9 col-lg-10">
                        <input type="checkbox" id="options1" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="options" data-dbb="1"<?php if($r['options']{1}==1)echo' checked';?>>
                        <label for="options1">
                    </div>
                </div>
                <div id="comments">
                    <h3>Comments</h3>
<?php $sc=$db->prepare("SELECT * FROM comments WHERE contentType=:contentType AND rid=:rid ORDER BY ti ASC");$sc->execute(array(':contentType'=>$r['contentType'],':rid'=>$r['id']));while($rc=$sc->fetch(PDO::FETCH_ASSOC)){?>
                    <div id="l_<?php echo$rc['id'];?>" class="media clearfix<?php if($rc['status']=='delete')echo' danger';if($rc['status']=='unapproved')echo' warning';?>">
                        <div class="media-object pull-left">
<?php $su=$db->prepare("SELECT * FROM login WHERE id=:id");$su->execute(array(':id'=>$rc['uid']));$ru=$su->fetch(PDO::FETCH_ASSOC);?>
                            <img class="commentavatar img-thumbnail" src="<?php if($ru['avatar']!=''&&file_exists('media/avatar/'.$ru['avatar']))echo'media/avatar/'.$ru['avatar'];elseif($ru['gravatar']!='')echo md5($ru['gravatar']);else echo$noavatar;?>">
                        </div>
                        <div class="media-body">
                            <div class="well">
                                <h5 class="media-heading"><?php echo$rc['name'];?></h5>
                                <time><small class="text-muted"><?php echo date($config['dateFormat'],$rc['ti']);?></small></time>
                                <?php echo strip_tags($rc['notes']);?>
                                <div id="controls-<?php echo$rc['id'];?>" class="btn-group pull-right">
                                    <button id="approve_<?php echo$rc['id'];?>" class="btn btn-success btn-sm<?php if($rc['status']!='unapproved')echo' hidden';?>" onclick="update('<?php echo$rc['id'];?>','comments','status','approved')"<?php if($config['options']{4}==1)echo' data-toggle="tooltip" title="Approve"';?>><i class="libre libre-approve"></i></button>
                                    <button class="btn btn-danger btn-sm" onclick="purge('<?php echo$rc['id'];?>','comments')"<?php if($config['options']{4}==1)echo' data-toggle="tooltip" title="Delete"';?>><i class="libre libre-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
<?php }?>
                    <iframe name="comments" id="comments" class="hidden"></iframe>
                    <div class="form-group">
                        <form role="form" target="comments" method="post" action="core/add_data.php">
                            <input type="hidden" name="act" value="add_comment">
                            <input type="hidden" name="rid" value="<?php echo$r['id'];?>">
                            <input type="hidden" name="contentType" value="<?php echo$r['contentType'];?>">
                            <label for="email" class="control-label col-xs-5 col-md-3 col-lg-2">Email</label>
                            <div class="input-group col-xs-7 col-md-9 col-lg-10">
                                <input type="text" class="form-control" name="email" value="<?php echo$user['email'];?>" readonly>
                            </div>
                            <label for="name" class="control-label col-xs-5 col-md-3 col-lg-2">Name</label>
                            <div class="input-group col-xs-7 col-md-9 col-lg-10">
                                <input type="text" class="form-control" name="name" value="<?php echo$user['name'];?>" readonly>
                            </div>
                            <label for="da" class="control-label col-xs-5 col-md-3 col-lg-2">Comment</label>
                            <div class="input-group col-xs-7 col-md-9 col-lg-10">
                                <textarea id="da" class="form-control" name="da" placeholder="Enter a Comment..." required></textarea>
                            </div>
                            <label class="control-label col-xs-5 col-md-3 col-lg-2">&nbsp;</label>
                            <div class="input-group col-xs-7 col-md-9 col-lg-10">
                                <button class="btn btn-default btn-block">Add Comment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="content-seo">
                <div class="form-group">
                    <label for="views" class="control-label col-xs-5 col-sm-3 col-lg-2">Views</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input type="text" id="views" class="form-control textinput" value="<?php echo$r['views'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="views"<?php if($user['options']{1}==0)echo' readonly';?>>
                    </div>
                </div>
                <div class="form-group">
                    <label for="schemaType" class="control-label col-xs-5 col-sm-3 col-lg-2">Schema Type</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <select id="schemaType" class="form-control" onchange="update('<?php echo$r['id'];?>','content','schemaType',$(this).val());"<?php if($user['options']{1}==0)echo' disabled';if($config['options']{4}==1)echo' data-toggle="tooltip" title="Schema for Microdata Content."';?>>
                            <option value="blogPost"<?php if($r['schemaType']=='blogPost')echo' selected';?>>blogPost for Articles</option>
                            <option value="Product"<?php if($r['schemaType']=='Product')echo' selected';?>>Product for Inventory</option>
                            <option value="Service"<?php if($r['schemaType']=='Service')echo' selected';?>>Service for Services</option>
                            <option value="ImageGallery"<?php if($r['schemaType']=='ImageGallery')echo' selected';?>>ImageGallery for Gallery Images</option>
                            <option value="Review"<?php if($r['schemaType']=='Review')echo' selected';?>>Review for Testimonials</option>
                            <option value="NewsArticle"<?php if($r['schemaType']=='NewsArticle')echo' selected';?>>NewsArticle for News</option>
                            <option value="Event"<?php if($r['schemaType']=='Event')echo' selected';?>>Event for Events</option>
                            <option value="CreativeWork"<?php if($r['schemaType']=='CreativeWork')echo' selected';?>>CreativeWork for Portfolio/Proofs</option>
                        </select>
                    </div>
                </div>
                <div id="d23" class="form-group">
                    <label for="keywords" class="control-label col-xs-5 col-sm-3 col-lg-2">Keywords</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input type="text" id="keywords" class="form-control textinput" value="<?php echo$r['keywords'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="keywords" placeholder="Enter Keywords..."<?php if($user['options']{1}==0)echo' readonly';?>>
                    </div>
                </div>
                <div id="d24" class="form-group">
                    <label for="tags" class="control-label col-xs-5 col-sm-3 col-lg-2">Tags</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input type="text" id="tags" class="form-control textinput" value="<?php echo$r['tags'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="tags" placeholder="Enter Tags..."<?php if($user['options']{1}==0)echo' readonly';?>>
                    </div>
                </div>
                <div id="d25" class="form-group">
                    <label for="caption" class="control-label col-xs-5 col-sm-3 col-lg-2">Caption</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input type="text" id="caption" class="form-control textinput" value="<?php echo$r['caption'];?>" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="caption" placeholder="Enter a Caption..."<?php if($user['options']{1}==0)echo' readonly';?>>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="content-settings">
                <div id="d26" class="form-group">
                    <label for="published" class="control-label col-xs-5 col-sm-3 col-lg-2">Status</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <select id="status" class="form-control" onchange="update('<?php echo$r['id'];?>','content','status',$(this).val());"<?php if($user['options']{1}==0)echo' readonly';if($config['options']{4}==1)echo' data-toggle="tooltip" title=""';?>>
                            <option value="unpublished"<?php if($r['status']=='unpublished')echo' selected';?>>Unpublished</option>
                            <option value="published"<?php if($r['status']=='published')echo' selected';?>>Published</option>
                            <option value="delete"<?php if($r['status']=='delete')echo' selected';?>>Delete</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="contentType" class="control-label col-xs-5 col-sm-3 col-lg-2">Content Type</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <select id="contentType" class="form-control" onchange="update('<?php echo$r['id'];?>','content','contentType',$(this).val());"<?php if($user['options']{1}==0)echo' disabled';if($config['options']{4}==1)echo' data-toggle="tooltip" title="Change the Type of Content this Item belongs to."';?>>
                            <option value="article"<?php if($r['contentType']=='article')echo' selected';?>>Article</option>
                            <option value="portfolio"<?php if($r['contentType']=='portfolio')echo' selected';?>>Portfolio</option>
                            <option value="events"<?php if($r['contentType']=='events')echo' selected';?>>Event</option>
                            <option value="news"<?php if($r['contentType']=='news')echo' selected';?>>News</option>
                            <option value="testimonials"<?php if($r['contentType']=='testimonials')echo' selected';?>>Testimonial</option>
                            <option value="inventory"<?php if($r['contentType']=='inventory')echo' selected';?>>Inventory</option>
                            <option value="service"<?php if($r['contentType']=='service')echo' selected';?>>Service</option>
                            <option value="gallery"<?php if($r['contentType']=='gallery')echo' selected';?>>Gallery</option>
                            <option value="proofs"<?php if($r['contentType']=='proofs')echo' selected';?>>Proof</option>
                        </select>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <label class="control-label col-xs-5 col-sm-3 col-lg-2">Internal</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input type="checkbox" id="internal0" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="internal" data-dbb="0"<?php if($r['internal']==1)echo' checked';?><?php if($user['options']{1}==0)echo' readonly';?>>
                        <label for="internal0">
                    </div>
                </div>
                <div id="d27" class="form-group clearfix<?php if($r['contentType']!='events'||$r['contentType']!='service')echo' hidden';?>">
                    <label class="control-label col-xs-5 col-sm-3 col-lg-2">Bookable</label>
                    <div class="input-group col-xs-7 col-sm-9 col-lg-10">
                        <input type="checkbox" id="bookable0" data-dbid="<?php echo$r['id'];?>" data-dbt="content" data-dbc="bookable" data-dbb="0"<?php if($r['bookable']==1)echo' checked';?><?php if($user['options']{1}==0)echo' readonly';?>>
                        <label for="bookable0">
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php }
