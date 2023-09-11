<?php

require('db.php');
include("auth.php"); //include auth.php file on all secure pages ?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Dashboard</title>
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
<?php require('basic.php');?>
<!--Header-part-->
<div id="header">
  <h1><a href="dash.php">Christa Networks</a></h1>
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
    <!--<li class="dropdown" id="menu-messages"><a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle"><i class="icon icon-envelope"></i> <span class="text">Messages</span> <span class="label label-important">5</span> <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a class="sAdd" title="" href="#"><i class="icon-plus"></i> new message</a></li>
        <li class="divider"></li>
        <li><a class="sInbox" title="" href="#"><i class="icon-envelope"></i> inbox</a></li>
        <li class="divider"></li>
        <li><a class="sOutbox" title="" href="#"><i class="icon-arrow-up"></i> outbox</a></li>
        <li class="divider"></li>
        <li><a class="sTrash" title="" href="#"><i class="icon-trash"></i> trash</a></li>
      </ul>
    </li>-->
    
    <li class=""><a title="" href="logout.php"><i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>
  </ul>
  
</div>
<?php
$sess = $_SESSION['username'];

$sqli = "SELECT * FROM userbillinfo WHERE username = '$sess'";
										$result = mysqli_query($con, $sqli);
											while ($row = mysqli_fetch_array($result)) {
											# code...
											$plan = ($row['planName']);
											$account = ($row['account']);
											$balance = ($row['balance']);
											
											
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
											$rexp = ($row['value']);
											$eXpiry = strtotime($row['value']);
											$times = time();
											if($eXpiry > $times ){
												$cls = "bg_ly";
												$status = "Active!</br>&#x1F603;";
												$days = round(($eXpiry - $times) / (60 * 60 * 24));
												$reminder = "Account active </br>Connect";
												$href= "http://www.sef.com";
												$mdalcls = "";
												$datamd = "";
												$remcol = "bg_lg";
											}else if($eXpiry < $times){
												$cls = "bg_lr";
												$status = "Expired</br></br>&#x1F61E;";
												$reminder = "UNPAID!</br>Pay Now";
												$href= "#";
												$mdalcls = "modal-trigger";
												$datamd = "modal-name";
												$remcol = "bg_lr";
												$days = round(($eXpiry - $times) / (60 * 60 * 24));
											}else{
												$cls = "bg_lr";
												$status = "Expired</br></br>&#x1F61E;";
												$reminder = "NOT PAID!</br>Pay Now";
												$href= "#";
												$mdalcls = "modal-trigger";
												$datamd = "modal-name";
												$remcol = "bg_lr";
											}
											
										}
$sqli = "SELECT * FROM billing_plans WHERE planName = '$plan'";
										$result4 = mysqli_query($con, $sqli);
										while ($row = mysqli_fetch_array($result4)) {
											$plancost = ($row['planCost']);
											$upload = ($row['planBandwidthUp']);
											$DOWNLOAD = ($row['planBandwidthUp']);
										}

										?>
<!--close-top-Header-menu
start-top-serch
<div id="search">
  <input type="text" placeholder="Search here..."/>
  <button type="submit" class="tip-bottom" title="Search"><i class="icon-search icon-white"></i></button>
</div>-->
<!--close-top-serch-->
<!--sidebar-menu-->
<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
  <ul>
    <li class="active"><a href="dash.php"><i class="icon icon-home"></i> <span>Dashboard</span></a> </li>
    <li> <a href="payments.php"><i class="icon icon-signal"></i> <span>My Payments</span></a> </li>
	<li> <a href="wallet.php"><i class="icon icon-signal"></i> <span>My Wallet</span></a> </li>
     
    </li>
    
    <li class="content"> <span>Upload </span>
      <div class="progress progress-mini progress-danger active progress-striped">
        <div style="width: <?php echo (($upload/100000)*100)?>%;" class="bar"></div>
      </div>
      <span class="percent">Mbps</span>
      <div class="stat"><?php echo $upload?></div>
    </li>
    <li class="content"> <span>DOWNLOAD</span>
      <div class="progress progress-mini active progress-striped">
        <div style="width: <?php echo (($upload/100000)*100)?>%;" class="bar"></div>
		
      </div>
      <span class="percent">Mbps</span>
      <div class="stat"><?php echo $DOWNLOAD?></div>
    </li>
  </ul>
