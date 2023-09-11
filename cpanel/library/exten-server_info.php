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
 * Description:
 * 		this script process some important server information and displays it
 *
 * Authors:	Liran Tal <liran@enginx.com>
 *		Carlos Cesario <carloscesario@gmail.com>
 *
 *********************************************************************************************************
 */
	include_once('include/management/pages_common.php');

// Display uptime system
// @return string Return uptime system
function uptime() {
	$file_name = "/proc/uptime";

	$fopen_file = fopen($file_name, 'r');
	$buffer = explode(' ', fgets($fopen_file, 4096));
	fclose($fopen_file);

	$sys_ticks = trim($buffer[0]);
	$min = $sys_ticks / 60;
	$hours = $min / 60;
	$days = floor($hours / 24);
	$hours = floor($hours - ($days * 24));
	$min = floor($min - ($days * 60 * 24) - ($hours * 60));
	$result = "";

	if ($days != 0) {
		if ($days > 1)
			$result = "$days " . " days ";
		else
			$result = "$days " . " day ";
	}

	if ($hours != 0) {
		if ($hours > 1)
			$result .= "$hours " . " hours ";
		else
			$result .= "$hours " . " hour ";
	}

	if ($min > 1 || $min == 0)
		$result .= "$min " . " minutes ";
	elseif ($min == 1)
		$result .= "$min " . " minute ";

	return $result;
}


// Display hostname system
// @return string System hostname or none
function get_hostname() {
	$file_name = "/proc/sys/kernel/hostname";

	if ($fopen_file = fopen($file_name, 'r')) {
		$result = trim(fgets($fopen_file, 4096));
		fclose($fopen_file);
	} else {
		$result = "(none)";
	}

	return $result;
}


// Display currenty date/time
// @return string Current system date/time or none
function get_datetime() {
	if ($today = date("F j, Y, g:i a")) {
		$result = $today;
	} else {
		$result = "(none)";
	}

	return $result;
}



// Get System Load Average
// @return array System Load Average
function get_system_load() {
	$file_name = "/proc/loadavg";
	$result = "";
	$output = "";

	// get the /proc/loadavg information
	if ($fopen_file = fopen($file_name, 'r')) {
		$result = trim(fgets($fopen_file, 256));
		fclose($fopen_file);
	} else {
		$result = "(none)";
	}

	$loadavg = explode(" ", $result);
	$output .= $loadavg[0] . " " . $loadavg[1] . " " . $loadavg[2] . "<br/>";


	// get information the 'top' program
	$file_name = "top -b -n1 | grep \"Tasks:\" -A1";
	$result = "";

	if ($popen_file = popen($file_name, 'r')) {
		$result = trim(fread($popen_file, 2048));
		pclose($popen_file);
	} else {
		$result = "(none)";
	}

	$result = str_replace("\n", "<br/>", $result);
	$output .= $result;

	return $output;
}


// Get Memory System MemTotal|MemFree
// @return array Memory System MemTotal|MemFree
function get_memory() {
    $file_name = "/proc/meminfo";
    $mem_array = array();

    $buffer = file($file_name);

    for ($i = 0; $i < count($buffer); $i++) {
        $value = $buffer[$i];
        if (strpos($value, ':') !== false) {
            $match_line = explode(':', $value);
            $match_value = explode(' ', trim($match_line[1]));
            if (is_numeric($match_value[0])) {
                $mem_array[trim($match_line[0])] = trim($match_value[0]);
            }
        }
    }

    return $mem_array;
}



//Get FreeDiskSpace
function get_hdd_freespace() {
$df = disk_free_space("/");
return $df;
}


// Convert value to MB
// @param decimal $value
// @return int Memory MB
function convert_ToMB($value) {
	return round($value / 1024) . " MB\n";
}



// Get all network names devices (eth[0-9])
// @return array Get list network name interfaces
function get_interface_list() {
	$devices = array();
	// Run the ip link show command and store the output in a variable
	$output = shell_exec('ip link show');

	// Match the pattern and store the results in an array
	preg_match_all('/\d: ([a-z0-9]+)/i', $output, $devices);

	// Return the devices array
	return $devices[1];

}



// Get ip address
// @param string $ifname
// @return string Ip address or (none)
function get_ip_addr($ifname) {
	// Run the ip addr show command for the specified device name and store the output
    $output = shell_exec("ip addr show $ifname");

    // Initialize an array to store the IP addresses
    $ipAddresses = [];
	if ($output == "") {
		return "(none)";
	}

    // Split the output into lines
    $lines = explode("\n", $output);

    // Iterate through each line of the output
    foreach ($lines as $line) {
        // Use a regular expression to extract IPv4 and IPv6 addresses
        if (preg_match('/inet (\d+\.\d+\.\d+\.\d+)/', $line, $matches)) {
            $ipv4Address = $matches[1];
            $ipAddresses['IPv4'] = $ipv4Address;
        } elseif (preg_match('/inet6 ([0-9a-fA-F:]+)/', $line, $matches)) {
            $ipv6Address = $matches[1];
            $ipAddresses['IPv6'] = $ipv6Address;
        }
    }

    // Return the IP addresses as strings
	return implode(' ', $ipAddresses);
}

