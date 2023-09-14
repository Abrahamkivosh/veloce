<?php
/*
Author: Javed Ur Rehman
Website: http://www.allphptricks.com/
*/
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Registration</title>
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php
	require('db.php');
?>
<div class="form">
<h1>Change Plan</h1>
<form name="registration" action="" method="post">
<select style = "color: #0000B5; font-weight:400; width: 262px;" type="text" value="" required="required" name="planName" placeholder="pick your plan">
<option>Please select your plan</option>
<?php
$sqli = "SELECT * FROM billing_plans ORDER BY planName";
										$result2 = mysqli_query($con, $sqli);
											while ($row = mysqli_fetch_array($result2)) {
											# code...
											echo ('<option>'.$row['planName'].'</option>');
										}
										?>
</select>
	
<input type="submit" name="submit" value="Register" />

</form>
<br /><br />
<a href="http://www.lagaster.org/">&copy; <?php echo date("Y"); ?>&nbspLagaster Microsystems</a> <br /><br />
</div>

<?php mysqli_close($con);?>
</body>
</html>
