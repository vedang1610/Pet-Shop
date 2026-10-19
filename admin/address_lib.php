<?php
// address_lib.php - customers' saved delivery addresses (used by the cart checkout).
// Expects $con (mysqli) from db.php.

function addressStates()
{
    return array(
        "Andaman and Nicobar Islands", "Andhra Pradesh", "Arunachal Pradesh", "Assam", "Bihar", "Chandigarh", "Chhattisgarh",
        "Dadra and Nagar Haveli and Daman and Diu", "Delhi", "Goa", "Gujarat", "Haryana", "Himachal Pradesh", "Jammu and Kashmir",
        "Jharkhand", "Karnataka", "Kerala", "Ladakh", "Lakshadweep", "Madhya Pradesh", "Maharashtra", "Manipur", "Meghalaya",
        "Mizoram", "Nagaland", "Odisha", "Puducherry", "Punjab", "Rajasthan", "Sikkim", "Tamil Nadu", "Telangana", "Tripura",
        "Uttar Pradesh", "Uttarakhand", "West Bengal",
    );
}

// Where the shop ships from, and how many days delivery usually takes. Change these to match your shop.
const SHOP_DISTRICT = 'Vadodara';
const SHOP_STATE    = 'Gujarat';
const DELIVERY_DAYS = array(
    'local' => array(1, 2),   // same district as the shop
    'state' => array(2, 4),   // same state
    'india' => array(4, 7),   // rest of India
);

// Estimated delivery window for an address, e.g. "Delivery by Thu, 23 Oct - Sat, 25 Oct"
function addressDeliveryEstimate($district, $state)
{
    if (strcasecmp(trim($district), SHOP_DISTRICT) === 0 && strcasecmp(trim($state), SHOP_STATE) === 0) {
        $zone = 'local';
        $label = 'Local delivery';
    } else if (strcasecmp(trim($state), SHOP_STATE) === 0) {
        $zone = 'state';
        $label = 'Within ' . SHOP_STATE;
    } else {
        $zone = 'india';
        $label = 'Standard delivery';
    }
    list($min, $max) = DELIVERY_DAYS[$zone];
    $from = new DateTime('today');
    $to = new DateTime('today');
    $from->modify("+$min day");
    $to->modify("+$max day");
    return array(
        'zone'  => $zone,
        'label' => $label,
        'min'   => $min,
        'max'   => $max,
        'from'  => $from->format('D, j M'),
        'to'    => $to->format('D, j M'),
        'text'  => $min === $max ? "Delivery by " . $to->format('D, j M') : "Delivery by " . $from->format('D, j M') . " - " . $to->format('D, j M'),
    );
}

