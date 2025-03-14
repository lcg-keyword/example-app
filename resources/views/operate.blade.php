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

    <form id="myForm" action="{{ url('execute') }}" method="get">
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
                    <th>sql</th>
                    <th>error</th>
                    <th>create-time</th>
                </tr>
                </thead>
                <tbody>
                @foreach($logs as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->user }}</td>
                        <td>{{ $product->sql }}</td>
                        <td>{{ $product->error }}</td>
                        <td>{{ $product->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
        {{--        {{ $pagers->links() }}--}}

        @if ($pagers->lastPage() > 1)
            <ul class="pagination">
                @if ($pagers->onFirstPage())
                    <li class="disabled"><span>&laquo; 上一页</span></li>
                @else
                    <li><a href="{{ $pagers->previousPageUrl().'&keyword='.$keyword }}">&laquo; 上一页</a></li>
                @endif

                @for ($i = 1; $i <= $pagers->lastPage(); $i++)
                    <li class="{{ ($pagers->currentPage() == $i) ? ' active' : '' }}">
                        <a href="{{ $pagers->url($i).'&keyword='.$keyword }}">{{ $i }}</a>
                    </li>
                @endfor

                @if ($pagers->hasMorePages())
                    <li><a href="{{ $pagers->nextPageUrl().'&keyword='.$keyword }}">下一页 &raquo;</a></li>
                @else
                    <li class="disabled"><span>下一页 &raquo;</span></li>
                @endif
            </ul>
        @endif

    @elseif(isset($logs) && is_string($logs))
        <p class="text-danger">{{$logs}}</p>
    @endif
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#myForm').on('submit', function (e) {
            textInput = $('#textInput').val().trim();
            if ('' === textInput) {
                e.preventDefault();
                return;
            }

            if (!textInput.toLowerCase().startsWith("select ")) {
                alert("Only SELECT is allowed");
                e.preventDefault();
            }
        });
        $('#export-excel').on('click', function (e) {
            e.preventDefault();
            // 调用接口进行数据导出
            param = $('#textInput').val().trim();
            if ('' === param) return;

            if (!param.toLowerCase().startsWith("select ")) {
                alert("Only SELECT is allowed");
                return;
            }

            window.location.href = "{{ route('export.excel') }}" + '?keyword=' + param;
        });
        $('#export-json').on('click', function (e) {
            e.preventDefault();
            param = $('#textInput').val().trim();

            if ('' === param) return;

            if (!param.toLowerCase().startsWith("select ")) {
                alert("Only SELECT is allowed");
                return;
            }

            window.location.href = "{{ route('export.json') }}" + '?keyword=' + param;
        });
    });
</script>
</body>
</html>
