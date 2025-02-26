<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ReportReasonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
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
            'Other'
        ];

        return [
            'name' => $reportReasons[array_rand($reportReasons)],
        ];
    }
}
