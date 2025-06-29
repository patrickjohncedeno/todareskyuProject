<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintRegistered;
use App\Models\ComplaintUnregistered;
use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'day');

        // Combine data from both registered and unregistered complaints for status counts
        $regPendingCount = DB::table('registered_complaint')->where('status', 'Pending')->count();
        $unregPendingCount = DB::table('unregistered_complaint')->where('status', 'Pending')->count();
        $regInProcessCount = DB::table('registered_complaint')->where('status', 'In Process')->count();
        $unregInProcessCount = DB::table('unregistered_complaint')->where('status', 'In Process')->count();
        $regDeniedCount = DB::table('registered_complaint')->where('status', 'Denied')->count();
        $unregDeniedCount = DB::table('unregistered_complaint')->where('status', 'Denied')->count();
        $regSuccessCount = DB::table('registered_complaint')->where('status', 'Settled')->count();
        $unregSuccessCount = DB::table('unregistered_complaint')->where('status', 'Settled')->count();
        $regUnresolvedCount = DB::table('registered_complaint')->where('status', 'Unresolved')->count();
        $unregUnresolvedCount = DB::table('unregistered_complaint')->where('status', 'Unresolved')->count();


        $registeredStatuses = DB::table('registered_complaint')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->whereIn('status', ['Settled', 'Unresolved'])
            ->groupBy('status')
            ->get();


        $unregisteredStatuses = DB::table('unregistered_complaint')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->whereIn('status', ['Settled', 'Unresolved'])
            ->groupBy('status')
            ->get();


        $registeredStatusData = [
            'labels' => ['Settled', 'Unresolved'],
            'datasets' => [
                [
                    'label' => 'Registered Complaints',
                    'data' => array_fill(0, 2, 0),
                    'backgroundColor' => [
                        'rgba(75, 192, 192, 1)', // Pending
                        'rgba(54, 162, 235, 1)', // In Process
                        'rgba(153, 102, 255, 1)', // Resolved
                        'rgba(255, 205, 86, 1)',  // Unresolved
                        'rgba(255, 99, 132, 1)'   // Denied
                    ],
                    'borderColor' => [
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    'borderWidth' => 1
                ]
            ]
        ];

        $unregisteredStatusData = [
            'labels' => ['Settled', 'Unresolved'],
            'datasets' => [
                [
                    'label' => 'Unregistered Complaints',
                    'data' => array_fill(0, 2, 0),
                    'backgroundColor' => [
                        'rgba(153, 102, 255, 1)', // Resolved
                        'rgba(255, 205, 86, 1)',  // Unresolved
                    ],
                    'borderColor' => [
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 205, 86, 1)',
                    ],
                    'borderWidth' => 1
                ]
            ]
        ];

        // Populate data for registered complaints
        foreach ($registeredStatuses as $status) {
            $index = array_search($status->status, $registeredStatusData['labels']);
            if ($index !== false) {
                $registeredStatusData['datasets'][0]['data'][$index] += $status->count;
            }
        }

        // Populate data for unregistered complaints
        foreach ($unregisteredStatuses as $status) {
            $index = array_search($status->status, $unregisteredStatusData['labels']);
            if ($index !== false) {
                $unregisteredStatusData['datasets'][0]['data'][$index] += $status->count;
            }
        }



        // VIOLATION FREQUENCY
        $totalViolations = DB::table('registered_complaint')->where('status', 'Success')->count()
            + DB::table('unregistered_complaint')->where('status', 'Success')->count();

        $violationCounts = DB::table('registered_complaint')
            ->select('violationID', DB::raw('COUNT(*) as count'))
            ->where('status', 'Settled')
            ->groupBy('violationID')
            ->get()
            ->merge(DB::table('unregistered_complaint')
                ->select('violationID', DB::raw('COUNT(*) as count'))
                ->where('status', 'Settled')
                ->groupBy('violationID')
                ->get());

        $totalViolations = $violationCounts->sum('count');
        $violationPercentages = $violationCounts->map(function ($item) use ($totalViolations) {
            return [
                'violation' => Violation::find($item->violationID)->violationName,
                'count' => $item->count,
                'percentage' => $totalViolations > 0 ? round(($item->count / $totalViolations) * 100, 2) : 0,
            ];
        })->sortByDesc('percentage');


        $registeredComplaintCount = ComplaintRegistered::where('status', 'Settled')->count();
        $unregisteredComplaintCount = ComplaintUnregistered::where('status', 'Settled')->count();






        //Registered and Unregistered Complaints (Pending, In Process, Denied) graphs
        $registeredStatusesPID = DB::table('registered_complaint')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->whereIn('status', ['Pending', 'In Process', 'Denied'])
            ->groupBy('status')
            ->get();

        $unregisteredStatusesPID = DB::table('unregistered_complaint')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->whereIn('status', ['Pending', 'In Process', 'Denied'])
            ->groupBy('status')
            ->get();

        $registeredStatusDataForNewGraph = [
            'labels' => ['Pending', 'In Process', 'Denied'],
            'datasets' => [
                [
                    'label' => 'Registered Complaints (Pending, In Process, Denied)',
                    'data' => array_fill(0, 3, 0),
                    'backgroundColor' => [
                        'rgba(75, 192, 192, 1)', // Pending
                        'rgba(54, 162, 235, 1)', // In Process
                        'rgba(255, 99, 132, 1)', // Denied
                    ],
                    'borderColor' => [
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                    ],
                    'borderWidth' => 1
                ]
            ]
        ];

        // Unregistered Complaints - Pending, In Process, Denied
        $unregisteredStatusDataForNewGraph = [
            'labels' => ['Pending', 'In Process', 'Denied'],
            'datasets' => [
                [
                    'label' => 'Unregistered Complaints (Pending, In Process, Denied)',
                    'data' => array_fill(0, 3, 0),
                    'backgroundColor' => [
                        'rgba(255, 159, 64, 1)', // Pending
                        'rgba(75, 192, 192, 1)', // In Process
                        'rgba(255, 99, 132, 1)', // Denied
                    ],
                    'borderColor' => [
                        'rgba(255, 159, 64, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                    ],
                    'borderWidth' => 1
                ]
            ]
        ];

        foreach ($registeredStatusesPID as $status) {
            $index = array_search($status->status, $registeredStatusDataForNewGraph['labels']);
            if ($index !== false) {
                $registeredStatusDataForNewGraph['datasets'][0]['data'][$index] += $status->count;
            }
        }

        // Populate data for unregistered complaints
        foreach ($unregisteredStatusesPID as $status) {
            $index = array_search($status->status, $unregisteredStatusDataForNewGraph['labels']);
            if ($index !== false) {
                $unregisteredStatusDataForNewGraph['datasets'][0]['data'][$index] += $status->count;
            }
        }





        return response()->view('dashboard', [
            'registeredStatusDataForNewGraph' => json_encode($registeredStatusDataForNewGraph),
            'unregisteredStatusDataForNewGraph' => json_encode($unregisteredStatusDataForNewGraph),
            'registeredStatusData' => json_encode($registeredStatusData),
            'unregisteredStatusData' => json_encode($unregisteredStatusData),
            'violationPercentages' => $violationPercentages,
            'regPendingCount' => $regPendingCount,
            'unregPendingCount' => $unregPendingCount,
            'regInProcessCount' => $regInProcessCount,
            'unregInProcessCount' => $unregInProcessCount,
            'regDeniedCount' => $regDeniedCount,
            'unregDeniedCount' => $unregDeniedCount,
            'regSuccessCount' => $regSuccessCount,
            'unregSuccessCount' => $unregSuccessCount,
            'regUnresolvedCount' => $regUnresolvedCount,
            'unregUnresolvedCount' => $unregUnresolvedCount,
            'currentFilter' => $filter,
            'registeredComplaintCount' => $registeredComplaintCount,
            'unregisteredComplaintCount' => $unregisteredComplaintCount,
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function getViolationsData(Request $request)
    {

        $filter = $request->input('filter', 'day');
        if ($filter == 'month') {
            // Data by month
            $registeredViolations = DB::table('registered_complaint')
                ->select(DB::raw('MONTH(dateSubmitted) as month'), DB::raw('COUNT(*) as count'))
                ->where('status', 'Settled')
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $unregisteredViolations = DB::table('unregistered_complaint')
                ->select(DB::raw('MONTH(dateSubmitted) as month'), DB::raw('COUNT(*) as count'))
                ->where('status', 'Settled')
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // Initialize the array to hold counts for each month
            $violationCounts = array_fill(1, 12, 0);

            // Add registered violations
            foreach ($registeredViolations as $violation) {
                $violationCounts[$violation->month] += $violation->count;
            }

            // Add unregistered violations
            foreach ($unregisteredViolations as $violation) {
                $violationCounts[$violation->month] += $violation->count;
            }

            $labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            $timeFrame = 'Month';
        } elseif ($filter == 'year') {
            // Data by year
            $registeredViolations = DB::table('registered_complaint')
                ->select(DB::raw('YEAR(dateSubmitted) as year'), DB::raw('COUNT(*) as count'))
                ->where('status', 'Settled')
                ->groupBy('year')
                ->orderBy('year')
                ->get();

            $unregisteredViolations = DB::table('unregistered_complaint')
                ->select(DB::raw('YEAR(dateSubmitted) as year'), DB::raw('COUNT(*) as count'))
                ->where('status', 'Settled')
                ->groupBy('year')
                ->orderBy('year')
                ->get();

            // Initialize the array to hold counts for each year
            $violationCounts = [];

            // Add registered violations
            foreach ($registeredViolations as $violation) {
                if (!isset($violationCounts[$violation->year])) {
                    $violationCounts[$violation->year] = 0;
                }
                $violationCounts[$violation->year] += $violation->count;
            }

            // Add unregistered violations
            foreach ($unregisteredViolations as $violation) {
                if (!isset($violationCounts[$violation->year])) {
                    $violationCounts[$violation->year] = 0;
                }
                $violationCounts[$violation->year] += $violation->count;
            }

            $labels = array_keys($violationCounts); // The years will be the labels
            $timeFrame = 'Year';
        } else {
            // Data by day of the week
            $registeredViolations = DB::table('registered_complaint')
                ->select(DB::raw('DAYOFWEEK(dateSubmitted) as day_of_week'), DB::raw('COUNT(*) as count'))
                ->where('status', 'Settled')
                ->groupBy('day_of_week')
                ->orderBy('day_of_week')
                ->get();

            $unregisteredViolations = DB::table('unregistered_complaint')
                ->select(DB::raw('DAYOFWEEK(dateSubmitted) as day_of_week'), DB::raw('COUNT(*) as count'))
                ->where('status', 'Settled')
                ->groupBy('day_of_week')
                ->orderBy('day_of_week')
                ->get();

            // Initialize the array to hold counts for each day of the week
            $violationCounts = array_fill(1, 7, 0);

            // Add registered violations
            foreach ($registeredViolations as $violation) {
                $violationCounts[$violation->day_of_week] += $violation->count;
            }

            // Add unregistered violations
            foreach ($unregisteredViolations as $violation) {
                $violationCounts[$violation->day_of_week] += $violation->count;
            }

            $labels = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            $timeFrame = 'Day';
        }
        $violationData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Violations Per ' . $timeFrame,
                    'data' => array_values($violationCounts),
                    'tension' => 0.3,
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'fill' => true
                ]
            ]
        ];


        return response()->json($violationData);
    }


    public function filter(Request $request)
    {
        
        
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        // $users = User::whereDate('dateSubmitted','>=',$start_date)
        //             ->whereDate('dateSubmitted','<=',$end_date)
        //             ->get();

        // Combine data from both registered and unregistered complaints for status counts
        $regPendingCount = DB::table('registered_complaint')->where('status', 'Pending')
            ->whereDate('dateSubmitted', '>=', $start_date)
            ->whereDate('dateSubmitted', '<=', $end_date)
            ->count();
        $unregPendingCount = DB::table('unregistered_complaint')->where('status', 'Pending')
            ->whereDate('dateSubmitted', '>=', $start_date)
            ->whereDate('dateSubmitted', '<=', $end_date)
            ->count();
        $regInProcessCount = DB::table('registered_complaint')->where('status', 'In Process')
            ->whereDate('dateSubmitted', '>=', $start_date)
            ->whereDate('dateSubmitted', '<=', $end_date)
            ->count();
        $unregInProcessCount = DB::table('unregistered_complaint')->where('status', 'In Process')
            ->whereDate('dateSubmitted', '>=', $start_date)
            ->whereDate('dateSubmitted', '<=', $end_date)
            ->count();
        $regDeniedCount = DB::table('registered_complaint')->where('status', 'Denied')
            ->whereDate('dateSubmitted', '>=', $start_date)
            ->whereDate('dateSubmitted', '<=', $end_date)
            ->count();
        $unregDeniedCount = DB::table('unregistered_complaint')->where('status', 'Denied')
            ->whereDate('dateSubmitted', '>=', $start_date)
            ->whereDate('dateSubmitted', '<=', $end_date)
            ->count();
        $regSuccessCount = DB::table('registered_complaint')->where('status', 'Settled')
            ->whereDate('dateSubmitted', '>=', $start_date)
            ->whereDate('dateSubmitted', '<=', $end_date)
            ->count();
        $unregSuccessCount = DB::table('unregistered_complaint')->where('status', 'Settled')
            ->whereDate('dateSubmitted', '>=', $start_date)
            ->whereDate('dateSubmitted', '<=', $end_date)
            ->count();
        $regUnresolvedCount = DB::table('registered_complaint')->where('status', 'Unresolved')
            ->whereDate('dateSubmitted', '>=', $start_date)
            ->whereDate('dateSubmitted', '<=', $end_date)
            ->count();
        $unregUnresolvedCount = DB::table('unregistered_complaint')->where('status', 'Unresolved')
            ->whereDate('dateSubmitted', '>=', $start_date)
            ->whereDate('dateSubmitted', '<=', $end_date)
            ->count();


        $registeredStatuses = DB::table('registered_complaint')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->whereIn('status', ['Settled', 'Unresolved'])
            ->whereDate('dateSubmitted', '>=', $start_date)
            ->whereDate('dateSubmitted', '<=', $end_date)
            ->groupBy('status')
            ->get();


        $unregisteredStatuses = DB::table('unregistered_complaint')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->whereIn('status', ['Settled', 'Unresolved'])
            ->whereDate('dateSubmitted', '>=', $start_date)
            ->whereDate('dateSubmitted', '<=', $end_date)
            ->groupBy('status')
            ->get();


        $registeredStatusData = [
            'labels' => ['Settled', 'Unresolved'],
            'datasets' => [
                [
                    'label' => 'Registered Complaints',
                    'data' => array_fill(0, 2, 0),
                    'backgroundColor' => [
                        'rgba(75, 192, 192, 1)', // Pending
                        'rgba(54, 162, 235, 1)', // In Process
                        'rgba(153, 102, 255, 1)', // Resolved
                        'rgba(255, 205, 86, 1)',  // Unresolved
                        'rgba(255, 99, 132, 1)'   // Denied
                    ],
                    'borderColor' => [
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    'borderWidth' => 1
                ]
            ]
        ];

        $unregisteredStatusData = [
            'labels' => ['Settled', 'Unresolved'],
            'datasets' => [
                [
                    'label' => 'Unregistered Complaints',
                    'data' => array_fill(0, 2, 0),
                    'backgroundColor' => [
                        'rgba(153, 102, 255, 1)', // Resolved
                        'rgba(255, 205, 86, 1)',  // Unresolved
                    ],
                    'borderColor' => [
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 205, 86, 1)',
                    ],
                    'borderWidth' => 1
                ]
            ]
        ];

        // Populate data for registered complaints
        foreach ($registeredStatuses as $status) {
            $index = array_search($status->status, $registeredStatusData['labels']);
            if ($index !== false) {
                $registeredStatusData['datasets'][0]['data'][$index] += $status->count;
            }
        }

        // Populate data for unregistered complaints
        foreach ($unregisteredStatuses as $status) {
            $index = array_search($status->status, $unregisteredStatusData['labels']);
            if ($index !== false) {
                $unregisteredStatusData['datasets'][0]['data'][$index] += $status->count;
            }
        }



        // VIOLATION FREQUENCY
        $totalViolations = DB::table('registered_complaint')->where('status', 'Success')->whereDate('dateSubmitted', '>=', $start_date)
            ->whereDate('dateSubmitted', '<=', $end_date)->count()
            + DB::table('unregistered_complaint')->where('status', 'Success')->whereDate('dateSubmitted', '>=', $start_date)
            ->whereDate('dateSubmitted', '<=', $end_date)->count();

        $violationCounts = DB::table('registered_complaint')
            ->select('violationID', DB::raw('COUNT(*) as count'))
            ->where('status', 'Settled')
            ->whereDate('dateSubmitted', '>=', $start_date)
            ->whereDate('dateSubmitted', '<=', $end_date)
            ->groupBy('violationID')
            ->get()
            ->merge(DB::table('unregistered_complaint')
                ->select('violationID', DB::raw('COUNT(*) as count'))
                ->where('status', 'Settled')
                ->whereDate('dateSubmitted', '>=', $start_date)
                ->whereDate('dateSubmitted', '<=', $end_date)
                ->groupBy('violationID')
                ->get());

        $totalViolations = $violationCounts->sum('count');
        $violationPercentages = $violationCounts->map(function ($item) use ($totalViolations) {
            return [
                'violation' => Violation::find($item->violationID)->violationName,
                'count' => $item->count,
                'percentage' => $totalViolations > 0 ? round(($item->count / $totalViolations) * 100, 2) : 0,
            ];
        })->sortByDesc('percentage');


        $registeredComplaintCount = ComplaintRegistered::where('status', 'Settled')->whereDate('dateSubmitted', '>=', $start_date)
        ->whereDate('dateSubmitted', '<=', $end_date)->count();
        $unregisteredComplaintCount = ComplaintUnregistered::where('status', 'Settled')->whereDate('dateSubmitted', '>=', $start_date)
        ->whereDate('dateSubmitted', '<=', $end_date)->count();






        //Registered and Unregistered Complaints (Pending, In Process, Denied) graphs
        $registeredStatusesPID = DB::table('registered_complaint')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->whereIn('status', ['Pending', 'In Process', 'Denied'])
            ->whereDate('dateSubmitted', '>=', $start_date)
            ->whereDate('dateSubmitted', '<=', $end_date)
            ->groupBy('status')
            ->get();

        $unregisteredStatusesPID = DB::table('unregistered_complaint')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->whereIn('status', ['Pending', 'In Process', 'Denied'])
            ->whereDate('dateSubmitted', '>=', $start_date)
            ->whereDate('dateSubmitted', '<=', $end_date)
            ->groupBy('status')
            ->get();

        $registeredStatusDataForNewGraph = [
            'labels' => ['Pending', 'In Process', 'Denied'],
            'datasets' => [
                [
                    'label' => 'Registered Complaints (Pending, In Process, Denied)',
                    'data' => array_fill(0, 3, 0),
                    'backgroundColor' => [
                        'rgba(75, 192, 192, 1)', // Pending
                        'rgba(54, 162, 235, 1)', // In Process
                        'rgba(255, 99, 132, 1)', // Denied
                    ],
                    'borderColor' => [
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                    ],
                    'borderWidth' => 1
                ]
            ]
        ];

        // Unregistered Complaints - Pending, In Process, Denied
        $unregisteredStatusDataForNewGraph = [
            'labels' => ['Pending', 'In Process', 'Denied'],
            'datasets' => [
                [
                    'label' => 'Unregistered Complaints (Pending, In Process, Denied)',
                    'data' => array_fill(0, 3, 0),
                    'backgroundColor' => [
                        'rgba(255, 159, 64, 1)', // Pending
                        'rgba(75, 192, 192, 1)', // In Process
                        'rgba(255, 99, 132, 1)', // Denied
                    ],
                    'borderColor' => [
                        'rgba(255, 159, 64, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                    ],
                    'borderWidth' => 1
                ]
            ]
        ];

        foreach ($registeredStatusesPID as $status) {
            $index = array_search($status->status, $registeredStatusDataForNewGraph['labels']);
            if ($index !== false) {
                $registeredStatusDataForNewGraph['datasets'][0]['data'][$index] += $status->count;
            }
        }

        // Populate data for unregistered complaints
        foreach ($unregisteredStatusesPID as $status) {
            $index = array_search($status->status, $unregisteredStatusDataForNewGraph['labels']);
            if ($index !== false) {
                $unregisteredStatusDataForNewGraph['datasets'][0]['data'][$index] += $status->count;
            }
        }

        return response()->view('dashboard', [
            'registeredStatusDataForNewGraph' => json_encode($registeredStatusDataForNewGraph),
            'unregisteredStatusDataForNewGraph' => json_encode($unregisteredStatusDataForNewGraph),
            'registeredStatusData' => json_encode($registeredStatusData),
            'unregisteredStatusData' => json_encode($unregisteredStatusData),
            'violationPercentages' => $violationPercentages,
            'regPendingCount' => $regPendingCount,
            'unregPendingCount' => $unregPendingCount,
            'regInProcessCount' => $regInProcessCount,
            'unregInProcessCount' => $unregInProcessCount,
            'regDeniedCount' => $regDeniedCount,
            'unregDeniedCount' => $unregDeniedCount,
            'regSuccessCount' => $regSuccessCount,
            'unregSuccessCount' => $unregSuccessCount,
            'regUnresolvedCount' => $regUnresolvedCount,
            'unregUnresolvedCount' => $unregUnresolvedCount,
            //  
            'registeredComplaintCount' => $registeredComplaintCount,
            'unregisteredComplaintCount' => $unregisteredComplaintCount,
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }
}
