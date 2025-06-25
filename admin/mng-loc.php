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
 * Authors:    Liran Tal <liran@enginx.com>
 *
 *********************************************************************************************************
 */

include "library/checklogin.php";
$operator = $_SESSION['operator_user'];

include 'library/check_operator_perm.php';

// include db connection file
require_once "db.php";

// set session's page variable
$_SESSION['PREV_LIST_PAGE'] = $_SERVER['REQUEST_URI'];

//setting values for the order by and order type variables
isset($_REQUEST['orderBy']) ? $orderBy = $_REQUEST['orderBy'] : $orderBy = "id";
isset($_REQUEST['orderType']) ? $orderType = $_REQUEST['orderType'] : $orderType = "asc";

include_once 'library/config_read.php';
$log = "visited page: ";
$logQuery = "performed query for listing of records on page: ";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<script src="library/javascript/pages_common.js" type="text/javascript"></script>
<script src="library/javascript/rounded-corners.js" type="text/javascript"></script>
<script src="library/javascript/form-field-tooltip.js" type="text/javascript"></script>

<script type="text/javascript" src="library/javascript/ajax.js"></script>
<script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>

<title>WISP MANAGEMENT</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="css/1.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="css/2.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="css/form-field-tooltip.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" type="text/css" href="library/js_date/datechooser.css">
<!--[if lte IE 6.5]>
<link rel="stylesheet" type="text/css" href="library/js_date/select-free.css"/>
<![endif]-->
</head>

<?php

include "menu-mng-users.php";

?>
    <?php

if (isset($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}
$no_of_records_per_page = 20;
$offset = ($pageno - 1) * $no_of_records_per_page;

$total_pages_sql = "SELECT COUNT(*) FROM userbillinfo";
$result = mysqli_query($con, $total_pages_sql);
$total_rows = mysqli_fetch_array($result)[0];
// print_r($total_rows);
// exit();
$total_pages = ceil($total_rows / $no_of_records_per_page);

$sql = "SELECT * FROM userbillinfo LIMIT $offset, $no_of_records_per_page";
$res_data = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($res_data)) {
    //here goes the data
}

?>

		<div id="contentnorightbar">



                <div id="helpPage" style="display:none;visibility:visible" >
					<?php echo t('helpPage', 'mnglistall') ?>
					<br/>
				</div>
					<div id="returnMessages">
					</div>
				<br/>

<?php

// must be included after opendb because it needs to read the CONFIG_IFACE_TABLES_LISTING variable from the config file

// setup php session variables for exporting
$_SESSION['reportTable'] = $configValues['CONFIG_DB_TBL_RADCHECK'];
$_SESSION['reportQuery'] = " WHERE UserName LIKE '%'";
$_SESSION['reportType'] = "usernameListGeneric";

//    $query

// $orderBy = $dbSocket->escapeSimple($orderBy);
// $orderType = $dbSocket->escapeSimple($orderType);

//orig: used as method to get total rows - this is required for the pages_numbering.php page

/* we are searching for both kind of attributes for the password, being User-Password, the more
common one and the other which is Password, this is also done for considerations of backwards
compatibility with version 0.7        */

$sql = "SELECT userbillinfo.username, userinfo.firstname, userbillinfo.phone, userinfo.lastname, userbillinfo.account, userbillinfo.location, userbillinfo.area, userbillinfo.building, userbillinfo.planName FROM userbillinfo RIGHT JOIN userinfo ON userbillinfo.username=userinfo.username ORDER BY account LIMIT $offset, $no_of_records_per_page";
$result = mysqli_query($con, $sql);
$logDebugSQL = "";
$logDebugSQL .= $sql . "\n";

if (isset($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}
$no_of_records_per_page = 3;
$offset = ($pageno - 1) * $no_of_records_per_page;

$total_pages_sql = "SELECT COUNT(*) FROM userbillinfo";
$result2 = mysqli_query($con, $total_pages_sql);
$total_rows = mysqli_fetch_array($result2)[0];
$total_pages = ceil($total_rows / $no_of_records_per_page);

echo "<table>";
echo "
<tr>
<th>Name</th>
<th>Phone</th>
<th>Account</th>
<th>Geolocation</th>
<th>Area</th>
<th>Building</th>
<th>Plan</th>
</tr>";

while ($row = mysqli_fetch_array($result)) {

    echo "<tr>";
    echo "<td>" . ($row['firstname']) . " " . ($row['lastname']) . "</td>";
    echo "<td>" . $row['phone'] . "</td>";
    echo "<td>" . $row['account'] . "</td>";
    echo "<td>" . $row['location'] . "</td>";
    echo "<td>" . $row['area'] . "</td>";
    echo "<td>" . $row['building'] . "</td>";
    echo "<td>" . $row['planName'] . "</td>";
    echo "</tr>";

}

echo "
<tr>

</tr>";
echo "</table>";

mysqli_close($con);
?>
<ul class="pagination">
        <li><a href="?pagesi=1">First</a></li>
        <li class="<?php if ($pageno <= 1) {echo 'disabled';}?>">
            <a href="<?php if ($pageno <= 1) {echo '#';} else {echo "?pageno=" . ($pageno - 1);}?>">Prev</a>
        </li>
        <li class="<?php if ($pageno >= $total_pages) {echo 'disabled';}?>">
            <a href="<?php if ($pageno >= $total_pages) {echo '#';} else {echo "?pageno=" . ($pageno + 1);}?>">Next</a>
        </li>
        <li><a href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
    </ul>


<?php
include 'include/config/logging.php';
?>

		</div>

		<div id="footer">

<?php
include 'page-footer.php';
?>


		</div>

</div>
</div>


<script type="text/javascript">
	var tooltipObj = new DHTMLgoodies_formTooltip();
	tooltipObj.setTooltipPosition('right');
	tooltipObj.setPageBgColor('#EEEEEE');
	tooltipObj.setTooltipCornerSize(15);
	tooltipObj.initFormFieldTooltip();
</script>

</body>
</html>
