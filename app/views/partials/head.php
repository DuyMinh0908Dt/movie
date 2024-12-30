<!DOCTYPE html>
<html lang="vi">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="/public/css/fontawesone.min.css">
    <link rel="stylesheet" href="/public/css/styles.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="/public/assets/css/home.css" />
    <link rel="preconnect" href="https://fonts.`googleapis`.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <?php
    global $title;
    echo " <title>" . ($title ?? "Cinestar") . "</title>";
    ?>
    

</head>

<body class='tw-bg-gray-100'>
    <?php require ('nav.php');
    require ('toast.php');
    ?>
    <div class='tw-h-36'></div>