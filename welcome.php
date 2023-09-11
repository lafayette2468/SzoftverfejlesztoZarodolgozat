<?php
include 'config.php';
$user_id = $_SESSION['user_id'];
$email = $_SESSION['login_email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    switch ($_POST['submit']) {
        case 'add':
            $coname = mysqli_real_escape_string($conn, $_POST['coname']);
            $taxno = mysqli_real_escape_string($conn, $_POST['taxno']);
            if ($coname != '' && $taxno != '') {
                $sql = "SELECT * FROM companies WHERE co_taxno ='$taxno'";
                $result = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($result);
                if ($count == 0) {
                    $sql = "INSERT INTO `companies` (co_name, co_taxno) 
                    VALUES ('$coname', '$taxno')";
                    if (mysqli_query($conn, $sql)) {
                        $sql = "SELECT * FROM companies WHERE co_taxno ='$taxno'";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $co_id = $row['co_id'];
                        $sql2 = "INSERT INTO connect (user_id, co_id, role) VALUES ('$user_id', '$co_id','1')";
                        $result2 = mysqli_query($conn, $sql2);
                        $error =  "A vállalat rögzítésre került!";
                    } else {
                        $error = mysqli_error($conn);
                    }
                } else {
                    $error = "Már van ilyen vállalat rögzítve a rendszerünkben. <br>Kíván hozzáférési kérést küldeni a vállalatban szerkesztési jogosultsággal rendelkező felhasználó(k) számára? <br>Ez esetben a kéréssel együtt e-mail címe is továbbításra kerül!";
                    while ($row = mysqli_fetch_assoc($result)) {
                        $error2 = $row['co_id'];
                    }
                }
            } else {
                $error = 'Minden mező kitöltése kötelező!';
            }
            break;
        case 'save':
            $_SESSION['co_sel'] = $_POST['co_select'];
            $co_sel= $_POST['co_select'];
            $sql4 = "SELECT * FROM connect, companies WHERE companies.co_id=connect.co_id AND companies.co_name='$co_sel' AND user_id='$user_id'  ;";
            $row4=mysqli_fetch_assoc(mysqli_query($conn, $sql4));
            $_SESSION['role']=$row4['role'];
            header('Location: welcome');
            break;
        case 'access':
            $co_id = $_POST['co_id'];
            $sql3 = "INSERT INTO connect (user_id, co_id, role) VALUES ('$user_id', '$co_id','0');";
            if (mysqli_query($conn, $sql3)) {
                $error =  "A kérés kiküldésre került.";
            } else {
                $error = mysqli_error($conn) . $co_id;
            }
    }
}


?>


<h1>Üdvözöljük! </h1>

<?php

$sql = "SELECT * FROM companies, connect WHERE companies.co_id=connect.co_id AND companies.co_id IN (SELECT companies.co_id FROM `connect`, companies WHERE connect.co_id=companies.co_id AND connect.user_id='$user_id' AND role=1) AND role=0;";
$result = mysqli_query($conn, $sql);
$count = mysqli_num_rows($result);
if ($count > 0) {
    echo '<div class="alert alert-warning" role="alert">Az ön által kezelt valamely vállalathoz hozzáférési jogosultságot igényeltek. A jogosultságokat a <a href="profile">Profil</a> menüben tudja kezelni.</div>';
}
?>
A folytatáshoz kérem válassza ki a kívánt vállalatot, vagy rögzítsen egy újat.
<br><br>
<!-- Vállalat kiválasztása -->
<form method="post">
    <select class="custom-select" name="co_select">
        <option selected>Vállalat kiválasztása</option>

        <?php
        $email = $_SESSION['login_email'];
        $sql = "SELECT * FROM companies, connect, users  WHERE companies.co_id=connect.co_id AND connect.user_id=users.user_id AND email ='$email' AND connect.role>0";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row['co_name'] . '">' . $row['co_name'] . '</option>';
        }
        ?>
    </select>
    
    <br><br>
    <button type="submit" name="submit" value="save" class="btn btn-primary">Mentés</button>

</form>

<br><br>
<!-- Új vállalat felvitele -->
<form method="post">
    <div class="form-group">
        <label for="companyname">Új vállalat neve</label>
        <input class="form-control" type="text" placeholder="Vállalat neve" id="companyname" name="coname">
    </div>
    <div class="form-group">
        <label for="taxnumber">Adószám első 8 számjegye</label>
        <input class="form-control" type="number" placeholder="12345678" min="10000000" max="99999999" id="taxnumber" name="taxno">
    </div>
    <button type="submit" name="submit" value="add" class="btn btn-primary">Rögzítés</button>

</form>
<!-- Üzenetek kiírása -->
<?php
if (isset($error)) {
    echo '<br><div class="alert alert-primary" role="alert">' . $error . '</div>';
}
if (isset($error2)) {
    echo '<form method="post">
        <a href="welcome" class="btn btn-secondary" role="button">Mégsem</a>
        <input type="hidden" name="co_id" value="' . $error2 . '" />
        <button type="submit" name="submit" value="access" class="btn btn-primary">Igen</button>
        </form>';
}
?>