<?php

require('db.php');
include("auth.php"); //include auth.php file on all secure pages ?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Urban Wireless</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="css/bootstrap.min.css" />
<link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="css/fullcalendar.css" />
<link rel="stylesheet" href="css/stylez.css">
<link rel="stylesheet" href="css/matrix-style.css" />
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/matrix-media.css" />
<link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
<link rel="stylesheet" href="css/jquery.gritter.css" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
</head>
<body>

<!--Header-part-->
<div id="header">
  <h1><a href="dash.php">Urban Wireless</a></h1>
</div>
<!--close-Header-part--> 


<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
    <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">Welcome <?php echo $_SESSION['username']; ?>!</span><b class="caret"></b></a>
      <ul class="dropdown-menu">
        
        <li class="divider"></li>
        <li><a href="logout.php"><i class="icon-key"></i> Log Out</a></li>
      </ul>
    </li>
    <li class="dropdown" id="menu-messages"><a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle"><i class="icon icon-envelope"></i> <span class="text">Messages</span> <span class="label label-important">5</span> <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a class="sAdd" title="" href="#"><i class="icon-plus"></i> new message</a></li>
        <li class="divider"></li>
        <li><a class="sInbox" title="" href="#"><i class="icon-envelope"></i> inbox</a></li>
        <li class="divider"></li>
        <li><a class="sOutbox" title="" href="#"><i class="icon-arrow-up"></i> outbox</a></li>
        <li class="divider"></li>
        <li><a class="sTrash" title="" href="#"><i class="icon-trash"></i> trash</a></li>
      </ul>
    </li>
    <li class=""><a title="" href="#"><i class="icon icon-cog"></i> <span class="text">Settings</span></a></li>
    <li class=""><a title="" href="logout.php"><i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>
  </ul>
  
</div>
<!--close-top-Header-menu-->
<!--start-top-serch-->
<div id="search">
  <input type="text" placeholder="Search here..."/>
  <button type="submit" class="tip-bottom" title="Search"><i class="icon-search icon-white"></i></button>
</div>
<!--close-top-serch-->
<!--sidebar-menu-->
<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
  <ul>
    <li class="active"><a href="dash.php"><i class="icon icon-home"></i> <span>Dashboard</span></a> </li>
    <li> <a href="charts.html"><i class="icon icon-signal"></i> <span>My Payments</span></a> </li>
     
    </li>
    
    <li class="content"> <span>Monthly Bandwidth Transfer</span>
      <div class="progress progress-mini progress-danger active progress-striped">
        <div style="width: 77%;" class="bar"></div>
      </div>
      <span class="percent">77%</span>
      <div class="stat">21419.94 / 14000 MB</div>
    </li>
    <li class="content"> <span>Disk Space Usage</span>
      <div class="progress progress-mini active progress-striped">
        <div style="width: 87%;" class="bar"></div>
      </div>
      <span class="percent">87%</span>
      <div class="stat">604.44 / 4000 MB</div>
    </li>
  </ul>
</div>
<!--sidebar-menu-->


<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->
<?php
$sess = $_SESSION['username'];

$sqli = "SELECT * FROM userbillinfo WHERE username = '$sess'";
										$result = mysqli_query($con, $sqli);
											while ($row = mysqli_fetch_array($result)) {
											# code...
											$plan = ($row['planName']);
											$account = ($row['account']);
											
											
										}
$sqli = "SELECT * FROM userinfo WHERE username = '$sess'";
										$result2 = mysqli_query($con, $sqli);
											while ($row = mysqli_fetch_array($result2)) {
											# code...
											$fName = ($row['firstname']);
											$lName = ($row['lastname']);	
											
										}
$sqli = "SELECT * FROM radcheck WHERE ((username = '$sess')&&(attribute = 'Expiration'))";
										$result3 = mysqli_query($con, $sqli);
											while ($row = mysqli_fetch_array($result3)) {
											# code...
											$eXpiry = strtotime($row['value']);
											$times = time();
											if($eXpiry > $times ){
												$cls = "bg_ly";
												$status = "Active!</br>&#x1F603;";
												$days = round(($eXpiry - $times) / (60 * 60 * 24));
												$reminder = "Paid";
												$remcol = "bg_lg";
											}else if($eXpiry < $times){
												$cls = "bg_lr";
												$status = "Expired</br></br>&#x1F61E;";
												$reminder = "Pay Now";
												$remcol = "bg_lr";
												$days = round(($eXpiry - $times) / (60 * 60 * 24));
											}else{
												echo "OLA";
											}
											
										}
