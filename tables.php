<?php
include("engine/connection.php");

session_start();
$sql = "SELECT * FROM leads_tbl";
$result = mysqli_query($connection, $sql);

if(!isset($_SESSION['npp'])) {
    header("Location: index.php");
    exit;
}

// Update tks_last_update
$dbh = $connection;
date_default_timezone_set('Asia/Jakarta');
$update_time = date('Y-m-d H:i:s');
$sql_update = $dbh->prepare("UPDATE rdb_user1 SET tks_last_update = ? WHERE tks_npp = ?");
$sql_update->bind_param("ss", $update_time, $_SESSION['npp']);
$sql_update->execute();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Table Data Leads - RDB</title>
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
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">Settings</a></li>
                        <li><a class="dropdown-item" href="#!">Activity Log</a></li>
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
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <?php echo $_SESSION['nama_lengkap']; ?>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">DATA LEADS</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Tables</li>
                        </ol>
                        <!-- <div class="card mb-4">
                            <div class="card-body">
                                DataTables is a third party plugin that is used to generate the demo table below. For more information about DataTables, please visit the
                                <a target="_blank" href="https://datatables.net/">official DataTables documentation</a>
                                .
                            </div>
                        </div> -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Table Data Leads
                            </div>
                            <div class="card-body">
                            <?php
                            // Cek apakah ada baris data yang dikembalikan
                            if (mysqli_num_rows($result) > 0) {
                                echo '<table id="datatablesSimple">
                                        <thead>
                                            <tr>
                                                <th>Code Leads</th>
                                                <th>Nama Leads</th>
                                                <th>Nama Calon Nasabah</th>
                                                <th>Alamat</th>
                                                <th>Kecamatan</th>
                                                <th>Kabupaten</th>
                                                <th>Provinsi</th>
                                                <th>Nama PIC</th>
                                                <th>No HP</th>
                                                <th>Code Cabang</th>
                                                <th>Cabang</th>
                                                <th>Outlet</th>
                                                <th>Name Inputter</th>
                                                <th>NPP Inputter</th>
                                                <th>Time Inputter</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Code Leads</th>
                                                <th>Nama Leads</th>
                                                <th>Nama Calon Nasabah</th>
                                                <th>Alamat</th>
                                                <th>Kecamatan</th>
                                                <th>Kabupaten</th>
                                                <th>Provinsi</th>
                                                <th>Nama PIC</th>
                                                <th>No HP</th>
                                                <th>Code Cabang</th>
                                                <th>Cabang</th>
                                                <th>Outlet</th>
                                                <th>Name Inputter</th>
                                                <th>NPP Inputter</th>
                                                <th>Time Inputter</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>';

                                // Loop untuk menampilkan data dalam tabel
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<tr>
                                            <td>' . $row["CODE_LEADS"] . '</td>
                                            <td>' . $row["NAMA_LEADS"] . '</td>
                                            <td>' . $row["NAMA_CALON_NASABAH"] . '</td>
                                            <td>' . $row["ALAMAT"] . '</td>
                                            <td>' . $row["KECAMATAN"] . '</td>
                                            <td>' . $row["KABUPATEN"] . '</td>
                                            <td>' . $row["PROVINSI"] . '</td>
                                            <td>' . $row["NAMA_PIC"] . '</td>
                                            <td>' . $row["NO_HP"] . '</td>
                                            <td>' . $row["CODE_CABANG"] . '</td>
                                            <td>' . $row["CABANG"] . '</td>
                                            <td>' . $row["OUTLET"] . '</td>
                                            <td>' . $row["NAME_INPUTTER"] . '</td>
                                            <td>' . $row["NPP_INPUTTER"] . '</td>
                                            <td>' . $row["TIME_INPUTTER"] . '</td>
                                            <td>
                                            <a href="edit.php?id=' . $row["CODE_LEADS"] . '" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#actionModal">Action</a> <!-- Tombol Edit -->                                            </td>
                                        </tr>';
                                }
                                echo '</tbody>
                                    </table>';
                            } else {
                                echo "No leads data available.";
                            }
                            ?>
                            </div>

                            <div class="card-footer">
                            <a href="export_excel.php" class="btn btn-success">Export</a>
                            </div>
                        </div>

                        <!-- Modal Pop-up -->
                        <div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel">Default Modal</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Tanggal Follow Up: <br>
                                Jenis Follow Up: <br>
                                Hasil Follow Up: <br>
                                Closing: <br>
                                Resume: <br>
                                Produk Closing: <br>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                            </div>
                        </div>
                        </div>

                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2023</div>
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
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>
