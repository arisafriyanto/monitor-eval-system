<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Report $report)
    {
        return $user->role === "regional" && $report->status === "pending";
    }

    public function delete(User $user, Report $report)
    {
        return $user->role === "regional" && $report->status === "pending";
    }

    public function approved(User $user, Report $report)
    {
        return $user->role === "admin" && $report->status === "pending";
    }

    public function rejected(User $user, Report $report)
    {
        return $user->role === "admin" && $report->status === "pending";
    }
}
