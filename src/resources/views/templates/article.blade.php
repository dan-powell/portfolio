@extends('base')

@section('class')Projects _show -article @stop

@section('main')

    <section class="Section -gradient -md">
        <div class="_container">
            <h1 class="pageHeading">
            	@if (isset($project->title) && $project->title != null)
                {{{ $project->title }}}
                @else
                	Somebody was careless and forgot to add a title&hellip;
                @endif
            </h1>
        </div>
    </section>

	@if (isset($project->hero) && $project->hero != null)
	<section class="Section -media">
		{{ HTML::image('img/test-hero/' . $project->hero) }}
	</section>
	@endif

	@if (isset($project->intro) && $project->intro != null)
    <section class="Section -hero -md">
	    <div class="_container Content">
            {{ Markdown::parse($project->intro) }}
	    </div>
    </section>
    @endif

	@if (isset($project->body) && $project->body != null)
    <section class="Section -content -xl">
        <div class="_container">
            <article class="Content UserGrid">
				{{ Markdown::parse($project->body) }}
            </article>
        </div>
    </section>
	@endif

@stop

@section('sidebar')
@stop

@section('htmlfoot')
    <script src="{{ url() }}/assets/js/projects.built.min.js" type="text/javascript"></script>
@stop