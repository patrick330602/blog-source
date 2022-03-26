<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body,
        html {
            font-family: Roboto, "Helvetica Neue", Helvetica, "PingFang SC", "Hiragino Sans GB", "Microsoft YaHei", "微软雅黑", Arial, sans-serif;
            color: white;
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

        #bg {
            background-image: url("https://cdn.patrickwu.space/base/404-bg.gif");
            background-repeat: no-repeat;
            background-attachment: scroll;
            background-clip: border-box;
            background-origin: padding-box;
            background-size: cover;
            background-position: bottom;
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0px;
            left: 0px;
            z-index: -1;
        }

        #cover {
            background-color: rgba(0, 0, 0, 0.9);
            opacity: 0.8;
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0px;
            left: 0px;
            z-index: -1;
        }

        #content {
            text-align: center;
            position: center center;
            align-content: center;
        }

        #fof {
            text-align: center;
            font-size: 80px;
            color: grey;
        }

        #fog {
            text-align: center;
            font-size: 30px;
            color: grey;
        }

        #parent {
            text-align: center;
            font-size: 12px;
        }

        #child {
            text-align: center;
            padding: 20px 80px;
            display: none;
            font-size: 10px;
        }

        #parent:hover #child {
            display: block;
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