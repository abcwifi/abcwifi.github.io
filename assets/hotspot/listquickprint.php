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

ini_set('max_execution_time', 300);

if (!isset($_SESSION["mikhmon"])) {
	header("Location:../dealer.php?id=login");
} else {
	$qpid = $_GET['qpid'];
	$rem = $_GET['remove'];
	$charup = array(
		"lower" => "abcd",
		"upper" => " ABCD",
		"upplow" => " aBcD",
		"mix" => " 5ab2c34d",
		"mix1" => " 5AB2C34D",
		"mix2" => "5aB2c34D",
	);

	$charvc = array(
		"lower" => " abcd2345",
		"upper" => " ABCD2345",
		"upplow" => " aBcD2345",
		"num" => " 1234",
	);

if(isset($qpid) && isset($rem)){
	$API->comm("/system/script/remove", array(
		".id" => "$qpid",
));
echo '<script>window.location.reload()</script>';
}	

	// get quick print
$getquickprint = $API->comm("/system/script/print", array("?.id" => "$qpid"));
  $quickprintdetails = $getquickprint[0];
  $qpid = $quickprintdetails['.id'];
  $quickprintsource = explode("#",$quickprintdetails['source']);
  $package = $quickprintsource[1];
  $server = $quickprintsource[2];
  $usermode = $quickprintsource[3];
  $userlength = $quickprintsource[4];
  $prefix = $quickprintsource[5];
  $char = $quickprintsource[6];
  $profile = $quickprintsource[7];
  $timelimit = $quickprintsource[8];
  $datalimit = $quickprintsource[9];
	$comment = $quickprintsource[10];
	if($usermode == "up"){
		$tusermode =  $_user_pass;
		$tchar = $charup[$char];
	}elseif($usermode == "vc"){
		$tusermode =  $_user_user;
		$tchar = $charvc[$char];
	}
	if (substr(formatBytes2($datalimit, 2), -2) == "MB") {
		$udatalimit = $datalimit / 1048576;
		$xdatalimit = 1048576;
    $MG = "MB";
  } elseif (substr(formatBytes2($datalimit, 2), -2) == "GB") {
		$udatalimit = $datalimit / 1073741824;
		$xdatalimit = 1073741824;
    $MG = "GB";
  } else{
		$udatalimit = "";
		$xdatalimit = 1048576;
    $MG = "MB";
  }

	// array color
    $color = array('1' => 'bg-blue', 'bg-indigo', 'bg-purple', 'bg-pink', 'bg-red', 'bg-yellow', 'bg-green', 'bg-teal', 'bg-cyan', 'bg-grey', 'bg-light-blue');

    $srvlist = $API->comm("/ip/hotspot/print");
    $getprofile = $API->comm("/ip/hotspot/user/profile/print");
	

	if (isset($_POST['name'])) {
        $name = ($_POST['name']);
        $sname = "0-Quick_Print_".(preg_replace('/\s+/', '-', $_POST['name']));		
                $qty = ($_POST['qty']);
		$server = ($_POST['server']);
		$user = ($_POST['user']);
		$userl = ($_POST['userl']);
		$prefix = ($_POST['prefix']);
		$char = ($_POST['char']);
		$profile = ($_POST['profile']);
		$timelimit = ($_POST['timelimit']);
		$datalimit = ($_POST['datalimit']);
		$adcomment = ($_POST['adcomment']);
		$mbgb = ($_POST['mbgb']);
		if ($timelimit == "") {
			$timelimit = "0";
		} else {
			$timelimit = $timelimit;
		}
		if ($datalimit == "") {
			$datalimit = "0";
		} else {
			$datalimit = $datalimit * $mbgb;
		}
		if ($adcomment == "") {
			$adcomment = "";
		} else {
			$adcomment = $adcomment;
		}
		$getprofile = $API->comm("/ip/hotspot/user/profile/print", array("?name" => "$profile"));
		$ponlogin = $getprofile[0]['on-login'];
		$getvalid = explode(",", $ponlogin)[3];
		$getprice = explode(",", $ponlogin)[2];
		$getsprice = explode(",", $ponlogin)[4];
		$getlock = explode(",", $ponlogin)[6];
		$_SESSION['ubp'] = $profile;
		$commt = $user . "-" . rand(100, 999) . "-" . date("m.d.y") . "-" . $adcomment;
		$gentemp = $commt . "|~" . $profile . "~" . $getvalid . "~" . $getprice . "!".$getsprice."~" . $timelimit . "~" . $datalimit . "~" . $getlock;
		$gen = '<?php $genu="'.encrypt($gentemp).'";?>';
		$temp = './voucher/temp.php';
		$handle = fopen($temp, 'w') or die('Cannot open file:  ' . $temp);
		$data = $gen;
		fwrite($handle, $data);

		$a = array("1" => "", "", 1, 2, 2, 3, 3, 4);

		if ($user == "up") {
			for ($i = 1; $i <= $qty; $i++) {
				if ($char == "lower") {
					$u[$i] = randLC($userl);
				} elseif ($char == "upper") {
					$u[$i] = randUC($userl);
				} elseif ($char == "upplow") {
					$u[$i] = randULC($userl);
				} elseif ($char == "mix") {
					$u[$i] = randNLC($userl);
				} elseif ($char == "mix1") {
					$u[$i] = randNUC($userl);
				} elseif ($char == "mix2") {
					$u[$i] = randNULC($userl);
				}
				if ($userl == 3) {
					$p[$i] = randN(3);
				} elseif ($userl == 4) {
					$p[$i] = randN(4);
				} elseif ($userl == 5) {
					$p[$i] = randN(5);
				} elseif ($userl == 6) {
					$p[$i] = randN(6);
				} elseif ($userl == 7) {
					$p[$i] = randN(7);
				} elseif ($userl == 8) {
					$p[$i] = randN(8);
				}

				$u[$i] = "$prefix$u[$i]";
			}

			for ($i = 1; $i <= $qty; $i++) {
				$API->comm("/ip/hotspot/user/add", array(
					"server" => "$server",
					"name" => "$u[$i]",
					"password" => "$p[$i]",
					"profile" => "$profile",
					"limit-uptime" => "$timelimit",
					"limit-bytes-total" => "$datalimit",
					"comment" => "$commt",
				));
			}
		}

		if ($user == "vc") {
			$shuf = ($userl - $a[$userl]);
			for ($i = 1; $i <= $qty; $i++) {
				if ($char == "lower") {
					$u[$i] = randLC($shuf);
				} elseif ($char == "upper") {
					$u[$i] = randUC($shuf);
				} elseif ($char == "upplow") {
					$u[$i] = randULC($shuf);
				}
				if ($userl == 3) {
					$p[$i] = randN(1);
				} elseif ($userl == 4 || $userl == 5) {
					$p[$i] = randN(2);
				} elseif ($userl == 6 || $userl == 7) {
					$p[$i] = randN(3);
				} elseif ($userl == 8) {
					$p[$i] = randN(4);
				}

				$u[$i] = "$prefix$u[$i]$p[$i]";

				if ($char == "num") {
					if ($userl == 3) {
						$p[$i] = randN(3);
					} elseif ($userl == 4) {
						$p[$i] = randN(4);
					} elseif ($userl == 5) {
						$p[$i] = randN(5);
					} elseif ($userl == 6) {
						$p[$i] = randN(6);
					} elseif ($userl == 7) {
						$p[$i] = randN(7);
					} elseif ($userl == 8) {
						$p[$i] = randN(8);
					}

					$u[$i] = "$prefix$p[$i]";
				}
				if ($char == "mix") {
					$p[$i] = randNLC($userl);


					$u[$i] = "$prefix$p[$i]";
				}
				if ($char == "mix1") {
					$p[$i] = randNUC($userl);


					$u[$i] = "$prefix$p[$i]";
				}
				if ($char == "mix2") {
					$p[$i] = randNULC($userl);


					$u[$i] = "$prefix$p[$i]";
				}

			}
			for ($i = 1; $i <= $qty; $i++) {
				$API->comm("/ip/hotspot/user/add", array(
					"server" => "$server",
					"name" => "$u[$i]",
					"password" => "$u[$i]",
					"profile" => "$profile",
					"limit-uptime" => "$timelimit",
					"limit-bytes-total" => "$datalimit",
					"comment" => "$commt",
				));
			}
		}


		if ($qty < 2) {
			echo "<script>window.location='./?hotspot-user=" . $u[1] . "&session=" . $session . "'</script>";
		} else {
			echo "<script>window.location='./?hotspot-user=generate&session=" . $session . "'</script>";
		}
	}

	$getprofile = $API->comm("/ip/hotspot/user/profile/print");
	include_once('./voucher/temp.php');
	$genuser = explode("-", decrypt($genu));
	$genuser1 = explode("~", decrypt($genu));
	$umode = $genuser[0];
	$ucode = $genuser[1];
	$udate = $genuser[2];
	$uprofile = $genuser1[1];
	$uvalid = $genuser1[2];
	$ucommt = $genuser[3];
	if ($uvalid == "") {
		$uvalid = "-";
	} else {
		$uvalid = $uvalid;
	}
	$uprice = explode("!",$genuser1[3])[0];
	if ($uprice == "0") {
		$uprice = "-";
	} else {
		$uprice = $uprice;
	}
	$suprice = explode("!",$genuser1[3])[1];
	if ($suprice == "0") {
		$suprice = "-";
	} else {
		$suprice = $suprice;
	}
	$utlimit = $genuser1[4];
	if ($utlimit == "0") {
		$utlimit = "-";
	} else {
		$utlimit = $utlimit;
	}
	$udlimit = $genuser1[5];
	if ($udlimit == "0") {
		$udlimit = "-";
	} else {
		$udlimit = formatBytes($udlimit, 2);
	}
	$ulock = $genuser1[6];
	//$urlprint = "$umode-$ucode-$udate-$ucommt";
	$urlprint = explode("|", decrypt($genu))[0];
	if ($currency == in_array($currency, $cekindo['indo'])) {
		$uprice = $currency . " " . number_format((float)$uprice, 0, ",", ".");
		$suprice = $currency . " " . number_format((float)$suprice, 0, ",", ".");
	} else {
		$uprice = $currency . " " . number_format((float)$uprice);
		$suprice = $currency . " " . number_format((float)$suprice);

	}

}
?>
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

