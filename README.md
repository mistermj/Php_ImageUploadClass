# Php_ImageUploadLibrary
Upload Image calling only two functions

## How it works?

```php
<?php
	if ( isset ( $_POST['submit'] ) )  {
		$image = new ImageUpload();
		$image->ValidateImage();
		$image->UploadImage();
 	}
?>
```
