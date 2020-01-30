<div>

    {{-- {{ $closedates }} --}}

    <h2>H-7 project anda menuju Closing Date:</h2> <br><br>

    @foreach($closedate as $data)
        <a href="app.sinergy.co.id/detail_project/{{ $data->lead_id }}">[{{ $data->lead_id }}]</a> {{ $data->opp_name }} <br>
    @endforeach

    {{-- @foreach($presales as $datas)
        {{ $datas->email }}
    @endforeach --}}

</div>