<?php
$mysqli = connect();
?>
<form id="tourForm">
    <select id="countrySelect" name="countryid" class="col-sm-3 col-md-3 col-lg-3">
        <option value="0">Select country...</option>
        <?php
        $res = $mysqli->query("SELECT * FROM countries ORDER BY country");
        while ($row = mysqli_fetch_array($res, MYSQLI_NUM)) {
            echo '<option value="' . $row[0] . '">' . $row[1] . '</option>';
        }
        mysqli_free_result($res);
        ?>
    </select>
    <br>
    <select id="citySelect" name="cityid" class="col-sm-3 col-md-3 col-lg-3" disabled>
        <option value="0">Select city...</option>
    </select>
    <br>
    <div id="hotelsTable"></div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#countrySelect').change(function () {
            const countryId = $(this).val();
            if (countryId == 0) {
                $('#citySelect').html('<option value="0">Select city...</option>').prop('disabled', true);
                $('#hotelsTable').html('');
                return;
            }
            $.ajax({
                url: 'pages/fetchData.php',
                method: 'POST',
                data: { type: 'cities', countryid: countryId },
                success: function (response) {
                    $('#citySelect').html(response).prop('disabled', false);
                    $('#hotelsTable').html('');
                }
            });
        });

        $('#citySelect').change(function () {
            const cityId = $(this).val();
            if (cityId == 0) {
                $('#hotelsTable').html('');
                return;
            }
            $.ajax({
                url: 'pages/fetchData.php',
                method: 'POST',
                data: { type: 'hotels', cityid: cityId },
                success: function (response) {
                    $('#hotelsTable').html(response);
                }
            });
        });
    });
</script>
