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
 * 			Filippo Maria Del Prete <filippo.delprete@gmail.com>
 *
 *********************************************************************************************************
 */
 
    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

	include('library/check_operator_perm.php');


	/* //setting values for the order by and order type variables
	isset($_REQUEST['orderBy']) ? $orderBy = $_REQUEST['orderBy'] : $orderBy = "id";
	isset($_REQUEST['orderType']) ? $orderType = $_REQUEST['orderType'] : $orderType = "desc";

	isset($_GET['invoice_id']) ? $invoice_id = $_GET['invoice_id'] : $invoice_id = "";
	isset($_GET['user_id']) ? $user_id = $_GET['user_id'] : $user_id = "";
	isset($_GET['username']) ? $username = $_GET['username'] : $username = "";

	
	$edit_username = $username;
	$edit_invoice_id = $invoice_id;

	include_once('library/config_read.php');
    $log = "visited page: ";
    $logQuery = "performed query for listing of records on page: ";
	 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title>WISP MANAGEMENT</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/1.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="css/form-field-tooltip.css" type="text/css" media="screen,projection" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<script src="library/javascript/pages_common.js" type="text/javascript"></script>
<script src="library/javascript/rounded-corners.js" type="text/javascript"></script>
<script src="library/javascript/form-field-tooltip.js" type="text/javascript"></script>
<?php

	include ("menu-bill-payments.php");
	
?>
		
		<div id="contentnorightbar">
		
				
				
				<div id="helpPage" style="display:none;visibility:visible" >
					<?php echo t('helpPage','paymentslist') ?>
					<br/>
				</div>
				<br/>


<?php

        
	include('db.php');
	
if (isset($_GET['pageno'])) {
            $pageno = $_GET['pageno'];
        } else {
            $pageno = 1;
        }
        $no_of_records_per_page = 15;
        $offset = ($pageno-1) * $no_of_records_per_page;

       
      

        $total_pages_sql = "SELECT COUNT(*) FROM mpesa_payments";
        $result = mysqli_query($con,$total_pages_sql);
        $total_rows = mysqli_fetch_array($result)[0];
        $total_pages = ceil($total_rows / $no_of_records_per_page);

        $sql = "SELECT * FROM mpesa_payments LIMIT $offset, $no_of_records_per_page";
        $res_data = mysqli_query($con,$sql);
       /*  while($row = mysqli_fetch_array($res_data)){
            //here goes the data
        } */
        
    

	
?>
 <div class="container-fluid">
    <div class="quick-actions_homepage">
      <ul class="quick-actions">
        <table id="t01">
  <tr>
	<th>Name</th>
	<th>Account</th>
    <th>Date</th>
    <th>Amount</th> 
    <th>Ref.No</th>
	<th>Phone.No</th>
  </tr>
 
    <?php 
	while ($row = mysqli_fetch_array($res_data)){
	echo"<tr>"."<td>".($row['FirstName'])." ".($row['MiddleName'])." ".($row['LastName'])."</td>";
	echo"<td>".($row['BillRefNumber'])."</td>";
	echo"<td>".date('M,d,Y H:i:s', strtotime($row['TransTime']))."</td>";
    echo"<td>".($row['TransAmount'])."</td>";
    echo"<td>".($row['TransID'])."</td>";
	echo"<td>"."+".($row['MSISDN'])."</td>"."</tr>";}
	?>

<tr>
	<th>Name</th>
	<th>Account</th>
    <th>Date</th>
    <th>Amount</th> 
    <th>Ref.No</th>
	<th>Phone.No</th>
  </tr>

  
</table>
<?php mysqli_close($con);?>

    </div>
<!--End-Action boxes-->    

<!--Chart-box-->    
 
</div>	
<div style="display:inline-block">			
<ul>
        <li><a href="?pageno=1">First</a></li>
        <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
            <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
        </li>
        <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
            <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
        </li>
        <li><a href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
    </ul>
</div>	
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

<script type="text/javascript">
var tooltipObj = new DHTMLgoodies_formTooltip();
tooltipObj.setTooltipPosition('right');
tooltipObj.setPageBgColor('#EEEEEE');
tooltipObj.setTooltipCornerSize(15);
tooltipObj.initFormFieldTooltip();
</script>

</body>
</html>
