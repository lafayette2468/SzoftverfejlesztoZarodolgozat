<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/my.css">
    <title>Eszközkezelő</title>
</head>

<body>
    <div class="container">
        <?php include 'header.php'; ?>
        <div class="row">
            <div class="col-md-12 main">
                <?php

                if (isset($_SESSION['page'])) {
                    $_GET['page'] = $_SESSION['page'];

                    unset($_SESSION['page']);
                }
                if (!isset($_GET['page'])) {
                    include 'home.php';
                } else {
                    $page = $_GET['page'];
                    switch ($page) {
                        case 'link1':
                            include 'link1.php';
                            break;
                        case 'link2':
                            if ($_SESSION['role'] == 1) {
                                include 'link2.php';
                            } else {
                                include 'welcome.php';
                            }
                            break;
                        case 'link3':
                            if ($_SESSION['role'] == 1) {
                                include 'link3.php';
                            } else {
                                include 'welcome.php';
                            }
                            break;
                        case 'registration':
                            include 'registration.php';
                            break;
                        case 'login':
                            include 'login.php';
                            break;
                        case 'welcome':
                            include 'welcome.php';
                            break;
                        case 'profile':
                            include 'profile.php';
                            break;
                        case 'logout':
                            include 'logout.php';
                            break;
                        case 'gdpr':
                            include 'gdpr.php';
                            break;
                        default:
                            include 'home.php';
                            break;
                    }
                }


                ?>

            </div>

        </div>
        <?php include 'footer.php'; ?>

    </div>





    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>