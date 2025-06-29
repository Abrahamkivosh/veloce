<?php

require('db.php');
include("auth.php"); //include auth.php file on all secure pages 
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Veloce</title>
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
  <style>
    table {
      width: 100%;
    }

    table,
    th,
    td {
      border: 1px solid black;
      border-collapse: collapse;
    }

    th,
    td {
      padding: 15px;
      text-align: left;
    }

    table#t01 tr:nth-child(even) {
      background-color: #eee;
    }

    table#t01 tr:nth-child(odd) {
      background-color: #fff;
    }

    table#t01 th {
      background-color: black;
      color: white;
    }
  </style>
</head>

<body>

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
    if ($eXpiry > $times) {
      $cls = "bg_ly";
      $status = "Active!</br>&#x1F603;";
      $days = round(($eXpiry - $times) / (60 * 60 * 24));
      $reminder = "Account active </br>Connect";
      $href = "http://www.sef.com";
      $mdalcls = "";
      $datamd = "";
      $remcol = "bg_lg";
    } else if ($eXpiry < $times) {
      $cls = "bg_lr";
      $status = "Expired</br></br>&#x1F61E;";
      $reminder = "UNPAID!</br>Pay Now";
      $href = "#";
      $mdalcls = "modal-trigger";
      $datamd = "modal-name";
      $remcol = "bg_lr";
      $days = round(($eXpiry - $times) / (60 * 60 * 24));
    } else {
      $cls = "bg_lr";
      $status = "Expired</br></br>&#x1F61E;";
      $reminder = "NOT PAID!</br>Pay Now";
      $href = "#";
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


  <!--Header-part-->
  <div id="header">
    <h1><a href="dash.php">Veloce</a></h1>
  </div>
  <!--close-Header-part-->


  <!--top-Header-menu-->
  <div id="user-nav" class="navbar navbar-inverse">
    <ul class="nav">
      <li class="dropdown" id="profile-messages"><a title="" href="#" data-toggle="dropdown"
          data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i> <span
            class="text">Welcome <?php echo $_SESSION['username']; ?>!</span><b class="caret"></b></a>
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
    </li>
    <li class=""><a title="" href="#"><i class="icon icon-cog"></i> <span class="text">Settings</span></a></li>-->
      <li class=""><a title="" href="logout.php"><i class="icon icon-share-alt"></i> <span
            class="text">Logout</span></a></li>
    </ul>

  </div>
  <!--close-top-Header-menu-->
  <!--start-top-serch-->
  <div id="search">
    <input type="text" placeholder="Search here..." />
    <button type="submit" class="tip-bottom" title="Search"><i class="icon-search icon-white"></i></button>
  </div>
  <!--close-top-serch-->
  <!--sidebar-menu-->
  <div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-signal"></i> Dashboard</a>
    <ul>
      <li><a href="dash.php"><i class="icon icon-home"></i> <span>Dashboard</span></a> </li>
      <li> <a href="payments.php"><i class="icon icon-signal"></i> <span>My Payments</span></a> </li>
      <li class="active"> <a href="wallet.php"><i class="icon icon-signal"></i> <span>My Wallet</span></a></li>

      </li>

      <li class="content"> <span>Upload </span>
        <div class="progress progress-mini progress-danger active progress-striped">
          <div style="width: <?php echo (($upload / 100000) * 100) ?>%;" class="bar"></div>
        </div>
        <span class="percent">Mbps</span>
        <div class="stat"><?php echo $upload ?></div>
      </li>
      <li class="content"> <span>Download</span>
        <div class="progress progress-mini active progress-striped">
          <div style="width: <?php echo (($upload / 100000) * 100) ?>%;" class="bar"></div>

        </div>
        <span class="percent">Mbps</span>
        <div class="stat"><?php echo $DOWNLOAD ?></div>
      </li>
    </ul>
  </div>
  <!--sidebar-menu-->


  <!--main-container-part-->
  <div id="content">
    <!--breadcrumbs-->
    <div id="content-header">
      <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>
          Home</a></div>
    </div>
    <!--End-breadcrumbs-->
    <?php
    $sess = $_SESSION['username'];

    $sqli = "SELECT * FROM userbillinfo WHERE username = '$sess'";
    $result = mysqli_query($con, $sqli);
    while ($row = mysqli_fetch_array($result)) {
      # code...
      if (($row['balance']) > 0) {
        $plan = ($row['planName']);
        $account = ($row['account']);
        $balance = ($row['balance']);
      } else {

        $account = ($row['account']);
        $balance = ($row['balance']);
        $balance = 0;
      }
    }

    ?>



    <!--Modal-->


    <!--Action boxes-->

    <div class="container-fluid">
      <div class="quick-actions_homepage">


        <div class="row-fluid">
          <div class="span6">
            <div class="widget-box">
              <div class="widget-title bg_lg" style="background: #222;">
                <span class="icon"><i class="icon-money"></i></span>
                <h5>Wallet Overview</h5>
              </div>
              <div class="widget-content" style="font-size: 1.2em;">
                <p><strong>Account Number:</strong> <span
                    style="color: #007bff;"><?php echo htmlspecialchars($account); ?></span></p>
                <p><strong>Current Balance:</strong> <span
                    style="color: #28a745; font-size: 1.5em;">₦<?php echo number_format($balance, 2); ?></span></p>
              </div>
            </div>
          </div>
          <div class="span6">
            <div class="widget-box">
              <div class="widget-title bg_ly" style="background: #f39c12;">
                <span class="icon"><i class="icon-plus"></i></span>
                <h5>Top Up Wallet</h5>
              </div>
              <div class="widget-content">
                <form action="topup.php" method="post" class="form-horizontal" style="margin-bottom:0;">
                  <div class="control-group">
                    <label class="control-label" for="topup-amount">Amount (KES):</label>
                    <div class="controls">
                      <input type="number" min="100" step="100" name="amount" id="topup-amount" class="span8"
                        placeholder="Enter amount" required>
                    </div>
                  </div>


                  <div class="form-group">
                    <button type="submit" class="btn btn-success"><i class="icon-arrow-up"></i> Top Up Now</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!--Chart-box-->

      </div>

      <!--end-main-container-part-->

      <!--Footer-part-->

      <div class="row-fluid">
        <div id="footer" class="span12"> &copy;&nbsp<?php echo date('Y') ?> Client portal. Brought to you by <a
            href="http://lagaster.org">Lagaster Microsystems</a> </div>
      </div>
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
      $(".modal-trigger").click(function(e) {
        e.preventDefault();
        dataModal = $(this).attr("data-modal");
        $("#" + dataModal).css({
          "display": "block"
        });
        // $("body").css({"overflow-y": "hidden"}); //Prevent double scrollbar.
      });

      $(".close-modal, .modal-sandbox").click(function() {
        $(".modal").css({
          "display": "none"
        });
        // $("body").css({"overflow-y": "auto"}); //Prevent double scrollbar.
      });
    </script>
    <script type="text/javascript">
      $(".confirm").click(function(e) {
        e.preventDefault();
        dataModal = $(this).attr("data-modal");
        $("#" + dataModal).css({
          "display": "block"
        });
        // $("body").css({"overflow-y": "hidden"}); //Prevent double scrollbar.
      });

      $(".close-modal, .modal-sandbox").click(function() {
        $(".modal").css({
          "display": "none"
        });
        // $("body").css({"overflow-y": "auto"}); //Prevent double scrollbar.
      });
    </script>
    <script type="text/javascript">
      // This function is called from the pop-up menus to transfer to
      // a different page. Ignore if the value returned is a null string:
      function goPage(newURL) {

        // if url is empty, skip the menu dividers and reset the menu selection to default
        if (newURL != "") {

          // if url is "-", it is this page -- reset the menu:
          if (newURL == "-") {
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