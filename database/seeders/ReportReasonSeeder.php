<?php

namespace Database\Seeders;

use App\Models\ReportReason;
use Illuminate\Database\Seeder;

class ReportReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reportReasons = [
            'Spam',
            'Harassment',
            'Inappropriate Content',
            'Fake Information',
            'Hate Speech',
            'Copyright Infringement',
            'Privacy Violation',
            'Scam or Fraud',
            'Violence or Threat',
            'Other',
        ];

        foreach ($reportReasons as $reason) {
            ReportReason::create([
                'name' => $reason,
            ]);
        }
    }
}
