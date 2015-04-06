<?php if($user['rank']>399){?>
<div id="libr8_admin_header">
	<nav class="navbar navbar-inverse" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#admin-collapse">
					<span class="sr-only">Administration</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<div class="collapse navbar-collapse" id="admin-collapse">
				<ul class="nav navbar-nav pull-right relative">
					<li<?php if($view=='statistics'){echo' class="active"';}?>><a href="<?php echo URL;?>admin/statistics">Statistics</a></li>
					<li<?php if($view=='pages'){echo' class="active"';}?>><a href="<?php echo URL;?>admin/pages">Pages</a></li>
					<li<?php if($view=='content'||$view=='article'||$view=='portfolio'||$view=='events'||$view=='news'||$view=='testimonials'||$view=='inventory'||$view=='services'||$view=='gallery'){echo' class="active"';}?>><a href="<?php echo URL;?>admin/content">Content</a></li>
<?php if($user['rank']==1000||$user['options']{1}==1){?>
					<li<?php if($view=='bookings'){echo' class="active"';}?>><a href="<?php echo URL;?>admin/bookings">Bookings</a></li>
<?php }
if($user['rank']==1000||$user['options']{2}==1){?>
					<li<?php if($view=='orders'){echo' class="active"';}?>><a href="<?php echo URL;?>admin/orders/all">Orders</a></li>
<?php }
if($user['rank']==1000||$user['options']{3}==1){?>
					<li<?php if($view=='media'){echo' class="active"';}?>><a href="<?php echo URL;?>admin/media">Media</a></li>
<?php }?>
					<li<?php if($view=='accounts'){echo' class="active"';}?>><a href="<?php echo URL;?>admin/accounts">Accounts</a></li>
<?php if($user['rank']==1000||$user['options']{5}==1){?>
					<li<?php if($view=='messages'){echo' class="active"';}?>>
						<a href="<?php echo URL;?>admin/messages">Messages</a>
<?php $s=$db->prepare("SELECT COUNT(id) as cnt FROM messages WHERE folder='INBOX' AND status='unread'");
$s->execute();
$cnt=$s->fetch(PDO::FETCH_ASSOC);?>
					</li>
<?php }
if($user['rank']==1000||$user['options']{6}==1){?>
					<li<?php if($view=='preferences'){echo' class="active"';}?>><a href="<?php echo URL;?>admin/preferences">Preferences</a></li>
<?php }?>
					<li>
<?php if($user['rank']==1000||$user['options']{0}==1){?>
						<button class="btn btn-success dropdown-toggle" style="margin:7px 10px;" data-toggle="dropdown" data-placement="right">Add <i class="caret"></i></button>
						<ul class="dropdown-menu multi-level pull-right">
							<li><a href="<?php echo URL;?>admin/add/article">Article</a></li>
							<li><a href="<?php echo URL;?>admin/add/portfolio">Portfolio</a></li>
							<li><a href="<?php echo URL;?>admin/add/events">Event</a></li>
							<li><a href="<?php echo URL;?>admin/add/news">News</a></li>
							<li><a href="<?php echo URL;?>admin/add/testimonials">Testimonial</a></li>
							<li><a href="<?php echo URL;?>admin/add/inventory">Inventory</a></li>
							<li><a href="<?php echo URL;?>admin/add/services">Service</a></li>
							<li><a href="<?php echo URL;?>admin/add/gallery">Gallery</a></li>
							<li><a href="<?php echo URL;?>admin/add/bookings">Booking</a></li>
							<li><a href="<?php echo URL;?>admin/add/proofs">Proofs</a></li>
							<li><a href="<?php echo URL;?>admin/accounts/add">Account</a></li>
							<li class="dropdown-submenu pull-left">
								<a href="#">Orders</a>
								<ul class="dropdown-menu">
									<li><a href="<?php echo URL;?>admin/orders/addquote">Quote</a></li>
									<li><a href="<?php echo URL;?>admin/orders/addinvoice">Invoice</a></li>
								</ul>
							</li>
						</ul>
					</li>
<?php }?>
					<li>
<?php if($user['gravatar']!=''){
		$avatar='http://www.gravatar.com/avatar/'.md5($user['gravatar']);
	}elseif($user['avatar']!=''&&file_exists('media/'.$user['avatar'])){
		$avatar='media/'.$user['avatar'];
	}else{
		$avatar=$noavatar;
	}?>
				<div class="btn-group">
					<a class="btn btn-primary dropdown-toggle" style="margin-top:7px" data-toggle="dropdown" href="#">
						<img id="avatar" class="fa img-circle" src="<?php echo$avatar;?>"> <?php echo$user['username'];?>
						<i class="fa fa-caret-down"></i>
					</a>
					<ul class="dropdown-menu pull-right">
						<li><a href="<?php echo URL.'admin/accounts/edit/'.$user['id'];?>"><i class="fa fa-pencil fa-fw"></i> Edit</a></li>
						<li class="divider"></li>
						<li><a href="<?php echo URL;?>logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
					</ul>
				</div>
					</li>
				</ul>

			</div>
		</div>
	</nav>
</div>
<?php }
