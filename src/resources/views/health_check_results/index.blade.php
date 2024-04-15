@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <h1 class="card-header">{{ __('特定保健指導対象者一覧') }}</h1>
                    <div class="card-body">
                        <form action="{{ route('health_check_results.import') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <label for="csv_file">CSVファイル:</label>
                                <input type="file" id="csv_file" name="csv_file" required>
                            </div>
                            <div>
                                <a href="https://laravel-news.com"><button type="submit" class='btn btn-success'>アップロード</button></a>
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
            <div class="col-lg-12">
                <div class="card-body">
                    <form id="searchForm" onsubmit="return false;">
                        <div class="form-group">
                            <label for="searchKeyword">検索:</label>
                            <input type="text" class="form-control" id="searchKeyword" placeholder="保険証記号、保険証番号、漢字氏名、カナ（姓）、カナ（名）、メールアドレス">
                        </div>
                    </form>

                    <table class="table table-responsive table-striped" id="healthCheckResultsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
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
                        <!-- 検索結果を動的に挿入 -->
                        </tbody>
                    </table>

                    <nav>
                        <ul class="pagination" id="pagination">
                            <!-- ページネーションを動的に生成 -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

@section('scripts')
    <script>
        const searchKeyword = document.getElementById('searchKeyword');
        const healthCheckResultsTable = document.querySelector('#healthCheckResultsTable tbody');
        const pagination = document.getElementById('pagination');

        searchKeyword.addEventListener('input', function () {
            fetchResults(1, searchKeyword.value);
        });

        async function fetchResults(page = 1, keyword = '') {
            try {
                const url = `/health_check_results/search?page=${page}&keyword=${encodeURIComponent(keyword)}`;
                const response = await fetch(url);
                const data = await response.json();
                renderTable(data.data);
                renderPagination(data);
            } catch (error) {
                console.error('Fetch error:', error);
            }
        }

        fetchResults();

        function renderTable(results) {
            healthCheckResultsTable.innerHTML = '';
            results.forEach(result => {
                const row = document.createElement('tr');
                row.innerHTML = `
                <td>${result.id}</td>
                <td>${result.uuid}</td>
                <td>${result.insurance_provider}</td>
                <td>${result.affiliated_insurer}</td>
                <td>${result.insurer_number}</td>
                <td>${result.insurance_symbol}</td>
                <td>${result.insurance_number}</td>
                <td>${result.kanji_name}</td>
                <td>${result.kana_surname}</td>
                <td>${result.kana_name}</td>
                <td>${result.support_level}</td>
                <td>${result.gender}</td>
                <td>${result.date_of_birth ? new Date(result.date_of_birth).toLocaleDateString('ja-JP') : ''}</td>
                <td>${result.age}</td>
                <td>${result.medical_examination_date ? new Date(result.medical_examination_date).toLocaleDateString('ja-JP') : ''}</td>
                <td>${result.height}</td>
                <td>${result.weight_at_medical_examination}</td>
                <td>${result.bmi}</td>
                <td>${result.abdominal_circumference_at_medical_examination}</td>
                <td>${result.systolic_1}</td>
                <td>${result.systolic_2}</td>
                <td>${result.systolic_other}</td>
                <td>${result.diastolic_1}</td>
                <td>${result.diastolic_2}</td>
                <td>${result.diastolic_other}</td>
                <td>${result.neutral_fat}</td>
                <td>${result.fasting_neutral_fat}</td>
                <td>${result.occasional_neutral_fat}</td>
                <td>${result.hdl}</td>
                <td>${result.ldl}</td>
                <td>${result.got}</td>
                <td>${result.gpt}</td>
                <td>${result.γ_gt}</td>
                <td>${result.fasting_blood_sugar}</td>
                <td>${result.occasional_blood_sugar}</td>
                <td>${result.hba1c}</td>
                <td>${result.medication_1}</td>
                <td>${result.medication_2}</td>
                <td>${result.medication_3}</td>
                <td>${result.smoking}</td>
                <td>${result.initial_interview_date ? new Date(result.initial_interview_date).toLocaleDateString('ja-JP') : ''}</td>
                <td>${result.initial_interview_time}</td>
                <td>${result.email}</td>
                <td>${result.subject_characteristics}</td>
        `;
                healthCheckResultsTable.appendChild(row);
            });
        }

        function renderPagination(data) {
            pagination.innerHTML = '';
            for (let i = 1; i <= data.last_page; i++) {
                const li = document.createElement('li');
                li.className = 'page-item ' + (i === data.current_page ? 'active' : '');
                const a = document.createElement('a');
                a.href = '#';
                a.className = 'page-link';
                a.textContent = i;
                a.addEventListener('click', function (e) {
                    e.preventDefault();
                    fetchResults(i, searchKeyword.value);
                });
                li.appendChild(a);
                pagination.appendChild(li);
            }
        }


    </script>
@endsection
