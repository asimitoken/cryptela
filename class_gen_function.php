<?php 

class gen_function extends main_function {
	var $conn;
	function gen_function()
	{
		$this->conn = parent::main_function();
		parent::setMysqlKeyword("now()");
	}
	function close_mysql()
	{
		parent::close_mysql_connection();
	}
	function short_description($descr,$noc="")
	{
	
		$sdescr=strip_tags($descr);
		if(empty($noc))$noc=200;
		if(trim($sdescr)<>'')
		{
			if(strlen($sdescr)>$noc)
				return substr($sdescr, 0, $noc).".....";
			else
				return $sdescr;
		}
		else
		return $sdescr;
	}
	
	function short_description_cw($descr,$noc="")
	{
		
		if(strlen(strip_tags(html_entity_decode($descr)))>$noc)
		{
			$descr = substr(strip_tags(html_entity_decode($descr)),0,$noc);
			$pos   = strrpos($descr," ");
			$descr = substr(strip_tags(html_entity_decode($descr)),0,$pos).'...';							
		}
		else
		{
			$descr = strip_tags(html_entity_decode($descr));						
		}
		
		return $descr;
	}
	
	function fetch_content($id,$type='full',$char=200)
	{
		$resCon=parent::selectData(TABLE_CONTENT,"","content_status='Active' and content_id='".$this->filter_mysql($id)."'",1);
		if($type=='full')
		{
			return html_entity_decode($resCon['content_descr']);
		}
		else
		{
			return $this->short_description(html_entity_decode($resCon['content_descr']),$char);			
		}
	}
	
	function fetch_short_content($id,$type='full',$char=200)
	{
		$resCon=parent::selectData(TABLE_CONTENT,"","content_status='Active' and content_id='".$this->filter_mysql($id)."'",1);
		if($type=='full')
		{
			return html_entity_decode($resCon['content_short_desc']);
		}
		else
		{
			return $this->short_description(html_entity_decode($resCon['content_short_desc']),$char);			
		}
	}
	
	function fetch_content_title($id,$type='n')
	{
		$resCon=parent::selectData(TABLE_CONTENT,"","content_status='Active' and content_id='".$this->filter_mysql($id)."'",1);
		if($type=='n')
		{
			return $resCon['content_header'];
		}
		else
		{
			$data = explode(" ",$resCon['content_header']);
			$title = '<span>'.$data[0].'</span>';
			for($i=1;$data[$i]!='';$i++)
			{
				$title .= " ".$data[$i];
			}
			return $title;
		}			
	}
	
	function fetch_content_second_title($id,$type='n')
	{
		$resCon=parent::selectData(TABLE_CONTENT,"","content_status='Active' and content_id='".$this->filter_mysql($id)."'",1);
		if($type=='n')
		{
			return $resCon['content_header2'];
		}
		else
		{
			$data = explode(" ",$resCon['content_header2']);
			$title = '<span>'.$data[0].'</span>';
			for($i=1;$data[$i]!='';$i++)
			{
				$title .= " ".$data[$i];
			}
			return $title;
		}			
	}
	
	function fetch_content_page($id)
	{
		$resCon=parent::selectData(TABLE_CONTENT,"","content_status='Active' and content_id='".$this->filter_mysql($id)."'",1);
		return $resCon['content_page'];
					
	}
	function fetch_content_banner($id){
			$res=parent::selectData(TABLE_CONTENT,"content_banner","content_id='".$this->filter_mysql($id)."'",1);
			$banner=$res['content_banner'];
			return($banner);
	}
	
	function short_descr($descr,$noc="")
	{
		
		if(strlen(strip_tags(html_entity_decode($descr)))>$noc)
		{
			$descr = substr(strip_tags(html_entity_decode($descr)),0,$noc);
			$pos   = strrpos($descr," ");
			$descr = substr(strip_tags(html_entity_decode($descr)),0,$pos).'...';							
		}
		else
		{
			$descr = strip_tags(html_entity_decode($descr));						
		}
		
		return $descr;
	}
	
	function fetch_box_content($fld,$type='n')
	{
		$resBox=parent::selectData(TABLE_SETTINGS,"","set_id='1'",1);
		if($type=='n')
		{
			return html_entity_decode($resBox[$fld]);
		}
		else
		{
			$data = explode(" ",$resBox[$fld]);
			$dataC = '<span>'.$data[0].'</span>';
			for($i=1;$data[$i]!='';$i++)
			{
				$dataC .= " ".$data[$i];
			}
			return $dataC;
		}
	}

	///////////for redirect url////////
	function reDirect($url)
	{
		@header("Location: ".$url);
		exit;
	}
	function go_To($url)
	{
		echo "<script>window.location.href='$url'</script>";
		exit;
	}
	///////////for redirect url////////
	
	
	function filterData($data,$strip=1)
	{
		$fdata=parent::data_prepare($data,0);
		if(!$strip)
			return $fdata;
		return stripslashes($fdata);
	}
	function filterData_array($datagiven)
	{
		foreach ($datagiven as $key=>$value)
		{
			if(is_array($value))
			{
				$data[$key]=$this->filterData_array($value);
			}
			else
			{
				$data[$key]=$this->filterData($value);
			}
		}
		return $data;
	}
	
	function filter_numeric($data)
	{
		$dataF = preg_replace('/[^0-9]/', '',$data);
		return $dataF;
	}
	function filter_alphabet($data)
	{
		$dataF = preg_replace('/[^a-zA-Z]/','',$data);
		return $dataF;
	}
	function filter_alphanum($data)
	{
		$dataF = preg_replace('/[^a-zA-Z0-9]/', '',$data);
		return $dataF;
	}
	function filter_mysql($data)
	{
		$dataF = mysqli_real_escape_string($this->conn,$data);
		return $dataF;
	}
	
	
	function filter_mysql_array($datagiven)
	{
		foreach ($datagiven as $key=>$value)
		{
			if(is_array($value))
			{
				$data[$key]=$this->filter_mysql_array($value);
			}
			else
			{
				$data[$key]=$this->filter_mysql($value);
			}
		}
		return $data;
	}
	
	function selectDate($sel_val,$year,$month)
	{
		switch($month)
		{
			case '1': case '3': case '5': case '7': case '8': case '10': case '12':
				$no_of_days=31;
				break;
			case '4': case '6': case '9': case '11':
				$no_of_days=30;
				break;
			case '2':
				$no_of_days=28;
				if((($month%4==0)&&($month%100!=0))||($month%400==0))
				{
					$no_of_days=29;
				}
				break;
			default:
			echo "Wrong data";
			break;
		}
		for($i=1;$i<=$no_of_days;$i++)
		{
			$options.="<option value='".$i."'";
			if($sel_val==$i){ $options.=" selected"; } $options.=">".$i."</option>"; 
		}
		return $options;
	}
	function selectMonth($sel_val)// used
	{
		//$options="<option value='0'>Select</option>";
		$options.="<option value='1'";
		if($sel_val=='1'){ $options.=" selected"; } $options.=">1</option>"; 
		$options.="<option value='2'";
		if($sel_val=='2'){ $options.=" selected"; } $options.=">2</option>";
		$options.="<option value='3'";
		if($sel_val=='3'){ $options.=" selected"; } $options.=">3</option>";
		$options.="<option value='4'";
		if($sel_val=='4'){ $options.=" selected"; } $options.=">4</option>";
		$options.="<option value='5'";
		if($sel_val=='5'){ $options.=" selected"; } $options.=">5</option>";
		$options.="<option value='6'";
		if($sel_val=='6'){ $options.=" selected"; } $options.=">6</option>";
		$options.="<option value='7'";
		if($sel_val=='7'){ $options.=" selected"; } $options.=">7</option>";
		$options.="<option value='8'";
		if($sel_val=='8'){ $options.=" selected"; } $options.=">8</option>";
		$options.="<option value='9'";
		if($sel_val=='9'){ $options.=" selected"; } $options.=">9</option>";
		$options.="<option value='10'";
		if($sel_val=='10'){ $options.=" selected"; } $options.=">10</option>";
		$options.="<option value='11'";
		if($sel_val=='11'){ $options.=" selected"; } $options.=">11</option>";
		$options.="<option value='12'";
		if($sel_val=='12'){ $options.=" selected"; } $options.=">12</option>";
		return $options;
	}
	function selectYear($sel_val) // used
	{
		$year=date('Y')+50;
		if(!$sel_val)
		{
			$sel_val==date('Y');
		}
		//$options="<option value='0'>Select</option>";
		for($i=0;$i<100;$i++)
		{
			$options.="<option value=".$year;
			if($year==$sel_val){  $options.=" selected"; } 
			$options.=">".$year."</option>";
			$year--;
			
		}
		return $options;
	}
	
	///////////////////////
	
	function mailBody($bodypart)
	{
		$data='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Hashing Ad Space</title>
</head>
<body>
<table width="600" border="0" cellspacing="1" cellpadding="3" align="center" style="border:1px solid #d6d6d6; font:normal 12px/16px Arial, Helvetica, sans-serif; color:#818181;">
  <tr>
    <td align="left" valign="top" style="height:50px; border-bottom:3px solid #eeefef;background-color:#f9f9f9; text-align: center"><img width="400" src="'.FURL.'img/logo_email.png"/>
	</td>
  </tr>
  <tr>
    <td align="left" valign="top" style="padding:10px 20px 0px 20px; color:#4b4b4b;"><table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td align="left" valign="top">'.$bodypart.'</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" valign="middle" style="height:50px; background-color:#4F595B; color:#fff">Copyright &copy;'.date("Y").' Hashing Ad Space, All Rights Reserved.</td>
  </tr>
</table>
</body>
</html>';
		return $data;
	}
	
	
	function sendMail_server($to="", $subject="", $body="",$from="",$fromname="",$type="",$replyto="",$bcc="",$cc="")
	{
		if(empty($type))
		{
			$type="html";
		}
		if($type=="plain")
		{
			$body = strip_tags($body);
		}
		if($type=="html")
		{
			$body = "<font face='Verdana, Arial, Helvetica, sans-serif'>".$body."</font>";
		}
		/* To send HTML mail*/ 
		$headers = "MIME-Version: 1.0\r\n"; 
		$headers.= "Content-type: text/".$type."; charset=iso-8859-1\r\n";
		/* additional headers */ 
		//$headers .= "To: <".$to.">\r\n"; 
		if(!empty($from))
		{
			$headers .= "From: ".$fromname." <".$from.">\r\n";
		}
		
		if(!empty($replyto))
		{
			$headers .= "Reply-To: <".$replyto.">\r\n"; 
		}
		if(!empty($cc))
		{
			$headers .= "Cc: ".$cc."\r\n";
		}
		if(!empty($bcc))
		{
			$headers .= "Bcc: ".$bcc."\r\n";
		}
		if(@mail($to, $subject, $body, $headers))
		{
			return 1;
		}
		else
		{
			return $headers;
		}
	}
	
	
	function sendMail($to="", $subject="", $body="",$from="",$fromname="",$type="",$replyto="",$bcc="",$cc="")
	{
		if(empty($type))
		{
			$type="html";
		}
		if($type=="plain")
		{
			$body = strip_tags($body);
		}
		if($type=="html")
		{
			$body = "<font face='Verdana, Arial, Helvetica, sans-serif'>".$body."</font>";
		}
		
		global $error;
		$mail = new PHPMailer();  // create a new object
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true;  // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 465; 
		
		$mail->IsHTML(true); 
		
		$mail->Username = GUSER;  
		$mail->Password = GPWD;           
		$mail->SetFrom($from, $from_name);
		$mail->Subject = $subject;
		$mail->Body = $body;
		$mail->AddAddress($to);
		if(!$mail->Send()) 
		{
			$error = 'Mail error: '.$mail->ErrorInfo; 
			return $error;
		} else {
			$error = 'Message sent!';
			return $error;
		}
		
		
		// Instantiation and passing `true` enables exceptions
		$mail = new PHPMailer(true);
		
		try {
			//Server settings
			$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
			$mail->isSMTP();                                            // Send using SMTP
			$mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
			$mail->Username   = GUSER;                                  // SMTP username
			$mail->Password   = GUSER;                                  // SMTP password
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
			$mail->Port       = 587;                                    // TCP port to connect to
		
					
			// Content
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = 'Here is the subject';
			$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
			$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		
			$mail->send();
			echo 'Message has been sent';
		} catch (Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
				
			}
	
	
	
	function sendMailSES($to="", $subject="", $body="",$from="",$fromname="",$type="",$replyto="",$bcc="",$cc="")
	{
		if(empty($type))
		{
			$type="html";
		}
		if($type=="plain")
		{
			$body = strip_tags($body);
		}
		if($type=="html")
		{
			$body = "<font face='Verdana, Arial, Helvetica, sans-serif'>".$body."</font>";
		}
		
		global $error;
		$mail = new PHPMailer();  // create a new object
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true;  // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail

		$mail->Host = 'smtp.mailgun.org';
		$mail->Port = 465; 
		
		$mail->IsHTML(true); 
		

		$mail->Username = '';  
		$mail->Password = '';           
		$mail->SetFrom($from, $from_name);
		$mail->Subject = $subject;
		$mail->Body = $body;
		$mail->AddAddress($to);
		if(!empty($bcc))
		{
			$mail->addBcc($bcc);
		}
		if(!$mail->Send()) {
			$error = 'Mail error: '.$mail->ErrorInfo; 
			return $error;
		} else {
			$error = 'Message sent!';
			return $error;
		}
		
	}


//////////////////////FILE UPLOAD/////////////////////////////
function uploadFile($file_id, $folder="", $types="",$rename="") {

	$file_title = $_FILES[$file_id]['name'];
	$file_tmp = $_FILES[$file_id]['tmp_name'];
	
    if(!$file_title) return array('','No file specified');
    //Get file extension
    $ext_arr = explode(".",basename($file_title));
    $ext = strtolower($ext_arr[count($ext_arr)-1]); //Get the last extension
	
   if(!empty($types))
   { 
     $all_types = explode(",",strtolower($types));
		if($types) 
		{
			if(in_array($ext,$all_types));
			else {
				$result = "'".$file_title."' is not a valid file."; //Show error if any.
				return array('',$result);
			}
		}
	}

    //Not really uniqe - but for all practical reasons, it is
	if(!empty($rename))
	{
	 	$file_name=$rename.'.'.$ext; 
	}
	else
	{
		$uniqer = substr(md5(uniqid(rand(),1)),0,5);
		$file_name = $uniqer . '_' . date('YmdHis').'.'.$ext;//Get Unique Name
	}
    //Where the file must be uploaded to
    if($folder) $folder .= '/';//Add a '/' at the end of the folder
    $uploadfile = $folder . $file_name;

    $result = '';
    //Move the file from the stored location to the new location
    if (!move_uploaded_file($file_tmp, $uploadfile)) {
        $result = "Cannot upload the file '".$file_title."'"; //Show error if any.
        if(!file_exists($folder)) {
            $result .= " : Folder don't exist.";
        } elseif(!is_writable($folder)) {
            $result .= " : Folder not writable.";
        } 
        $file_name = '';
        
    } else {
        if(!$_FILES[$file_id]['size']) { //Check if the file is made
            @unlink($uploadfile);//Delete the Empty file
            $file_name = '';
            $result = "Empty file found - please use a valid file."; //Show the error message
        } else {
            chmod($uploadfile,0777);//Make it universally writable.
        }
    }

    return array($file_name,$result);
}

function uploadMultyFile($name,$tmp_name,$size, $folder="", $types="",$rename="") {

	$file_title = $name;
	$file_tmp = $tmp_name;
	
    if(!$file_title) return array('','No file specified');
    //Get file extension
    $ext_arr = split("\.",basename($file_title));
    $ext = strtolower($ext_arr[count($ext_arr)-1]); //Get the last extension
	
   if(!empty($types))
   { 
     $all_types = explode(",",strtolower($types));
		if($types) 
		{
			if(in_array($ext,$all_types));
			else {
				$result = "'".$file_title."' is not a valid file."; //Show error if any.
				return array('',$result);
			}
		}
	}

    //Not really unique - but for all practical reasons, it is
	if(!empty($rename))
	{
	 	$file_name=$rename.'.'.$ext; 
	}
	else
	{
		$uniqer = substr(md5(uniqid(rand(),1)),0,5);
		$file_name = $uniqer . '_' . date('YmdHis').'.'.$ext;//Get Unique Name
	}
    //Where the file must be uploaded to
    if($folder) $folder .= '/';//Add a '/' at the end of the folder
    $uploadfile = $folder . $file_name;

    $result = '';
    //Move the file from the stored location to the new location
    if (!move_uploaded_file($file_tmp, $uploadfile)) {
        $result = "Cannot upload the file '".$file_title."'"; //Show error if any.
        if(!file_exists($folder)) {
            $result .= " : Folder don't exist.";
        } elseif(!is_writable($folder)) {
            $result .= " : Folder not writable.";
        } 
        $file_name = '';
        
    } else {
        if(!$size) { //Check if the file is made
            @unlink($uploadfile);//Delete the Empty file
            $file_name = '';
            $result = "Empty file found - please use a valid file."; //Show the error message
        } else {
            chmod($uploadfile,0777);//Make it universally writable.
        }
    }

    return array($file_name,$result);
}
//////////////////////FILE UPLOAD/////////////////////////////

	
function get_page_name($path='')
{
	$page_path = ($path != "") ? $path : $_SERVER['HTTP_REFERER']; 
	$url_parts = parse_url($page_path);
	$tmp_path = explode("/",$url_parts['path']); //pre($tmp_path);
	$page_name = array_pop($tmp_path);
	$page_name = !empty($page_name) ? $page_name : "index.php";
	$page_name .= ($url_parts['query'] != "") ? "?".$url_parts['query'] : "";
	$page_name .= ($url_parts['fragment'] != "") ? "#".$url_parts['fragment'] : "";
	return $page_name;
}
 

function curPageURL() {
	 $pageURL = 'http';
	 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
}

	function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
	}
	
	function randCode($limit)
	{
		$rand=rand();
		$rand1=md5($rand);
		$pass = substr($rand1, 0, $limit);
		return $pass;
	}
	
	function encryptPass($strPass){
		$strPass=trim($strPass);
		$basePass=base64_encode($strPass);
		$revPass=strrev($basePass);
		$first4=$this->randCode(4);
		$last4=$this->randCode(4);
		$enc_revPass=$first4.$revPass.$last4;
		return $enc_revPass;
	}
	function retrievePass($enc_revPass){
		$pass=substr($enc_revPass,4);
		$last4=substr($pass,-4,4);
		$pass1=str_replace($last4,"",$pass);
		$revPass=strrev($pass1);
		$oriPass=base64_decode($revPass);
		return $oriPass;
	}
	
	
	
	
	function getAllUserSelected($selval)
	{	
		$str.="<option value=''>Select Member</option>";
		$res=parent::selectData(TABLE_USER,"","1","");
		while($row=mysqli_fetch_array($res))
		{
			$str.="<option value='".$row['u_login_id']."'";
			if($row['u_login_id']==$selval)
			{
				$str.=' selected';
			}			
			$str.=">".$row['user_first_name'].' '.$row['user_last_name']."</option>";
		}
		return $str;
		
	}
	
	function getAllOrderUserSelected($selval)
	{	
		$str.="<option value=''>Select Member</option>";
		$sqlO = parent::selectData(TABLE_ORDER,"user_id","order_pstatus='p'","","","user_id");
		while($resO = mysqli_fetch_array($sqlO))
		{
			$res=parent::selectData(TABLE_USER,"","u_login_id='".$resO['user_id']."'","");
			while($row=mysqli_fetch_array($res))
			{
				$str.="<option value='".$row['u_login_id']."'";
				if($row['u_login_id']==$selval)
				{
					$str.=' selected';
				}			
				$str.=">".$row['user_first_name'].' '.$row['user_last_name']."</option>";
			}
		}	
		return $str;
		
	}
	
	function getUserSelected($selval)
	{	
		$res=parent::selectData(TABLE_USER,"","user_id<>0 and user_status='Active'","");
		while($row=mysqli_fetch_array($res))
		{
			$str.="<option value='".$row['u_login_id']."'";
			if($row['u_login_id']==$selval)
			{
				$str.=' selected';
			}
			$str.=">".$row['user_first_name'].' '.$row['user_last_name']."</option>";
		}
		return $str;
		
	}
	
	  
	 
	function build_Nlevel_tree_dropdown($tableAttr,$selval=0,$oldID=0,$depth=0,$ids=0,$opttag=0)
	{
		if(!is_array($tableAttr))
		{
			return false;
		}
		else
		{
			$table=$tableAttr[0];
			if(defined($table))
				$table=constant($table);
			else
				$table=$table;
			$parent_id=$tableAttr[1];
			$child_id=$tableAttr[2];
			$child_value=$tableAttr[3];
			$cond=$tableAttr[4];
		}
		$exclude=array();
		
		$child_query = parent::selectData($table,"",$parent_id."=".$oldID." ".$cond);
		while ( $child = mysqli_fetch_array($child_query) )
		{
			if($child[$child_id]!=$child[$parent_id])
			{
				$space ="";
				if($depth>$this->dep_lavel)
				{
					$this->dep_lavel=$depth;
				}
				for ( $c=0;$c<$depth;$c++ )
				{ 
					$space.= "--"; 
				}
				
				$selected="";
				if($selval==$child[$child_id]) $selected='selected';
				if(!$ids){
					if($child[$parent_id]==0 && $opttag==1){
						$tempTree.= "<optgroup label='".$child[$child_value]."'>";
						}else{
						$tempTree.= "<option value='".$child[$child_id]."' ".$selected.">".$space.$child[$child_value] . "</option>";
						}
					}else{
					$tempTree.= ",".$child[$child_id];
					}
				$depth++; 
				$tempTree.= $this->build_Nlevel_tree_dropdown($tableAttr,$selval,$child[$child_id],$depth,$ids,$opttag); 
				if($child[$parent_id]==0 && $opttag==1){
				$tempTree.="</optgroup>";
				}
				$depth--; 
				array_push($exclude, $child[$child_id]);
			}
		}
		return $tempTree;
	}
 
	function putMetaTags()
	{
		//$res=parent::selectData(TABLE_CONTENT,"","content_id='".$id."'",1);
		$settings = parent::selectData(TABLE_SETTINGS,"","set_id='1'",1);
		
		$meta_title=$settings['set_meta_title'];
		$meta_description=$settings['set_meta_description'];
		$meta_keywords=$settings['set_meta_keywords'];
		$meta_summary=$settings['set_meta_summary'];
		$meta_author=$settings['set_meta_author'];
		
		
		$meta_content .= '<title>'.$meta_title.'</title>';
		$meta_content  .= '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />';	
		$meta_content .= '<meta name="DESCRIPTION" content="'.$meta_description.'" />';
		$meta_content .= '<meta name="summary" content="'.$meta_summary.'" />';
		$meta_content .= '<meta name="Author" content="'.$meta_author.'">';
		$meta_content .= '<meta name="KEYWORDS" content="'.$meta_keywords.'" />';
		return $meta_content;
	}


	function putContent($id)
	{
		$res=parent::selectData(TABLE_CONTENT,"content_descr","content_id='".$this->filter_mysql($id)."'",1);
		$content=$res['content_descr'];
		return html_entity_decode($content);
	}
	
	function putContentHeader($id){
			$res=parent::selectData(TABLE_CONTENT,"content_header","content_id='".$this->filter_mysql($id)."'",1);
			$header=$res['content_header'];
			return($header);
	}
	
	function putContentTitle($id){
			$res=parent::selectData(TABLE_CONTENT,"content_title","content_id='".$this->filter_mysql($id)."'",1);
			$header=$res['content_title'];
			return($header);
	}
	
		
	function putPageSmallDesc($id)
	{
		$res=parent::selectData(TABLE_CONTENT,"content_small_descr","content_id='".$this->filter_mysql($id)."'",1);
		$smalldesc = nl2br(html_entity_decode($res['content_small_descr']));
		return ($smalldesc);
	}
	
	
	
	function putDefaultMetaTags($id=1)
	{
	
		$res=parent::selectData(TABLE_DEFAULT_META,"","def_meta_id='".$this->filter_mysql($id)."'",1);
		$meta_title=$res['def_meta_title'];
		$meta_description=$res['def_meta_description'];
		$meta_keywords=$res['def_meta_keywords'];
		$meta_summary=$res['def_meta_summary'];
		$meta_author=$res['def_meta_author'];
		
		$meta_content  = '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />';	
		$meta_content .= '<meta name="DESCRIPTION" content="'.$meta_description.'" />';
		$meta_content .= '<meta name="summary" content="'.$meta_summary.'" />';
		$meta_content .= '<meta name="Author" content="'.$meta_author.'">';
		$meta_content .= '<meta name="KEYWORDS" content="'.$meta_keywords.'" />';
		$meta_content .= '<title>'.$meta_title.'</title>';
		return $meta_content;
	}
	
	

	function getOnlyText($content,$length=100){
		 return(substr(strip_tags(preg_replace("/<.*?>/", "",html_entity_decode($content))),0,$length)."");
	}

	function ShowMessageFront($message_var='message',$message_class='MsgClass',$unset=1){
		if($_SESSION[$message_var]){
			echo '<label class="'.$_SESSION[$message_class].'">'.$_SESSION[$message_var].'</label>';
			if($unset == 1){
			$_SESSION[$message_var]=""; 
			$_SESSION[$message_class]="";
			}
		}
	}

	
	function nLevelPageSelected($selected_id,$current_id,$parent_id=0,$space=""){
	
		$res = parent::selectData(TABLE_CONTENT,"","content_status<>'Deleted' and `parent_id`='".$this->filter_mysql($parent_id)."'");
		while($row = mysqli_fetch_array($res)){ 
			if($current_id != $row['content_id']){
		?>
			<option value="<?= $row['content_id'];?>" <?= $selected_id==$row['content_id']?'selected="selected"':'';?>><?= $space.$row['content_title'];?></option>
		<?php } 
		
			$this->nLevelPageSelected($selected_id,$current_id,$row['content_id'],$space .= "--");
			$len = strlen($space);
			$space = substr($space,0,$len-2);
		}
		
	
	}

	
	function getUserName($user_id)
	{
		$user_id  = $this->filter_mysql($this->filter_numeric($user_id));
		if($user_id == 0){
			return('Admin');
		}
		$res = parent::selectData(TABLE_USER,""," u_login_id='".$user_id."'",1);
		return($res['user_first_name']." ".$res['user_last_name']);
	}
	
	function getUserCity($user_id)
	{
		if($user_id == 0){
			return('Admin');
		}
		$res = parent::selectData(TABLE_USER,"","user_status='Active' and u_login_id='".$this->filter_mysql($user_id)."'",1);
		return($res['user_city']);
	}
	
	
	function getPackageTitle($packId){
		$res = parent::selectData(TABLE_PACKAGE,"pack_title","pack_status='Active' and pack_id='".$this->filter_mysql($packId)."'",1);
		return($res['pack_title']);
	}
	
	function selectProduct($proId)
	{
		$lists=parent::selectData(TABLE_PRODUCT,"","pro_status='A'");
		$ret = '';
		while($res = mysqli_fetch_array($lists))
		{
			$ret .= '<option value="'.$res['pro_id'].'" '.($res['pro_id'] == $proId?'selected="selected"':'').'>'.$res['pro_title'].'</option>';
		}
		return($ret);
	}
	
	function getUserAvatar($user_id)
	{
		$res = parent::selectData(TABLE_USER,"user_avatar","user_status='Active' and u_login_id='".$this->filter_mysql($user_id)."'",1);
		return($res['user_avatar']==""?"no_avatar.png":$res['user_avatar']);
	}

	function getImageThumb($fol=IMAGES,$pic='',$title='',$class='',$width='50',$height='50',$path='')
	{
		if(is_file($path.$fol.$pic))
		{
			$img_string = '<img src="'.$path.'thumb/phpThumb.php?src=../'.$fol.$pic.'&amp;hp='.$height.'&amp;wl='.$width.'" alt="'.$title.'" class="'.$class.'" border="0">';
		}
		else
		{
			//$fol=IMAGES;
			$pic=NOT_FOUND_IMG;
			$img_string = '<img src="'.$path.'thumb/phpThumb.php?src=../'.$pic.'&amp;hp='.$height.'&amp;wl='.$width.'" alt="'.$title.'" class="'.$class.'" border="0">';
		}
		return $img_string;
	}
	
	function getImageThumbUrl($fol=IMAGES,$pic='',$title='',$class='',$width='50',$height='50',$path='')
	{
		if(is_file($path.$fol.$pic))
		{
			$img_string = '<img src="'.FURL.$path.'thumb/phpThumb.php?src=../'.$fol.$pic.'&amp;hp='.$height.'&amp;wl='.$width.'" alt="'.$title.'" class="'.$class.'" border="0">';
		}
		else
		{
			//$fol=IMAGES;
			$pic=NOT_FOUND_IMG;
			$img_string = '<img src="'.FURL.$path.'thumb/phpThumb.php?src=../'.$pic.'&amp;hp='.$height.'&amp;wl='.$width.'" alt="'.$title.'" class="'.$class.'" border="0">';
		}
		return $img_string;
	}
	
	
	function getPageName($content_id){
	$res = parent::selectData(TABLE_CONTENT,"content_title","content_id='".$this->filter_mysql($content_id)."' and content_status='Active'",1);
	return $res['content_title'];
	}

	function check_email_address($email) 
	{
		  if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
			return false;
		  }
		
		  $email_array = explode("@", $email);
		  $local_array = explode(".", $email_array[0]);
		  for ($i = 0; $i < sizeof($local_array); $i++) 
		  {
			if(!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",$local_array[$i]))
			{
			  return false;
			}
  }

  if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
    $domain_array = explode(".", $email_array[1]);
    if (sizeof($domain_array) < 2) 
	{
        return false; 
    }
    for ($i = 0; $i < sizeof($domain_array); $i++) 
	{
      if(!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$",$domain_array[$i])) 
	  {
        return false;
      }
    }
  }
  return true;
}

function getUserAddress($user_id){
	$res = parent::selectData(TABLE_USER,"","u_login_id='".$this->filter_mysql($user_id)."' and user_status='Active'",1);
	$ret = 'Address: '.$res['user_address'];
	$ret .= '<br>';
	$ret .= '<strong>State :</strong> '.$res['user_state']."   ";
	$ret .= '<strong>City :</strong> '.$res['user_city'];
	$ret .= '<br>';
	$ret .= '<strong>Zip Code:</strong> '.$res['user_zip'];
	return $ret;
}

function createCVLink($user_id){
	$res = parent::selectData(TABLE_RESUME,"","user_id='".$this->filter_mysql($user_id)."' and res_status<>'Deleted'",1);
	if($res){
		return true;
	} else {
		return false;
	}
}
function add_message($msgvar,$message)
	{
		$_SESSION[$msgvar] .= $message."<br>";
	}
	
	function get_message($msgvar)
	{
		return $_SESSION[$msgvar];
	}
	
	function remove_message($msgvar)
	{
		$_SESSION[$msgvar] = "";
		$_SESSION['messageClass'] = "";		
		
	}
	function display_message($msgvar)
	{
		$message = '';
		if($this->get_message($msgvar))
		{
			if($_SESSION['messageClass']=='successClass')
			{
			$message = '<div class="alert alert-block alert-success">
              <button type="button" class="close" data-dismiss="alert"> <i class="ace-icon fa fa-times"></i> </button>
              <i class="ace-icon fa fa-check green"></i> '.$this->get_message($msgvar).'</div>';
			}
			elseif($_SESSION['messageClass']=='infoClass')
			{
			$message = '<div class="alert alert-block alert-info">
              <i class="fa fa-comment"></i> <button type="button" class="close" data-dismiss="alert"> <i class="ace-icon fa fa-times"></i> </button>
              '.$this->get_message($msgvar).'</div>';
			}
			else
			{
				$message = '<div class="alert alert-block alert-danger">
              <button type="button" class="close" data-dismiss="alert"> <i class="ace-icon fa fa-times"></i> </button>
              '.$this->get_message($msgvar).'</div>';
			}
		}
		$this->remove_message($msgvar);
		return $message;
	}
	
	function getFieldValueByFieldName($table,$field_get,$field_name,$field_val){
		$data=parent::selectData($table,$field_get,"$field_name='".$this->filter_mysql($field_val)."'",1);
		return($data[$field_get]);
	}
	





function dateFormat($date){
	return(date('jS M, Y',strtotime($date)));
}
function dateFormatDb($date){
	$dt = explode("/",$date);
	$newDate = $dt[2]."-".$dt[0]."-".$dt[1];
	return $newDate;
}

function getYoutubeId($link)
{
	preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $link, $matches);
	if($matches[0]!='') { return $matches[0]; }
	else {
		preg_match('#(\.be/|/embed/|/v/|/watch\?v=)([A-Za-z0-9_-]{5,11})#', $link, $matches);
		if(isset($matches[2]) && $matches[2] != ''){
			 $YoutubeCode = $matches[2];
		}
		return  $YoutubeCode;
	}
}
	
