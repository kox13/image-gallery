<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Milky Way galaxy gallery</title>
    <link rel="stylesheet" href="/static/styles/main.css"/>
    {{stylesheet}}
</head>
<body>
    <?php include_once __DIR__ . "/partials/header.php" ?>
    <?php include_once __DIR__ . "/partials/message.php" ?>

    <main id="contentContainer">
        {{content}}
    </main>

    <?php include_once __DIR__ . "/partials/footer.php" ?>

    <?php include_once __DIR__ . "/scripts/hideMessage.php" ?>
</body>
</html>