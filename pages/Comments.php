<?php
session_start();
include_once("pages/functions.php");
$mysqli = connect();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Travel Agency - Comments</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row">
        <header class="col-sm-12">
            <?php include_once("pages/login.php"); ?>
        </header>
    </div>

    <div class="row">
        <nav class="col-sm-12">
            <?php include_once('pages/menu.php'); ?>
        </nav>
    </div>

    <div class="row">
        <section class="col-sm-12">
            <h2>Отзывы об отелях</h2>
            <hr>
            <form action="index.php?page=2" method="post" class="form-group">
                <label for="hotelid">Выберите отель:</label>
                <select name="hotelid" id="hotelid" class="form-control col-sm-3" onchange="this.form.submit()">
                    <option value="0">Выберите отель...</option>
                    <?php
                    $res = $mysqli->query("SELECT * FROM hotels ORDER BY hotel");
                    while ($row = $res->fetch_assoc()) {
                        $selected = (isset($_POST['hotelid']) && $_POST['hotelid'] == $row['id']) ? 'selected' : '';
                        echo "<option value='{$row['id']}' $selected>{$row['hotel']}</option>";
                    }
                    $res->free();
                    ?>
                </select>
                <br>
                <textarea name="comment" class="form-control" rows="4" placeholder="Ваш комментарий..."></textarea>
                <br>
                <button type="submit" name="submit_comment" class="btn btn-primary">Отправить</button>
            </form>



            <?php


            if (isset($_POST['submit_comment'])) {
                $hotelid = intval($_POST['hotelid']);
                $comment = trim($_POST['comment']);
                $userid = $_SESSION['userid'] ?? null;

                if ($hotelid && $userid && !empty($comment)) {
                    $query = "INSERT INTO comments (userid, hotelid, comment) VALUES (?, ?, ?)";
                    $stmt = $mysqli->prepare($query);
                    $stmt->bind_param("iis", $userid, $hotelid, $comment);

                    if ($stmt->execute()) {
                        echo '<p class="text-success">Комментарий успешно добавлен!</p>';
                        header("Location: index.php?page=2&hotelid=$hotelid");
                        exit();
                    } else {
                        echo '<p class="text-danger">Ошибка добавления комментария: ' . $stmt->error . '</p>';
                    }
                    $stmt->close();
                } else {
                    echo '<p class="text-danger">Пожалуйста, выберите отель и напишите комментарий.</p>';
                }
            }


            if ($hotelid && $userid && !empty($comment)) {
                $query = "INSERT INTO comments (userid, hotelid, comment) VALUES (?, ?, ?)";
                $stmt = $mysqli->prepare($query);

                if ($stmt === false) {
                    die('Ошибка подготовки запроса: ' . $mysqli->error);
                }

                $stmt->bind_param("iis", $userid, $hotelid, $comment);

                if ($stmt->execute()) {
                    echo '<p class="text-success">Комментарий успешно добавлен!</p>';
                    header("Location: index.php?page=2&hotelid=$hotelid");
                    exit();
                } else {
                    echo '<p class="text-danger">Ошибка добавления комментария: ' . $stmt->error . '</p>';
                }
                $stmt->close();
            } else {
                echo '<p class="text-danger">Пожалуйста, выберите отель и напишите комментарий.</p>';
            }

            $hotelid = $_POST['hotelid'] ?? ($_GET['hotelid'] ?? 0);
            if ($hotelid != 0) {
                $query = "
        SELECT c.comment, c.created_at, u.login AS username, h.hotel 
        FROM comments c
        JOIN users u ON c.userid = u.id
        JOIN hotels h ON c.hotelid = h.id
        WHERE c.hotelid = $hotelid
        ORDER BY c.created_at DESC
    ";

                $res = $mysqli->query($query);

                if ($res->num_rows > 0) {
                    echo '<h3>Отзывы для выбранного отеля:</h3>';
                    echo '<table class="table table-striped">';
                    while ($row = $res->fetch_assoc()) {
                        echo "<tr>
                <td>{$row['hotel']}</td>
                <td>{$row['username']}</td>
                <td>{$row['comment']}</td>
                <td>{$row['created_at']}</td>
            </tr>";
                    }
                    echo '</table>';
                } else {
                    echo '<p class="text-info">Пока нет комментариев для этого отеля.</p>';
                }
            } else {
                echo '<p>Выберите отель, чтобы увидеть отзывы.</p>';
            }


            ?>

        </section>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
