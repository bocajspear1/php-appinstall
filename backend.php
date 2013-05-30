<?php
// Included the needed files

include('./config.php');
include('./lib/download_manager.php');
include('./lib/file_manager.php');
include('./lib/database_builder.php');

// Start Session
session_start();

// Check if command given
if (array_key_exists('command',$_POST)	)
	{
		// Get command sent
		$command = $_POST['command'];
		
		if ($command == 'setup')
			{
				// Clear out session
				$_SESSION['database_info'] = '';
				$_SESSION['download'] = '';
				$_SESSION['name'] = '';
				$_SESSION['location'] = '';
				$_SESSION['install_location'] = '';
								
				
				$check = new file_manager();
				
				// Setup, here we need to clear out the temp directory
				if ($check->is_temp_directory())
					{	 
						// If the temp direcotry exists, clear it
						$check->clear_temp_directory();
						
					}else{
						// If not make it
						$check->make_temp_directory();
					}
				
				
				if (!array_key_exists('name',$_POST)||!array_key_exists('download',$_POST)|| trim($_POST['name'])==''|| trim($_POST['download'])=='')
					{
						echo "ERROR: Recieved blank paramater";
					}else{
						$_SESSION['download'] = $_POST['download'];
						$_SESSION['name'] = $_POST['name'];
						
						// Check if the instance already exists
						
						if ($check->instance_exists($_SESSION['name']))
							{
								echo "ERROR: Instance already exists";
							}
						
					}
				
			}else if($command == 'download'){
				
				// This will download the given file
				if (array_key_exists('download',$_SESSION)&&trim($_SESSION['download']!=''))
					{
						// Make new download manager object
						$downloader = new download_manager();
						 
						// Download file, get location to where the file was downloaded and put it in 
						$_SESSION['location'] = $downloader->download($_SESSION['download']);
						
						if ($_SESSION['location']==false)
							{
								echo "ERROR: URL rejected";
							}
						
					}else{
						echo "ERROR: No file given";
					}
				
			}else if ($command=='unpack'){
				$unpacker = new file_manager();
					
				if ($_SESSION['location']!='')
					{
						$_SESSION['location'] = $unpacker->unpack($_SESSION['location']);
					}
				
				$unpacker = '';
				
			}else if ($command=='move'){
				
				if (array_key_exists('name',$_SESSION)&&trim($_SESSION['name']!=''))
					{
						$mover = new file_manager();
					
						if ($_SESSION['location']!='')
							{
								$_SESSION['install_location'] = $mover->create_install_folder($_SESSION['name']);
								$result = $mover->move_temp($_SESSION['location'],$_SESSION['install_location']);
								if ($result==false)
									{
										echo "ERROR: Folder Empty for some reason!";
									}
								
							}
					}else{
						echo "ERROR: No instance given";
					}
				
				
			}else if ($command=='create_database'){
				if (array_key_exists('name',$_SESSION)&&trim($_SESSION['name']!=''))
					{
						$database = new database_builder();
						$results = $database->create($_SESSION['name']);
						
						$_SESSION['database_info'] = $results;
					}
			}
		
		
	}else{
		echo "ERROR: No Command Given";
	}


?>
