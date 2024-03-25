<?php
include("engine/connection.php");

session_start();
if(!isset($_SESSION['npp'])) {
    header("Location: index.php");
    exit;
}

$dbh = $connection;
date_default_timezone_set('Asia/Jakarta');
$update_time = date('Y-m-d H:i:s');
$sql_update = $dbh->prepare("UPDATE rdb_user1 SET tks_last_update = ? WHERE tks_npp = ?");
$sql_update->bind_param("ss", $update_time, $_SESSION['npp']);
$sql_update->execute();

$npp = $_SESSION['npp'];

$stmt = $dbh->prepare("SELECT tks_kode_job, tks_nama_lengkap, tks_panggilan, tks_npp, tks_cab, tks_out, tks_notelp, tks_email FROM rdb_user1 WHERE tks_npp = ?");
$stmt->bind_param("s", $npp);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $takis = $result->fetch_assoc();
} else {
    $_SESSION['error'] = 'No user found with that NPP.';
    header('Location: error_page.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $updateStmt = $dbh->prepare("UPDATE rdb_user1 SET tks_kode_job = ?, tks_nama_lengkap = ?, tks_panggilan = ?, tks_cab = ?, tks_out = ?, tks_notelp = ?, tks_email = ?, tks_last_update = ? WHERE tks_npp = ?");
    
    $updateStmt->bind_param("sssssssss", $_POST['tks_kode_job'], $_POST['tks_nama_lengkap'], $_POST['tks_panggilan'], $_POST['tks_cab'], $_POST['tks_out'], $_POST['tks_notelp'], $_POST['tks_email'], $update_time, $npp);
    $update_time = date('Y-m-d H:i:s');

    if ($updateStmt->execute()) {
        $_SESSION['success'] = 'Profil berhasil diperbarui.';
    
        $updateStmt = $dbh->prepare("UPDATE rdb_user1 SET tks_kode_job = ?, tks_nama_lengkap = ?, tks_panggilan = ?, tks_cab = ?, tks_out = ?, tks_notelp = ?, tks_email = ?, tks_last_update = ? WHERE tks_npp = ?");
        $stmt->bind_param("s", $npp);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $takis = $result->fetch_assoc();
    
            $_SESSION['nama_lengkap']    = $takis['tks_nama_lengkap'];
            $_SESSION['email']           = $takis['tks_email'];
            $_SESSION['npp']             = $takis['tks_npp'];
            $_SESSION['kode_job']        = $takis['tks_kode_job'];
            $_SESSION['nama_lengkap']    = $takis['tks_nama_lengkap'];
            $_SESSION['nama_panggilan']  = $takis['tks_panggilan'];
            $_SESSION['cabang']          = $takis['tks_cab'];
            $_SESSION['outlet']          = $takis['tks_out'];
            $_SESSION['no_telp']         = $takis['tks_notelp'];
            $_SESSION['email']           = $takis['tks_email'];

        }
    
    } else {
        $_SESSION['error'] = 'Terjadi kesalahan saat memperbarui profil: ' . $dbh->error;
    }
} else {
    $stmt = $dbh->prepare("SELECT tks_kode_job, tks_nama_lengkap, tks_panggilan, tks_npp, tks_cab, tks_out, tks_notelp, tks_email FROM rdb_user1 WHERE tks_npp = ?");
    $stmt->bind_param("s", $npp);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $takis = $result->fetch_assoc();
    } else {
        $_SESSION['error'] = 'No user found with that NPP.';
        header('Location: error_page.php');
        exit;
    }
}
?>

<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f8f9fa;
    }

    h2 {
        padding-left: 20px;
        margin-top: 0;
        margin-bottom: 20px;
    }

    .container {
        display: flex;
        justify-content: center;
        align-items: start;
        padding: 20px;
    }

    .profile-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,.1);
        padding: 20px;
        margin: 2rem auto; /* Center card and add margin */
        width: 100%;
        max-width: 550px; /* Control card width */
    }

    .form-control {
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .form-group label {
        margin-bottom: 5px;
    }

    .btn-primary {
        padding: 5px 15px;
        font-size: 0.8rem;
        margin: 1rem auto; 
        display: block;
    }

    .alert {
        padding: 10px;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .profile-card {
            margin: 1rem auto;
            width: 90%;
        }

        .container {
            padding: 10px;
        }
    }
</style>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard - RDB</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a href="welcome.php">
                <img src="assets\image\BNI.png" alt="BNI" style="width:150px;height:50px;">
            </a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <!-- <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button> -->
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">Settings</a></li>
                        <li><a class="dropdown-item" href="profile_user.php">Profile</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Dashboards</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                RDB Dashboard
                            </a>
                            <div class="sb-sidenav-menu-heading">Menus</div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Pages
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="form_input.php">Input Data</a>
                                    <a class="nav-link" href="tables.php">Table Data Leads</a>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="py-1 sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <?php echo $_SESSION['nama_lengkap']; ?>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                        <!-- Main Content -->
                    <div class="card">
                        <div class="card-body">
                            <h2>Edit Profile</h2>
                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="alert alert-success">
                                    <?php echo $_SESSION['success']; ?>
                                    <?php unset($_SESSION['success']); ?>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger">
                                    <?php echo $_SESSION['error']; ?>
                                    <?php unset($_SESSION['error']); ?>
                                </div>
                            <?php endif; ?>
                            <form action="profile_user.php" method="post">
                                <div class="form-group">
                                    <label for="tks_kode_job">Kode Job</label>
                                    <input type="text" class="form-control" id="tks_kode_job" name="tks_kode_job" value="<?php echo htmlspecialchars($takis['tks_kode_job']); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="tks_nama_lengkap">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="tks_nama_lengkap" name="tks_nama_lengkap" value="<?php echo htmlspecialchars($takis['tks_nama_lengkap']); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="tks_panggilan">Panggilan</label>
                                    <input type="text" class="form-control" id="tks_panggilan" name="tks_panggilan" value="<?php echo htmlspecialchars($takis['tks_panggilan']); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="tks_npp">NPP</label>
                                    <input type="text" class="form-control" id="tks_npp" name="tks_npp" value="<?php echo htmlspecialchars($takis['tks_npp']); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="tks_cab">Cabang</label>
                                    <input type="text" class="form-control" id="tks_cab" name="tks_cab" value="<?php echo htmlspecialchars($takis['tks_cab']); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="tks_out">Out</label>
                                    <input type="text" class="form-control" id="tks_out" name="tks_out" value="<?php echo htmlspecialchars($takis['tks_out']); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="tks_notelp">Nomor Telepon</label>
                                    <input type="text" class="form-control" id="tks_notelp" name="tks_notelp" value="<?php echo htmlspecialchars($takis['tks_notelp']); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="tks_email">Email</label>
                                    <input type="email" class="form-control" id="tks_email" name="tks_email" value="<?php echo htmlspecialchars($takis['tks_email']); ?>">
                                </div>
                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </form>
                        </div>
                    </div>
                </main>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>
