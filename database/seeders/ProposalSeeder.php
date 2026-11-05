<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Proposal;
use App\Models\ProposalActivity;
use App\Models\ProposalKpi;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProposalSeeder extends Seeder
{
    public function run(): void
    {
        $department = Department::first();
        $owner = User::where('email', 'operations@college.edu')->first() ?? User::first();
        if (!$department || !$owner) {
            return;
        }

        $proposal = Proposal::firstOrCreate(
            ['reference_number' => 'PROP-2026-0001'],
            [
                'title' => 'Strategic Plan for Operations Improvement',
                'type' => 'strategic_plan',
                'department_id' => $department->id,
                'owner_id' => $owner->id,
                'summary' => 'Improve operational efficiency.',
                'background' => 'Background details.',
                'objectives' => 'Objectives.',
                'expected_outcomes' => 'Outcomes.',
                'implementation_period_start' => '2026-07-01',
                'implementation_period_end' => '2027-06-30',
                'total_budget_amount' => 5000000,
                'funding_source' => 'Internal',
                'status' => 'approved',
                'submitted_by' => $owner->id,
                'submitted_at' => now(),
                'approved_by' => $owner->id,
                'approved_at' => now(),
            ]
        );

        ProposalActivity::firstOrCreate(
            ['proposal_id' => $proposal->id, 'title' => 'Phase 1 Implementation'],
            [
                'description' => 'Initial phase',
                'responsible_person_id' => $owner->id,
                'start_date' => '2026-07-01',
                'end_date' => '2026-12-31',
                'budget_amount' => 2500000,
                'status' => 'in_progress',
                'progress_percentage' => 40,
            ]
        );

        ProposalKpi::firstOrCreate(
            ['proposal_id' => $proposal->id, 'name' => 'Completion Rate'],
            [
                'target_value' => '100',
                'actual_value' => '40',
                'unit' => '%',
                'measurement_frequency' => 'monthly',
                'status' => 'on_track',
                'remarks' => 'In progress',
            ]
        );
    }
}