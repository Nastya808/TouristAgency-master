<?php
session_start();
include_once("pages/functions.php");

$page = isset($_GET['page']) ? $_GET['page'] : "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Travel Agency</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row">
        <header class="col-sm-12 col-md-12 col-lg-12">
            <?php include_once("pages/login.php"); ?>
        </header>
    </div>

    <div class="row">
        <nav class="col-sm-12 col-md-12 col-lg-12 head">
            <?php include_once("pages/menu.php"); ?>
        </nav>
    </div>

    <div class="row">
        <section class="col-sm-12 col-md-12 col-lg-12">
            <?php
            switch ($page) {
                case 1:
                    if (isset($_SESSION['ruser'])) {
                        include_once("pages/tours.php");
                    } else {
                        echo "<p>Доступ запрещен. Пожалуйста, войдите в систему.</p>";
                    }
                    break;
                case 2:
                    if (isset($_SESSION['ruser'])) {
                        include_once("pages/comments.php");
                    } else {
                        echo "<p>Доступ запрещен. Пожалуйста, войдите в систему.</p>";
                    }
                    break;
                case 3:
                    include_once("pages/registration.php");
                    break;
                case 4:
                    if (isset($_SESSION['radmin'])) {
                        include_once("pages/admin.php");
                    } else {
                        echo "<p>Доступ запрещен. Только администраторы могут видеть эту страницу.</p>";
                    }
                    break;
                case 5:
                    if (isset($_SESSION['radmin'])) {
                        include_once("pages/private.php");
                    } else {
                        echo "<p>Доступ запрещен. Пожалуйста, войдите в систему.</p>";
                    }
                    break;
                default:
                    echo '<p>Выберите страницу из меню.</p>';
            }
            ?>
        </section>
    </div>
</div>

<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
