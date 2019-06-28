<?php
$isGet = isset($_GET['q']) && $_SERVER['REQUEST_METHOD'] == 'GET';
$isPost = isset($_POST['q']) && $_SERVER['REQUEST_METHOD'] == 'POST';
if ($isGet) {
    var_dump($_GET['q']);
} elseif ($isPost) {
    var_dump($_POST['q']);
} else {
    $xss = <<<XSS
'`"><\x00script>javascript:alert(1)</script>
XSS;
    ?>
    <p><?php echo htmlspecialchars($xss); ?></p>

    <form action="" method="get" target="_blank">
        <input type="text" name="q" size="50" value="">
        <button type="submit">GET</button>
    </form>

    <form action="" method="post" target="_blank">
        <input type="text" name="q" size="50" value="">
        <button type="submit">POST</button>
    </form>
    <?php
}
?>