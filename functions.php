<?php
include "koneksi.php";

function personalized($kategori)
{
    global $koneksi;
    $sql = "SELECT * FROM image_attraction";
    $row = $koneksi->prepare($sql);

    $row->execute();
    $items = $row->fetchAll();


    $kategoriArray = explode(',', $kategori);
    $result = [];

    if (count($kategoriArray) == 1) {
        $command = escapeshellcmd('swipl -s wisata.pl -g "print_all_wisata(' . $kategori . '), halt."');
        $output = shell_exec($command);

        $lines = explode("\n", trim($output));

        $data = [];
        foreach ($lines as $line) {
            $parts = explode(': ', $line);
            $nama = str_replace('_', ' ', $parts[0]);
            $deskripsi = explode(" Biaya", $parts[1]);
            $biaya = $parts[2];
            $data[] = [
                'nama' => $nama,
                'deskripsi' => $deskripsi[0],
                'biaya' => $biaya,
            ];
        }

        return ['items' => $items, 'data' => $data];
    } else {
        $kategoriNew = "[" . implode(',', array_map('trim', $kategoriArray)) . "]";

        $command = escapeshellcmd('swipl -s wisata.pl -g "wisata_berdasarkan_kategori_list(' . $kategoriNew . ', X), write(X), halt."');
        $output = shell_exec($command);

        // Process the Prolog output
        $output = trim($output, '[]');
        $output = preg_replace('/],\s*\[/', ']|[', $output);
        $itemsArray = explode('|', $output);

        $result = [];
        foreach ($itemsArray as $item) {
            list($nama, $deskripsi, $biaya) = array_map('trim', explode(',', $item));
            $nama = str_replace('[', '', $nama);
            $biaya = trim($biaya, '[]');
            $result[] = [
                'nama' => str_replace('_', ' ', $nama),
                'deskripsi' => $deskripsi,
                'biaya' => $biaya
            ];
        }

        return ['items' => $items, 'data' => $result];
    }
}

function categoryWithBudget($kategori, $budgetMin, $budgetMax)
{
    global $koneksi;
    $sql = "SELECT * FROM image_attraction";
    $row = $koneksi->prepare($sql);

    $row->execute();
    $items = $row->fetchAll();

    // Jalankan perintah shell untuk memanggil Prolog
    $command = escapeshellcmd('swipl -s wisata.pl -g "wisata_berdasarkan_kategori_budget(\'' . $kategori . '\', ' . $budgetMin . ', ' . $budgetMax . ', X), write(X), halt."');
    $output = shell_exec($command);

    // Periksa apakah output kosong
    if ($output == '[]') {
        return ['items' => $items, 'data' => null];
    }

    // Trim dan proses output dari Prolog
    $output = trim($output, '[]');
    $output = preg_replace('/],\s*\[/', ']|[', $output);
    $itemsArray = explode('|', $output);

    $result = [];
    foreach ($itemsArray as $item) {
        list($nama, $deskripsi, $biaya) = array_map('trim', explode(',', $item));
        $nama = str_replace('[', '', $nama);
        $biaya = trim($biaya, '[]');
        $result[] = [
            'nama' => str_replace('_', ' ', $nama),
            'deskripsi' => $deskripsi,
            'biaya' => $biaya
        ];
    }

    return ['items' => $items, 'data' => $result];
}



function wisataByCategory($kategori)
{
    global $koneksi;
    $sql = "SELECT * FROM image_attraction";
    $row = $koneksi->prepare($sql);

    $row->execute();
    $items = $row->fetchAll();

    $command = escapeshellcmd('swipl -s wisata.pl -g "print_all_wisata(' . $kategori . '), halt."');
    $output = shell_exec($command);

    $lines = explode("\n", trim($output));

    $data = [];
    foreach ($lines as $line) {
        $parts = explode(': ', $line);
        $nama = str_replace('_', ' ', $parts[0]);
        $deskripsi = explode(" Biaya", $parts[1]);
        $biaya = $parts[2];
        $data[] = [
            'nama' => $nama,
            'deskripsi' => $deskripsi[0],
            'biaya' => $biaya,
        ];
    }
    return ['items' => $items, 'data' => $data];
}

function plan($iduser, $tanggal, $wisata)
{
    global $koneksi;
    $sqlImage = "SELECT * FROM image_attraction where nama = '" . $wisata . "'";
    $rowImage = $koneksi->prepare($sqlImage);
    $rowImage->execute();
    $items = $rowImage->fetch();

    $sqlInsert = "INSERT INTO user_plans (user_id, image_attraction_id, date) VALUES (:user_id, :image_attraction_id, :date)";
    $stmt = $koneksi->prepare($sqlInsert);
    $stmt->bindParam(':user_id', $iduser);
    $stmt->bindParam(':image_attraction_id', $items['id']);
    $stmt->bindParam(':date', $tanggal);
    $stmt->execute();
}

function getItenary($iduser)
{
    global $koneksi;
    $sql = "
    SELECT up.*, im.nama, im.url, im.location, im.rating
    FROM user_plans up
    INNER JOIN image_attraction im ON up.image_attraction_id = im.id
    WHERE up.user_id = :user_id
";
    $row = $koneksi->prepare($sql);
    $row->bindParam(':user_id', $iduser);
    $row->execute();
    $items = $row->fetchAll();


    return $items;
}