ini_set('max_execution_time', 300);

if (!isset($_SESSION["mikhmon"])) {
	header("Location:../dealer.php?id=login");
} else {
	$qpid = $_GET['qpid'];
	$rem = $_GET['remove'];
	$charup = array(
		"lower" => "abcd",
		"upper" => " ABCD",
		"upplow" => " aBcD",
		"mix" => " 5ab2c34d",
		"mix1" => " 5AB2C34D",
		"mix2" => "5aB2c34D",
	);

	$charvc = array(
		"lower" => " abcd2345",
		"upper" => " ABCD2345",
		"upplow" => " aBcD2345",
		"num" => " 1234",
	);

if(isset($qpid) && isset($rem)){
	$API->comm("/system/script/remove", array(
		".id" => "$qpid",
));
echo '<script>window.location.reload()</script>';
}	

	// get quick print
$getquickprint = $API->comm("/system/script/print", array("?.id" => "$qpid"));
  $quickprintdetails = $getquickprint[0];
  $qpid = $quickprintdetails['.id'];
  $quickprintsource = explode("#",$quickprintdetails['source']);
  $package = $quickprintsource[1];
  $server = $quickprintsource[2];
  $usermode = $quickprintsource[3];
  $userlength = $quickprintsource[4];
  $prefix = $quickprintsource[5];
  $char = $quickprintsource[6];
  $profile = $quickprintsource[7];

	// array color
    $color = array('1' => 'bg-blue', 'bg-indigo', 'bg-purple', 'bg-pink', 'bg-red', 'bg-yellow', 'bg-green', 'bg-teal', 'bg-cyan', 'bg-grey', 'bg-light-blue');

    $srvlist = $API->comm("/ip/hotspot/print");
    $getprofile = $API->comm("/ip/hotspot/user/profile/print");
	if (isset($_POST['name'])) {
        $name = ($_POST['name']);
        $sname = "0-Quick_Print_".(preg_replace('/\s+/', '-', $_POST['name']));
		$server = ($_POST['server']);
		$user = ($_POST['user']);
		$userl = ($_POST['userl']);
		$prefix = ($_POST['prefix']);
		$char = ($_POST['char']);
		$profile = ($_POST['profile']);

        $source = '#'.$name.'#'.$server.'#'.$user.'#'.$userl.'#'.$prefix.'#'.$char.'#'.$profile.'#'.$timelimit.'#'.$datalimit.'#'.$adcomment.'#'.$getvalid.'#'.$getprice.'_'.$getsprice.'#'.$getlock;

		if (isset($qpid)){
			$API->comm("/system/script/set", array(
				".id" => "$qpid",
				"name" => "$sname",
				"source" => "$source",
				"comment" => "QuickPrintMikhmon",
		));
		}else{
        $API->comm("/system/script/add", array(
            "name" => "$sname",
            "source" => "$source",
            "comment" => "QuickPrintMikhmon",
				));
			}

		echo "<script>window.location='./?hotspot=list-quick-print&session=" . $session . "'</script>";
		
	}
}
?>
<div class="row">
<div class="col-4">
<div class="card box-bordered">
	<div class="card-header">
	<h3><i class="fa fa-print"></i> Cetak Otomatis <small id="loader" style="display: none;" ><i><i class='fa fa-circle-o-notch fa-spin'></i> <?= $_processing ?> </i></small></h3> 
	</div>
	<div class="row">
