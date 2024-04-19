<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HealthCheckResult;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class HealthCheckResultController extends Controller
{
    public function index()
    {
        return view('health_check_results.index');
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $page = $request->input('page', 1);
        $perPage = 10;

        $query = HealthCheckResult::query();

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('insurance_symbol', 'like', "%{$keyword}%")
                    ->orWhere('insurance_number', 'like', "%{$keyword}%")
                    ->orWhere('kanji_name', 'like', "%{$keyword}%")
                    ->orWhere('kana_last_name', 'like', "%{$keyword}%")
                    ->orWhere('kana_first_name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        $results = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($results);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        if ($request->hasFile('csv_file')) {
            $path = $request->file('csv_file')->store('temp');
            $csv = file(storage_path('app/' . $path));
            $data = array_map('str_getcsv', $csv);

            Storage::delete($path);

            $headers = array_map('trim', $data[0]);

            $expectedHeaders = ['UUID', '委託元保険者', '所属保険者', '保険者番号', '保険証記号', '保険証番号', '漢字氏名', 'カナ（姓）', 'カナ（名）', '支援レベル', '性別', '生年月日', '年齢', '健診日', '身長', '健診時体重', 'BMI', '健診時腹囲', '収縮期1', '収縮期2', '収縮期その他', '拡張期1', '拡張期2', '拡張期その他', '中性脂肪', '空腹時中性脂肪', '随時中性脂肪', 'HDL', 'LDL', 'GOT', 'GPT', 'γ-GT', '空腹時血糖', '随時血糖', 'HBA1C', '服薬1', '服薬2', '服薬3', '喫煙', '初回面談日', '初回面談時間', 'メールアドレス', '対象者の特徴'];

            if ($headers !== $expectedHeaders) {
                return back()->withErrors(['csv_file' => 'CSVフォーマットが正しくありません。']);
            }

            try {
                foreach (array_slice($data, 1) as $row) {
                    if (count($row) === count($headers)) {
                        $combined = [
                            'uuid' => $row[array_search('UUID', $headers)],
                            'insurance_provider' => $row[array_search('委託元保険者', $headers)],
                            'affiliated_insurer' => $row[array_search('所属保険者', $headers)],
                            'insurer_number' => $row[array_search('保険者番号', $headers)],
                            'insurance_symbol' => $row[array_search('保険証記号', $headers)],
                            'insurance_number' => $row[array_search('保険証番号', $headers)],
                            'kanji_name' => $row[array_search('漢字氏名', $headers)],
                            'kana_last_name' => $row[array_search('カナ（姓）', $headers)],
                            'kana_first_name' => $row[array_search('カナ（名）', $headers)],
                            'support_level' => $row[array_search('支援レベル', $headers)],
                            'gender' => $row[array_search('性別', $headers)],
                            'birth' => $this->formatDate($row, $headers, '生年月日', 'Y/m/d'),
                            'age' => $row[array_search('年齢', $headers)] !== '' ? (int)$row[array_search('年齢', $headers)] : null,
                            'medical_examination_date' => $this->formatDate($row, $headers, '健診日', 'Y/m/d'),
                            'height' => $row[array_search('身長', $headers)] !== '' ? (float)$row[array_search('身長', $headers)] : null,
                            'weight_at_medical_examination' => $row[array_search('健診時体重', $headers)] !== '' ? (float)$row[array_search('健診時体重', $headers)] : null,
                            'bmi' => $row[array_search('BMI', $headers)] !== '' ? (float)$row[array_search('BMI', $headers)] : null,
                            'abdominal_circumference_at_medical_examination' => $row[array_search('健診時腹囲', $headers)] !== '' ? (float)$row[array_search('健診時腹囲', $headers)] : null,
                            'systolic_1' => $row[array_search('収縮期1', $headers)] !== '' ? (int)$row[array_search('収縮期1', $headers)] : null,
                            'systolic_2' => $row[array_search('収縮期2', $headers)] !== '' ? (int)$row[array_search('収縮期2', $headers)] : null,
                            'systolic_other' => $row[array_search('収縮期その他', $headers)] !== '' ? (int)$row[array_search('収縮期その他', $headers)] : null,
                            'diastolic_1' => $row[array_search('拡張期1', $headers)] !== '' ? (int)$row[array_search('拡張期1', $headers)] : null,
                            'diastolic_2' => $row[array_search('拡張期2', $headers)] !== '' ? (int)$row[array_search('拡張期2', $headers)] : null,
                            'diastolic_other' => $row[array_search('拡張期その他', $headers)] !== '' ? (int)$row[array_search('拡張期その他', $headers)] : null,
                            'neutral_fat' => $row[array_search('中性脂肪', $headers)] !== '' ? (int)$row[array_search('中性脂肪', $headers)] : null,
                            'fasting_neutral_fat' => $row[array_search('空腹時中性脂肪', $headers)] !== '' ? (int)$row[array_search('空腹時中性脂肪', $headers)] : null,
                            'occasional_neutral_fat' => $row[array_search('随時中性脂肪', $headers)] !== '' ? (int)$row[array_search('随時中性脂肪', $headers)] : null,
                            'hdl' => $row[array_search('HDL', $headers)] !== '' ? (int)$row[array_search('HDL', $headers)] : null,
                            'ldl' => $row[array_search('LDL', $headers)] !== '' ? (int)$row[array_search('LDL', $headers)] : null,
                            'got' => $row[array_search('GOT', $headers)] !== '' ? (int)$row[array_search('GOT', $headers)] : null,
                            'gpt' => $row[array_search('GPT', $headers)] !== '' ? (int)$row[array_search('GPT', $headers)] : null,
                            'γ_gt' => $row[array_search('γ-GT', $headers)] !== '' ? (int)$row[array_search('γ-GT', $headers)] : null,
                            'fasting_blood_sugar' => $row[array_search('空腹時血糖', $headers)] !== '' ? (int)$row[array_search('空腹時血糖', $headers)] : null,
                            'occasional_blood_sugar' => $row[array_search('随時血糖', $headers)] !== '' ? (int)$row[array_search('随時血糖', $headers)] : null,
                            'hba1c' => $row[array_search('HBA1C', $headers)] !== '' && is_numeric(str_replace(',', '.', $row[array_search('HBA1C', $headers)])) ? (float)str_replace(',', '.', $row[array_search('HBA1C', $headers)]) : null,
                            'medication_1' => $row[array_search('服薬1', $headers)],
                            'medication_2' => $row[array_search('服薬2', $headers)],
                            'medication_3' => $row[array_search('服薬3', $headers)],
                            'smoking' => $row[array_search('喫煙', $headers)],
                            'initial_interview_date' => $this->formatDate($row, $headers, '初回面談日', 'Y/m/d'),
                            'initial_interview_time' => $this->formatTime($row, $headers, '初回面談時間'),
                            'email' => $row[array_search('メールアドレス', $headers)],
                            'subject_characteristics' => $row[array_search('対象者の特徴', $headers)],
                        ];

                        HealthCheckResult::create($combined);
                    }
                }
            } catch (\Exception $e) {
                logger()->error('Error inserting data: ' . $e->getMessage());
                return back()->withErrors(['csv_file' => 'データベースへの書き込み中にエラーが発生しました。'])->withInput();
            }

            return back()->with('success', 'CSVデータがインポートされました。');
        }

        return back()->withErrors(['csv_file' => 'ファイルがアップロードされていません。']);
    }

    public function testEmail()
    {
        $users = HealthCheckResult::whereNotNull('email')->get();
        foreach ($users as $user) {
            if (!empty($user->email) && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                Mail::raw('
特定保健指導では、①から③までを行います。
①初回面談
指導員と面談を行い、御自身の現在の体
の状態と生活習慣にあわせ、無理のない目
標を決めます。
②保健指導期間中
設定した目標に向け、食事の置き換えや
摂取量の調整、簡単な運動の習慣づけなど
に取り組みます。
③中間評価(積極的支援のみ)・最終評価
評価時に体重や腹囲などを測定し、生活
習慣の改善による効果を実感しましょう。
                        ', function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('特定保健指導についてお知らせ');
                });
            } else {
                logger()->warning("Invalid email for user ID {$user->id}: {$user->email}");
            }
        }
        return "メールが送信されました";
    }

    private function formatDate($row, $headers, $field, $format)
    {
        $dateString = $row[array_search($field, $headers)];
        $date = \DateTime::createFromFormat($format, $dateString);
        return $date !== false ? $date->format('Y-m-d') : null;
    }

    private function formatTime($row, $headers, $field)
    {
        $index = array_search($field, $headers);
        if ($index !== false && $row[$index] !== '') {
            $time = date('H:i:s', strtotime($row[$index]));
            return $time !== '00:00:00' ? $time : null;
        }
        return null;
    }

}
