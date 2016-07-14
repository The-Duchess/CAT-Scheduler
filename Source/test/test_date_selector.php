<html>
<head>
    <title>Date Select Test</title>
</head>

<body>
    <form action="<?php htmlentities($_SERVER['PHP_SELF']) ?>" method="post">
        <label>Select Three Dates</label>
        <br>
<?php

error_reporting(E_ALL);
ini_set('display_errors', 'on');

include "../Utility/utils.php";

$result1 = date_selector("formSubmit");
$result2 = date_selector("formSubmit", 2);
$result3 = date_selector("formSubmit", 10);

if (isset($result1)) {
    echo $result1->format("Y-m-d") . "\n";
}
if (isset($result2)) {
    echo $result2->format("Y-m-d") . "\n";
}
if (isset($result3)) {
    echo $result3->format("Y-m-d") . "\n";
}

?>
<input type="submit" name="formSubmit" value="Select" />
</form>
</body>
</html>
