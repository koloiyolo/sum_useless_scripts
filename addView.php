<?php


// made with wordpress in mind
// to track unique user count on website that nobody even visits
// uses mysqli


function addView() {

    // Define your database credentials
    $servername = 'host';
    $username = 'user';
    $password = 'pass';

    // Define blacklisted IP address ranges
    $blacklistedSubnet = '192.168.1.0'; 
    $subnetMask = '255.255.255.0';     
    
    $compared = $_SERVER['REMOTE_ADDR'];
    
    if (isBlackListed($compared, $blacklistedSubnet, $subnetMask)) {
        if (!isset($_COOKIE['visitor_id'])) {
            $visitorID = generateUniqueID();
            $expirationTime = time() + (x * 24 * 60 * 60); // x days in seconds
            setcookie('visitor_id', $visitorID, $expirationTime, '/');
            incrementDbVal($servername, $username, $password);
        }
    }
}

function isBlacklisted($ip, $blacklistedSubnet, $subnetMask) {
    $ipParts = explode('.', $ip);
    $subnetParts = explode('.', $blacklistedSubnet);
    $maskParts = explode('.', $subnetMask);

    for ($i = 0; $i < 4; $i++) {
        $subnetValue = (int)$subnetParts[$i] & (int)$maskParts[$i];
        $ipValue = (int)$ipParts[$i] & (int)$maskParts[$i];

        if ($subnetValue != $ipValue) {
            return false; 
        }
    }

    return true; 
}

function generateUniqueID() {
    return md5(uniqid(rand(), true));
}

function incrementDbVal($servername, $username, $password) {
    $conn = new mysqli($servername, $username, $password);

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    try {
        $sql = 'SELECT count FROM wp_views';
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $count = (int)$row['count'];
        } else {
            $count = 0;
        }
    } catch (Exception $e) {
        $sql = 'CREATE TABLE wp_views (count INT)';
        $conn->query($sql);
        $count = 0;
    } finally {
        $sql = "UPDATE wp_views SET count = count + 1";

        if ($conn->query($sql) === TRUE) {
            // Update successful
        } else {
            // Update unsuccessful
            return
        }
    }

    $conn->close();
}

/* use example

<?php
...
addView();
...
?>

*/
?>
