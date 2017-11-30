@extends('layouts.app')

@section('content')
    <script>
        var add_integral = function(point){

            $.post('/api/increase', {'_token':'{{ csrf_token() }}','point':point},function(){
                console.log('response');
            });
        };
    </script>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">主页</div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @auth
                            <div class="">
                                欢迎回来！你的积分
                                <h1>{{ $available }}分</h1>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="60"
                                     aria-valuemin="0" aria-valuemax="100" style="width: 40%;">
                                    <span class="sr-only">40% 完成</span>
                                </div>
                            </div>
                            <div class="btn-group btn-group-lg">
                                <button type="button" class="btn btn-default" onclick="add_integral(1)">增加 1</button>
                                <button type="button" class="btn btn-default">增加 2</button>
                                <button type="button" class="btn btn-default">增加 5</button>
                            </div>
                        @endauth

                        @guest
                            请登录！
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
