<div>
    <p>Hello {{ $incident->caller->name }}</p>
    <h3>An Incident has been opened on your behalf:</h3>
    <br><br>
    <p>Requested for: {{ $incident->caller->name }}</p>
    <p>Requested by: {{ $incident->caller->name }}</p>
    <p>Description: {{ $incident->description }}</p>
</div>
