<?php
// pincode_lookup.php - details for a PIN code, used by the delivery address form in the cart.
// GET ?pin=390007  ->  JSON: city/district, state, areas covered, and an estimated delivery window.
// Looks the PIN up with India Post (api.postalpincode.in) and caches the answer for 30 days.
session_start();
include('db.php');
include_once('address_lib.php');
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: private, max-age=3600');

if (!isset($_SESSION['userId'])) {
    http_response_code(401);
    echo json_encode(array('ok' => false, 'message' => 'Please log in.'));
    exit;
}

$pin = isset($_GET['pin']) ? preg_replace('/\D/', '', $_GET['pin']) : '';
if (!preg_match('/^[1-9]\d{5}$/', $pin)) {
    echo json_encode(array('ok' => false, 'message' => 'Please enter a valid 6-digit PIN code.'));
    exit;
}

mysqli_query($con, "CREATE TABLE IF NOT EXISTS `pincode_cache` (
    `pincode` char(6) NOT NULL,
    `found` tinyint(1) NOT NULL,
    `data` text NOT NULL,
    `fetched_at` datetime NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`pincode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// 1. Cache
$cached = null;
$stmt = mysqli_prepare($con, "SELECT found, data FROM `pincode_cache` WHERE pincode = ? AND fetched_at > (NOW() - INTERVAL 30 DAY)");
mysqli_stmt_bind_param($stmt, "s", $pin);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);
if ($row) {
    $cached = array('found' => (bool)$row['found'], 'offices' => json_decode($row['data'], true));
}

// 2. India Post
if ($cached === null) {
    $context = stream_context_create(array('http' => array('timeout' => 8, 'header' => "User-Agent: PetShop/1.0\r\n")));
    $response = @file_get_contents("https://api.postalpincode.in/pincode/" . $pin, false, $context);
    $decoded = $response !== false ? json_decode($response, true) : null;
    if (!is_array($decoded) || !isset($decoded[0]['Status'])) {
        echo json_encode(array('ok' => false, 'unavailable' => true, 'message' => "We couldn't check this PIN code right now. You can still fill in the city and state."));
        exit;
    }
    $offices = array();
    if ($decoded[0]['Status'] === 'Success' && !empty($decoded[0]['PostOffice'])) {
        foreach ($decoded[0]['PostOffice'] as $office) {
            $offices[] = array(
                'name'     => $office['Name'],
                'district' => $office['District'],
                'state'    => $office['State'],
                'division' => $office['Division'],
                'block'    => $office['Block'],
                'delivery' => $office['DeliveryStatus'],
            );
        }
    }
    $cached = array('found' => count($offices) > 0, 'offices' => $offices);
    $found = $cached['found'] ? 1 : 0;
    $json = json_encode($offices);
    $stmt = mysqli_prepare($con, "REPLACE INTO `pincode_cache` (pincode, found, data, fetched_at) VALUES (?, ?, ?, NOW())");
    mysqli_stmt_bind_param($stmt, "sis", $pin, $found, $json);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

if (!$cached['found']) {
    echo json_encode(array('ok' => false, 'message' => "We couldn't find PIN code $pin. Please check it."));
    exit;
}

// 3. Build the answer
$offices = $cached['offices'];
$first = $offices[0];

// Match India Post's state name to our list (e.g. "DELHI" / "Delhi")
$state = $first['state'];
foreach (addressStates() as $known) {
    if (strcasecmp($known, $state) === 0) { $state = $known; }
}

$areas = array();
foreach ($offices as $office) {
    $areas[trim(preg_replace('/\s+/', ' ', $office['name']))] = true;
}
$areas = array_keys($areas);
sort($areas, SORT_NATURAL | SORT_FLAG_CASE);

echo json_encode(array(
    'ok'       => true,
    'pincode'  => $pin,
    'city'     => $first['district'],
    'district' => $first['district'],
    'state'    => $state,
    'division' => $first['division'],
    'areas'    => $areas,
    'delivery' => addressDeliveryEstimate($first['district'], $state),
));
