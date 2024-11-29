<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Info</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/info.css">
    <style>
        .review-card {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            margin: 15px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .review-header {
            font-weight: bold;
            font-size: 1.2em;
        }
        .review-date {
            font-size: 0.9em;
            color: #777;
        }
        .gallery img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <?php
    include_once("functions.php");

    if (isset($_GET['hotel'])) {
        $hotelId = intval($_GET['hotel']);
        $mysqli = connect();

        // Получение данных отеля
        $hotelQuery = "SELECT * FROM hotels WHERE id = $hotelId";
        $hotelRes = $mysqli->query($hotelQuery);

        if ($hotelRes && $hotelData = $hotelRes->fetch_assoc()) {
            echo "<h2 class='text-center my-4 text-uppercase'>{$hotelData['hotel']}</h2>";

            echo '<div class="row">';
            echo '<div class="col-md-6 text-center">';

            // Галерея изображений
            echo '<h5 class="text-primary mb-3">Watch our pictures</h5>';
            echo '<div class="gallery">';
            $imageQuery = "SELECT imagepath FROM images WHERE hotelid = $hotelId";
            $imageRes = $mysqli->query($imageQuery);

            if ($imageRes->num_rows > 0) {
                while ($imageRow = $imageRes->fetch_assoc()) {
                    echo "<img src='../" . htmlspecialchars($imageRow['imagepath']) . "' alt='Hotel Image'>";
                }
            } else {
                echo "<p class='text-muted'>No images available for this hotel.</p>";
            }
            echo '</div></div>';

            // Информация об отеле
            echo '<div class="col-md-6">';
            echo "<h4 class='text-info'>Cost: <span class='badge bg-info text-dark'>{$hotelData['cost']} $</span></h4>";
            echo "<p class='border p-3'>" . htmlspecialchars($hotelData['info']) . "</p>";
            echo '<a href="#" class="btn btn-success mt-3">Book This Hotel</a>';
            echo '</div></div>';

            // Отзывы
            echo "<h3 class='mt-5'>Reviews:</h3>";
            $commentQuery = "
                    SELECT c.comment, c.created_at, u.login 
                    FROM comments c 
                    JOIN users u ON c.userid = u.id 
                    WHERE c.hotelid = $hotelId 
                    ORDER BY c.created_at DESC";
            $commentRes = $mysqli->query($commentQuery);

            if ($commentRes->num_rows > 0) {
                while ($commentRow = $commentRes->fetch_assoc()) {
                    echo "<div class='review-card'>";
                    echo "<div class='review-header'>" . htmlspecialchars($commentRow['login']) . "</div>";
                    echo "<div class='review-date'>" . htmlspecialchars($commentRow['created_at']) . "</div>";
                    echo "<p>" . htmlspecialchars($commentRow['comment']) . "</p>";
                    echo "</div>";
                }
            } else {
                echo '<p class="text-muted">No reviews yet.</p>';
            }
        } else {
            echo '<p class="text-danger text-center">Hotel not found.</p>';
        }

        $mysqli->close();
    } else {
        echo '<p class="text-danger text-center">No hotel selected.</p>';
    }
    ?>
</div>

<script src="../js/jquery-3.1.0.min.js"></script>
<script src="../js/gallery.js"></script>
<script src="../js/info2.js"></script>
</body>
</html>
