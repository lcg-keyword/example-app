<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search and Pagination</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <form action="{{ url('execute') }}" method="get">
        @csrf
        <div class="input-group mb-3">
            <input type="text" class="form-control" name="keyword" placeholder="input SQL"
                   value="{{ $keyword ?? '' }}">
            <button class="btn btn-primary" type="submit">search</button>
{{--            <button class="btn btn-primary" type="submit">export excel</button>--}}
{{--            <button class="btn btn-primary" type="submit">export json</button>--}}
        </div>
    </form>

    @if(isset($logs) && !is_string($logs))
        @if($logs->isNotEmpty())
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>user</th>
                    <th>error</th>
                    <th>create time</th>
                </tr>
                </thead>
                <tbody>
                @foreach($logs as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->user }}</td>
                        <td>{{ $product->error }}</td>
                        <td>{{ $product->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
{{--        {{ $logs->links() }}--}}
    @elseif(isset($logs) && is_string($logs))
        <p class="text-danger">{{$logs}}</p>
    @endif
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
