<?php
include 'config.php';
$email = $_SESSION['login_email'];
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE email ='$email'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$first_name = $row['first_name'];
$last_name = $row['last_name'];
$reg_date = strval($row['reg_date']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    switch ($_POST['submit']) {
        case 'edit':
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    if ($first_name != '' && $last_name != '') {
        $sql = "UPDATE users SET first_name='$first_name' , last_name='$last_name' WHERE email='$email' ";
        if (mysqli_query($conn, $sql)) {
            $error = 'Sikeres módosítás!';
        } else {
            $error = mysqli_error($conn);
        }
    } else {
        $error = 'Minden mező kitöltése kötelező!';
    } break;
    case 'permit':
        $company=$_POST['company'];
        $email=$_POST['email'];
        $role=$_POST['perm'];
        if ($role==1 OR $role==2) {
            $sql2="UPDATE connect SET role='$role' WHERE user_id='$email' AND co_id='$company';";
        }else
        if ($role=="denied")
        {
            $sql2="DELETE FROM connect WHERE user_id='$email' AND co_id='$company';";
        }
        if (mysqli_query($conn, $sql2)) {
            $error = 'Sikeres mentés!';
        } else {
            $error = mysqli_error($conn);
        }




}
}

?>

<br> <h3>Az ön profilja:</h3>
<form method="post">
    <div class="form-group">
        <label for="exampleInputEmail1">E-mail cím</label>
        <?php echo '<input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value=' . $email . ' readonly>'; ?>

    </div>
    <div class="form-group">
        <label for="last_name">Vezetéknév: </label>
        <?php echo "<input type='text' name='last_name' class='form-control' id='formGroupExampleInput'  value=$last_name>"; ?>
    </div>
    <div class="form-group">
        <label for="first_name">Keresztnév: </label>
        <?php echo '<input type="text" name="first_name" class="form-control" id="formGroupExampleInput2" value=' . $first_name . '>'; ?>
    </div>
    <div class="form-group">
        <label for="reg_date">Regisztráció ideje: </label>
        <?php echo '<input type="text" name="reg_date" class="form-control" id="formGroupExampleInput2" value=' . $reg_date . ' readonly>'; ?>
    </div>

    <button type="submit" name="submit" value="edit" class="btn btn-primary">Módosítás</button>
</form>


<?php
$sql = "SELECT * FROM companies, connect, users WHERE users.user_id=connect.user_id AND companies.co_id=connect.co_id AND companies.co_id IN (SELECT companies.co_id FROM `connect`, companies WHERE connect.co_id=companies.co_id AND connect.user_id='$user_id' AND role=1) AND users.user_id!='$user_id';";
$result = mysqli_query($conn, $sql);
$count = mysqli_num_rows($result);
if ($count > 0) {
    echo '<br><h3>Jogosultságok kezelése:</h3>';
    echo '<br><table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">Vállalat</th>
        <th scope="col">E-mail cím</th>
        <th scope="col">Jelenlegi jogosultság</th>
        <th scope="col">Jogosultság kiválasztása</th>
        <th scope="col">Mentés</th>
      </tr>
    </thead>
    <tbody>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<form method="post"><tr>
        <td>' . $row['co_name'] .'<input type="hidden" name="company" value="'.$row['co_id'].'"></td>
        <td>' . $row['email'] .'<input type="hidden" name="email" value="'.$row['user_id'].'"></td>
        <td>';
        if ($row['role']==0) {
            echo 'Várakozik';
        }else
        if ($row['role']==1) {
            echo 'Szerkeszthet';
        }else
        if ($row['role']==2) {
            echo 'Megtekinthet';
        }
        
        echo '</td>
        <td>
        <select name="perm">
          <option value="denied">Megtagadás</option>
          <option value="2">Megtekinthet</option>
          <option value="1">Szerkeszthet</option>
        </select></td>
        <td><button type="submit" name="submit"  value="permit" class="btn btn-primary">Mentés</button></td>
        </form></tr>';
    }
    echo '</tbody> </table>';

}

?>
 <div >
                <?php
                if  (isset($error))
                {echo '<br><div class="alert alert-primary" role="alert">' . $error . '</div>';}
                ?>

            </div>