<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href=<?php if (isset($_SESSION['login_email'])) {
                                        echo "welcome";
                                    } else {
                                        echo "home";
                                    } ?>>Kezdőlap</a>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
        <?php if (isset($_SESSION['co_sel']))
        {
        echo '    <li class="nav-item">
                <a class="nav-link" href="link1">Eszközök listázása</a>
            </li>';
            if ($_SESSION['role']==1) {
                
            echo '
            <li class="nav-item">
                <a class="nav-link" href="link2">Új eszköz rögzítése</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="link3">Havi zárás</a>
            </li>';
        }
        }?>
        </ul>

        <ul class="navbar-nav ml-auto">

            <?php
            if (isset($_SESSION['login_email'])) {

                if (isset($_SESSION['co_sel'])) { echo '
                    <li class="nav-item">
                            <a class="nav-link" href="welcome">'.$_SESSION['co_sel'].'</a>
                        </li>';}

                echo '
                <li class="nav-item">
                        <a class="nav-link" href="profile">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout">Kijelentkezés</a>
                    </li>';
            } else {
                echo '
                    <li class="nav-item">
                            <a class="nav-link" href="registration">Regisztráció</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login">Belépés</a>
                        </li>';
            }
            ?>


        </ul>


    </div>
</nav>