function getYoutubeVideo($url,$width="",$height=""){
	$v_id = $this->getYoutubeId($url);
	$embed = '<object width="'.$width.'" height="'.$height.'" data="http://youtube.com/v/'.$v_id.'" type="application/x-shockwave-flash"><param name="src" value="http://youtube.com/v/'.$v_id.'" /></object>';
	return($embed);
}
 
 
 
  function getVimeoVideoThumbnailByVideoId( $id = '', $thumbType = 'medium' ) {
        $id = trim( $id );
        if ( $id == '' ) {
            return FALSE;
        }
        $apiData = unserialize( file_get_contents( "http://vimeo.com/api/v2/video/$id.php" ) );
        if ( is_array( $apiData ) && count( $apiData ) > 0 ) {
            $videoInfo = $apiData[ 0 ];
            switch ( $thumbType ) {
                case 'small':
                    return $videoInfo[ 'thumbnail_small' ];
                    break;
                case 'large':
                    return $videoInfo[ 'thumbnail_large' ];
                    break;
                case 'medium':
                    return $videoInfo[ 'thumbnail_medium' ];
                default:
                    break;
            }
        }
        return FALSE;
		 
		
    }
    
	
	function getOrderStatusSelect($select){
		$status=parent::selectData(TABLE_ORDER_STATUS,"","1");
		while($st = mysqli_fetch_array($status)){ ?>
			<option value="<?= $st['status_name'];?>" <?=$select == $st['status_name']?'selected="selected"':'';?>><?= $st['status_name'];?></option>
	<?php }
		
	}
	
	function getOrderUserInfo($order_id,$user_id){
		$user=parent::selectData(TABLE_USER,"","u_login_id='".$this->filter_mysql($user_id)."'",1);
		$order=parent::selectData(TABLE_ORDER,"","order_id='".$this->filter_mysql($order_id)."'",1);
		
		$ret = '<strong>Email</strong> '.$user['user_name'].'<br>';
		$ret .= '<strong>Shipping Adress</strong> <br>';
		$ret .= 'Name: '.$order['order_ship_fname'].'&nbsp;'.$order['order_ship_lname'].' <br>';
		$ret .= 'Address: '.$order['order_ship_address1'].'<br>';
		$ret .= $order['order_ship_address2'].'<br>';
		$ret .= 'Phone: '.$order['order_ship_phone'];
		return($ret);
	}
	
	
	function validateRelIds($ids){
		return(trim(preg_replace("/,+/", ",", $ids),","));
	}
	
	
		
	function showEditLink($user_id,$id,$goto){
		if($_SESSION['user']['user_id'] == $user_id){
			// show edit link //
			$ret = '<a href="'.$goto.'?id='.base64_encode($id).'" class="edit_comment">Edit</a>';
		}
		return $ret;
	}
	
	
	
	function isEmail($string)
	{
		$email=trim($string);
		if(!preg_match ("/^[\w\.-]{1,}\@([\da-zA-Z-]{1,}\.){1,}[\da-zA-Z-]+$/", $email))
		{
			return false;
		}
		return true;	
	}
	
	function isURL($url)
	{ 
		if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
			return true;	
		} else {
			return false;
		}
	}


	
	function stateSelect($state_id)
	{
		//$str.= "<option value=''>--Select State--</option>";
		$state_id  = $this->filter_mysql($this->filter_numeric($state_id));
		$res=parent::selectData(TABLE_STATE,"","state_status='Active'","");
		while($row=mysqli_fetch_array($res))
		{
			$str.="<option value='".$row['state_id']."'";
			if($row['state_id']==$state_id)
			{
				$str.=' selected';
			}
			$str.=">".$row['state_name']."</option>";
		}
		return $str;
		
	}
	
	
	function stateCodeSelect($state_id)
	{
		$state_id  = $this->filter_mysql($state_id);
		$str.= "<option value=''>--Select State--</option>";
		$res=parent::selectData(TABLE_STATE,"","state_status='Active'","");
		while($row=mysqli_fetch_array($res))
		{
			$str.="<option value='".$row['state_code']."'";
			if($row['state_code']==$state_id)
			{
				$str.=' selected';
			}
			$str.=">".$row['state_name']."</option>";
		}
		return $str;
		
	}
	
	

	function citySelect($state_id,$city)
	{
		$state_id  = $this->filter_mysql($this->filter_numeric($state_id));
		$city  	   = $this->filter_mysql($city);
		
		$state_code = $this->getStateCode($state_id); 
		$sql_city = parent::selectData(TABLE_ZIPS," distinct(city) "," lower(state)='".strtolower($state_code)."'","","city ASC");
		$str = " <option value='0'>Select City</option>";		
		while($row_city=mysqli_fetch_array($sql_city))
		 {
		 	$str .="<option value='".ucwords(strtolower($row_city['city']))."'";
			if(trim(strtolower($row_city['city']))==trim(strtolower($city)))
			{
				$str .=' selected';
			}
			$str .=">".ucwords(strtolower($row_city['city']))."</option>";	 
		 }
		 echo $str;
		
	}

	
	function getState($selval)
	{
			$selval  = $this->filter_mysql($this->filter_numeric($selval));
			$resS=parent::selectData(TABLE_STATE,"","state_status='Active' and state_id='".$selval."'",1);
			if($resS['state_name'])  return $resS['state_name'];
			else return $selval;
		
	}
	
	function getStateCode($selval)
	{
		$selval  = $this->filter_mysql($this->filter_numeric($selval));
		$resS=parent::selectData(TABLE_STATE,"","state_status='Active' and state_id='".$selval."'",1);
		return $resS['state_code'];
	}
	
	function getStateIdFromCode($selval)
	{
		$selval  = $this->filter_mysql($selval);
		$resS=parent::selectData(TABLE_STATE,"","state_status='Active' and lower(state_code)='".strtolower($selval)."'",1);
		return $resS['state_id'];
	}
	
	
	
	function countrySelect($selval)
	{
		$selval  = $this->filter_mysql($this->filter_numeric($selval));
		
		$str.= "<option value=''>--Select Country--</option>";
		$res=parent::selectData(TABLE_COUNTRY,"","country_status='Active'","");
		while($row=mysqli_fetch_array($res))
		{
			$str.="<option value='".$row['country_id']."'";
			if($row['country_id']==$selval)
			{
				$str.=' selected';
			}
			$str.=">".$row['country_name']."</option>";
		}
		return $str;
		
	}
	
	 
	
	function getCountry($selval)
	{
		$selval  = $this->filter_mysql($this->filter_numeric($selval));
		$resS=parent::selectData(TABLE_COUNTRY,"","country_status='Active' and country_id='".$selval."'",1);
		if($resS['country_name'])  return $resS['country_name'];
		else return $selval;
		
	}
	
	function getCountryCode($selval)
	{
		$selval  = $this->filter_mysql($this->filter_numeric($selval));
		$resS=parent::selectData(TABLE_COUNTRY,"","country_status='Active' and country_id='".$selval."'",1);
		return $resS['country_code'];
	}
	

	
	function hasStates($con)
	{
		$con  = $this->filter_mysql($this->filter_numeric($con));
		$res=parent::selectData(TABLE_STATE,"","state_status='Active' and country_id='".$con."'");
		$num = mysqli_num_rows($res);
		if($num>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}	
	

	
	function get_tot_ad_directory_listing_user($userId)
	{ 		 
		$userId  	= $this->filter_mysql($this->filter_numeric($userId));
		$userR = parent::selectData(TABLE_AD_DIRECTORY_LISTING,"count(user_id) as tot","user_id='".$userId."' and adl_status<>'Deleted'",1);
		if($userR['tot']!="") return $userR['tot'];
		else return "0";
	}
	 
	
	function get_tot_login_ad_listing_user($userId)
	{ 		 
		$userId  	= $this->filter_mysql($this->filter_numeric($userId));
		
		$userR = parent::selectData(TABLE_AD_LISTING,"count(user_id) as tot","user_id='".$userId."' and logad_status<>'Deleted'",1);
		if($userR['tot']!="") return $userR['tot'];
		else return "0";
	}	
	
	function get_tot_minter_ads_impression_used($userId,$adl_id)
	{ 	
		$userId  	= $this->filter_mysql($this->filter_numeric($userId));
		$adl_id  	= $this->filter_mysql($this->filter_numeric($adl_id));

		if(!empty($adl_id)) { 	
			$extraw .= "and adl_id='".$adl_id."'"; 
		}
		$lists=parent::selectData(TABLE_ADMINTER_IMPRESSION,"","admi_status='Active' and user_id='".$userId."'".$extraw." ","");		
		
		while($data=mysqli_fetch_array($lists)) {
			if($data['admi_add_type']=='A'){					
				$Anewcart += $data['admi_impression'];	
			} else if($data['admi_add_type']=='D'){
				$Dnewcart += $data['admi_impression'];				
			}				
		}
		  $newcart = $Anewcart-$Dnewcart;
		 if($newcart!="") return $newcart;
		 else return "0";
	} 

	
	function get_tot_login_ads_impression_used($userId,$logad_id)
	{ 
		$userId  	= $this->filter_mysql($this->filter_numeric($userId));
		$logad_id  	= $this->filter_mysql($this->filter_numeric($logad_id));
		
		/* 
		if(!empty($logad_id)) { 	
			$extraw .= "and logad_id='".$logad_id."'"; 
		}
		$lists=parent::selectData(TABLE_ADLOGIN_IMPRESSION,"","adimp_status='Active' and user_id='".$userId."'".$extraw." ","");	
		while($data=mysqli_fetch_array($lists)) {
			if($data['adimp_add_type']=='A'){					
				$Anewcart += $data['adimp_impression'];	
			} else if($data['adimp_add_type']=='D'){
				$Dnewcart += $data['adimp_impression'];				
			}				
		}
		*/
		
		$lists=parent::selectData(TABLE_AD_LISTING,"","user_id='".$userId."'","");	
		while($data=mysqli_fetch_array($lists))
			{			 
				$dataVAl += $data['logad_impression'];				
			}
			
		$lists2=parent::selectData(TABLE_AD_LISTING,"","user_id='".$userId."' and logad_status='Deleted'","");	
		while($data2=mysqli_fetch_array($lists2))
			{			 
				$dataVAl -= $data2['logad_impression']-$data2['logad_view_imp'];				
			}				
		 
				 
		 if($dataVAl!="") return $dataVAl;
		 else return "0";
	} 	
	
	 
	 
	
	
	function get_tot_alloted_banner_ads_impression($userId,$pack_id,$banad_type_size)
	{ 	 		
		$userId  	= $this->filter_mysql($this->filter_numeric($userId));
		$pack_id    = $this->filter_mysql($this->filter_numeric($pack_id));
		$banad_type_size    = $this->filter_mysql($banad_type_size);
	
		$lists=parent::selectData(TABLE_BANNER_IMPRESSION,"","banimp_status='Active' and banimp_type_size='".$banad_type_size."' and pack_id='".$pack_id."' and user_id='".$userId."'","");
		while($data=mysqli_fetch_array($lists)) {
			if($data['banimp_type_size']=='square' && $data['banimp_add_type']=='A'){				
				$Anewcart[0] += $data['banimp_impression'];
			} else if($data['banimp_type_size']=='horizontal' && $data['banimp_add_type']=='A'){
				$Anewcart[1] += $data['banimp_impression'];
			}

			if($data['banimp_type_size']=='square' && $data['banimp_add_type']=='D'){				
				$Dnewcart[0] += $data['banimp_impression'];
			} else if($data['banimp_type_size']=='horizontal' && $data['banimp_add_type']=='D'){
				$Dnewcart[1] += $data['banimp_impression'];
			}			
		}
		 $newcart[0] = 	$Anewcart[0]-$Dnewcart[0];
		 $newcart[1] = 	$Anewcart[1]-$Dnewcart[1];
			
		 if($newcart!="") return $newcart;
		 else return "0";
	} 
	
	
	function get_tot_banner_ads_impression_used($userId,$banad_id)
	{ 	 
		$userId    = $this->filter_mysql($this->filter_numeric($userId));
		$banad_id  = $this->filter_mysql($this->filter_numeric($banad_id));
		
		if(!empty($banad_id)) 
		{ 	
			$extraw .= "and banad_id='".$banad_id."'"; 
		}
		$lists=parent::selectData(TABLE_BANNER_IMPRESSION,"","banimp_status='Active' and user_id='".$userId."' ".$extraw." ","");
		
		 
			while($data=mysqli_fetch_array($lists)) {
			if($data['banimp_type_size']=='square' && $data['banimp_add_type']=='A'){				
				if(!empty($data['banimp_geo_impression'])) $Anewcart[0] += $data['banimp_geo_impression'];
				else $Anewcart[0] += $data['banimp_impression'];
			} else if($data['banimp_type_size']=='horizontal' && $data['banimp_add_type']=='A'){
				if(!empty($data['banimp_geo_impression'])) $Anewcart[1] += $data['banimp_geo_impression'];
				else $Anewcart[1] += $data['banimp_impression'];
			}

			if($data['banimp_type_size']=='square' && $data['banimp_add_type']=='D'){				
				if(!empty($data['banimp_geo_impression'])) $Dnewcart[0] += $data['banimp_geo_impression'];
				else $Dnewcart[0] += $data['banimp_impression'];
			} else if($data['banimp_type_size']=='horizontal' && $data['banimp_add_type']=='D'){
				if(!empty($data['banimp_geo_impression'])) $Dnewcart[1] += $data['banimp_geo_impression'];
				else $Dnewcart[1] += $data['banimp_impression'];
			}
			
		}
		 $newcart[0] = 	$Anewcart[0]-$Dnewcart[0];
		 $newcart[1] = 	$Anewcart[1]-$Dnewcart[1];
		  
		 if($newcart!="") return $newcart;
		 else return "0";
	} 	
	
	function get_tot_banner_ads_user($userId)
	{ 	 
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		
		$useRsquare = parent::selectData(TABLE_BANNER_ADS,"count(user_id) as stot","banad_status <>'Deleted' and banad_type_size='square' and user_id='".$userId."'",1);
		$useHorizon = parent::selectData(TABLE_BANNER_ADS,"count(user_id) as htot","banad_status <>'Deleted' and banad_type_size='horizontal' and user_id='".$userId."'",1);
		$newcart[0]=$useRsquare['stot'];
		$newcart[1]=$useHorizon['htot'];	
		/* 
	 	$useHorizon = parent::selectData(TABLE_BANNER_ADS,"count(user_id) as tot","user_id='".$userId."'",1);
		$newcart=$useHorizon['tot'];
		*/ 
		if($newcart!="") return $newcart;
		else return "0";
		
		
		
		 
	}
	
	/* 
	function get_tot_ads_permission_user($userId,$packId)
	{ 
		if(!empty($packId)) { 	
			$extraw .= "and pack_id='".$packId."'"; 
		}
		$lists=parent::selectData(TABLE_ORDER_DETAILS,"","od_status='Active' and user_id='".$userId."'".$extraw." ","");		
		
		while($data=mysqli_fetch_array($lists)) {			 
				$newcart += $data['pack_quan'];			 
		}
		 
		 if($newcart!="") return $newcart;
		 else return "0";
		 
	}
	*/
	
	
	 
		
	function VideoCategorySelected($cId)
	{
			$cId  = $this->filter_mysql($this->filter_numeric($cId));
			$lists=parent::selectData(TABLE_VIDEO_CATEGORY,"","vcat_status='Active'","");	
			
			while($res = mysqli_fetch_array($lists))
			{
				$ret  .= '<option value="'.$res['vcat_id'].'" '.($res['vcat_id'] == $cId?'selected="selected"':'').'>'.$res['vcat_title'].'</option>';
			}
			return($ret);
	}	
	
	  
	
	function getVideoCat($selval)
	{
		$selval  = $this->filter_mysql($this->filter_numeric($selval));
		$resS=parent::selectData(TABLE_VIDEO_CATEGORY,"","vcat_status='Active' and vcat_id='".$selval."'",1);
		return $resS['vcat_title'];
	}
		
	
	function get_package_aff_distribution($selval)
	{
		$selval  = $this->filter_mysql($this->filter_numeric($selval));
		$resS=parent::selectData(TABLE_PACKAGE,"pack_sales_affliliate_distri","pack_status='Active' and pack_id='".$selval."'",1);
		return $resS['pack_sales_affliliate_distri'];
	}
	
	
	function get_pack_title($selval)
	{
		$selval  = $this->filter_mysql($this->filter_numeric($selval));
		$resS=parent::selectData(TABLE_PACKAGE,"","pack_status='Active' and pack_id='".$selval."'",1);
		return $resS['pack_title'];
	}
		
	 function get_package_selected($cId)
		{
			$cId  = $this->filter_mysql($this->filter_numeric($cId));
			$lists=parent::selectData(TABLE_PACKAGE,"","pack_status='Active'","");	
			
			while($res = mysqli_fetch_array($lists))
			{
				$ret  .= '<option value="'.$res['pack_id'].'" '.($res['pack_id'] == $cId?'selected="selected"':'').'>'.$res['pack_title'].'</option>';
			}
			return($ret);
		}


	function my_faq_title_list()
	{
		$fIds = "";	 		 
		$lists=parent::selectData(TABLE_FAQ,"","faq_status='Active'","");	
		$i=0;
		while($resR = mysqli_fetch_array($lists))
		{
			 $fIds .= "'".$resR['faq_quest_title']."'".",";
			// $fIds .= "'".html_entity_decode($resR['faq_quest_ans'])."'".",";
			 
		$i++; }
		
		$fIds = rtrim($fIds,",");
		return $fIds;
	 
	}
	
	function get_total_pack_purchased($userId,$pId)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		$pId  = $this->filter_mysql($this->filter_numeric($pId));
		$sqlS = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","sum(pack_quan) as sold","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id='".$pId."' and od.od_status='Active' and od.user_id='".$userId."'".$extraw."",1);
		if($sqlS['sold']) return $sqlS['sold'];
		else return "0";
		
		
	}
	
	
	function get_minter_advertising_vew($packId)
	{	
		$packId  = $this->filter_mysql($this->filter_numeric($packId));
		$totalMAV = 0;
		$sqlS = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","od.pack_quan as totalp","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id='".$packId."' and od.od_status='Active' and od.user_id='".$_SESSION['user']['u_login_id']."'");
		while($resS = mysqli_fetch_array($sqlS))
		{
			$packD = parent::selectData(TABLE_PACKAGE,"","pack_status='Active' and pack_id='".$packId."'",1);
			$totalMAV += $resS['totalp']*$packD['pack_ad_minter_adver_view'];
		}
	
		/*
			$lists=parent::selectData(TABLE_ORDER_DETAILS,"","od_status='Active' and pack_id ='".$packId."' and user_id='".$_SESSION['user']['u_login_id']."'","");		 
			while($data=mysqli_fetch_array($lists)) {
				$packD = parent::selectData(TABLE_PACKAGE,"","pack_status='Active' and pack_id='".$data['pack_id']."'",1);
				$totalMAV = $packD['pack_ad_minter_adver_view'];		 
			}
		*/
		 if($totalMAV!="") return $totalMAV;
		 else return "0";
		
	}
	
	function getUserSecondryPin($userId)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		$resS=parent::selectData(TABLE_USER_AUTH,"","user_id='".$userId."' and auth_status='Active'",1);
		return $resS['auth_user_pin'];	 
	}
	
	function getUserEmailFromAll($userId)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		$resS=parent::selectData(TABLE_USER_LOGIN,"","u_login_id='".$userId."'",1);
		return $resS['u_login_user_email'];	 
	}
	
	
	function getUserEmail($userId)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		$resS=parent::selectData(TABLE_USER_LOGIN,"","u_login_id='".$userId."' and u_login_status='Active'",1);
		return $resS['u_login_user_email'];	 
	}
	
	function get_tot_asimi_token($userId)
	{ 	 
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		$lists=parent::selectData(TABLE_USER_WALLET,"","wall_status='Active' and user_id='".$userId."'","");		 
		while($data=mysqli_fetch_array($lists)) {
			$subTotal += $data['wall_asimi'];
		}		 
		if($subTotal!="") return $subTotal;
		else return "0";

	} 
	 
	function get_asimi_curr_price()
	{

		$output = json_decode($result, true);
		$btc_usd_price = $output['lastPrice']*.01;*/
		
		/*$url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
		$parameters = [
		  'start' => '1',
		  'limit' => '1',
		  'convert' => 'USD'
		];
		
		$headers = [
		  'Accepts: application/json',
		  'X-CMC_PRO_API_KEY: 3349e417-d262-448b-9804-18b8aac0b0a1'
		];
		$qs = http_build_query($parameters); // query string encode the parameters
		$request = "{$url}?{$qs}"; // create the request URL
		
		
		$curl = curl_init(); // Get cURL resource
		// Set cURL options
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $request,            // set the request URL
		  CURLOPT_HTTPHEADER => $headers,     // set the headers 
		  CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
		));
		
		$response = curl_exec($curl); // Send the request, save the response		
		curl_close($curl); // Close request		
		$res = json_decode($response);		
		$btc_usd_price = trim($res->data[0]->quote->USD->price);*/
		
				
		/*$asimi_btc_price = 0;
		$result1 = file_get_contents("https://matcher.waves.exchange/matcher/orderbook/EbLVSrAi6vS3AkLwBinzZCvAXP2yYiFJEzj1MBVHcwZ5/8LQW8f7P5d5PZM7GtZEBgaqRPGSzS3DfPuiXrURJ4AJS/status");
		$output1 = json_decode($result1, true);
		$asimi_btc_price = $output1['lastPrice']*.00000001;*/


		//$arrAPI['ap_price'] = round($asimi_btc_price*$btc_usd_price ,8);

		//$arrAPI['ap_btc_price'] = round($asimi_btc_price ,8);
		
		$arrAPI['ap_price'] = 0;
		
		if(is_numeric($arrAPI['ap_price']) && $arrAPI['ap_price']>0) return $arrAPI['ap_price'];
		else
		{
			$arrSP = parent::selectData(TABLE_ASIMI_PRICE,"","ap_price>0",1,"ap_id desc");
			if(is_numeric($arrSP['ap_price']) && $arrSP['ap_price']>0) return $arrSP['ap_price'];
			else return 0;
		}
	}
	
	function get_asimi_ps_curr_price()
	{
		return "0.10";
	}	 
	
	function get_current_asimi_token($dollerVal)
	{ 	 
		$token = $this->get_asimi_curr_price();
		$asimTotalval = ($dollerVal / $token); 
		$asimTotal = 	(float)round($asimTotalval,8);		
		if($asimTotal!="") return $asimTotal;
		else return "0";

	} 
	
	function get_current_ps_asimi_token($dollerVal)
	{ 	 
		$token = $this->get_asimi_ps_curr_price();
		$asimTotalval = ($dollerVal / $token); 
		$asimTotal = 	number_format((float)$asimTotalval, 4, '.', '');		
		if($asimTotal!="") return $asimTotal;
		else return "0";

	} 
	 
	////////////////////////Not in use ////////////////
	function get_tot_asimi_token_balance($userId)
	{  
			$userId  = $this->filter_mysql($this->filter_numeric($userId));
			$ucredit = 0;
			$bcredit = 0;
			$sqlU = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_asimi","wall_status='Active' and ( wall_type='d' or wall_type='cr' or wall_type='pbr' or wall_type='me' or wall_type='rs' or wall_type='e' or wall_type='me' or wall_type='rs' or wall_type='j' or wall_type='r') and wall_pstatus='p' and user_id='".$userId."'",1);
			$ucredit = $sqlU['total_asimi'];
			
			 
						
			$uwsql = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_deduction","wall_status='Active' and (wall_type='w' or wall_type='mw' or wall_type='cd' or wall_type='pbt' or wall_type='p' or wall_type='vep') and wall_pstatus='p' and user_id='".$this->filter_mysql($userId)."'",1);
			$bcredit = $uwsql['total_deduction'];					
			 
			$balance = $ucredit-$bcredit;				
			return($balance);
	}	
	
	
	function get_all_paid_order_id($userId)
	{  
			$userId  = $this->filter_mysql($this->filter_numeric($userId));
			//get_advertising_block_packId	
			 $ordIds = "";
				$sqlU = parent::selectData(TABLE_ORDER,"","order_status='Active' and user_id='".$userId."' and order_pstatus='p' order by order_id asc");
				while($resU = mysqli_fetch_array($sqlU)) 
				{
					$ordIds .= $resU['order_id'].",";
				}
				$ordIds = rtrim($ordIds,",");
				if($ordIds=="")  $ordIds = 0;	
			  	 
			return($ordIds);
			 
	}	

function get_tot_package_purchased_by_pack_id_special($pId)
{
	$pId  = $this->filter_mysql($this->filter_numeric($pId));
	$sqlS = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","sum(pack_quan) as sold","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id='".$pId."' and od.od_status ='Active' and od.od_pack_status ='Active' and od.user_id='".$_SESSION['user']['u_login_id']."'".$extraw."",1);
	
	$tot_login_stake = $this->get_member_login_stake($_SESSION['user']['u_login_id']);		
	if($tot_login_stake == 10000) $tot_adminter_sold = $sqlS['sold']+1;
	else  $tot_adminter_sold = $sqlS['sold'];		
	/*$tot_adminter_sold = $sqlS['sold'];	*/			
	if($tot_adminter_sold) return $tot_adminter_sold;
	else return "0";
}
function get_tot_banner_ads_user_sp($userId)
{ 	 
	$userId  = $this->filter_mysql($this->filter_numeric($userId));
	
	$useRsquare = parent::selectData(TABLE_BANNER_ADS,"count(user_id) as stot","banad_status <>'Deleted' and banad_type_size='square' and user_id='".$userId."'",1);
	$useHorizon = parent::selectData(TABLE_BANNER_ADS,"count(user_id) as htot","banad_status <>'Deleted' and banad_type_size='horizontal' and user_id='".$userId."'",1);
	$newcart[0]=$useRsquare['stot'];
	$newcart[1]=$useHorizon['htot'];	
	/* 
 	$useHorizon = parent::selectData(TABLE_BANNER_ADS,"count(user_id) as tot","user_id='".$userId."'",1);
	$newcart=$useHorizon['tot'];
	*/ 
	if($newcart!="") return $newcart;
	else return "0"; 
}
 function createImageNameString($imageTitle, $extention, $id) {
    $arr_dash = array(" ", "", "_", "~", "!", "@", "#", "$", ":");
    $arr_strip = array("%", "^", "&", "*", "(", ")", "+", "=", ",", ".", "/", ";", "\"", "{", "[", "]", "}", "\\", "|", "'","?","<",">");
    $ImageNameString = strtolower(str_replace($arr_strip, "", str_replace($arr_dash, "-",$imageTitle)));
    $ImageNameString = str_replace("----", "-", $ImageNameString);
    $ImageNameString = str_replace("---", "-", $ImageNameString);
    $ImageNameString = str_replace("--", "-", $ImageNameString);
    $finalString = $ImageNameString . "-" . $id . "." . $extention;
    return $finalString;
  }
function get_tot_package_purchased_by_pack_id($pId)
	{
		$pId  = $this->filter_mysql($this->filter_numeric($pId));
		$today  = date("Y-m-d");
		if($pId =='4') 
		{ 				
			$extraw .= " and od.od_pack_val_days > od.od_pack_val_used";
		}
		else if($pId =='5')
		{
			$extraw .= " and od.od_pack_validity >= '".$today."'";
		}
		$sqlS = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","sum(pack_quan) as sold","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id='".$pId."' and od.od_status ='Active' and od.od_pack_status ='Active' and od.user_id='".$_SESSION['user']['u_login_id']."'".$extraw."",1);
		
		$tot_login_stake = $this->get_member_login_stake($_SESSION['user']['u_login_id']);		
		if($tot_login_stake == 10000) $tot_adminter_sold = $sqlS['sold']+1;
		else  $tot_adminter_sold = $sqlS['sold'];		
		/*$tot_adminter_sold = $sqlS['sold'];	*/			
		if($tot_adminter_sold) return $tot_adminter_sold;
		else return "0";
	}


