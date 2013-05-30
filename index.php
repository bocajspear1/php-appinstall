
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>PHP AppInstall</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 1.22" />
	<link href='http://fonts.googleapis.com/css?family=Forum' rel='stylesheet' type='text/css'>
	<style>
	#display 
	{
		
		padding:20px;
		width:400px;
		font-family:Courier New;
		text-align:left;
		margin-left:auto;
		margin-right:auto;
		overflow:auto;
		
	}
	.error
		{
			color:red;
		}
	
	#main
		{
			margin-top:60px;
			text-align:center;
			margin-left:auto;
			margin-right:auto;
			width:550px;
			border:1px solid black;
			border-radius: 15px;
			padding:15px;
			overflow:auto;
		}
	
	#main .row
		{
			display:block;
			width:100%;
			overflow:auto;
			margin:15px;
		}
	
	#main .row label
		{
			display:block;
			float:left;
			width:35%;
		}
		
	#main .row input
		{
			float:left;
			width:60%;
		}
	
	
	#main button
		{
			float:right;
			width:50px;
			display:block;
		}
	
	.criterror 
		{
			padding:15px;
			background-color:#FFB0B0;
			border: 1px dashed red;
			margin:10px;
			line-height:30px;
		}
		
	#title
		{
			display:block;
			font-size:1.5em;
			font-family: 'Forum', cursive;
			margin:5px;
		}
	</style>
	<script src="./js/jquery.js" type="text/javascript" ></script>
	
	
	<script>
		
	// Global for name of the installed instance
	var instance_name = '';
	
	// Global for the download link
	var download_link = '';
	
	// Global for location of installation
	var install_location = '';
	
	$(document).ready(function(){

		$("#go").click(function(){
			start();
		});
	});
	

	
		/* 
		 * Function: start [Protected]
		 *  
		 * Description: Starts the installation process, gives the backend the 'setup' command, if successful, calls
		 * 
		 * Input:
		 *		None
		 * 
		 * Output:
		 * 		None
		 * 
		 * Global:
		 * 		
		 * AJAX:
		 *		Sends request to backend.php
		 *
		 *		Parameters:
		 *			command: 'setup'
		 *			download: download_link
		 *			name: instance_name
		 *			
		 *
		 * Usage:
		 * 		start();
		 * 
		 */
	function start()
		{
			// Get the name of the instance
			instance_name = $("#instance-name").val();
			
			// Get the download link
			download_link = $("#download-link").val();
			
			// Clear the messages
			clear_messages();
			
			// Check to make sure the values given are not blank
			if (instance_name==''||download_link=='')
				{
					alert("The download and/or the instance name is blank!");
					return;
				}
				
			add_message("Starting Installation...<br> Please Wait. <img class='load-img' id='start-img' src='img/wait.gif' height='25' width='25'/><br>");

			// Hide the go button
			hide_button();
			$("#display").css("border","1px dashed black");
			
			$.post("./backend.php",
			{
				command: 'setup',
				name: instance_name,
				download: download_link
			},
			function(data,status){
				if (status == 'success')
					{
						$('.load-img').attr('src','');
					
						if (!is_error(data))
							{
								// Remove the previous waiting image
								$('.load-img').attr('src','');
			
								add_message("<br>Installation setup complete!");

								// Continue onto downloading the file
								download();
							}else{
								add_message("<br><span class='error'>Setup Error:" + data + "</span>");
								restore();
							}
						
					}else{
						add_message("Download Unsuccessful" + data);
						restore();
					}
			  
			});
			
			
			
		}
		
	function download()
		{
			
			
			// Give message indicating step
			add_message("<br>Starting Download...<img class='load-img' id='download-img' src='img/wait.gif' height='25' width='25'/>");
			
			// Send command
			$.post("./backend.php",
				{
					command: 'download'
				},
				function(data,status){
					if (status == 'success')
						{
							// Remove the previous waiting image
							$('.load-img').attr('src','');
						
							if (!is_error(data))
								{
									add_message("<br>Download Complete!");

									unpack();
								}else{
									add_message("<br><span class='error'>Download Error:" + data + "</span>");
									restore();
								}
							
						}else{
							add_message("Download Unsuccessful" + data);
							restore();
						}
				  
				});
			
			
		}
		
	function unpack()
		{
			
			add_message("<br>Unpacking...<img class='load-img' id='unpack-img' src='img/wait.gif' height='25' width='25'/>");
			
			// Send command
			$.post("./backend.php",
				{
					command: 'unpack'
				},
				function(data,status){
					if (status == 'success')
						{
							// Remove the previous waiting image
							$('.load-img').attr('src','');
							
							
							if (!is_error(data))
								{
									
										add_message("<br>Unpacking Complete!");

										move();
										
								}else{
								
									add_message("<br><span class='error'>Unpacking Error:" + data + "</span>");
									restore();
								}
						}else{
							add_message("Unpack Unsuccessful" + data);
							restore();
						}
				  
				});
		}
		
	function move()
		{
			add_message("<br>Moving Files...<img class='load-img' id='unpack-img' src='img/wait.gif' height='25' width='25'/>");
			$.post("./backend.php",
				{
					command: 'move'
				},
				function(data,status){
					if (status == 'success')
						{
							// Remove the previous waiting image
							$('.load-img').attr('src','');
							
							// Check for errors
							if (!is_error(data))
								{
									
									add_message("<br>Files moved successfully!");
									
								
									create_database();
									
									
								}else{
								
									add_message("<br><span class='error'>Moving Error:" + data + "</span>");
									restore();
								}
							
						}else{
							add_message("Unpack Unsuccessful" + data);
							restore();
						}
				  
				});
		}
	
	function create_database()
		{
			add_message("<br>Creating Database...<img class='load-img' id='unpack-img' src='img/wait.gif' height='25' width='25'/>");
			$.post("./backend.php",
				{
					command: 'create_database'
				},
				function(data,status){
					if (status == 'success')
						{
							// Remove the previous waiting image
							$('.load-img').attr('src','');
							
							// Check for errors
							if (!is_error(data))
								{
									
									add_message("<br>Database created successfully!");
									
									redirect();
									
									
								}else{
								
									add_message("<br><span class='error'>Database Error:" + data + "</span>");
									restore();
								}
							
						}else{
							add_message("Database Creation Unsuccessful" + data);
							restore();
						}
				  
				});
			
			
			
		}
	
	function in_string(find,haystack)
		{
			if (haystack.indexOf(find) == -1)
				{
					return false;
				}else{
					return true;
				}
		}
	
	function is_error(data)
		{
			// Check for builtin errors
			if (in_string("ERROR:",data))
				{
					return true;
				// Check for errors from php
				}else if(in_string(" error",data)){
					return true;
					
				}else{
				// Otherwise, you're fine!
					return false;
				}
		}

	function add_message(message)
		{
			$("#display").append(message);
		}
		
	function clear_messages()
		{
			$("#display").html('');
		}
	
	function hide_button()
		{
			$("#go").css('display','none');
		}
	
	function show_button()
		{
			$("#go").css('display','block');
		}
		
	function restore()
		{
			show_button();
		}
		
	function redirect()
		{
			var current_url = document.URL;
			var url_array = current_url.split("/");
			url_array[url_array.length - 1] = '';
			current_url = url_array.join("/") + "install.php";
			
			window.location = current_url;
		}
	</script>
	
