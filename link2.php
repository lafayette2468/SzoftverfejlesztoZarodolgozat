<?php include 'config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $co_sel = $_SESSION['co_sel'];
    $asset_name = mysqli_real_escape_string($conn, $_POST['asset_name']);
    $gl_name = mysqli_real_escape_string($conn, $_POST['gl_name']);
    $depr_name = mysqli_real_escape_string($conn, $_POST['depr_name']);
    $gr_value = mysqli_real_escape_string($conn, $_POST['gr_value']);
    $cap_date = mysqli_real_escape_string($conn, $_POST['cap_date']);
    if ($asset_name != '' && $gl_name != '' && $depr_name != '' && $gr_value != '' && $cap_date != '') {
        $sql = "SELECT * FROM companies WHERE co_name='$co_sel'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $co_id = $row['co_id'];

        $sql = "SELECT MAX(month) AS MAXI FROM booking, assets WHERE assets.asset_no=booking.asset_no AND co_id='$co_id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $maxi = $row['MAXI'];
        $sql = "SELECT MAX(asset_ser) AS SERIAL FROM assets WHERE co_id='$co_id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        if(is_null($row['SERIAL'])){
        $serial=1000001;    
        }
        else{
        $serial=$row['SERIAL']+1;}
        if ($cap_date > $maxi) {
            $sql = "INSERT INTO `assets` (asset_ser, asset_name, co_id, gl_id, depr_percent, gr_value, cap_date) 
            VALUES ('$serial', '$asset_name', '$co_id', '$gl_name', '$depr_name', '$gr_value', '$cap_date')";
            if (mysqli_query($conn, $sql)) {
                $error = "Az eszköz rögzítésre került.";
            } else {
                $error2 = mysqli_error($conn);
            }
        } else {
            $error2="Az aktiválás dátuma nem eshet már lezárt időszakra!";
        }
    } else {
        $error2="Minden mező kitöltése kötelező!";
    }
}


?>

<h1>Új eszköz rögzítése</h1>

<form method="post">
    <div class="form-group">
        <label for="asset_name">Eszköz megnevezése</label>
        <input class="form-control" type="text" placeholder="Eszköz név" name="asset_name">
    </div>

    <div class="form-group">
        <label for="gl_name">Főkönyv kiválasztása</label>
        <select class="custom-select" name="gl_name">

            <?php
            $sql = "SELECT * FROM gl";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . $row['gl_id'] . '">' . $row['gl_id'] . ' ' . $row['gl_name'] . '</option>';
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="depr_name">ÉCS kulcs</label>
        <select class="custom-select" name="depr_name">


            <?php
            $sql = "SELECT * FROM depr";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . $row['depr_percent'] . '">' . $row['depr_percent'] . '% - ' . $row['depr_years'] . '</option>';
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="gr_value">Bekerülési érték</label>
        <input class="form-control" type="number" min="1" placeholder="Bekerülési érték" name="gr_value">
    </div>

    <div class="form-group">
        <label for="cap_date">Aktiválás dátuma</label>
        <input class="form-control" type="date" name="cap_date">
    </div>

    <button type="submit" name="submit" class="btn btn-primary">Rögzítés</button>

</form>

<?php
if (isset($error)) {
    echo '<br><div class="alert alert-primary" role="alert">' . $error . '</div>';
}
if (isset($error2)) {
    echo '<br><div class="alert alert-danger" role="alert">' . $error2 . '</div>';
}
?>