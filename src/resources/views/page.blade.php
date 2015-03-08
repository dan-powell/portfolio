@extends('base')

@section('class')Projects _show -page @stop

@section('meta')
<title>{{ $page->seo_title }}</title>
<meta name="description" content="{{ $page->seo_description }}">
@stop

@section('sidebar-buttons')
    <a href="{{ route('projects.show', $project->slug) }}" class="navButton -back">
        <span class="sr-only">Back</span>
    </a>
@stop

@section('main')
    <article>

        @foreach($page->sections as $section)

            @include('partials.section')

        @endforeach

    </article>
@stop

@section('sidebar')
    @include('partials.list')
@stop

@section('scripts')
    @parent
    <script src="{{ url() }}/js/projects.js" type="text/javascript"></script>
@stop