<form autocomplete="off" method="post" action="">
	<div>
<?php if(isset($qpid)){echo "
		<a class='btn bg-warning' href='./?hotspot=list-quick-print&session=".$session."'> <i class='fa fa-close'></i> ".$_cancel."</a>";
}else{
	echo "<a class='btn bg-warning' href='./?hotspot=list-quick-print&session=".$session."'> <i class='fa fa-close'></i> ".$_close."</a>";
} ?>
    <button type="submit" name="save" onclick="loader()" class="btn bg-primary" title="Generate User"> <i class="fa fa-save"></i> Transfer</button>
    <a class="btn bg-danger" title="Print QR" href="./voucher/print.php?id=<?= $urlprint; ?>&qr=yes&session=<?= $session; ?>" target="_blank"> <i class="fa fa-qrcode"></i> QR</a>
    <a class="btn bg-secondary" title="Print Default" href="./voucher/print.php?id=<?= $urlprint; ?>&qr=no&session=<?= $session; ?>" target="_blank"> <i class="fa fa-print"></i> Tanpa QR</a>
    <a class="btn bg-info" title="Print Small" href="./voucher/print.php?id=<?= $urlprint; ?>&small=yes&session=<?= $session; ?>" target="_blank"> <i class="fa fa-print"></i> Minimal</a>
