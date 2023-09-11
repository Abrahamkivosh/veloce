<?php
/*
 *********************************************************************************************************
 * daloRADIUS - RADIUS Web Platform
 * Copyright (C) 2007 - Liran Tal <liran@enginx.com> All Rights Reserved.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 *********************************************************************************************************
 *
 * Authors:	Liran Tal <liran@enginx.com>
 *
 *********************************************************************************************************
 */

    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

	#include('library/check_operator_perm.php');


	include 'library/opendb.php';

        isset($_REQUEST['username']) ? $uname = $_REQUEST['username'] : $uname = "";
        isset($_REQUEST['account']) ? $account = $_REQUEST['account'] : $account = "";
		isset($_REQUEST['balance']) ? $balance = $_REQUEST['balance'] : $balance = "";
        

	$edit_ratename = $uname; //feed the sidebar variables

	$logAction = "";
	$logDebugSQL = "";

	if (isset($_POST['submit'])) {

                $uname = $_POST['username'];
                $account = $_POST['account'];
                $balance = $_POST['balance'];
                

		if (trim($uname) != "") {

			$currDate = date('Y-m-d H:i:s');
			$currBy = $_SESSION['operator_user'];

			$ratetype = "$ratetypenum/$ratetypetime";

			$sql = "UPDATE ".$configValues['CONFIG_DB_TBL_DALOUSERBILLINFO']." SET ".
			" username='".$dbSocket->escapeSimple($uname)."', ".
			" account='".$dbSocket->escapeSimple($account).	"', ".
			" balance='".$dbSocket->escapeSimple($balance)."', ".
			" updatedate='$currDate', updateby='$currBy' ".
			" WHERE username='".$dbSocket->escapeSimple($uname)."'";
			$res = $dbSocket->query($sql);
			$logDebugSQL = "";
			$logDebugSQL .= $sql . "\n";

			$successMsg = "Updated rate: <b> $uname </b>";
			$logAction .= "Successfully updated wallet [$uname] on page: ";

		} else {
			$failureMsg = "no user name was entered, please specify a ruser name to edit.";
			$logAction .= "Failed updating user [$uname] on page: ";
		}

	}


	$sql = "SELECT * FROM ".$configValues['CONFIG_DB_TBL_DALOUSERBILLINFO']." WHERE username='".$dbSocket->escapeSimple($uname)."'";
	$res = $dbSocket->query($sql);
	$logDebugSQL .= $sql . "\n";

	$row = $res->fetchRow();
	$uname = $row[3];
	$account = $row[1];
	

	include 'library/closedb.php';


	if (trim($uname) == "") {
		$failureMsg = "no user name was entered or found in database, please specify a user name to edit";
	}


	include_once('library/config_read.php');
	$log = "visited page: ";


?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title><?php include('sitename.php');echo $sitename;?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/1.css" type="text/css" media="screen,projection" />
</head>
<script src="library/javascript/pages_common.js" type="text/javascript"></script>
<?php
	include_once ("library/tabber/tab-layout.php");
?>

<?php
	include ("menu-bill-wallet.php");
?>
	<div id="contentnorightbar">

		<h2 id="Intro" onclick="javascript:toggleShowDiv('helpPage')"><?php echo t('Intro','billwalletedit.php') ?>
		:: <?php if (isset($uname)) { echo $uname; } ?><h144>&#x2754;</h144></a></h2>

		<div id="helpPage" style="display:none;visibility:visible" >
			<?php echo t('helpPage','billwalletedit') ?>
			<br/>
		</div>
		<?php
			include_once('include/management/actionMessages.php');
		?>

		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

<div class="tabber">

	<div class="tabbertab" title="<?php echo t('title','walletInfo'); ?>">


	<fieldset>

		<h302> <?php echo t('title','walletInfo'); ?> </h302>
		<br/>

		<ul>

			<li class='fieldset'>
			<label for='ratename' class='form'><?php echo t('all','WalletName') ?></label>
			<input disabled name='ratename' type='text' id='ratename' value='<?php echo $uname ?>' tabindex=100 />
			</li>

			

			

			<li class='fieldset'>
			<br/>
			<hr><br/>
			<input type='submit' name='submit' value='<?php echo t('buttons','apply') ?>' tabindex=10000
				class='button' />
			</li>

		</ul>

	</fieldset>

	<input type=hidden value="<?php echo $uname ?>" name="ratename"/>

</div>




		</form>

<?php
	include('include/config/logging.php');
?>

		</div>

		<div id="footer">

<?php
	include 'page-footer.php';
?>


		</div>

</div>
</div>


</body>
</html>
