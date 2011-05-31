<?php

/* 
This tool was developed by Michael Diamond (http://www.DigitalGemstones.com) �2007
and is released freely for personal and corporate use under the licence which can be found at: 
http://digitalgemstones.com/licence.php 
and can be summarized as: 
You are free to use this software for any purpose as long as Digital Gemstones is credited, 
and may redistribute the software in its original form or modified as you see fit,  
as long as any credit comments in the code remain unchanged. 
*/ 

define('MAX_SIZE_EXCEDED', 101);
define('UPLOAD_FAILED', 102);
define('NO_UPLOAD', 103);
define('NOT_IMAGE', 104);
define('INVALID_IMAGE', 105);
define('NONEXISTANT_PATH', 106);

class ImgUploader
{
  var $tmp_name;
  var $name;
  var $size;
  var $type;
  var $error;
  var $width_orig;
  var $height_orig;
  var $num_type;
  var $errorCode = 0;
  var $allow_types = array(IMAGETYPE_JPG);
  
  function __construct($fileArray)
  {
    foreach($fileArray as $key => $value)
    {
      $this->$key = $value;
    }
    if($this->error > 0)
    {
      switch ($this->error)
      {
        case 1: $this->errorCode = MAX_SIZE_EXCEDED; break;
        case 2: $this->errorCode = MAX_SIZE_EXCEDED; break;
        case 3: $this->errorCode = UPLOAD_FAILED; break;
        case 4: $this->errorCode = NO_UPLOAD; break;
      }
    }
    if($this->errorCode == 0)
    {
      $this->secure();
    }
  }
  
  function secure()
  {
    //$this->num_type = exif_imagetype($this->tmp_name);
    @list($this->width_orig, $this->height_orig, $this->num_type) = getimagesize($this->tmp_name);
    
    if(filesize($this->tmp_name) > (1024 * 128)) // allows for 128 KB
    {
      $this->errorCode = MAX_SIZE_EXCEDED;
      return false;
    }
    
  	if (!$this->num_type)
  	{
  	  $this->errorCode = NOT_IMAGE;
  		return false;
  	}
  	if(!in_array($this->num_type, $this->allow_types))
  	{
  	  $this->errorCode = INVALID_IMAGE;
  	  return false;
  	}
  }
  
  function getError()
  {
    return $this->errorCode;
  }
  
  function upload_unscaled($folder, $name)
  {
    return $this->upload($folder, $name, "0", "0");
  }
  
  function upload($folder, $name, $width, $height, $scaleUp = false)
  {
    // $folder is location to be saved
    // $name is name of file, without file extention
    // $width is desired max width
    // $height is desired max height
    
    if($this->errorCode > 0)
      return false;
    
    // deal with sizing
    // if image is small enough to not scale, or upload_unscaled() is called, don't scale
    if((!$scaleUp && ($width > $this->width_orig && $height > $this->height_orig)) || ($width === "0" && $height === "0"))
    {
      $width = $this->width_orig;
      $height = $this->height_orig;
    }
    else
    {
      // if height diff is less than width dif, calc height
      if(($this->height_orig - $height) <= ($this->width_orig - $width))
        $height = ($width / $this->width_orig) * $this->height_orig;
      else
        $width = ($height / $this->height_orig) * $this->width_orig;
    }
  
    // Resample
    switch($this->num_type)
    {
      case IMAGETYPE_GIF: $image_o = imagecreatefromgif($this->tmp_name); $ext = '.gif'; break;
      case IMAGETYPE_JPEG: $image_o = imagecreatefromjpeg($this->tmp_name); $ext = '.jpg'; break;
      case IMAGETYPE_PNG: $image_o = imagecreatefrompng($this->tmp_name); $ext = '.png'; break;
    }
    
    $filepath = $folder.(substr($folder,-1) != '/' ? '/' : '');
    if(is_dir($_SERVER['DOCUMENT_ROOT'].$filepath))
      $filepath .= $name.$ext;
    else
    {
      echo "can't fined '".$_SERVER['DOCUMENT_ROOT'].$filepath."'";
      $this->errorCode = NONEXISTANT_PATH;
      imagedestroy($image_o);
      return false;
    }
      
    $image_r = imagecreatetruecolor($width, $height);
    imagecopyresampled($image_r, $image_o, 0, 0, 0, 0, $width, $height, $this->width_orig, $this->height_orig);
    
    switch($this->num_type)
    {
      case IMAGETYPE_GIF: imagegif($image_r, $_SERVER['DOCUMENT_ROOT'].$filepath); break;
      case IMAGETYPE_JPEG: imagejpeg($image_r, $_SERVER['DOCUMENT_ROOT'].$filepath); break;
      case IMAGETYPE_PNG: imagepng($image_r, $_SERVER['DOCUMENT_ROOT'].$filepath); break;
    }
  
    imagedestroy($image_o);
    imagedestroy($image_r);
    
    return '/'.$filepath;
  }
}