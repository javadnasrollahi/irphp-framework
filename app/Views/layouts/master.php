<!-- app/Views/layouts/master.php -->
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>IRPHP Framework</title>
</head>
<body>
    <header>
        <h2>IRPHP فریم‌ورک</h2>
        <hr>
    </header>

    <main>
        <?php echo $content?>
    </main>

    <footer>
        <hr>
        <p style="font-size: 12px;">کپی‌رایت © <?php echo date('Y')?></p>
    </footer>
</body>
</html>
