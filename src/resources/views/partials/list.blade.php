@if(isset($projects) && count($projects) > 0)
    <div class="well">
        <h2>Featured Projects</h2>
        <div class="row">
        	@foreach($projects as $project)
        	    <div class="col-sm-4">
                    @include('portfolio::partials.thumb', ['project' => $project])
        	    </div>
        	@endforeach
        </div>
    </div>
@endif