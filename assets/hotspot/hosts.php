<?php
/*
 *  Copyright (C) 2018 Laksamadi Guko.
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
session_start();
// hide all error
error_reporting(0);
if (!isset($_SESSION["mikhmon"])) {
	header("Location:../dealer.php?id=login");
} else {

	if ($hotspot == "hostp") {
		$gethosts = $API->comm("/ip/hotspot/host/print", array(
			"?bypassed" => "yes",
		));
		$TotalReg = count($gethosts);

		$counthosts = $API->comm("/ip/hotspot/host/print", array(
			"?bypassed" => "yes",
			"count-only" => "",
		));

	} elseif ($hotspot == "hosta") {
		$gethosts = $API->comm("/ip/hotspot/host/print", array(
			"?authorized" => "yes",
		));
		$TotalReg = count($gethosts);

		$counthosts = $API->comm("/ip/hotspot/host/print", array(
			"?authorized" => "yes",
			"count-only" => "",
		));

	} else {
		$gethosts = $API->comm("/ip/hotspot/host/print");
		$TotalReg = count($gethosts);

		$counthosts = $API->comm("/ip/hotspot/host/print", array(
			"count-only" => "",
		));
	}
}
?>
<meta http-equiv="refresh" content="59" >
<div class="row">
<div class="col-12">
<div class="card">
<div class="card-header align-middle">
	<h3><i class="fa fa-laptop"></i> Device  
		<?php
	if ($counthosts < 2) {
		echo "$counthosts Aktif";
	} elseif ($counthosts > 1) {
		echo "$counthosts Aktif ";
	};
	?>&nbsp;
		 | &nbsp;&nbsp;<i onclick="location.reload();" class="fa fa-refresh pointer " title="Reload data"></i>
    </h3>
</div>
&nbsp;&nbsp;&nbsp;59 detik auto reload otomatis
<!-- /.card-header -->
<div class="card-body">	
  <div class="w-6">
    <input id="filterTable" type="text" class="form-control" placeholder="Pencarian...">
  </div>
<div class="overflow box-bordered mr-t-10" style="max-height: 75vh">  
<th ><i ></i>&nbsp;Klik Text Address untuk mengatur secara urut</th>
	   
<table id="dataTable" class="table table-bordered table-hover text-nowrap"> 
 <thead>
  <tr>

    <th></th>

        <th class="pointer" title="Click to sort"><i class="fa fa-sort"></i> Address</th>
	<th class="pointer" title="Click to sort"><i class="fa fa-sort"></i> Expire/Posisi Router</th>
        <th class="pointer" title="Click to sort"><i class="fa fa-sort"></i> MAC Address</th>
  </thead>
  <tbody>  	
<?php
for ($i = 0; $i < $TotalReg; $i++) {
	$hosts = $gethosts[$i];
	$id = $hosts['.id'];

	$addr = $hosts['address'];
	$commt = $hosts['comment'];
	$maca = $hosts['mac-address'];

	$uriprocess = "'./?remove-host=" . $id . "&session=" . $session . "'";

	echo "<tr>";

	echo "<td style='text-align:center;'>";
	if ($hosts['authorized'] == "true" && $hosts['DHCP'] == "true") {
		echo "<b class='text-success' title='A - authorized, H - DHCP'>A H</b>";
	} elseif ($hosts['authorized'] == "true" && $hosts['dynamic'] == "true") {
		echo "<b class='text-success' title='A - Authorized, D - dynamic'>A D</b>";
	} elseif ($hosts['authorized'] == "true") {
		echo "<b class='text-success' title='A - authorized'>A</b>";
	} elseif ($hosts['DHCP'] == "true") {
		echo "<b class='text-success' title='H - DHCP'>H</b>";
	} elseif ($hosts['dynamic'] == "true") {
		echo "<b class='text-success' title='D - dynamic'>D</b>";
	} elseif ($hosts['bypassed'] == "true") {
		echo "<b class='text-primary' title='P - Bypassed'>P</b>";
	} else {
	}
	echo "</td>";
	echo "<td>" . $addr . "</td>";
	echo "<td>" . $commt . "</td>";
	echo "<td>" . $maca . "</td>";
	echo "</tr>";
}
?>
  </tbody>
</table>
</div>
</div>
</tr>
</div>
</div>