</div><table class="table">
</div>
<table class="table">
  <tr>
    <td class="align-middle">Jumlah</td><td><div><input class="form-control " type="number" name="qty" min="1" max="500" value="0" required="1"></div></td>
  </tr>
  <tr>
    <td class="align-middle">Agen</td><td><div><input class="form-control " type="text" name="name" value="<?= $package ?>" required="0"></div></td>
  </tr>
  <tr>
    <td class="align-middle">Digits</td><td>
      <select class="form-control" id="userl" name="userl" required="1">
			<?php if(isset($qpid)){echo '<option>'.$userlength.'</option>';}?>
        <option>8</option>
				<option>3</option>
				<option>4</option>
				<option>5</option>
				<option>6</option>
				<option>7</option>
				<option>8</option>
			</select>
    </td>
  </tr>
  <tr>
    <td class="align-middle"><?= $_prefix ?></td><td><input class="form-control " type="text" size="4" maxlength="4" autocomplete="off" name="prefix" value="<?= $prefix ?>"></td>
  </tr>
  <tr>
    <td class="align-middle">Paket</td><td>
			<select class="form-control " onchange="GetVP();" id="uprof" name="profile" required="1">
				<?php if (isset($qpid)) {
				echo "<option>" . $profile . "</option>";
			} else {
			}
			$TotalReg = count($getprofile);
			for ($i = 0; $i < $TotalReg; $i++) {
				echo "<option>" . $getprofile[$i]['name'] . "</option>";
			}
			?>
			</select>
		</td>
	</tr>
	<tr>
    <td hidden="true" class="align-middle"><?= $_user_mode ?></td><td>
			<select hidden="true" class="" onchange="defUserl();" id="user" name="user" required="1">
				<?php if(isset($qpid)){echo '<option value="'.$usermode.'">'.$tusermode.'</option>';}?>
				<option value="vc"><?= $_user_user ?></option>
				<option value="up"><?= $_user_pass ?></option>
			</select>
		</td>
	</tr>

  <tr>
    <td hidden="true" class="align-middle"><?= $_character ?></td><td>
      <select hidden="true" class="" name="char" required="1">
			<?php if(isset($qpid)){echo '<option value="'.$char.'">'.$_random.' '.$tchar.'</option>';}?>
				<option id="num" style="display:none;" value="num"><?= $_random ?> 1234</option>
				<option id="lower" style="display:block;" value="lower"><?= $_random ?> abcd</option>
				<option id="upper" style="display:block;" value="upper"><?= $_random ?> ABCD</option>
				<option id="upplow" style="display:block;" value="upplow"><?= $_random ?> aBcD</option>
				<option id="lower1" style="display:none;" value="lower"><?= $_random ?> abcd2345</option>
				<option id="upper1" style="display:none;" value="upper"><?= $_random ?> ABCD2345</option>
				<option id="upplow1" style="display:none;" value="upplow"><?= $_random ?> aBcD2345</option>
				<option id="mix" style="display:block;" value="mix"><?= $_random ?> 5ab2c34d</option>
				<option id="mix1" style="display:block;" value="mix1"><?= $_random ?> 5AB2C34D</option>
				<option id="mix2" style="display:block;" value="mix2"><?= $_random ?> 5aB2c34D</option>
			</select>
    </td>
  </tr>
  <tr>
    <td hidden="true" class="align-middle">Server</td>
    <td>
		<select hidden="true" class="" name="server" required="1">
			<?php if(isset($qid)){echo '<option>'. $server .'</option>';}else{echo '<option>all</option>';} ?>
				<?php $TotalReg = count($srvlist);
			for ($i = 0; $i < $TotalReg; $i++) {
				echo "<option>" . $srvlist[$i]['name'] . "</option>";
			}
			?>
		</select>
	</td>
	</tr>

    <td  colspan="4" class="align-middle w-12"  id="GetValidPrice">
    	<?php if ($genprof != "") {
					echo $ValidPrice;
				} ?>
    </td>
  </tr>
