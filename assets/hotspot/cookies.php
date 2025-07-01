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

	if ($tool == "hostp") {
		$gethosts = $API->comm("/tool/netwatch/print", array(
			"?bypassed" => "yes",
		));
		$TotalReg = count($gethosts);

		$counthosts = $API->comm("/tool/netwatch/print", array(
			"?bypassed" => "yes",
			"count-only" => "",
		));

	} elseif ($hotspot == "hosta") {
		$gethosts = $API->comm("/tool/netwatch/print", array(
			"?authorized" => "yes",
		));
		$TotalReg = count($gethosts);

		$counthosts = $API->comm("/tool/netwatch/print", array(
			"?authorized" => "yes",
			"count-only" => "",
		));

	} else {
		$gethosts = $API->comm("/tool/netwatch/print");
		$TotalReg = count($gethosts);

		$counthosts = $API->comm("/tool/netwatch/print", array(
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
	<h3><i class=" fa fa-wifi"></i> Total ROUTER :  
		<?php
	if ($counthosts < 2) {
		echo "$counthosts Unit";
	} elseif ($counthosts > 1) {
		echo "$counthosts Unit ";
	};
	?>&nbsp;
		 
		 | &nbsp;&nbsp;<i onclick="location.reload();" class="fa fa-refresh pointer " title="Reload data"></i> 
    </h3>
</div>
&nbsp;&nbsp;&nbsp;59 detik auto reload otomatis
<!-- /.card-header -->
<div class="card-body">	
  <div class="w-6">

    <input id="filterTable" type="text" class="form-control" placeholder="Pencarian..">
  </div>
<div class="overflow box-bordered mr-t-10" style="max-height: 75vh">  
<th ><i ></i>&nbsp;Klik Text Router untuk mengatur secara urut</th>
 	   
<table id="dataTable" class="table table-bordered table-hover text-nowrap">
  <thead>
  <tr>
    <th class="pointer" title="Click to sort"><i class="fa fa-sort"></i> Status</th>
    <th class="pointer" title="Click to sort"><i class="fa fa-sort"></i> Posisi/Tempat</th>
    <th class="pointer" title="Click to sort"><i class="fa fa-sort"></i> Router</th>
    <th class="pointer" title="Click to sort"><i class="fa fa-sort"></i> Waktu Up/Down</th>


	
  </thead>
  <tbody>  	
<?php
for ($i = 0; $i < $TotalReg; $i++) {
	$hosts = $gethosts[$i];
	$id = $hosts['.id'];

	$status = $hosts['status'];
        $commt = $hosts['comment'];
	$host = $hosts['host'];
	$since = $hosts['since'];
	

	$uriprocess = "'./?remove-host=" . $id . "&session=" . $session . "'";

	echo "<tr>";

        echo "<td>" . $status . "</td>";
	echo "<td>" . $commt . "</td>";
	echo "<td>" . $host . "</td>";
	echo "<td>" . $since . "</td>";
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