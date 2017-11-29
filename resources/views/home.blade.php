@extends('layouts.app')

@section('content')
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
                        欢迎回来！你的积分
                        <h1>{{ $total }}分</h1>
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
