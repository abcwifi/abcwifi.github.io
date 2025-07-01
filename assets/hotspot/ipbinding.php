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

	$getbinding = $API->comm("/ip/hotspot/ip-binding/print");
	$TotalReg = count($getbinding);

	$countbinding = $API->comm("/ip/hotspot/ip-binding/print", array(
		"count-only" => "",
	));
}

?>
<meta http-equiv="refresh" content="59" >
<div class="row">
<div class="col-12">
<div class="card">
<div class="card-header align-middle">
	<h3>&nbsp;<i class=" fa fa-address-book"></i> Binding Device 
<?php
if ($countbinding < 2) {
	echo "$countbinding Terdaftar";
} elseif ($countbinding > 1) {
	echo "$countbinding Terdaftar";
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
    <th class="pointer" title="Click to sort"><i class="fa fa-sort"></i> Posisi</th>
    <th class="pointer" title="Click to sort"><i class="fa fa-sort"></i> MAC Address</th>
  </tr>
  </thead>
  <tbody> 
<?php
for ($i = 0; $i < $TotalReg; $i++) {
	$binding = $getbinding[$i];
	$id = $binding['.id'];

	$maca = $binding['mac-address'];
	$addr = $binding['address'];
	$toaddr = $binding['to-address'];
	$server = $binding['server'];
	$commt = $binding['comment'];
	$bdisabled = $binding['disabled'];

	echo "<tr>";
	?>
  	<td style='text-align:center;'>
  	<?php

		if ($bdisabled == "true")
		echo "<td style='text-align:center;'>";
		if ($binding['bypassed'] == "true") {
			echo "<b style='color:#0091EA;'>P</b>";
		} else {
		}
		
		echo "<td>" . $addr . "</a></td>";
		echo "<td>" . $commt . "</td>";
		echo "<td>" . $maca . "</td>";
		echo "</tr>";
	}
	?>
  </tbody>
</table>
</div>
</div>
</div>
</div>