function addressEnsureTable($con)
{
    mysqli_query($con, "CREATE TABLE IF NOT EXISTS `user_addresses` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `full_name` varchar(100) NOT NULL,
        `phone` varchar(15) NOT NULL,
        `alt_phone` varchar(15) DEFAULT NULL,
        `pincode` varchar(6) NOT NULL,
        `locality` varchar(150) NOT NULL,
        `address_line` varchar(255) NOT NULL,
        `city` varchar(100) NOT NULL,
        `state` varchar(60) NOT NULL,
        `landmark` varchar(150) DEFAULT NULL,
        `address_type` varchar(10) NOT NULL DEFAULT 'home',
        `created_at` datetime NOT NULL DEFAULT current_timestamp(),
        `last_used_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `by_user` (`user_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

// The customer's addresses, most recently used first
function addressList($con, $userId)
{
    $userId = (int)$userId;
    $stmt = mysqli_prepare($con, "SELECT * FROM `user_addresses` WHERE user_id = ? ORDER BY COALESCE(last_used_at, created_at) DESC, id DESC");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $rows = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $rows;
}

// One address, only if it belongs to this customer
function addressFind($con, $userId, $addressId)
{
    $userId = (int)$userId;
    $addressId = (int)$addressId;
    $stmt = mysqli_prepare($con, "SELECT * FROM `user_addresses` WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $addressId, $userId);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $row ? $row : null;
}

// Reads and checks the "new address" form. Returns array($address, $errors).
function addressFromPost($post)
{
    $get = function ($key, $max) use ($post) {
        $value = isset($post[$key]) ? trim(preg_replace('/\s+/', ' ', (string)$post[$key])) : '';
        return mb_substr($value, 0, $max);
    };
    $a = array(
        'full_name'    => $get('addrName', 100),
        'phone'        => preg_replace('/\D/', '', $get('addrPhone', 20)),
        'alt_phone'    => preg_replace('/\D/', '', $get('addrAltPhone', 20)),
        'pincode'      => preg_replace('/\D/', '', $get('addrPincode', 10)),
        'locality'     => $get('addrLocality', 150),
        'address_line' => $get('addrLine', 255),
        'city'         => $get('addrCity', 100),
        'state'        => $get('addrState', 60),
        'landmark'     => $get('addrLandmark', 150),
        'address_type' => (isset($post['addrType']) && $post['addrType'] === 'work') ? 'work' : 'home',
    );
    // Accept +91 / 0 in front of mobile numbers
    foreach (array('phone', 'alt_phone') as $field) {
        if (strlen($a[$field]) > 10) $a[$field] = substr($a[$field], -10);
    }

    $errors = array();
    if (mb_strlen($a['full_name']) < 2) $errors[] = "Please enter the full name.";
    if (!preg_match('/^[6-9]\d{9}$/', $a['phone'])) $errors[] = "Please enter a valid 10-digit mobile number.";
    if ($a['alt_phone'] !== '' && !preg_match('/^[6-9]\d{9}$/', $a['alt_phone'])) $errors[] = "The alternate phone number isn't valid.";
    if (!preg_match('/^[1-9]\d{5}$/', $a['pincode'])) $errors[] = "Please enter a valid 6-digit PIN code.";
    if (mb_strlen($a['locality']) < 2) $errors[] = "Please enter the locality.";
    if (mb_strlen($a['address_line']) < 5) $errors[] = "Please enter the house number, building and street.";
    if (mb_strlen($a['city']) < 2) $errors[] = "Please enter the city or district.";
    if (!in_array($a['state'], addressStates(), true)) $errors[] = "Please choose a state.";
    return array($a, $errors);
}

function addressSave($con, $userId, $a)
{
    $userId = (int)$userId;
    $alt = $a['alt_phone'] !== '' ? $a['alt_phone'] : null;
    $landmark = $a['landmark'] !== '' ? $a['landmark'] : null;
    $stmt = mysqli_prepare($con, "INSERT INTO `user_addresses` (user_id, full_name, phone, alt_phone, pincode, locality, address_line, city, state, landmark, address_type, last_used_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    mysqli_stmt_bind_param($stmt, "issssssssss", $userId, $a['full_name'], $a['phone'], $alt, $a['pincode'], $a['locality'], $a['address_line'], $a['city'], $a['state'], $landmark, $a['address_type']);
    mysqli_stmt_execute($stmt);
    $id = mysqli_insert_id($con);
    mysqli_stmt_close($stmt);
    return $id;
}

function addressTouch($con, $addressId)
{
    $addressId = (int)$addressId;
    mysqli_query($con, "UPDATE `user_addresses` SET last_used_at = NOW() WHERE id = $addressId");
}

function addressDelete($con, $userId, $addressId)
{
    $userId = (int)$userId;
    $addressId = (int)$addressId;
    $stmt = mysqli_prepare($con, "DELETE FROM `user_addresses` WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $addressId, $userId);
    mysqli_stmt_execute($stmt);
    $deleted = mysqli_stmt_affected_rows($stmt) === 1;
    mysqli_stmt_close($stmt);
    return $deleted;
}

// The address as it's saved on the order (shown on order pages, admin and invoice)
function addressFormat($a)
{
    $lines = array();
    $lines[] = $a['full_name'] . " (" . ($a['address_type'] === 'work' ? 'Work' : 'Home') . ")";
    $lines[] = $a['address_line'] . ", " . $a['locality'];
    if (!empty($a['landmark'])) $lines[] = "Landmark: " . $a['landmark'];
    $lines[] = $a['city'] . ", " . $a['state'] . " - " . $a['pincode'];
    $lines[] = "Phone: " . $a['phone'] . (!empty($a['alt_phone']) ? ", Alternate: " . $a['alt_phone'] : "");
    return implode("\n", $lines);
}
