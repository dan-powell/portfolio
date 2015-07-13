<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Portfolio || {{ $project->seo_title }}</title>
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
            <h1>{{ $project->title }}</h1>

            <div class="row">
                <article class="col-sm-6">
            		@if (count($project->sections) > 0)
            	        @foreach($project->sections as $section)
            	            @include('portfolio::partials.section')
            	        @endforeach
                    @else
                        {!! Markdown::parse($project->markup) !!}
            		@endif
                </article>
                <aside class="col-sm-3">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3>Tags</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="list-group">
                                @if (count($project->tags) > 0)
                                    @foreach($project->tags as $tag)
                                        <li class="list-group-item">{{ $tag->title }}</li>
                                    @endforeach
                                @else
                                    <li class="list-group-item">No tags</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </aside>
                <aside class="col-sm-3">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3>Pages</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="list-group">
                                @if (count($project->pages) > 0)
                                    @foreach($project->pages as $page)
                                        <li class="list-group-item"><a href="{{ route('projects.page', [$project->slug, $page->slug]) }}">{{ $page->title }}</a></li>
                                    @endforeach
                                @else
                                    <li class="list-group-item">No pages</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>

            <hr/>

            <a href="{{ route('projects.index') }}" class="btn btn-primary">Back</a>

            <hr/>

            @include('portfolio::partials.list')

        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

        @yield('scripts')
    </body>
</html>