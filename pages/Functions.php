<?php
function connect($host='localhost', $user='root', $pass='', $dbname='TouristAgency')
{
    $mysqli = new mysqli($host, $user, $pass, $dbname);
    if ($mysqli->connect_errno) {
        die('Error connection: ' . $mysqli->connect_error);
    }
    $mysqli->set_charset('utf8');
    return $mysqli;
}

function register($name, $pass, $pass2, $email)
{
    $name = trim(htmlspecialchars($name));
    $pass = trim(htmlspecialchars($pass));
    $pass2 = trim(htmlspecialchars($pass2));
    $email = trim(htmlspecialchars($email));

    if (empty($name) || empty($pass) || empty($pass2) || empty($email)) {
        echo "<h3><span style='color:red;'>Fill All Required Fields!</span></h3>";
        return false;
    }

    if (strlen($name) < 6 || strlen($name) > 30 || strlen($pass) < 6 || strlen($pass) > 30) {
        echo "<h3><span style='color:red;'>Values Length Must Be Between 6 And 30!</span></h3>";
        return false;
    }

    if ($pass !== $pass2) {
        echo "<h3><span style='color:red;'>Passwords Do Not Match!</span></h3>";
        return false;
    }

    $mysqli = connect();
    $hashedPass = password_hash($pass, PASSWORD_BCRYPT);

    $stmt = $mysqli->prepare('INSERT INTO users (login, pass, email, roleid) VALUES (?, ?, ?, 2)');
    $stmt->bind_param('sss', $name, $hashedPass, $email);

    if (!$stmt->execute()) {
        if ($stmt->errno == 1062) {
            echo "<h3><span style='color:red;'>This Login Is Already Taken!</span></h3>";
        } else {
            echo "<h3><span style='color:red;'>Error: " . $stmt->error . "</span></h3>";
        }
        $stmt->close();
        return false;
    }

    echo "<h3><span style='color:green;'>Registration Successful!</span></h3>";
    $stmt->close();
    return true;
}

function login($name, $pass)
{
    $name = trim(htmlspecialchars($name));
    $pass = trim(htmlspecialchars($pass));

    if (empty($name) || empty($pass)) {
        echo "<h3><span style='color:red;'>Fill All Required Fields!</span></h3>";
        return false;
    }

    if (strlen($name) < 6 || strlen($name) > 30 || strlen($pass) < 6 || strlen($pass) > 30) {
        echo "<h3><span style='color:red;'>Value Length Must Be Between 6 And 30!</span></h3>";
        return false;
    }

    $mysqli = connect();
    $stmt = $mysqli->prepare('SELECT id, roleid, pass FROM users WHERE login = ?');
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        if (password_verify($pass, $row['pass'])) {
            $_SESSION['ruser'] = $name;
            $_SESSION['userid'] = $row['id'];
            if ($row['roleid'] == 1) {
                $_SESSION['radmin'] = $name;
            }
            echo "<h3><span style='color:green;'>Login Successful!</span></h3>";
            $stmt->close();
            return true;
        } else {
            echo "<h3><span style='color:red;'>Invalid Password!</span></h3>";
        }
    } else {
        echo "<h3><span style='color:red;'>No Such User!</span></h3>";
    }

    $stmt->close();
    return false;
}
?>