$sqli = "SELECT * FROM billing_plans WHERE planName = '$plan'";
										$result4 = mysqli_query($con, $sqli);
										while ($row = mysqli_fetch_array($result4)) {
											$plancost = ($row['planCost']);
										}
$sqli = "SELECT * FROM mpesa_payments WHERE ((BillRefNumber = '$account')&&(utilized==0))";
										$result5 = mysqli_query($con, $sqli);
										if !isset($result5){
											sleep(10);
											$result5 = mysqli_query($con, $sqli);	
										}
										else{
										while ($row = mysqli_fetch_array($result5)) {
											$mid = ($row['TransID']);
											$mtime = strtotime($row['TransTime']);
											$mamount = ($row['TransAmount']);
											$mpaccount = ($row['BillRefNumber']);
											$phone = ($row['MSISDN']);
											
										}
										}

}						
										?>
<?php

function pay($mid, $mamount){
	if (isset($mid)&&($mamount==$plancost)){
		$sqli = "SELECT * FROM radcheck WHERE username = '$sess'";
		$res = mysqli_query($con, $sqli);
		$= time();
		$nwtime=$mtime + 2592000;
	if isset($res){
		$sql = "UPDATE radcheck SET value='$nwtime' WHERE attribute='Expiration'";
		
	}
		
		$ins = "INSERT into `radcheck` (enableportallogin, firstname, lastname, username, portalloginpassword, email, mobilephone, creationdate, creationby, updatedate, updateby, ckey, ctime, mng_hs_usr ) VALUES ('$enabled', '$firstname', '$lastname', '$username', '".md5($password)."', '$email', '$mobilephone', '$trn_date', '$created_by', '$update', '$updated_by', '$ckey', '$ctime', '$mng_hs_usr')";
	
	}
	
}					?>					


<!--Modal-->
<div class="modal" id="modal-name">
  <div class="modal-sandbox"></div>
  <div class="modal-box">
    <div class="modal-header">
      <div class="close-modal">&#10006;</div> 
      <img src= "img/mpesa.png" width="100px"height="50px">
    </div>
    <div class="modal-body">
	<ol>
      <li>Go to Mpesa Menu</li>
      <li>Select Lipa na M-PESA</li>
	  <li>Select Pay Bill</li>
	  <li>Enter Business No: <strong style = "color:#FF5733;">609497</strong></li>
	  <li>Enter Account No:&nbsp <strong style = "color:#FF5733;"> <?php echo $account?> </strong></li>
	  <li>Enter Amount:&nbsp <strong style = "color:#FF5733;"><?php echo $plancost?> </strong> </li>
	  <li>Enter Your Mpesa Pin and press ok.</li>
	 </ol>
	  <p>Please enter the exact amount as indicated, to complete the payment</p>
	  <ul><li class="bg_ls"> <a href="#" class="confirm" data-modal="confirm">Modal open!</a> <!-- Trigger Modal. --> </li></ul>
	  
	</div>
  </div>
</div>
<div class="modal" id="confirm">
  <div class="modal-sandbox"></div>
  <div class="modal-box">
    <div class="modal-header">
      <div class="close-modal">&#10006;</div> 
      <img src= "img/mpesa.png" width="100px"height="50px">
    </div>
    <div class="modal-body">
	<img src = "img/wait.gif">
	</div>
  </div>
</div>
<!--Action boxes-->

  <div class="container-fluid">
    <div class="quick-actions_homepage">
      <ul class="quick-actions">
        <li class="bg_lb"> <a href="#">  <span class="label label-important">20</span>Name:</br> <?php echo strtoupper("<strong>".$fName."</br>".$lName."</strong>");?> </a> </li>
        <li class="bg_lg span3"> <a href="">Current Plan:</br> <?php echo strtoupper($plan);?></br></a> </li>
        <li class="<?php echo $cls;?>"> <a href="#"><span class="label label-success"><?php echo $days."&nbspDays"?></span><?php echo "Status:&nbsp".$status;?> </a> </li>
        <li class="bg_lo"> <a href="">Account Number:</br><?php echo strtoupper("<strong>".$account."</strong>");?></a> </li>
        
		<li class="bg_ls"> <a href="#" class="modal-trigger" data-modal="modal-name">Modal open!</a> <!-- Trigger Modal. --> </li>
        <li class="bg_lo span3"> <a href="form-common.html"> <i class="icon-th-list"></i> Forms</a> </li>
        <li class="bg_ls"> <a href="buttons.html"> <i class="icon-tint"></i> Buttons</a> </li>
        <li class="bg_lb"> <a href="interface.html"> <i class="icon-pencil"></i>Elements</a> </li>
        <li class="bg_lg"> <a href="calendar.html"> <i class="icon-calendar"></i> Calendar</a> </li>
        <li class="<?php echo $remcol;?>"> <a href=""> <i class="icon-info-sign"></i><?php echo $reminder;?></a> </li>

      </ul>
    </div>
