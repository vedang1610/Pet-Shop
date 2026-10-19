<?php
// password_reset_lib.php - shared code for forgotPassword.php and resetPassword.php.
// Customers prove it's their account with their registered email + phone number.
// The admin account (no phone number) uses its email + the recovery key in recovery_config.php.
// Expects $con (mysqli) from db.php and an active session.

const RESET_WINDOW_MINUTES   = 15;  // failed tries are counted over this window
const RESET_MAX_FAILS_EMAIL  = 5;   // wrong tries allowed per email in the window
const RESET_MAX_FAILS_IP     = 20;  // wrong tries allowed per computer in the window
const RESET_VERIFIED_MINUTES = 10;  // time to choose a new password after verifying

function resetAccountType($value)
{
    return $value === 'admin' ? 'admin' : 'user';
}

function resetTable($type)
{
    return $type === 'admin' ? 'admin' : 'users';
}

function resetLoginPage($type)
{
    return $type === 'admin' ? 'index.php' : 'userLogin.php';
}

function resetClientIp()
{
    return isset($_SERVER['REMOTE_ADDR']) ? substr($_SERVER['REMOTE_ADDR'], 0, 45) : '';
}

function resetEnsureTable($con)
{
    mysqli_query($con, "CREATE TABLE IF NOT EXISTS `password_reset_attempts` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `account_type` varchar(10) NOT NULL,
        `email` varchar(100) NOT NULL,
        `ip` varchar(45) NOT NULL,
        `success` tinyint(1) NOT NULL DEFAULT 0,
        `created_at` datetime NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        KEY `by_email` (`account_type`, `email`, `created_at`),
        KEY `by_ip` (`ip`, `created_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

// True when this email or this computer has had too many wrong tries recently
function resetIsLocked($con, $type, $email)
{
    $ip = resetClientIp();
    $stmt = mysqli_prepare($con, "SELECT
            SUM(account_type = ? AND email = ?) AS byEmail,
            SUM(ip = ?) AS byIp
        FROM password_reset_attempts
        WHERE success = 0 AND created_at > (NOW() - INTERVAL " . RESET_WINDOW_MINUTES . " MINUTE)");
    mysqli_stmt_bind_param($stmt, "sss", $type, $email, $ip);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return (int)$row['byEmail'] >= RESET_MAX_FAILS_EMAIL || (int)$row['byIp'] >= RESET_MAX_FAILS_IP;
}

function resetLogAttempt($con, $type, $email, $success)
{
    $ip = resetClientIp();
    $ok = $success ? 1 : 0;
    $stmt = mysqli_prepare($con, "INSERT INTO password_reset_attempts (account_type, email, ip, success) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssi", $type, $email, $ip, $ok);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Only the last 10 digits count, so "+91 98765 43210" matches "9876543210"
function resetNormalisePhone($phone)
{
    $digits = preg_replace('/\D/', '', (string)$phone);
    return strlen($digits) > 10 ? substr($digits, -10) : $digits;
}

// Checks the details. $proof is the phone number (customers) or the recovery key (admin).
function resetVerify($con, $type, $email, $proof)
{
    if ($type === 'admin')
    {
        // recovery_config.php is kept out of git; copy recovery_config.example.php to create it
        $configFile = __DIR__ . '/recovery_config.php';
        $config = file_exists($configFile) ? require $configFile : array();
        $key = isset($config['admin_recovery_key']) ? trim($config['admin_recovery_key']) : '';
        $stmt = mysqli_prepare($con, "SELECT id FROM `admin` WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $found = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) !== null;
        mysqli_stmt_close($stmt);
        return $found && strlen($key) >= 8 && stripos($key, 'CHANGE-ME') !== 0 && hash_equals(strtoupper($key), strtoupper(trim($proof)));
    }

    $stmt = mysqli_prepare($con, "SELECT phone FROM `users` WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $given = resetNormalisePhone($proof);
    $match = false;
    while ($row = mysqli_fetch_assoc($result))
    {
        $stored = resetNormalisePhone($row['phone']);
        if (strlen($given) >= 10 && strlen($stored) >= 10 && hash_equals($stored, $given))
        {
            $match = true;
        }
    }
    mysqli_stmt_close($stmt);
    return $match;
}

// Saves the new password. The login pages compare `pass` as stored, so it is stored the same way.
function resetSavePassword($con, $type, $email, $newPassword)
{
    $table = resetTable($type);
    $stmt = mysqli_prepare($con, "UPDATE `$table` SET pass = ? WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "ss", $newPassword, $email);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

// ---------- "verified" state between the two pages ----------
function resetMarkVerified($type, $email)
{
    session_regenerate_id(true);
    $_SESSION['reset_verified'] = array('type' => $type, 'email' => $email, 'until' => time() + RESET_VERIFIED_MINUTES * 60);
}

function resetVerifiedFor($type)
{
    $v = isset($_SESSION['reset_verified']) ? $_SESSION['reset_verified'] : null;
    if (!$v || $v['type'] !== $type || $v['until'] < time())
    {
        return null;
    }
    return $v['email'];
}

function resetClearVerified()
{
    unset($_SESSION['reset_verified'], $_SESSION['reset_csrf']);
}

// ---------- CSRF token for the forms ----------
function resetCsrfToken()
{
    if (empty($_SESSION['reset_csrf'])) {
        $_SESSION['reset_csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['reset_csrf'];
}

function resetCsrfValid($token)
{
    return !empty($_SESSION['reset_csrf']) && is_string($token) && hash_equals($_SESSION['reset_csrf'], $token);
}
