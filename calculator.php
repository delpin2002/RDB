<?php
include("engine/connection.php");

session_start();
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


    <style>
        /* Style untuk body, sidebar, dan main content */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }

        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 200px;
            height: 100vh;
            background-color: #343a40;
            color: #fff;
            z-index: 1;
            transition: transform 0.3s ease-in-out;
            overflow-y: auto;
        }

        #sidebar.hidden {
            transform: translateX(-200px);
        }

        #main-content {
            margin-left: 200px;
            padding: 20px;
            transition: margin-left 0.3s ease-in-out;
        }

        /* Style untuk tombol toggle */
        #toggle-button {
            position: fixed;
            top: 20px;
            left: 200px;
            z-index: 2;
            transition: left 0.3s ease-in-out;
            background-color: #343a40;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 50%;
            font-size: 1.5rem;
            cursor: pointer;
        }

        #toggle-button:hover {
            background-color: #555;
        }

        /* Style untuk list menu di sidebar */
        #sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        #sidebar ul li {
            padding: 10px 20px;
            border-bottom: 1px solid #555;
            transition: background-color 0.3s;
        }

        #sidebar ul li:hover {
            background-color: #555;
        }

        #sidebar ul li a {
            color: #fff;
            text-decoration: none;
        }

        /* Style untuk kontainer di main content */
        .container {
            margin-top: 50px; /* Adjust sesuai kebutuhan */
            text-align: center;
        }

        .container h2 {
            margin-bottom: 20px;
        }

        .container p {
            margin-bottom: 10px;
        }

        .container a {
            display: block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .container a:hover {
            text-decoration: underline;
        }

        /* Gaya Tabel */
        .performance-table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        .performance-table th, .performance-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .performance-table th {
            background-color: #007bff;
            color: black;
        }

        .performance-table tr:hover {
            background-color: #f2f2f2;
        }

        /* Gaya Formulir dan Tombol */
        input[type=number] {
            padding: 5px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type=submit] {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type=submit]:hover {
            background-color: #218838;
        }
        

        @media screen and (max-width: 600px) {
            .performance-table {
                width: 100%;
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
            <a href="calculator.php">
                <img src="assets\image\BNI.png" alt="BNI" style="width:150px;height:50px;">
            </a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <!-- <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                </div> -->
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
                                Sales Dashboard
                            </a>
                            <!-- <div class="sb-sidenav-menu-heading">Menus</div>
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
                            </div> -->
                        </div>
                    </div>
                    <div class="py-1 sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <?php echo $_SESSION['nama_lengkap']; ?>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main class="mt-4">
                        <!-- Main Content -->
                        <div id="container-fluid px-4">

                            <div class="container">
                                <h2 class="mb-4"> Performance Dashboard RME</h2>
                                <form id="realization-form">
                                    <div class="table-responsive">
                                    <table class="table performance-table" border="1">
                                        <!-- -->
                                        <tr>
                                            <th>KATEGORI</th>
                                            <th>PRODUK</th>
                                            <th>TARGET</th>
                                            <th>REALISASI  </th>
                                            <th>% TARGET</th>
                                            <th>BOBOT</th>
                                            <th>TOTAL</th>
                                        </tr>
                                        <!-- -->
                                        <tr>
                                            <td rowspan="2">AKUISISI</td>
                                            <td>EDC</td>
                                            <td><input type="number" name="akuisisi_target_edc" min="0" max="999" step="1" value="3" readonly></td>
                                            <td><input type="number" name="akuisisi_realisasi_edc" min="0" max="999" step="1" ></td>
                                            <td><input type="number" name="akuisisi_persen_target_edc" min="0" max="999" step="1" readonly>%</td>
                                            <td><input type="number" name="akuisisi_persen_bobot_edc" min="0" max="999" step="1" value="20" readonly>%</td>
                                            <td id="akuisisi-total-edc">0%</td>
                                        </tr>
                                        <tr>
                                            <td>QRIS</td>
                                            <td><input type="number" name="akuisisi_target_qris" min="0" max="999" step="1" value="5" readonly></td>
                                            <td><input type="number" name="akuisisi_realisasi_qris" min="0" max="999" step="1"></td>
                                            <td><input type="number" name="akuisisi_persen_target_qris" min="0" max="999" step="1" readonly>%</td>
                                            <td><input type="number" name="akuisisi_persen_bobot_qris" min="0" max="999" step="1" value="10" readonly>%</td>
                                            <td id="akuisisi-total-qris">0%</td>
                                        </tr>
                                        <!--  -->
                                        <tr>
                                            <td rowspan="2">SV</td>
                                            <td>EDC</td>
                                            <td><input type="number" name="sv_target_edc" min="0" max="999" step="1" value="15" readonly>%</td>
                                            <td><input type="number" name="sv_realisasi_edc" min="0" max="999" step="1"></td>
                                            <td><input type="number" name="sv_persen_target_edc" min="0" max="999" step="1" value="75" readonly>%</td>
                                            <td><input type="number" name="sv_persen_bobot_edc" min="0" max="999" step="1" value="25" readonly>%</td>
                                            <td id="sv-total-edc">0%</td>
                                        </tr>
                                        <tr>
                                            <td>QRIS</td>
                                            <td><input type="number" name="sv_target_qris" min="0" max="999" step="1" value="50" readonly>%</td>
                                            <td><input type="number" name="sv_realisasi_qris" min="0" max="999" step="1"></td>
                                            <td><input type="number" name="sv_persen_target_qris" min="0" max="999" step="1" value="75" readonly>%</td>
                                            <td><input type="number" name="sv_persen_bobot_qris" min="0" max="999" step="1" value="15" readonly>%</td>
                                            <td id="sv-total-qris">0%</td>
                                        </tr>
                                        <!--  -->
                                        <tr>
                                            <td rowspan="1">TABUNGAN</td>
                                            <td>CASA</td>
                                            <td><input type="number" name="sv_target_tabungan" min="0" max="999" step="1" value="2" readonly>%</td>
                                            <td><input type="number" name="sv_realisasi_tabungan" min="0" max="999" step="1"></td>
                                            <td><input type="number" name="sv_persen_target_tabungan" min="0" max="999" step="1" value="75" readonly>%</td>
                                            <td><input type="number" name="sv_persen_bobot_tabungan" min="0" max="999" step="1" value="30" readonly>%</td>
                                            <td id="tabungan-total-casa">0%</td>
                                        </tr>
                                        <!--  -->
                                        <tr>
                                            <td rowspan="1">TOTAL</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td id="total-all" style="color: red; font-size: 30px;">0%</td>
                                        </tr>
                                        
                                    </table>
                                    <input class="btn btn-success mt-3" type="submit" value="Calculate Totals">
                                </form>
                            </div>

                            
                            <script>
                                document.getElementById('realization-form').addEventListener('submit', function(event) {
                                    event.preventDefault();

                                    function calculatePercentage(realized, target) {
                                        if (isNaN(realized) || isNaN(target) || target === 0) {
                                            return 0;
                                        }
                                        return Math.round((realized / target) * 100);
                                    }

                                    function calculateWeightedTotal(percentage, weight) {
                                        if (isNaN(percentage) || isNaN(weight)) {
                                            return 0;
                                        }
                                        return (percentage * weight) / 100;
                                    }

                                    const inputs = document.querySelectorAll('.performance-table input[type=number]');
                                    let grandTotal = 0;

                                    // ALL COLUMN CALCULATE //

                                    // for (let i = 0; i < inputs.length / 4; i++) {
                                    //     let target = parseFloat(inputs[i * 4].value);
                                    //     let realisasi = parseFloat(inputs[i * 4 + 1].value);
                                    //     let persenTargetInput = inputs[i * 4 + 2];
                                    //     let bobot = parseFloat(inputs[i * 4 + 3].value);
                                    //     let totalCell = inputs[i * 4 + 3].parentNode.nextElementSibling;

                                    //     let persenTarget = calculatePercentage(realisasi, target);
                                    //     let total = calculateWeightedTotal(persenTarget, bobot);

                                    //     persenTargetInput.value = persenTarget;
                                    //     totalCell.textContent = total.toFixed(2) + '%';

                                    //     grandTotal += total;
                                    // }

                                    // document.getElementById('total-all').textContent = grandTotal.toFixed(2) + '%';

                                    // TESTING KONSTANT 75% //

                                    for (let i = 0; i < inputs.length / 4; i++) {
                                        // Assign each input to a variable
                                        let target = parseFloat(inputs[i * 4].value);
                                        let realisasi = parseFloat(inputs[i * 4 + 1].value);
                                        let persenTargetInput = inputs[i * 4 + 2];
                                        let bobot = parseFloat(inputs[i * 4 + 3].value);
                                        let totalCell = inputs[i * 4 + 3].parentNode.nextElementSibling;

                                        if (persenTargetInput.name !== "sv_persen_target_edc" &&
                                            persenTargetInput.name !== "sv_persen_target_qris" &&
                                            persenTargetInput.name !== "sv_persen_target_tabungan") {
                                            let persenTarget = calculatePercentage(realisasi, target);
                                            persenTargetInput.value = persenTarget; 
                                        }

                                        // TOTAL
                                        let persenTarget = parseFloat(persenTargetInput.value);
                                        let total = calculateWeightedTotal(persenTarget, bobot);

                                        totalCell.textContent = total.toFixed(2) + '%';

                                        grandTotal += total;
                                    }

                                    document.getElementById('total-all').textContent = grandTotal.toFixed(2) + '%';
                                });
                            </script>

                            

                        </div>
                </main>
                <footer class="py-3 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <!-- <div class="text-muted">Copyright &copy; Your Website 2024</div> -->
                            <div class="text-muted">BNI</div>
                            <!-- <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div> -->
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