</table>
</form>
</div>
</div>
</div>
<div class="col-8">
	<div class="card">
		<div class="card-header">
			<h3><i class="fa fa-ticket"></i> Paket Otomatis</h3>
  <tr> 
  <td>Terakhir</td>
  </tr>
  <tr>
  	<td>Kode di Transfer</td><td>&nbsp;&nbsp;:&nbsp;<?= $ucode ?>&nbsp;-</td>
  </tr>
  <tr>
  	<td>Tanggal</td><td>&nbsp;&nbsp;:&nbsp;<?= $udate ?>&nbsp;-</td>
  </tr>
  <tr>
  	<td>Paket</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;<?= $uprofile ?></td>
  </tr>

		</div>
		<div class="card-body">
            <div class="row">
                <div class="overflow box-bordered">
                <table class="table table-bordered table-hover text-nowrap">
                    <tr>
                    <th>Agen</th>
                    <th hidden="true">Server</th>
                    <th hidden="true">Mode</th>
                    <th>Digits</th>
                    <th><?= $_prefix ?></th>
                    <th>Paket</th>
                    </tr>
<?php
// get quick print
$getquickprint = $API->comm("/system/script/print", array("?comment" => "QuickPrintMikhmon"));
$TotalReg = count($getquickprint);
for ($i = 0; $i < $TotalReg; $i++) {
  $quickprintdetails = $getquickprint[$i];
  $qpid = $quickprintdetails['.id'];
  $quickprintsource = explode("#",$quickprintdetails['source']);
  $package = $quickprintsource[1];
  $server = $quickprintsource[2];
  $usermode = $quickprintsource[3];
  $userlength = $quickprintsource[4];
  $prefix = $quickprintsource[5];
  $char = $quickprintsource[6];
  $profile = $quickprintsource[7];
?>
<tr><td><a title="Edit <?= $_package.' '. $package; ?>" href="./?hotspot=list-quick-print&qpid=<?= $qpid; ?>&session=<?= $session; ?>"><i class="fa fa-edit"></i> <?= $package; ?></a></td>
<td hidden="true"><?= $server ?></td>
<td hidden="true"><?= $usermode ?></td>
<td><?= $userlength ?></td>
<td><?= $prefix ?></td>
<td><?= $profile ?></td>
<td><i class='fa fa-minus-square text-danger pointer' onclick="if(confirm('Are you sure to delete (<?= $package; ?>)?')){loadpage('./?hotspot=list-quick-print&remove&qpid=<?= $qpid; ?>&session=<?= $session; ?>')}else{}" title='Remove <?= $package; ?>'></i>&nbsp</td>	
              </tr>
        <?php 
      }
    ?>
    </table>
    </div>
    </div>
<script>
// get valid $ price
function GetVP(){
  var prof = document.getElementById('uprof').value;
  var url = "./process/getvalidprice.php?name=";
  var session = "&session=<?= $session; ?>"
  var getvalidprice = url+prof+session
  $("#GetValidPrice").load(getvalidprice);
}
</script>

