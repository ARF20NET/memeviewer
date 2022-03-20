<?php
	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$query = parse_url($url, PHP_URL_QUERY);

	// Returns a string if the URL has parameters or NULL if not
	if ($query) {
		$pref = '&';
	} else {
		$pref = '?';
	}
	
	if (!isset($_GET["type"])) {
		$_GET["type"] = "img";
	}
	
	if (!isset($_GET["rnd"])) {
		$_GET["rnd"] = "false";
	}
	
	if (!isset($_GET["page"])) {
		$_GET["page"] = 0;
	}
	
	$type = $_GET['type'];
	
	$page = intval($_GET["page"]);
?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="/style.css">
        <title>ARFNET Meme Page</title>
		<style>
		.title {
			font-size: 36px;
		}
		
		header *{
			display: inline-block;
		}
		
		*{
			vertical-align: middle;
			max-width: 100%;
		}
		
		.element {
			width: 20%;
			display: inline-block;
		}
		
		.content {
			display: inline;
			//flex-wrap: wrap;
		}
		
		.element span {
			display: block;
		}
		
		.element video {
			//width: 20%;
		}
		</style>
	</head>
	<body>
		<header>
			<img src="/arfnet_logo.png" width="64">
			<span class="title"><strong>ARFNET</strong></span>
		</header>
		<h3>Why not</h3>
		<hr>
		<a class="home" href="/">Home</a><br>
		<a class="text" href="?type=img">Image</a>
		<a class="text" href="?type=vid&page=0">Video</a>
		<a class="text" href="?type=scr">Even more random screenshots</a>
		<a class="text" href="?type=eaf">Explosions and fire</a><br>
		<a class="text" href="<?php echo "$_SERVER[REQUEST_URI]".$pref; ?>rnd=false">Order</a>
		<a class="text" href="<?php echo "$_SERVER[REQUEST_URI]".$pref; ?>rnd=true">Random</a><br>
		<?php 
		function showPage() {
			global $type;
			global $page;
			if ($type == "vid") {
				$oldurl = $_SERVER["REQUEST_URI"];
				$newurl = substr($oldurl, 0, 6 + strpos($oldurl, "&page="));
				//$newurl2 = substr($oldurl, strpos($oldurl, "&", 6 + strpos($oldurl, "&page=")));
				echo '<a href="'.$newurl.($page - 1)./*$newurl2.*/'">Previous page</a>';
				echo '<span>Page '.$page.'</span>';
				echo '<a href="'.$newurl.($page + 1)./*$newurl2.*/'">Next page</a>';

			}
		}	
		
		showPage(); ?>
		<hr>
		<div class="content">
		<?php
			function scan_dir($dir) {
				$ignored = array('.', '..', '.svn', '.htaccess');

				$files = array();    
				foreach (scandir($dir) as $file) {
					if (in_array($file, $ignored)) continue;
					$files[$file] = filemtime($dir . '/' . $file);
				}

				arsort($files);
				$files = array_keys($files);

				return ($files) ? $files : false;
			}
		
			if ($type == "img")
				$dir = "/d/FTPServer/dcimg";
			else if ($type == "vid")
				$dir = "/d/FTPServer/dcmemes/transcoded";
			else if ($type == "scr")
				$dir = "/d/FTPServer/morememes";
			else if ($type == "eaf")
				$dir = "/d/FTPServer/explosionsandfire/clips";
			
			$files = scan_dir($dir);
			$type = $_GET['type'];
			
			if ($_GET["rnd"] == "true")
				shuffle($files);
			
			
			$iperpage = 20;
			$base = $iperpage * $page;
			
			for ($i = $base; ($type != "vid" || $i < $base + 20) && $i < count($files); $i++) {
				$file = $files[$i];
				if ($file != "." && $file != "..") {
					if ($type == "img")
						echo "<a href=\"../FTPServer/dcimg/$file\"><img width=\"20%\" src=\"../FTPServer/dcimg/$file\"></a>";
					else if ($type == "vid")
						echo "<video width=\"20%\" controls preload=\"auto\"><source src=\"../FTPServer/dcmemes/transcoded/$file\" type=\"video/mp4\"></video>";
					else if ($type == "scr")
						echo "<a href=\"../FTPServer/morememes/$file\"><img width=\"20%\" src=\"../FTPServer/morememes/$file\"></a>";
					else if ($type == "eaf")
						echo "<div class=\"element\"><video controls preload=\"auto\"><source src=\"../FTPServer/explosionsandfire/clips/$file\" type=\"video/mp4\"></video><span>$file</span></div>";
				}
			}
		?>
		</div>
		<hr>
		<?php showpage();	?>
	</body>
</html>