<?php

class ImageUpload  {

	protected $imagesFolder = "images/";
	protected $message = [];
	protected $permitted_types = [
		'image/jpeg',
		'image/bmp',
		'image/png',
		'image/jpg',
		'image/gif'
	];
	private $file_name;
	private $file_type;
	private $is_validated;
	private $temp_name;
	private $new_name;
	private $is_name_changed;
	private $file_error;
	private $file_size;
	public function __construct ()  {

		if ( ! is_dir ( $this->imagesFolder ) || ! is_writable ( $this->imagesFolder ) )  {
			$this->message[] .= "Folder not found or is not writable";	
		}
		if ( $this->imagesFolder[ strlen($this->imagesFolder)-1 ] != '/')  {
			$this->imagesFolder .= '/';
		}
		$this->file_name = $_FILES['filename']['name'];
		$this->file_type = $_FILES['filename']['type'];
		$this->temp_name = $_FILES['filename']['tmp_name'];
		$this->file_error = $_FILES['filename']['error'];
		$this->file_size = $_FILES['filename']['size'];		
		$this->is_validated = false;
		$this->is_name_changed = false;

		$this->ValidateImage();
		$this->UploadImage();
		$this->ShowResult();
	}	
	public function ValidateImage ()  {
		if ( strlen($this->file_name) > 255 || strlen($this->file_name) < 1 )  {
			$this->message[] .= "Change file name and upload again";
			return;
		}
		if ( file_exists($this->imagesFolder . $this->file_name) )  {
			// do not reject file rather keep it.
			$fullpath = $this->imagesFolder . $this->file_name;
			$file_info = pathinfo($fullpath);
			$this->new_name = $file_info['filename'];
			$this->new_name .= "-". mt_rand(1000, 10000000);
			$this->new_name .= "." . $file_info['extension'];
			$this->is_name_changed = true;
		}
		if ( in_array($this->file_type, $this->permitted_types) )  {
			$this->is_validated = true;
		} 
		if ( $this->file_size > 1024 * 1024 )  {
			$this->message[] .= "Images more than 1 MB are not allowed";
			$this->is_validated = false;
		}
	}
	public function UploadImage ()  {

		if ( $this->is_validated )  {
			if ( $this->is_name_changed )  {
				$result = move_uploaded_file($this->temp_name, $this->imagesFolder . $this->new_name);
				if ( $result )  {
					$this->message[] .= "Success";
				}
			}
			$is_moved = move_uploaded_file($this->temp_name, $this->imagesFolder . $this->file_name);
			if ( !$is_moved )  {
				$this->message[] .= "Can't move the file to folder";
			}
		}
		else  {
			$this->message[] .= "Not validated";
		}
	}
	public function ShowResult ()  {
		foreach ( $this->message as $error )  {
			echo "<pre>";
				echo $error . "<br />";
			echo "</pre>";
		}
	}
}
?>
