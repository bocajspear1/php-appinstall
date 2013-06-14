<?php
session_start();

$location = $_SESSION['install_location']
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Complete the installation of <?php echo $_SESSION['name'];?></title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 1.22" />
	<script src="./js/jquery.js" type="text/javascript" ></script>
	<script>
	
	$(document).ready(function(){
			$('#frame_display').load(function(){
			
			$("#url-current").val(document.getElementById('frame_display').contentWindow.location.href);
		});
		
	});
	</script>
	<style>
		html
			{
				height:100%;
			}
		body
			{
				height:100%;
				padding:0px;
				margin:0px;
			}
		div#info
			{
				padding:0px;
				margin:0px;
				position:fixed;
				box-shadow: 1px 0px 2px black;
				overflow:auto;
				width:100%;
				height:90px;
				background-color:white;
			}
			
		div#info ul
			{
				padding:0px;
				margin:0px;
			}
			
		div#info ul li
			{
				float:left;
				display:block;
				margin:5px;
				padding:7px;
			}
		
		div#go
			{
				float:right;
				margin:5px;
				padding:7px;
			}
		
		div#go  a
			{
				color:blue;
			}
		
		iframe
			{
				margin-top:90px;
				width:100%;
				
				min-height: 800px;
				border:0px solid white;
			}
			
		.value
			{
				font-family:Courier New;
			}
		
	#menu
		{
			float:left;
			position:absolute;
			bottom:0;
			padding:15px;
		}
		
	#menu button
		{
			display:block;
			height:25px;
			float:left;
		}
	
	#menu #url-current
		{
			width:500px;
			float:left;
			background-color:white;
		}
	</style>
</head>

<body>

	<div id="info">
		<div id="menu">
		
			<input type="text" disabled="disabled" value="" id="url-current">
			<button onclick="document.getElementById('frame_display').contentWindow.history.go(-1);">Back</button>
		</div>
		<ul>
			<li><strong>Database Name:</strong> <span class="value"><?php echo $_SESSION['database_info']['database'];?></span></li>
			<li><strong>Database Username:</strong> <span class="value"><?php echo $_SESSION['database_info']['user'];?></span></li>
			<li><strong>Database Password:</strong> <span class="value"><?php echo $_SESSION['database_info']['password'];?></span></li>
			<li></li>
		</ul>
		
		<div id="go">
			
			<a href="<?php echo $location;?>">Go to Site (Don't forget your database info!)</a>
			<br><a href="#" onclick="document.getElementById('frame_display').contentWindow.location.reload(true);">Reload Frame</a> (Use this instead of the browser's reload)
			
		</div>	
	</div>

	<iframe src="<?php echo $location?>" id="frame_display">
	
	</iframe>
	<script>
	
	</script>
</body>

</html>
