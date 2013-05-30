<?php

class download_manager
	{
		public function __construct()
			{
				// Make sure the temp directory exists
				if (!file_exists('./temp'))
					{
						// If not, make it
						mkdir('./temp');
					}
			}
		
		public function download($download)
			{
				if ($this->valid_link($download))
					{
						$current = file_get_contents($download);
			
						$file = "./temp/tempfile.tar.gz";
						file_put_contents($file, $current);
						
						return $file;
					}else{
						return false;
					}
				
				
			}
			
		private function valid_link($link)
			{
				// Make sure it has http:// or https:// in beginning
				$prefix = substr($link,0,7);
				
				if ($prefix=='http://')
					{
						return true;
					}else{
						$prefix = substr($link,0,8);
						
						if ($prefix=='https://')
							{
								return true;
							}else{
								return false;
							}
					}
				
				
			}
	}

				
				

?>

