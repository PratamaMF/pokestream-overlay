<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::orderBy('created_at', 'desc')->get();

        return view('activity-log.index', compact('logs'));
    }

    public function clearHistory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'range'    => 'required|in:24_hours,1_week,1_month,3_months,1_year,all',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('SA-error', 'Validation failed. Please select the time range correctly.');
        }

        $user = Auth::user();
        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()->with('SA-error', 'Confirmation failed! The password you entered is incorrect.');
        }

        try {
            $query = ActivityLog::query();
            $now = now();
            
            $message = 'Activity history successfully cleared.';

            switch ($request->range) {
                case '24_hours':
                    $query->where('created_at', '<', $now->subHours(24));
                    $message = 'Activity logs older than 24 hours were successfully cleared.';
                    break;
                case '1_week':
                    $query->where('created_at', '<', $now->subWeeks(1));
                    $message = 'Activity logs older than 1 week were successfully cleared.';
                    break;
                case '1_month':
                    $query->where('created_at', '<', $now->subMonths(1));
                    $message = 'Activity logs older than 1 month were successfully cleared.';
                    break;
                case '3_months':
                    $query->where('created_at', '<', $now->subMonths(3));
                    $message = 'Activity logs older than 3 months were successfully cleared.';
                    break;
                case '1_year':
                    $query->where('created_at', '<', $now->subYears(1));
                    $message = 'Activity logs older than 1 year were successfully cleared.';
                    break;
                case 'all':
                    $message = 'All activity logs were successfully cleared.';
                    break;
                default:
                    return redirect()->back()->with('SA-error', 'Validation failed. Please select the time range correctly.');
            }

            $query->delete();

            return redirect()->route('activity.index')->with('SA-success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('SA-error', 'An error occurred while clearing the activity history');
        }
    }
}