	<?php // START PHP
include_once(dirname(__FILE__) . '/classes/class.config.php');
$folder = new Folder();
$deep	= isset($_GET['deep']) ? $_GET['deep'] : false;
// END PHP ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-type" content="text/html; charset=iso-8859-1" />
<title><?php echo Config::get('sitename') ?></title>

<link rel="stylesheet" type="text/css" href="<?php echo Config::get('baseurl') ?>css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo Config::get('baseurl') ?>css/font-awesome.css">
<link rel="stylesheet" type="text/css" href="<?php echo Config::get('baseurl') ?>css/font-awesome-ie7.css">
<link rel="stylesheet" type="text/css" href="<?php echo Config::get('baseurl') ?>css/style.css">

<script type="text/javascript">
	var baseurl = '<?php echo Config::get('baseurl') ?>';
	var rewrite = <?php echo (int) Config::get('rewrite') ?>;
</script>
<script type="text/javascript" src="<?php echo Config::get('baseurl') ?>js/jquery-1.8.1.min.js"></script>
<script type="text/javascript" src="<?php echo Config::get('baseurl') ?>js/jquery-icontains.js"></script>
<script type="text/javascript" src="<?php echo Config::get('baseurl') ?>js/jquery-querystring.js"></script>
<script type="text/javascript" src="<?php echo Config::get('baseurl') ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo Config::get('baseurl') ?>js/stupidtable.min.js"></script>
<script type="text/javascript" src="<?php echo Config::get('baseurl') ?>js/core.js"></script>
</head>
<body>
	<div class="container" style="padding-top: 30px;">
		<div class="clearfix">
			<h3><?php echo Config::get('sitename') ?></h3>
			<div class="pull-left">
				<p><?php echo $folder->count; ?> objects in this folder, <?php echo Folder::format($folder->size) ?> total.</p>
			</div>
			<div class="pull-right">
				<div class="input-prepend input-append">
					<div>
						Search: <input type="text" name="search" id="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : '' ?>">
						<button class="btn" type="button" id="btn-search" title="Search"><i class="icon-search"></i></button>
						<button class="btn" type="button" id="btn-reset" title="Reset"><i class="icon-repeat"></i></button>
					</div>
					<label class="checkbox">
						<input type="checkbox" name="deep"<?php if($deep != false) echo ' checked="checked"' ?>> Search subfolder <small>(ctrl+s to toggle)</small>
					</label>
				</div>
			</div>
		</div>
		<div class="pull-left">
			<div class="breadcrumb">
				<div class="row">
					<div class="span1 home">
						<a href="<?php echo Config::get('baseurl') ?>"<i class="icon-home"></i></a> <i class="icon-chevron-right"></i>
					</div>
					<div class="span23 breads">
						<ul class="clearfix">
							<?php $trails = $folder->getTrails(); foreach($trails AS $key=>$row) { ?>
							<?php if(isset($trails[$key+1])) { ?>
								<li><a href="<?php echo $row['path'] ?>"><?php echo $row['name'] ?></a><i class="icon-chevron-right"></i></li>
							<?php } else { ?> 
								<li class="last"><?php echo $row['name'] ?></li>
							<?php } } ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<table class='table table-striped' id="idx">
			<thead>
				<tr>
					<th class="type-string sortable">Filename</th>
					<th class="type-int sortable">Size <i class=''></i></th>
					<th class="type-int sortable">Date <i class=''></i></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($folder->getDirs() AS $dir) { ?>
					<tr class="folder">
						<td class="name" data-order-by="<?php echo strtolower($dir['name']) ?>">
							<i class="icon-folder-close"></i> &nbsp;<a href="<?php echo $dir['url'] ?>"><?php echo $dir['name'] ?></a>
							<?php if($deep != false) { ?><div class="path"><?php echo trim($dir['path'], '/') ?></div><?php } ?>
						</td>
						<td class="span3"></td>
						<td class="span3" data-order-by="<?php echo $dir['date'] ?>"><?php echo date(Config::get('date'), $dir['date']) ?></td>
					</tr>
				<?php } ?>
				
				<?php foreach($folder->getFiles() AS $file) { ?>
				<tr>
					<td class="name" data-order-by="<?php echo strtolower($file['name']) ?>">
						<i class="icon-<?php echo $file['icon'] ?>"></i> &nbsp;<a href="<?php echo $file['url'] ?>"><?php echo $file['name'] ?></a>
						<?php if($deep != false) { ?><div class="path"><?php echo trim($file['path'], '/') ?></div><?php } ?>
					</td>
					<td class="span3" style="width:50px;" data-order-by="<?php echo $file['size'] ?>"><?php echo Folder::format($file['size']) ?></td>
					<td class="span3" data-order-by="<?php echo $file['date'] ?>"><?php echo date(Config::get('date'), $file['date']) ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<div id="footer">
			&copy; Created by <a href="http://codeshare.my" target="_blank">Khairul Ashraff</a>. You are free to use this in any away without attribution.
		</div>
	</div>
	<div class="modal hide fade" id="image-preview">a</div>
</body>
</html>