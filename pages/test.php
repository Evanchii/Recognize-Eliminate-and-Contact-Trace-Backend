<?php 
if(isset($_POST["submit"])) {
    echo '<pre>';
var_dump($_POST);
echo '</pre>';
}
?>

<html>
    <body>
        <form action="test.php" method="POST">
            <input type="date" name="date" id="why" disabled>
            <input type="time" name="time" id="nowork">
            <input type="submit" name="submit">
        </form>
    </body>
</html>