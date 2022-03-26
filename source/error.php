<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body,
        html {
            font-family: Roboto, "Helvetica Neue", Helvetica, "PingFang SC", "Hiragino Sans GB", "Microsoft YaHei", "微软雅黑", Arial, sans-serif;
            color: black;
        }

        a {
            display: inline;
            text-decoration: none;
            font-weight: bold;
        }
        a:link {
            color: azure;
        }
        a:hover {
            text-decoration: underline;
            color: azure;
        }
        a:active {
            color: azure;
        }
        a:visited {
            color: aliceblue;
        }
    </style>
    <title>Error <?= http_response_code() ?></title>
</head>

<body>
    <center><img src="https://cdn.patrickwu.space/status/<?= http_response_code() ?>.jpg" /></center>
    <center><a href="https://patrickwu.space/">Bring Me Back</a></center>

    </div>
</body>

</html>