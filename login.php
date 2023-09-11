<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $password = md5($password);
    $sql = "SELECT user_id FROM users WHERE email ='$email' AND password ='$password'";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);

    if ($count == 1) {
        $_SESSION['login_email'] = $email;
        $_SESSION['page'] = 'welcome';
        while ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['user_id'] = $row['user_id'];
        }
        $date = date('Y-m-d H:i:s');
        $sql2 = "UPDATE users SET last_login='$date' WHERE email ='$email'";
        if (mysqli_query($conn, $sql2)) {
            header('Location: welcome');
        }
            else{$error = mysqli_error($conn);}
        
    } else {
        $error = 'Hibás felhasználónév vagy jelszó!';
    }
}

?>


<form method="post">
    <div class="form-group">
        <label for="exampleInputEmail1">E-mail cím</label>
        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
        <small id="emailHelp" class="form-text text-muted">Az e-mail címedet nem osztjuk meg senkivel. </small>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Jelszó</label>
        <input type="password" name="password" class="form-control" id="exampleInputPassword1">
    </div>

    <button type="submit" class="btn btn-primary">Bejelentkezés</button>
</form>
<div>
    <?php
    if (isset($error)) {
        echo $error;
    }
    ?>

</div>