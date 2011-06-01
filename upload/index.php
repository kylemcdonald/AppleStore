<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Uploader</title>
	</head>
	<body>
		<p>
			<?php
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				
				include '../ImgUploader.class.php';
				
				$ip = $_SERVER['REMOTE_ADDR'];
				
				$blocked = array(
					// add any blocked addresses here
				);
				
				$safe = TRUE;
				foreach($blocked as $cur) {
					if($cur == $ip) {
						$safe = FALSE;
						break;
					}
				}
				
				if($safe) {
					$targetDir = '/applestore/images/';
				} else {
					$targetDir = '/applestore/blocked/';
				}
				$img = new imgUploader($_FILES['file']);
				$name = time().":".$ip;
				$full = $img->upload_unscaled($targetDir, $name);
				if($full) {
					echo '<img src="../images/'.$name.'.png" />';
				} else {
					echo 'ERROR! '.$img->getError();
				}
			};
			?>
		</p>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
			<p>
				<input type="hidden" name="MAX_FILE_SIZE" value="131072" />
				<input type="file" name="file" />
				<input type="submit" value="Upload File" />
			</p>
		</form>
	</body>
</html>
