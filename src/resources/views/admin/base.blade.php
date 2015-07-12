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

        <nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
          <div class="container">
            <div class="navbar-header">
              <a class="navbar-brand" href="#">Portfolio Administration</a>
            </div>

            <ul class="nav navbar-nav">
                <li ui-sref-active="active"><a ui-sref="dashboard">Dashboard</a></li>
                <li ui-sref-active="active">
                    <a ui-sref="project" class="hidden">Projects</a>
                    <a ui-sref="project.index">Projects</a>
                </li>
                <li ui-sref-active="active">
                    <a ui-sref="tag" class="hidden">Tags</a>
                    <a ui-sref="tag.index">Tags</a>
                </li>
                <li ui-sref-active="active">
                    <a ui-sref="page" class="hidden">Pages</a>
                    <a ui-sref="page.index">Pages</a>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="{{ url() }}/portfolio" target="_blank">View Portfolio</a></li>
                <p class="navbar-text navbar-right">Signed in as <a href="#" class="navbar-link">{{ Auth::user()->name }}</a></p>
              </ul>

          </div>
        </nav>

        <div ui-view></div>

    </div>

</body>
</html>