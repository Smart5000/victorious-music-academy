<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\StudentCourseAccessManager;
use Illuminate\Console\Command;

class SyncStudentCourseAccess extends Command
{
    protected $signature = 'academy:sync-course-access {--user= : Sync one student by user ID}';

    protected $description = 'Sync selected instruments and first course access for subscribed students.';

    public function handle(StudentCourseAccessManager $courseAccess): int
    {
        $query = User::query()
            ->students()
            ->whereNotNull('selected_instrument_id')
            ->whereHas('subscriptions', fn ($subscriptions) => $subscriptions->active())
            ->with('selectedInstrument');

        if ($userId = $this->option('user')) {
            $query->whereKey($userId);
        }

        $synced = 0;

        $query->each(function (User $user) use ($courseAccess, &$synced): void {
            if (! $user->selectedInstrument) {
                return;
            }

            $courseAccess->initializeForInstrument($user, $user->selectedInstrument);
            $synced++;
        });

        $this->info("Synced course access for {$synced} subscribed student(s).");

        return self::SUCCESS;
    }
}
