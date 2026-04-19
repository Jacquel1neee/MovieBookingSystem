<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hall;
use App\Models\Seat;
use Illuminate\Http\Request;

class HallController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (! auth()->user()->is_admin) {
                abort(403, 'Unauthorized access.');
            }

            return $next($request);
        });
    }

    public function index()
    {
        $halls = Hall::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.halls.index', compact('halls'));
    }

    public function create()
    {
        return view('admin.halls.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:halls|max:255',
            'experience_type' => 'required|string|max:255',
            'rows' => 'required|integer|min:1|max:26',
            'columns' => 'required|integer|min:1|max:50',
        ]);

        $hall = Hall::create([
            'name' => $request->name,
            'experience_type' => $request->experience_type,
            'rows' => $request->rows,
            'columns' => $request->columns,
            'total_seats' => $request->rows * $request->columns,
        ]);

        // Create seats
        for ($row = 1; $row <= $request->rows; $row++) {
            for ($col = 1; $col <= $request->columns; $col++) {
                $rowLetter = chr(64 + $row); // A, B, C, etc.
                $seatNumber = $rowLetter.$col;

                Seat::create([
                    'hall_id' => $hall->id,
                    'row' => $row,
                    'column' => $col,
                    'seat_number' => $seatNumber,
                    'type' => ($row == $request->rows) ? 'vip' : 'regular', // Last row is VIP
                ]);
            }
        }

        return redirect()->route('admin.halls.index')
            ->with('success', 'Hall created successfully with seats.');
    }

    public function edit(Hall $hall)
    {
        return view('admin.halls.edit', compact('hall'));
    }

    public function update(Request $request, Hall $hall)
    {
        $request->validate([
            'name' => 'required|max:255|unique:halls,name,'.$hall->id,
            'experience_type' => 'required|string|max:255',
        ]);

        $hall->update($request->only('name', 'experience_type'));

        return redirect()->route('admin.halls.index')
            ->with('success', 'Hall updated successfully.');
    }

    public function destroy(Hall $hall)
    {
        $hall->delete();

        return redirect()->route('admin.halls.index')
            ->with('success', 'Hall deleted successfully.');
    }

    public function seats(Hall $hall)
    {
        $seats = $hall->seats()->orderBy('row')->orderBy('column')->get();

        return view('admin.halls.seats', compact('hall', 'seats'));
    }

    public function updateSeats(Request $request, Hall $hall)
    {
        foreach ($request->seat_types as $rowNum => $type) {
            $hall->seats()->where('row', $rowNum)->update(['type' => $type]);
        }

        return redirect()->route('admin.halls.seats', $hall)
            ->with('success', 'Seat types updated successfully.');
    }
}
