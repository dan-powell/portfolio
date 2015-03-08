@section('styles')
    @parent
    @if (isset($section->styles) && $section->styles != '')
        <style type="text/css">
            {!! $section->styles !!}
        </style>
    @endif
@stop

@if(isset($section->markup) && $section->markup != '')
    {{ $section->markup }}
@endif

@section('scripts')
    @parent
    @if (isset($section->scripts) && $section->scripts != '')
        <script type="text/javascript">
            {!! $section->scripts !!}
        </script>
    @endif
@stop