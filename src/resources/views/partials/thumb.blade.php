<a href="{{ (isset($project->url) && $project->url != '') ? $project->url : route('projects.show', $project->slug) }}" class="Project">
    @if(File::exists('projects/' . $project->slug . '/thumb.jpg'))
        <img
            srcset="{{ url() }}/img/projects/{{ $project->slug }}/thumb.jpg?w=800&h=800&fit=crop 2x"
            src="{{ url() }}/img/projects/{{ $project->slug }}/thumb.jpg?w=400&h=400&fit=crop"
            alt="{{{ $project->title }}}"/>
    @elseif(File::exists('projects/' . $project->slug . '/thumb.gif'))
        <img
            src="{{ url() }}/projects/{{ $project->slug }}/thumb.gif"
            alt="{{{ $project->title }}}"/>
    @else

        <img src="{{ url() }}/img/holding/spacer_1x1.png" alt="{{{ $project->title }}}"/>
    @endif

    <div class="_titleWrapper">
        <h3 class="_title">{{ str_limit($project->title, 60) }}&nbsp;</h3>
    </div>
</a>