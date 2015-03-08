@extends('base')

@section('meta')
<title>{{ $project->seo_title }}</title>
<meta name="description" content="{{ $project->seo_description }}">
@stop

@section('class')Projects _show @stop

@section('sidebar-buttons')
    <a href="{{ route('projects.index') }}" class="navButton -back">
        <span class="sr-only">Back</span>
    </a>
@stop

@section('main')
    <article>

		@if (count($project->sections) > 0)
	        @foreach($project->sections as $section)

	            @include('portfolio::partials.section')

	        @endforeach
        @else

            <section class="Section -primary">
		        <div class="_container">
		        	<div class="Content">
		                {!! Markdown::parse($project->markup) !!}
		        	</div>
		     	</div>
		    </section>

		@endif

    </article>
@stop

@section('sidebar')
    @include('portfolio::partials.list')
@stop

@section('scripts')
    @parent
    @if (isset($project->scripts) && $project->scripts != '')
            {!! $project->scripts !!}
    @endif
@stop