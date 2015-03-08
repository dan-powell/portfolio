<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Portfolio || {{ $page->seo_title }}</title>
        <meta name="description" content="{{ $project->seo_description }}">

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

        @yield('styles')

    </head>
    <body>
        <div class="container">
            <h1>{{ $page->title }}</h1>

            <div class="row">
            <article class="col-sm-8">

        		@if (count($page->sections) > 0)
        	        @foreach($page->sections as $section)
        	            @include('portfolio::partials.section')
        	        @endforeach
                @else
                    {{ $page->markup }}
        		@endif

            </article>

            <hr/>

            <a href="{{ route('projects.show', $project->slug) }}" class="btn btn-primary">Back</a>

        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

        @yield('scripts')
    </body>
</html>