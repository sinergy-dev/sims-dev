<!DOCTYPE html>
<html>
    <head>
        <title>Page Not Found</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: white;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
                background-image: url("glitch2.jpg");
                background-size: 1370px;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                text-align: center;
                font-size: 150px;
                margin-bottom: 20px;
            }

            .sub {
                text-align: center;
                font-size: 50px;
            }

            button {
                position:relative;
                margin-top:600%;
                background-color: #f7c100;
                color: white;
                border: none;
                padding: 10px 25px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 15px
            }

        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                    <a href="{{ URL::previous() }}"><button class="btn btn-primary-back pull-left"> <span>Back</span></button></a>
            </div>
        </div>
    </body>
</html>