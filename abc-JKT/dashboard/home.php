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
	header("Location:../cek.php?id=login");
} else {

	$idhr = $_GET['idhr'];
	$idbl = $_GET['idbl'];
	$idbl2 = explode("/",$idhr)[0].explode("/",$idhr)[2];
	if ($idhr != ""){
		$_SESSION['report'] = "&idhr=".$idhr;
	} elseif ($idbl != ""){
		$_SESSION['report'] = "&idbl=".$idbl;
	} else {
		$_SESSION['report'] = "";
	}
	$_SESSION['idbl'] = $idbl;
	$remdata = ($_POST['remdata']);
	$prefix = $_GET['prefix'];
	

	$gettimezone = $API->comm("/system/clock/print");
	$timezone = $gettimezone[0]['time-zone-name'];
	date_default_timezone_set($timezone);

	if (isset($remdata)) {
		if (strlen($idhr) > "0") {
			if ($API->connect($iphost, $userhost, decrypt($passwdhost))) {
				$API->write('/system/script/print', false);
				$API->write('?source=' . $idhr . '', false);
				$API->write('=.proplist=.id');
				$ARREMD = $API->read();
				for ($i = 0; $i < count($ARREMD); $i++) {
					$API->write('/system/script/remove', false);
					$API->write('=.id=' . $ARREMD[$i]['.id']);
					$READ = $API->read();

				}
			}
		} elseif (strlen($idbl) > "0") {
			if ($API->connect($iphost, $userhost, decrypt($passwdhost))) {
				$API->write('/system/script/print', false);
				$API->write('?owner=' . $idbl . '', false);
				$API->write('=.proplist=.id');
				$ARREMD = $API->read();
				for ($i = 0; $i < count($ARREMD); $i++) {
					$API->write('/system/script/remove', false);
					$API->write('=.id=' . $ARREMD[$i]['.id']);
					$READ = $API->read();

				}
			}

		}
		echo "<script>window.location='./?report=selling&session=" . $session . "'</script>";
	}

	if ($prefix != "") {
		$fprefix = "-prefix-[" . $prefix . "]";
	} else {
		$fprefix = "";
	}
	if (strlen($idhr) > "0") {
		if ($API->connect($iphost, $userhost, decrypt($passwdhost))) {
			$getData = $API->comm("/system/script/print", array(
				"?source" => "$idhr",
			));
			$TotalReg = count($getData);
		}
		$filedownload = $idhr;
		$shf = "hidden";
		$shd = "inline-block";
	} elseif (strlen($idbl) > "0") {
		if ($API->connect($iphost, $userhost, decrypt($passwdhost))) {
			$getData = $API->comm("/system/script/print", array(
				"?owner" => "$idbl",
			));
			$TotalReg = count($getData);
		}
		$filedownload = $idbl;
		$shf = "hidden";
		$shd = "inline-block";
	} elseif ($idhr == "" || $idbl == "") {
		if ($API->connect($iphost, $userhost, decrypt($passwdhost))) {
			$getData = $API->comm("/system/script/print", array(
				"?comment" => "mikhmon",
			));
			$TotalReg = count($getData);
		}
		$filedownload = "all";
		$shf = "text";
		$shd = "none";
	} elseif (strlen($idbl) > "0" ) {
		if ($API->connect($iphost, $userhost, decrypt($passwdhost))) {
			$getData = $API->comm("/system/script/print", array(
				"?owner" => "$idbl",
			));
			$TotalReg = count($getData);
		}
		$filedownload = $idbl;
		$shf = "hidden";
		$shd = "inline-block";
	}
	
}
?>
		<script>
			function downloadCSV(csv, filename) {
			  var csvFile;
			  var downloadLink;
			  // CSV file
			  csvFile = new Blob([csv], {type: "text/csv"});
			  // Download link
			  downloadLink = document.createElement("a");
			  // File name
			  downloadLink.download = filename;
			  // Create a link to the file
			  downloadLink.href = window.URL.createObjectURL(csvFile);
			  // Hide download link
			  downloadLink.style.display = "none";
			  // Add the link to DOM
			  document.body.appendChild(downloadLink);
			  // Click download link
			  downloadLink.click();
			  }
			  
			  function exportTableToCSV(filename) {
			    var csv = [];
			    var rows = document.querySelectorAll("#dataTable tr");
			    
			   for (var i = 0; i < rows.length; i++) {
			      var row = [], cols = rows[i].querySelectorAll("td, th");
			   for (var j = 0; j < cols.length; j++)
            row.push(cols[j].innerText);
        csv.push(row.join(","));
        }
        // Download CSV file
        downloadCSV(csv.join("\n"), filename);
        }

// https://stackoverflow.com/questions/33218607/use-inline-css-to-apply-usd-currency-format-within-html-table
function number_format(number, decimals, dec_point, thousands_sep) {

  number = (number + '')
    .replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + (Math.round(n * k) / k)
        .toFixed(prec);
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
    .split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '')
    .length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1)
      .join('0');
  }
  return s.join(dec);
}
        
		window.onload=function() {
          var sum = 0;
          var dataTable = document.getElementById("selling");
          
          // use querySelector to find all second table cells
          var cells = document.querySelectorAll("td + td + td + td + td + td");
          for (var i = 0; i < cells.length; i++)
          sum+=parseFloat(cells[i].firstChild.data);
          
          var th = document.getElementById('total');
    <?php if ($currency == in_array($currency, $cekindo['indo'])) {
      echo 'th.innerHTML = "'.$currency.' " + number_format(th.innerHTML + (sum),"","",".") ;';
		} else {
			echo 'th.innerHTML = "'.$currency.' " + number_format(th.innerHTML + (sum),2,".",",") ;';
		} ?>
		
		var tables = document.getElementsByTagName('tbody');
    var table = tables[tables.length -1];
    var rows = table.rows;
    for(var i = 0, td; i < rows.length; i++){
        td = document.createElement('td');
        td.appendChild(document.createTextNode(i + 1));
        rows[i].insertBefore(td, rows[i].firstChild);
    }
        }
		</script>

<script>
$(document).ready(function(){
  $("#openResume").click(function(){
    notify("Calculating data");
    window.location = "./?report=resume-report&idbl=<?= $idbl;?>&session=<?= $session;?>"
  });
});
</script>
<div class="row">
<div class="col-12">
<div class="card">
<div class="card-header">
	<h3>Klik Cek dibawah ini  <?= ucfirst($idhr) . ucfirst(substr($idbl,0,3).' '.substr($idbl,3,5));	if ($prefix != "") {echo " prefix [" . $prefix . "]";} ?> <small id="loader" style="display: none;" ><i><i class='fa fa-circle-o-notch fa-spin'></i> <?= $_processing ?> </i></small></h3>
</div>
<div class="row">
	<div class="row">
	<div class="col-12">
		<div style="padding-bottom: 5px; padding-top: 5px;">
		  
		  <button class="btn bg-primary" onclick="location.href='./?report=selling&session=<?= $session; ?>';" title="Reload all data"><i class="fa fa-search"></i> cek</button>

		</div>
	</div>
	</div>
</div>
</div>

</div>
</div>
