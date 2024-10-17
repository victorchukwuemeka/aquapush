@extends("layouts.app")

@section("content")


    <h1>Deploy Your Laravel App</h1>
    <form method="POST" action="{{ url('deploy') }}">
        @csrf
        <label for="repository">GitHub Repository:</label>
        <input type="text" id="repository" name="repository" placeholder="e.g., username/repository" required>
        <button type="submit">Deploy</button>
    </form>

@endsection