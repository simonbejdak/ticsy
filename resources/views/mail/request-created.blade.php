<div>
    <p>Hello {{ $request->caller->name }}</p>
    <h3>A Request has been opened on your behalf:</h3>
    <br><br>
    <p>Requested for: {{ $request->caller->name }}</p>
    <p>Requested by: {{ $request->caller->name }}</p>
    <p>Description: {{ $request->description }}</p>
</div>
