<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Deploy Your Laravel App</h1>
    <form method="POST" action="{{ url('deploy') }}">
        @csrf
        <label for="repository">GitHub Repository:</label>
        <input type="text" id="repository" name="repository" placeholder="e.g., username/repository" required>
        <button type="submit">Deploy</button>
    </form>
</body>
</html>
