<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tito Ganteng</title>
</head>
<body onload="parseJwt('{{$data}}')">
    <h1>Tito Ganteng</h1>
    <script type="text/javascript">
        function parseJwt (token) {
            var base64Url = token.split('.')[1];
            var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
            var jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
                return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
            }).join(''));

            console( JSON.parse(jsonPayload));
        };


    </script>
</body>
</html>