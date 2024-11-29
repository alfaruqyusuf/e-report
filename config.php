
<?php


$con = mysqli_connect("localhost", "root", "", "pengaduan_masyarakat");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

function generateToken($user_id)
{
    global $con;
    try {
        $token = bin2hex(random_bytes(32));
        $expired = date("Y-m-d H:i:s", time() + (60 * 60));
        $stmt = $con->prepare("INSERT INTO tokens (token, expired, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $token, $expired, $user_id);
        if ($stmt->execute()) {
            return $token;
        } else {
            return null;
        }
    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $e->getMessage();
        return null;
    }
}

function validateToken($token)
{
    global $con;
    try {
        $stmt = $con->prepare("SELECT * FROM tokens WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (strtotime($row['expired']) > time()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function isAdmin($token) {
    global $con;
    try {
        $stmt = $con->prepare("SELECT role FROM tokens JOIN users ON users.id = tokens.user_id WHERE tokens.user_id = users.id AND tokens.token = ?");
        $stmt = $con->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['role'] == 'admin') {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


function getUser($token) {
    global $con;
    
    $stmt = $con->prepare("SELECT users.id, username, email, age, profile_picture, role 
                          FROM tokens 
                          JOIN users ON users.id = tokens.user_id 
                          WHERE tokens.user_id = users.id 
                          AND tokens.token = ?");
    
    $stmt->bind_param('s', $token);
    
    if ($stmt->execute()) {
        $user = $stmt->get_result()->fetch_assoc();
        return $user;
    }
    
    return null;
}

?>
