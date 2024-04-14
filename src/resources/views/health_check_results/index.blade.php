@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
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
                <div class="card mt-4">
                    <div class="card-header">{{ __('特定保健指導対象者一覧') }}</div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>UUID</th>
                                <th>委託元保険者</th>
                                <th>所属保険者</th>
                                <th>保険者番号</th>
                                <th>保険証記号</th>
                                <th>保険証番号</th>
                                <th>漢字氏名</th>
                                <th>カナ（姓）</th>
                                <th>カナ（名）</th>
                                <th>支援レベル</th>
                                <th>性別</th>
                                <th>生年月日</th>
                                <th>年齢</th>
                                <th>健診日</th>
                                <th>身長</th>
                                <th>健診時体重</th>
                                <th>BMI</th>
                                <th>健診時腹囲</th>
                                <th>収縮期1</th>
                                <th>収縮期2</th>
                                <th>収縮期その他</th>
                                <th>拡張期1</th>
                                <th>拡張期2</th>
                                <th>拡張期その他</th>
                                <th>中性脂肪</th>
                                <th>空腹時中性脂肪</th>
                                <th>随時中性脂肪</th>
                                <th>HDL</th>
                                <th>LDL</th>
                                <th>GOT</th>
                                <th>GPT</th>
                                <th>γ-GT</th>
                                <th>空腹時血糖</th>
                                <th>随時血糖</th>
                                <th>HBA1C</th>
                                <th>服薬1</th>
                                <th>服薬2</th>
                                <th>服薬3</th>
                                <th>喫煙</th>
                                <th>初回面談日</th>
                                <th>初回面談時間</th>
                                <th>メールアドレス</th>
                                <th>対象者の特徴</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($healthCheckResults as $result)
                                <tr>
                                    <td>{{ $result->uuid }}</td>
                                    <td>{{ $result->insurance_provider }}</td>
                                    <td>{{ $result->affiliated_insurer }}</td>
                                    <td>{{ $result->insurer_number }}</td>
                                    <td>{{ $result->insurance_symbol }}</td>
                                    <td>{{ $result->insurance_number }}</td>
                                    <td>{{ $result->kanji_name }}</td>
                                    <td>{{ $result->kana_last_name }}</td>
                                    <td>{{ $result->kana_first_name }}</td>
                                    <td>{{ $result->support_level }}</td>
                                    <td>{{ $result->gender }}</td>
                                    <td>{{ $result->birth ? \Carbon\Carbon::parse($result->birth)->format('Y/m/d') : '' }}</td>
                                    <td>{{ $result->age }}</td>
                                    <td>{{ $result->medical_examination_date ? \Carbon\Carbon::parse($result->medical_examination_date)->format('Y/m/d') : '' }}</td>
                                    <td>{{ $result->height }}</td>
                                    <td>{{ $result->weight_at_medical_examination }}</td>
                                    <td>{{ $result->bmi }}</td>
                                    <td>{{ $result->abdominal_circumference_at_medical_examination }}</td>
                                    <td>{{ $result->systolic_1 }}</td>
                                    <td>{{ $result->systolic_2 }}</td>
                                    <td>{{ $result->systolic_other }}</td>
                                    <td>{{ $result->diastolic_1 }}</td>
                                    <td>{{ $result->diastolic_2 }}</td>
                                    <td>{{ $result->diastolic_other }}</td>
                                    <td>{{ $result->neutral_fat }}</td>
                                    <td>{{ $result->fasting_neutral_fat }}</td>
                                    <td>{{ $result->occasional_neutral_fat }}</td>
                                    <td>{{ $result->hdl }}</td>
                                    <td>{{ $result->ldl }}</td>
                                    <td>{{ $result->got }}</td>
                                    <td>{{ $result->gpt }}</td>
                                    <td>{{ $result->γ_gt }}</td>
                                    <td>{{ $result->fasting_blood_sugar }}</td>
                                    <td>{{ $result->occasional_blood_sugar }}</td>
                                    <td>{{ $result->hba1c }}</td>
                                    <td>{{ $result->medication_1 }}</td>
                                    <td>{{ $result->medication_2 }}</td>
                                    <td>{{ $result->medication_3 }}</td>
                                    <td>{{ $result->smoking }}</td>
                                    <td>{{ $result->initial_interview_date ? \Carbon\Carbon::parse($result->initial_interview_date)->format('Y/m/d') : '' }}</td>
                                    <td>{{ $result->initial_interview_time }}</td>
                                    <td>{{ $result->email }}</td>
                                    <td>{{ $result->subject_characteristics }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
