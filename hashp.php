<?php
function generateHashedPasswordAndPsalt($userPassword) {
    $siteSalt = "jocoogja"; // Salt yang digunakan secara global di aplikasi
    $userPsalt = bin2hex(random_bytes(16)); // Menghasilkan string acak untuk psalt

    // Hash gabungan dari kata sandi, site_salt, dan psalt pengguna
    $hashedPassword = hash('sha256', $userPassword . $siteSalt . $userPsalt);

    return array('hashedPassword' => $hashedPassword, 'psalt' => $userPsalt);
}

// Contoh penggunaan fungsi
$userPassword = "delvin2002";
$result = generateHashedPasswordAndPsalt($userPassword);

echo "Hashed Password: " . $result['hashedPassword'] . "\n";
echo "Psalt: " . $result['psalt'] . "\n";
?>
