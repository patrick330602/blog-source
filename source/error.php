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
            color: black;
        }
        a:hover {
            text-decoration: underline;
            color: black;
        }
        a:active {
            color: black;
        }
        a:visited {
            color: black;
        }
    </style>
    <title>Error <?= http_response_code() ?></title>
</head>

<body>
    <center><img src="https://cdn.patrickwu.space/status/<?= http_response_code() ?>.jpg" /></center>
    <center>Powered by <a href="https://http.cat">http.cat</a></center><br/>
    <center><a href="https://patrickwu.space/">Bring Me Back</a> | <a href="https://patrickwu.space/zh">帶我回去</a></center>

    </div>
</body>

</html>