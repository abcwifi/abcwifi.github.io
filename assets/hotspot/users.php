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

// hide all error
error_reporting(0);
ini_set('max_execution_time', 300);

if (!isset($_SESSION["mikhmon"])) {
  header("Location:../seller.php?id=login");
} else {

  if ($prof == "all") {
    $getuser = $API->comm("/ip/hotspot/user/print");
    $TotalReg = count($getuser);

    $counttuser = $API->comm("/ip/hotspot/user/print", array(
      "count-only" => ""
    ));

  } elseif ($prof != "all") {
    $getuser = $API->comm("/ip/hotspot/user/print", array(
      "?profile" => "$prof",
    ));
    $TotalReg = count($getuser);

    $counttuser = $API->comm("/ip/hotspot/user/print", array(
      "count-only" => "",
      "?profile" => "$prof",
    ));

  }
  if ($comm != "") {
    $getuser = $API->comm("/ip/hotspot/user/print", array(
      "?comment" => "$comm",
    //"?uptime" => "00:00:00"
    ));
    $TotalReg = count($getuser);

    $counttuser = $API->comm("/ip/hotspot/user/print", array(
      "count-only" => "",
      "?comment" => "$comm",
    ));
    
  }
  $exp = $_GET['exp'];
  if ($exp != "") {
    $getuser = $API->comm("/ip/hotspot/user/print", array(
      "?limit-uptime" => "1s",
    ));
    
    $counttuser = $API->comm("/ip/hotspot/user/print", array(
      "count-only" => "",
      "?limit-uptime" => "1s",
    ));
    
  }
  $getprofile = $API->comm("/ip/hotspot/user/profile/print");
  $TotalReg2 = count($getprofile);
}
?>
<meta http-equiv="refresh" content="59" >
<div class="row">
<div class="col-12">
<div class="card">
<div class="card-header">
    
</div>
<div class="card-body">

  <div class="row">
   <div class="card-header">
  <div class="input-group">    
<div class="">
      <select style="padding:0px;" class="card-header" onchange="location = this.value; loader()">
  </div>

  <div class="">
    <select style="padding:0px;" class="card-header" id="comment" name="comment" onchange="location = './?hotspot=users&comment='+ this.value +'&session=<?= $session;?>';">
    <?php
    if ($comm != "") {
    } else {
      echo "<option>Data Voucher </option>";
    }
    $TotalReg = count($getuser);
    for ($i = 0; $i < $TotalReg; $i++) {
      $ucomment = $getuser[$i]['comment'];
      $uprofile = $getuser[$i]['profile'];
      $acomment .= ",".$ucomment."#". $uprofile;
    }

    $ocomment=  explode(",",$acomment);
    
    $comments=array_count_values($ocomment) ;
    foreach ($comments as $tcomment=>$value) {

      if (is_numeric(substr($tcomment, 3, 3))) {
       
        echo "<option value='" . explode("#",$tcomment)[0] . "' >". explode("#",$tcomment)[0]." ".explode("#",$tcomment)[1]. " [".$value. "]</option>";
       }
 
    }

    ?> <a class="btn bg-primary" href="./?hotspot=list-quick-print&session=<?= $session; ?>" class="menu <?= $teditor; ?>"> <i class="fa fa-edit "></i> Tansfer </a> | &nbsp;<i onclick="location.reload();" class="fa fa-refresh pointer " title="Reload data"></i>&nbsp;&nbsp;59 detik auto reload
    </select>
 
 

  </div>
  </div>
  </div>
 
  <div class="col-6">
  <script>
    function printV(a,b){
    var comm = document.getElementById('comment').value;
    var url = "./voucher/print.php?id="+comm+"&"+a+"="+b+"&session=<?= $session; ?>";
    if (comm === "" ){
      <?php if ($currency == in_array($currency, $cekindo['indo'])) { ?>
      alert('Silakan pilih salah satu Comment terlebih dulu!');
      <?php
    } else { ?>
      alert('Please choose one of the Comments first!');
      <?php
    } ?>
    }else{
      var win = window.open(url, '_blank');
      win.focus();
    }}
  </script>
  </div>
</div>
<div class="overflow mr-t-10 box-bordered" style="max-height: 75vh">
<table id="dataTable" class="table table-bordered table-hover text-nowrap">
  <thead>
  <tr>
    <th>STATUS</th>
    <th>NOTA-QR</th>
    <th></i> Expired / Status</th>
    </tr>
  </thead>

  <tbody id="tbody">

<?php
for ($i = 0; $i < $TotalReg; $i++) {
  $userdetails = $getuser[$i];
  $uid = $userdetails['.id'];
  $userver = $userdetails['server'];
  $uname = $userdetails['name'];
  $upass = $userdetails['password'];
  $uprofile = $userdetails['profile'];
  $umacadd = $userdetails['mac-address'];
  $uuptime = formatDTM($userdetails['uptime']);
  $ubytesi = formatBytes($userdetails['bytes-in'], 2);
  $ubyteso = formatBytes($userdetails['bytes-out'], 2);

  $ucomment = $userdetails['comment'];
  $udisabled = $userdetails['disabled'];
  $utimelimit = $userdetails['limit-uptime'];
  if ($utimelimit == '1s') {
    $utimelimit = ' expired';
  } else {
    $utimelimit = ' ' . $utimelimit;
  }
  $udatalimit = $userdetails['limit-bytes-total'];
  if ($udatalimit == '') {
    $udatalimit = '';
  } else {
    $udatalimit = ' ' . formatBytes($udatalimit, 2);
  }

  echo "<tr>";
  ?>
  

  <?php
  if ($udisabled == "true") {} else {}
  echo "<td>" . $userver . "</td>";

  if ($uname == $upass) {
    $usermode = "vc";
  } else {
    $usermode = "up";
  }
  echo "<td><a title='" . $uid . "' href=./?hotspot-user=" . $uid . "&session=" . $session . "><i class='fa fa-edit'></i> " . $uid . " </a>";
  
  
  echo "<td>";
  if ($uname == "default-trial") {
  } else if (substr($ucomment,0,3) == "vc-" || substr($ucomment,0,3) == "up-") {
    echo "<href=./?hotspot=users&comment=" . $ucomment . "&session=" . $session . " title=''><i class='fa fa-search'></i> ". $ucomment." ". $udatalimit ." ".$utimelimit . "";
  } else if ($utimelimit == ' expired') {
    echo "<a href=./?hotspot=users&profile=all&exp=1&session=" . $session . " title='Filter by expired'><i class='fa fa-search'></i> " . $ucomment." ". $udatalimit ." ".$utimelimit . "</a>";
  }else{
    echo $ucomment.' ';
  }
  echo  "</td>";


}
?>
  </tr>

  </tbody>

</table>
</div>
</div>
</div>
</div>
</div>


	
	
