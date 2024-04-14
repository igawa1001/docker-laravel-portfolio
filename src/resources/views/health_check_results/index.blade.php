@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">特定保健指導対象者一覧</div>
                    <div class="card-body">
                        <form action="{{ route('health_check_results.import') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <label for="csv_file">CSVファイル:</label>
                                <input type="file" id="csv_file" name="csv_file" required>
                            </div>
                            <div>
                                <button type="submit">アップロード</button>
                            </div>
                        </form>
                        @if(session('success'))
                            <div>{{ session('success') }}</div>
                        @endif
                        @if($errors->any())
                            <div>{{ $errors->first() }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