// Get mac address
// @param string $ifname
// @return string Mac address or (none)
function get_mac_addr($ifname) {
	// Run the ip addr show command for the specified device name and store the output
	$output = shell_exec("ip addr show $ifname");

	// Initialize a variable to store the MAC address
	$macAddress = null;

	if ($output == "") {
		return "(none)";
	}

	// Split the output into lines
	$lines = explode("\n", $output);

	// Iterate through each line of the output
	foreach ($lines as $line) {
		// Use a regular expression to extract the MAC address
		if (preg_match('/link\/ether ([0-9a-fA-F:]+)/', $line, $matches)) {
			$macAddress = $matches[1];
			break; // Stop processing after the first MAC address is found
		}
	}

	return $macAddress;
}


// Get netmask address
// @param string $ifname
// @return string Netmask address or (none)
function get_mask_addr($ifname) {

	// Run the ip addr show command for the specified device name and store the output
    $output = shell_exec("ip addr show $ifname");

    // Initialize a variable to store the IPv4 netmask
    $ipv4Netmask = null;
	if ($output == "") {
		return "(none)";
	}

    // Split the output into lines
    $lines = explode("\n", $output);

    // Iterate through each line of the output
    foreach ($lines as $line) {
        // Use a regular expression to extract the IPv4 address and netmask
        if (preg_match('/inet (\d+\.\d+\.\d+\.\d+)\/(\d+)/', $line, $matches)) {
            $ipv4Netmask = $matches[2];
            break; // Stop processing after the first IPv4 address is found
        }
    }

    return $ipv4Netmask;
}


?>


<?php
	echo "<h3>General Information</h3>";
?>

<table class='summarySection'>
  <tr>
    <td class='summaryKey'> Uptime </td>
    <td class='summaryValue'><span class='sleft'><?php echo uptime(); ?></span> </td>
  </tr>
  <tr>
    <td class='summaryKey'> System Load </td>
    <td class='summaryValue'><span class='sleft'><?php echo get_system_load(); ?></span> </td>
  </tr>
  <tr>
    <td class='summaryKey'> Hostname </td>
    <td class='summaryValue'><span class='sleft'><?php echo get_hostname(); ?></span> </td>
  </tr>
  <tr>
    <td class='summaryKey'> Current Date </td>
    <td class='summaryValue'><span class='sleft'><?php echo get_datetime(); ?></span> </td>
  </tr>
</table>


<?php
	echo "<h3>Memory Information</h3>";
	$meminfo = get_memory();
?>


<table class='summarySection'>
  <tr>
    <td class='summaryKey'> Mem. Total </td>
    <td class='summaryValue'><span class='sleft'><?php echo convert_ToMB ($meminfo['MemTotal']); ?></span> </td>
  </tr>
  <tr>
    <td class='summaryKey'> Mem. Free </td>
    <td class='summaryValue'><span class='sleft'><?php echo convert_ToMB ($meminfo['MemFree']); ?></span> </td>
  </tr>
  <tr>
    <td class='summaryKey'> Mem. Used </td>
    <td class='summaryValue'>
		<span class='sleft'>
			<?php
				$memused = ($meminfo['MemTotal'] - $meminfo['MemFree']);
				echo convert_ToMB ($memused);
			?>
		</span> </td>
  </tr>
</table>


<?php
	echo "<h3>Harddrive Information</h3>";
	$hddfreespace = get_hdd_freespace();
?>


<table class='summarySection'>
  <tr>
    <td class='summaryKey'> Free Drive Space </td>
    <td class='summaryValue'><span class='sleft'><?php echo toxbyte ($hddfreespace); ?></span> </td>
  </tr>

</table>

<?php
	echo "<h3>Network Interfaces</h3>";
	$iflist = get_interface_list();

	foreach ($iflist as $ifname) {
			echo "\t<table class='summarySection'>\n";
			echo "\t<tr>\n";
			echo "\t\t<td class='summaryKey'></td>\n";
			echo "\t\t<td class='stitle'>\n";
			echo "\t\t\t$ifname\n";
			echo "\t\t</td>\n";
			echo "\t</tr>\n";
			echo "\t<tr>\n";
			echo "\t<tr>\n";
			echo "\t\t<td class='summaryKey'>\n";
			echo "\t\t\tIp\n";
			echo "\t\t</td>\n";
			echo "\t\t<td class='summaryValue'>\n";
			echo "\t\t\t".get_ip_addr($ifname)."\n";
			echo "\t\t</td>\n";
			echo "\t</tr>\n";
			echo "\t<tr>\n";
			echo "\t\t<td class='summaryKey'>\n";
			echo "\t\t\tMask\n";
			echo "\t\t</td>\n";
			echo "\t\t<td class='summaryValue'>\n";
			echo "\t\t\t".get_mask_addr($ifname)."\n";
			echo "\t\t</td>\n";
			echo "\t</tr>\n";
			echo "\t<tr>\n";
			echo "\t\t<td class='summaryKey'>\n";
			echo "\t\t\tMAC address\n";
			echo "\t\t<td class='summaryValue'>\n";
			echo "\t\t\t".get_mac_addr($ifname)."\n";
			echo "\t\t</td>\n";
			echo "\t</tr>\n";
		}

	echo "\t</table>\n";
?>
