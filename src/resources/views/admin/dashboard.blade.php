<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="stylesheet" href="{{ url() }}/vendor/portfolio/admin/css/main.css">

    <script src="{{ url() }}/vendor/portfolio/admin/js/plugins.js" type="text/javascript"></script>
    <script src="{{ url() }}/vendor/portfolio/admin/js/main.js" type="text/javascript"></script>
</head>
<body xmlns:ng="http://angularjs.org" ng-app="ng-portfolio">

    <div class="container">
        <ul class="nav nav-pills">
            <li><a href="#dashboard">Dashboard</a></li>
            <li><a href="#project">Projects</a></li>
        </ul>

        <div ng-view class="well">

        </div>
    </div>

</body>
</html>