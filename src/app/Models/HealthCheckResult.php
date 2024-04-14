<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthCheckResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'insurance_provider',
        'affiliated_insurer',
        'insurer_number',
        'insurance_symbol',
        'insurance_number',
        'kanji_name',
        'kana_surname',
        'kana_name',
        'support_level',
        'gender',
        'date_of_birth',
        'age',
        'medical_examination_date',
        'height',
        'weight_at_medical_examination',
        'bmi',
        'abdominal_circumference_at_medical_examination',
        'systolic_1',
        'systolic_2',
        'systolic_other',
        'diastolic_1',
        'diastolic_2',
        'diastolic_other',
        'neutral_fat',
        'fasting_neutral_fat',
        'occasional_neutral_fat',
        'hdl',
        'ldl',
        'got',
        'gpt',
        'γ_gt',
        'fasting_blood_sugar',
        'occasional_blood_sugar',
        'hba1c',
        'medication_1',
        'medication_2',
        'medication_3',
        'smoking',
        'initial_interview_date',
        'initial_interview_time',
        'email',
        'subject_characteristics',
    ];
}
