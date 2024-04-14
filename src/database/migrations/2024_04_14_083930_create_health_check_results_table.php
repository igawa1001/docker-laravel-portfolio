<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('health_check_results', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('insurance_provider'); // 委託元保険者
            $table->string('affiliated_insurer'); // 所属保険者
            $table->string('insurer_number'); // 保険者番号
            $table->string('insurance_symbol'); // 保険証記号
            $table->string('insurance_number'); // 保険証番号
            $table->string('kanji_name'); // 漢字氏名
            $table->string('kana_last_name'); // カナ（姓）
            $table->string('kana_first_name'); // カナ（名）
            $table->string('support_level')->nullable(); // 支援レベル
            $table->string('gender'); // 性別
            $table->date('birth'); // 生年月日
            $table->integer('age'); // 年齢
            $table->date('medical_examination_date')->nullable(); // 健診日
            $table->decimal('height', 5, 1)->nullable(); // 身長
            $table->decimal('weight_at_medical_examination', 5, 1)->nullable(); // 健診時体重
            $table->decimal('bmi', 4, 1)->nullable(); // BMI
            $table->decimal('abdominal_circumference_at_medical_examination', 4, 1)->nullable(); // 健診時腹囲
            $table->integer('systolic_1')->nullable(); // 収縮期1
            $table->integer('systolic_2')->nullable(); // 収縮期2
            $table->integer('systolic_other')->nullable(); // 収縮期その他
            $table->integer('diastolic_1')->nullable(); // 拡張期1
            $table->integer('diastolic_2')->nullable(); // 拡張期2
            $table->integer('diastolic_other')->nullable(); // 拡張期その他
            $table->integer('neutral_fat')->nullable(); // 中性脂肪
            $table->integer('fasting_neutral_fat')->nullable(); // 空腹時中性脂肪
            $table->integer('occasional_neutral_fat')->nullable(); // 随時中性脂肪
            $table->integer('hdl')->nullable(); // HDL
            $table->integer('ldl')->nullable(); // LDL
            $table->integer('got')->nullable(); // GOT
            $table->integer('gpt')->nullable(); // GPT
            $table->integer('γ_gt')->nullable(); // γ-GT
            $table->integer('fasting_blood_sugar')->nullable(); // 空腹時血糖
            $table->integer('occasional_blood_sugar')->nullable(); // 随時血糖
            $table->decimal('hba1c', 3, 1)->nullable(); // HBA1C
            $table->string('medication_1')->nullable(); // 服薬1
            $table->string('medication_2')->nullable(); // 服薬2
            $table->string('medication_3')->nullable(); // 服薬3
            $table->string('smoking')->nullable(); // 喫煙
            $table->date('initial_interview_date')->nullable(); // 初回面談日
            $table->time('initial_interview_time')->nullable(); // 初回面談時間
            $table->string('email')->nullable(); // メールアドレス
            $table->text('subject_characteristics')->nullable(); // 対象者の特徴
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('health_check_results');
    }
};