///////////////////////////////////Minting Ads/////////////////////////////////////////////

    function memebr_max_minting_ads_to_view_today($userId)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		$tot_mint_ads_today = $this->get_tot_package_purchased_by_pack_id(4);
		
		$mintingArr = parent::selectData(TABLE_ADVER_PACK_MINING_BENEFIT,"","apmb_status='Active' and apmb_ad_blocks_owned <= '".$tot_mint_ads_today."' order by apmb_id desc",1); 
		 
		$mintingVal = $mintingArr['apmb_ad_view_req_max'];
		
		return $mintingVal;		
		
	}
	
	function memebr_max_booster_ads_to_view_today($userId)
	{	
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		$tot_boost_ads_today = $this->get_tot_package_purchased_by_pack_id(5);

		//$mintBoostArr = parent::selectData(TABLE_MINING_BOOSTER_PACK_BENEFIT,"","mbpb_status='Active' and mbpb_ad_block_owned <= '".$tot_boost_ads_today."' order by mbpb_id desc",1); 
		//$mintBoostVal = $mintBoostArr['mbpb_ad_view_req_max'];

		return $tot_boost_ads_today;			
	}

	function memebr_max_ads_to_view_today()
	{
		$max_mint_ads = 0;
		//$max_mint_ads 		= $this->memebr_max_minting_ads_to_view_today(4);
		$max_booster_ads 	= $this->memebr_max_booster_ads_to_view_today(5);

		$totads = ($max_mint_ads+$max_booster_ads);
		if($totads!="") return $totads;
		else return "0";			
	}
	
	function minting_max_limit_ads_by_date($userId,$dt)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		$dt  = $this->filter_mysql($dt);
		$totalAC = 0;			

		$target_date = $dt;
		$target_date_time = $target_date." 23:59:59";	
		$extraw = "and od.od_pack_validity >= '".$target_date."' and od.od_created<='".$target_date_time."'"; 
	//	$extraw .= "and od.od_pack_val_days > od.od_pack_val_used";
			
		/*$sqlS = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","sum(pack_quan) as sold","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id='4' and od.od_status='Active' and od.user_id='".$userId."'".$extraw."",1);
		if($sqlS['sold']!='')
		{
			$mintingArr = parent::selectData(TABLE_ADVER_PACK_MINING_BENEFIT,"","apmb_status='Active' and apmb_ad_blocks_owned <= '".$sqlS['sold']."' order by apmb_id desc",1); 
			$totalAC += $mintingArr['apmb_ad_view_req_max'];
		}
		else
		{
			$totalAC += 1;
		}	*/
		
		if(strtotime($target_date)<=strtotime('2019-02-24'))
		{
			$totalAC += 1;
		}
			
		$target_date = $dt;
		$target_date_time = $target_date." 23:59:59";	
		$extraw = "and od.od_pack_validity >= '".$target_date."' and od.od_created<='".$target_date_time."'"; 
		///$extraw .= "and od.od_pack_val_days > od.od_pack_val_used";
		
		$sqlSS = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","sum(pack_quan) as solds","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id='5' and od.od_status='Active' and od.user_id='".$userId."'".$extraw."",1);
		if($sqlSS['solds']!='')
		{
			//$mintBoostArr = parent::selectData(TABLE_MINING_BOOSTER_PACK_BENEFIT,"mbpb_ad_view_req_max","mbpb_status='Active' and mbpb_ad_block_owned <= '".$sqlSS['solds']."' order by mbpb_id desc",1); 
			//$totalAC += $mintBoostArr['mbpb_ad_view_req_max'];
			$totalAC += $sqlSS['solds'];
		}	
		
		$tot_login_stake = $this->get_member_login_stake($userId);		
		if($tot_login_stake == 10000) $totalAC = $totalAC+1;
		
		if($totalAC) return $totalAC;
		else return 0;
	}
	
	function member_minting_ads_viewed_today($userId)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		$adlIds = "";
		$today  = date("Y-m-d");
		$sqlU = parent::selectData(TABLE_AD_MINTER_MEMBER_VIEW,"","ammv_status='Active' and user_id='".$userId."' and ammv_created_dt='".$today."' order by ammv_id asc");
		while($resU = mysqli_fetch_array($sqlU)) 
		{
			$adlIds .= trim($resU['adl_id'],",").",";
		}		 
		if($adlIds!="") return $adlIds;
		else return "0";	
	}	
	
	function member_count_minting_ads_viewed_today($userId)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		$adlIds = 0;
		$today  = date("Y-m-d");
		$sqlU = parent::selectData(TABLE_AD_MINTER_MEMBER_VIEW,"adl_id","ammv_status='Active' and user_id='".$userId."' and ammv_created_dt='".$today."' order by ammv_id asc");
		while($resU = mysqli_fetch_array($sqlU)) 
		{
			$totalTDV = explode(",",trim($resU['adl_id'],","));
			$adlIds += count($totalTDV);
		}
		return $adlIds;
	}	
	
	function member_count_minting_ads_viewed_by_date($userId,$dt)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));	
		$dt  	 = $this->filter_mysql($dt);	
		
		$adlIds = 0;
		$sqlU = parent::selectData(TABLE_AD_MINTER_MEMBER_VIEW,"adl_id","ammv_status='Active' and user_id='".$this->filter_mysql($userId)."' and ammv_created_dt='".$dt."' order by ammv_id asc");
		while($resU = mysqli_fetch_array($sqlU)) 
		{
			$totalTDV = explode(",",trim($resU['adl_id'],","));
			$adlIds += count($totalTDV);
		}
		return $adlIds;
	}
		
	function member_minting_ads_already_viewed($userId)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));	
		$adlIds  = "";
		$today   = date("Y-m-d");
		$sqlU    = parent::selectData(TABLE_AD_MINTER_MEMBER_VIEW,"","ammv_status = 'Active' AND user_id = '".$userId."' ORDER BY ammv_id asc");
		while($resU = mysqli_fetch_array($sqlU)) 
		{
			$adlIds .= trim($resU['adl_id'],",").",";
		}
		 
		if($adlIds!="") return $adlIds;
		else return "0";	
	}
	
	function get_member_minting_ad_to_view_geo($userId) 
	{
		$userId 				= $this->filter_mysql($this->filter_numeric($userId));
		$userD 					= parent::selectData(TABLE_USER,"user_language,user_country","u_login_id='".$userId."' and user_status='Active'",1); 
		$user_language 			= $userD['user_language']; 
		$glangD 				= parent::selectData(TABLE_GEO_LANGUAGE,"glan_id","glan_code='".$user_language."'",1);
		$adl_geo_lang_id 	    = $glangD['glan_id']; 
		$adl_geo_con_id 		= $userD['user_country'];
		$ggconD 				= parent::selectData(TABLE_GEO_GROUP_COUNTRY,"ggc_id","ggc_countries like '%,".$adl_geo_con_id.",%' ",1);
		$adl_geo_con_group_id 	= $ggconD['ggc_id'];
		
		$alreadyview = $this->member_minting_ads_already_viewed($userId);
		$adlIds = rtrim($alreadyview,",");
		$extra .= " AND adl_id NOT IN (".$adlIds.")";
		  
		$extra .= " and (adl_geo_lang_id like '%,".$adl_geo_lang_id.",%' or adl_geo_lang_id = 'all' or adl_geo_lang_id = '') and ((adl_geo_con_group_id like '%,".$adl_geo_con_group_id.",%' or adl_geo_con_group_id ='' or adl_geo_con_group_id ='0') and (adl_geo_con_id like '%,".$adl_geo_con_id.",%' or adl_geo_con_id ='' or adl_geo_con_id ='0'))";
		
		$adlAId = parent::selectData(TABLE_AD_DIRECTORY_LISTING,"","adl_status = 'Active' AND user_id != '".$userId."' AND adl_impression > adl_impression_view ".$extra." ORDER BY RAND ()",1);
		
		$already_view_id = $adlAId['adl_id'];
		$getad = $already_view_id;
		if (empty($getad)) {
			$todayview = $this->member_minting_ads_viewed_today($userId);
			$adlTIds = rtrim($todayview,",");
			$extraa .= " AND adl_id NOT IN (".$adlTIds.")"; 
			 
			$extraa .= " and (adl_geo_lang_id like '%,".$adl_geo_lang_id.",%' or adl_geo_lang_id = 'all' or adl_geo_lang_id = '') and ((adl_geo_con_group_id like '%,".$adl_geo_con_group_id.",%' or adl_geo_con_group_id ='' or adl_geo_con_group_id ='0') and (adl_geo_con_id like '%,".$adl_geo_con_id.",%' or adl_geo_con_id ='' or adl_geo_con_id ='0'))";
			
			$adlTId = parent::selectData(TABLE_AD_DIRECTORY_LISTING,"","adl_status = 'Active' AND user_id != '".$userId."' AND adl_impression > adl_impression_view ".$extraa." ORDER BY RAND ()",1);
			
			$today_view_id = $adlTId['adl_id'];
			$getad = $today_view_id;
		}
		if(empty($getad)) 
		{ 
			$extraan .= " and (adl_geo_lang_id like '%,".$adl_geo_lang_id.",%' or adl_geo_lang_id = 'all' or adl_geo_lang_id = '') and ((adl_geo_con_group_id like '%,".$adl_geo_con_group_id.",%' or adl_geo_con_group_id ='' or adl_geo_con_group_id ='0') and (adl_geo_con_id like '%,".$adl_geo_con_id.",%' or adl_geo_con_id ='' or adl_geo_con_id ='0'))";
			
			$adlTId = parent::selectData(TABLE_AD_DIRECTORY_LISTING,"","adl_status = 'Active' AND user_id != '".$userId."' AND adl_impression > adl_impression_view ".$extraan." ORDER BY RAND ()",1);
			$random_id = $adlTId['adl_id'];
			$getad = $random_id;
		}
 
		return($getad);
	}
	 
	
	function get_member_minting_ad_to_view($userId) 
	{
		$userId = $this->filter_mysql($this->filter_numeric($userId));
		
		$userD 					= parent::selectData(TABLE_USER,"user_language,user_country","u_login_id='".$userId."' and user_status='Active'",1); 
		$user_language 			= $userD['user_language']; 
		$glangD 				= parent::selectData(TABLE_GEO_LANGUAGE,"glan_id","glan_code='".$user_language."'",1);
		$adl_geo_lang_id 	    = $glangD['glan_id']; 
		$adl_geo_con_id 		= $userD['user_country'];
		$ggconD 				= parent::selectData(TABLE_GEO_GROUP_COUNTRY,"ggc_id","ggc_countries like '%,".$adl_geo_con_id.",%' ",1);
		$adl_geo_con_group_id 	= $ggconD['ggc_id'];		
		
		$alreadyview = $this->member_minting_ads_already_viewed($userId);
		$adlIds = rtrim($alreadyview,",");
		$extra .= " AND adl_id NOT IN (".$adlIds.")";
		
		$extra .= " and (adl_geo_lang_id like '%,".$adl_geo_lang_id.",%' or adl_geo_lang_id = 'all' or adl_geo_lang_id = '') and ((adl_geo_con_group_id like '%,".$adl_geo_con_group_id.",%' or adl_geo_con_group_id ='' or adl_geo_con_group_id ='0') and (adl_geo_con_id like '%,".$adl_geo_con_id.",%' or adl_geo_con_id ='' or adl_geo_con_id ='0'))";
		
		$adlAId = parent::selectData(TABLE_AD_DIRECTORY_LISTING,"","adl_status = 'Active' AND user_id != '".$userId."' AND adl_impression > adl_impression_view ".$extra." ORDER BY RAND ()",1);
		$already_view_id = $adlAId['adl_id'];
		$getad = $already_view_id;
		if (empty($getad)) {
			$todayview = $this->member_minting_ads_viewed_today($userId);
			$adlTIds = rtrim($todayview,",");
			$extraa .= " AND adl_id NOT IN (".$adlTIds.")";
			
			$extraa .= " and (adl_geo_lang_id like '%,".$adl_geo_lang_id.",%' or adl_geo_lang_id = 'all' or adl_geo_lang_id = '') and ((adl_geo_con_group_id like '%,".$adl_geo_con_group_id.",%' or adl_geo_con_group_id ='' or adl_geo_con_group_id ='0') and (adl_geo_con_id like '%,".$adl_geo_con_id.",%' or adl_geo_con_id ='' or adl_geo_con_id ='0'))";
			
			$adlTId = parent::selectData(TABLE_AD_DIRECTORY_LISTING,"","adl_status = 'Active' AND user_id != '".$userId."' AND adl_impression > adl_impression_view ".$extraa." ORDER BY RAND ()",1);
			$today_view_id = $adlTId['adl_id'];
			$getad = $today_view_id;
		}
		if(empty($getad)) 
		{
			$extraan .= " and (adl_geo_lang_id like '%,".$adl_geo_lang_id.",%' or adl_geo_lang_id = 'all' or adl_geo_lang_id = '') and ((adl_geo_con_group_id like '%,".$adl_geo_con_group_id.",%' or adl_geo_con_group_id ='' or adl_geo_con_group_id ='0') and (adl_geo_con_id like '%,".$adl_geo_con_id.",%' or adl_geo_con_id ='' or adl_geo_con_id ='0'))";
			
			$adlTId = parent::selectData(TABLE_AD_DIRECTORY_LISTING,"","adl_status = 'Active' AND user_id != '".$userId."' AND adl_impression > adl_impression_view ".$extraan." ORDER BY RAND ()",1);
			$random_id = $adlTId['adl_id'];
			$getad = $random_id;
		}
			// Uncomment to provide advertisers with extra traffic if we are out of supply
			// if(empty($getad)) {
				// $adlTId = parent::selectData(TABLE_AD_DIRECTORY_LISTING,"","adl_status = 'Active' AND user_id != '".$userId."' ORDER BY RAND ()",1);
				// $random_id = $adlTId['adl_id'];
				// $getad = $random_id;
			// }
			
		return($getad);
	}


	///////////////////////////////////Login Ads/////////////////////////////////////////////
	
	

	function member_login_ads_viewed_today($userId)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));	 
		$adlIds = "";
		$today  = date("Y-m-d");
 
		$sqlU = parent::selectData(TABLE_LOGIN_AD_MEMBER_VIEW,"","lamv_status='Active' and user_id='".$userId."' and lamv_created_dt='".$today."' order by lamv_id asc");
		while($resU = mysqli_fetch_array($sqlU)) 
		{
			$adlIds .= trim($resU['logad_id'],",").",";
		}		 
		 $logAdIds = rtrim($adlIds,",");
		if($logAdIds!="") return $logAdIds;
		else return "0";	
	}	
	 		
	function member_login_ads_already_viewed($userId)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));	 
		$adlIds = "";
		$today  = date("Y-m-d");
		$sqlU = parent::selectData(TABLE_LOGIN_AD_MEMBER_VIEW,"","lamv_status='Active' and user_id='".$userId."' order by lamv_id asc");
		while($resU = mysqli_fetch_array($sqlU)) 
		{
			$adlIds .= trim($resU['logad_id'],",").",";
		}
		 $logAdIds = rtrim($adlIds,",");
		if($logAdIds!="") return $logAdIds;
		else return "0";	
	}

 	function get_member_login_ads_to_view($userId)
	{  		
		$userId  = $this->filter_mysql($this->filter_numeric($userId));	 
		$alreadyview = $this->member_login_ads_already_viewed($userId);			
		$extra .= " and logad_id not in (".$alreadyview.")";
		 
		$sqlU = parent::selectData(TABLE_LOGIN_AD_LISTING,"","logad_status='Active' and user_id!='".$userId."' and logad_impression > logad_view_imp ".$extra."","","logad_id asc","","0,1");
		while($resU = mysqli_fetch_array($sqlU)) 
			{
				$adlIds .= $resU['logad_id'].",";
			}

		$getad = rtrim($adlIds,",");
		 

			if(empty($getad))
			{
				$todayview = $this->member_login_ads_viewed_today($userId);				 
				$extraa .= " and logad_id not in (".$todayview.")";

				$sqlUU = parent::selectData(TABLE_LOGIN_AD_LISTING,"","logad_status='Active' and user_id!='".$this->filter_mysql($userId)."' and logad_impression > logad_view_imp ".$extraa."","","logad_id asc","","0,1");
				while($resUU = mysqli_fetch_array($sqlUU)) 
					{
						$adlITds .= $resUU['logad_id'].",";
					}
				 
				 $getad = rtrim($adlITds,",");
				 
			}	
			if(empty($getad))
			{
				$sqlUR = parent::selectData(TABLE_LOGIN_AD_LISTING,"","logad_status='Active' and user_id!='".$this->filter_mysql($userId)."' and logad_impression > logad_view_imp ","","RAND ()","","0,1");
				while($resUR = mysqli_fetch_array($sqlUR)) 
					{
						$adlIRds .= $resUR['logad_id'].",";
					}
				 
				$getad = rtrim($adlIRds,",");
				 
			}	
		  
			//return($getad);	
			if($getad!="") return $getad;
			else return "0";	
		}

	function set_member_view_login_ads($logad_id)
	{ 
		$logad_id  = $this->filter_numeric($logad_id);	 
		$userId = $_SESSION['user']['u_login_id'];
		$today  = date("Y-m-d");	
		
	    $data =parent::selectData(TABLE_LOGIN_AD_MEMBER_VIEW,"","lamv_status='Active' and lamv_created_dt ='".$today."' and user_id ='".$this->filter_mysql($userId)."' order by lamv_id asc",1);	
		if(empty($data['lamv_id'])) { 
			$_POST['logad_id'] 			= ",".$logad_id.",";
			$_POST['user_id'] 			= $userId;
			$_POST['lamv_created_dt']	= CURRENT_DATE_TIME;
			$_POST['lamv_modified']		= CURRENT_DATE_TIME;				
			parent::insertData(TABLE_LOGIN_AD_MEMBER_VIEW,$_POST);		 
		} else {
			//$updatelamArr['logad_id'] = $data['logad_id'].','.$logad_id;
			$logid = $data['logad_id'];
			$frlogid =  trim($logid,",");
			$final_logdid =  ltrim($frlogid,",");
			$arraydata = explode(',', $final_logdid.','.$logad_id);	
			 
			//$arryunique = array_unique($arraydata); 
			$setarryunique = implode(',', $arraydata);			 
			$updatelamArr['logad_id'] = ",".$setarryunique.",";
			
			parent::updateData(TABLE_LOGIN_AD_MEMBER_VIEW,$updatelamArr,"lamv_id='".$data['lamv_id']."'");		
			 
						 
		}
		$data_lad =parent::selectData(TABLE_LOGIN_AD_LISTING,"","logad_id='".$logad_id."'",1);
		$updateArr['logad_view_imp'] = $data_lad['logad_view_imp']+1;							 
		parent::updateData(TABLE_LOGIN_AD_LISTING,$updateArr,"logad_id='".$logad_id."'");	
		
		$sqlU = parent::selectData(TABLE_USER,"user_login_imp","u_login_id='".$userId."'",1); 
		$arrUU['user_login_imp'] = $sqlU['user_login_imp']+1;
		parent::updateData(TABLE_USER,$arrUU,"user_id='".$sqlU['user_id']."'");
	 
	}	

	///////////////////////////////////Banner Ads/////////////////////////////////////////////

	
	function member_banner_ads_viewed_today($type,$userId)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));	  
		$type    = $this->filter_mysql($type);	
		$adlIds = "";
		$today  = date("Y-m-d");
 
		$sqlU = parent::selectData(TABLE_BANNER_AD_MEMBER_VIEW,"","bamv_status='Active' and bamv_type ='".$type."' and user_id='".$userId."' and bamv_created_dt='".$today."' order by bamv_id asc");
		while($resU  = mysqli_fetch_array($sqlU)) 
		{
			$adlIds .= trim($resU['banad_id_imp'],",").",";
		}		 
		 
		$logAdIds = rtrim($adlIds,",");
		$arraydata = explode(',', $logAdIds);
		//$arryunique = array_unique($arraydata); 
		$setarryunique = implode(',', $arraydata);	
		// return $setarryunique;
		if($logAdIds!="") return $logAdIds;
		else return "0";	
	}	
	 		
	function member_banner_ads_already_viewed($type,$userId)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));	  
		$type    = $this->filter_mysql($type);	
	
		$adlIds = "";
		$today  = date("Y-m-d");
		$sqlU = parent::selectData(TABLE_BANNER_AD_MEMBER_VIEW,"","bamv_status='Active' and bamv_type ='".$type."' and user_id='".$userId."' order by bamv_id asc");
		while($resU = mysqli_fetch_array($sqlU)) 
		{
			$adlIds .= trim($resU['banad_id_imp'],",").",";
		}		 
		$banAdIds = rtrim($adlIds,",");
		//$arraydata = explode(',', $banAdIds);
		//$arryunique = array_unique($arraydata); 
		//$setarryunique = implode(',', $arraydata);	
		// return $setarryunique;
		if($banAdIds!="") return $banAdIds;
		else return "0";	
	}
	
	function get_member_banner_ads_to_view_geo($type, $userId, $cnt) 
	{ 
		$deliver 	= array();
		$userId 	= $this->filter_mysql($this->filter_numeric($userId));
		$type 		= $this->filter_mysql($type);
		$cnt 		= $this->filter_mysql($this->filter_numeric($cnt));
		 
		$userD 		= parent::selectData(TABLE_USER,"user_language,user_country","u_login_id='".$userId."' and user_status='Active'",1); 
		$user_language 	= $userD['user_language']; 
		$glangD 	= parent::selectData(TABLE_GEO_LANGUAGE,"glan_id","glan_code='".$user_language."'",1);
		$banad_geo_lang_id 	    = $glangD['glan_id']; 
		$banad_geo_con_id 		= $userD['user_country'];
		$ggconD 		= parent::selectData(TABLE_GEO_GROUP_COUNTRY,"ggc_id","ggc_countries like '%,".$banad_geo_con_id.",%' ",1);
		$banad_geo_con_group_id = $ggconD['ggc_id'];
		
		
		$adC = 0;
		$banS = parent::selectData(TABLE_BANNER_AD_LAST_SEEN,"","bls_type='".$type."'",1);
		$last_seen_id = $banS['banad_id'];
		$extra = " and banad_id > '".$last_seen_id."'";
		 
		$extra .= " and (banad_geo_lang_id like '%,".$banad_geo_lang_id.",%' or banad_geo_lang_id = 'all' or banad_geo_lang_id = '') and (banad_geo_con_group_id like '%,".$banad_geo_con_group_id.",%' or banad_geo_con_id='".$banad_geo_con_id."' or banad_geo_con_group_id ='')"; 
		
		startElementTest("get_member_banner_ads_to_view - TABLE_BANNER_ADS 1");		
		$sqlU = parent::selectData(TABLE_BANNER_ADS,"banad_id, user_id","banad_status='Active' and banad_type_size ='".$type."' and banad_impression > banad_view_imp_ach ".$extra."","","banad_id asc","","0,".$cnt);
		while ($resU = mysqli_fetch_array($sqlU)) 
		{
			$uq_data = array(
				"uq_time" => time(), 
				"uq_bid" => $resU['banad_id'],
				"uq_uid" => $resU['user_id']
			);
			parent::insertData("hashing_update_queue", $uq_data);
			$adlIds .= $resU['banad_id'].",";
			$adC++;
			 
		}
		endElementTest("get_member_banner_ads_to_view - TABLE_BANNER_ADS 1");
		if ($adC < $cnt) { 
			$rest1 = $cnt-$adC; 
			$extraa = " and banad_id <= '".$last_seen_id."'";
			
			 
			$extraa .= " and (banad_geo_lang_id like '%,".$banad_geo_lang_id.",%' or banad_geo_lang_id = 'all' or banad_geo_lang_id = '') and (banad_geo_con_group_id like '%,".$banad_geo_con_group_id.",%' or banad_geo_con_id='".$banad_geo_con_id."' or banad_geo_con_group_id ='')"; 
			
			startElementTest("get_member_banner_ads_to_view - TABLE_BANNER_ADS 2");
			$sqlUU = parent::selectData(TABLE_BANNER_ADS,"banad_id, user_id","banad_status='Active' and banad_type_size ='".$this->filter_mysql($type)."'  and banad_impression > banad_view_imp_ach ".$extraa."","","banad_id asc","","0,".$rest1);
			while ($resUU = mysqli_fetch_array($sqlUU)) {
				$uq_data = array(
					"uq_time" => time(), 
					"uq_bid" => $resUU['banad_id'],
					"uq_uid" => $resUU['user_id']
				);
				parent::insertData("hashing_update_queue", $uq_data);
				$adlIds .= $resUU['banad_id'].",";
				$adC++;
				 
			}
			endElementTest("get_member_banner_ads_to_view - TABLE_BANNER_ADS 2");
		}
		 
		$getad = rtrim($adlIds,",");
		$getads = explode(",",$getad);
		$total = count($getads);
		$last_id = $getads[$total-1];
		if ($last_id) {
			$lastSD['banad_id'] = $last_id;
			$lastSD['bls_update'] = date("Y-m-d H:i:s");
			parent::updateData(TABLE_BANNER_AD_LAST_SEEN,$lastSD,"bls_type='".$type."'");
		}
		if ($getad != "")  return $getad;
		return "0";
	}




	function get_member_banner_ads_to_view($type, $userId, $cnt) {
		$deliver 	= array();
		$userId 	= $this->filter_mysql($this->filter_numeric($userId));
		$type 		= $this->filter_mysql($type);
		$cnt 		= $this->filter_mysql($this->filter_numeric($cnt));	

		$userD 					= parent::selectData(TABLE_USER,"user_language,user_country","u_login_id='".$userId."' and user_status='Active'",1); 
		$user_language 			= $userD['user_language']; 
		$glangD 				= parent::selectData(TABLE_GEO_LANGUAGE,"glan_id","glan_code='".$user_language."'",1);
		$banad_geo_lang_id 	    = $glangD['glan_id']; 
		$banad_geo_con_id 		= $userD['user_country'];
		$ggconD 				= parent::selectData(TABLE_GEO_GROUP_COUNTRY,"ggc_id","ggc_countries like '%,".$banad_geo_con_id.",%' ",1);
		$banad_geo_con_group_id = $ggconD['ggc_id'];
		
		$adC = 0;
		$banS = parent::selectData(TABLE_BANNER_AD_LAST_SEEN,"","bls_type='".$type."'",1);
		$last_seen_id = $banS['banad_id'];
		$extra = " and banad_id > '".$last_seen_id."'";
		
		$extra .= " and (banad_geo_lang_id like '%,".$banad_geo_lang_id.",%' or banad_geo_lang_id = 'all' or banad_geo_lang_id = '') and ((banad_geo_con_group_id like '%,".$banad_geo_con_group_id.",%' or banad_geo_con_group_id ='' or banad_geo_con_group_id ='0') and (banad_geo_con_id like '%,".$banad_geo_con_id.",%' or banad_geo_con_id ='' or banad_geo_con_id ='0'))";
		
		 
		startElementTest("get_member_banner_ads_to_view - TABLE_BANNER_ADS 1");
		$sqlU = parent::selectData(TABLE_BANNER_ADS,"banad_id, user_id","banad_status='Active' and banad_type_size ='".$type."' and banad_impression > banad_view_imp_ach ".$extra."","","banad_id asc","","0,".$cnt);
		while ($resU = mysqli_fetch_array($sqlU)) {
			$uq_data = array(
				"uq_time" => time(), # TABLE_BANNER_ADS / banad_view_imp_ach / banad_id + TABLE_USER / user_ban_deliver / u_login_id
				"uq_bid" => $resU['banad_id'],
				"uq_uid" => $resU['user_id']
			);
			parent::insertData("hashing_update_queue", $uq_data);
			$adlIds .= $resU['banad_id'].",";
			$adC++;
			# Update Owner Banner Deliver			
			# $owner_id = $resU['user_id'];
			# if (isset($deliver[$owner_id])) $deliver[$owner_id] = $deliver[$owner_id]+1;
			# else $deliver[$owner_id] = 1;
			# $userOD  = parent::selectData(TABLE_USER,"user_ban_deliver","u_login_id='".$owner_id."'",1);
			# $deliverD['user_ban_deliver'] = $userOD['user_ban_deliver'] + 1;
			# parent::updateData(TABLE_USER,$deliverD,"u_login_id='".$owner_id."'");
			# parent::quickIncrement(TABLE_USER,"user_ban_deliver","u_login_id='".$owner_id."'");
		}
		endElementTest("get_member_banner_ads_to_view - TABLE_BANNER_ADS 1");
		if ($adC < $cnt) { 
			$rest1 = $cnt-$adC;
			$extraa  = " and banad_id <= '".$last_seen_id."'";
			$extraa .= " and (banad_geo_lang_id like '%,".$banad_geo_lang_id.",%' or banad_geo_lang_id = 'all' or banad_geo_lang_id = '') and ((banad_geo_con_group_id like '%,".$banad_geo_con_group_id.",%' or banad_geo_con_group_id ='' or banad_geo_con_group_id ='0') and (banad_geo_con_id like '%,".$banad_geo_con_id.",%' or banad_geo_con_id ='' or banad_geo_con_id ='0'))";
			startElementTest("get_member_banner_ads_to_view - TABLE_BANNER_ADS 2");
			$sqlUU = parent::selectData(TABLE_BANNER_ADS,"banad_id, user_id","banad_status='Active' and banad_type_size ='".$this->filter_mysql($type)."'  and banad_impression > banad_view_imp_ach ".$extraa."","","banad_id asc","","0,".$rest1);
			while ($resUU = mysqli_fetch_array($sqlUU)) {
				$uq_data = array(
					"uq_time" => time(), # TABLE_BANNER_ADS / banad_view_imp_ach / banad_id + TABLE_USER / user_ban_deliver / u_login_id
					"uq_bid" => $resUU['banad_id'],
					"uq_uid" => $resUU['user_id']
				);
				parent::insertData("hashing_update_queue", $uq_data);
				$adlIds .= $resUU['banad_id'].",";
				$adC++;
				# Update Owner Banner Deliver
				# $owner_id = $resUU['user_id'];
				# if (isset($deliver[$owner_id])) $deliver[$owner_id] = $deliver[$owner_id]+1;
				# else $deliver[$owner_id] = 1;
				# $userOD  = parent::selectData(TABLE_USER,"user_ban_deliver","u_login_id='".$owner_id."'",1);
				# $deliverD['user_ban_deliver'] = $userOD['user_ban_deliver'] + 1;
				# parent::updateData(TABLE_USER,$deliverD,"u_login_id='".$owner_id."'");
				# parent::quickIncrement(TABLE_USER,"user_ban_deliver","u_login_id='".$owner_id."'");
			}
			endElementTest("get_member_banner_ads_to_view - TABLE_BANNER_ADS 2");
		}
		# Update Last Seen ID
		$getad = rtrim($adlIds,",");
		$getads = explode(",",$getad);
		$total = count($getads);
		$last_id = $getads[$total-1];
		if ($last_id) {
			$lastSD['banad_id'] = $last_id;
			$lastSD['bls_update'] = date("Y-m-d H:i:s");
			parent::updateData(TABLE_BANNER_AD_LAST_SEEN,$lastSD,"bls_type='".$type."'");
		}
		if ($getad != "") {
			return $getad;
		}
		return "0";
	}

	function get_member_banner_ads_to_view_old($type,$userId,$cnt)
	{  				
		$userId  = $this->filter_mysql($this->filter_numeric($userId));	  
		$type    = $this->filter_mysql($type);	  
		$cnt     = $this->filter_mysql($this->filter_numeric($cnt));	  
		
			$adC = 0;		
			$alreadyview = $this->member_banner_ads_already_viewed($type,$userId);	
			if($alreadyview=='') $alreadyview = 0;			 	
			$extra = " and banad_id not in (".$alreadyview.")";			 
			$sqlU = parent::selectData(TABLE_BANNER_ADS,"","banad_status='Active' and banad_type_size ='".$type."' and user_id!='".$userId."' and banad_impression > banad_view_imp_ach ".$extra."","","RAND ()","","0,".$cnt);
			while($resU = mysqli_fetch_array($sqlU)) 
			{
					$adlIds .= $resU['banad_id'].",";
					$adC++;
					
				   ///////////////////////Update Owner Banner Deliver////////////////	
					$owner_id = $resU['user_id'];		
					$userOD  = parent::selectData(TABLE_USER,"user_ban_deliver","u_login_id='".$owner_id."'",1);
					$deliverD['user_ban_deliver'] = $userOD['user_ban_deliver'] + 1;
					parent::updateData(TABLE_USER,$deliverD,"u_login_id='".$owner_id."'");			
				   /////////////////////////////////////////////////////////////////////
			}

		    $getad = rtrim($adlIds,",");	
			if($adC<$cnt)
			{
				$rest1 = $cnt-$adC;
				$todayview = $this->member_banner_ads_viewed_today($type,$userId);	
				$remList = rtrim($todayview.",".$getad,",");	
				if($remList=='') $remList = 0;	 
				$extraa = " and banad_id not in (".$remList.")";
				$sqlUU = parent::selectData(TABLE_BANNER_ADS,"","banad_status='Active' and banad_type_size ='".$this->filter_mysql($type)."' and user_id!='".$this->filter_mysql($userId)."' and banad_impression > banad_view_imp_ach ".$extraa."","","RAND ()","","0,".$rest1);
				while($resUU = mysqli_fetch_array($sqlUU)) 
					{
						$adlIds .= $resUU['banad_id'].",";
						$adC++;
						///////////////////////Update Owner Banner Deliver////////////////	
						$owner_id = $resUU['user_id'];		
						$userOD  = parent::selectData(TABLE_USER,"user_ban_deliver","u_login_id='".$owner_id."'",1);
						$deliverD['user_ban_deliver'] = $userOD['user_ban_deliver'] + 1;
						parent::updateData(TABLE_USER,$deliverD,"u_login_id='".$owner_id."'");			
					   /////////////////////////////////////////////////////////////////////
					}				 
				$getad = rtrim($adlIds,",");		 
			}
			if($adC<$cnt)
			{
				$rest2 = $cnt-$adC;
				if($getad=='') $getad = 0;	 
				$extraas = " and banad_id not in (".$getad.")";
				$sqlUR = parent::selectData(TABLE_BANNER_ADS,"","banad_status='Active' and banad_type_size ='".$this->filter_mysql($type)."' and user_id!='".$this->filter_mysql($userId)."' and banad_impression > banad_view_imp_ach ".$extraas,"","RAND ()","","0,".$rest2);
				while($resUR = mysqli_fetch_array($sqlUR)) 
					{
						$adlIds .= $resUR['banad_id'].",";
						$adC++;
						///////////////////////Update Owner Banner Deliver////////////////	
						$owner_id = $resUR['user_id'];		
						$userOD  = parent::selectData(TABLE_USER,"user_ban_deliver","u_login_id='".$owner_id."'",1);
						$deliverD['user_ban_deliver'] = $userOD['user_ban_deliver'] + 1;
						parent::updateData(TABLE_USER,$deliverD,"u_login_id='".$owner_id."'");			
					   /////////////////////////////////////////////////////////////////////
					}				 	
				 $getad = rtrim($adlIds,",");				 
			}
			/*
			if($getadalready !="") { $getad = $getadalready;
			} else if($getadtoday !="") { $getad = $getadtoday;
			} else { $getad = $getadany; }
			*/
			  if($getad!="")
			  {
			  	$sqlMV = parent::selectData(TABLE_BANNER_AD_MEMBER_VIEW,"","user_id='".$this->filter_mysql($userId)."' and bamv_created_dt='".date("Y-m-d")."'",1);
				if($sqlMV['bamv_id']=='')
				{
					 $bamvI['user_id'] = $userId;
					 $bamvI['banad_id_imp'] = ",".$getad.",";
					 $bamvI['bamv_created_dt'] = date("Y-m-d");
					 $bamvI['bamv_modified'] =  date("Y-m-d H:i:s");
					 $bamvI['bamv_status']   = 'Active'; 
					 parent::insertData(TABLE_BANNER_AD_MEMBER_VIEW,$bamvI);
					 
					 $adIds = explode(",",$getad);
					 for($i=0;$adIds[$i]!='';$i++)
					 {
						$data_lad =parent::selectData(TABLE_BANNER_ADS,"banad_view_imp_ach","banad_id='".$adIds[$i]."'",1);
						$updateArr['banad_view_imp_ach'] = $data_lad['banad_view_imp_ach']+1;							 
						//parent::updateData(TABLE_BANNER_ADS,$updateArr,"banad_id='".$adIds[$i]."'");
						parent::quickIncrement(TABLE_BANNER_ADS,"banad_view_imp_ach","banad_id='".$adIds[$i]."'");
					}
				}
				else
				{
					$bamvU['banad_id_imp'] = ",".trim($sqlMV['banad_id_imp'],",").",".$getad.",";
					$bamvU['bamv_modified'] =  date("Y-m-d H:i:s");
					parent::updateData(TABLE_BANNER_AD_MEMBER_VIEW,$bamvU,"bamv_id='".$sqlMV['bamv_id']."'");
					
					$adIds = explode(",",$getad);
					for($i=0;$adIds[$i]!='';$i++)
					{
						$data_lad =parent::selectData(TABLE_BANNER_ADS,"banad_view_imp_ach","banad_id='".$adIds[$i]."'",1);
						$updateArr['banad_view_imp_ach'] = $data_lad['banad_view_imp_ach']+1;							 
						//parent::updateData(TABLE_BANNER_ADS,$updateArr,"banad_id='".$adIds[$i]."'");
						parent::quickIncrement(TABLE_BANNER_ADS,"banad_view_imp_ach","banad_id='".$adIds[$i]."'");
					}
				}
			  	return $getad;
			  }
			  else return "0";				
			 
		}
  	
	function set_member_view_banner_ads($banad_id)
	{ 
		$banad_id  	= $this->filter_numeric($banad_id);	  
		$userId 	= $this->filter_numeric($_SESSION['user']['u_login_id']); 
		$today  	= date("Y-m-d");	
		$data 		= parent::selectData(TABLE_BANNER_AD_MEMBER_VIEW,"","bamv_status='Active' and bamv_created_dt ='".$today."' and user_id ='".$userId."' order by bamv_id asc",1);	
		 
		if(empty($data)){
		
			$dataVBA['banad_id'] 			= ','.$banad_id.',';
			$dataVBA['user_id'] 			= $userId;
			$dataVBA['bamv_type'] 			= $type;
			$dataVBA['bamv_created_dt']		= CURRENT_DATE_TIME;
			$dataVBA['bamv_modified']		= CURRENT_DATE_TIME;				
			parent::insertData(TABLE_BANNER_AD_MEMBER_VIEW,$dataVBA);	
		} else { 
			$arraydata = explode(',', trim($data['banad_id'],",").','.$banad_id); 
			$setarryunique = implode(',', $arraydata);			 
			$updatelamArr['banad_id'] = ",".$setarryunique.",";
			parent::updateData(TABLE_BANNER_AD_MEMBER_VIEW,$updatelamArr,"bamv_id='".$data['bamv_id']."'");
			
		} 
		$banadData = explode(',', $banad_id);
		foreach($banadData as $banArr)
		{ 
			parent::quickIncrement(TABLE_BANNER_ADS,"banad_view_imp","banad_id='".$banArr."'");	
		} 
		parent::quickIncrement(TABLE_USER,"user_ban_imp","u_login_id='".$userId."'"); 
	}		
	
	function get_total_month_mint_ads_to_view($t)
	{
			$t  = $this->filter_mysql($this->filter_numeric($t));
			$userD = parent::selectData(TABLE_USER_LOGIN,"","u_login_id='".$_SESSION['user']['u_login_id']."' and u_login_status='Active'",1);
			$userAD = explode(" ",$userD['u_login_modified']);
			$totalAC = 0;
			for($i=0;$i<=$t;$i++)
			{		
			
				$today  = date("Y-m-d");
				$target_date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$i,date("Y")));
				$target_date_time = $target_date." 23:59:59";	
				//$extraw = "and od.od_pack_validity >= '".$target_date."' and od.od_created<='".$target_date_time."'"; 
				/*$extraw = "and od.od_pack_val_days > od.od_pack_val_used";
					
				$sqlS = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","sum(pack_quan) as sold","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id='4' and od.od_status='Active' and od.user_id='".$_SESSION['user']['u_login_id']."'".$extraw."",1);
				//echo "<br><br>";
				if($sqlS['sold']!='')
				{
					$mintingArr = parent::selectData(TABLE_ADVER_PACK_MINING_BENEFIT,"apmb_ad_view_req_max","apmb_status='Active' and apmb_ad_blocks_owned <= '".$sqlS['sold']."' order by apmb_id desc",1); 
		 
					$totalAC += $mintingArr['apmb_ad_view_req_max'];
				}
				else
				{
					if(strtotime($userAD[0])<=strtotime($target_date)) $totalAC += 1;
				}		*/	
				
				if(strtotime($userAD[0])<=strtotime($target_date) && strtotime($target_date)<=strtotime('2019-02-24')) $totalAC += 1;
			}
			
			if($totalAC) return $totalAC;
			else return 0;
	}
	
	function get_total_month_boost_ads_to_view($t) {
		$t = $this->filter_mysql($this->filter_numeric($t));
		$totalAC = 0;
		for ($i = 0; $i <= $t; $i++) {
			$today = date("Y-m-d");
			$target_date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$i,date("Y")));
			$target_date_time = $target_date." 23:59:59";
			$extraw  = "and od.od_pack_validity >= '".$target_date."' and od.od_created<='".$target_date_time."'";
			//$extraw .= "and od.od_pack_val_days > od.od_pack_val_used";
			$sqlS = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","sum(od.pack_quan) as sold","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id='5' and od.od_status='Active' and od.user_id='".$_SESSION['user']['u_login_id']."'".$extraw."",1);
			/*if ($sqlS['sold'] != '') {
				$mintBoostArr = parent::selectData(TABLE_MINING_BOOSTER_PACK_BENEFIT,"mbpb_ad_view_req_max","mbpb_status='Active' and mbpb_ad_block_owned <= '".$sqlS['sold']."' order by mbpb_id desc",1);
				$totalAC += $mintBoostArr['mbpb_ad_view_req_max'];
			}*/
			$totalAC += $sqlS['sold'];
			$sqlLS = parent::selectData(TABLE_USER_WALLET,"SUM(`wall_asimi`) AS `total_login_stake`", "`wall_status` = 'Active' AND `wall_type` = 'ls' AND `wall_pstatus` = 'p' AND `user_id` = '".$_SESSION['user']['u_login_id']."' AND `wall_modified` <= '".$target_date_time."'", 1);
			$sqlLUS = parent::selectData(TABLE_USER_WALLET,"SUM(`wall_asimi`) AS `total_login_unstake`", "`wall_status` = 'Active' AND `wall_type` = 'lus' AND `wall_pstatus` = 'p' AND `user_id` = '".$_SESSION['user']['u_login_id']."' AND `wall_modified` <= '".$target_date_time."'", 1);
			$tot_login_stake = $sqlLS['total_login_stake'] - $sqlLUS['total_login_unstake'];
			if ($tot_login_stake == 10000) $totalAC++;
		}
		if ($totalAC) return $totalAC;
		else return 0;
	}

	function get_total_minting_ads_to_view_in_month($t=29)
	{
		$t  = $this->filter_mysql($this->filter_numeric($t));
		$total = $this->get_total_month_mint_ads_to_view($t) + $this->get_total_month_boost_ads_to_view($t);
		return $total;
	
	}
	
	function member_count_minting_ads_viewed_in_month($userId)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		$totalVA = 0;
		$fromDate = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-29,date("Y")));
		$toDate = date("Y-m-d");

		$sqlU = parent::selectData(TABLE_AD_MINTER_MEMBER_VIEW,"adl_id","ammv_status='Active' and user_id='".$userId."' and ammv_created_dt>='".$fromDate."' and ammv_created_dt<='".$toDate."' ","","ammv_id asc");
		while($resU = mysqli_fetch_array($sqlU))
		{	
			//echo "<br><br>";
			$totalTDV = explode(",",trim($resU['adl_id'],","));
			$totalVA += count($totalTDV);
		}
		return $totalVA;
	}	
	
	function member_count_minting_ads_viewed_from_dt($userId,$fromdt)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		$fromdt  = $this->filter_mysql($fromdt);
		$totalVA = 0;
		//$fromDate = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-29,date("Y")));
		$toDate = date("Y-m-d");

		$sqlU = parent::selectData(TABLE_AD_MINTER_MEMBER_VIEW,"adl_id","ammv_status='Active' and user_id='".$userId."' and ammv_created_dt>='".$fromdt."' and ammv_created_dt<='".$toDate."' ","","ammv_id asc");
		while($resU = mysqli_fetch_array($sqlU))
		{	
			//echo "<br><br>";
			$totalTDV = explode(",",trim($resU['adl_id'],","));
			$totalVA += count($totalTDV);
		}
		return $totalVA;
	}	
	
	function minting_ads_viewed_by_date($userId,$dt)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		$dt  = $this->filter_mysql($dt);
		
		$sqlU = parent::selectData(TABLE_AD_MINTER_MEMBER_VIEW,"adl_id","ammv_status='Active' and user_id='".$userId."' and ammv_created_dt='".$dt."'",1);
		$totalTDV = explode(",",trim($sqlU['adl_id'],","));
		if($totalTDV[0]=="") $totalVA = 0;
		else $totalVA = count($totalTDV);
		if($totalVA) return $totalVA;
		else return 0;
	}
	
	
	
	function banner_ads_viewed_all_by_date($bId,$dt)
	{
		$bId  = $this->filter_mysql($this->filter_numeric($bId));
		$dt  = $this->filter_mysql($dt);
		
		$str = ",".$bId.",";
		$sqlU = parent::selectData(TABLE_BANNER_AD_MEMBER_VIEW,"banad_id","bamv_status='Active' and banad_id like '%,".$bId.",%'  and bamv_created_dt='".$dt."'","","bamv_id asc");
		while($resU = mysqli_fetch_array($sqlU))
		{
			//$totalBAC += substr_count($resU['banad_id'],$str);
			$data = explode(",",trim($resU['banad_id'],","));				
			$totalBAC += count(array_keys($data,$bId));
		}
		
		if($totalBAC) return $totalBAC;
		else return 0;
	}
	
	
	function add_ads_viewed_all_by_date($bId,$dt)
	{
		$bId  = $this->filter_mysql($this->filter_numeric($bId));
		
		$str = ",".$bId.",";
		$sqlU = parent::selectData(TABLE_AD_MINTER_MEMBER_VIEW,"adl_id","ammv_status='Active' and adl_id like '%,".$bId.",%'  and ammv_created_dt='".$dt."'","","ammv_id asc");
		while($resU = mysqli_fetch_array($sqlU))
		{
			//$totalBAC += substr_count($resU['adl_id'],$str);
			$data = explode(",",trim($resU['adl_id'],","));				
			$totalBAC += count(array_keys($data,$bId));
		}
		
		if($totalBAC) return $totalBAC;
		else return 0;
	}
	
	function login_ads_viewed_all_by_date($bId,$dt)
	{
		$bId  = $this->filter_mysql($this->filter_numeric($bId));
		$dt   = $this->filter_mysql($dt);
		
		$str = ",".$bId.",";
		$sqlU = parent::selectData(TABLE_LOGIN_AD_MEMBER_VIEW,"logad_id","lamv_status='Active' and logad_id like '%,".$bId.",%'  and lamv_created_dt='".$dt."'","","lamv_id asc");
		while($resU = mysqli_fetch_array($sqlU))
		{
			//$totalBAC += substr_count($resU['logad_id'],$str);
			$data = explode(",",trim($resU['logad_id'],","));				
			$totalBAC += count(array_keys($data,$bId));
		}
		
		if($totalBAC) return $totalBAC;
		else return 0;
	}
	
	function get_total_referrals($uId)
	{
		$uId  = $this->filter_mysql($this->filter_numeric($uId));
		
		$sqlR = parent::selectData(TABLE_USER,"count(user_id) as totalR","user_referrer = '".$uId."' and user_status<>'Deleted'",1);
		if($sqlR['totalR']) return $sqlR['totalR'];
		else return 0;
	}
	
	function get_all_referrals($uId)
	{
		$uId  = $this->filter_mysql($this->filter_numeric($uId));
		$ids = '';
		$sqlR = parent::selectData(TABLE_USER,"u_login_id","user_referrer = '".$uId."' and user_status<>'Deleted'");
		while($resR =  mysqli_fetch_array($sqlR))
		{
			$ids .= $resR['u_login_id'].",";
		}
		$ids = rtrim($ids,",");
		if($ids) return $ids;
		else return "";
	}
	
	function aff_prelaunch_comm($aff_id,$dt)
	{
		$aff_id  = $this->filter_mysql($this->filter_numeric($aff_id));
		$ref_ids = explode(",",$this->get_all_referrals($aff_id));		
		$totalRE = 0;
		for($i=0;$ref_ids[$i]!='';$i++)
		{
			$totalRE += $this->total_asimi_minted_from_wallet($ref_ids[$i]);
		}
		return round($totalRE*.1,4);
		
	}
	
	function aff_total_asimi_comm($aff_id)
	{
		$aff_id  = $this->filter_mysql($this->filter_numeric($aff_id));
		$totalAC = 0;
		$sqlAC = parent::selectData(TABLE_REFERRAL_EARNING,"sum(rear_asimi) as total_aff_asimi","aff_id='".$aff_id."' and rear_status='Active'",1);
		$totalAC = $sqlAC['total_aff_asimi'];

		if($totalAC) return $totalAC;
		else return 0;
	}
	

	
	
	
	function aff_total_usd_sales($aff_id)
	{
		$aff_id  = $this->filter_mysql($this->filter_numeric($aff_id));
		/*$totalUS = 0;
		$sqlACS = parent::selectData(TABLE_REFERRAL_EARNING." re, ".TABLE_ORDER." od","sum(od.order_total) as totalsales","re.aff_id='".$aff_id."' and re.rear_status='Active' and re.order_id=od.order_id and od.order_pstatus='p'",1);		
		$totalUS = $sqlACS['totalsales'];

		if($totalUS) return $totalUS;
		else return 0;*/
		
		$sqlACS = parent::selectData(TABLE_ORDER,"sum(order_total) as totalsales","order_pstatus='p' and user_id in (select u_login_id from ".TABLE_USER." where user_referrer='".$aff_id."')",1);	
		$totalUS = $sqlACS['totalsales'];
		if($totalUS) return $totalUS;
		else return 0;
	}
	
	function aff_total_usd_sales_mint($aff_id)
	{		
		$aff_id  = $this->filter_mysql($this->filter_numeric($aff_id));
		$sqlACS = parent::selectData(TABLE_ORDER." o, ".TABLE_ORDER_DETAILS." od","sum(od_total) as totalsales","o.order_pstatus='p' and o.order_id=od.order_id and od.pack_id='5' and o.user_id in (select u_login_id from ".TABLE_USER." where user_referrer='".$aff_id."')",1);	
		$totalUS = $sqlACS['totalsales'];
		if($totalUS) return $totalUS;
		else return 0;
	}
	
	function aff_total_usd_sales_adv($aff_id)
	{		
		$aff_id  = $this->filter_mysql($this->filter_numeric($aff_id));

		
		$sqlACS = parent::selectData(TABLE_ORDER." o, ".TABLE_ORDER_DETAILS." od","sum(od_total) as totalsales","o.order_pstatus='p' and o.order_id=od.order_id and od.pack_id<>'5' and o.user_id in (select u_login_id from ".TABLE_USER." where user_referrer='".$aff_id."')",1);		
		$totalUS = $sqlACS['totalsales'];
		if($totalUS) return $totalUS;
		else return 0;
	}
	
	
	
	function aff_sales_by_date($userId,$dt)
	{
		$userId    = $this->filter_mysql($this->filter_numeric($userId));
		$dt   	   = $this->filter_mysql($dt);
		
		$totalUS = 0;
		$sqlACS = parent::selectData(TABLE_REFERRAL_EARNING." re, ".TABLE_ORDER." od","sum(od.order_total) as totalsales","re.aff_id='".$userId."' and re.rear_status='Active' and re.order_id=od.order_id and od.order_pstatus='p' and re.rear_date='".$dt."'",1);
		$totalUS = $sqlACS['totalsales'];
		
		if($totalUS) return $totalUS;
		else return 0;
	}
	
	function sq_ban_ads_imp_unassigned($user_id)
	{	
		$user_id   = $this->filter_mysql($this->filter_numeric($user_id));
		$totalSAP  = 0;	 
		$getAvgImp = 0; 
		$sqlimp = parent::selectData(TABLE_IMPRESSION_CREDIT,"sum(ic_impression) as total_imp","ic_imp_type='bansqr' and user_id='".$user_id."' and ic_status='Active'",1);
		$totalSAP = $sqlimp['total_imp'];		
		
		$sqlA = parent::selectData(TABLE_BANNER_ADS,"sum(banad_impression) as total_ban_imp","banad_type_size='square' AND banad_geo_impression ='0' and user_id='".$user_id."' and banad_status <>'Deleted'",1);	
		
		$sqlAG = parent::selectData(TABLE_BANNER_ADS,"sum(banad_geo_impression) as total_ban_geo_imp","banad_type_size='square' AND banad_geo_impression !='0' and user_id ='".$user_id."' and banad_status <>'Deleted'",1);		 
		$totalSAP = $totalSAP - ($sqlA['total_ban_imp']+$sqlAG['total_ban_geo_imp']);
		 
		$total_banad_imp_view = 0;
		$sqlB = parent::selectData(TABLE_BANNER_ADS, "SUM(banad_view_imp_ach) AS total_banad_imp_view","banad_type_size='square' and user_id = '".$user_id."' AND banad_geo_impression ='0' AND banad_status = 'Deleted'", 1);
		$total_banad_imp_view = $sqlB['total_banad_imp_view'];
		
		$sqlBG = parent::selectData(TABLE_BANNER_ADS, "SUM(banad_view_imp_ach) AS total_banad_imp_view,SUM(banad_impression) AS tot_banad_imp,SUM(banad_geo_impression) AS tot_banad_geo_imp", "banad_type_size='square' and user_id = '".$user_id."' AND banad_geo_impression !='0' AND banad_status= 'Deleted'", 1);
		if(!empty($sqlBG['tot_banad_geo_imp']))  $getAvgImp  = ($sqlBG['tot_banad_imp']-$sqlBG['total_banad_imp_view'])*(floor($sqlBG['tot_banad_geo_imp']/$sqlBG['tot_banad_imp']));
		
		$totalAMVP = $totalSAP -($total_banad_imp_view+$getAvgImp); 
		if ($totalAMVP < 0 || $totalAMVP == '') return 0;
		else return $totalAMVP;
		
		 
	}
	
	function hr_ban_ads_imp_unassigned($user_id) 
	{
		$user_id   = $this->filter_mysql($this->filter_numeric($user_id));
		$totalSAP  = 0;	 
		$getAvgImp = 0; 
		$sqlimp = parent::selectData(TABLE_IMPRESSION_CREDIT,"sum(ic_impression) as total_imp","ic_imp_type='banhrz' and user_id='".$user_id."' and ic_status='Active'",1);
		$totalSAP = $sqlimp['total_imp'];		
		
		$sqlA = parent::selectData(TABLE_BANNER_ADS,"sum(banad_impression) as total_ban_imp","banad_type_size='horizontal' AND banad_geo_impression ='0' and user_id='".$user_id."' and banad_status <>'Deleted'",1);	
		
		$sqlAG = parent::selectData(TABLE_BANNER_ADS,"sum(banad_geo_impression) as total_ban_geo_imp","banad_type_size='horizontal' AND banad_geo_impression !='0' and user_id ='".$user_id."' and banad_status <>'Deleted'",1);		 
		$totalSAP = $totalSAP - ($sqlA['total_ban_imp']+$sqlAG['total_ban_geo_imp']);
		 
		$total_banad_imp_view = 0;
		$sqlB = parent::selectData(TABLE_BANNER_ADS, "SUM(banad_view_imp_ach) AS total_banad_imp_view","banad_type_size='horizontal' and user_id = '".$user_id."' AND banad_geo_impression ='0' AND banad_status = 'Deleted'", 1);
		$total_banad_imp_view = $sqlB['total_banad_imp_view'];
		
		$sqlBG = parent::selectData(TABLE_BANNER_ADS, "SUM(banad_view_imp_ach) AS total_banad_imp_view,SUM(banad_impression) AS tot_banad_imp,SUM(banad_geo_impression) AS tot_banad_geo_imp", "banad_type_size='horizontal' and user_id = '".$user_id."' AND banad_geo_impression !='0' AND banad_status= 'Deleted'", 1);
		if(!empty($sqlBG['tot_banad_geo_imp']))  $getAvgImp  = ($sqlBG['tot_banad_imp']-$sqlBG['total_banad_imp_view'])*(floor($sqlBG['tot_banad_geo_imp']/$sqlBG['tot_banad_imp']));
		
		$totalAMVP = $totalSAP -($total_banad_imp_view+$getAvgImp); 
		if ($totalAMVP < 0 || $totalAMVP == '') return 0;
		else return $totalAMVP;
		
	}	
	
	function sq_ban_ads_imp_unassigned_bk($user_id)
	{	
		$user_id   = $this->filter_mysql($this->filter_numeric($user_id));
		$totalSAP  = 0;	 
		$sqlimp = parent::selectData(TABLE_IMPRESSION_CREDIT,"sum(ic_impression) as total_imp","ic_imp_type='bansqr' and user_id='".$user_id."' and ic_status='Active'",1);
		$totalSAP = $sqlimp['total_imp'];		
		
		$sqlA = parent::selectData(TABLE_BANNER_ADS,"sum(banad_impression) as total_ban_imp","banad_type_size='square' and user_id='".$user_id."' and banad_status <>'Deleted'",1);		 
		$totalSAP = $totalSAP - $sqlA['total_ban_imp'];
		
		
		$sqlB = parent::selectData(TABLE_BANNER_ADS,"","banad_type_size='square' and user_id='".$user_id."' and banad_status ='Deleted'");	 
		while($resB = mysqli_fetch_array($sqlB))
		{
			/*if($resB['banad_view_imp_ach'] <= $resB['banad_impression'])
			{		
				//$totalSAP += $resB['banad_impression']-$resB['banad_view_imp_ach'];
				$totalSAP = $totalSAP - $resB['banad_view_imp_ach'];
			}	*/	
			$totalSAP = $totalSAP - $resB['banad_view_imp_ach'];
		}		
		
		 
		if($totalSAP < 0 || $totalSAP =='') return 0;
		else return $totalSAP;
	}
	
	function hr_ban_ads_imp_unassigned_bk($user_id)
	{
		$user_id   = $this->filter_mysql($this->filter_numeric($user_id));
		$totalSAP = 0;
		 
		$sqlimp = parent::selectData(TABLE_IMPRESSION_CREDIT,"sum(ic_impression) as total_ic_imp","ic_imp_type='banhrz' and user_id='".$user_id."' and ic_status='Active'",1);	 
		$totalSAP = $sqlimp['total_ic_imp'];

		$sqlA = parent::selectData(TABLE_BANNER_ADS,"sum(banad_impression) as total_banad_imp","banad_type_size='horizontal' and user_id='".$user_id."' and banad_status <>'Deleted'",1);
		$totalSAP = $totalSAP - $sqlA['total_banad_imp'];

		
		$sqlB = parent::selectData(TABLE_BANNER_ADS,"","banad_type_size='horizontal' and user_id='".$user_id."' and banad_status ='Deleted'");	 
		while($resB = mysqli_fetch_array($sqlB))
		{
			/*if($resB['banad_view_imp_ach'] <= $resB['banad_impression'])
			{
				//$totalSAP += $resB['banad_impression']-$resB['banad_view_imp_ach'];
				$totalSAP = $totalSAP - $resB['banad_view_imp_ach'];
			}	*/		
			$totalSAP = $totalSAP - $resB['banad_view_imp_ach'];
		}		
		 
		if($totalSAP < 0 || $totalSAP =='') return 0;
		else return $totalSAP;
		
		
	}
	
	function ad_minter_views_unassigned($user_id)
	{
		$user_id   		= $this->filter_mysql($this->filter_numeric($user_id));
		$totalAMVP 		= 0; 
		$getAvgImp  	= 0; 
		
		$sqlimp = parent::selectData(TABLE_IMPRESSION_CREDIT, "SUM(ic_impression) AS total_ic_imp", "ic_imp_type = 'minter' AND user_id = '".$user_id."' AND ic_status = 'Active'", 1);
		$totalIC = $sqlimp['total_ic_imp']; 
		
		$sqlA = parent::selectData(TABLE_AD_DIRECTORY_LISTING, "SUM(adl_impression) AS total_adl_imp", "user_id = '".$user_id."' AND adl_geo_impression ='0' AND adl_status != 'Deleted'", 1);
		$get_adl_imp = $sqlA['total_adl_imp'];
		
		$sqlAG = parent::selectData(TABLE_AD_DIRECTORY_LISTING, "SUM(adl_geo_impression) AS tot_adl_geo_imp", "user_id = '".$user_id."' and adl_geo_impression !='0' AND adl_status != 'Deleted'", 1);
		$get_adl_geo_imp = $sqlAG['tot_adl_geo_imp']; 
		$get_totalAdlImp = $get_adl_imp+$get_adl_geo_imp;
		
		$totalAMVI = $totalIC - $get_totalAdlImp;
		
		$total_adl_imp_view = 0;
		$sqlB = parent::selectData(TABLE_AD_DIRECTORY_LISTING, "SUM(adl_impression_view) AS total_adl_imp_view", "user_id = '".$user_id."' AND adl_geo_impression ='0' AND adl_status = 'Deleted'", 1);
		$total_adl_imp_view = $sqlB['total_adl_imp_view'];
		
		$sqlBG = parent::selectData(TABLE_AD_DIRECTORY_LISTING, "SUM(adl_impression_view) AS total_adl_imp_view,SUM(adl_impression) AS tot_adl_imp,SUM(adl_geo_impression) AS tot_adl_geo_imp", "user_id = '".$user_id."' AND adl_geo_impression !='0' AND adl_status = 'Deleted'", 1);
		if(!empty($sqlBG['tot_adl_geo_imp']))  $getAvgImp  = ($sqlBG['tot_adl_imp']-$sqlBG['total_adl_imp_view'])*(floor($sqlBG['tot_adl_geo_imp']/$sqlBG['tot_adl_imp']));
		 
		
		$totalAMVP = $totalAMVI -($total_adl_imp_view+$getAvgImp); 
		if ($totalAMVP < 0 || $totalAMVP == '') return 0;
		else return $totalAMVP;
		 
	}
	
	
	function ad_minter_views_unassigned_bk($user_id)
	{
		$user_id   = $this->filter_mysql($this->filter_numeric($user_id));
		$totalAMVP = 0;
		/*
		$sqlS = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","od.pack_quan as totalp","o.order_id=od.order_id and o.order_pstatus='p' and od.pack_id='1' and od.user_id='".$user_id."'");
		while($resS = mysqli_fetch_array($sqlS))
		{
			$packD = parent::selectData(TABLE_PACKAGE,"","pack_status='Active' and pack_id='1'",1);
			$totalAMVP += $resS['totalp']*$packD['pack_ad_minter_adver_view'];
		}
		
		$sqlSS = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","od.pack_quan as totalpp","o.order_id=od.order_id and o.order_pstatus='p' and od.pack_id='4' and od.user_id='".$user_id."'");
		while($resSS = mysqli_fetch_array($sqlSS))
		{
			$packDD = parent::selectData(TABLE_PACKAGE,"","pack_status='Active' and pack_id='4'",1);
			$totalAMVP += $resSS['totalpp']*$packDD['pack_ad_minter_adver_view'];
		}
		*/
		
		$sqlimp = parent::selectData(TABLE_IMPRESSION_CREDIT, "SUM(ic_impression) AS total_ic_imp", "ic_imp_type = 'minter' AND user_id = '".$user_id."' AND ic_status = 'Active'", 1);
		$totalAMVP = $sqlimp['total_ic_imp'];
		$sqlA = parent::selectData(TABLE_AD_DIRECTORY_LISTING, "SUM(adl_impression) AS total_adl_imp", "user_id = '".$user_id."' AND adl_status != 'Deleted'", 1);
		$totalAMVP = $totalAMVP - $sqlA['total_adl_imp'];
		$sqlB = parent::selectData(TABLE_AD_DIRECTORY_LISTING, "SUM(adl_impression_view) AS total_adl_imp_view", "user_id = '".$user_id."' AND adl_status = 'Deleted'", 1);
		$totalAMVP = $totalAMVP - $sqlB['total_adl_imp_view'];
		if ($totalAMVP < 0 || $totalAMVP == '') return 0;
		else return $totalAMVP;
	}
		
 
	
	function user_ban_ad_clk_achieved($user_id)
	{
		$user_id   = $this->filter_mysql($this->filter_numeric($user_id));
		$sqlMB = parent::selectData(TABLE_BANNER_ADS,"SUM(banad_view_imp) AS total","user_id='".$user_id."'");
		$resMB = mysqli_fetch_array($sqlMB);
		return $resMB['total'];		
	}
	
	function user_ban_ad_imp_deliver($user_id)
	{
		$user_id   = $this->filter_mysql($this->filter_numeric($user_id));
		$myads = "";
		$clkC = 0;
		$sqlMB = parent::selectData(TABLE_BANNER_ADS,"banad_id","user_id='".$user_id."'  and banad_status='Active'");
		while($resMB = mysqli_fetch_array($sqlMB))
		{
			$sqlMBC = parent::selectData(TABLE_BANNER_AD_MEMBER_VIEW,"banad_id_imp","banad_id_imp like '%,".$resMB['banad_id'].",%'");
			while($resMBC = mysqli_fetch_array($sqlMBC))
			{
				//$clkC += substr_count($resMBC['banad_id_imp'],','.$resMB['banad_id'].',');
				
				$data = explode(",",trim($resMBC['banad_id_imp'],","));				
				$clkC += count(array_keys($data,$resMB['banad_id']));
			}
		}
		return $clkC;		
	}
	
	function user_ad_minter_views_deliver($user_id)
	{
		$user_id   = $this->filter_mysql($this->filter_numeric($user_id));

		$myads = "";
		$imp = 0;
		$sqlMB = parent::selectData(TABLE_AD_DIRECTORY_LISTING,"adl_id","user_id='".$user_id."'  and adl_status='Active'");
		while($resMB = mysqli_fetch_array($sqlMB))
		{
			$sqlMBC = parent::selectData(TABLE_AD_MINTER_MEMBER_VIEW,"adl_id","adl_id like '%,".$resMB['adl_id'].",%'");
			while($resMBC = mysqli_fetch_array($sqlMBC))
			{
				$data = explode(",",trim($resMBC['adl_id'],",")); 
				$imp += count(array_keys($data,$resMB['adl_id']));
				
			}
			
			$sqlNM = parent::selectData(TABLE_MEMBER_NEW_AD_MINTER_VIEW,"adl_id","adl_id like '%,".$resMB['adl_id'].",%'");
			while($resNM = mysqli_fetch_array($sqlNM))
			{
				$data = explode(",",trim($resNM['adl_id'],",")); 
				$imp += count(array_keys($data,$resMB['adl_id']));
				
			}

		}
		
		return $imp;	
	}
	
	function search_ad_count($fullStr,$keyStr)
	{
		$count = 0;
		$keyAr = explode(",",$keyStr);
		for($i=0;$keyAr[$i]!='';$i++)
		{
			//$count += substr_count($fullStr,','.$keyAr[$i].',');
			
			$data = explode(",",trim($fullStr,","));
			$count += count(array_keys($data,$keyAr[$i]));
		}
		return $count;	
	}
	
	function minter_ads_viewed_by_date($userId,$dt)
	{
		$userId   = $this->filter_mysql($this->filter_numeric($userId));
		$dt        = $this->filter_mysql($dt);
		$myads = "";
		$imp = 0;
		$sqlMB = parent::selectData(TABLE_AD_DIRECTORY_LISTING,"adl_id","user_id='".$userId."'  and adl_status='Active'");
		while($resMB = mysqli_fetch_array($sqlMB))
		{
			$sqlMBC = parent::selectData(TABLE_AD_MINTER_MEMBER_VIEW,"adl_id","adl_id like '%,".$resMB['adl_id'].",%' and ammv_created_dt='".$dt."'");
			while($resMBC = mysqli_fetch_array($sqlMBC))
			{
				//$imp += substr_count($resMBC['adl_id'],','.$resMB['adl_id'].',');
				$data = explode(",",trim($resMBC['adl_id'],","));
				$imp += count(array_keys($data,$resMB['adl_id']));
			}
			
			$sqlNM = parent::selectData(TABLE_MEMBER_NEW_AD_MINTER_VIEW,"adl_id","adl_id like '%,".$resMB['adl_id'].",%' and mnmv_created_dt='".$dt."'");
			while($resNM = mysqli_fetch_array($sqlNM))
			{
				$data = explode(",",trim($resNM['adl_id'],",")); 
				$imp += count(array_keys($data,$resMB['adl_id']));
				
			}
			
		}
		return $imp;
	}
	
	 
	
	function earned_minting_view_by_date($user_id,$dt)
	{
		$user_id   = $this->filter_mysql($this->filter_numeric($user_id));
		$dt        = $this->filter_mysql($dt);
	
		$earned_Mint = 0;
		$today = date("Y-m-d");
		
		/*$sqlSA = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","sum(pack_quan) as boughtA","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id='4' and od.od_status='Active' and od.user_id='".$user_id."' and od.od_pack_val_days > od.od_pack_val_used",1);
		
		$mintingArr = parent::selectData(TABLE_ADVER_PACK_MINING_BENEFIT,"","apmb_status='Active' and apmb_ad_blocks_owned <= '".$sqlSA['boughtA']."' order by apmb_id desc",1);	
		$mintingMinVal = $mintingArr['apmb_ad_view_req_min'];
		$mintingMaxVal = $mintingArr['apmb_ad_view_req_max'];*/
		
		//$sqlSM = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","sum(pack_quan) as boughtM","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id='5' and od.od_status='Active' and od.user_id='".$user_id."' and od.od_pack_val_days > od.od_pack_val_used",1);
		//$mintBoostArr = parent::selectData(TABLE_MINING_BOOSTER_PACK_BENEFIT,"","mbpb_status='Active' and mbpb_ad_block_owned <= '".$sqlSM['boughtM']."' order by mbpb_id desc",1); 
		//$mintBoostMinVal = $mintBoostArr['mbpb_ad_view_req_min'];
		//$mintBoostMaxVal = $mintBoostArr['mbpb_ad_view_req_max'];
		
		$adMintViewdToday = $this->member_count_minting_ads_viewed_by_date($user_id,$dt);
		
		//if($adMintViewdToday<$mintingMinVal) $earned_Mint = 0;
		//else if ($adMintViewdToday>=$mintingMinVal && $adMintViewdToday<=$mintingMaxVal )  $earned_Mint = $adMintViewdToday;
		//else if ($adMintViewdToday<($mintingMaxVal+$mintBoostMinVal)) $earned_Mint = $mintingMaxVal;
		//else if ($adMintViewdToday>=($mintingMaxVal+$mintBoostMinVal) && $adMintViewdToday<=($mintingMaxVal+$mintBoostMaxVal) ) $earned_Mint = $adMintViewdToday;
		//if ($adMintViewdToday>=($mintingMaxVal+$mintBoostMinVal)) $earned_Mint = $adMintViewdToday;
		$earned_Mint = $adMintViewdToday;
		//return $mintingMinVal."||".$mintingMaxVal."||".$adMintViewdToday."||".$earned_Mint;
		
		
		return $earned_Mint;
	}
	
	function earned_minting_booster($user_id,$dt)
	{
		$user_id   = $this->filter_mysql($this->filter_numeric($user_id));
		$dt        = $this->filter_mysql($dt);
		
		$earned_booster_Mint = 0;
		$today = date("Y-m-d");
		
		/*$sqlSA = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","sum(pack_quan) as boughtA","o.order_id=od.order_id and o.order_pstatus='p' and od.pack_id='4' and od.user_id='".$user_id."' and od.od_pack_val_days > od.od_pack_val_used",1);
		
		$mintingArr = parent::selectData(TABLE_ADVER_PACK_MINING_BENEFIT,"","apmb_status='Active' and apmb_ad_blocks_owned <= '".$sqlSA['boughtA']."' order by apmb_id desc",1);	
		$mintingMinVal = $mintingArr['apmb_ad_view_req_min'];
		$mintingMaxVal = $mintingArr['apmb_ad_view_req_max'];*/
		$mintingMinVal = 0;
		$mintingMaxVal = 0;
		// and od.od_pack_val_days > od.od_pack_val_used		
		$sqlSM = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","sum(pack_quan) as boughtM","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id='5' and od.od_status='Active' and od.user_id='".$this->filter_mysql($user_id)."' and od.od_pack_validity >= '".$today."'",1);
		$mintBoostArr = parent::selectData(TABLE_MINING_BOOSTER_PACK_BENEFIT,"","mbpb_status='Active' and mbpb_ad_block_owned <= '".$sqlSM['boughtM']."' order by mbpb_id desc",1); 
		$mintBoostMinVal = $mintBoostArr['mbpb_ad_view_req_min'];
		$mintBoostMaxVal = $mintBoostArr['mbpb_ad_view_req_max'];
		
		$adMintViewdToday = $this->member_count_minting_ads_viewed_by_date($user_id,$dt);
		
		//if($adMintViewdToday<$mintingMinVal) $earned_booster_Mint = 0;
		//else if($adMintViewdToday>=$mintingMinVal && $adMintViewdToday<=$mintingMaxVal )  $earned_booster_Mint = 0;
		//else if ($adMintViewdToday<($mintingMaxVal+$mintBoostMinVal)) $earned_booster_Mint = 0;
		//else if ($adMintViewdToday>=($mintingMaxVal+$mintBoostMinVal) && $adMintViewdToday<=($mintingMaxVal+$mintBoostMaxVal) ) $earned_booster_Mint = 1;
		if ($adMintViewdToday>=($mintingMaxVal+$mintBoostMinVal)) $earned_booster_Mint = $adMintViewdToday;
		$earning = 0;
		/*if($earned_booster_Mint)
		{
			$sqlMBA = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","sum(od_mem_cashback_token) as memctoken","o.order_id=od.order_id and o.order_pstatus='p' and od.pack_id='5' and od.user_id='".$user_id."' and od.od_pack_validity >= '".$dt."'",1);
			$earning = number_format($sqlMBA['memctoken']/365,4);
			
		}*/
		//return $earning;
		return ($adMintViewdToday-1);
	}
	
	function minting_booster_cashback($user_id,$dt,$earnC)
	{
		$user_id   = $this->filter_mysql($this->filter_numeric($user_id));
		$dt        = $this->filter_mysql($dt);
		$today 	   = date("Y-m-d");
		$m         = 1;
		$totalBE   = 0;
		$totalC    = $earnC;
		if($totalC)
		{
			$sqlMBA = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","od.pack_quan,od.od_mem_cashback_token,od.od_id,od.user_id,od.od_pack_val_used,od.od_pack_val_days,od.od_pack_validity","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id='5' and od.od_status='Active' and od.user_id='".$user_id."' and od.od_pack_validity >= '".$dt."'","","o.order_id asc");
			while($resMBA = mysqli_fetch_array($sqlMBA))
			{
				if($resMBA['pack_quan']<=$totalC && $totalC>0)
				{
					$totalC  =  $totalC - $resMBA['pack_quan'];
					$totalBE +=  $resMBA['od_mem_cashback_token']/365;
					
					$sqlmbcS = parent::selectData(TABLE_MB_CASHBACK,"mbc_id,od_id","od_id='".$resMBA['od_id']."' and mbc_date='".$dt."'",1);
					if($sqlmbcS['mbc_id']=='')
					{
						$mcbI['od_id'] 			 = $resMBA['od_id'];
						$mcbI['user_id'] 		 = $resMBA['user_id'];
						$mcbI['mbc_cashback'] 	 = $resMBA['od_mem_cashback_token']/365;
						$mcbI['mbc_date'] 		 = $dt;
						$mcbI['mbc_udate'] 		 = date("Y-m-d");
						$mcbI['mbc_utime']		 = date("H:i:s");
							
						parent::insertData(TABLE_MB_CASHBACK,$mcbI);		
						
						$arrODU['od_pack_val_used']	= $resMBA['od_pack_val_used']+1;
						//echo $arrODU['od_pack_val_used']."||".$resMBA['od_id'];
						//echo "<br>";						
						//parent::updateData(TABLE_ORDER_DETAILS,$arrODU,"od_id='".$resMBA['od_id']."'");	
						parent::quickIncrement(TABLE_ORDER_DETAILS,"od_pack_val_used","od_id='".$resMBA['od_id']."'");	
					}	
				}
				else if($resMBA['pack_quan']>$totalC  && $totalC>0)
				{
					$rQty = $totalC;
					$totalC = 0;
					$totalBE +=  ($resMBA['od_mem_cashback_token']*$rQty)/($resMBA['pack_quan']*365);
					
					$sqlmbcS = parent::selectData(TABLE_MB_CASHBACK,"mbc_id,od_id","od_id='".$resMBA['od_id']."' and mbc_date='".$dt."'",1);
					if($sqlmbcS['od_id']=='')
					{
						$mcbI['od_id'] 			 = $resMBA['od_id'];
						$mcbI['user_id'] 		 = $resMBA['user_id'];
						$mcbI['mbc_cashback'] 	 = ($resMBA['od_mem_cashback_token']*$rQty)/($resMBA['pack_quan']*365);
						$mcbI['mbc_date'] 		 = $dt;
						$mcbI['mbc_udate'] 		 = date("Y-m-d");
						$mcbI['mbc_utime']	     = date("H:i:s");
							
						parent::insertData(TABLE_MB_CASHBACK,$mcbI);
						
						$arrODU['od_pack_val_used']	= $resMBA['od_pack_val_used']+1;
						$eDay = $resMBA['od_pack_val_days']-$resMBA['od_pack_val_used'];
						$arrODU['od_pack_validity']	= date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$eDay,date("Y")));
						parent::updateData(TABLE_ORDER_DETAILS,$arrODU,"od_id='".$resMBA['od_id']."'");	
						
					}	
				}
			}
		}	
		return $totalBE;
	}
	
	function earned_member_cashback($user_id,$dt)
	{
		$user_id   = $this->filter_mysql($this->filter_numeric($user_id));
		$earningMC = 0;
		$today  = date("Y-m-d");	
		$sqlMBA = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","sum(od_mem_cashback_token) as memctoken","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id='4' and od.od_status='Active' and od.user_id='".$user_id."' and od.od_pack_validity >= '".$today."'",1);
		$earningMC = number_format($sqlMBA['memctoken']/365,4);			

		return $earningMC;
	}
	
	
	function total_ext_share_by_month($mon,$yr)
	{
		$startDate = date("Y-m-d",mktime(0,0,0,$mon,1,$yr));
		$endDate = date("Y-m-t",mktime(0,0,0,$mon,1,$yr));
		
		$sqlTMP = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","sum(od.od_minting_token) as totalEST","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id='4' and od.od_status='Active' and o.order_date>='".$startDate."' and o.order_date<='".$endDate."'",1);
		if($sqlTMP['totalEST']>0) return $sqlTMP['totalEST'];
		else return 0;
	}
	
	function member_ext_share_by_month($user_id,$mon,$yr)
	{ 
		$user_id   = $this->filter_mysql($this->filter_numeric($user_id));
		$startDate = date("Y-m-d",mktime(0,0,0,$mon,1,$yr));
		$endDate   = date("Y-m-t",mktime(0,0,0,$mon,1,$yr));
		
		$sqlTMP = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","sum(od.pack_quan) as totalp","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id='4' and od.od_status='Active' and od.user_id='".$user_id."'  and o.order_date>='".$startDate."' and o.order_date<='".$endDate."'",1);
		if($sqlTMP['totalp']>0) return $sqlTMP['totalp'];
		else return 0;
	}
	
	function get_total_earned_minting_view($user_id)
	{ 
		$user_id  = $this->filter_mysql($this->filter_numeric($user_id));	
		$adlIds = 0;
		$today  = date("Y-m-d");
		$extraw .= "and ammv_created_dt='".$today."'"; 
		$sqlU = parent::selectData(TABLE_AD_MINTER_MEMBER_VIEW,"adl_id","ammv_status='Active'".$extraw." order by ammv_id asc");
		while($resU = mysqli_fetch_array($sqlU)) 
		{
			$totalTDV = explode(",",trim($resU['adl_id'],","));
			$adlIds += count($totalTDV);
		}
		
		$ad_view_for_individual = $this->member_count_minting_ads_viewed_today($user_id);		
		$each_day_token = 17438;		
		$token_pre_share = $each_day_token/$adlIds;
		$member_reward = $token_pre_share*$ad_view_for_individual;
		
		return round($member_reward,4);
 
	}
	
	function total_asimi_minted($user_id)
	{
		$user_id  = $this->filter_mysql($this->filter_numeric($user_id));
		$calDay = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
		$totalAE = 0;
		$sqlTAM = parent::selectData(TABLE_MEMBER_EARNING,"( sum(me_share_coin)+sum(me_bshare_coin)+sum(me_eshare_coin)+sum(me_mcshare_coin)+sum(me_aff_coin)) as total_asimi_mint","user_id='".$user_id."' and me_created<='".$calDay."'",1);		
		//$totalAE += $resTAM['me_share_coin']+$resTAM['me_bshare_coin']+$resTAM['me_eshare_coin']+$resTAM['me_mcshare_coin']+$resTAM['me_aff_coin'];
		$totalAE = $sqlTAM['total_asimi_mint'];
		
		return $totalAE;
	} 
	
	function total_asimi_minted_from_wallet($user_id)
	{
		$user_id  = $this->filter_mysql($this->filter_numeric($user_id));
		//$calDay = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
		$totalAE = 0;
		$sqlU = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_asimi","wall_status='Active' and (wall_type='e' or wall_type='me' or wall_type='rs') and wall_pstatus='p' and user_id='".$user_id."'",1);
		$ucredit = $sqlU['total_asimi'];
		
		return $ucredit;
	} 
	
	
	function get_total_asimi_minted_by_date($user_id,$dt)
	{
		$user_id  = $this->filter_mysql($this->filter_numeric($user_id));
		$dt 	  = $this->filter_mysql($dt);
		$totalAE = 0;
		$sqlTAM = parent::selectData(TABLE_MEMBER_EARNING,"( sum(me_share_coin)+sum(me_bshare_coin)+sum(me_eshare_coin)+sum(me_mcshare_coin)+sum(me_aff_coin)) as total_asimi_mint","user_id='".$user_id."' and me_created='".$dt."'",1);
		$totalAE = $sqlTAM['total_asimi_mint'];

		return $totalAE;
	} 
	
	function get_total_minting_asimi_by_date($userId,$dt)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		$dt 	 = $this->filter_mysql($dt);
		$totalAE = 0;
		$sqlTAM = parent::selectData(TABLE_MEMBER_EARNING,"( sum(me_share_coin)+sum(me_bshare_coin)) as total_asimi_mint","user_id='".$userId."' and me_created='".$dt."'",1);
		$totalAE = $sqlTAM['total_asimi_mint'];

		return $totalAE;
	}
	
	 
	function get_asimi_increse_balance_by_date($userId,$date)
	{  			
			$userId  = $this->filter_mysql($this->filter_numeric($userId));
			$date  = $this->filter_mysql($date);
			
			$ucredit = "";
			$sqlU = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_asimi","wall_status='Active' and (wall_type='p' or wall_type='vep') and wall_pstatus='p' and user_id='".$userId."' and wall_created < '".$date."'",1);
			$ucredit = $sqlU['total_asimi'];				
			  
			$balance = $ucredit-$bcredit;
			$asimTotal = 	number_format((float)$balance, 4, '.', '');	
			return($asimTotal);
	}
	function get_asimi_decrese_balance_by_date($userId,$date)
	{  			
			$userId  = $this->filter_mysql($this->filter_numeric($userId));
			$date  = $this->filter_mysql($date);
			
			$ucredit = "";
			$sqlU = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_asimi","wall_status='Active' and (wall_type='e' or wall_type='me' or wall_type='rs') and wall_pstatus='p' and user_id='".$userId."' and wall_created < '".$date."'",1);
			$ucredit = $sqlU['total_asimi'];		
			  
			$balance = $ucredit-$bcredit;
				
			$asimTotal = 	number_format((float)$balance, 4, '.', '');	
			return($asimTotal);
	}	
	
	
	
	
	function get_asimi_balance_by_date($date)
	{  			
			$ucredit = "";
			// and user_id='".$userId."'
			//wall_type='d' or wall_type='e' or wall_type='j' or wall_type='r' or wall_type='a' or wall_type='ve' or wall_type='vea' or wall_type='rw' or wall_type='rwa'
			$sqlU = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_asimi","wall_status='Active' and (wall_type='d' or wall_type='e') and wall_pstatus='p' and wall_created < '".$this->filter_mysql($date)."'",1);
			$ucredit = $sqlU['total_asimi'];
			
						
			$uwsql = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_used_asimi","wall_status='Active' and (wall_type='p' or wall_type='vep' or wall_type='w' or wall_type='mw') and wall_pstatus='p' and wall_created < '".$this->filter_mysql($date)."'",1);
			$bcredit = $uwsql['total_used_asimi'];			 
			 	 
			$balance = $ucredit-$bcredit;
				
			$asimTotal = 	number_format((float)$balance, 4, '.', '');	
			return($asimTotal);
	}
	
	function banner_impression_alloc($banId)
	{
		   $banId  = $this->filter_mysql($this->filter_numeric($banId));
		   $total_alloc = 0;
		   $lists=parent::selectData(TABLE_BANNER_IMPRESSION,"","banimp_status='Active' and banad_id='".$this->filter_mysql($banId)."'","");

			while($data=mysqli_fetch_array($lists)) {
			if( $data['banimp_add_type']=='A'){				
				$total_alloc += $data['banimp_impression'];
			}

			if($data['banimp_add_type']=='D'){				
				$total_alloc -= $data['banimp_impression'];
			}			
		}
		return $total_alloc;
	}	
	
	function banner_impression_served($banId)
	{
		$banId  = $this->filter_mysql($this->filter_numeric($banId));
		$str = ",".$banId.",";
		$sqlU = parent::selectData(TABLE_BANNER_AD_MEMBER_VIEW,"banad_id_imp","bamv_status='Active' and banad_id like '%,".$this->filter_mysql($banId).",%'","","bamv_id asc");
		while($resU = mysqli_fetch_array($sqlU))
		{
			//$totalBAC += substr_count($resU['banad_id'],$str);
			$data = explode(",",trim($resU['banad_id_imp'],","));
			$totalBAC += count(array_keys($data,$banId));
		}
		
		if($totalBAC) return $totalBAC;
		else return 0;
	}
	
	function mailchimp_subscription($fullname,$email)
	{
	
			$name = explode(" ",$fullname);
			$fname = $name[0];
			$lname = '';
			for($i=1;$name[$i]!='';$i++)
			{
				$lname .= $name[$i]." ";
			}
			$lname = trim($lname);
			$msg = '';
			if(!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL) === false){
			// MailChimp API credentials
			$apiKey = '282634b825d69b988d6540db2356e55e-us19';
			$listID = '50cae7d896';
			$dataCenter = 'us19';
			
			// MailChimp API URL
			$memberID = md5(strtolower($email));
			$dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
			$url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listID . '/members/' . $memberID;
			
			// member information
			$json = json_encode([
				'email_address' => $email,
				'status'        => 'subscribed',
				'merge_fields'  => [
					'FNAME'     => $fname,
					'LNAME'     => $lname
				]
			]);
			
			
			
			// send a HTTP POST request with curl
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
			curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			$result = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			
			// store the status message based on response code
			if ($httpCode == 200) {
				$msg = 'You have successfully subscribed to our website';
			} else {
				switch ($httpCode) {
					case 214:
						$msg = 'You are already subscribed.';
						break;
					default:
						$msg = 'Some problem occurred, please try again.';
						break;
				}
			}
		}else{
			$msg = 'Please enter valid email address.';
		}
		
		return $msg;
	}
	
	function getResponse_subscription($fullname,$email)
	{
		$apikey = '20e8c7b035beeb463f2f7b5ff3a94e49';
		$url = 'https://api.getresponse.com/v3/contacts';

		$headers = [
                    'Accept: application/json',
                    'Content-Type: application/json',
                    'X-Auth-Token: api-key '.$apikey
                ];
				
				$data['name'] 	= $fullname;
				$data['email']  = 	$email;
				$data['campaign']['campaignId'] = 	'aX6RR';
				$data['dayOfCycle'] = '0';
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_URL, 'https://api.getresponse.com/v3/contacts');
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
                $result = curl_exec($curl);
				$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                $result2 = json_decode($result);
				curl_close($curl);
	}	
	function timestampdiff($qw,$saw)
	{
	 	$datetime1 = new DateTime($qw);
		$datetime2 = new DateTime($saw);
		$interval = $datetime1->diff($datetime2);
		return $interval->format('%i');
	}
	
	function subAdminSelect($selval)
	{
		$selval  = $this->filter_mysql($this->filter_numeric($selval));

		$str.= "<option value=''>--Select Sub Admin--</option>";
		$res=parent::selectData(TABLE_ADMIN,"","admin_type='s' and admin_status='Active'","");
		while($row=mysqli_fetch_array($res))
		{
			$str.="<option value='".$row['admin_id']."'";
			if($row['admin_id']==$selval)
			{
				$str.=' selected';
			}
			$str.=">".$row['admin_name']."</option>";
		}
		return $str;
		
	}
	
	function getAdminName($admin_id)
	{
		$admin_id  = $this->filter_mysql($this->filter_numeric($admin_id));

		$res = parent::selectData(TABLE_ADMIN,"","admin_status='Active' and admin_id='".$admin_id."'",1);
		return($res['admin_name']);
	}
	
	
	function checkPagePermission($page_name)
	{ 
		$dataP = parent::selectData(TABLE_ALL_PAGES,"","page_status='Active' and (page_name like '%".$this->filter_mysql($page_name)."%')",1);
		$pageId = $dataP['page_id'];
		
		$dataxcP = parent::selectData(TABLE_SUB_ADMIN_PAGES,"","sap_status='Active' and admin_id='".$_SESSION['admin']['admin_id']."' and FIND_IN_SET('".$pageId."', page_id)",1);
		if($_SESSION['admin']['admin_type'] == 's')
		{	
			if(empty($dataxcP['sap_id'])) {
				return 0;
			} else {
				return 1;
			}	
		} 
		else
		{
			return 1;
		}
	
				
	}
	
	function getUserEmailSelected($selval,$userId)
	{	
	
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		$selval  = $this->filter_mysql($this->filter_numeric($selval));
		
		$res=parent::selectData(TABLE_USER,"","user_id<>0 and user_status='Active' and u_login_id !='".$userId."'","");
		while($row=mysqli_fetch_array($res))
		{
			$str.="<option value='".$row['user_id']."'";
			if($row['user_id']==$selval)
			{
				$str.=' selected';
			}
			$str.=">".$row['user_first_name'].' '.$row['user_last_name'].' ('.$row['user_email'].')'."</option>";
		}
		return $str;
		
	}
	
	function getReferralName($user_id)
	{
		$user_id  = $this->filter_mysql($this->filter_numeric($user_id));
		if($user_id == 0){
			return('Admin');
		}
		$res = parent::selectData(TABLE_USER,"","user_status='Active' and u_login_id='".$user_id."'",1);
		return($res['user_first_name']." ".$res['user_last_name']." (".$res['user_email'].")");
	}
	
	
	
	function getEmailFormat($selval)
	{	
	
		$selval  = $this->filter_mysql($this->filter_numeric($selval));
		$res=parent::selectData(TABLE_EMAIL_FACILITY,"","ef_id<>0 and ef_status='Active'","");
		while($row=mysqli_fetch_array($res))
		{
			$str.="<option value='".$row['ef_id']."'";
			if($row['ef_id']==$selval)
			{
				$str.=' selected';
			}
			$str.=">".$row['ef_title']."</option>";
		}
		return $str;
		
	}
	
	function getAllMemberIds()
	{
		$fIds = "";	 		 
		$lists = parent::selectData(TABLE_USER_LOGIN,"","u_login_status='Active'","");  
		$i=0;
		while($resR = mysqli_fetch_assoc($lists))
		{
			 $fIds .= "'".$resR['u_login_id']."'".",";
			 
			 
		$i++; }
		
		$fIds = rtrim($fIds,",");
		return $fIds;
	 
	}
	
	
	function getRestMemberIds($user_login_ids)
	{
		$user_login_ids  = $this->filter_mysql($this->filter_numeric($user_login_ids));
		$fIds = "";	 		 
	   
		$lists = parent::selectData(TABLE_USER_LOGIN,"","u_login_status='Active' and u_login_id >'".$user_login_ids."' order by u_login_id asc limit 20");
		$i=0;
		while($resR = mysqli_fetch_assoc($lists))
		{
			 $fIds .= "".$resR['u_login_id']."".",";
			 
			 
		$i++; }
		
		$fIds = rtrim($fIds,",");
		return $fIds;
	 
	}
	
	function package_new_coin_dist($user_id,$dt,$earnC,$totalNC)
	{
		$user_id  	= $this->filter_mysql($this->filter_numeric($user_id));
		$dt       	= $this->filter_mysql($dt);
		$today  	= date("Y-m-d");
		$m=1;
		$totalC = $earnC;
		if($totalC)
		{
			$sqlMBA = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","od.pack_quan,od.od_id,od.user_id","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id='5' and od.od_status='Active' and od.user_id='".$user_id."' and od.od_pack_validity >= '".$dt."'","","o.order_id asc");
			while($resMBA = mysqli_fetch_array($sqlMBA))
			{
			//echo "ok";
				if($resMBA['pack_quan']<=$totalC && $totalC>0)
				{
					$totalC  =  $totalC - $resMBA['pack_quan'];
					
					$sqlmbcS = parent::selectData(TABLE_MB_NEW_SHARE,"mbn_id,od_id","od_id='".$resMBA['od_id']."' and mbn_date='".$dt."'",1);
					if($sqlmbcS['od_id']=='')
					{
						$mcbI['od_id'] 			 = $resMBA['od_id'];
						$mcbI['user_id'] 		 = $resMBA['user_id'];
						$mcbI['mbn_share'] 		 = ($totalNC/$earnC)*$resMBA['pack_quan'];
						$mcbI['mbn_date'] 		 = $dt;
						$mcbI['mbn_udate'] 		 = date("Y-m-d");
						$mcbI['mbn_utime']		 = date("H:i:s");
							
						parent::insertData(TABLE_MB_NEW_SHARE,$mcbI);		
						
						//$arrODU['od_pack_val_used']	= $resMBA['od_pack_val_used']+1;
						//echo $arrODU['od_pack_val_used']."||".$resMBA['od_id'];
						//echo "<br>";
						
						//parent::updateData(TABLE_ORDER_DETAILS,$arrODU,"od_id='".$resMBA['od_id']."'");	
					}	
				}
				else if($resMBA['pack_quan']>$totalC  && $totalC>0)
				{
					$rQty = $totalC;
					$totalC = 0;					
					
					$sqlmbcS = parent::selectData(TABLE_MB_NEW_SHARE,"mbn_id,od_id","od_id='".$resMBA['od_id']."' and mbn_date='".$dt."'",1);
					if($sqlmbcS['od_id']=='')
					{
						$mcbI['od_id'] 			 = $resMBA['od_id'];
						$mcbI['user_id'] 		 = $resMBA['user_id'];
						$mcbI['mbn_share'] 		 = ($totalNC/$earnC)*$rQty;
						$mcbI['mbn_date'] 		 = $dt;
						$mcbI['mbn_udate'] 		 = date("Y-m-d");
						$mcbI['mbn_utime']		 = date("H:i:s");
							
						parent::insertData(TABLE_MB_NEW_SHARE,$mcbI);
						
					}	
				}
			}
		}	
	}
	
	function get_package_share($od_id,$user_id,$dt)
	{
		$user_id  = $this->filter_mysql($this->filter_numeric($user_id));
		$od_id    = $this->filter_mysql($this->filter_numeric($od_id));
		$dt       = $this->filter_mysql($dt);
		
		$sqlOD = parent::selectData(TABLE_MB_NEW_SHARE,"mbn_share","od_id='".$od_id."' and user_id='".$user_id."' and mbn_date='".$dt."'",1);
		return $sqlOD['mbn_share'];
	}
	
	
	function get_member_pre_sale_balance($uId,$dt)
	{
		$uId  = $this->filter_mysql($this->filter_numeric($uId));
		$dt  = $this->filter_mysql($dt);
		
		$ucredit = "";
		$sqlU = parent::selectData(TABLE_USER_WALLET,"","wall_status='Active' and wall_type='d' and wall_pstatus='p' and user_id='".$uId."' and wall_modified>='".$dt."' order by wall_id asc");
		while($resU = mysqli_fetch_assoc($sqlU)) 
		{
			$ucredit += $resU['wall_asimi'];
		}
		
		$sqlS = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","od.od_tot_token as totalp","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id='5' and od.od_status='Active' and od.user_id='".$uId."' and od.od_created>='".$dt."'");
		while($resS = mysqli_fetch_array($sqlS))
		{
			$ucredit -= $resS['totalp'];
		}
		
		return $ucredit;
	}
	
	
	function getRestAffiliateMemberIds($user_id)
	{
	
		$user_id  = $this->filter_mysql($this->filter_numeric($user_id));
		$fIds = "";	 		 
	   
		$lists = parent::selectData(TABLE_BECOME_AFFILIATE,"","baff_status='Active' and user_id >'".$user_id."' order by user_id asc limit 20");
		$i=0;
		while($resR = mysqli_fetch_assoc($lists))
		{
			 $fIds .= "".$resR['user_id']."".",";	 
			 
		$i++; }
		
		$fIds = rtrim($fIds,",");
		return $fIds;
	 
	}
	
	
		
	function getInactiveMemberIds($user_login_ids)
	{
		$user_login_ids  = $this->filter_mysql($this->filter_numeric($user_login_ids));
	
		$fIds = "";
		$lists = parent::selectData(TABLE_USER_LOGIN,"","u_login_status='Registered' and u_login_id >'".$user_login_ids."' order by u_login_id asc limit 20");
		$i=0;
		while($resR = mysqli_fetch_assoc($lists))
		{
			 $fIds .= "".$resR['u_login_id']."".",";		 
		$i++; 
		}
		
		$fIds = rtrim($fIds,",");
		return $fIds;
	 
	}
	
	
	
	function get_affiliate($userId)
	{ 
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		$userD = parent::selectData(TABLE_USER,"","u_login_id='".$userId."' and user_status='Active'",1);	
		$userAD = parent::selectData(TABLE_USER_LOGIN,"u_login_id","u_login_id='".$userD['user_referrer']."' and u_login_status='Active'",1);
		if($userAD['u_login_id']) return $userAD['u_login_id'];		 
		else return 0;

	}
	
	function get_affilate_name($userId)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		$userD = parent::selectData(TABLE_USER,"","u_login_id='".$userId."' and user_status='Active'",1);	
		$userAD = parent::selectData(TABLE_USER,"user_id,user_first_name,user_last_name","u_login_id='".$userD['user_referrer']."'",1);
		if($userAD['user_id']) return $userAD['user_first_name']." ".$userAD['user_last_name'];		 
		else return "Admin";
	}
	
	function get_affilate_email($userId)
	{
		$userId  	= $this->filter_mysql($this->filter_numeric($userId));
		$userD 		= parent::selectData(TABLE_USER,"","u_login_id='".$userId."' and user_status='Active'",1);	
		$userAD 	= parent::selectData(TABLE_USER,"user_id,user_first_name,user_last_name,user_email","u_login_id='".$userD['user_referrer']."'",1);
		if($userAD['user_id']) return $userAD['user_email'];		 
		else return "Admin";
	}	
	

	
	function get_all_login_ads_viewed_by_date($dt)
	{
		$lapD     = parent::selectData(TABLE_LOGIN_AD_PACKAGE,"","lap_date='".$dt."' and (lap_url1_status='y' or lap_url2_status='y') and lap_pstatus ='p'",1);		
		$totalBAC = $lapD['lap_url1_tot_view']+$lapD['lap_url2_tot_view'];		
		if($totalBAC) return $totalBAC;
		else return 0;
	}
	
	
	function get_mint_pack_for_cashback($user_id)
	{
		$user_id  = $this->filter_mysql($this->filter_numeric($user_id));
		$cashPack = array();
		$today = date("Y-m-d");
		$i=0;
		$sqlMBA = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","od.pack_quan,od.od_mem_cashback_token,od.od_id,od.user_id,od.od_pack_val_used,od.od_pack_val_days,od.od_pack_validity","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id='5' and od.od_status='Active' and od.user_id='".$user_id."' and od.od_pack_validity >= '".$today."'","","o.order_id asc");
		while($resMBA = mysqli_fetch_array($sqlMBA))
		{
			$cashPack[$i]['od_id'] 			 = $resMBA['od_id'];
			$cashPack[$i]['mint_cashback'] 	 = $resMBA['od_mem_cashback_token']/365;
			$i++;
		}	
		
		return $cashPack;
	}
	
	 
	
	function get_affiliate_minting_pack_sales($user_id)
	{
		$user_id  = $this->filter_numeric($user_id); 
		$all_referrals = $this->get_all_referrals($user_id);
		if(!empty($all_referrals))
		{
			$cur_ref_minting_sales = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","sum(od.pack_quan) as totalp,sum(od.pack_quan*od.pack_price) as totalprice","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id ='5' and od.od_status='Active' and od.user_id IN (".$all_referrals.")",1);
			$ref_tot_purch_minting = $cur_ref_minting_sales['totalp'];
			$ref_tot_purch_minting_price = $cur_ref_minting_sales['totalprice'];
			if($ref_tot_purch_minting_price) return($ref_tot_purch_minting_price);
			else return 0;
		} else return 0;
	}
	
	function get_affiliate_advertising_pack_sales($user_id)
	{
		$user_id  = $this->filter_numeric($user_id); 
		$all_referrals = $this->get_all_referrals($user_id); 
		if(!empty($all_referrals))
		{
			$cur_ref_adver_sales = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","sum(od.pack_quan) as totalp,sum(od.pack_quan*od.pack_price) as totalprice","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id <>'5' and od.od_status='Active' and od.user_id IN (".$all_referrals.")",1);
			$ref_tot_purch_adver = $cur_ref_adver_sales['totalp'];
			$ref_tot_purch_adver_price = $cur_ref_adver_sales['totalprice'];

			if($ref_tot_purch_adver_price) return($ref_tot_purch_adver_price);
			else return 0;
		} else return 0;
	   
	}
	
	
	
	function get_member_minting_update($user_id)
	{
		$user_id  = $this->filter_mysql($this->filter_numeric($user_id));
		
		$cur_mintD = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","sum(od.pack_quan) as totalp","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id ='5' and od.od_status='Active' and od.user_id='".$user_id."'",1);
		$current_mint_levelD = $cur_mintD['totalp'];
		// $current_mint_levelD = 200;

		$mproUD = parent::selectData(TABLE_MINTING_PROGRESS,"","mp_status='Active'",1);	
		
		$lproUD = parent::selectData(TABLE_LAUNCH_PROGRESS,"","user_id='".$user_id."' and lp_status='Active'",1); 
 
		if(empty($lproUD['lp_id']))
		{		
			$mintPro = parent::selectData(TABLE_MINTING_PROGRESS,"","mp_status='Active'","","mp_id asc");
			//$i = 1;
			while($mintPD = mysqli_fetch_assoc($mintPro)) 
			{ 
				if($current_mint_levelD >= $mintPD['mp_pack_requir']) 
				{
					$lprog['mp_date_stage'.$mintPD['mp_id']]  =  CURRENT_DATE_TIME;
					//$i++;
				}
			}  
			 
			$lprog['user_id']   = $user_id;
			$lprog['lp_created'] = CURRENT_DATE_TIME;
			$lprog['lp_modified'] = CURRENT_DATE_TIME;							
			parent::insertData(TABLE_LAUNCH_PROGRESS,$lprog);		
			 
		} 
		else 
		{
			
			$mintPro = parent::selectData(TABLE_MINTING_PROGRESS,"","mp_status='Active'","","mp_id asc");
			while($mintPD = mysqli_fetch_assoc($mintPro)) 
			{				 
				if($current_mint_levelD >= $mintPD['mp_pack_requir'] && $lproUD['mp_date_stage'.$mintPD['mp_id']] == '0000-00-00 00:00:00')
				{
					 $lprog['mp_date_stage'.$mintPD['mp_id']]  =  CURRENT_DATE_TIME;  
				}
			}  
			 
			$lprog['lp_created'] = CURRENT_DATE_TIME;
			$lprog['lp_modified'] = CURRENT_DATE_TIME;				 
			parent::updateData(TABLE_LAUNCH_PROGRESS,$lprog,"lp_id='".$lproUD['lp_id']."'");	
			
		}
			
			
	}
	
	
	
	function get_affiliate_sales_update($aff_id)
	{
			$aff_id  = $this->filter_mysql($this->filter_numeric($aff_id));
			$tot_price_mint_pack  = $this->get_affiliate_minting_pack_sales($aff_id);	
			$tot_price_adver_pack = $this->get_affiliate_advertising_pack_sales($aff_id);
			$afproUD = parent::selectData(TABLE_AFFILIATE_PROGRESS,"","ap_status='Active'",1);	
			$lproUD = parent::selectData(TABLE_LAUNCH_PROGRESS,"","user_id='".$aff_id."' and lp_status='Active'",1); 
		 
		if(empty($lproUD['lp_id']))
		{		
			$affPro = parent::selectData(TABLE_AFFILIATE_PROGRESS,"","ap_status='Active'","","ap_id asc");
			while($affPD = mysqli_fetch_assoc($affPro)) 
			{ 
					if($tot_price_mint_pack >=$affPD['ap_minting_volume'] && $tot_price_adver_pack >=$affPD['ap_advertising_volume']) 
					{	
						$lprog['ap_date_stage'.$affPD['ap_id']]  =  CURRENT_DATE_TIME; 		
						$this->qualified_affiliate_bonus_email($aff_id,$affPD['ap_id']);	 
					}
			}  
				 
				$lprog['user_id']   = $aff_id;
				$lprog['lp_created'] = CURRENT_DATE_TIME;
				$lprog['lp_modified'] = CURRENT_DATE_TIME;							
				parent::insertData(TABLE_LAUNCH_PROGRESS,$lprog);		
				 
		} 
		else 
		{
				
				$affProU = parent::selectData(TABLE_AFFILIATE_PROGRESS,"","ap_status='Active'","","ap_id asc");
				while($affPD = mysqli_fetch_assoc($affProU)) 
				{ 			 
					if($tot_price_mint_pack >= $affPD['ap_minting_volume'] && $tot_price_adver_pack >=$affPD['ap_advertising_volume'] && $lproUD['ap_date_stage'.$affPD['ap_id']] == '0000-00-00 00:00:00') 
					{
						$lprog['ap_date_stage'.$affPD['ap_id']]  = CURRENT_DATE_TIME;		
						$this->qualified_affiliate_bonus_email($aff_id,$affPD['ap_id']);						
					}
				}  				 
				
				$lprog['lp_created'] = CURRENT_DATE_TIME;
				$lprog['lp_modified'] = CURRENT_DATE_TIME;				 
				parent::updateData(TABLE_LAUNCH_PROGRESS,$lprog,"lp_id='".$lproUD['lp_id']."'");
			}
	}
	
	function selectMintingProgress($mintpId)
	{
		$mintpId  = $this->filter_mysql($this->filter_numeric($mintpId));
		$lists=parent::selectData(TABLE_MINTING_PROGRESS,"","mp_status='Active'");
		$ret = '';
		while($res = mysqli_fetch_array($lists))
		{
			$ret .= '<option value="'.$res['mp_id'].'" '.($res['mp_id'] == $mintpId?'selected="selected"':'').'>Stage '.$res['mp_id'].'</option>';
		}
		return($ret);
	}
	
	
	function selectAfiliateVolumeProgress($affpId)
	{
		$affpId  = $this->filter_mysql($this->filter_numeric($affpId));
		$lists=parent::selectData(TABLE_AFFILIATE_PROGRESS,"","ap_status='Active'");
		$ret = '';
		while($res = mysqli_fetch_array($lists))
		{
			$ret .= '<option value="'.$res['ap_id'].'" '.($res['ap_id'] == $affpId?'selected="selected"':'').'>Stage'.$res['ap_id'].'</option>';
		}
		return($ret);
	}
	
	
	function get_minting_rotator_position_claimed($mp_id)
	{
		$mp_id  = $this->filter_mysql($this->filter_numeric($mp_id));
		$minting_position = parent::selectData(TABLE_LAUNCH_PROGRESS,"count(user_id) as tot","mp_date_stage".$mp_id." !='0000-00-00 00:00:00' and lp_status='Active'",1);
		$ret = $minting_position['tot'];
		return($ret);
	}
	
	function get_affiliate_volume_bonus($ap_id)
	{
		$ap_id  = $this->filter_mysql($this->filter_numeric($ap_id));
		$minting_position = parent::selectData(TABLE_LAUNCH_PROGRESS,"count(user_id) as tot","ap_date_stage".$ap_id." !='0000-00-00 00:00:00' and lp_status='Active'",1);
		$ret = $minting_position['tot'];
		return($ret);
	}
	
	
	
	function qualified_affiliate_bonus_email($aff_id,$level=1)
	{
		$aff_id  	= $this->filter_numeric($aff_id);		
		$affD 		= parent::selectData(TABLE_USER,"","u_login_id='".$aff_id."'",1);
		
		/////////////MAIL////////////////
		$affMailMessage = '<p>Hello '.$affD['user_first_name'].' '.$affD['user_last_name'].',<br>';
		if($level==1)
		{
		$affMailSubject = 'Qualified for level 1 Affiliate bonus';	
		$affMailMessage .= 'Congratulations!  You have qualified for Level 1 Affiliate Bonus! </p><p>This bonus is earned through a combined sales volume of Asimi Stakes set up by your referrals and the Advertising volume purchased by your referrals.</p><p>Because you have a total of $3,125 in Minting Volume or more and $781 in Advertising Volume or more you will receive a bonus of 156 Asimi paid to the Asimi wallet in Waves that you have saved in your Hashing Ad Space account.</p><p>Your success is our success and we thank you for your continued commitment to Hashing Ad Space.</p>';
		}
		else if($level==2)
		{
			$affMailSubject = 'Qualified for level 2 Affiliate bonus';
			$affMailMessage .= 'Congratulations!  You have qualified for Level 2 Affiliate Bonus!</p><p>This bonus is earned through a combined sales volume of Asimi Stakes set up by your referrals and the Advertising volume purchased by your referrals.</p><p>Because you have a total of $6,250 in Minting Volume or more and $1,563 in Advertising Volume or more you will receive a bonus of 313 Asimi paid to the Asimi wallet in Waves that you have saved in your Hashing Ad Space account.</p><p>Your success is our success and we thank you for your continued commitment to Hashing Ad Space.</p>';
		
		}
		else if($level==3)
		{
			$affMailSubject = 'Qualified for level 3 Affiliate bonus';
			$affMailMessage .= 'Congratulations!  You have qualified for Level 3 Affiliate Bonus!</p><p>This bonus is earned through a combined sales volume of Asimi Stakes set up by your referrals and the Advertising volume purchased by your referrals.</p><p>Because you have a total of $12,500 in Minting Volume or more and $3,125 in Advertising Volume or more you will receive a bonus of 625 Asimi paid to the Asimi wallet in Waves that you have saved in your Hashing Ad Space account.</p><p>Your success is our success and we thank you for your continued commitment to Hashing Ad Space.</p>';
		
		}
		else if($level==4)
		{
			$affMailSubject = 'Qualified for level 4 Affiliate bonus';
			$affMailMessage .= 'Congratulations!  You have qualified for Level 4 Affiliate Bonus!</p><p>This bonus is earned through a combined sales volume of Asimi Stakes set up by your referrals and the Advertising volume purchased by your referrals.</p><p>Because you have a total of $25,000 in Minting Volume or more and $6,250 in Advertising Volume or more, you will receive a bonus of 1,250 Asimi paid to the Asimi wallet in Waves that you have saved in your Hashing Ad Space account.</p><p>Your success is our success and we thank you for your continued commitment to Hashing Ad Space.</p>';
		
		}
		else if($level==5)
		{
			$affMailSubject = 'Qualified for level 5 Affiliate bonus';
			$affMailMessage .= 'Congratulations!  You have qualified for Level 5 Affiliate Bonus!</p><p>This bonus is earned through a combined sales volume of Asimi Stakes set up by your referrals and the Advertising volume purchased by your referrals.</p><p>Because you have a total of $50,000 in Minting Volume or more and $12,500 in Advertising Volume or more you will receive a bonus of 2,500 Asimi paid to the Asimi wallet in Waves that you have saved in your Hashing Ad Space account.</p><p>Your success is our success and we thank you for your continued commitment to Hashing Ad Space.</p>';
		
		}
		else if($level==6)
		{
			$affMailSubject = 'Qualified for level 6 Affiliate bonus';
			$affMailMessage .= 'Congratulations!  You have qualified for Level 6 Affiliate Bonus!</p><p>This bonus is earned through a combined sales volume of Asimi Stakes set up by your referrals and the Advertising volume purchased by your referrals.</p><p>Because you have a total of $100,000 in Minting Volume or more and $25,000 in Advertising Volume or more you will receive a bonus of 5,000 Asimi paid to the Asimi wallet in Waves that you have saved in your Hashing Ad Space account.</p><p>Your success is our success and we thank you for your continued commitment to Hashing Ad Space.</p>';
		
		}
		else if($level==7)
		{
			$affMailSubject = 'Qualified for level 7 Affiliate bonus';
			$affMailMessage .= 'Congratulations!  You have qualified for Level 7 Affiliate Bonus!</p><p>This bonus is earned through a combined sales volume of Asimi Stakes set up by your referrals and the Advertising volume purchased by your referrals.</p><p>Because you have a total of $200,000 in Minting Volume or more and $50,000 in Advertising Volume or more you will receive a bonus of 10,000 Asimi paid to the Asimi wallet in Waves that you have saved in your Hashing Ad Space account.</p><p>Your success is our success and we thank you for your continued commitment to Hashing Ad Space.</p>';
		
		}
		else if($level==8)
		{
			$affMailSubject = 'Qualified for level 8 Affiliate bonus';
			$affMailMessage .= 'Congratulations!  You have qualified for the top Level 8 Affiliate Bonus!  What an incredible success!</p><p>This bonus is earned through a combined sales volume of Asimi Stakes set up by your referrals and the Advertising volume purchased by your referrals.</p><p>Because you have a total of $400,000 in Minting Volume or more and $100,000 in Advertising Volume or more you will receive a bonus of 20,000 Asimi paid to the Asimi wallet in Waves that you have saved in your Hashing Ad Space account.</p><p>Your success is our success and we thank you for your continued commitment to Hashing Ad Space.</p>';
		
		}
		
		$affMailMessage .= '<p>Maximize Your Online Business Success!<br><strong>Hashing Ad Space</strong></p><br>'.ADDRESS_LINE_1.ADDRESS_SEPRATER_1.'<br>'.ADDRESS_LINE_2.ADDRESS_SEPRATER_2.ADDRESS_LINE_3.'<br>----------------------------------------------<br>S: https://hashingadspace.zendesk.com/<br>W: '.FURL.'<br>----------------------------------------------';
		$affMailBody    = $this->mailBody($affMailMessage); 
		
		$to             = $affD['user_email'];
		$from           = FROM_EMAIL_2;	
		$this->sendMailSES($to, $affMailSubject,$affMailBody,$from,"Hashing Ad Space",$type);
	}
	
	///////////////////////////////////////Launch Functions//////////////////////////////////////
	
	function aff_prelaunch_comm_launch($aff_id)
	{
		$ref_ids = explode(",",$this->get_all_referrals($aff_id));	
			
		$totalRE = 0;
		if(!empty($ref_ids))
		{
			for($i=0;!empty($ref_ids[$i]);$i++)
			{
				$totalRE += $this->total_newshare_asimi_minted_launch($ref_ids[$i]);
			}
		}	
		return round($totalRE*.1,8);
		
	}
	
	function total_newshare_asimi_minted_launch($userId)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		
		$dataU = parent::selectData(TABLE_USER_LOGIN,"u_login_created","u_login_id='".$userId."'",1);
		$active_date = $dataU['u_login_created'];
		
		$calDay = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
		$totalAE = 0;
		$sqlU = parent::selectData(TABLE_MEMBER_EARNING,"sum(me_share_coin) as total_new_share_min","user_id='".$userId."' and me_created<='".$calDay."'",1);	
		$ucredit = $sqlU['total_new_share_min'];
		
		return $ucredit;
	} 
	
	
	
	function aff_prelaunch_comm_launch_by_date($aff_id,$date)
	{
		$ref_ids = explode(",",$this->get_all_referrals($aff_id));		
		$totalRE = 0;
		for($i=0;$ref_ids[$i]!='';$i++)
		{
			$totalRE += $this->total_newshare_asimi_minted_launch_by_date($ref_ids[$i],$date);
		}
		return round($totalRE*.1,8);
		
	}
	
	function total_newshare_asimi_minted_launch_by_date($userId,$date)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		$calDay = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
		$totalAE = 0;
		$sqlU = parent::selectData(TABLE_MEMBER_EARNING,"sum(me_share_coin) as total_new_share_min","user_id='".$userId."' and me_created='".$date."'",1);	
		$ucredit = $sqlU['total_new_share_min'];
		
		return $ucredit;
	} 
	
	function prelaunch_balance_launch($userId)
	{
			$userId  = $this->filter_mysql($this->filter_numeric($userId));
			$last_date = '2019-01-27';
		    $ucredit = 0;
			$bcredit = 0;
			$rcredit = 0;
			$sqlU = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_asimi","wall_status='Active' and ( wall_type='d' or wall_type='e' or wall_type='j' or wall_type='r') and wall_pstatus='p' and user_id='".$userId."' and wall_created<='".$last_date."'",1);
			$ucredit = $sqlU['total_asimi'];
			
			 
						
			$uwsql = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_deduction","wall_status='Active' and (wall_type='w' or wall_type='mw' or wall_type='p') and wall_pstatus='p' and user_id='".$userId."' and wall_created<='".$last_date."'",1);
			$bcredit = $uwsql['total_deduction'];					
			 
			$balance = $ucredit-$bcredit;				
			return($balance);
	}
	
	function account_balance_launch($userId)
	{  
			$userId  = $this->filter_mysql($this->filter_numeric($userId));
			$start_date = '2019-01-28';
			$ucredit = 0;
			$bcredit = 0;
			$sqlU = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_asimi","wall_status='Active' and ( wall_type='d' or wall_type='e' or wall_type='cr' or wall_type='pbr' or wall_type='me' or wall_type='rs' or wall_type='j' or wall_type='r' or wall_type='a' or wall_type='ve' or wall_type='vea' or wall_type='rw' or wall_type='rwa' or wall_type='ref' or wall_type='lsw' or wall_type='rsw' or wall_type='rbw' or wall_type='btcp' or wall_type='ethp' or wall_type='wavesp' or wall_type='ltcp' or wall_type='brz' or wall_type='lus' or wall_type='lsr' or wall_type='laa' or wall_type='lasa' or wall_type='spr' or wall_type='kvfa' or wall_type='lrw' or wall_type='sb' or wall_type='sba' or wall_type='psb' or wall_type='psba' or wall_type='nmae' or wall_type='dmp' or wall_type='ab' or wall_type='bsp' or wall_type='spbc' or wall_type='sppc') and wall_pstatus='p' and user_id='".$this->filter_mysql($userId)."' and wall_created>='".$start_date."'",1);
			$ucredit = $sqlU['total_asimi'];
					 
						
			$uwsql = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_deduction","wall_status='Active' and (wall_type='w' or wall_type='mw' or wall_type='kyc' or wall_type='cd' or wall_type='ls' or wall_type='btz' or wall_type='pbt' or wall_type='vep' or wall_type='lap' or wall_type='lasp' or wall_type='iaf' or wall_type='spb' or wall_type='spp') and wall_pstatus='p' and user_id='".$userId."' and wall_created>='".$start_date."'",1);
			$bcredit = $uwsql['total_deduction'];					
			 
			$balance = $ucredit-$bcredit;				
			return($balance);
	}	
	
	function purchase_total_launch($userId)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));		
		$start_date = '2019-01-28';
		$bcredit = 0;		
				
		$uwsql = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_deduction","wall_status='Active' and (wall_type='p') and wall_pstatus='p' and user_id='".$userId."' and wall_created>='".$start_date."'",1);
		$bcredit = $uwsql['total_deduction'];					
		
		if($bcredit) return($bcredit);
		else return 0;
	}
	
	function total_available_balance_launch($userId)
	{
		$available_balance = $this->account_balance_launch($userId) + $this->aff_prelaunch_comm_launch($userId);
		$pre_lauch_balance = $this->prelaunch_balance_launch($userId);
		$purchase_total_launch = $this->purchase_total_launch($userId);
		
		if($pre_lauch_balance>$purchase_total_launch)
		{
			$pre_lauch_balance = $pre_lauch_balance - $purchase_total_launch;
		}
		else
		{
			
			$available_balance = $available_balance + $pre_lauch_balance - $purchase_total_launch;
			$pre_lauch_balance = 0;
		}
		
		$total_current_balance = $pre_lauch_balance+$available_balance;
		
		return $total_current_balance;
	}
	
	
	
	
	function total_available_balance_launch_midnight($userId)
	{
		$available_balance = $this->account_balance_launch_midnight($userId) + $this->aff_prelaunch_comm_launch($userId);
		$pre_lauch_balance = $this->prelaunch_balance_launch($userId);
		$purchase_total_launch = $this->purchase_total_launch_midnight($userId);
		
		if($pre_lauch_balance>$purchase_total_launch)
		{
			$pre_lauch_balance = $pre_lauch_balance - $purchase_total_launch;
		}
		else
		{
			
			$available_balance = $available_balance + $pre_lauch_balance - $purchase_total_launch;
			$pre_lauch_balance = 0;
		}
		
		$total_current_balance = $pre_lauch_balance+$available_balance;
		
		return $total_current_balance;
	}
	
	function account_balance_launch_midnight($userId)
	{  
			$userId  = $this->filter_mysql($this->filter_numeric($userId));
			$start_date = '2019-01-28';
			$calDay = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
			$ucredit = 0;
			$bcredit = 0;
			$sqlU = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_asimi","wall_status='Active' and ( wall_type='d' or wall_type='e' or wall_type='cr' or wall_type='pbr' or wall_type='me' or wall_type='rs' or wall_type='j' or wall_type='r' or wall_type='a' or wall_type='ve' or wall_type='vea' or wall_type='rw' or wall_type='rwa' or wall_type='ref' or wall_type='lsw' or wall_type='rsw' or wall_type='rbw' or wall_type='btcp' or wall_type='ethp' or wall_type='brz' or wall_type='lus' or wall_type='lsr' or wall_type='laa' or wall_type='lasa' or wall_type='spr' or wall_type='kvfa' or wall_type='lrw' or wall_type='sb' or wall_type='sba' or wall_type='psb' or wall_type='psba' or wall_type='nmae') and wall_pstatus='p' and user_id='".$this->filter_mysql($userId)."' and wall_created>='".$start_date."' and wall_created<='".$calDay."'",1);
			$ucredit = $sqlU['total_asimi'];
					 
						
			$uwsql = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_deduction","wall_status='Active' and (wall_type='w' or wall_type='mw' or wall_type='kyc' or wall_type='cd' or wall_type='ls' or wall_type='btz' or wall_type='pbt' or wall_type='vep' or wall_type='lap' or wall_type='lasp' or wall_type='iaf') and wall_pstatus='p' and user_id='".$userId."' and wall_created>='".$start_date."' and wall_created<='".$calDay."'",1);
			$bcredit = $uwsql['total_deduction'];					
			 
			$balance = $ucredit-$bcredit;				
			return($balance);
	}	
	
	function purchase_total_launch_midnight($userId)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));		
		$start_date = '2019-01-28';
		$calDay = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
		$bcredit = 0;		
				
		$uwsql = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_deduction","wall_status='Active' and (wall_type='p') and wall_pstatus='p' and user_id='".$userId."' and wall_created>='".$start_date."' and wall_created<='".$calDay."'",1);
		$bcredit = $uwsql['total_deduction'];					
		
		if($bcredit) return($bcredit);
		else return 0;
	}
	
	
	function purchase_total_ever($userId)
	{
			$userId  = $this->filter_mysql($this->filter_numeric($userId));
			$start_date = date("Y-m-d");
			$bcredit = 0;
		
						
			$uwsql = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_deduction","wall_status='Active' and ( wall_type='p' or wall_type='vep') and wall_pstatus='p' and user_id='".$userId."' and wall_created<='".$start_date."'",1);
			$bcredit = $uwsql['total_deduction'];					
			
			if($bcredit) return($bcredit);
			else return 0;
	}
	
	
	function getAllPages($page_id)
	{	
		
		$resww = parent::selectData(TABLE_ALL_PAGES,"","page_id<>0 and page_status='Active'","","page_id");
	 
		$pageId = explode(",",$page_id);
		while($row=mysqli_fetch_array($resww))
		{
			$str.="<div class='col-xs-10 col-sm-4'><input type='checkbox' name='page_id[]' id='page_id' value='".$row['page_id']."'";
			if(in_array($row['page_id'],$pageId))
			{
				$str.=' checked';
			}
			$str.="> ".$row['page_title']."</div>";
		}
		return $str;
		
	}
	
	
	function getAllPagesName($page_id)
	{	
		$resww = parent::selectData(TABLE_ALL_PAGES,"","page_id in (".$this->filter_mysql($page_id).") and page_status='Active'","");
		while($row=mysqli_fetch_array($resww))
		{
			$str .= $row['page_title']." | ";
			//$str.="<span>".$row['page_title']."</span>";
		}
		return $str;
		 
	}
	
	function get_asimi_token_doller_value($asimi_token)
	{ 	 
		$token 			= $this->get_asimi_curr_price();
		$dollerTotalval = ($asimi_token*$token); 
		$dollerTotal 	= 	(float)round($dollerTotalval,8);		
		if($dollerTotal!="") return $dollerTotal;
		else return "0";

	} 
	
	function user_list_from_wallet_address($wallet)
	{
		
		$user_id = "";
		$sqlW = $this->selectData(TABLE_USER_WALLET,"user_id","wall_from_wallet='".$this->filter_mysql($wallet)."' and wall_status='Active'");
		while($resW  = mysqli_fetch_array($sqlW))
		{
			$user_id .= $resW['user_id'].",";
		}
		$user_id = rtrim($user_id,",");
		$sqlUW = $this->selectData(TABLE_USER,"u_login_id","user_wallet_address='".$this->filter_mysql($wallet)."' and user_status<>'Deleted'");
		while($resUW  = mysqli_fetch_array($sqlUW))
		{
			$user_id .= $resUW['u_login_id'].",";
		}
		$user_id = rtrim($user_id,",");
		$data = "";
		if($user_id!='')
		{
			$sqlU = $this->selectData(TABLE_USER_LOGIN,"u_login_id,u_login_user_email","u_login_id in (".$user_id.") and u_login_status<>'Deleted'");
			while($resU  = mysqli_fetch_array($sqlU))
			{
				$data .= ' <a href="view_member.php?uId='.$resU['u_login_id'].'" target="_blank">'.$resU['u_login_user_email'].'</a> ||';
			}
		}
		$data = rtrim($data,"||");
		return $data;
	}

	function get_tot_banner_impression_alloted($userId,$status)
	{ 
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		
		if($status =='Active') { 	
			$extraw .= "and banad_status <> 'Deleted'"; 
		} else {
			$extraw .= "and banad_status = 'Deleted'";  
		}
		$useRsquare 	= parent::selectData(TABLE_BANNER_ADS,"sum(banad_impression) as stot","user_id='".$userId."' and banad_geo_impression ='0'".$extraw." ","1");
		$useRsquare2 	= parent::selectData(TABLE_BANNER_ADS,"sum(banad_impression) as sgeotot","user_id='".$userId."' and banad_geo_impression !='0'".$extraw." ","1");
		
		$newcart = $useRsquare['stot']+$useRsquare2['sgeotot'];	 		 
		  
		if($newcart!="") return $newcart;
		else return "0";	
		 
	}
	function get_tot_banner_impression_served($userId,$status)
	{ 	
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		
		if($status =='Active') { 	
			$extraw .= "and banad_status <> 'Deleted'"; 
		} else {
			$extraw .= "and banad_status = 'Deleted'"; 
		}
		
		$useRsquare = parent::selectData(TABLE_BANNER_ADS,"sum(banad_view_imp_ach) as stot","user_id='".$userId."'".$extraw." and banad_impression > banad_view_imp_ach","1");		 
		$newcart1 =$useRsquare['stot'];
		$useRsquare2 = parent::selectData(TABLE_BANNER_ADS,"sum(banad_impression) as stot_ach","user_id='".$userId."'".$extraw." and banad_view_imp_ach >= banad_impression","1"); 		 
		$newcart2 =$useRsquare2['stot_ach'];
		 
		$newcart = $newcart1+$newcart2;
		
		if($newcart!="") return $newcart;
		else return "0";	
		 
	}
	
	function get_total_ad_impression_alloted($user_id,$status)
	{	
		$user_id  = $this->filter_mysql($this->filter_numeric($user_id));
		
		if($status =='Active') { 	
			$extraw .= "and adl_status <> 'Deleted'"; 
		} else {
			$extraw .= "and adl_status = 'Deleted'"; 
		}
		$sqlimp = parent::selectData(TABLE_AD_DIRECTORY_LISTING,"sum(adl_impression) as total_adl_imp","user_id='".$user_id."'".$extraw." ","1");
		//$sqlimp = parent::selectData(TABLE_AD_DIRECTORY_LISTING,"SUM(adl_impression) AS total_adl_imp", "user_id = '".$this->filter_mysql($user_id)."' AND adl_status = 'Active'", 1);
		$totalAMVP = $sqlimp['total_adl_imp'];	 
		if ($totalAMVP < 0 || $totalAMVP == '') return 0;
		else return $totalAMVP;
	}
	
	function get_total_ad_impression_served($user_id,$status)
	{
		$user_id  = $this->filter_mysql($this->filter_numeric($user_id));
		
		if($status =='Active') { 	
			$extraw .= "and adl_status <> 'Deleted'"; 
		} else {
			$extraw .= "and adl_status = 'Deleted'"; 
		} 
		$sqlimp = parent::selectData(TABLE_AD_DIRECTORY_LISTING,"sum(adl_impression_view) as total_adl_imp","user_id='".$user_id."'".$extraw." ","1");
		//$sqlimp = parent::selectData(TABLE_AD_DIRECTORY_LISTING, "SUM(adl_impression_view) AS total_adl_imp", "user_id = '".$this->filter_mysql($user_id)."' AND adl_status = 'Active'", 1);
		$totalAMVP = $sqlimp['total_adl_imp'];	 
		if ($totalAMVP < 0 || $totalAMVP == '') return 0;
		else return $totalAMVP;
	}
		
	function get_view_to_earn_asimi_balance($userId)
	{	 
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		 
		$sqlasimi = parent::selectData(TABLE_VTOE_ASIMI_CREDIT,"sum(vc_asimi) as total_asimi","user_id='".$userId."' and vc_status='Active'",1);			 
		$sql_cs_asimi_add = parent::selectData(TABLE_VTOE_CAMPAIGN_SETUP,"SUM(cs_asimi) AS total_added_asimi","user_id = '".$userId."' AND cs_status <> 'Deleted'",1);
		
		$used_asimi = 0;		
		$sql_cs_asimi_del = parent::selectData(TABLE_VTOE_CAMPAIGN_SETUP,"cs_asimi,cs_asimi_used","user_id = '".$userId."' AND cs_status='Deleted'");
		while($res_cs_asimi_del = mysqli_fetch_array($sql_cs_asimi_del))
		{
				$used_asimi +=  $res_cs_asimi_del['cs_asimi_used'];
		}
		 
		//$totalbalAsimi = $used_asimi; 
		$totalbalAsimi = ($sqlasimi['total_asimi']-$sql_cs_asimi_add['total_added_asimi']-$used_asimi); 
		 
		$totalUS = $totalbalAsimi;
		if($totalUS) return $totalUS;
		else return 0; 		
	}	
	
	function total_active_vtoe_campaign($userId)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));	
		$sqlVE = parent::selectData(TABLE_VTOE_CAMPAIGN_SETUP,"count(cs_id) totalVE","user_id='".$userId."' and cs_status<>'Deleted'",1);
		return $sqlVE['totalVE'];
		
	}	
	
	function total_vtoe_view_by_date($userId,$date)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));	
		$date    = $this->filter_mysql($date);
		
		$sqlVTEV = parent::selectData(TABLE_VTOE_MEMBER_VIEW,"","user_id='".$userId."' and vmv_created_dt='".$date."'",1);	
		if($sqlVTEV['vmv_id']!='')
		{
			$data  = ltrim(rtrim($sqlVTEV['cs_id'],","),",");
			$datas = explode(",",$data);
			return count($datas);
		}
		else
		{
			return "0";
		}
	}
	
	function total_vtoe_viewed($userId)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));			
		$v2e_viewed = 0;
		$sqlVTEV = parent::selectData(TABLE_VTOE_MEMBER_VIEW,"","user_id='".$userId."'");	
		while($resCTEV = mysqli_fetch_array($sqlVTEV))
		{
			$data  = ltrim(rtrim($resCTEV['cs_id'],","),",");
			$datas = explode(",",$data);
			$v2e_viewed += count($datas);
		}
		return $v2e_viewed;
	}
	
	function vtoe_view_add($userId,$csId)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		$csId    = $this->filter_mysql($this->filter_numeric($csId));
		$today   = date("Y-m-d");
		
		$flag = 0;
		$dataMV = parent::selectData(TABLE_VTOE_MEMBER_VIEW,"","vmv_status='Active' and vmv_created_dt ='".$today."' and user_id ='".$userId."'",1);
		if($dataMV['cs_id']!="")
		{
			$dataV = explode(",",trim($dataMV['cs_id'],","));
			if(in_array($csId,$dataV))
			{
				$flag = 1;
			}
		}
		
		if($flag==0)
		{
			$sqlVTCS = parent::selectData(TABLE_VTOE_CAMPAIGN_SETUP,"","cs_id='".$csId."' and cs_view > cs_view_used and cs_asimi > cs_asimi_used",1);
			if($sqlVTCS['cs_id']!='')
			{
				$arrVTCS['cs_asimi_used'] = $sqlVTCS['cs_asimi_used'] + $sqlVTCS['cs_view_cost'];
				$arrVTCS['cs_view_used']  = $sqlVTCS['cs_view_used'] + 1;
				 
	
				if($arrVTCS['cs_view_used'] <= $sqlVTCS['cs_view'])
				{
					parent::updateData(TABLE_VTOE_CAMPAIGN_SETUP,$arrVTCS,"cs_id='".$csId."'");
				
				////////////////////////////////Member_View Earning//////////////
				$sqlVME =  parent::selectData(TABLE_VTOE_MEMBER_EARNING,"","user_id='".$userId."' and vme_date='".$today."'",1);
				if($sqlVME['vme_id']!='')
				{
					$vmU['vme_asimi']  = $sqlVME['vme_asimi'] + ($sqlVTCS['cs_view_cost']*.7);
					$vmU['vme_update'] = date("Y-m-d H:i:s");
					parent::updateData(TABLE_VTOE_MEMBER_EARNING,$vmU,"vme_id='".$sqlVME['vme_id']."'");
				}
				else
				{
					$vmI['user_id']    = $userId;
					$vmI['vme_asimi'] = ($sqlVTCS['cs_view_cost']*.7);
					$vmI['vme_date']  = date("Y-m-d");
					$vmI['vme_update'] = date("Y-m-d H:i:s");
					parent::insertData(TABLE_VTOE_MEMBER_EARNING,$vmI);
				}
				
				//////////////////////////Wallet_Update///////////////
					$sqlWA = parent::selectData(TABLE_USER_WALLET,"","wall_type='ve' and user_id='".$userId."' and wall_created='".$today."'",1);
					if($sqlWA['wall_id']!='')
					{
						$wallU['wall_asimi'] = $sqlWA['wall_asimi']+($sqlVTCS['cs_view_cost']*.7);
						parent::updateData(TABLE_USER_WALLET,$wallU,"wall_id='".$sqlWA['wall_id']."'");
					}
					else
					{
							$wallI['wall_type']     = 've';
							$wallI['user_id']       = $userId;
							$wallI['wall_asimi']    = ($sqlVTCS['cs_view_cost']*.7);					
							$wallI['wall_pstatus']  = 'p';
							$wallI['wall_created']  = $today;
							$wallI['wall_time']     = date("H:i:s");
							$wallI['wall_modified'] = date("Y-m-d H:i:s");
							parent::insertData(TABLE_USER_WALLET,$wallI);
					}
					
					
					//////////////////////////////////V2E member view update//////////////////////////
					$sqlVC   = parent::selectData(TABLE_VTOE_MEMBER_VIEW,"","user_id='".$userId."' and vmv_created_dt='".$today ."'",1);
					$csdata = parent::selectData(TABLE_VTOE_CAMPAIGN_SETUP,"","cs_id='".$csId."'",1);
					if($userId =='5' || $userId =='26' || $userId =='52' || $userId =='29273') $totAdPermission = 50;
					else $totAdPermission = 50;
					
					if($sqlVC['vmv_id']!='')
					{			
						if($csdata['cs_type'] =='p' && $sqlVC['vmv_tot_pvtoe']<5)
						{
							$dataVID['vmv_tot_pvtoe'] = $sqlVC['vmv_tot_pvtoe'] + 1;
						}
						else if($csdata['cs_type'] =='g' && $sqlVC['vmv_tot_gvtoe']< $totAdPermission)
						{
							$dataVID['vmv_tot_gvtoe'] = $sqlVC['vmv_tot_gvtoe'] + 1;
						}
						$dataVID['cs_id'] = ",".rtrim(ltrim($sqlVC['cs_id'],","),",").",".$csId.",";
						$dataVID['vmv_modified'] = date("Y-m-d H:i:s");			
						parent::updateData(TABLE_VTOE_MEMBER_VIEW,$dataVID,"vmv_id='".$sqlVC['vmv_id']."'");
					}	
					else
					{	
						if($csdata['cs_type'] =='p')
						{
							$dataVID['vmv_tot_pvtoe'] = 1;
						}
						else if($csdata['cs_type'] =='g')
						{
							$dataVID['vmv_tot_gvtoe'] = 1;
						}
						$dataVID['user_id']         = $userId;
						$dataVID['cs_id'] 	        = ",".$csId.",";
						$dataVID['vmv_created_dt']  = date("Y-m-d");
						$dataVID['vmv_modified'] 	= date("Y-m-d H:i:s");
						parent::insertData(TABLE_VTOE_MEMBER_VIEW,$dataVID,"vmv_id='".$sqlVC['vmv_id']."'");
					}
					
					
					
					
					////////////////////V2E Ad Clicks earning/////////////////
					if($_SESSION['monad_id']!='' && $_SESSION['monad_id']!=0)
					{
						$monadArr['monad_earning'] = ($sqlVTCS['cs_view_cost']*.7);
						parent::updateData(TABLE_MONITORING_ADCLICKS,$monadArr,"monad_id='".$_SESSION['monad_id']."'");
					}
					
				/////////////////////////////////Affiliate Earning////////////////////////////////////////
				
					$affiliate = $this->get_affiliate($userId);
					if($affiliate>0)
					{
						$sqlWA = parent::selectData(TABLE_USER_WALLET,"","wall_type='vea' and order_id ='' and user_id='".$affiliate."' and wall_created='".$today."'",1);
						if($sqlWA['wall_id']!='')
						{
							$wallU['wall_asimi'] = $sqlWA['wall_asimi'] + ($sqlVTCS['cs_view_cost']*.1);
							parent::updateData(TABLE_USER_WALLET,$wallU,"wall_id='".$sqlWA['wall_id']."'");
						}
						else
						{
								$wallI['wall_type']     = 'vea';
								$wallI['user_id']       = $affiliate;
								$wallI['wall_asimi']    = ($sqlVTCS['cs_view_cost']*.1);						
								$wallI['wall_pstatus']  = 'p';
								$wallI['wall_created']  = $today;
								$wallI['wall_time']     = date("H:i:s");
								$wallI['wall_modified'] = date("Y-m-d H:i:s");
								parent::insertData(TABLE_USER_WALLET,$wallI);
						}
					}	
				}	
			}  
		}
		
		return $flag;	
	}
	function raffle_entry($userId)
	{
		$userId  = $this->filter_numeric($userId);	
		$today   = date("Y-m-d");
		$total_viewed = $this->total_vtoe_view_by_date($userId,$today);
		$total_gen_view = $this->total_genral_vtoe_view_by_date($userId);
		$entry = 0;
		//if($total_gen_view==10) $entry = 1;
		/*if($userId =='5' || $userId =='26' || $userId =='52' || $userId =='29273')
		{
			if($total_viewed == 50) $entry = 2;
			else if($total_viewed >= 25 && $total_viewed < 50) $entry = 1;
		}
		else
		{
			if($total_viewed == 25) $entry = 2;
			else if($total_viewed >= 10 && $total_viewed < 25) $entry = 1;
		}		
		*/
		if($total_viewed == 50) $entry = 2;
		else if($total_viewed >= 25 && $total_viewed < 50) $entry = 1;
		 
		$sqlRE = parent::selectData(TABLE_RAFFLE_ENTRY,"count(re_id) as totalE","user_id='".$userId."' and re_cat='re' and re_added='".$today."'",1);
		if($sqlRE['totalE'] < $entry)
		{
			if($entry==1)
			{
				$dataI['user_id']    = $userId;
				$dataI['re_type']    = $entry;
				$dataI['re_win']     = 'n';
				$dataI['re_added']   = $today;
				$dataI['re_updated'] = date("Y-m-d H:i:s");
				parent::insertData(TABLE_RAFFLE_ENTRY,$dataI);
				 
				// if($userId =='5' || $userId =='52' || $userId =='44129' || $userId =='6' || $userId =='60432' || $userId =='337270' || $userId =='43') {}
					$mreArr = array();
					$mreArr['user_id']     = $userId;
					$mreArr['mre_cat']     = 'vtoe';
					$mreArr['mre_win']     = 'n';
					$mreArr['mre_added']   = $today;
					$mreArr['mre_updated'] = date("Y-m-d H:i:s");
					parent::insertData(TABLE_MINTING_RAFFLE_ENTRY,$mreArr);
				
				 
				
			} 
			else if($entry==2)
			{
				$dataU['user_id']    = $userId;
				$dataU['re_type']    = $entry;
				$dataU['re_win']     = 'n';
				$dataU['re_added']   = $today;
				$dataU['re_updated'] = date("Y-m-d H:i:s");
				parent::insertData(TABLE_RAFFLE_ENTRY,$dataU);
			}
			 
		}
		/*
		$total_premium_viewed = $this->total_preminum_vtoe_view_by_date($userId);
		if($total_premium_viewed == 5)
		{
			$resc = parent::selectData(TABLE_RAFFLE_ENTRY,"","user_id='".$userId."' and re_added='".$today."' and re_cat='res'",1);
			if($resc['user_id']=='')
			{
				$dataUP['user_id']    = $userId;
				$dataUP['re_cat']     = 'res';
				$dataUP['re_win']     = 'n';
				$dataUP['re_added']   = $today;
				$dataUP['re_updated'] = date("Y-m-d H:i:s");
				parent::insertData(TABLE_RAFFLE_ENTRY,$dataUP);
			}
		}*/
		
	}
	
	function raffle_winner($date)
	{
		$date  = $this->filter_mysql($date);	
		
		$win = 0;
		$win_ids   = array();
		$win_users = "";
		$sqlRE = parent::selectData(TABLE_RAFFLE_ENTRY,"","re_added='".$date."' and re_cat='re' and re_win='y'");
		while($resRE = mysqli_fetch_array($sqlRE))
		{
			$win_ids[$win]   = $resRE['re_id'];
			$win_users      .= $resRE['user_id'].",";
			$win++;
		}
		$win_users = trim($win_users);
		if($win_users=="") $win_users = "-111";
		else $win_users = rtrim($win_users,",");
		while($win<10)
		{
			$sqlREW = parent::selectData(TABLE_RAFFLE_ENTRY,"","re_added='".$date."' and re_win='n' and re_cat='re' and user_id NOT IN (".$win_users.")","","rand()","","0,1");
			while($resREW = mysqli_fetch_array($sqlREW))
			{

				$rwD['re_win'] 	   = 'y';
				$rwD['re_win_pos'] = $win+1;
				$rwD['re_updated'] = date("Y-m-d H:i:s");
				parent::updateData(TABLE_RAFFLE_ENTRY,$rwD,"re_id='".$resREW['re_id']."'");		
						
				$win_ids[$win]   = $resREW['re_id'];
				$win_users      .= ",".$resREW['user_id'];
				$win_users = ltrim($win_users,",");
				$win++;
			}
		}
		return $win_ids;
	}
	
	function raffle_stake_winner($date)
	{
		$date  = $this->filter_mysql($date);	
		$sqlRE = parent::selectData(TABLE_RAFFLE_ENTRY,"","re_added='".$date."' and re_cat='re' and re_win='y'");
		while($resRE = mysqli_fetch_array($sqlRE))
		{
			$win_users      .= $resRE['user_id'].",";
		}	
		$win_users = rtrim($win_users,",");
		if($win_users=="") $win_users = "-111";
		$win = 0;
		$win_ids   = array();
		$sqlRE = parent::selectData(TABLE_RAFFLE_ENTRY,"","re_added='".$date."' and re_cat='res' and re_win='n' and user_id NOT IN (".$win_users.")",1,"rand()");
		$win_ids[$win]   = $sqlRE['re_id'];	
		
		if($win_ids[0]!='')
		{
			$rwD['re_win'] 	   = 'y';
			$rwD['re_win_pos'] =  1;
			$rwD['re_updated'] = date("Y-m-d H:i:s");
			parent::updateData(TABLE_RAFFLE_ENTRY,$rwD,"re_id='".$win_ids[0]."'");	
		}
		return $win_ids;	
	}
	
	function raffle_banner_winner($date)
	{
		$date  = $this->filter_mysql($date);	
		$sqlRE = parent::selectData(TABLE_RAFFLE_ENTRY,"","re_added='".$date."' and re_cat in ('re','res') and re_win='y'");
		while($resRE = mysqli_fetch_array($sqlRE))
		{
			$win_users      .= $resRE['user_id'].",";
		}	
		$win_users = rtrim($win_users,",");
		if($win_users=="") $win_users = "-111";
		$win = 0;
		$win_ids   = array();
		$sqlRE = parent::selectData(TABLE_RAFFLE_ENTRY,"","re_added='".$date."' and re_cat='be' and re_win='n' and user_id NOT IN (".$win_users.")",1,"rand()");
		$win_ids[$win]   = $sqlRE['re_id'];	
		
		if($win_ids[0]!='')
		{
			$rwD['re_win'] 	   = 'y';
			$rwD['re_win_pos'] =  1;
			$rwD['re_updated'] = date("Y-m-d H:i:s");
			parent::updateData(TABLE_RAFFLE_ENTRY,$rwD,"re_id='".$win_ids[0]."'");	
		}
		return $win_ids;	
	}
		
	
	function set_lcp_tracking($page_name,$aff_ref_id)
	{	
		$tdate = date('Y-m-d');
		$lcpTD = parent::selectData(TABLE_LCP_TRACKING,"","lp_created='".$tdate."' and lp_page_name='".$page_name."'",1);
		if($lcpTD['lp_id'] !='')
		{		 	
			$lpuArr['lp_tot_display']   = $lcpTD['lp_tot_display']+1;				
			$lpuArr['lp_page_name']     = $page_name;				 
			$lpuArr['lp_modified'] 	    = date('Y-m-d');
			parent::updateData(TABLE_LCP_TRACKING,$lpuArr,"lp_id='".$lcpTD['lp_id']."'");
		}
		else 
		{			 
			$lcptArr['lp_tot_display']  = 1;				
			$lcptArr['lp_page_name']    = $page_name;			 	 
			$lcptArr['lp_created'] 		= date('Y-m-d');
			$lcptArr['lp_modified'] 	= CURRENT_DATE_TIME;
			parent::insertData(TABLE_LCP_TRACKING,$lcptArr);				
		}
		
		$ulcpD = parent::selectData(TABLE_USER_LCP_TRACKING,"","user_id='".$aff_ref_id."'",1);		 
		if($ulcpD['ulcp_id'] !='')
		{			 
			$lpuuArr['user_id']       					= $aff_ref_id;						 
			$lpuuArr['ulcp_tot_display_'.$page_name]   	= $ulcpD['ulcp_tot_display_'.$page_name]+1;			
			$lpuuArr['ulcp_page_name_'.$page_name]     	= $page_name;			
			$lpuuArr['ulcp_modified'] 	    			= CURRENT_DATE_TIME;
			parent::updateData(TABLE_USER_LCP_TRACKING,$lpuuArr,"ulcp_id='".$ulcpD['ulcp_id']."'");
		}
		else 
		{
			$ulcpArr['user_id']       					= $aff_ref_id;		
			$ulcpArr['ulcp_tot_display_'.$page_name]   	= 1;			
			$ulcpArr['ulcp_page_name_'.$page_name]     	= $page_name;			
			$ulcpArr['ulcp_created'] 					= date('Y-m-d');
			$ulcpArr['ulcp_modified'] 					= CURRENT_DATE_TIME;
			parent::insertData(TABLE_USER_LCP_TRACKING,$ulcpArr);				
		}
		
	}
	
	
	function total_preminum_vtoe_view_by_date($userId)
	{			 
		$today  = date("Y-m-d");
		$sqlVC   = parent::selectData(TABLE_VTOE_MEMBER_VIEW,"","user_id='".$userId."' and vmv_created_dt='".$today ."'",1);
		
		if($sqlVC['vmv_tot_pvtoe']) return $sqlVC['vmv_tot_pvtoe']; 
		else return 0;
		 
		 
	}
	function total_genral_vtoe_view_by_date($userId)
	{			 
		$today  = date("Y-m-d");
		$sqlVC   = parent::selectData(TABLE_VTOE_MEMBER_VIEW,"vmv_tot_gvtoe","user_id='".$userId."' and vmv_created_dt='".$today ."'",1);
		
		if($sqlVC['vmv_tot_gvtoe']) return $sqlVC['vmv_tot_gvtoe']; 
		else return 0;
		
	}
	
	
	function get_total_v2e_asimi_allocated($userId)
	{
		$resV2EAA = parent::selectData(TABLE_VTOE_CAMPAIGN_SETUP,"sum(cs_asimi) totalA","(cs_status ='Active' or cs_status ='Pause') and user_id='".$userId."' ",1);
		return $resV2EAA['totalA'];
	}
	function get_total_v2e_asimi_used($userId)
	{
		$resV2EAU = parent::selectData(TABLE_VTOE_CAMPAIGN_SETUP,"sum(cs_asimi_used) totalU","(cs_status ='Active' or cs_status ='Pause') and user_id='".$userId."'",1);
		return $resV2EAU['totalU'];
	}		
	
	function get_max_adminter_ad_setup_permission($userId)
	{
		
		$resV2EAU = parent::selectData(TABLE_ORDER." o, ".TABLE_ORDER_DETAILS." od","o.order_id,od.user_id,od.pack_id","o.order_status='Active' and o.order_pstatus='p' and od.pack_id in (1,6,7,8,9) and o.order_id=od.order_id",1,"od.pack_id desc");
		$max_allow = 5;
		if($resV2EAU['pack_id']==9)      $max_allow = 50;
		else if($resV2EAU['pack_id']==8) $max_allow = 25;
		else if($resV2EAU['pack_id']==7) $max_allow = 15;
		else if($resV2EAU['pack_id']==6) $max_allow = 10;
		
		return $max_allow;
	}
	
	function view_to_earn_ads_viewed_by_date($userId,$dt)
	{
		$userId   = $this->filter_numeric($userId);
		$dt       = $this->filter_mysql($dt);
		$myads = "";
		$imp = 0;

		$sqlMB = parent::selectData(TABLE_VTOE_CAMPAIGN_SETUP,"cs_id","user_id='".$userId."' and cs_status<>'Deleted'");
		while($resMB = mysqli_fetch_array($sqlMB))
		{			 
			$sqlMBC = parent::selectData(TABLE_VTOE_MEMBER_VIEW,"cs_id","cs_id like '%,".$resMB['cs_id'].",%' and vmv_created_dt='".$dt."'");
			while($resMBC = mysqli_fetch_array($sqlMBC))
			{				 
				$data = explode(",",trim($resMBC['cs_id'],","));
				$imp += count(array_keys($data,$resMB['cs_id']));
			}
		}
		return $imp;
	}
	 
	
	function get_total_view_to_earn_clicks_achived($userId)
	{
		$userId   = $this->filter_numeric($userId);	 
		$sqlMB = parent::selectData(TABLE_VTOE_CAMPAIGN_SETUP,"sum(cs_view_used) as tot_cs_view","user_id='".$userId."'",1);
		$clicks =  $sqlMB['tot_cs_view'];
		return $clicks;
	}
	
	function get_vtoe_setup_raffle_entry_check($userId)
	{	
		$userId   = $this->filter_numeric($userId);	 	
		$Sdate = date("Y-m-d").' 00:00:00';
		$Edate = date("Y-m-d").' 23:59:59';
	 
		$flag = 0;
		$sqlMB = parent::selectData(TABLE_VTOE_CAMPAIGN_SETUP,"","user_id='".$userId."' and cs_type ='p' and cs_modified!='0000-00-00 00:00:00' and cs_status<>'Deleted'");
		while($resMB = mysqli_fetch_array($sqlMB))
		{	  
		
			$sqlVAV = parent::selectData(TABLE_VTOE_ADD_VIEW,"","cs_id='".$resMB['cs_id']."' and vcount_type ='A' and vcount_modified >='".$Sdate."' and vcount_modified <='".$Edate."' and vcount_status ='Active' and vcount_view >= 2500");
			while($resVAV = mysqli_fetch_array($sqlVAV))
			{		
				$tot_count_view = 0;			
				$sqlVAV2 = parent::selectData(TABLE_VTOE_ADD_VIEW,"","cs_id='".$resVAV['cs_id']."' and vcount_modified >='".$Sdate."' and vcount_modified <='".$Edate."' and vcount_status !='Deleted'");
				while($data = mysqli_fetch_array($sqlVAV2))
				{						 
					if($data['vcount_type']=='A'){					
						$tot_count_view += $data['vcount_view'];	
					} else if($data['vcount_type']=='D'){
						$tot_count_view -= $data['vcount_view'];				
					}				 
				} 
				//$tot_count_view = $add_view-$delete_view;
				if($tot_count_view >= 2500)
				{
					$flag = 1;
				} 	  
				  
			}	  
		}
		return $flag;
				
	}
	
	
	
	function vtoe_setup_raffle_entry($csId)
	{ 	 
		$csId   = $this->filter_numeric($csId);  
		$vcampD = parent::selectData(TABLE_VTOE_CAMPAIGN_SETUP,"","cs_id='".$csId."' and cs_status !='Deleted'",1);		
		$refDa 	= parent::selectData(TABLE_RAFFLE_ENTRY,"","user_id='".$vcampD['user_id']."' and re_added ='".date("Y-m-d")."' and re_type ='2' and re_cat ='res'",1);	
		$refBDa = parent::selectData(TABLE_RAFFLE_ENTRY,"","user_id='".$vcampD['user_id']."' and re_added ='".date("Y-m-d")."' and re_cat ='be'",1);	
		
		$chRefD = $this->get_vtoe_setup_raffle_entry_check($vcampD['user_id']);		
		//if($vcampD['cs_view'] >= 2500 && $refDa['re_id'] == '')
		if($chRefD && $refDa['re_id']=='' && $refBDa['re_id']=='')
		{
			$dataU['user_id']    = $vcampD['user_id'];	
			$dataU['re_cat']     = 'res';
			//$dataU['re_win']     = 'n';
			$dataU['re_type']    = 2;
			$dataU['re_added']   = date("Y-m-d");
			$dataU['re_updated'] = date("Y-m-d H:i:s");
			parent::insertData(TABLE_RAFFLE_ENTRY,$dataU);
			
			$dataU['user_id']    = $vcampD['user_id'];	
			$dataU['re_cat']     = 'be';
			//$dataU['re_bwin']    = 'n';
			$dataU['re_type']    = 1;
			$dataU['re_added']   = date("Y-m-d");
			$dataU['re_updated'] = date("Y-m-d H:i:s");
			parent::insertData(TABLE_RAFFLE_ENTRY,$dataU);
		}
		else if($chRefD==0 && $refDa['re_id']!='' && $refBDa['re_id']!='')
		{
			parent::deleteData(TABLE_RAFFLE_ENTRY,"re_id='".$refDa['re_id']."'");
			parent::deleteData(TABLE_RAFFLE_ENTRY,"re_id='".$refBDa['re_id']."'");	
		}	
		 
	}
	
	function page_track($id)
	{	
		$id  = $this->filter_numeric($id);
		$today = date("Y-m-d");
		$sqlPT = parent::selectData(TABLE_PAGE_TRACK,"","pt_date='".$today."'",1);
		if($sqlPT['pt_id'])
		{
			$arrPTU['pt_count_'.$id] = 	$sqlPT['pt_count_'.$id]+1;	
			parent::updateData(TABLE_PAGE_TRACK,$arrPTU,"pt_id='".$sqlPT['pt_id']."'");
		}
		else
		{
			$arrPTI['pt_count_'.$id] = 	1;	
			$arrPTI['pt_date'] 		 = 	$today;	
			parent::insertData(TABLE_PAGE_TRACK,$arrPTI);
		}
	}
	
	function get_total_minting_package_by_member($userId)
	{    
		$userId  = $this->filter_numeric($userId);
		//$extraw .= "and od.od_pack_val_days > od.od_pack_val_used";	
		$today  = date("Y-m-d");
		$extraw .= " and od.od_pack_validity >= '".$today."'";		
		$sqlS = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","sum(pack_quan) as sold","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id='5' and od.od_status ='Active' and od.user_id='".$userId."'".$extraw."",1);
		if($sqlS['sold']) return $sqlS['sold'];
		else return "0";
		
	}
	
	function get_total_adv_package_by_member($userId)
	{ 	 
		$userId  = $this->filter_numeric($userId);
		$sqlS = parent::selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","sum(pack_quan) as sold","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and (od.pack_id not in (5,0,12) or lap_id !='0') and od.od_status ='Active' and od.user_id='".$userId."'".$extraw."",1);
		if($sqlS['sold']) return $sqlS['sold'];
		else return "0";
		
	}
	


	function get_date_time_difference($end)
	{
		$start = date("Y-m-d H:i:s",mktime(date("H"),date("i"),date("s"),date("m"),date("d")+5,date("Y")));
		$uts['start']      =    strtotime( $start );
		$uts['end']        =    strtotime( $end );
		if( $uts['start']!==-1 && $uts['end']!==-1 )
		{
			if( $uts['end'] >= $uts['start'] )
			{
				$diff    =    $uts['end'] - $uts['start'];
				if( $days=intval((floor($diff/86400))) )
					$diff = $diff % 86400;
				if( $hours=intval((floor($diff/3600))) )
					$diff = $diff % 3600;
				if( $minutes=intval((floor($diff/60))) )
					$diff = $diff % 60;
				$diff    =    intval( $diff );
				//return( array('years'=>$years,'months'=>$months,'days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
				
				return "".$days." days ".$hours." hrs ".$minutes." min ".$diff." sec";
				
			}
			else
			{
				return "Ending date/time is earlier than the start date/time";
			}
		}
		else
		{
			return "Invalid date/time data detected";
		}
	}

	function get_date_time_difference_st($end)
		{
			$start = date("Y-m-d H:i:s",mktime(date("H"),date("i"),date("s"),date("m"),date("d")+4,date("Y")));
			$uts['start']      =    strtotime( $start );
			$uts['end']        =    strtotime( $end );
			if( $uts['start']!==-1 && $uts['end']!==-1 )
			{
				if( $uts['end'] >= $uts['start'] )
				{
					$diff    =    $uts['end'] - $uts['start'];
					if( $days=intval((floor($diff/86400))) )
						$diff = $diff % 86400;
					if( $hours=intval((floor($diff/3600))) )
						$diff = $diff % 3600;
					if( $minutes=intval((floor($diff/60))) )
						$diff = $diff % 60;
					$diff    =    intval( $diff );
					//return( array('years'=>$years,'months'=>$months,'days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
					
					return "".$days." days ".$hours." hrs ".$minutes." min ".$diff." sec";
					
				}
				else
				{
					return "Ending date/time is earlier than the start date/time";
				}
			}
			else
			{
				return "Invalid date/time data detected";
			}
		}


	function select_available_login_ad_slot()
	{
		$str .= '<option Value="">Select A Login Ad Slot</option>';
		$fromDate = date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y"))); 
		$extra .= " and lap_date  >= '".$fromDate."'";
		$sqlLP = parent::selectData(TABLE_LOGIN_AD_PACKAGE,"","lap_status='Active' and lap_pstatus='u' and lap_url_1='' ".$extra,"","lap_date asc");
		while($resLP = mysqli_fetch_array($sqlLP))
		{
			$str.="<option value='".$resLP['lap_id']."'";
		
			$str.="> Login Ad - ".date("d F,Y",strtotime($resLP['lap_date']))."</option>";
		}
		return $str;
	}
	
	function select_available_login_ad_staker_slot()
	{
		$str .= '<option Value="">Select A Login Ad Stakers Slot</option>';
		$fromDate = date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y"))); 
		$extra .= " and lasp_date  >= '".$fromDate."'";
		$sqlLP = parent::selectData(TABLE_LOGIN_AD_STAKER_PACKAGE,"","lasp_status='Active' and lasp_pstatus='u' and lasp_url_1='' ".$extra,"","lasp_date asc");
		while($resLP = mysqli_fetch_array($sqlLP))
		{
			$str.="<option value='".$resLP['lasp_id']."'";
		
			$str.="> Login Ad Stakers- ".date("d F,Y",strtotime($resLP['lasp_date']))."</option>";
		}
		return $str;
	}
	
	function select_available_login_ad_date()
	{
		 
		$fromDate = date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y"))); 
		$extra .= " and lap_date  >= '".$fromDate."'";
		$i=0;
		$sqlLP = parent::selectData(TABLE_LOGIN_AD_PACKAGE,"","lap_status='Active' and lap_pstatus='u' and lap_url_1='' ".$extra,"","lap_date asc");
		while($resLP = mysqli_fetch_array($sqlLP))
		{ 
			$lapDates[$i]= date('j-n-Y', strtotime($resLP['lap_date']));	
			$i++;						
		}
		
		if(count($lapDates)<1) $lapDates[0] = '';
		return($lapDates);
	}
	
	function select_available_login_ad_staker_date()
	{
		 
		$fromDate = date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y"))); 
		$extra .= " and lasp_date  >= '".$fromDate."'";
		$i=0;
		$sqlLP = parent::selectData(TABLE_LOGIN_AD_STAKER_PACKAGE,"","lasp_status='Active' and lasp_pstatus='u' and lasp_url_1='' ".$extra,"","lasp_date asc");
		while($resLP = mysqli_fetch_array($sqlLP))
		{ 
			$laspDates[$i]= date('j-n-Y', strtotime($resLP['lasp_date']));	
			$i++;						
		}
		
		if(count($lapDates)<1) $laspDates[0] = '';
		return($laspDates);
	}
	
	
	
	function update_member_ad_block($userId)
	{ 
		$userId  = $this->filter_numeric($userId);		 		
		$tot_adv_pack = $this->get_total_adv_package_by_member($userId);
		$tot_stake    = $this->get_total_minting_package_by_member($userId);				 
		$arrUU['user_tot_adv'] 	  = $tot_adv_pack;
		$arrUU['user_tot_stake']  = $tot_stake;
		parent::updateData(TABLE_USER,$arrUU,"u_login_id='".$userId."'");		
	}
	
	function get_gmail_user_cleartext($email_id)
	{
		$email_filter = $this->filter_mysql($email_id);
		
		$email = isset($email_filter) ? strtolower(trim($email_filter))  : '';
		list ($emailuser, $emaildomain) = array_pad(explode("@", $email, 2), 2, null);
		list ($emailuser, $emailidentifier) = array_pad(explode("+", $emailuser, 2), 2, null);
		$gmail_user_cleartext = str_replace(".", "", $emailuser);
		
		return $gmail_user_cleartext;
	}
	
	function monitor_login_ads($userId,$ip)
	{	
		$userId  = $this->filter_numeric($userId);	
		$userD = parent::selectData(TABLE_USER,"user_referrer,u_login_id","u_login_id='".$userId."'",1);				
		$mlArr['monl_ip']    		= $ip;
		$mlArr['monl_browser']    	= $_SERVER['HTTP_USER_AGENT'];
		$mlArr['user_id']    		= $userId;
		$mlArr['referrer_id']    	= $userD['user_referrer'];
		$mlArr['monl_date']   		= CURRENT_DATE_TIME;
		$monID =  parent::insertData(TABLE_MONITORING_LOGINS,$mlArr);
		return $monID;
	}
	
	function monitor_making_changes($userId,$ip,$msges,$type)
	{	
		$userId  = $this->filter_numeric($userId);			 
		$mlArr['monc_ip']    		= $ip;
		$mlArr['monc_type']    		= $type;
		$mlArr['monc_changes']    	= $msges;
		$mlArr['monc_browser']    	= $_SERVER['HTTP_USER_AGENT'];
		$mlArr['user_id']    		= $userId;			 
		$mlArr['monc_date']   		= CURRENT_DATE_TIME;
		parent::insertData(TABLE_MONITORING_CHANGES,$mlArr);
		
	}
	
	function monitor_ad_clicks($userId,$ip,$csId,$type)
	{	
		$userId  = $this->filter_numeric($userId);			 
		$mlArr['monad_ip']    		= $ip;
		$mlArr['monad_type']    	= $type;
		$mlArr['monad_ad_id']    	= $csId;		
		$mlArr['monad_browser']    	= $_SERVER['HTTP_USER_AGENT'];
		$mlArr['user_id']    		= $userId;			 
		$mlArr['monad_date']   		= CURRENT_DATE_TIME;
		$monadId = parent::insertData(TABLE_MONITORING_ADCLICKS,$mlArr);
		$_SESSION['monad_id'] = $monadId; 
	}
	
	
	function total_affiliate_income_generated($userId)
	{
		$totalP = 0;
		$totalVEA = 0;
		$totalRW = 0;
		$totalME = 0;
		$total_aff_inc = 0;
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		$query = "SELECT sum(wall_asimi) as total_purchase_aff from ".TABLE_USER_WALLET." where (wall_type='a' or wall_type='laa') and wall_pstatus='p' and order_id IN (SELECT order_id from ".TABLE_ORDER." where user_id='".$userId."')";
		$sql = parent::query_run($query); 		 
		while($data=mysqli_fetch_array($sql)) 
		{
			$totalP = $data['total_purchase_aff'];
		}	
		
		$dataVE = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_v2e_aff","wall_type='ve' and wall_pstatus='p' and  user_id='".$userId."'",1);			
		$totalVEA =	$dataVE['total_v2e_aff']/7;
		

		$dataRW = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_rw_aff","wall_type='rw' and wall_pstatus='p' and  user_id='".$userId."'",1);			
		$totalRW = 	$dataRW['total_rw_aff']/10;
		
		$sqlME = parent::selectData(TABLE_MEMBER_EARNING,"sum(me_share_coin) as total_asimi_mint_aff","user_id='".$userId."'",1);	
		$totalME = 	$sqlME['total_asimi_mint_aff']/10;	
		
		$total_aff_inc = $totalP + $totalVEA + $totalRW + $totalME;
		
		return $total_aff_inc;
	}
	
	
	function total_advertising_display_delivered()
	{
		$totalADY = 0;
		$adMID = parent::selectData(TABLE_AD_DIRECTORY_LISTING,"sum(adl_impression_view) as total_delivered","",1);
		$totalADY = $adMID['total_delivered'];
		$sqlLP = parent::selectData(TABLE_LOGIN_AD_PACKAGE,"sum(lap_url1_tot_view) as total_url1_view, sum(lap_url2_tot_view) as total_url2_view","lap_status='Active' and lap_pstatus !='u' ",1);
		$tot_login_ad_view = $sqlLP['total_url1_view']+$sqlLP['total_url2_view'];
		$queryV2EV  = "select (sum(length(cs_id)-length(replace(cs_id,',',''))) - 1) as total_v2e_viewed from ".TABLE_VTOE_MEMBER_VIEW." where 1 ";
		$sqlV2EVIEW = parent::query_run($queryV2EV);
		$resV2EVIEW = mysqli_fetch_array($sqlV2EVIEW); 	
		$totalVMV   = $resV2EVIEW['total_v2e_viewed'];	 	
		$tot_adv_display_deliver = ($totalADY+$tot_login_ad_view+$totalVMV);
		if($tot_adv_display_deliver) return($tot_adv_display_deliver);
		else return 0;
		 
	}
	
	
	function total_banner_impression_delivered()
	{
		$sqlBAIV = parent::selectData(TABLE_BANNER_ADS,"sum(banad_view_imp_ach) as total_banad_viewed","",1);
		
		$tot_ban_ad_imp_deliverd = $sqlBAIV['total_banad_viewed'];		
		if($tot_ban_ad_imp_deliverd) return($tot_ban_ad_imp_deliverd);
		else return 0;
		 
	}
	
	 
	function total_v2e_earning($userId)
	{
		$ucredit = 0;
		$userId  = $this->filter_numeric($userId);	
		$sqlU = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_v2e_earn_asimi","wall_status='Active' and ( wall_type='ve') and wall_pstatus='p' and user_id='".$userId."'",1);
		$ucredit = $sqlU['total_v2e_earn_asimi'];
		return $ucredit;
	}	
	
	function all_total_v2e_earning_old()
	{
		
		$ucredit = 0;
		$sqlU = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_v2e_earn_asimi","wall_status='Active' and ( wall_type='ve') and wall_pstatus='p'",1);
		$ucredit = $sqlU['total_v2e_earn_asimi'];
		return $ucredit;
	}	
	
	function all_total_v2e_earning()
	{
		$yesterday = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
		$settingsDate =  parent::selectData("settings","","name='cron_live_data_date'",1);
		if($settingsDate['value']!=$yesterday)
		{
			
			$total_v2e_sql = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_v2e_earn_asimi","wall_status='Active' and  wall_type='ve' and wall_pstatus='p' and wall_created<='".$yesterday."'",1);
			$settings_data['value'] = $yesterday;
			parent::updateData("`settings`", $settings_data, "`name` = 'cron_live_data_date'");
			$settings_data = array();
			$settings_data['value'] = $total_v2e_sql['total_v2e_earn_asimi'];
			parent::updateData("`settings`", $settings_data, "`name` = 'cron_live_data_v2e_asmi'");
			
		}
		$settingsv2eAsimi =  parent::selectData("settings","","name='cron_live_data_v2e_asmi'",1);
		$ucredit = 0;
		$sqlU = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_v2e_earn_asimi","wall_status='Active' and ( wall_type='ve') and wall_pstatus='p' and wall_created>'".$yesterday."'",1);
		$ucredit = $sqlU['total_v2e_earn_asimi']+$settingsv2eAsimi['value'];
		return $ucredit;
	}	
	
	function total_purchase($userId)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));		
		$pcredit = 0;				
		$uwsql = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_purchase","wall_status='Active' and (wall_type='p') and wall_pstatus='p' and user_id='".$userId."' and wall_created>='".$start_date."'",1);
		$pcredit = $uwsql['total_purchase'];					
		
		if($pcredit) return($pcredit);
		else return 0;
	}
	
	function total_deposit($userId)
	{
		$deposit = 0;
		$userId  = $this->filter_numeric($userId);	
		$sqlU = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_deposit","wall_status='Active' and ( wall_type='d') and wall_pstatus='p' and user_id='".$userId."'",1);
		$deposit = $sqlU['total_deposit'];
		if($deposit) return($deposit);
		else return 0;
	}	
	
	function total_withdrawal($userId)
	{
		$withdraw = 0;
		$userId  = $this->filter_numeric($userId);	
		$sqlU = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_withdraw","wall_status='Active' and ( wall_type='w' or wall_type='mw') and wall_pstatus='p' and user_id='".$userId."'",1);
		$withdraw = $sqlU['total_withdraw'];
		if($withdraw) return($withdraw);
		else return 0;
	}	
	 
	function total_withdrawal_doller($userId)
	{
		$withdraw = 0;
		$userId  = $this->filter_numeric($userId);	
		$sqlU = parent::selectData(TABLE_USER_WALLET,"sum(wall_price) as total_withdraw","wall_status='Active' and ( wall_type='w' or wall_type='mw') and wall_pstatus='p' and user_id='".$userId."'",1);
		$withdraw = $sqlU['total_withdraw'];
		if($withdraw) return($withdraw);
		else return 0;
	}
	
	function prelaunch_balance_transfer($userId)
	{
		$pbtransfer = 0;
		$userId  = $this->filter_numeric($userId);	
		$sqlU = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_pb_transfer","wall_status='Active' and ( wall_type='pbt') and wall_pstatus='p' and user_id='".$userId."'",1);
		$pbtransfer = $sqlU['total_pb_transfer'];
		if($pbtransfer) return($pbtransfer);
		else return 0;
	}
	
	function thousandsCurrencyFormat($num)
	{
		if( $num > 1000 ) 
		{
			$x = round($num);
			$x_number_format = number_format($x);
			$x_array = explode(',', $x_number_format);
			$x_parts = array('K', 'M', 'B', 'T');
			$x_count_parts = count($x_array) - 1;
			$x_display = $x;
			$x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
			$x_display .= $x_parts[$x_count_parts - 1];			
			return $x_display;
		}		
		return $num;
	}
	
	function get_member_login_stake($userId)
	{
		$tot_login_stake = 0;
		$userId  = $this->filter_numeric($userId);	
		$sqlLS 	= parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_login_stake","wall_status='Active' and wall_type='ls' and wall_pstatus='p' and user_id='".$userId."'",1);
		$sqlLUS = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_login_unstake","wall_status='Active' and wall_type='lus' and wall_pstatus='p' and user_id='".$userId."'",1);
		$tot_login_stake = $sqlLS['total_login_stake']-$sqlLUS['total_login_unstake'];		
		if($tot_login_stake) return($tot_login_stake);
		else return 0;
	}
	
	function getcleantext($string) 
	{	 
		$string = preg_replace('/\s+/', '-', $string); // 1
		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // 2
		$string = trim($string, "-"); // 3
		$string = strtolower($string); // 4
		$stringr = preg_replace('/--+/', '-', $string); // 5
		return $stringr;	 
		
	}

	function get_article_urlcount($ar_title)
	{
		$resPV = parent::selectData(TABLE_ARTICLE,"count(ar_id) as tot_article","ar_status<>'Deleted' and ar_title ='".$ar_title."'",1);
		return $resPV['tot_post'];
	}

	function get_premium_banner_ads_to_view($type,$userId)
	{
		$type   = $this->filter_mysql($type);
		$userId = $this->filter_mysql($userId);
		$adC = 0;
		$banS = parent::selectData(TABLE_BANNER_AD_LAST_SEEN,"","bls_type='".$type."'",1);
		$last_seen_id = $banS['banad_id'];
		$extra = " and lb_id > '".$last_seen_id."'";
		$today  = date("Y-m-d");	
		$resU = parent::selectData(TABLE_LEADERBOARD_BANNER,"lb_id","lb_status='Active' and lb_type ='".$type."' and (lb_click > lb_click_ach or ('".$today."' <= lb_end_date && lb_ad_type ='t') or ('".$today."' <= lb_end_date && lb_ad_type ='c' and order_id != '')) ".$extra."",1,"lb_id asc");
		if($resU['lb_id']!='')
		{
			$ordID = parent::selectData(TABLE_LEADERBOARD_BANNER,"order_id,impression","lb_id='".$resU['lb_id']."'",1);
			//$lbArr['lb_type']   	= $type;
			//$lbArr['user_id']   	= $userId;
			//$lbArr['lbu_type']   	= 'v';
			if($ordID['order_id'] > 0){
				$lbArr['impression']   		= $ordID['impression']+1;
				parent::updateData(TABLE_LEADERBOARD_BANNER,$lbArr,"lb_id='".$resU['lb_id']."'");
			}
			$lbArr['lb_id']   		= $resU['lb_id'];
			$lbArr['lbu_created']   = CURRENT_DATE_TIME;							
			parent::insertData(TABLE_LEADERBOARD_UPDATE,$lbArr);
			$adlIds = $resU['lb_id'];
			$adC++;
		}
		if ($adC<1) 
		{ 
			$extraa = " and lb_id <= '".$last_seen_id."'";
			$resUU = parent::selectData(TABLE_LEADERBOARD_BANNER,"lb_id","lb_status='Active' and lb_type ='".$type."'  and (lb_click > lb_click_ach or ('".$today."' <= lb_end_date && lb_ad_type ='t')) ".$extraa."",1,"lb_id asc");
			if($resUU['lb_id']!='')
			{
				//$lbArr['lb_type']   	= $type;
				//$lbArr['user_id']   	= $userId;
				//$lbArr['lbu_type']   	= 'v';
				$lbArr['lb_id']   		= $resUU['lb_id'];
				$lbArr['lbu_created']   = CURRENT_DATE_TIME;							
				parent::insertData(TABLE_LEADERBOARD_UPDATE,$lbArr);
				$adlIds = $resUU['lb_id'];
				$adC++;
			}
		}
		# Update Last Seen ID
		$last_id = $adlIds;
		if ($last_id) 
		{
			$lastSD['banad_id'] = $last_id;
			$lastSD['bls_update'] = date("Y-m-d H:i:s");
			parent::updateData(TABLE_BANNER_AD_LAST_SEEN,$lastSD,"bls_type='".$type."'");
		}
		if ($adlIds != "") 
		{
			return $adlIds;
		}
		return "0";
	}
	

	
	function get_leaderboard_banner_view($lb_id)
	{ 
		$tot_views 	= 0; 
		$tot_ledBan = parent::selectData(TABLE_LEADERBOARD_BANNER,"lb_view_ach","lb_id='".$lb_id."'",1); 
		$tot_views 	= $tot_ledBan['lb_view_ach'];
		
		return($tot_views);
		 
	}
	
	function floorDec($val, $precision = 8) 
	{
		$final_val = 0;
		if ($precision < 0) { $precision = 0; }
		$numPointPosition = intval(strpos($val, '.'));
		if ($numPointPosition === 0) { 
			return $val;
		}
		$new_val = floatval(substr($val, 0, $numPointPosition + $precision + 1));
		if($val > $new_val)
		{
			$final_val = $new_val + .00000001;
		}
		else
		{
			$final_val = $new_val;
		}
    	return rtrim(number_format($final_val,8),"0");
	}
	
	function get_withdraw_account_balance($userId)
	{  
		$userId  			= $this->filter_numeric($userId);
		$avl_balance 		= $this->total_available_balance_launch($userId);
		$deposit_balance 	= $this->get_deposit_balance($userId);		 
		$balance 			= $avl_balance-$deposit_balance;	 
		if($balance) return $balance;
		else return "0";	 
	}
	
	 
	function get_deposit_balance($userId)
	{  
		$userId  = $this->filter_numeric($userId);
		$date  = '2019-10-10';
		$lists=parent::selectData(TABLE_USER_WALLET,"","wall_status='Active' and wall_pstatus='p' and wall_type in ('d','btcp','ethp','p','vep','lap','ls','mw','ref') and wall_created >='".$date."' and user_id='".$userId."'","","wall_id asc");		 
		
		$deposit = 0;
		
		while($data=mysqli_fetch_array($lists)) 
		{
			
			if($data['wall_type'] =='d' || $data['wall_type'] =='btcp' || $data['wall_type'] =='ethp' || $data['wall_type'] =='ref')
			{
				$deposit += $data['wall_asimi'];
			}
			else if($data['wall_type'] =='p' || $data['wall_type'] =='vep' || $data['wall_type'] =='lap' || $data['wall_type'] =='ls' || $data['wall_type'] =='mw')
			{ 
				$deposit = $deposit - $data['wall_asimi'];
			}		
		} 

		if($deposit>0) return $deposit;
		else return "0";	 
		 
	}
	
	function get_member_deposit_amount($userId)
	{  
		$userId = $this->filter_numeric($userId);
		$date  	= '2019-10-10';
		$data	= parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as tot_deposit","wall_status='Active' and wall_pstatus='p' and wall_type ='d' and user_id='".$userId."'","1");		 
		$deposit = $data['tot_deposit'];  
		if($deposit>0) return $deposit;
		else return "0";	 
		 
	}
	
	function get_total_v2e_view_delivered($userId)
	{
		$resV2EAU = parent::selectData(TABLE_VTOE_CAMPAIGN_SETUP,"sum(cs_view_used) totalU","cs_type ='g' and user_id='".$userId."'",1);
		 
		if($resV2EAU['totalU']>0) return $resV2EAU['totalU'];
		else return "0";	
	}
	
	function get_total_premium_v2e_view_delivered($userId)
	{
		$resV2EAU = parent::selectData(TABLE_VTOE_CAMPAIGN_SETUP,"sum(cs_view_used) totalU","cs_type ='p' and user_id='".$userId."'",1);
		if($resV2EAU['totalU']>0) return $resV2EAU['totalU'];
		else return "0";
	}
	
	function get_total_login_ad_view_delivered($userId)
	{
		$resLAP = parent::selectData(TABLE_LOGIN_AD_PACKAGE,"sum(lap_url1_tot_view+lap_url2_tot_view) totalU","lap_pstatus ='p' and user_id='".$userId."'",1);	 
		if($resLAP['totalU']>0) return $resLAP['totalU'];
		else return "0";
	}	
	
	function affiliate_sales_content_prizes($num)
	{	 
		if($num ==1)
		{
			$str.='<td class="right_broder2" width="33%">3 Asimi 1 oz Silver Bullion Coins</td>';
			$str.='<td class="right_broder2" width="33%">100% Advertising Sales Volume</td>';
			$str.='<td>3 Free Minter Stakes</td>';
		}
		else if($num ==2)
		{
			$str.='<td class="right_broder2" width="33%">2 Asimi 1 oz Silver Bullion Coins</td>';
			$str.='<td class="right_broder2" width="33%">80% Advertising Sales Volume</td>';
			$str.='<td>2 Free Minter Stakes</td>';
		}
		else if($num ==3)
		{
			$str.='<td class="right_broder2" width="33%">1 Asimi 1 oz Silver Bullion Coins</td>';
			$str.='<td class="right_broder2" width="33%">60% Advertising Sales Volume</td>';
			$str.='<td>1 Free Minter Stake</td>';
		}
		else if($num ==4)
		{
			$str.='<td class="right_broder2" width="33%">50% Advertising Sales Volume</td>';
			$str.='<td width="64%">1 Free Minter Stake</td>';			
		}
		else if($num ==5)
		{
			$str.='<td class="right_broder2" width="33%">30% Advertising Sales Volume</td>';
			$str.='<td width="64%">1 Free Minter Stake</td>';			 
		}
		else if($num ==6 || $num ==7)
		{
			$str.='<td class="right_broder2" width="33%">25% Advertising Sales Volume</td>';
			$str.='<td width="64%">1 Free Minter Stake</td>';			 
		}
		else if($num ==8 || $num ==9 || $num ==10)
		{
			$str.='<td width="100%">25% Advertising Sales Volume</td>';
			 			 
		}
		else
		{
			$str.='<td width="100%">No prize</td>';	
		}
		 
		return $str;
	}

	function top_minters_all_prizes($num)
	{
		if($num ==1) $str.='5 x Asimi 1 oz Silver Bullion Coin';
		else if($num ==2)$str.='4 x Asimi 1 oz Silver Bullion Coin';
		else if($num ==3)$str.='3 x Asimi 1 oz Silver Bullion Coin';
		else if($num ==4 || $num ==5 || $num ==6 || $num ==7 || $num ==8 || $num ==9 || $num ==10)$str.='1 x Asimi 1 oz Silver Bullion Coin';
		else $str.='No prize';			
		return	$str;	
	}

	function top_affiliates_prizes($num)
	{
		if($num ==1)
		{
			$str.='<td class="right_broder2" width="33%">3 x Asimi 1 oz Silver Bullion Coin</td>';
			$str.='<td class="right_broder2" width="33%">10% Advertising Sales Volume Bonus</td>';
			$str.='<td>7 x Login Ad Day</td>';		
		}
		else if($num ==2)
		{
			$str.='<td class="right_broder2" width="33%">2 x Asimi 1 oz Silver Bullion Coin</td>';
			$str.='<td class="right_broder2" width="33%">10% Advertising Sales Volume Bonus</td>';
			$str.='<td>6 x Login Ad Day</td>';		
		}
		else if($num ==3)
		{
			$str.='<td class="right_broder2" width="33%">1 x Asimi 1 oz Silver Bullion Coin</td>';
			$str.='<td class="right_broder2" width="33%">10% Advertising Sales Volume Bonus</td>';
			$str.='<td>5 x Login Ad Day</td>';		
		}
		else if($num ==4)
		{
			$str.='<td class="right_broder2" width="33%">10% Advertising Sales Volume Bonus</td>';
			$str.='<td width="64%">4 x Login Ad Day</td>';		
		}
		else if($num ==5)
		{
			$str.='<td class="right_broder2" width="33%">10% Advertising Sales Volume Bonus</td>';
			$str.='<td width="64%">3 x Login Ad Day</td>';		
		}
		else if($num ==6)
		{
			$str.='<td class="right_broder2" width="33%">10% Advertising Sales Volume Bonus</td>';
			$str.='<td width="64%">2 x Login Ad Day</td>';		
		}
		else if($num ==7)
		{
			$str.='<td class="right_broder2" width="33%">10% Advertising Sales Volume Bonus</td>';
			$str.='<td width="64%">1 x Login Ad Day</td>';		
		}
		else if($num ==8 || $num ==9 || $num ==10)
		{
			$str.='<td width="100%">10% Advertising Sales Volume Bonus</td>';			 
		}
		else  
		{
			$str.='<td width="100%">No prize</td>';			 
		}
		return $str;
	}	
	
	function top_affiliates_prizes_new($num)
	{
		if($num ==1)
		{
			$str.='<td class="right_broder2" width="50%">1,000 Asimi </td>';
			$str.='<td>1 Login Ad Day</td>';		
		}
		else if($num ==2)
		{
			$str.='<td class="right_broder2" width="50%">250 Asimi</td>';
			$str.='<td>1 Login Ad Day</td>';		
		}
		else if($num ==3)
		{
			$str.='<td class="right_broder2" width="50%">100 Asimi </td>';
			$str.='<td>200,000 Banner Credits</td>';		
		}
		else if($num ==4)
		{
		    $str.='<td class="right_broder2" width="50%">100 Asimi </td>';
			$str.='<td width="64%">100,000 Banner Credits</td>';		
		}
		else if($num ==5)
		{
			$str.='<td class="right_broder2" width="50%">50 Asimi </td>';
			$str.='<td width="64%">50,000 Banner Credits</td>'; 
		}
		else  
		{
			$str.='<td width="100%">&nbsp;</td>';			 
		}
		return $str;
	}	
	 
	function ad_minter_stakes_contest_prizes($num)
	{
		if($num ==1)
		{
			$str.='<td class="right_broder2" width="33%">5 x Asimi 1 oz Silver Bullion Coin</td>';
			$str.='<td class="right_broder2" width="33%">90 Days Vacation Time</td>';
			$str.='<td>50 New Referrals</td>';		
		}
		else if($num ==2)
		{
			$str.='<td class="right_broder2" width="33%">4 x Asimi 1 oz Silver Bullion Coin</td>';
			$str.='<td class="right_broder2" width="33%">60 Days Vacation Time</td>';
			$str.='<td>40 New Referrals</td>';		
		}
		else if($num ==3)
		{
			$str.='<td class="right_broder2" width="33%">3 x Asimi 1 oz Silver Bullion Coin</td>';
			$str.='<td class="right_broder2" width="33%">30 Days Vacation Time</td>';
			$str.='<td>30 New Referrals</td>';		
		}
		else if($num ==4)
		{
			$str.='<td class="right_broder2" width="33%">1 x Asimi 1 oz Silver Bullion Coin</td>';
			$str.='<td class="right_broder2" width="33%">21 Days Vacation Time</td>';
			$str.='<td>20 New Referrals</td>';		
		}
		else if($num ==5)
		{
			$str.='<td class="right_broder2" width="33%">1 x Asimi 1 oz Silver Bullion Coin</td>';
			$str.='<td class="right_broder2" width="33%">21 Days Vacation Time</td>';
			$str.='<td>10 New Referrals</td>';		
		}
		else if($num ==6)
		{
			$str.='<td class="right_broder2" width="33%">1 x Asimi 1 oz Silver Bullion Coin</td>';
			$str.='<td class="right_broder2" width="33%">21 Days Vacation Time</td>';
			$str.='<td>5 New Referrals</td>';		
		}
		else if($num ==7 || $num ==8)
		{
			$str.='<td class="right_broder2" width="33%">1 x Asimi 1 oz Silver Bullion Coins</td>';
			$str.='<td class="right_broder2" width="33%">14 Days Vacation Time</td>';
			$str.='<td>5 New Referrals</td>';		
		}
		else if($num ==9 || $num ==10)
		{
			$str.='<td class="right_broder2" width="33%">1 x Asimi 1 oz Silver Bullion Coin</td>';
			$str.='<td class="right_broder2" width="33%">7 Days Vacation Time</td>';
			$str.='<td>5 New Referrals</td>';		
		}
		else 
		{
			$str.='<td width="100%">No prize</td>';	
		}
		return $str;		
	}
	
	function ad_minter_stakes_contest_prizes_new($num)
	{
		if($num ==1)
		{
			$str.='<td class="right_broder2" width="50%">1,000 Asimi</td>';
			$str.='<td>7 Vacation Days</td>';			 		
		}
		else if($num ==2)
		{
			$str.='<td class="right_broder2" width="50%">250 Asimi</td>';
			$str.='<td>6 Vacation Days</td>';		
		}
		else if($num ==3)
		{
			$str.='<td class="right_broder2" width="50%">100 Asimi</td>';
			$str.='<td>5 Vacation Days</td>';	
		}
		else if($num ==4)
		{
			$str.='<td class="right_broder2" width="50%">100 Asimi</td>';
			$str.='<td>4 Vacation Days</td>';
		}
		else if($num ==5)
		{
			$str.='<td class="right_broder2" width="50%">50 Asimi</td>';
			$str.='<td>3 Vacation Days</td>';
		}    	
		else 
		{
			$str.='<td width="100%">&nbsp;</td>';	
		}
		return $str;		
	}
	
	function top_advertisers_contest_prizes($num)
	{
		if($num ==1)
		{
			$str.='<td class="right_broder2" width="50%">500,000 Banner Credits</td>';
			$str.='<td>2 Login Ad Days</td>';			 		
		}
		else if($num ==2)
		{
			$str.='<td class="right_broder2" width="50%">200,000 Banner Credits</td>';
			$str.='<td>1 Login Ad Day</td>';		
		}
		else if($num ==3)
		{
			$str.='<td class="right_broder2" width="50%">500,000 Banner Credits</td>';
			$str.='<td></td>';	
		}
		else if($num ==4)
		{
			$str.='<td class="right_broder2" width="50%">200,000 Banner Credits</td>';
			$str.='<td></td>';
		}
		else if($num ==5)
		{
			$str.='<td class="right_broder2" width="50%">100,000 Banner Credits</td>';
			$str.='<td></td>';
		}    	
		else 
		{
			$str.='<td width="100%">&nbsp;</td>';	
		}
		return $str;		
	}
	

	
	
	
	
	
	function usd_roundup($float)
	{
		$l = explode(".",$float);
		$s1 = '';
		$t1 = substr($l[1],1,1);
		$t2 = substr($l[1],2,1);
		if($t2>=5) $s1= $t1+1;
		else $s1= $t1;
		
		$val = $l[0].'.'.substr($l[1],0,1).$s1;
		return $val;
	}
		
		
	function select_group_countries($ggcId)
	{
		$ggcD  = parent::selectData(TABLE_GEO_GROUP_COUNTRY,"","ggc_status='Active' and ggc_id='".$ggcId."'",1);		 
		$i=0;
		$paval = explode(",",$ggcD['ggc_countries']);
		$res=parent::selectData(TABLE_COUNTRY,"","country_status='Active'","");
		 
		while($row=mysqli_fetch_array($res))
		{
			$str.='<label class="checkbox-inline">';			
			$str.='<input type="checkbox" name="country_id[]" value="'.$row['country_id'].'"';
			if(in_array($row['country_id'],$paval))
			{
				$str.=' checked';
			}
			$str.=">".$row['country_name']."</td>";
			$str.='</label>';
			 
		$i++;
		}
		 
		return $str;
	}	
	
	
	function select_languages()
	{
		///$dataGL  = parent::selectData(TABLE_GEO_LANGUAGE,"","glan_status='Active' and glan_id='".$lanId."'",1); 
		 
		$res=parent::selectData(TABLE_GEO_LANGUAGE,"","glan_status ='Active'","","glan_ucount desc");
		 
		while($row=mysqli_fetch_array($res))
		{
			$str.='<label class="checkbox-inline" style="margin-left:10px">';			
			$str.='<input type="checkbox" name="glan_id[]" value="'.$row['glan_id'].'"';
			if($row['glan_is_active']=='y') {
				$str.=' checked';
			}
			$str.=">".$row['glan_name']."(".$row['glan_code'].") <strong>[".$row['glan_ucount']."]</strong></td>";
			$str.='</label>';
			 
		$i++;
		}
		 
		return $str;
	}
	
	function select_geo_country($selval)
	{
		$selval  = $this->filter_mysql($this->filter_numeric($selval));
		
		$str.= "<option value=''>--Select Country--</option>";
		$res=parent::selectData(TABLE_GEO_COUNTRY,"","gcon_status='Active'","");
		while($row=mysqli_fetch_array($res))
		{
			$str.="<option value='".$row['country_id']."'";
			if($row['country_id']==$selval)
			{
				$str.=' selected';
			}
			$str.=">".$row['gcon_name']."</option>";
		}
		return $str;
		
	}
	
	
	
	
	function get_tot_vtoe_member_view_before_date_vtoeId($csId,$dt)
	{
		$csId   = $this->filter_numeric($csId);
		$dt     = $this->filter_mysql($dt);		
		$imp 	= 0;		 		 
		$sqlMBC = parent::selectData(TABLE_VTOE_MEMBER_VIEW,"count(cs_id) as tot","cs_id like '%,".$csId.",%' and vmv_created_dt <='".$dt."'",1); 
		$imp 	= $sqlMBC['tot'];		 
		return $imp;
	} 
	
	
	function get_account_administration_fee($userId)
	{
		$tot_acc_admin_fee = 0;
		$userId  = $this->filter_numeric($userId);	
		$sqlLS 	 = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_admin_fee","wall_status='Active' and wall_type='iaf' and wall_pstatus='p' and user_id='".$userId."'",1);		 
		$tot_acc_admin_fee = $sqlLS['total_admin_fee'];		
		if($tot_acc_admin_fee) return($tot_acc_admin_fee);
		else return 0;
	}
	
	 
	function tot_member_login_with_lastIp($userId)
	{ 
		 
		// $calDay = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-15,date("y")));
		$lstLog = parent:: selectData(TABLE_MONITORING_LOGINS,"monl_date","user_id = '".$userId."'","1","monl_id asc","user_id");
		$calDay = date("Y-m-d",strtotime($lstLog['monl_date'])); 
		//$sql 	= parent::selectData(TABLE_MONITORING_LOGINS,"monl_id,monl_ip,user_id","monl_date > '".$calDay.' 00:00:00'."' and user_id = '".$userId."'","","","monl_ip");
		$sql 	= parent::selectData(TABLE_MONITORING_LOGINS,"monl_id,monl_ip,user_id","user_id = '".$userId."'","","","monl_ip");		
		while($data=mysqli_fetch_assoc($sql))
		{	
	
			$sql2 = parent:: selectData(TABLE_MONITORING_LOGINS,"monl_ip,user_id","monl_ip = '".$data['monl_ip']."'","","","user_id"); 
			$rowspan = mysqli_num_rows($sql2); 
			if($rowspan > '1') return $rowspan; 
			 
		}
	}
	
	 
	
	function tot_member_login_with_lastIptest($userId)
	{  
		$lstLog = parent:: selectData(TABLE_MONITORING_LOGINS,"monl_date","user_id = '".$userId."'","1","monl_id asc","user_id");
		$calDay = date("Y-m-d",strtotime($lstLog['monl_date'])); 
		$sql 	= parent::selectData(TABLE_MONITORING_LOGINS,"monl_id,monl_ip,user_id","monl_date > '".$calDay.' 00:00:00'."' and user_id = '".$userId."'","","","monl_ip");		
		while($data=mysqli_fetch_assoc($sql))
		{			
			 
			$sql2 = parent:: selectData(TABLE_MONITORING_LOGINS,"monl_ip,user_id","monl_ip = '".$data['monl_ip']."'","","","user_id"); 
			$rowspan = mysqli_num_rows($sql2); 
			if($rowspan > '1') return $rowspan; 
			 
		}
	}
	
	function concerned_user_survey_balance($userId)
	{ 
		$userId  = $this->filter_numeric($userId);	
		$sqlLS   = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as tot_sb_balance","wall_status='Active' and wall_type='sb' and wall_pstatus='p' and user_id='".$userId."'",1);
		$totbal  = $sqlLS['tot_sb_balance'];
		if($totbal) return $totbal; 
		else return 0; 	
		 
	}
	
	function user_pending_survey_balance($userId)
	{ 
		$userId  = $this->filter_numeric($userId);	
		$sqlLS   = parent::selectData(TABLE_SURVEY,"sum(sur_asimi) as tot_pend_sb_balance","sur_status='Active' and user_id='".$userId."'",1);
		$totbal  = $sqlLS['tot_pend_sb_balance'];
		if($totbal) return $totbal; 
		else return 0; 	
		 
	}
	
 
	
	///////////////////////////CSV DOC////////////////////////////////////////////
	
	function total_available_balance_launch_by_date($userId)
	{
		$available_balance 		= $this->account_balance_launch_by_date($userId) + $this->aff_prelaunch_com_launch_by_date($userId);
		$pre_lauch_balance 		= $this->prelaunch_balance_launch($userId);
		$purchase_total_launch 	= $this->purchase_total_launch_by_date($userId);
		
		if($pre_lauch_balance>$purchase_total_launch)
		{
			$pre_lauch_balance = $pre_lauch_balance - $purchase_total_launch;
		}
		else
		{ 
			$available_balance = $available_balance + $pre_lauch_balance - $purchase_total_launch;
			$pre_lauch_balance = 0;
		}
		
		$total_current_balance = $pre_lauch_balance+$available_balance;
		
		return $total_current_balance;
	}
		
	function account_balance_launch_by_date($userId)
	{  
			$userId  	= $this->filter_mysql($this->filter_numeric($userId));
			$start_date = '2019-01-28';
			$calDay 	= date("Y-m-01",mktime(0,0,0,date("m"),date("d"),date("Y")));
			$ucredit 	= 0;
			$bcredit 	= 0;
			$sqlU = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_asimi","wall_status='Active' and ( wall_type='d' or wall_type='e' or wall_type='cr' or wall_type='pbr' or wall_type='me' or wall_type='rs' or wall_type='j' or wall_type='r' or wall_type='a' or wall_type='ve' or wall_type='vea' or wall_type='rw' or wall_type='rwa' or wall_type='ref' or wall_type='lsw' or wall_type='rsw' or wall_type='rbw' or wall_type='btcp' or wall_type='ethp' or wall_type='brz' or wall_type='lus' or wall_type='lsr' or wall_type='laa' or wall_type='lasa' or wall_type='spr' or wall_type='kvfa' or wall_type='lrw' or wall_type='sb' or wall_type='sba' or wall_type='psb' or wall_type='psba' or wall_type='nmae') and wall_pstatus='p' and user_id='".$this->filter_mysql($userId)."' and wall_created>='".$start_date."' and wall_created <'".$calDay."'",1);
			$ucredit = $sqlU['total_asimi'];
					 
						
			$uwsql = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_deduction","wall_status='Active' and (wall_type='w' or wall_type='mw' or wall_type='kyc' or wall_type='cd' or wall_type='ls' or wall_type='btz' or wall_type='pbt' or wall_type='vep' or wall_type='lap' or wall_type='lasp' or wall_type='iaf') and wall_pstatus='p' and user_id='".$userId."' and wall_created>='".$start_date."' and wall_created < '".$calDay."'",1);
			$bcredit = $uwsql['total_deduction'];					
			 
			$balance = $ucredit-$bcredit;				
			return($balance);
	}	
	
	function aff_prelaunch_com_launch_by_date($aff_id)
	{
		$ref_ids = explode(",",$this->get_all_referrals($aff_id)); 
		$date = date("Y-m-01",mktime(0,0,0,date("m"),date("d"),date("Y")));
		$totalRE = 0;
		for($i=0;$ref_ids[$i]!='';$i++)
		{
			$totalRE += $this->total_asimi_minted_launch_by_date($ref_ids[$i],$date);
		}
		return round($totalRE*.1,8);
		
	}
	
	function total_asimi_minted_launch_by_date($userId,$date)
	{
		$userId  	= $this->filter_mysql($this->filter_numeric($userId)); 
		$totalAE 	= 0;
		$sqlU = parent::selectData(TABLE_MEMBER_EARNING,"sum(me_share_coin) as total_new_share_min","user_id='".$userId."' and me_created < '".$date."'",1);	
		$ucredit = $sqlU['total_new_share_min'];
		
		return $ucredit;
	} 
	
	function purchase_total_launch_by_date($userId)
	{
		$userId  	= $this->filter_numeric($userId);		
		$start_date = '2019-01-28';
		$calDay 	= date("Y-m-01",mktime(0,0,0,date("m"),date("d"),date("Y")));
		$bcredit 	= 0;		
				
		$uwsql = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_deduction","wall_status='Active' and (wall_type='p') and wall_pstatus='p' and user_id='".$userId."' and wall_created >='".$start_date."' and wall_created < '".$calDay."'",1);
		$bcredit = $uwsql['total_deduction'];					
		
		if($bcredit) return($bcredit);
		else return 0;
	}
	
	
	function get_account_administration_fee_by_date($userId)
	{
		$calDay  = date("Y-m-01",mktime(0,0,0,date("m"),date("d"),date("Y")));
		$tot_acc_admin_fee = 0;
		$userId  = $this->filter_numeric($userId);	
		$sqlLS 	 = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_admin_fee","wall_status='Active' and wall_type='iaf' and wall_pstatus='p' and user_id='".$userId."' and wall_created < '".$calDay."'",1);		 
		$tot_acc_admin_fee = $sqlLS['total_admin_fee'];		
		if($tot_acc_admin_fee) return($tot_acc_admin_fee);
		else return 0;
	}
	
	function get_member_login_stake_by_date($userId)
	{
		$calDay  = date("Y-m-01",mktime(0,0,0,date("m"),date("d"),date("Y")));
		$tot_login_stake = 0;
		$userId  = $this->filter_numeric($userId);	
		$sqlLS 	 = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_login_stake","wall_status='Active' and wall_type='ls' and wall_pstatus='p' and user_id='".$userId."' and wall_created < '".$calDay."'",1);
		$sqlLUS = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_login_unstake","wall_status='Active' and wall_type='lus' and wall_pstatus='p' and user_id='".$userId."' and wall_created < '".$calDay."'",1);
		$tot_login_stake = $sqlLS['total_login_stake']-$sqlLUS['total_login_unstake'];		
		if($tot_login_stake) return($tot_login_stake);
		else return 0;
	}
	
	function prelaunch_balance_transfer_by_date($userId)
	{
		$calDay  = date("Y-m-01",mktime(0,0,0,date("m"),date("d"),date("Y")));
		$pbtransfer = 0;
		$userId  = $this->filter_numeric($userId);	
		$sqlU = parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_pb_transfer","wall_status='Active' and ( wall_type='pbt') and wall_pstatus='p' and user_id='".$userId."' and wall_created < '".$calDay."'",1);
		$pbtransfer = $sqlU['total_pb_transfer'];
		if($pbtransfer) return($pbtransfer);
		else return 0;
	}
	
	
	//////////////////////New Minting Functions///////////////////////////
	
	function memebr_max_ads_to_view_today_new($userId)
	{
		$calDay = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-2,date("Y")));
		$sqlD 	= parent::selectData(TABLE_VERIFIED_ASIMI_WALLET_BALANCE,"vawb_view_limit","user_id='".$userId."' and vawb_status='Active' and vawb_date='".$calDay."' ",1); 
		$totIndView = $sqlD['vawb_view_limit']; 
		if($totIndView!="") return $totIndView;
		else return "0";			
	}
	
	function get_verified_asimi_wallet_balance($wallet)
	{	 
		$headers 	= array(); 
		$headers[] 	= 'Content-Type: application/json'; 
		$ch = curl_init('https://nodes.wavesplatform.com/assets/balance/'.trim($wallet).'/EbLVSrAi6vS3AkLwBinzZCvAXP2yYiFJEzj1MBVHcwZ5'); 
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		$response = json_decode(curl_exec($ch));
		curl_close($ch); 
		$balance = -1;
		if(!isset($response->error))
		{
			if(isset($response->balance))
			{
				$balance 		= $response->balance/100000000; 
			}
		}		
		if(!isset($response->error)) return $balance."||".$response->error."||".$response->message;
		else return $balance;
	}
	
	function get_verified_companywide_wallet_balance()
	{	 
		 
		$settings 	= parent::selectData(TABLE_SETTINGS,"set_wallet_balance","set_id='1'",1); 
		$calDay   	= date("Y-m-d",mktime(0,0,0,date("m"),date("d")-2,date("Y")));
		// vawb_asimi >='".$settings['set_wallet_balance']."' and 
		$SqlD 		= parent::selectData(TABLE_VERIFIED_ASIMI_WALLET_BALANCE,"sum(vawb_asimi) as tot_asimi","vawb_date='".$calDay."' and vawb_status='Active'",1); 
		if($SqlD['tot_asimi']) return($SqlD['tot_asimi']);
		else return 0; 
	}
	
	function get_member_verified_wallet_balance($userId)
	{	 
		$settings 	= parent::selectData(TABLE_SETTINGS,"set_wallet_balance","set_id='1'",1);
		$calDay   	= date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
		$SqlD 		= parent::selectData(TABLE_VERIFIED_ASIMI_WALLET_BALANCE,"vawb_asimi","user_id='".$userId."' and vawb_date='".$calDay."' and vawb_status='Active'",1);  
		if($SqlD['vawb_asimi']) return($SqlD['vawb_asimi']);
		else return 0; 
	}
	
	function show_member_verified_wallet_balance($userId)
	{	 
		$settings 	= parent::selectData(TABLE_SETTINGS,"set_wallet_balance","set_id='1'",1);
		$calDay   	= date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
		$SqlD 		= parent::selectData(TABLE_VERIFIED_ASIMI_WALLET_BALANCE,"vawb_asimi","user_id='".$userId."' and vawb_date='".$calDay."' and vawb_status='Active'",1);  
		if(isset($SqlD['vawb_asimi'])) return($SqlD['vawb_asimi']);
		else return "Balance not read!"; 
	}
	
	function get_paid_wave_balance_by_date($datee,$userID)
	{	 
		$SqlD = parent::selectData(TABLE_NEW_MINTING_WALLET_EARNING,"mwe_asimi","mwe_bdate='".$datee."' and user_id = '".$userID."'",1);  
		if($SqlD['mwe_asimi']) return($SqlD['mwe_asimi']);
		else return 0; 
	}
	function get_paid_tot_ad_view_by_date($datee,$userID)
	{	 
		$SqlD = parent::selectData(TABLE_NEW_MINTING_WALLET_EARNING,"mwe_tot_ad_view","mwe_bdate='".$datee."' and user_id = '".$userID."'",1);  
		if($SqlD['mwe_tot_ad_view']) return($SqlD['mwe_tot_ad_view']);
		else return 0; 
	}
	
	
	function member_count_new_minting_ads_viewed_today($userId)
	{
		$userId = $this->filter_numeric($userId);
		$adlIds = 0;
		$today  = date("Y-m-d");
		$sqlU   = parent::selectData(TABLE_MEMBER_NEW_AD_MINTER_VIEW,"adl_id","mnmv_status='Active' and mnmv_created_dt ='".$today."' and user_id ='".$userId."' order by mnmv_id asc");
		while($resU = mysqli_fetch_array($sqlU)) 
		{
			$totalTDV = explode(",",trim($resU['adl_id'],","));
			$adlIds += count($totalTDV);
		}
		return $adlIds;
	}
	
	function member_count_new_minting_ads_viewed_by_date($userId,$dt)
	{
		$userId  = $this->filter_numeric($userId);	
		$dt  	 = $this->filter_mysql($dt); 
		$adlIds  = 0; 
		$sqlU    = parent::selectData(TABLE_MEMBER_NEW_AD_MINTER_VIEW,"adl_id","mnmv_status='Active' and mnmv_created_dt ='".$dt."' and user_id ='".$userId."' order by mnmv_id asc");
		while($resU = mysqli_fetch_array($sqlU)) 
		{
			$totalTDV  = explode(",",trim($resU['adl_id'],","));
			$adlIds   += count($totalTDV);
		}
		return $adlIds;
	}
	
	 
	function get_member_wave_balance($userId)
	{ 
		$userId  = $this->filter_numeric($userId);	
		$sqlD 	 = parent::selectData(TABLE_USER_MWALLET,"sum(mw_wave) as tot_wave_damount","mw_status='Active' and (mw_type='d' or mw_type='b') and mw_pstatus='p' and user_id='".$userId."'",1); 
		$sqlPD 	 = parent::selectData(TABLE_USER_MWALLET,"sum(mw_wave) as tot_wave_pamount","mw_status='Active' and mw_type IN ('p','mwr') and mw_pstatus='p' and user_id='".$userId."'",1); 
		$totAmount = round($sqlD['tot_wave_damount']-$sqlPD['tot_wave_pamount'],8);	 
		if($totAmount) return($totAmount);
		else return 0;
	}
	
	
	function count_new_minting_ads_viewed_by_date($dt)
	{ 
		$calDate = $this->filter_mysql($dt); 
		$adlIds  = 0;
		if($calDate >= "2021-01-20")
		{	
			$sqlU    = parent::selectData(TABLE_MEMBER_NEW_AD_MINTER_VIEW,"adl_id","mnmv_status='Active' and mnmv_created_dt ='".$calDate."'");
			while($resU = mysqli_fetch_array($sqlU)) 
			{
				$totalTDV = explode(",",trim($resU['adl_id'],","));
				$adlIds += count($totalTDV);
			}
		}
		else 
		{
			$sqlOU = parent::selectData(TABLE_AD_MINTER_MEMBER_VIEW,"adl_id","ammv_status='Active' and ammv_created_dt='".$calDate."' order by ammv_id asc");
			while($resU = mysqli_fetch_array($sqlOU)) 
			{ 
				$totalTDV = explode(",",trim($resU['adl_id'],","));
				$adlIds += count($totalTDV);
			}	 
			
		}	
		return $adlIds;
	}
	
	function count_tot_member_in_new_minting_ads_viewed_by_date($dt)
	{ 
		$calDate 	= $this->filter_mysql($dt); 
		$totMember 	= 0;
		if($calDate >= "2021-01-20")
		{	
			$sqlU    	= parent::selectData(TABLE_MEMBER_NEW_AD_MINTER_VIEW,"count(user_id) as tot_member","mnmv_status='Active' and mnmv_created_dt ='".$calDate."'",1); 
			$totMember  = $sqlU['tot_member'];
		}
		else 
		{
			$sqlU    	= parent::selectData(TABLE_AD_MINTER_MEMBER_VIEW,"count(user_id) as tot_member","ammv_status='Active' and ammv_created_dt ='".$calDate."'",1); 
			$totMember  = $sqlU['tot_member'];
		}	
		return $totMember;
	}
	
	function avg_minter_asimi_by_date($dt)
	{
		$dt 	= $this->filter_mysql($dt);
		$tot_avg_return = 0;
		if($dt >= "2021-01-20")
		{
			$sqlN 	= parent::selectData(TABLE_NEW_MINTING_WALLET_EARNING,"sum(mwe_asimi)/count(mwe_id) as totavg_earn","mwe_pstatus = 'p' and mwe_date = '".$dt."'",1);
			$tot_avg_return = $sqlN['totavg_earn']; 	
		}
		else 
		{ 
			$sqlOM = parent::selectData(TABLE_MEMBER_EARNING,"sum(me_share_coin)/count(me_id) as tot_avg_earn","me_updated = '".$dt."'",1); 
			$tot_avg_return = $sqlOM['tot_avg_earn']; 	
		}	
	    
		return $tot_avg_return;
	}
	
	function get_top_minters_in_asimi($sevDate,$oneDate)
	{
		$sevTh = $this->filter_mysql($sevDate);
		$oneTh = $this->filter_mysql($oneDate);
		$lastWTMAsimi = 0;
		if($sevTh >= "2021-01-20")
		{
			$sqlLWTM = parent::selectData(TABLE_NEW_MINTING_WALLET_EARNING,"sum(mwe_asimi) as top_earning","mwe_pstatus = 'p' and mwe_date >= '".$sevTh."' and mwe_date <= '".$oneTh."'","1","top_earning desc","",""); 
			$lastWTMAsimi = $sqlLWTM['top_earning'];
		}
		else 
		{ 
			$sqlOM = parent::selectData(TABLE_MEMBER_EARNING,"sum(me_share_coin) as tot_avg_earn","me_updated >= '".$sevTh."' and me_updated <= '".$oneTh."'",1); 
			$lastWTMAsimi = $sqlOM['tot_avg_earn']; 	
		}	
		return $lastWTMAsimi;
		
	}
	 
	function count_total_minting_days($dt)
	{ 
		$calDate 	= $this->filter_mysql($dt); 
		$mstart_date 	= "2018-10-20"; 
		$toDay  	 	= $calDate;
		$diff 			= strtotime($toDay) - strtotime($mstart_date);     
		$tot_mint_days  = abs(round($diff / 86400))-1;  
		return $tot_mint_days;
	}
	
	function avg_calculation($val1,$val2)
	{
		if($val1==0 || $val2 == 0) return 0;
		else return $val1/$val2;
	}
	
	function total_available_waves_balance($userId)
	{
		$deposit = 0;				
		$wavesdsql = parent::selectData(TABLE_USER_MWALLET,"sum(mw_wave) as total_deposit","mw_status='Active' and mw_type='d' and mw_pstatus='p' and user_id='".$userId."'",1);
		$deposit = $wavesdsql['total_deposit'];	
		
		$deduct = 0;				
		$wavesdesql = parent::selectData(TABLE_USER_MWALLET,"sum(mw_wave) as total_deduct","mw_status='Active' and mw_type in ('p','mwr') and mw_pstatus='p' and user_id='".$userId."'",1);
		$deduct = $wavesdesql['total_deduct'];
		
		$balance = 	$deposit - 	$deduct;			
		
		if($balance) return($balance);
		else return 0;
	}
	
	function get_minter_rank($wallBalace)
	{
		$lavel 	=  -1;
		if($wallBalace >='100' &&  $wallBalace < '1000')
		{
			$lavel 		= 0;
			$priceRang 	= "100-999.99999999";
			$AsimiName 	= "Asimi Explorer";
		}
		else if($wallBalace >='1000' &&  $wallBalace < '2000')
		{
			$lavel 		= 1;
			$priceRang 	= "1000-1999.99999999";
			$AsimiName 	= "Asimi Minter";
		}
		else if($wallBalace >='2000' &&  $wallBalace < '4000')
		{
			$lavel 		= 2;
			$priceRang 	= "2000-3999.99999999";
			$AsimiName 	= "Asimi Collector";
		}
		else if($wallBalace >='4000' &&  $wallBalace < '8000')
		{
			$lavel 		= 3;
			$priceRang 	= "4000-7999.99999999";
			$AsimiName 	= "Asimi Builder";
		}
		else if($wallBalace >='8000' &&  $wallBalace < '16000')
		{
			$lavel 		= 4;
			$priceRang 	= "8000-15999.99999999";
			$AsimiName 	= "Asimi Producer";
		}
		else if($wallBalace >='16000' &&  $wallBalace < '32000')
		{
			$lavel 		= 5;
			$priceRang 	= "16000-31999.99999999";
			$AsimiName 	= "Asimi Grower";
		}
		else if($wallBalace >='32000' &&  $wallBalace < '64000')
		{
			$lavel 		= 6;
			$priceRang 	= "32000-63999.99999999";
			$AsimiName 	= "Asimi Leader";
		}
		else if($wallBalace >='64000' &&  $wallBalace < '128000')
		{
			$lavel 		= 7;
			$priceRang 	= "64000-127999.99999999";
			$AsimiName 	= "Asimi Role Model";
		}
		else if($wallBalace >='128000' &&  $wallBalace < '256000')
		{
			$lavel 		= 8;
			$priceRang 	= "128000-255999.99999999";
			$AsimiName 	= "Asimi Hero";
		}
		else if($wallBalace >='256000' &&  $wallBalace < '512000')
		{
			$lavel 		= 9;
			$priceRang 	= "256000-511999.99999999";
			$AsimiName 	= "Asimi Superstar";
		}
		else if($wallBalace >='512000')
		{
			$lavel 		= 10;
			$priceRang 	= "512000+";
			$AsimiName 	= "Asimian";
		}
		 
		
		return $lavel."||".$priceRang."||".$AsimiName;
		
	}
	
	 
	function get_tot_member_by_minter_rank($rank)
	{
		$calDay   	= date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));	 
		if($rank==0) 		$totSt =round(100/1000 ,2);
		else if($rank==1) 	$totSt =round(1000/1000 ,2);
		else if($rank==2)  	$totSt =round(2000/1000 ,2);  
		else if($rank==3) 	$totSt =round(4000/1000 ,2);  
		else if($rank==4) 	$totSt =round(8000/1000 ,2); 
		else if($rank==5) 	$totSt =round(16000/1000 ,2);  
		else if($rank==6)	$totSt =round(32000/1000 ,2); 
		else if($rank==7) 	$totSt =round(64000/1000 ,2);  
		else if($rank==8) 	$totSt =round(128000/1000 ,2);  
		else if($rank==9) 	$totSt =round(256000/1000 ,2);   
		else if($rank==10)	$totSt =round(512000/1000 ,2);  
		else $totSt = round(100/1000 ,2);
		return $totSt; 
	} 
	 
	 
	function get_minter_member_wallet_balance($userId)
	{	 
		$settings 	= parent::selectData(TABLE_SETTINGS,"set_wallet_balance","set_id='1'",1);
		$calDay   	= date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
		$SqlD 		= parent::selectData(TABLE_VERIFIED_ASIMI_WALLET_BALANCE,"vawb_asimi","user_id='".$userId."' and vawb_date='".$calDay."' and vawb_status='Active'",1);  
		if(isset($SqlD['vawb_asimi'])) return($SqlD['vawb_asimi']);
		else return 0; 
	}
	
	function send_ranker_email($rank,$userId)
	{	
		$rank 		= $this->filter_numeric($rank); 
		
		if($rank > 0)
		{
			$rankVar 	= rankVar;  
			$name 		= $rankVar[$rank]['name'];
			$image 		= $rankVar[$rank]['image'];
			$min 		= $rankVar[$rank]['min'];
			$max 		= $rankVar[$rank]['max'];
			$title 		= $rankVar[$rank]['title'];
			$userD  	= parent::selectData(TABLE_USER,""," u_login_id='".$userId."'",1);
			$memName 	= $userD['user_first_name'];  
			$subTitle 	= str_replace('[name]', $memName, $title); 
			$bodyReg 	= $rankVar[$rank]['body'];

			$remsg  	= str_replace('[name]', $memName, $bodyReg); 
			$remsg 	   .= "<br /><br />Thank You<br/>";
			$remsg 	   .= MAIL_THANK_YOU;		

			$remsg 		.= '<p style="border-top:1px solid #808080"></p>';  
			$StyleVAr 	 = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
			$remsg 		.='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$this->encryptPass($userId).'">UNSUBSCRIBE</a> <br/><br/>Hashing Ad Space - '.ADDRESS_LINE_1.ADDRESS_SEPRATER_1.'<br>'.ADDRESS_LINE_2.ADDRESS_SEPRATER_2.ADDRESS_LINE_3.'</p>'; 

			$body 		= $this->mailBody($remsg); 
			$from 		= FROM_EMAIL_2;
			$to         = $this->getUserEmailFromAll($userId); 
			$subject 	= $subTitle;			
			//$this->sendMailSES($to,$subject,$body,$from,SITE_TITLE,$type);
			$bccEmail 	= "clientcogs2@gmail.com";	
			$this->sendMailSES($to,$subject,$body,$from,SITE_TITLE,$type,"",$bccEmail); 
		}
		 
	}
	
	function ad_reward_raffle_entry($userId)
	{
		$userId  		= $this->filter_numeric($userId);	
		$today   		= date("Y-m-d");
		$dataARE 		= parent::selectData(TABLE_ADINPLAY_RAFFLE_ENTRY,"count(are_id) as totalW","are_added='".$today."' and user_id=".$userId."","1");  
		if($dataARE['totalW'] < 25)
		{
			$dataI['user_id']    	= $userId; 
			$dataI['are_win']     	= 'n';
			$dataI['are_tot_entry'] = 1;
			$dataI['are_added']   	= $today;
			$dataI['are_updated'] 	= date("Y-m-d H:i:s");
			parent::insertData(TABLE_ADINPLAY_RAFFLE_ENTRY,$dataI); 
		} 
		/*$sqlRE = parent::selectData(TABLE_ADINPLAY_RAFFLE_ENTRY,"are_id,are_tot_entry","user_id='".$userId."' and are_added='".$today."'",1);
		if(empty($sqlRE['are_id']))
		{ 
			$dataI['user_id']    	= $userId; 
			$dataI['are_win']     	= 'n';
			$dataI['are_tot_entry'] = 1;
			$dataI['are_added']   	= $today;
			$dataI['are_updated'] 	= date("Y-m-d H:i:s");
			parent::insertData(TABLE_ADINPLAY_RAFFLE_ENTRY,$dataI);
			 
		}
		else
		{
			$rwD['are_tot_entry'] = $sqlRE['are_tot_entry']+1;
			$rwD['are_updated'] 	= date("Y-m-d H:i:s");
			parent::updateData(TABLE_ADINPLAY_RAFFLE_ENTRY,$rwD,"are_id='".$sqlRE['are_id']."'");	
		}
		*/
		  
	}
	
	function raffle_reward_winner($date)
	{
		$date  		= $this->filter_mysql($date); 
		$dataSql 	= parent::selectData(TABLE_ADINPLAY_RAFFLE_ENTRY,"","are_added='".$date."' and are_win='y'",1);
		$winareId 	= $dataSql['are_id'];
		if(empty($winareId))
		{
			$sqlRE 		= parent::selectData(TABLE_ADINPLAY_RAFFLE_ENTRY,"","are_added='".$date."' and are_win='n'",1,"rand()");
			$win_ids   	= $sqlRE['are_id'];	
			
			if($win_ids !='')
			{
				$rwD['are_win'] 	   	= 'y'; 
				$rwD['are_updated'] 	= date("Y-m-d H:i:s");
				parent::updateData(TABLE_ADINPLAY_RAFFLE_ENTRY,$rwD,"are_id='".$win_ids."'");	
			}
			return $win_ids;	
		} else return $winareId;	
		
		
	}
	
	
	
	function minting_raffle_entry($userId,$cat)
	{
		$userId  	= $this->filter_numeric($userId);	
		$today   	= date("Y-m-d"); 
		// if($userId =='5' || $userId =='52' || $userId =='44129' || $userId =='6' || $userId =='60432' || $userId =='337270' || $userId =='43') {}
			$dataUP = array();
			$dataUP['user_id']    	= $userId;
			$dataUP['mre_cat']     	= $cat;
			$dataUP['mre_win']     	= 'n';
			$dataUP['mre_added']   	= $today;
			$dataUP['mre_updated'] 	= date("Y-m-d H:i:s");
			parent::insertData(TABLE_MINTING_RAFFLE_ENTRY,$dataUP);
		 
		  
	}
	
	function minting_reward_winner($date)
	{
		$date  		= $this->filter_mysql($date); 
		$dataSql 	= parent::selectData(TABLE_MINTING_RAFFLE_ENTRY,"","mre_added='".$date."' and mre_win='y'",1);
		$winareId 	= $dataSql['mre_id'];
		if(empty($winareId))
		{
			$site_set	= parent::selectData(TABLE_SETTINGS,"set_jackpot,set_daily_prize","set_id=1",1);
			$sqlRE 		= parent::selectData(TABLE_MINTING_RAFFLE_ENTRY,"","mre_added='".$date."' and mre_win='n'",1,"rand()");
			$win_ids   	= $sqlRE['mre_id'];	
			
			if($win_ids !='')
			{
				$rwD['mre_daily_prize'] 	   	= $site_set['set_daily_prize']; 
				$rwD['mre_jackpot_value'] 	   	= $site_set['set_jackpot']; 
				$rwD['mre_win'] 	   			= 'y'; 
				$rwD['mre_updated'] 			= date("Y-m-d H:i:s");
				parent::updateData(TABLE_MINTING_RAFFLE_ENTRY,$rwD,"mre_id='".$win_ids."'");	
			}
			return $win_ids;	
		} else return $winareId;	
		 
	} 
	
	function get_affilate_income_by_date($date,$userId)
	{
		$date  		= $this->filter_mysql($date); 
		$totAffEar 	= 0; 
		$sqlU 		= parent::selectData(TABLE_USER_WALLET,"sum(wall_asimi) as total_asimi","wall_status='Active' and (wall_type='nmae' or wall_type='a' or wall_type='rwa' or wall_type='vea' or wall_type='laa' or wall_type='lasa' or wall_type='sba' or wall_type='psba') and wall_pstatus='p' and user_id='".$userId."' and wall_created = '".$date."'",1);
		if($sqlU['total_asimi'] > 0 ) 	return $totAffEar = round($sqlU['total_asimi'] ,1); 	
		else return $totAffEar 	= 0.00;	
		
		 
	} 
	
	function get_wall_lsr_user()
	{
		$calDay = "2021-09-25";	
		$fIds 	= "";	 		 
		$lists	=parent::selectData(TABLE_USER_WALLET,"","wall_type='lsr' and wall_pstatus='p' and wall_created = '".$calDay."'","","user_id asc");	
		$i=0;
		while($resR = mysqli_fetch_array($lists))
		{
			 $fIds .= "'".$resR['user_id']."'".",";
			 
			 
		$i++; }
		
		$fIds = rtrim($fIds,",");
		return $fIds;
	 
	}
	
	function total_monlix_usd_reward($userId)
	{ 
		$earning 	= 0;				
		$monlixsql 	= parent::selectData(TABLE_USER_BWALLET,"sum(bwall_reward_value) as total_bal","bwall_status='Active' and bwall_type in ('d','msb','msba','reward') and bwall_pstatus='p' and user_id='".$userId."'",1);
		$earning 	= $monlixsql['total_bal'];
		
		$deduct 	= 0;				
		$monsqlD 	= parent::selectData(TABLE_USER_BWALLET,"sum(bwall_reward_value) as total_bal","bwall_status='Active' and bwall_type ='w' and bwall_pstatus='p' and user_id='".$userId."'",1);
		$deduct 	= $monsqlD['total_bal'];
		$balance 	= $earning - $deduct;	
		if($balance) return($balance);
		else return 0;
	}
	
	function total_monlix_usd_payout($userId)
	{  
		$earning 	= 0;				
		$monlixsql 	= parent::selectData(TABLE_USER_BWALLET,"sum(bwall_price) as total_bal","bwall_status='Active' and bwall_type in ('d','msb','msba','reward') and bwall_pstatus='p' and user_id='".$userId."'",1);
		$earning 	= $monlixsql['total_bal'];
		
		$deduct 	= 0;				
		$monsqlD 	= parent::selectData(TABLE_USER_BWALLET,"sum(bwall_price) as total_bal","bwall_status='Active' and bwall_type ='w' and bwall_pstatus='p' and user_id='".$userId."'",1);
		$deduct 	= $monsqlD['total_bal'];
		$balance 	= $earning - $deduct;	
		if($balance) return($balance);
		else return 0;
		  
	}
	
	
	
	
	
	
	
}

?>