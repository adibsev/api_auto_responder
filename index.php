<?php
error_reporting(1);
$mysql_host = "192.168.xxx.xxx";
$mysql_user = "xxx";
$mysql_pass = "xxx";
$mysql_dbname = "xxx";
$conn = mysqli_connect($mysql_host, $mysql_user, $mysql_pass, $mysql_dbname);

function bersihkannomertelp($angka)
{
    $angka = str_replace('+', '', $angka);
    $angka = str_replace('-', '', $angka);
    $angka = str_replace(' ', '', $angka);
    $angka = str_replace('(', '', $angka);
    $angka = str_replace(')', '', $angka);
    $angka = str_replace('@c.us', '', $angka);
    return $angka;
}

date_default_timezone_set("Asia/Jakarta");
$updates = file_get_contents("php://input");
$updates = json_decode($updates, true);
$pesan = $updates['query']['message'];
$pengirim = bersihkannomertelp($updates['query']['sender']);
$format_pesan = 0;
$pesan = strtoupper($pesan);
$datas = explode("#", $pesan);
$perintah = $datas[0];

if ($perintah == "/START") {
    $pesan_balik = 'selamat datang';
    $format_pesan = 1;
} elseif ($perintah == "LIHAT DATA") {
    $sql = "select * from user where nama='" . trim($datas[1]) . "'";
    $result = mysqli_query($conn, $sql);
    $row_cnt = mysqli_num_rows($result);
    while ($row = mysqli_fetch_assoc($result)) {
        $pesan_balik = 'data ditemukan' . $row['nama'];
        $format_pesan = 1;
    }
    mysqli_free_result($result);
} else {
    $pesan_balik = 'perintah tidak dikenali';
    $format_pesan = 1;
}

$pesan_kirim = array(
    'replies' => array(
        array(
            'message' => $pesan_balik
        )
    ) 
);

echo json_encode($pesan_kirim);
mysqli_close($conn);
