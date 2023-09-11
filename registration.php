<?
include 'config.php';
// session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email=mysqli_real_escape_string($conn, $_POST['email']);
    $pass1=mysqli_real_escape_string($conn, $_POST['pass1']);
    $pass2=mysqli_real_escape_string($conn, $_POST['pass2']);
    $date= date('Y-m-d H:i:s');
    


    if ($email != '' && $pass1!='' && $pass2 != '' && isset($_POST['accept']) )
    {
        if ($pass1==$pass2) {
            
            $sql="SELECT user_id FROM users WHERE email ='$email'";
            $result= mysqli_query($conn, $sql);
            $count=mysqli_num_rows($result);
            if ($count == 0) {
                $sql="INSERT INTO `users` (email,password,reg_date) 
                VALUES ('$email', md5('$pass1'), '$date')";
                if (mysqli_query($conn, $sql)){
                    $_SESSION['login_email'] = $email;
                    $sql="SELECT user_id FROM users WHERE email ='$email' AND password =md5('$pass1')";
                    $result= mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $_SESSION['user_id']=$row['user_id'];
                    }
                    header('Location: profile');
                }
                else{
                    $error = mysql_error($conn);
                }


            }
            else{$error = 'Létező e-mail cím!';}
        


    }
    else {$error = 'Nem egyezik a két jelszó!';}
} else {
    $error = 'Minden mező kitöltése kötelező!';
}
  
}

?>


<form method="post">
    <div class="form-group">
        <label for="exampleInputEmail1">E-mail cím</label>
        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">

    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Jelszó</label>
        <input type="password" name="pass1" class="form-control" id="exampleInputPassword1">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Jelszó újra</label>
        <input type="password" name="pass2" class="form-control" id="exampleInputPassword1">
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="exampleCheck1" name="accept">
        <label class="form-check-label"  for="exampleCheck1">Az <a href="gdpr">adatkezelési tájékoztatót</a> elfogadom.</label>
    </div>
    <br>
    <button type="submit" class="btn btn-primary">Regisztráció</button>
</form>
<div>
    <?php
    if (isset($error)) {
        echo $error;
    }
    ?>

</div>