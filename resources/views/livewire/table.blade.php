<div>
    <table>
        @foreach($table->headers as $header)
            <th>{{ $header }}</th>
        @endforeach
    </table>
</div>
