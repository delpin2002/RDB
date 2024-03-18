<?php
// Koneksi ke database
include("engine/connection.php");
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Membuat objek spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Mengatur judul kolom
$sheet->setCellValue('A1', 'Code Leads');
$sheet->setCellValue('B1', 'Nama Leads');
$sheet->setCellValue('C1', 'Nama Calon Nasabah');
$sheet->setCellValue('D1', 'Alamat');
$sheet->setCellValue('E1', 'Kecamatan');
$sheet->setCellValue('F1', 'Kabupaten');
$sheet->setCellValue('G1', 'Provinsi');
$sheet->setCellValue('H1', 'Nama PIC');
$sheet->setCellValue('I1', 'No HP');
$sheet->setCellValue('J1', 'Code Cabang');
$sheet->setCellValue('K1', 'Cabang');
$sheet->setCellValue('L1', 'Outlet');
$sheet->setCellValue('M1', 'Name Inputter');
$sheet->setCellValue('N1', 'Time Inputter');
// Tambahkan judul kolom lainnya sesuai kebutuhan

// Query untuk mengambil data
$sql = "SELECT * FROM leads_tbl";
$result = mysqli_query($connection, $sql);
$rowNum = 2; // Mulai dari baris kedua karena baris pertama untuk header

// Loop untuk mengisi spreadsheet dengan data
while ($row = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $rowNum, $row['CODE_LEADS']);
    $sheet->setCellValue('B' . $rowNum, $row['NAMA_LEADS']);
    $sheet->setCellValue('C' . $rowNum, $row['NAMA_CALON_NASABAH']);
    $sheet->setCellValue('D' . $rowNum, $row['ALAMAT']);
    $sheet->setCellValue('E' . $rowNum, $row['KECAMATAN']);
    $sheet->setCellValue('F' . $rowNum, $row['KABUPATEN']);
    $sheet->setCellValue('G' . $rowNum, $row['PROVINSI']);
    $sheet->setCellValue('H' . $rowNum, $row['NAMA_PIC']);
    $sheet->setCellValue('I' . $rowNum, $row['NO_HP']);
    $sheet->setCellValue('J' . $rowNum, $row['CODE_CABANG']);
    $sheet->setCellValue('K' . $rowNum, $row['CABANG']);
    $sheet->setCellValue('L' . $rowNum, $row['OUTLET']);
    $sheet->setCellValue('M' . $rowNum, $row['NAME_INPUTTER']);
    $sheet->setCellValue('N' . $rowNum, $row['TIME_INPUTTER']);
    // Set nilai sel lainnya berdasarkan data Anda
    $rowNum++;
}

// Tentukan header untuk download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="DataLeads.xlsx"');
header('Cache-Control: max-age=0');

// Menulis file Excel dan melakukan download
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
