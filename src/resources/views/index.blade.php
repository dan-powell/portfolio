<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Portfolio</title>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="container">
            <h1>Portfolio Index</h1>

            @include('portfolio::partials.list')

            @if(isset($projects) && count($projects) > 0)
                <div class="well">
                    <h2>All Projects</h2>
                    <div class="row">
                	    @foreach($projects as $key => $project)
                            <div class="col-md-4">
                                @include('portfolio::partials.thumb', ['project' => $project])
                            </div>

                            @if($key % 3 == 2)
                                <div class="clearfix visible-md-block visible-lg-block"></div>
                            @endif

                        @endforeach
                    </div>
                </div>
            @else
            	<p>No Projects found</p>
            @endif
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    </body>
</html>