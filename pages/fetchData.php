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

$mysqli = connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type'])) {
    $type = $_POST['type'];

    if ($type === 'cities' && isset($_POST['countryid'])) {
        $countryId = intval($_POST['countryid']);
        $res = $mysqli->query("SELECT * FROM cities WHERE countryid = $countryId ORDER BY city");
        echo '<option value="0">Select city...</option>';
        while ($row = mysqli_fetch_array($res, MYSQLI_NUM)) {
            echo '<option value="' . $row[0] . '">' . $row[1] . '</option>';
        }
        mysqli_free_result($res);
    }

    if ($type === 'hotels' && isset($_POST['cityid'])) {
        $cityId = intval($_POST['cityid']);
        $sel = 'SELECT co.country, ci.city, ho.hotel, ho.cost, ho.stars, ho.id 
                FROM hotels ho, cities ci, countries co 
                WHERE ho.cityid = ci.id AND ho.countryid = co.id AND ho.cityid = ' . $cityId;

        $res = $mysqli->query($sel);
        if ($res && mysqli_num_rows($res) > 0) {
            echo '<table class="table table-striped text-center">';
            echo '<thead><tr><td>Hotel</td><td>Country</td><td>City</td><td>Price</td><td>Stars</td><td>Link</td></tr></thead>';
            while ($row = mysqli_fetch_array($res, MYSQLI_NUM)) {
                echo '<tr><td>' . $row[2] . '</td><td>' . $row[0] . '</td><td>' . $row[1] . '</td>
                      <td>$' . $row[3] . '</td><td>' . $row[4] . '</td>
                      <td><a href="pages/hotelinfo.php?hotel=' . $row[5] . '" target="_blank">more info</a></td></tr>';
            }
            echo '</table>';
        } else {
            echo '<p>No results found for the selected city.</p>';
        }
    }
}
?>
