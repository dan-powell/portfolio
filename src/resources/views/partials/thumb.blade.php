<div class="thumbnail">
    <a href="{{ (isset($project->url) && $project->url != '') ? $project->url : route('projects.show', $project->slug) }}">
        @if(File::exists('projects/' . $project->slug . '/thumb.jpg'))
            <img
                srcset="{{ url() }}/img/projects/{{ $project->slug }}/thumb.jpg?w=800&h=800&fit=crop 2x"
                src="{{ url() }}/img/projects/{{ $project->slug }}/thumb.jpg?w=400&h=400&fit=crop"
                alt="{{{ $project->title }}}"/>
        @elseif(File::exists('projects/' . $project->slug . '/thumb.gif'))
            <img
                src="{{ url() }}/projects/{{ $project->slug }}/thumb.gif"
                alt="{{{ $project->title }}}"/>
        @endif

    </a>
    <div class="caption">
        <h3 class="_title">
            <a href="{{ (isset($project->url) && $project->url != '') ? $project->url : route('projects.show', $project->slug) }}">
                {{ str_limit($project->title, 60) }}
            </a>
        </h3>
    </div>
</div>
