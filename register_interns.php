<?php

/**
 * Script pendaftaran ulang peserta magang BPJS
 * - Hapus semua akun intern
 * - Buat mentor UNCHECKED jika belum ada
 * - Daftarkan ulang semua peserta
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// =============================================
// STEP 1: HAPUS SEMUA AKUN INTERN
// =============================================
echo "=== STEP 1: HAPUS SEMUA AKUN INTERN ===" . PHP_EOL;
$deletedCount = User::where('role', 'intern')->delete();
echo "  => $deletedCount akun intern dihapus." . PHP_EOL . PHP_EOL;

// =============================================
// STEP 2: BUAT / AMBIL MENTOR UNCHECKED
// =============================================
echo "=== STEP 2: MENTOR UNCHECKED ===" . PHP_EOL;
$uncheckedMentor = User::where('role', 'mentor')
    ->where('name', 'UNCHECKED')
    ->first();

if (!$uncheckedMentor) {
    $uncheckedMentor = User::create([
        'name'     => 'UNCHECKED',
        'email'    => 'unchecked@mentor.com',
        'password' => Hash::make('unchecked@mentor.com'),
        'role'     => 'mentor',
    ]);
    echo "  => Mentor UNCHECKED dibuat dengan ID: " . $uncheckedMentor->id . PHP_EOL;
} else {
    echo "  => Mentor UNCHECKED sudah ada dengan ID: " . $uncheckedMentor->id . PHP_EOL;
}
echo PHP_EOL;

// =============================================
// STEP 3: MAPPING MENTOR NAME => ID
// =============================================
echo "=== STEP 3: MENTOR MAP ===" . PHP_EOL;
$mentorKeywords = [
    'ARI'       => null,
    'EKA'       => null,
    'FARIZA'    => null,
    'FITRIANI'  => null,
    'GHAFFAR'   => null,
    'RISNIAR'   => null,
    'SAKINAH'   => null,
    'SENDI'     => null,
    'UDUR'      => null,
    'UNCHECKED' => $uncheckedMentor->id,
];

$mentors = User::where('role', 'mentor')->get();
foreach ($mentors as $mentor) {
    $nameParts = explode(' ', strtoupper($mentor->name));
    $firstName = $nameParts[0];
    if (array_key_exists($firstName, $mentorKeywords)) {
        $mentorKeywords[$firstName] = $mentor->id;
    }
}

foreach ($mentorKeywords as $key => $id) {
    echo "  $key => " . ($id ?? 'NOT FOUND') . PHP_EOL;
}
echo PHP_EOL;

// =============================================
// STEP 4: DATA PESERTA MAGANG
// Format: [NIM, Nama, Mentor key (atau null = UNCHECKED)]
// =============================================
$interns = [
    ['220906074', 'Agung Prasetyo Siregar', null],
    ['230501011', 'Devi Martauli Pakpahan', 'RISNIAR'],
    ['230501027', 'Dwy Aprianti Sianturi', 'FITRIANI'],
    ['230501028', 'Juin Farel S. Sitanggang', 'SAKINAH'],
    ['230501040', 'Siti Nurhalizah Nasution', 'GHAFFAR'],
    ['230501043', 'Liriska Ningsi Bondar', 'FITRIANI'],
    ['230501063', 'Andreash Jr. Gultom', 'SENDI'],
    ['230501066', 'Putra Rahmad Jais', 'ARI'],
    ['230501084', 'Rumondang Tri Hany', 'SENDI'],
    ['230501090', 'Ari Alfandi Purba', 'GHAFFAR'],
    ['230501111', 'Rachel Agnes Agape Br.simanjuntak', 'ARI'],
    ['230501112', 'Jemima Nathanael Saragih', 'GHAFFAR'],
    ['230501144', 'Atha Nugraha', 'EKA'],
    ['230501149', 'Kavin Samuel Sitio', 'RISNIAR'],
    ['230501155', 'Tiona Risky Octavia Sianipar', 'EKA'],
    ['230501156', 'Grace Annelia Siburian', 'SAKINAH'],
    ['230501163', 'Cristover Vernando Pasaribu', 'SENDI'],
    ['230501171', 'Daffa Manggala Arinta Ginting', 'SENDI'],
    ['230501175', 'Noprengky Sangabta Barus', 'RISNIAR'],
    ['230502214', 'Ayomi Tata Lase', 'FITRIANI'],
    ['230503030', 'Grace Sartika Padang', null],
    ['230503038', 'Novena Ho Abigail', null],
    ['230503057', 'Christina Mutiara Britania Pakpahan', 'EKA'],
    ['230503063', 'Cindy Claudya Simangunsong', 'RISNIAR'],
    ['230503083', 'Maria Bornok Oktaviani Sipahutar', 'EKA'],
    ['230503111', 'Tiffanyia Nahampun', null],
    ['230503116', 'Revelina Situmorang', null],
    ['230503125', 'Joy Ende Kanon Sitorus', null],
    ['230503133', 'Aurora Hannah Ariana Hutapea', null],
    ['230503147', 'Yousev Daniel Gery Pangihutan Lumbantobing', 'GHAFFAR'],
    ['230503172', 'Marissa Libry Patricia Siburian', null],
    ['230503174', 'Bremana Tia Lukas Sitepu', 'SENDI'],
    ['230503190', 'Yoan Novelin Basania Br Hutagaol', null],
    ['230503213', 'Handika Parulian Tampubolon', 'SENDI'],
    ['230503214', 'Grace Caroline Marpaung', null],
    ['230503217', 'Yohanes Dwi Putra Gultom', 'SAKINAH'],
    ['230503224', 'Imanta Pilipi Masaro Surbakti', 'GHAFFAR'],
    ['230503227', 'Obrient Sembiring', 'SAKINAH'],
    ['230701011', 'Widya Kartika', 'SAKINAH'],
    ['230701015', 'Andika Mayasari Simanullang', 'EKA'],
    ['230701017', 'Cindy Rika Maretha Simatupang', 'FITRIANI'],
    ['230701020', 'Marniman Jemila Zalukhu', 'ARI'],
    ['230701037', 'Yola Juni Artika', 'FARIZA'],
    ['230701040', 'Nabila Aulia', 'FARIZA'],
    ['230701043', 'Winny Stefanie', 'EKA'],
    ['230701048', 'Mitha Permata Sari', 'RISNIAR'],
    ['230701049', 'Adila Yasmin Aifa Panjaitan', 'ARI'],
    ['230701052', 'Rosi Adelia', 'FITRIANI'],
    ['230701056', 'Donnasari Fransiska Br Panggabean', 'EKA'],
    ['230701076', 'Salsabila Ramadhani Napitupulu', 'EKA'],
    ['230701080', 'Rosa Ayulia', 'FARIZA'],
    ['230705010', 'Ibnati Faiza Hamdi', 'SAKINAH'],
    ['230705081', 'Vera Aprilia Putri', 'SENDI'],
    ['230705105', 'Elizabeth Yoshelita Sumbayak', 'RISNIAR'],
    ['230705107', 'Uswatun Hasanah Lubis', 'FARIZA'],
    ['230705155', 'Chesya Elga Dayanti Purba', 'SENDI'],
    ['230705162', 'Imelda Indry Arta Raja Guk Guk', null],
    ['230706030', 'Elvrida Sianturi', 'FITRIANI'],
    ['230706034', 'Sry Maghdalena Tambun', 'FITRIANI'],
    ['230706036', 'Luna Devina Sitanggang', 'ARI'],
    ['230706053', 'Enjelina Risnawati Lubis', 'RISNIAR'],
    ['230706055', 'Putri Amanda Viola Siregar', 'SAKINAH'],
    ['230706081', 'Salda Laura Simalango', null],
    ['230901043', 'Nur Nimsih Tiominar Sidabutar', 'EKA'],
    ['230901045', 'Hanifa Raslia Fitri', 'SENDI'],
    ['230901061', 'Mey Hana Rameuli Panjaitan', 'SENDI'],
    ['230901073', 'Tessalonika N. Purba', 'ARI'],
    ['230902012', 'Khairunnisa Nabila Syahputri', 'GHAFFAR'],
    ['230902046', 'Jihan Hafiza Batubara', 'GHAFFAR'],
    ['230902054', 'Kania Nadine Rahel Simatupang', 'FITRIANI'],
    ['230903008', 'Syahirah Azzahra', null],
    ['230906037', 'Egi Yoselisa Rigwina Br Tarigan', 'GHAFFAR'],
    ['230906084', 'Dhana Taruli Siallagan', 'SENDI'],
    ['230906095', 'Mutiara Yohanna Aprilia Bangun', null],
    ['231401114', 'Aryasatya Wicaksana', null],
    ['231402002', 'Ahmad Rozan Raufansyah Nasution', null],
    ['231402053', 'Prayer Ezekiel Levy Sitepu', null],
    ['231402056', 'Samuel Gautama Manik', null],
    ['231402059', 'Tama Hagana Perangin-angin', null],
    ['231402127', 'Zahra Naziha Parinduri', null],
    ['240705081', 'Tria Amelia Surbakti', 'GHAFFAR'],
    ['240705095', 'Sarah Anastasia', 'EKA'],
    ['240705100', 'Suryani Praysiska Silalahi', 'GHAFFAR'],
    ['240705117', 'Agnes Dhea Oktavia Sinaga', 'FITRIANI'],
    ['240705123', 'Zsa Zsa Adelia Attaqiyyah', null],
    ['240705152', 'Andyra Talitha Hadisty', 'SENDI'],
    ['240705170', 'Misel Ananta Simamora', 'EKA'],
    ['241000055', 'Sofina Zuhra', 'FARIZA'],
    ['241000276', 'Nabilah Putri Ariza', null],
    ['241708007', 'Emya Agi Estefanie Br Sembiring', null],
    ['241708010', 'Putri Novelina Purba', null],
    ['241708016', 'Fenni Wilfrida Simanungkalit', null],
    ['241708023', 'Nazwa Amelia', null],
    ['241708025', 'Izqi Dhan Uznury Sitorus', null],
    ['241712006', 'Parida Lubis', 'RISNIAR'],
    ['241712008', 'Melati Simanungkalit', null],
    ['241712011', 'Ririn Margaretha Simanjuntak', null],
    ['241712012', 'Ruth Angelia Sihombing', 'SENDI'],
    ['241712021', 'Ivana Kristina Br.siagian', 'SAKINAH'],
    ['241712056', 'Muhammad Rizky Pramadhana Lubis', null],
    ['241712059', 'Febiola Kristin Aruan', 'RISNIAR'],
    ['241712076', 'Vicensyah Octavia Siagian', null],
    ['241712089', 'Joseph Joel Sinaga', null],
    ['230503142', 'Marnala Sonia Theresia Tambunan', 'SAKINAH'],
    ['230906067', 'Meisyeila Napitupulu', 'ARI'],
    ['231402096', 'Alfathin Suwailim', 'GHAFFAR'],
    ['230501013', 'Yuni Marito Rumapea', 'FITRIANI'],
    ['230501019', 'Zahra Amelia', 'FARIZA'],
    ['230501050', 'Bunga Lorenza', 'FITRIANI'],
    ['230501055', 'Roland Rafael Lbn Batu', 'ARI'],
    ['230501076', 'Rahmayanti', 'GHAFFAR'],
    ['230501094', 'Petra Immanuel Sitepu', 'RISNIAR'],
    ['230501095', 'Debbyana Limbong', 'SAKINAH'],
    ['230501105', 'Muhammad Sazzaly', 'ARI'],
    ['230501123', 'Pandapotan Jultribowo Purba', 'RISNIAR'],
    ['230501141', 'Armelia Perangin Angin', 'GHAFFAR'],
    ['230501143', 'Rendy Bungsu Martua Siahaan', 'EKA'],
    ['230501146', 'Novriani Turnip', 'RISNIAR'],
    ['230501150', 'Agnes Monika Br Sembiring Maha', 'ARI'],
    ['230501153', 'Muhd. Taufik', 'ARI'],
    ['230501158', 'Alde Barent Napitupulu', 'ARI'],
    ['230501173', 'Tom Willy Julius', 'FITRIANI'],
    ['230502037', 'Haikal Muhammad Husein', 'FARIZA'],
    ['230502067', 'Yunita Feryanti Sijabat', 'RISNIAR'],
    ['230502138', 'Rehan Geofani Sitanggang', 'GHAFFAR'],
    ['230502177', 'Rudi Ebenhaizer Sianturi', 'EKA'],
    ['230502206', 'Marulitua Simangunsong', 'EKA'],
    ['230502208', 'Luygi Alfido Denza Sihombing', 'RISNIAR'],
    ['230503015', 'Syamsiah Marito Tambunan', null],
    ['230503016', 'Najua', 'GHAFFAR'],
    ['230503017', 'Rasya Adella Sukma', 'ARI'],
    ['230503037', 'Putri Dianeta Sinaga', 'SAKINAH'],
    ['230503051', 'Rachel Chaterine Danielle Hutagalung', 'FARIZA'],
    ['230503070', 'Arya A.r Nababan', 'RISNIAR'],
    ['230503119', 'Stiivo Johannes Sitompul', 'RISNIAR'],
    ['230503129', 'Intan Febriana Manalu', 'SENDI'],
    ['230503138', 'Yoseline Abelia Nainggolan', null],
    ['230503193', 'Dwi Cindy Setya Ningsih Ambarita', 'SAKINAH'],
    ['230503197', 'Lestari Valentsya Margaretha Br Simamora', 'ARI'],
    ['230503201', 'Kalista Estefany Br Tarigan', null],
    ['230503218', 'Neny Triana Sianturi', 'EKA'],
    ['230701051', 'Achnes Julianti Samosir', 'SENDI'],
    ['230705057', 'Joyce Trinita Simorangkir', null],
    ['230803001', 'Adinda Nayla', 'EKA'],
    ['230803039', 'Laura Eli Juliana Br . Simanjuntak', null],
    ['230901031', 'Epi Multisari Gea', 'SAKINAH'],
    ['230901035', 'Ersi Susi Dewi Saragih', 'RISNIAR'],
    ['230901057', 'Yulisari Olivia Manihuruk', 'RISNIAR'],
    ['230901071', 'Oliva Hotmaulina Sitanggang', 'FARIZA'],
    ['230901091', 'Syakila Jantina Lubis', 'FARIZA'],
    ['230906002', 'Tia Anissa Rahma', 'EKA'],
    ['230906004', 'Chairunnisa', 'FARIZA'],
    ['230906016', 'Martha Antika Sinambela', 'FARIZA'],
    ['230906020', 'Armisari', 'SAKINAH'],
    ['230906024', 'Vera Christanty Sinaga', 'ARI'],
    ['230906032', 'Christian Abed Nego', 'FITRIANI'],
    ['230906038', 'Nabila Syahwa', 'GHAFFAR'],
    ['230906040', 'Angelica Maria Br Naiborhu', 'SAKINAH'],
    ['230906048', 'Rafael Batista Sihombing', 'EKA'],
    ['230906054', 'Kevin Pakpahan', null],
    ['230906060', 'Farid Abrar Batubara', 'FARIZA'],
    ['230906066', 'Juahta Soladeo Ginting', 'FARIZA'],
    ['230906069', 'Arkan Rafid Algahzy', 'GHAFFAR'],
    ['230906076', 'Prayoga Kandou', 'GHAFFAR'],
    ['230906082', 'Romian Arta Gurning', 'FITRIANI'],
    ['230906086', 'Nasya', 'SENDI'],
    ['230906094', 'David Parningotan Tumanggor', 'SAKINAH'],
    ['231301150', 'Wira Prakasa', 'SAKINAH'],
    ['231301219', 'Ayyesha Hervi Ananda', 'SAKINAH'],
    ['231401103', 'Michael Valent Satrio Munthe', 'FITRIANI'],
    ['231401136', 'Kevin Swandana', null],
    ['231402010', 'Jeremia Hasudungan Sitinjak', null],
    ['231402012', 'Yenni Theresia Zalukhu', null],
    ['231402013', 'Euprasia Enjelika Situmorang', null],
    ['231402016', 'Zalfa\'a Zahirah', 'ARI'],
    ['231402067', 'Rizky Valentino Siahaan', 'EKA'],
    ['231402106', 'Muhammad Ilyas Hasibuan', 'FARIZA'],
    ['231402123', 'Nayla Syifa', null],
    ['231402132', 'Elvan Yohanes Gultom', null],
    ['240200042', 'Arani Gadri Khaila', 'GHAFFAR'],
    ['240200388', 'Berlian Arta Melvina Purba', 'FITRIANI'],
    ['240200410', 'Alya Mukhbita Arifin', 'FARIZA'],
    ['240200460', 'Thierry Fidel Chiuloto', 'FARIZA'],
    ['240705056', 'Syarifah Sarah Nurjihan', 'ARI'],
    ['240705134', 'Zaskia Naura Rianto', 'ARI'],
    ['240705140', 'Novalini Annisa Fitri', null],
    ['240705147', 'Mumtaz Naurah', 'FARIZA'],
    ['240705165', 'Nazira Ade Ifana Br Harahap', 'FITRIANI'],
    ['240905075', 'Bunga Mulia Rendana', 'RISNIAR'],
    ['240907042', 'Hage Hanyta Sembiring', 'SAKINAH'],
    ['241705042', 'Mouren Angelica Tarida Siburian', 'FARIZA'],
    ['241705048', 'Naomi Gilbertine Nainggolan', 'FITRIANI'],
    ['241706045', 'Rahellina Dameria Hutagalung', 'SENDI'],
    ['241706062', 'Joe Harlen Siahaan', 'SENDI'],
    ['241712004', 'Rima Nazwa', null],
    ['241712013', 'Aditya Fahreza', 'FITRIANI'],
    ['241712028', 'Argya Ariella', 'SENDI'],
    ['241712078', 'Fadil Iyad Rofid', 'ARI'],
    ['220703019', 'Alpiani Lubis', 'UDUR'],
    ['230501005', 'Bynnu Putri Deeno', 'UDUR'],
    ['230501034', 'Ofly Nesiamah Manurung', null],
    ['230501099', 'Sisilia Leowandi', 'UDUR'],
    ['230903035', 'Nabila Azzahra', 'UDUR'],
    ['230906006', 'Azura Syahfika', null],
    ['230906017', 'Divya Lauretta Sembiring', null],
    ['230906018', 'Nabila Syakila', 'FARIZA'],
    ['230906033', 'Syarifah Najla Firiza', 'UDUR'],
    ['230906036', 'Annisa Azzahra', 'UDUR'],
    ['230906053', 'Putri Aqilah', 'FARIZA'],
    ['230906056', 'Fery Mhd Solin', 'UDUR'],
    ['230906058', 'Paulus Immanuel Manik', 'UDUR'],
    ['230906068', 'Arfin R. Hakim Daulay', 'UDUR'],
    ['230906088', 'M Luthfi Ramdhan As', 'FARIZA'],
    ['231402026', 'Adelia Sakinah Pane', null],
    ['240705041', 'Naila Hendiana', 'FARIZA'],
    ['241708021', 'Herlina Silvia Manurung', null],
    ['241708031', 'Vina Cicilia Camelia Br Sebayang', null],
];

// =============================================
// STEP 4: DAFTARKAN PESERTA
// =============================================
echo "=== STEP 4: DAFTARKAN PESERTA MAGANG ===" . PHP_EOL;

$created = 0;
$errors  = [];

foreach ($interns as $internData) {
    [$nim, $name, $mentorKey] = $internData;

    // Jika mentor null → pakai UNCHECKED
    $resolvedKey = $mentorKey ?? 'UNCHECKED';
    $mentorId    = $mentorKeywords[$resolvedKey] ?? null;

    if ($mentorId === null) {
        $errors[] = "[$nim] $name: Mentor '$resolvedKey' tidak ditemukan!";
        echo "[ERROR]  NIM $nim | $name: Mentor '$resolvedKey' tidak ditemukan!" . PHP_EOL;
        continue;
    }

    $email = strtolower($nim) . '@intern.bpjs.com';

    try {
        User::create([
            'name'      => $name,
            'email'     => $email,
            'nim'       => $nim,
            'password'  => Hash::make($nim),
            'role'      => 'intern',
            'mentor_id' => $mentorId,
        ]);
        echo "[OK]     NIM $nim | $name | Mentor: $resolvedKey" . PHP_EOL;
        $created++;
    } catch (\Exception $e) {
        $errors[] = "[$nim] $name: " . $e->getMessage();
        echo "[ERROR]  NIM $nim | $name: " . $e->getMessage() . PHP_EOL;
    }
}

echo PHP_EOL;
echo "==================================================" . PHP_EOL;
echo "SELESAI!" . PHP_EOL;
echo "  Berhasil dibuat : $created akun" . PHP_EOL;
echo "  Error           : " . count($errors) . " akun" . PHP_EOL;

if (!empty($errors)) {
    echo PHP_EOL . "=== DAFTAR ERROR ===" . PHP_EOL;
    foreach ($errors as $err) {
        echo "  - $err" . PHP_EOL;
    }
}
echo "==================================================" . PHP_EOL;
