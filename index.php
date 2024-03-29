<?php
include("engine/connection.php");

session_start();
if(isset($_SESSION['npp'])) {
    header("Location: welcome.php");
    exit();
}

$dbh = $connection;

function login($tks_npp, $tks_pass, $dbh) {
    try {
        $sql = $dbh->prepare("SELECT * FROM rdb_user1 WHERE tks_npp = ?");
        $sql->bind_param("s", $tks_npp);
        $sql->execute();

        $result = $sql->get_result();

        if($result->num_rows > 0) {
            $takis = $result->fetch_assoc();

            $p          = $takis['tks_pass'];
            $p_salt     = $takis['psalt'];

            $site_salt  = "jocoogja";
            $salted_hash = hash('sha256', $tks_pass.$site_salt.$p_salt);

            if ($p == $salted_hash) {
                date_default_timezone_set('Asia/Jakarta');

                $update_time = date('Y-m-d H:i:s');
                $sql_update = $dbh->prepare("UPDATE rdb_user1 SET tks_last_update = ?, tks_last_login = ? WHERE tks_npp = ?");
                $sql_update->bind_param("sss", $update_time, $update_time, $tks_npp);
                $sql_update->execute(); 

                $_SESSION['npp']           = $takis['tks_npp'];
                $_SESSION['kode_job']      = $takis['tks_kode_job'];
                $_SESSION['nama_lengkap']  = $takis['tks_nama_lengkap'];
                $_SESSION['nama_panggilan']  = $takis['tks_panggilan'];
                $_SESSION['cabang']  = $takis['tks_cab'];
                $_SESSION['outlet']  = $takis['tks_out'];
                $_SESSION['no_telp']  = $takis['tks_notelp'];
                $_SESSION['email']         = $takis['tks_email'];

                header("Location: welcome.php");
                exit();
            } else {
                $_SESSION['err'] = 1;
            }
        } else {
            $_SESSION['err'] = 1;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

if(isset($_POST['tks_npp']) && isset($_POST['tks_pass'])) {
    $tks_npp = $_POST['tks_npp'];
    $tks_pass = $_POST['tks_pass'];
    login($tks_npp, $tks_pass, $dbh);
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login - RDB </title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>

    <video id="background-video" autoplay loop muted poster="https://assets.codepen.io/6093409/river.jpg">
        <source src="assets/video/videoplayback.webm" type="video/mp4">
    </video>


        <style> 
                /* body {
                background-image: url('assets/image/BGBNI.jpg');
                background-size: cover; 
                background-repeat: no-repeat; 
                background-attachment: fixed; 
                background-size: cover;
                background-position: center;
                } */

                #background-video {
                width: 100vw;
                height: 100vh;
                object-fit: cover;
                position: fixed;
                left: 0;
                right: 0;
                top: 0;
                bottom: 0;
                z-index: -1;
                }
        </style>

    
    
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header">
                                        <img src="assets\image\BNI.png" alt="BNI" style="width:150px;height:50px;">
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="index.php">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputNPP" type="text" name="tks_npp" placeholder="insert your npp!" required/>
                                                <label for="inputEmail">NPP</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputPassword" type="password" name="tks_pass" placeholder="Password" required/>
                                                <label for="inputPassword">Password</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <button class="btn btn-primary" type="submit" value="Login">Login</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small">
                                            <a href="register.php">Sign up</a> |
                                            <a href="password.html">Forgot Password</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-3 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2024</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
