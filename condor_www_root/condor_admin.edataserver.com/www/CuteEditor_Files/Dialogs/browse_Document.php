<?php
error_reporting(E_ALL ^ E_NOTICE);
	header("Expires: " . gmdate("D, d M Y H:i:s", time() + (-1*60)) . " GMT"); 
	require("Include_Security.php") ;
	require("Include_Mimetype.php") ;
	$folpath=@$_GET["loc"];
	$goingup=@$_GET["u"];
	$current_Path=@$_GET["DP"];
    
	if (substr($current_Path,strlen($current_Path)-(1))!="/")
		$current_Path=$current_Path."/";
    
	if (strpos(strtolower($current_Path),strtolower(trim($FilesGalleryPath))) <0 || $FilesGalleryPath=="")
	{
		print "The area you are attempting to access is forbidden";
		exit();
	} 
	
	if ($folpath!="" && $goingup!="y" && substr($folpath,strlen($folpath)-(1))!="/")
	{
		$folpath=$folpath."/";
	} 
	
	$action=@$_GET["action"];
	switch ($action)
	{
		case "deletefile":
			unlink(ServerMapPath($_GET["filename"],$AbsoluteFilesGalleryPath,$FilesGalleryPath));
			print "<script language=\"javascript\">parent.ResetFields();</script>";
			break;
		case "renamefile":
			rename(ServerMapPath($_GET["filename"],$AbsoluteFilesGalleryPath,$FilesGalleryPath),ServerMapPath($_GET["newname"],$AbsoluteFilesGalleryPath,$FilesGalleryPath));
			print "<script language=\"javascript\">parent.row_click('".$_GET["newname"]."');</script>";
			break;
		case "renamefolder":
			rename(ServerMapPath($_GET["filename"],$AbsoluteFilesGalleryPath,$FilesGalleryPath),ServerMapPath($_GET["newname"],$AbsoluteFilesGalleryPath,$FilesGalleryPath));
			break;			
		case "downloadfile":			
			$tmpPath = str_replace("\\", "/", ServerMapPath($_GET["filename"],$AbsoluteFilesGalleryPath,$FilesGalleryPath));
			$tmp = explode("/", $tmpPath);
			$fileName = $tmp[sizeof($tmp)-1];

			header("Content-Type: application/octet");
			header('Content-Disposition: attachment; filename="' . $fileName . '"');
			readfile(ServerMapPath($_GET["filename"],$AbsoluteFilesGalleryPath,$FilesGalleryPath));
			break;
		case "deletefolder":			
			rmdir(ServerMapPath($_GET["foldername"],$AbsoluteFilesGalleryPath,$FilesGalleryPath)); 
			break;
		case "createfolder":
			$folderPath=$_GET["foldername"];
			$folderPath=$current_Path.$folpath.$folderPath;
			$folderPath=ServerMapPath($folderPath,$AbsoluteFilesGalleryPath,$FilesGalleryPath);
			if (file_exists($folderPath))
			{
				$exMessage="Unable to create the folder \"".$folderPath."\", there exists a file or a folder with the same name...";
			}
			else
			{
				$result= mkdir($folderPath,0777);
				if(!$result) {
               		$exMessage="Unable to create the folder \"".$folderPath."\", make sure the name you entered is a valid folder name";
				}
				else
				{
					$exMessage="Created the folder \"".$folderPath."\"...";
				} 
			} 
			break;
	} 
	function Showbrowse_Img($spec)
	{
	  extract($GLOBALS);
	  $s="";
	  $directory=ServerMapPath($spec,$AbsoluteFilesGalleryPath,$FilesGalleryPath);
    // if the path has a slash at the end we remove it here
    if(substr($directory,-1) == '/')
    {
      $directory = substr($directory,0,-1);
    }

	  $s=$s."<table border=\"0\" cellspacing=\"1\" cellpadding=\"1\" valign=\"top\" id=\"FoldersAndFiles\" style=\"width:100%\" class=sortable>";
	  $s=$s."<tr onMouseOver=\"row_over(this)\" onMouseOut=\"row_out(this)\" bgcolor=\"#f0f0f0\">";
	  $s=$s."<td width=16 nowrap><img src=\"../Images/refresh.gif\" title=\"refresh\" onclick=\"parent.Refresh('".$folpath."');\" onMouseOver=\"parent.CuteEditor_ColorPicker_ButtonOver(this);\" style=\"VERTICAL-ALIGN: middle\"></td>";
	  $s=$s."<td width=\"220\" Class=\"filelistHeadCol\"><b>Name</b></td>";
	  $s=$s."<td width=50 nowrap Class=\"filelistHeadCol\"><b>Size</b></td>";
	  $s=$s."<td width=50 nowrap Class=\"filelistHeadCol\"><b>Modified</b></td>";
	//  $s=$s."<td width=50 nowrap Class=\"filelistHeadCol\"><b>Created</b></td>";
	  $s=$s."<td width=15 nowrap Class=\"filelistHeadCol\"><b>Attributes</b></td>";
	  $s=$s."<td width=50 nowrap Class=\"filelistHeadCol\"><b>Type</b></td>";
    
	  if ($AllowDelete=="true")
	  {
      $s=$s."<td nowrap></td>";
	  } 

	  if ($AllowRename=="true")
	  {
		  $s=$s."<td nowrap></td>";
	  } 

	  if ($AllowDelete=="true")
	  {
      $s=$s."<td nowrap></td>";
	  } 

	  $s=$s."</tr>";
	  $s=$s."<tr onMouseOver=\"row_over(this)\" onMouseOut=\"row_out(this)\" onclick=\"Editor_upfolder();\">";
	  $s=$s."<td><img src=\"../Images/parentfolder.gif\" title=\"Go up one level\" style=\"VERTICAL-ALIGN: middle\"></td>";
	  $s=$s."<td>...</td>";
	  $s=$s."<td></td>";
	  $s=$s."<td></td>";
	  $s=$s."<td></td>";
	  $s=$s."<td></td>";
    
	  if ($AllowDelete=="true")
	  {
		  $s=$s."<td nowrap></td>";
	  } 
	  if ($AllowRename=="true")
	  {
		  $s=$s."<td nowrap></td>";
	  } 
	  if ($AllowDelete=="true")
	  {
		  $s=$s."<td nowrap></td>";
	  } 
	  $s=$s."</tr>";
    
    $dirlist = array();
    $filelist = array();
    
    // we open the directory
    if($f = opendir($directory))
    {
		  while (($file = readdir($f)) !== false)
		  {
         if ($file != "." && $file != "..") 
         {
            $path = $directory.'/'.$file;        
            if(is_file($path))            
            {
               $filelist[] = $file;  
            }
            elseif(is_dir($path))
            {
               $dirlist[] = $file;            
            }
          }
		   }
       closedir($f);
    }
    
    if($dirlist)
	{
		asort($dirlist);
		while (list ($key, $file) = each ($dirlist))
		{
        $file_stat = ServerMapPath($current_Path.$folpath.$file,$AbsoluteFilesGalleryPath,$FilesGalleryPath);
        $lastmod = date("m/d/Y",filemtime($file_stat)); 
        $p;
        
        if (!(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')){
          $p = show_perms(fileperms($file_stat));
        }
        else 
        {
          $p = base_convert(fileperms($file_stat),10,8);
          $p = substr($p,strlen($p)-3);
        }
            
        //add the html for the folders
		    $str_openfolderEvent="onclick=\"parent.SetUpload_FolderPath('".$current_Path.$folpath.$file."');location.href='browse_Document.php?".$setting."&loc=".$folpath.$file."&Theme=".$Theme."&DP=".$current_Path."';\"";
		    $s=$s."<tr onMouseOver=\"row_over(this)\" onMouseOut=\"row_out(this)\">";
		    $s=$s."<td ".$str_openfolderEvent."><img vspace=\"0\" hspace=\"0\" src=\"../Images/closedfolder.gif\" style=\"VERTICAL-ALIGN: middle\"></td>"."\r\n";
		    $s=$s."<td valign=\"top\" style=\"cursor:pointer\" ".$str_openfolderEvent.">"."\r\n";
		    $s=$s.$file."&nbsp;&nbsp;</td>"."\r\n";
		    $s=$s."<td nowrap style=\"cursor:pointer;\"></td>";	
		    $s=$s."<td nowrap>".$lastmod."</td>";		
	//	    $s=$s."<td nowrap>".$lastmod."</td>";	 
		    $s=$s."<td nowrap>". $p ."</td>";	 
		    $s=$s."<td nowrap>Directory</td>";	   
	      if ($AllowDelete=="true")
		    {
		      $s=$s."<td nowrap style=\"cursor:pointer; border:1px\" ><img vspace=\"0\" hspace=\"0\" src=\"../Images/delete.gif\" onclick=\"deletefolder('".$current_Path.$folpath.$file."')\" title=\"Delete\"></td>";
		    } 

	      if ($AllowRename=="true")
		    {
		      $s=$s."<td nowrap style=\"cursor:pointer; border:1px\" ><img vspace=\"0\" hspace=\"0\" src=\"../Images/edit.gif\" title=\"Rename\" onclick=\"renamefolder('".$current_Path.$folpath.$file."')\"></td>";
		    } 
		    
	      if ($AllowDelete=="true")
		    {
		      $s=$s."<td ></td>";
		    } 
		    $s=$s."</tr>"."\r\n";      
      }
    }    
    if($filelist)
	{
		asort($filelist);
		while (list ($key, $file) = each ($filelist))
		{
			if(ValidImage($file))
			{        
			$file_stat = stat(ServerMapPath($current_Path.$folpath.$file,$AbsoluteFilesGalleryPath,$FilesGalleryPath));
			$size = FormatSize($file_stat[7]);
			$lastmod = date("m/d/Y",$file_stat[10]);
			//$created = date("m/d/Y",$file_stat[8]);        
			//add the html for the folders
	        
			$p;
	        
			if (!(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')){
			$p = show_perms(fileperms(ServerMapPath($current_Path.$folpath.$file,$AbsoluteFilesGalleryPath,$FilesGalleryPath)));
			}
			else 
			{
			$p = base_convert(fileperms(ServerMapPath($current_Path.$folpath.$file,$AbsoluteFilesGalleryPath,$FilesGalleryPath)),10,8);
			$p = substr($p,strlen($p)-3);
			}
			$f_Tooltip="";
			$f_Tooltip=$f_Tooltip."<nobr>Name: ".$file."</nobr><br>";
			$f_Tooltip=$f_Tooltip."<nobr>Size: ".$size."</nobr><br>";
			$f_Tooltip=$f_Tooltip."<nobr>Date modified: ".$lastmod."</nobr><br>";
			$f_Tooltip=$f_Tooltip."<nobr>Attributes: ".$p."</nobr><br>";

		    $s=$s."<tr onclick=\"parent.row_click('".$current_Path.$folpath.$file."','".$f_Tooltip."'); \" onMouseOver=\"row_over(this)\" onMouseOut=\"row_out(this)\">";
		    $s=$s."<td><img vspace=\"0\" hspace=\"0\" src=\"../Images/".strtolower(substr(strrchr($file, '.'), 1)).".gif\" style=\"VERTICAL-ALIGN: middle\"></td>"."\r\n";
		    $s=$s."<td valign=\"top\" style=\"cursor:pointer\" >"."\r\n";
		    $s=$s.$file."&nbsp;&nbsp;</td>"."\r\n";
		    $s=$s."<td nowrap style=\"cursor:pointer;\">".$size."</td>";	
		    $s=$s."<td nowrap>".$lastmod."</td>";		
	//	    $s=$s."<td nowrap>".$created."</td>";	 
		    $s=$s."<td nowrap>".$p."</td>";	 
		    $s=$s."<td nowrap>".FindType(GetExtension($file))."</td>";	         
	      if ($AllowDelete=="true")
		    {
		      $s=$s."<td nowrap style=\"cursor:pointer; border:1px\" ><img vspace=\"0\" hspace=\"0\" src=\"../Images/delete.gif\" onclick=\"deletefile('".$current_Path.$folpath.$file."')\" title=\"Delete\"></td>";
		    } 

	      if ($AllowRename=="true")
		    {
		      $s=$s."<td nowrap style=\"cursor:pointer; border:1px\" ><img vspace=\"0\" hspace=\"0\" src=\"../Images/edit.gif\" title=\"Rename\" onclick=\"renamefile('".$current_Path.$folpath.$file."','$file')\"></td>";
		    } 
		    
	      if ($AllowDelete=="true")
		    {
		      $s=$s."<td nowrap style=\"cursor:pointer; border:1px\" ><img vspace=\"0\" hspace=\"0\" src=\"../Images/download.gif\" onclick=\"downloadfile('".$current_Path.$folpath.$file."')\" title=\"Download\"></td>";
		    } 
		    $s=$s."</tr>"."\r\n";      
			}
      }
    }  
  $s=$s."</table>";
  return $s;
} 

function ValidImage($str_FileName)
{
	extract($GLOBALS);
	$valid = false;
	$ext = GetExtension($str_FileName);
	$filter_Array;
	$filter_Array=explode(",",strtolower($DocumentFilters));
	foreach ($filter_Array as $v) {
   		if(strcasecmp($ext,$v)==0)
		{
			$valid = true;
			break;
		}
	}
	return $valid;
} 

function FormatSize ($size) {

    // Setup some common file size measurements.
    $kb = 1024;         // Kilobyte
    $mb = 1024 * $kb;   // Megabyte
    $gb = 1024 * $mb;   // Gigabyte
    $tb = 1024 * $gb;   // Terabyte
    
    /* If it's less than a kb we just return the size, otherwise we keep going until
    the size is in the appropriate measurement range. */
    if($size < $kb) {
        return $size." B";
    }
    else if($size < $mb) {
        return round($size/$kb,2)." KB";
    }
    else if($size < $gb) {
        return round($size/$mb,2)." MB";
    }
    else if($size < $tb) {
        return round($size/$gb,2)." GB";
    }
    else {
        return round($size/$tb,2)." TB";
    }
}	
?>
<html>
  <head>
    <title>Browse</title>
    <script type="text/javascript">
      var folderpath = "browse_Document.php?<?php echo $setting ; ?>&Theme=<?php echo $Theme; ?>&DP=<?php echo $current_Path ; ?>";

      function Editor_upfolder(path) {
      var arrloc = curloc.split("/");
      str = "";
      for (i=0;i<arrloc.length-2;i++) {
				str += arrloc[i] + "/";
			}
			
			str="browse_Document.php?<?php echo $setting ; ?>&Theme=<?php echo $Theme; ?>&DP=<?php echo $current_Path ; ?>&loc=" + str + "&u=y";
			location.href = str;
			parent.SetUpload_FolderPath('<?php echo $current_Path ; ?>');
		}
		
		
		function deletefile(path)
		{
	                if(isDemoMode)
                    {
                        alert("This function is disabled in the demo mode.");
	                    return;
	                } 
			if (confirm("Delete File " + path + "?")) {
				self.location.replace(folderpath+"&loc=<?php echo $folpath; ?>&action=deletefile&filename=" + path + "");
			}	
		}
		
		function deletefolder(path)
		{
	                if(isDemoMode)
                    {
                        alert("This function is disabled in the demo mode.");
	                    return;
	                } 
			if (confirm("Delete Folder " + path + "?")) {
				self.location.replace(folderpath+"&loc=<?php echo $folpath; ?>&action=deletefolder&foldername=" + path + "");
			}	
		}
		function downloadfile(path)
		{
	                if(isDemoMode)
                    {
                        alert("This function is disabled in the demo mode.");
	                    return;
	                } 
			self.location.replace(folderpath+"&loc=<?php echo $folpath; ?>&action=downloadfile&filename=" + path + "");
		}
		function renamefile(path,oldname)
		{		
	                if(isDemoMode)
                    {
                        alert("This function is disabled in the demo mode.");
	                    return;
	                } 
			var i=oldname.lastIndexOf('.'); 
			var ext=oldname.substr(i);
			var t= oldname.substr(0,oldname.lastIndexOf('.'));
			
			if(parent.Browser_IsIE7())
	        {
		        parent.IEprompt(promptCallback,'Type the new name for this file:', "");		
		        function promptCallback(newName)
		        {				 
	               if ((newName) && (newName!=""))
	               {
	               newName=newName + ext;    		
		                self.location.replace(folderpath+"&loc=<?php echo $folpath; ?>&action=renamefile&filename=" + path + "&newname=<?php echo $current_Path; ?><?php echo $folpath; ?>" + newName + "");
	               }	
		        }
	        }
	        else
	        {
			    var newName=prompt("Type the new name for this file:",t);
    						 
			    if ((newName) && (newName!=""))
			    {
				    newName=newName + ext;
				    self.location.replace(folderpath+"&loc=<?php echo $folpath; ?>&action=renamefile&filename=" + path + "&newname=<?php echo $current_Path; ?><?php echo $folpath; ?>" + newName + "");
			    }
	        }	
		}
		
		
		function renamefolder(path)
		{
	                if(isDemoMode)
                    {
                        alert("This function is disabled in the demo mode.");
	                    return;
	                } 
		    if(parent.Browser_IsIE7())
	        {
		        parent.IEprompt(promptCallback,'Type the new name for this folder:', "");		
		        function promptCallback(newName)
		        {
			        if ((newName) && (newName!=""))
			        {
				        self.location.replace(folderpath+"&loc=<?php echo $folpath; ?>&action=renamefolder&filename=" + path + "&newname=<?php echo $current_Path; ?><?php echo $folpath; ?>" + newName + "");
			        }	
		        }
	        }
	        else
	        {
			    var newName = prompt('Type the new name for this folder:','');
			    if ((newName) && (newName!=""))
			    {
				    self.location.replace(folderpath+"&loc=<?php echo $folpath; ?>&action=renamefolder&filename=" + path + "&newname=<?php echo $current_Path; ?><?php echo $folpath; ?>" + newName + "");
			    }	
	        }	
		}
		function row_over(row)
		{
			row.style.backgroundColor='#eeeeee';
		}
		function row_out(row)
		{
			row.style.backgroundColor='';
		}
	</script>
	<script type="text/javascript">var curloc = "<?php echo $folpath; ?>";</script> 
	<script type="text/javascript" src="filebrowserpage.js"></script>
	<script type="text/javascript" src="sorttable.js"></script>
	
	<style type="text/css">
		body
		{		
	        margin:0 0 0 0;
	        padding:0 0 0 0;
			background-color: white;
		}
		.sortable
		{
			color: windowtext; font:normal 11px Tahoma; 
		}
		.filelistHeadCol A
		{
			font:normal 11px Tahoma; 
			cursor: default; 
			color: windowtext;
		}

		a.filelistHeadCol:visited, a.filelistHeadCol:link /* Font used for links in the navigation menu */
		{
			font:normal 11px Tahoma;
			color: #ffffff;
			text-decoration: none; 
		}

		a.filelistHeadCol:Hover /* Font used for hovering over a link in the navigation menu */
		{
			color: #FF3300;
			font:normal 11px Tahoma; 
		}
	</style>	
</head>
<body>
    <?php 
      echo Showbrowse_Img($current_Path.$folpath);
	  ?>
</body>
</html>