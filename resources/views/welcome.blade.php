<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <link href="public/css/app.css" type="text/css" rel="stylesheet">
        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
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
                font-size: 96px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Laravel 5</div>
                <div class="testing">This is only a test!!</div>
            </div>
            <div id="app">
                <alert>
                    <strong>General!</strong> Blah Blah Blah.
                </alert>
                <alert type="error">
                    <strong>Error!</strong> Your account has not been updated.
                </alert>
                <alert type="success">
                    <strong>Success!</strong> Your account has been updated.
                </alert>
            </div>
        </div>
        <script src="/bsmma/public{{ elixir('js/index.js') }}"></script>
    </body>
</html>