</div>
<!--sidebar-menu-->

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="dash.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->

				


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
	  <li>Enter Business No: <strong style = "color:#FF5733;"><?php echo $paybill;?></strong></li>
	  <li>Enter Account No:&nbsp <strong style = "color:#FF5733;"> <?php echo $account?> </strong></li>
	  <li>Enter Amount:&nbsp <strong style = "color:#FF5733;"><?php echo $plancost?> </strong> </li>
	  <li>Enter Your Mpesa Pin and press ok.</li>
	 </ol>
	  <p>Please enter the exact amount as indicated, to complete the payment</p>
	  <input type="submit" class="confirm" data-modal="confirm" value="Confirm Payment.">
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
	<img src = "img/giphy.gif" width="300px" height="255px">
	</div>
  </div>
</div>
<!--Action boxes-->

  <div class="container-fluid">
    <div class="quick-actions_homepage">
      <ul class="quick-actions">
        <li class="bg_lb"> <a href="#">  <span class="label label-important"></span>Name:</br> <?php echo strtoupper("<strong>".$fName."</br>".$lName."</strong>");?> </a> </li>
		<li class="<?php echo $remcol;?>"> <a href ="<?php echo $href;?>" class="<?php echo $mdalcls;?>" data-modal="<?php echo $datamd;?>"><?php echo $reminder;?></a> </li>
        <li class="bg_lg span3"> <a href="">Current Plan:</br> <?php echo strtoupper($plan);?></br></a> </li>
        <li class="<?php echo $cls;?>"> <a href="#"><span class="label label-success"><?php echo $days."&nbspDays"?></span><?php echo "Status:&nbsp".$status;?> </a> </li>
        <li class="bg_lo"> <a href="">Account Number:</br><?php echo strtoupper("<strong>".$account."</strong>");?></a> </li>
        <li class="bg_ls"> <a href="">Account Balance:</br></br><?php echo strtoupper("<strong>Ksh.".$balance."</strong>");?></a> </li>
		
        <li class="bg_lo span3"> <a href="">Expiry Date:</br></br></i><?php echo ("<strong>".$rexp."</strong>");?></i></a> </li>
        <li class="bg_ls"> <a href="#"> <i class="icon-tint"></i> IP Address</a> </li>
        
        <li class="bg_lg"> <a href="#"> <i class=""></i></br>THROUGHPUT</a> </li>
		<li class="bg_lg"> <a href="#"> <i class=""></i></br>STATISTICS</a> </li>
        

      </ul>
    </div>
<!--End-Action boxes-->    

<!--Chart-box-->    
    <div class="row-fluid">
      <div class="widget-box">
        <div class="widget-title bg_lg"><span class="icon"><i class="icon-signal"></i></span>
          <h5>Analytics</h5>
        </div>
        <div class="widget-content" >
          <div class="row-fluid">
            <div class="span9">
              <div>
	                     
</div>
            </div>
            <div class="span3">
              <ul class="site-stats">
                <li class="bg_lh"><i class="icon-user"></i> <strong></strong> <small>Total Logins</small></li>
                <li class="bg_lh"><i class="icon-plus"></i> <strong></strong> <small>Max hops</small></li>
                <li class="bg_lh"><i class="icon-shopping-cart"></i> <strong></strong> <small>Total Pays</small></li>
                <li class="bg_lh"><i class="icon-tag"></i> <strong></strong> <small>Total Orders</small></li>
                <li class="bg_lh"><i class="icon-repeat"></i> <strong></strong> <small>Pending Orders</small></li>
                <li class="bg_lh"><i class="icon-globe"></i> <strong></strong> <small>Online Orders</small></li>
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
  <div id="footer" class="span12"> &copy;&nbsp<?php echo date('Y')?> Client portal. Brought to you by <a href="http://canyonmicrosystems.co.ke">Canyon Microsystems</a> </div>
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
<?php mysqli_close($con);?>
</body>
</html>
