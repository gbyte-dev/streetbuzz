<?php

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* ADVANCED */

if(!file_exists(dirname(dirname(dirname(dirname(__FILE__)))).DIRECTORY_SEPARATOR.'system'.DIRECTORY_SEPARATOR.'conf_main.php')){
	echo "Please check if cometchat is installed in the correct directory <br> Generally cometchat should be installed in <SHARETRONIX_HOME_DIRECTORY>/cometchat";
	exit;
}
$C = new StdClass;
$C->INCPATH = dirname(dirname(dirname(dirname(__FILE__)))).DIRECTORY_SEPARATOR.'system';
include_once(dirname(dirname(dirname(dirname(__FILE__)))).DIRECTORY_SEPARATOR.'system'.DIRECTORY_SEPARATOR.'conf_main.php');

define('SET_SESSION_NAME',$C->RNDKEY.str_replace(array('.','-'), '', $C->DOMAIN));	// Session name
define('DO_NOT_START_SESSION','0');													// Set to 1 if you have already started the session
define('SWITCH_ENABLED','0');
define('INCLUDE_JQUERY','1');
define('FORCE_MAGIC_QUOTES','0');

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* DATABASE */

// DO NOT EDIT DATABASE VALUES BELOW
// DO NOT EDIT DATABASE VALUES BELOW
// DO NOT EDIT DATABASE VALUES BELOW

