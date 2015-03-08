@section('styles')
    @parent
    @if (isset($section->styles) && $section->styles != '')
        <style type="text/css">
            {!! $section->styles !!}
        </style>
    @endif
@stop

@if(isset($section->markup) && $section->markup != '')
    <section class="Section{{(isset($section->section_classes)) ? ' ' . $section->section_classes : ''}}">
        <div class="{{(isset($section->container_classes)) ? '_container ' . $section->container_classes : ''}}">
        	<div class="Content">
                {!! Markdown::parse($section->markup) !!}
        	</div>
     	</div>
    </section>
@endif

@section('scripts')
    @parent
    @if (isset($section->scripts) && $section->scripts != '')
        <script type="text/javascript">
            {!! $section->scripts !!}
        </script>
    @endif
@stop