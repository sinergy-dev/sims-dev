<div>
    <h2>H-1 training dimulai :</h2>
    @foreach($eventdate as $data)
        <p>- <a href="app.sinergy.co.id/event_management/{{ $data->id }}">{{ $data->title }}.</a>
            (<b><?php
                if($data->attendee != null) {
                    $array = $data->attendee;
                    $arrays = json_decode($array);
                    $serialized = serialize($arrays);
                    $newarray = unserialize($serialized);
                    echo implode(', ', $newarray);
                }  
            ?></b>)
        </p>
    @endforeach
</div>