define('DB_SERVER',				$C->DB_HOST				);
define('DB_PORT',				"3306"					);
define('DB_USERNAME',				$C->DB_USER				);
define('DB_PASSWORD',				$C->DB_PASS				);
define('DB_NAME',				$C->DB_NAME				);
if(defined('USE_CCAUTH') && USE_CCAUTH == '0'){
define('TABLE_PREFIX',				""					);
define('DB_USERTABLE',				"users"					);
define('DB_USERTABLE_USERID',                   "id"					);
define('DB_USERTABLE_NAME',			"username"				);
define('DB_AVATARTABLE',                        " "					);
define('DB_AVATARFIELD',                        " ".TABLE_PREFIX.DB_USERTABLE.".avatar ");

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* FUNCTIONS */


	function getUserID() {
		$userid = 0;

		if (!empty($_SESSION['basedata']) && $_SESSION['basedata'] != 'null') {
			$_REQUEST['basedata'] = $_SESSION['basedata'];
		}

		if (!empty($_REQUEST['basedata'])) {

			if (function_exists('mcrypt_encrypt') && defined('ENCRYPT_USERID') && ENCRYPT_USERID == '1') {
				$key = "";
				if( defined('KEY_A') && defined('KEY_B') && defined('KEY_C') ){
					$key = KEY_A.KEY_B.KEY_C;
				}
				$uid = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode(rawurldecode($_REQUEST['basedata'])), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
				if (intval($uid) > 0) {
					$userid = $uid;
				}
			} else {
				$userid = $_REQUEST['basedata'];
			}
		}

		if (!empty($_SESSION['NETWORKS_USR_DATA']) && !empty($_SESSION['NETWORKS_USR_DATA'][1]) && !empty($_SESSION['NETWORKS_USR_DATA'][1]['LOGGED_USER']) && !empty($_SESSION['NETWORKS_USR_DATA'][1]['LOGGED_USER']->id)) {
			$userid = $_SESSION['NETWORKS_USR_DATA'][1]['LOGGED_USER']->id;
		}

		$userid = intval($userid);
		return $userid;
	}

	function chatLogin($userName,$userPass) {
		$userid = 0;
		if (filter_var($userName, FILTER_VALIDATE_EMAIL)) {
			$sql = ("SELECT * FROM ".TABLE_PREFIX.DB_USERTABLE." WHERE email ='".$userName."'");
		} else {
			$sql = ("SELECT * FROM ".TABLE_PREFIX.DB_USERTABLE." WHERE username ='".$userName."'");
		}
		$result = mysqli_query($GLOBALS['dbh'],$sql);
		$row = mysqli_fetch_assoc($result);
		if($row['password'] == md5($userPass)){
			$userid =  $row['id'];
	                if (isset($_REQUEST['callbackfn']) && $_REQUEST['callbackfn'] == 'mobileapp') {
	                    $sql = ("insert into cometchat_status (userid,isdevice) values ('".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."','1') on duplicate key update isdevice = '1'");
	                    mysqli_query($GLOBALS['dbh'], $sql);
	                }
		}
		if($userid && function_exists('mcrypt_encrypt') && defined('ENCRYPT_USERID') && ENCRYPT_USERID == '1'){
			$key = "";
				if( defined('KEY_A') && defined('KEY_B') && defined('KEY_C') ){
					$key = KEY_A.KEY_B.KEY_C;
				}
			$userid = rawurlencode(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $userid, MCRYPT_MODE_CBC, md5(md5($key)))));
		}

		return $userid;
	}

	function getFriendsList($userid,$time) {
		global $hideOffline;
		$offlinecondition = '';
		$sql = ("select DISTINCT ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." userid, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." username, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." link, ".DB_AVATARFIELD." avatar, cometchat_status.lastactivity lastactivity, cometchat_status.status, cometchat_status.message, cometchat_status.isdevice from ".TABLE_PREFIX."users_followed join ".TABLE_PREFIX.DB_USERTABLE." on  ".TABLE_PREFIX."users_followed.who = ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." left join cometchat_status on ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = cometchat_status.userid ".DB_AVATARTABLE." where ".TABLE_PREFIX."users_followed.whom = '".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."' order by username asc");
		if ((defined('MEMCACHE') && MEMCACHE <> 0) || DISPLAY_ALL_USERS == 1) {
			if ($hideOffline) {
				$offlinecondition = "where ((cometchat_status.lastactivity > (".mysqli_real_escape_string($GLOBALS['dbh'],$time)."-".((ONLINE_TIMEOUT)*2).")) OR cometchat_status.isdevice = 1)
	 and (cometchat_status.status IS NULL OR cometchat_status.status <> 'invisible' OR cometchat_status.status <> 'offline')";
			}
			$sql = ("select DISTINCT ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." userid, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." username, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." link, ".DB_AVATARFIELD." avatar, cometchat_status.lastactivity lastactivity, cometchat_status.status, cometchat_status.message, cometchat_status.isdevice from ".TABLE_PREFIX.DB_USERTABLE." left join cometchat_status on ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = cometchat_status.userid ".DB_AVATARTABLE." ".$offlinecondition." order by username asc");
		}

		return $sql;
	}

	function getFriendsIds($userid) {
		$sql = ("select ".TABLE_PREFIX."users_followed.who friendid from ".TABLE_PREFIX."users_followed where ".TABLE_PREFIX."users_followed.whom = '".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."'");

		return $sql;
	}

	function getUserDetails($userid) {
		$sql = ("select ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." userid, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." username, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." link, ".DB_AVATARFIELD." avatar, cometchat_status.lastactivity lastactivity, cometchat_status.status, cometchat_status.message, cometchat_status.isdevice from ".TABLE_PREFIX.DB_USERTABLE." left join cometchat_status on ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = cometchat_status.userid ".DB_AVATARTABLE." where ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = '".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."'");

		return $sql;
	}

	function updateLastActivity($userid) {
		$sql = ("insert into cometchat_status (userid,lastactivity) values ('".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."','".getTimeStamp()."') on duplicate key update lastactivity = '".getTimeStamp()."'");
		return $sql;
	}

	function getUserStatus($userid) {
		 $sql = ("select cometchat_status.message, cometchat_status.status from cometchat_status where userid = '".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."'");

		 return $sql;
	}

	function fetchLink($link) {
		 return BASE_URL.'../'.$link;
	}

	function getAvatar($image) {
		if($image){
			if (is_file(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'avatars'.DIRECTORY_SEPARATOR.$image)) {
	        	return BASE_URL.'../storage/avatars/'.$image;
		    }else if (is_file(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'i'.DIRECTORY_SEPARATOR.'avatars'.DIRECTORY_SEPARATOR.$image)) {
	        	return BASE_URL.'../i/avatars/'.$image;
		    } else {
		        return BASE_URL.'/images/noavatar.png';
		    }
		}
		else{
			if (is_file(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'avatars'.DIRECTORY_SEPARATOR.'_noavatar_user.gif')) {
	        	return BASE_URL.'../storage/avatars/'.'_noavatar_user.gif';
		    }else if (is_file(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'i'.DIRECTORY_SEPARATOR.'avatars'.DIRECTORY_SEPARATOR.'_noavatar_user.gif')) {
	        	return BASE_URL.'../i/avatars/'.'_noavatar_user.gif';
		    } else {
		        return BASE_URL.'/images/noavatar.png';
		    }
		}
		
	}

	function getTimeStamp() {
		return time();
	}

	function processTime($time) {
		return $time;
	}

	if (!function_exists('getLink')) {
	  	function getLink($userid) { return fetchLink($userid); }
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/* HOOKS */

	function hooks_statusupdate($userid,$statusmessage) {

	}

	function hooks_forcefriends() {

	}

	function hooks_activityupdate($userid,$status) {

	}

	function hooks_message($userid,$to,$unsanitizedmessage) {

	}

	function hooks_updateLastActivity($userid) {

	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* LICENSE */

include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'license.php');
$x="\x62a\x73\x656\x34\x5fd\x65c\157\144\x65";
eval($x('JHI9ZXhwbG9kZSgnLScsJGxpY2Vuc2VrZXkpOyRwXz0wO2lmKCFlbXB0eSgkclsyXSkpJHBfPWludHZhbChwcmVnX3JlcGxhY2UoIi9bXjAtOV0vIiwnJywkclsyXSkpOw'));

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
