<?php
//header("Access-Control-Allow-Origin: *");
//header('Access-Control-Allow-Headers: X-Requested-With');
mb_http_input("utf-8");
mb_http_output("utf-8");
date_default_timezone_set("PRC");
error_reporting(E_ERROR|E_WARNING);
error_reporting(E_ALL);
error_reporting(0);
session_start();
	##
	$MYSQL_HOST  = "localhost";
	$MYSQL_LOGIN = "1111hui";
	$MYSQL_PASS  = "Hdmt8888";
	$MYSQL_DB    = "1111hui_ban";

	##
	$LOCAL_JS    = TRUE; // FALSE for inclusion of remote js files
    ##

	#a session variable is set by class for much of the CRUD functionality -- eg adding a row
    //session_start();

    #for pesky IIS configurations without silly notifications turned off
    //error_reporting(E_ALL - E_NOTICE);

	$useMySQLi = true;
	if (!class_exists("mysqli")){
		$useMySQLi = false; //mysqli is not enabled on this server; fallback to using mysql
	}

	if ($useMySQLi){
		$mysqliConn = new mysqli($MYSQL_HOST, $MYSQL_LOGIN, $MYSQL_PASS, $MYSQL_DB);
		/* check connection */
		if (mysqli_connect_errno()) {
			//logError("Connect failed in getMysqli(): ", mysqli_connect_error());
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		$mysqliConn->set_charset("utf8");
	}
	else{
		/*
		   use this connection if your hosting config does NOT support mysqli
		   this code was for mySQL connections; was replaced in v8.6 with mysqli
		*/

		$db = @mysql_connect($MYSQL_HOST,$MYSQL_LOGIN,$MYSQL_PASS);

		if(!$db){
			echo('Unable to authenticate user. <br />Error: <b>' . mysql_error() . "</b>");
			exit;
		}
		$connect = @mysql_select_db($MYSQL_DB);
		if (!$connect){
			echo('Unable to connect to db <br />Error: <b>' . mysql_error() . "</b>");
			exit;
		}
		mysql_query("SET NAMES 'utf8'");
		//mysql_query("SET character_set_results = 'utf8_general_ci', character_set_client = 'utf8_general_ci', character_set_connection = 'utf8_general_ci', character_set_database = 'utf8_general_ci', character_set_server = 'utf8_general_ci'", $db);

	}


function getTableComment($tableName){
	global $MYSQL_DB,$mysqliConn, $useMySQLi;
	$comments = mysqli_query($mysqliConn,"SELECT table_comment FROM information_schema.tables WHERE table_schema =  '$MYSQL_DB' AND table_name =  '{$tableName}'" );
	$row=mysqli_fetch_assoc($comments);
	return $row["table_comment"]? $row["table_comment"] :$tableName;
}

function getColumnComment($tableName){
	global $MYSQL_DB,$mysqliConn, $useMySQLi;
	$ret = array();
	$comments = mysqli_query($mysqliConn, "SELECT column_name, column_comment FROM information_schema.columns  WHERE table_schema ='$MYSQL_DB' AND table_name = '{$tableName}'");
	while($row = mysqli_fetch_assoc($comments)){
		$ret[$row["column_name"]] = $row["column_comment"]? $row["column_comment"] :$row["column_name"];
	}
	return $ret;
}

	$GET = array();
	$POST = array();
	escapeRequest();
	function escapeRequest(){
		global $GET, $POST, $mysqliConn, $useMySQLi;
		//isPost = ($_SERVER['REQUEST_METHOD'] === 'POST');
		foreach ($_POST as $key=>$value) {
			$POST[$key] =  $useMySQLi ? mysqli_real_escape_string($mysqliConn,  $value) : mysql_real_escape_string($value) ;
		}
		foreach ($_GET as $key=>$value) {
			$GET[$key] = $useMySQLi ? mysqli_real_escape_string($mysqliConn,  $value) : mysql_real_escape_string($value) ;
		}
	}

	function qs (){
		$args = func_get_args();
		$query  = array_shift($args);
		if(gettype($query)!="string") {
			$debug = $query;
			$query =  array_shift($args);
		}
		return q( vsprintf($query, $args), $debug );
	}


	# what follows are custom database handling functions - required for the ajaxCRUD class
	# ...but these also may be helpful in your application(s) :-)
	if (!function_exists('q')) {
		function q($q, $debug = 0){
			global $mysqliConn, $useMySQLi;

			if ($useMySQLi){
				if (!($r = $mysqliConn->query($q))) {
					$errorMsg = "Mysql Error in preheader.php q(). The query was: " . $q . " and the possible mysqli error follows:" . $mysqliConn->error;
					//logError($errorMsg);
					exit("<p>$errorMsg</p>");
				}
			}
			else{
				//mysql connection; was replaced in v8.6 with mysqli
				$r = mysql_query($q);
				if(mysql_error()){
					echo mysql_error();
					echo "$q<br>";
				}
			}

			if($debug == 1){
				echo "<br>$q<br>";
			}

			if(stristr(substr($q,0,8),"delete") ||	stristr(substr($q,0,8),"insert") || stristr(substr($q,0,8),"update")){
				if ($useMySQLi){
					$affectedRows = $mysqliConn->affected_rows;
				}
				else{
					$affectedRows = mysql_affected_rows();
				}
				if ($affectedRows > 0){
					return true;
				}
				return false;
			}


			if ($useMySQLi){
				$numRows = $r->num_rows;
			}
			else{
				$numRows = mysql_num_rows($r);
			}
			if ($numRows > 1){
				if ($useMySQLi){
					while ($row = $r->fetch_array()){
						$results[] = $row;
					}
				}
				else{
					while($row = mysql_fetch_array($r)){
						$results[] = $row;
					}
				}
			}
			else if ($numRows == 1){
				$results = array();
				if ($useMySQLi){
					$results[] = $r->fetch_array();
				}
				else{
					$results[] = mysql_fetch_array($r);
				}
			}
			else{
				$results = array();
			}

			return $results;
		}
	}

	if (!function_exists('q1')) {
		function q1($q, $debug = 0){
			global $mysqliConn, $useMySQLi;

			if ($useMySQLi){
				if (!($r = $mysqliConn->query($q))) {
					$errorMsg = "Mysql Error in preheader.php q1(). The query was: " . $q . " and the possible mysqli error follows:" . $mysqliConn->error;
					//logError($errorMsg);
					exit($errorMsg);
				}
			}
			else{
				$r = mysql_query($q);
				if(mysql_error()){
					echo mysql_error();
					echo "<br>$q<br>";
				}
			}

			if($debug == 1){
				echo "<br>$q<br>";
			}

			if ($useMySQLi && isset($r)){
				$row = $r->fetch_array();
			}
			else{
				$row = @mysql_fetch_array($r);
			}

			if(count($row) == 2){
				return $row[0];
			}

			return $row;
		}
	}

	if (!function_exists('qr')) {
		function qr($q, $debug = 0){
			global $mysqliConn, $useMySQLi;

			if ($useMySQLi){
				if (!($r = $mysqliConn->query($q))) {
					$errorMsg = "Mysql Error in preheader.php qr(). The query was: " . $q . " and the possible mysqli error follows:" . $mysqliConn->error;
					exit("<p>$errorMsg</p>");
				}
			}
			else{
				$r = mysql_query($q);
				if(mysql_error()){
					echo mysql_error();
					echo "<br>$q<br>";
				}
			}

			if($debug == 1){
				echo "<br>$q<br>";
			}

			if(stristr(substr($q,0,8),"delete") ||	stristr(substr($q,0,8),"insert") || stristr(substr($q,0,8),"update")){
				if ($useMySQLi){
					$numberOfAffectedRows = $mysqliConn->affected_rows;
				}
				else{
					$numberOfAffectedRows = mysql_affected_rows();
				}

				if ($numberOfAffectedRows > 0) {
					return true;
				}
				return false;
			}

			if(stristr(substr($q,0,8),"create") || stristr(substr($q,0,8),"drop")){
				//added for executing create table statements; e.g. the example install script /examples/install.php
				return true;
			}

			$results = array();

			if ($useMySQLi){
				$results[] = $r->fetch_array();
			}
			else{
				$results[] = mysql_fetch_array($r);
			}

			$results = $results[0];

			return $results;
		}
	}





/*
Utf-8、gb2312都支持的汉字截取函数
cut_str(字符串, 截取长度, 开始长度, 编码);
编码默认为 utf-8
开始长度默认为 0
*/

function cut_str($string, $sublen, $start = 0, $code = 'UTF-8')
{
    if($code == 'UTF-8')
    {
        $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
        preg_match_all($pa, $string, $t_string);

        if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."";
        return join('', array_slice($t_string[0], $start, $sublen));
    }
    else
    {
        $start = $start*2;
        $sublen = $sublen*2;
        $strlen = strlen($string);
        $tmpstr = '';

        for($i=0; $i< $strlen; $i++)
        {
            if($i>=$start && $i< ($start+$sublen))
            {
                if(ord(substr($string, $i, 1))>129)
                {
                    $tmpstr.= substr($string, $i, 2);
                }
                else
                {
                    $tmpstr.= substr($string, $i, 1);
                }
            }
            if(ord(substr($string, $i, 1))>129) $i++;
        }
        if(strlen($tmpstr)< $strlen ) $tmpstr.= "";
        return $tmpstr;
    }
}



?>