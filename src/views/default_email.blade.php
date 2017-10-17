<p>Hey {{$name}}</p>
<br />
<p>Details of your violation: <br />
@foreach($rule_key_fields as $key => $value)
    {{$key}} : {{$value}}
@endforeach
</p>
