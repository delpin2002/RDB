<?php
session_start();
require_once "engine/connection.php";
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

if (!$connection) {
    die("Database connection failed");
}

function generateNewCode($lastCode, $connection, $nppInputter, $namaInputter) {
    // Change the date format to ddmmyy
    date_default_timezone_set('Asia/Jakarta');
    $today = date('dmy');

    // Check if the last code was generated today
    $lastDatePart = substr($lastCode, 0, 6);
    if ($lastDatePart == $today) {
        // If yes, increment the number part
        $lastNumber = (int)substr($lastCode, -4);
        $newNumber = $lastNumber + 1;
        $newNumberPadded = str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        $newCode = $today . $newNumberPadded;
    } else {
        // If no, start with 0001
        $newCode = $today . '0001';
    }

    // Prepare a statement to check for duplicate codes
    $stmt = $connection->prepare("SELECT COUNT(*) FROM leads_tbl WHERE CODE_LEADS = ? AND NPP_INPUTTER != ? AND NAME_INPUTTER != ?");
    $stmt->bind_param("sss", $newCode, $nppInputter, $namaInputter);
    $stmt->execute();
    $count = 0;
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    // If a duplicate is found, recursively generate a new code
    if ($count > 0) {
        return generateNewCode($newCode, $connection, $nppInputter, $namaInputter);
    }

    // Return the new code if no duplicates are found
    return $newCode;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $file = $_FILES['fileUpload']['tmp_name'];
    $spreadsheet = IOFactory::load($file);
    $worksheet = $spreadsheet->getActiveSheet();
    $highestRow = $worksheet->getHighestRow();
    
    // Get the last code from the database
    $lastCodeStatement = $connection->query("SELECT CODE_LEADS FROM leads_tbl ORDER BY CODE_LEADS DESC LIMIT 1");
    $lastCodeRow = $lastCodeStatement->fetch_assoc();
    $lastCode = $lastCodeRow ? $lastCodeRow['CODE_LEADS'] : null;

    // Get the inputter's information from session
    $nppInputter = $_SESSION['npp'];
    $namaInputter = $_SESSION['nama_lengkap'];

    // Generate a new unique code for this batch
    $newCode = generateNewCode($lastCode, $connection, $nppInputter, $namaInputter);

    // Iterate through each row in the Excel file
    for ($row = 2; $row <= $highestRow; $row++) {
        $rowData = $worksheet->rangeToArray('A' . $row . ':K' . $row, NULL, TRUE, FALSE)[0];
        
        // Extract data for each column

        if (empty($rowData[0])) {
            continue; // Skip this iteration if the row is empty
        }

        $namaLeads = $_POST['NAMA_LEADS'];
        $namaCalonNasabah = $rowData[0];
        $alamat = $rowData[1];
        $kecamatan = $rowData[2];
        $kabupaten = $rowData[3];
        $provinsi = $rowData[4];
        $nama_pic = $rowData[5];
        $no_hp = $rowData[6];
        $code_cabang = $rowData[7];
        $cabang = $rowData[8];
        $outlet = $rowData[9];

        // Insert data into the database with the new unique code
        $sql = $connection->prepare("INSERT INTO leads_tbl (CODE_LEADS, NAMA_LEADS, NAMA_CALON_NASABAH, ALAMAT, KECAMATAN, KABUPATEN, PROVINSI, NAMA_PIC, NO_HP, CODE_CABANG, CABANG, OUTLET, NAME_INPUTTER, NPP_INPUTTER, TIME_INPUTTER) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $sql->bind_param("ssssssssssssss", $newCode, $namaLeads, $namaCalonNasabah, $alamat, $kecamatan, $kabupaten, $provinsi, $nama_pic, $no_hp, $code_cabang, $cabang, $outlet, $namaInputter, $nppInputter);
        $sql->execute();
    }

    // Store the last generated code in session, in case it's needed
    $_SESSION['lastGeneratedCode'] = $newCode;

    // Redirect back to the form page
    header("Location: form_input.php");
    exit();
}

?>
