<?php

namespace App\Console\Commands;

use App\Models\HealthCheckResult;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmailsDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends an email daily to all users with an email address.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
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
        return Command::SUCCESS;
    }
}
