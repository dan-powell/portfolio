@if(isset($projects) && count($projects) > 0)
    <div class="ProjectList">
    	@foreach($projects as $project)
    	    <div class="_column">
                @include('portfolio::partials.thumb', ['project' => $project])
    	    </div>
    	@endforeach
    </div>
@else
	<p>&hellip;or not. There aren&lsquo;t any projects to show at the moment, probably because I&lsquo;m fiddling around with something on this site.</p>
	<p>Sorry, please check back later and there might be something to see.</p>
@endif