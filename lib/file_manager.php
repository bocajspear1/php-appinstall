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
				$file_uncompress = $this->uncompress($file);
				
				// Delete compressed file
				unlink($file);
				
				$file_untar = $this->untar($file_uncompress);
				
				// Delete tar file
				unlink($file_uncompress);
				
				return $file_untar;
			}
			
		private function uncompress($file)
			{
				$sfp = gzopen("./" . $file, "rb");
				$destination = "./" . str_replace(".gz","",$file);
				
				$fp = fopen($destination, "w");

				while ($string = gzread($sfp, 4096)) {
					fwrite($fp, $string, strlen($string));
				}
				gzclose($sfp);
				fclose($fp);
				
				return $destination;
			}
			
		private function untar($file)
			{
				try
					{
						$phar = new PharData($file);
						$destination = './' . str_replace('.tar',"", $file);
						$phar->extractTo($destination);
						
						return $destination;
					}
					catch (Exception $e)
					{
						echo "ERROR: Improper Previous Removal!";
					}


			}
			
		public function move_temp($temp,$install_location)
			{
				$dir_contents = scandir($temp);
				if (count($dir_contents)==3)
					{
						$this->xmove($temp . "/" . $dir_contents[2],$install_location);
					}else{
						$this->xmove($temp,$install_location);
					}
				
				
				$this->remove_dir($temp);
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
				$this->clear_directory('./temp');
			}
			
		public function is_temp_directory()
			{
				// Make sure the temp directory exists
				if (!file_exists('./temp'))
					{
						return false;
					}else{
						return true;
					}
			}
			
		public function make_temp_directory()
			{
				if (!file_exists('./temp'))
					{
						// If not, make it
						mkdir('./temp');
						
					}else{
						
					}
			}
	}

?>