<!--End-Action boxes-->    

<!--Chart-box-->    
    <div class="row-fluid">
      <div class="widget-box">
        <div class="widget-title bg_lg"><span class="icon"><i class="icon-signal"></i></span>
          <h5>Site Analytics</h5>
        </div>
        <div class="widget-content" >
          <div class="row-fluid">
            <div class="span9">
              <div class="chart"></div>
            </div>
            <div class="span3">
              <ul class="site-stats">
                <li class="bg_lh"><i class="icon-user"></i> <strong>2540</strong> <small>Total Users</small></li>
                <li class="bg_lh"><i class="icon-plus"></i> <strong>120</strong> <small>New Users </small></li>
                <li class="bg_lh"><i class="icon-shopping-cart"></i> <strong>656</strong> <small>Total Shop</small></li>
                <li class="bg_lh"><i class="icon-tag"></i> <strong>9540</strong> <small>Total Orders</small></li>
                <li class="bg_lh"><i class="icon-repeat"></i> <strong>10</strong> <small>Pending Orders</small></li>
                <li class="bg_lh"><i class="icon-globe"></i> <strong>8540</strong> <small>Online Orders</small></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
<!--End-Chart-box--> 
    <hr/>
    
  </div>
</div>

<!--end-main-container-part-->

<!--Footer-part-->

<div class="row-fluid">
  <div id="footer" class="span12"> &copy;&nbsp<?php echo date('Y')?> Client portal. Brought to you by <a href="http://lagaster.org">Lagaster Microsystems</a> </div>
</div>

<!--end-Footer-part-->

<script src="js/excanvas.min.js"></script> 
<script src="js/index.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<script src="js/jquery.min.js"></script> 
<script src="js/jquery.ui.custom.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/jquery.flot.min.js"></script> 
<script src="js/jquery.flot.resize.min.js"></script> 
<script src="js/jquery.peity.min.js"></script> 
<script src="js/fullcalendar.min.js"></script> 
<script src="js/matrix.js"></script> 
<script src="js/matrix.dashboard.js"></script> 
<script src="js/jquery.gritter.min.js"></script> 
<script src="js/matrix.interface.js"></script> 
<script src="js/matrix.chat.js"></script> 
<script src="js/jquery.validate.js"></script> 
<script src="js/matrix.form_validation.js"></script> 
<script src="js/jquery.wizard.js"></script> 
<script src="js/jquery.uniform.js"></script> 
<script src="js/select2.min.js"></script> 
<script src="js/matrix.popover.js"></script> 
<script src="js/jquery.dataTables.min.js"></script> 
<script src="js/matrix.tables.js"></script> 

<script type="text/javascript">

$(".modal-trigger").click(function(e){
  e.preventDefault();
  dataModal = $(this).attr("data-modal");
  $("#" + dataModal).css({"display":"block"});
  // $("body").css({"overflow-y": "hidden"}); //Prevent double scrollbar.
});

$(".close-modal, .modal-sandbox").click(function(){
  $(".modal").css({"display":"none"});
  // $("body").css({"overflow-y": "auto"}); //Prevent double scrollbar.
});
</script>
<script type="text/javascript">

$(".confirm").click(function(e){
  e.preventDefault();
  dataModal = $(this).attr("data-modal");
  $("#" + dataModal).css({"display":"block"});
  // $("body").css({"overflow-y": "hidden"}); //Prevent double scrollbar.
});

$(".close-modal, .modal-sandbox").click(function(){
  $(".modal").css({"display":"none"});
  // $("body").css({"overflow-y": "auto"}); //Prevent double scrollbar.
});
</script>
<script type="text/javascript">
  // This function is called from the pop-up menus to transfer to
  // a different page. Ignore if the value returned is a null string:
  function goPage (newURL) {

      // if url is empty, skip the menu dividers and reset the menu selection to default
      if (newURL != "") {
      
          // if url is "-", it is this page -- reset the menu:
          if (newURL == "-" ) {
              resetMenu();            
          } 
          // else, send page to designated URL            
          else {  
            document.location.href = newURL;
          }
      }
  }

// resets the menu selection upon entry to this page:
function resetMenu() {
   document.gomenu.selector.selectedIndex = 2;
}
</script>
</body>
</html>
