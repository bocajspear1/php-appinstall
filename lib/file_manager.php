<?php

class file_manager
	{
		// Creates location where the files downloaded are installed
		public function create_install_folder($name)
			{
				if (!file_exists(INSTALL_FOLDER))
					{
						mkdir(INSTALL_FOLDER);
					}
				$install_location = INSTALL_FOLDER . "/" . $name;
				mkdir($install_location);
				return $install_location;
			}
		
		public function is_accessible($folder)
			{
				if (is_writable($folder)&&is_readable($folder))
					{
						return true;
					}else{
						return false;
					}
			}

		public function instance_exists($name)
			{
				if (!file_exists(INSTALL_FOLDER . "/" . $name))
					{
						return false;
					}else{
						return true;
					}
			}
			
		public function unpack($file)
			{
				$unpacked_file = '';
				$uncompressed_file = '';
				
				if ($this->get_file_type($file)=='application/x-gzip')
					{
						$file = $this->rename_file($file,TEMP_FOLDER . '/tempfile.tar.gz');
						
						
						// Uncompress this file
						$uncompressed_file = $this->uncompress_gz($file);
						
						// Unpack the file
						$unpacked_file = $this->untar($uncompressed_file);
						
					}else if ($this->get_file_type($file)=='application/zip'){
						
						
						$file = $this->rename_file($file,TEMP_FOLDER . '/tempfile.zip');
						
						$unpacked_file = $this->uncompress_zip($file);
						
					}else if ($this->get_file_type($file)=='application/x-bzip2'){
						throw new Exception("Filetype currently not supported!");
					}else{
						throw new Exception("Filetype is not supported type.");
					}
				
				
				
				
				
				
				return $unpacked_file;
			}
		
		private function rename_file($file,$to)
			{
				
				rename($file,$to);
				return $to;
			}
		
		private function get_file_type($name)
			{
				if (trim($name!=''))
					{
						$fileinfo = finfo_open(FILEINFO_MIME_TYPE);
						$filetype = finfo_file($fileinfo, $name);
						finfo_close($fileinfo);
						
						return $filetype;
					}else{
						throw new Exception("Name was blank in file type finder!");
					}
				
			}
		
		private function uncompress_zip($file)
			{
				
				$destination = './' . str_replace('.zip',"", $file);
				$zip = new ZipArchive;
				if ($zip->open($file) === TRUE) {
					$zip->extractTo($destination);
					$zip->close();
					unlink($file);
					return $destination;
				} else {
					throw new Exception("Unzip Failed!");
				}
			}
		
		private function uncompress_gz($file)
			{
				$sfp = gzopen("./" . $file, "rb");
				$destination = "./" . str_replace(".gz","",$file);
				
				$fp = fopen($destination, "w");

				while ($string = gzread($sfp, 4096)) {
					fwrite($fp, $string, strlen($string));
				}
				gzclose($sfp);
				fclose($fp);
				unlink($file);
				return $destination;
			}
			
		private function untar($file)
			{
				$destination = './' . str_replace('.tar',"", $file);
				try
					{
						$phar = new PharData($file);
						
						$phar->extractTo($destination);
						
						return $destination;
					}
					catch (Exception $e)
					{
						if (strpos($e->getMessage(), 'corrupted tar') !== FALSE)  
							{
								
								$just_folder_array = explode("/",$destination);
								$just_folder = $just_folder_array[count($just_folder_array)-1];
								
								mkdir('./temp/' . $just_folder);
								 
								exec('tar xvf ' . $file . " -C ./temp/" . $just_folder);
								
								// Delete tar file
								unlink($file);
								return $destination;
							}else{
								echo "ERROR: " . $e->getMessage();
							}
						
					
						//
					}


			}
			
		public function move_temp($temp,$install_location)
			{
				$dir_contents = scandir($temp);
				if (count($dir_contents)==2)
					{
						return false;
					}else if (count($dir_contents)==3){
						$this->xmove($temp . "/" . $dir_contents[2],$install_location);
					}else{
						$this->xmove($temp,$install_location);
					}
				
				
				$this->remove_dir($temp);
				return true;
			}

			
		private function xmove($src,$dest)
			{
				$dir_contents = scandir($src);
				
				
				
			 foreach  ($dir_contents as $file) 
				{
					 if (is_dir($src . "/" . $file)&&($file!='.') &($file!='..')) {
							mkdir ($dest.'/'.$file);
						   $this->xmove($src.'/'.$file, $dest.'/'.$file);
						   rmdir($src.'/'.$file);
					   } else if (!is_dir($file)){
						   copy($src.'/'.$file, $dest.'/'.$file);
						   unlink($src.'/'.$file);
					   }
				}
			}	 
			   
		public function remove_dir($dir)
			{
				$dir_contents = scandir($dir);
				foreach ($dir_contents as $item)
					{
						$item_path = $dir . '/' . $item;
						if (is_dir($item_path) && $item != '.' && $item != '..')
							{
								$this->remove_dir($item_path);
							}else if (!is_dir($item_path)){
								unlink ($item_path);
							}
					}
				rmdir($dir);
			}
			 
		 
		public function clear_directory($dir)
				{
					// Remove the directory
					$this->remove_dir($dir);
					// Remake the directory
					mkdir($dir);
				}
		
		public function clear_temp_directory()
			{
				$this->clear_directory(TEMP_FOLDER);
			}
			
		public function is_temp_directory()
			{
				return $this->folder_exists(TEMP_FOLDER);
			}
			
		public function make_temp_directory()
			{
				if (!file_exists(TEMP_FOLDER))
					{
						// If not, make it
						mkdir(TEMP_FOLDER);
						
					}else{
						
					}
			}
			
		public function folder_exists($folder)
			{
					// Make sure the temp directory exists
				if (!file_exists($folder))
					{
						return false;
					}else{
						return true;
					}
			}
	}

?>
