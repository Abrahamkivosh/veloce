<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

    include ("menu-home.php");

	include_once('library/config_read.php');
    $log = "visited page: ";
    include('include/config/logging.php');

?>
		
		
		
		<div id="contentnorightbar">
		
				<h2 id="Intro"><a href="#"></a></h2>
				<p>

<?php
	include 'library/exten-welcome_page.php';
?>
				</p>
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
