@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add GitHub Repository to Droplet</h2>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('droplet.setup', [$droplet->id]) }}" method="POST">
        @csrf
         
        <input type="hidden" name="droplet_ip" value="{{ $droplet->ip_address  }}" >
    

        <div class="mb-3">
            <label for="repo_url" class="form-label">GitHub Repository URL</label>
            <input type="url" class="form-control" id="repo_url" name="repo_url" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Repository</button>
    </form>
</div>
@endsection
