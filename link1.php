<?php include 'config.php';
$co_sel = $_SESSION['co_sel'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  switch ($_POST['submit']) {
    case 'export':
      header('Content-Description: File Transfer');
      header('Content-Type: application/csv');
      header("Content-Disposition: attachment; filename=" . $co_sel.date('Ymd_His') . "-asset-export.csv");
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

      $fp = fopen('php://output', 'w');
      ob_clean();
      $sql = "SELECT * , SUM(depr_amount) AS cur_dep FROM assets, companies, depr, booking, gl WHERE assets.co_id=companies.co_id AND assets.depr_percent=depr.depr_percent AND booking.asset_no=assets.asset_no AND assets.gl_id=gl.gl_id AND co_name='$co_sel' GROUP BY assets.asset_no;";
      $result = mysqli_query($conn, $sql);
      fprintf($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
      fwrite($fp, "Sorszám;Megnevezés;Osztály;Aktiválás dátuma;Deaktiválás dátuma;ÉCS kulcs;Bruttó;ÉCS;KSZÉ\r\n");
      while ($row = mysqli_fetch_assoc($result)) {
        $data = array($row['asset_ser'], $row['asset_name'],$row['gl_id']."-".$row['gl_name'], $row['cap_date'], $row['decap_date'], $row['depr_percent'], $row['gr_value'], $row['cur_dep'], $row['gr_value'] - $row['cur_dep']);

        fputcsv($fp, $data, ";");
      }

      ob_flush();
      fclose($fp);
      die();

    case 'decap':
      $decap_date = $_POST['decap_date'];
      $asset_id = $_POST['decap_id'];
      $capfordecap = $_POST['cap_date'];
      $decap_value=$_POST['decap_value'];
      $sql = "SELECT MAX(month) AS MAXI FROM booking, assets, companies WHERE assets.asset_no=booking.asset_no AND companies.co_id=assets.co_id AND co_name='$co_sel'";
      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($result);
      $maxi = $row['MAXI'];
      
      if ($capfordecap > $decap_date) {
        $error = "A deaktiválás dátuma nem lehet régebbi, mint az aktiválásé!";
      } else
      if ($maxi >= $decap_date) {
        $error = "A deaktiválás dátuma nem lehet régebbi az utolsó lezárt időszaknál!";
      } else {
        $monthend=date('Y-m-t', strtotime($decap_date));
        $sql = "UPDATE assets SET decap_date='$decap_date' WHERE asset_no='$asset_id';";
        $result = mysqli_query($conn, $sql);
        $sql2="INSERT INTO booking (asset_no, month, depr_amount) VALUES ('$asset_id', '$monthend', '$decap_value');";
        $result2 = mysqli_query($conn, $sql2);
      }
  }
}
?>

<h1><?php echo "A(z) " . $_SESSION['co_sel'] . " eszközei:"; ?></h1>

<input type="text" id="searchid" onkeyup="searchbyid()" placeholder="Keresés: sorszám">
<input type="text" id="searchname" onkeyup="searchbyname()" placeholder="Keresés: megnevezés">
<br><br>
<table class="table table-hover" id="assets">
  <thead>
    <tr>
      <th scope="col">Sorszám</th>
      <th scope="col">Megnevezés</th>
      <th scope="col">Osztály</th>
      <th scope="col">Akt. dátuma</th>
      <th scope="col">Deakt. dátuma</th>
      <th scope="col">ÉCS kulcs</th>
      <th scope="col">Bruttó</th>
      <th scope="col">ÉCS</th>
      <th scope="col">KSZÉ</th>
      <?php if ($_SESSION['role'] == '1') {
        echo '<th scope="col">Deaktiválás</th>';
      } ?>
    </tr>
  </thead>
  <tbody>
    <?php

    $sql = "SELECT * FROM assets, companies, depr, gl WHERE assets.co_id=companies.co_id AND assets.depr_percent=depr.depr_percent AND assets.gl_id=gl.gl_id AND co_name='$co_sel';";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
      echo '<tr>
                <td>' . $row['asset_ser'] . '</td>
                <td>' . $row['asset_name'] . '</td>
                <td>' . $row['gl_id'] . '</td>
                <td>' . $row['cap_date'] . '</td>
                <td>' . $row['decap_date'] . '</td>
                <td>' . $row['depr_percent'] . ' %</td>
                <td>' . number_format($row['gr_value'],0,","," ") . '</td>
                <td>';
      $asset_no = $row['asset_no'];
      $sql2 = "SELECT SUM(depr_amount) AS cur_dep FROM booking WHERE asset_no=$asset_no;";
      $row2 = mysqli_fetch_assoc(mysqli_query($conn, $sql2));
      echo number_format($row2['cur_dep'],0,","," ") . '</td>
                <td>' . number_format(($row['gr_value'] - $row2['cur_dep']),0,","," "). '</td>
                <td>';
      if ($row['decap_date'] == "" and  $_SESSION['role'] == '1') {
        echo '<form method="post"><button type="button"  class="btn btn-danger" data-toggle="modal" data-target="#Modal1">
                  Deaktiválás </button>
                  <div class="modal fade" id="Modal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Deaktiválás dátuma</h5>
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      Kérem adja meg a deaktiválás dátumát!
      <input class="form-control" type="date"  name="decap_date">
      <input type="hidden" name="decap_id" value="' . $row['asset_no'] . '">
      <input type="hidden" name="cap_date" value="' . $row['cap_date'] . '">
      <input type="hidden" name="decap_value" value="' . ($row['gr_value'] - $row2['cur_dep']) . '">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Mégsem</button>
        <button type="submit" name="submit"  value="decap" class="btn btn-primary">Deaktiválás</button>
      </div>
    </div>
  </div>
</div></form></td>';
      }
      echo '</tr>';
    }
    ?>
  </tbody>
</table>
<form method="post">
  <button type="submit" name="submit" value="export" class="btn btn-primary">Exportálás</button>

</form>
<br>
<?php

if (isset($error)) {
  echo '<br><div class="alert alert-danger" role="alert">' . $error . '</div>';
}
?>

<script>
  function searchbyname() {

    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchname");
    filter = input.value.toUpperCase();
    table = document.getElementById("assets");
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[1];
      if (td) {
        txtValue = td.textContent || td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
        } else {
          tr[i].style.display = "none";
        }
      }
    }
  }
  function searchbyid() {

var input, filter, table, tr, td, i, txtValue;
input = document.getElementById("searchid");
filter = input.value.toUpperCase();
table = document.getElementById("assets");
tr = table.getElementsByTagName("tr");

for (i = 0; i < tr.length; i++) {
  td = tr[i].getElementsByTagName("td")[0];
  if (td) {
    txtValue = td.textContent || td.innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      tr[i].style.display = "";
    } else {
      tr[i].style.display = "none";
    }
  }
}
}
</script>