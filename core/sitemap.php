<?php
header('Content-type:text/xml');
include'db.php';
echo'<?xml version="1.0" encoding="UTF-8"?>';?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<?php $s=$db->query("SELECT contentType FROM menu WHERE active='1'");
while($r=$s->fetch(PDO::FETCH_ASSOC)){?>
	<url>
		<loc><?php echo URL.$r['contentType'];?></loc>
		<changefreq>daily</changefreq>
		<priority>0.85</priority>
	</url>
<?php $s2=$db->prepare("SELECT contentType,title FROM content WHERE contentType=:contentType AND status='published' AND internal!='1' ORDER BY ti DESC");
	$s2->execute(array(':contentType'=>$r['contentType']));
	while($r2=$s2->fetch(PDO::FETCH_ASSOC)){?>
	<url>
		<loc><?php echo URL.$r['contentType'].'/'.str_replace(' ','-',$r2['title']);?></loc>
		<changefreq>daily</changefreq>
		<priority>0.85</priority>
	</url>
<?php }
}?>
</urlset>
