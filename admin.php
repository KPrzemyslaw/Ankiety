<!DOCTYPE html><?php include_once 'php/KPAutoloader.php'; ?>
<html lang="en">
<head>
    <title>Ankiety</title>
    <meta charset="UTF-8" />
    <meta name="author" content="Przemysław Kotlarz" />
    <script id="text_javascript_kp" type="text/javascript" src="js/kp.js?t=<?= \KPrzemyslaw\KPConfigure::getVersion(true) ?>"></script>
</head>
<body>
    <div id="htmltemplates"></div>
    <script>
        KP.loadApplication('admin', function(data, status){
            KPAppMain();
        });
    </script>
    <script src='php/session.php'></script>
</body>
</html>