</head>

<body>
	<?php
		include('./config.php');
		include('./lib/download_manager.php');
		include('./lib/file_manager.php');
		include('./lib/database_builder.php');

		// Stores if the app should show
		$can_continue = true;
		
		$check = new file_manager();
				
		// Check if there is a temp directory
		if (!$check->is_temp_directory())
			{
				// If there is not, make it
				$check->make_temp_directory();
			}
		
		$user_id = posix_geteuid();
		
		// Check if all folders accessible
		if (!$check->is_accessible("./temp")) 
			{
				echo "<div class='criterror'>The permissions on temp folder do not allow for reading and/or writing to the folder.<br>Please make sure the temp folder is readable and writable by the web server user.</div>";
				$can_continue = false;
			}
		
		if (!$check->is_accessible(INSTALL_FOLDER))
			{
				echo "<div class='criterror'>The permissions on INSTALL_FOLDER folder do not allow for reading and/or writing to the folder.<br>Please make sure the INSTALL_FOLDER folder is readable and writable by the web server user.</div>";
				$can_continue = false;
			}

	?>
	<?php if ($can_continue)
		{
			
			echo '
			<div id="main">
				<span id="title">PHP AppInstall</span>
				<div class="row"><label>Download Link: </label><input id="download-link" name="download-link" type="text" /></div>
				<div class="row"><label>Instance Name: </label><input id="instance-name" name="instance-name" type="text" maxlength="11"/></div>
				<button id="go">Go</button>
				
				
			</div>
			<div id="display"></div>';
			
		}
	?>
</body>

</html>
