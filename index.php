<?php // START PHP
include_once(dirname(__FILE__) . '/class.folder.php');
$folder = new Folder();
$deep	= isset($_GET['deep']) ? $_GET['deep'] : false;
// END PHP ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-type" content="text/html; charset=iso-8859-1" />
<title><?php echo Config::get('sitename') ?></title>

<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/font-awesome.css">
<link rel="stylesheet" type="text/css" href="css/font-awesome-ie7.css">
<link rel="stylesheet" type="text/css" href="css/style.css">

<script type="text/javascript" src="js/jquery-1.8.1.min.js"></script>
<script type="text/javascript" src="js/jquery-icontains.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/stupidtable.min.js"></script>
<script type="text/javascript" src="js/core.js"></script>
</head>
<body>
	<div class="container" style="padding-top: 30px;">
		<h3><?php echo $folder->name == '' ? Config::get('sitename') : $folder->name ?></h3>
		<div class="pull-left">
			<p><?php echo $folder->count; ?> objects in this folder, <?php echo Folder::format($folder->size) ?> total.</p>
			<?php if($folder->current) { ?><p><i class="icon-chevron-left"></i> &nbsp;<a href="<?php echo $folder->getUpUrl() ?>">Back</a></p><?php } ?>
		</div>
		<div class="pull-right">
				<div class="input-prepend input-append">
					<div>
						Search: <input type="text" name="search" id="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : '' ?>">
						<button class="btn" type="button" id="btn-search" title="Search"><i class="icon-search"></i></button>
						<button class="btn" type="button" id="btn-reset" title="Reset"><i class="icon-repeat"></i></button>
					</div>
					<label class="checkbox">
						<input type="checkbox" name="deep"<?php if($deep != false) echo ' checked="checked"' ?>> Search subfolder
					</label>
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
	</div>
	<div class="modal hide fade" id="image-preview">a</div>
</body>
</html>