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
            <input id="textInput" type="text" class="form-control" name="keyword" placeholder="input SQL"
                   value="{{ $keyword ?? '' }}">
            <div>
                <button class="btn btn-primary" type="submit">search</button>
                <button class="btn btn-primary" id="export-excel">export excel</button>
                <button class="btn btn-primary" id="export-json">export json</button>
            </div>

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
                    <th>create-time</th>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#export-excel').on('click', function(e) {
            e.preventDefault();
            // 调用接口进行数据导出
            param = $('#textInput').val();
            window.location.href = "{{ route('export.excel') }}"+'?keyword='+param;
        });
        $('#export-json').on('click', function(e) {
            e.preventDefault();
            param = $('#textInput').val();
            window.location.href = "{{ route('export.json') }}"+'?keyword='+param;
        });
    });
</script>
</body>
</html>
