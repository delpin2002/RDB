<div class="card-body">
                            <!-- Card body content -->
                            <?php
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