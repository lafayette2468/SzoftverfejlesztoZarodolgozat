<?php include 'config.php';
$csv="";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $co_sel = $_SESSION['co_sel'];
    $month = $_POST['month'];
    
    $monthstart = date('Y-m-01', strtotime($month));
    //Kiszámítjuk, hány nap van az évben.


    if (date('L', strtotime($month))) {
        $ydays = 366;
    } else {
        $ydays = 365;
    }

    $sql = "SELECT * FROM assets, companies WHERE assets.co_id=companies.co_id AND companies.co_name='$co_sel' AND cap_date<='$month' AND (decap_date>='$monthstart' OR ISNULL(decap_date));";
    $result = mysqli_query($conn, $sql);
    
    $csv.="Eszköz száma;" . $month . "\r\n";



    while ($row = mysqli_fetch_assoc($result)) {
        $asset_no = $row['asset_no'];
        $asset_ser=$row['asset_ser'];
        $sql2 = "SELECT SUM(depr_amount) AS DEP FROM booking WHERE asset_no=$asset_no GROUP BY asset_no;";
        $row2 = mysqli_fetch_assoc(mysqli_query($conn, $sql2));
        if (is_null($row2['DEP'])) {
            $dep = 0;
        } else {
            $dep = $row2['DEP'];
        }
        //Kiszámítjuk, hány napnyi értékcsökkenést kell elszámolni:
        //ha már nulla az értéke, nem számolunk el semmit:
        if ($dep >= $row['gr_value']) {
            $days = 0;
        } else {
            //ha a hónap előtt aktiválva, a hónap után deaktiválva, akkor a hónap minden napja
            if ($row['cap_date'] < $monthstart and ($row['decap_date'] > $month or is_null($row['decap_date']))) {
                $days = intval(date('t', strtotime($month)));
            } else
                //ha a hónap közben lett aktiválva és dekativálva is, akkor a két esemény közt eltelt napok száma.
                if ($row['cap_date'] >= $monthstart and $row['decap_date'] <= $month and !is_null($row['decap_date'])) {
                    $days = intval(date('j', strtotime($row['decap_date']))) - intval(date('j', strtotime($row['cap_date']))) + 1;
                } else
                    //ha hónap közben aktiváltuk, de még nincs deaktiválva/később lett deaktiválva, akkor az azóta eltelt napok száma.
                    if ($row['cap_date'] >= $monthstart) {
                        $days = intval(date('t', strtotime($month))) - intval(date('j', strtotime($row['cap_date']))) + 1;
                    }
                    //ide már csak a hónap előtt aktivált, de közben deaktivált eszközök kerülnek, lekérdezéskor kiszűrtük a hónapon kívül eső eszközöket.
                    else {
                        $days = intval(date('j', strtotime($row['decap_date'])));
                    }
        }
        //kalkulál a hónapra eső értékcsökkenést:

        $depr_amount = round($row['gr_value'] / $ydays * $days * ($row['depr_percent'] / 100), 0);
        //ha többet kalkulálunk, mint ami hátravan, akkor a maradékot számol csak el:

        if ($row['gr_value'] - $depr_amount < $dep) {
            $depr_amount = $row['gr_value'] - $depr_amount;
        }

        //SQL-be beír:
        $sql3 = "INSERT INTO `booking` (asset_no, month, depr_amount) 
        VALUES ('$asset_no', '$month', '$depr_amount')";
        if (mysqli_query($conn, $sql3)) {
            $error = "A havi elszámolás sikeresen lefutott.";
        } else {
            $error = mysqli_error($conn);
        }
        //fájl változójába írás:
        $csv .=$asset_ser.";".$depr_amount."\r\n";
        
    }
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=".$co_sel.date('Ymd_His')."depr-export.csv");
    ob_clean();
    $fp = fopen('php://output', 'w');
    fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
    fwrite($fp,$csv);
    ob_flush();
    fclose($fp);
    die();
    
}

        ?>

<h1>Havi zárás</h1>

A kiválasztott cég aktuális hónapjának lezárása előtt kérjük győzödjön meg róla, hogy:
<br><br>
<ul>
    <li> minden, az időszakot érintő eszközt felvitt az eszközkezelőbe, illetve</li>
    <li> az időszakban deaktivált eszközöket rögzítette,</li>
</ul>
ugyanis ezek későbbi módosítására lehetőség <b>NINCS</b>!
<br><br>
Az aktuálisan lezárható hónap:<b>

    <?php
    //ha már történt a cégben zárás, akkor az utolsó zárt hónap utáni hónap, ha még nem, akkor a legkorábban aktivált eszköz hónapja.
    $co_sel = $_SESSION['co_sel'];
    $sql = "SELECT MAX(booking.month) as MAXI FROM booking, assets, companies WHERE assets.co_id=companies.co_id AND assets.asset_no=booking.asset_no AND companies.co_name='$co_sel';";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        if (!is_null($row['MAXI'])) {

            $month = date('Y-m-t', strtotime($row['MAXI'] . '+1 day'));
            echo date('Y/m', strtotime($month)) . '.';
        } else {
            $sql = "SELECT MIN(assets.cap_date) AS MINI FROM assets, companies WHERE assets.co_id=companies.co_id AND co_name='$co_sel';";
            $result2 = mysqli_query($conn, $sql);
            $row2 = mysqli_fetch_assoc($result2);
            if (!is_null($row2['MINI'])) {

                $month = date('Y-m-t', strtotime($row2['MINI']));
                echo date('Y/m', strtotime($month)) . '.';
            } else {
                echo "Még nincs rögzítve eszköz a vállalatban!";
                $error2 = '';
            }
        }
    }

    ?>
</b>


<?php
if (!isset($error2)) {
    echo '<br> <br> A futtatást követően automatikusan letöltésre kerül az eredményről egy fájl.
    <br><br>';
    echo '<form method ="post">
    <input type="hidden" name="month" value="'.$month.'">
    <button type="submit" name="submit" class="btn btn-primary">Zárás futtatása</button>

</form>';
}
?>

<?php
if (isset($error)) {
    echo '<br><div class="alert alert-primary" role="alert">' . $error . '</div>';
}

?>
<br>