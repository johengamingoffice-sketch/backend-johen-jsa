<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\MeetingRequest;
use Illuminate\Http\Request;

class MeetingApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Meeting::with('creator');

        if ($request->date) {
            $query->whereDate('date', $request->date);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->month && $request->year) {
            $query->whereMonth('date', $request->month)
                  ->whereYear('date', $request->year);
        }

        if ($request->room) {
            $query->where('room', $request->room);
        }

        $recurring = Meeting::whereNotNull('recurring_type')->get();
        $nonRecurring = $query->orderBy('date')->orderBy('start_time')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'recurring' => $recurring,
                'meetings' => $nonRecurring,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'nullable|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'room' => 'required|string|max:255',
            'recurring_type' => 'nullable|in:weekly,biweekly,monthly',
            'recurring_day' => 'nullable|string|max:20',
            'team' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:booked,ongoing,queue',
        ]);

        $validated['created_by'] = $request->user()->id;
        $meeting = Meeting::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Meeting berhasil dibuat',
            'data' => $meeting->load('creator'),
        ], 201);
    }

    public function show(Meeting $meeting)
    {
        return response()->json([
            'success' => true,
            'data' => $meeting->load('creator'),
        ]);
    }

    public function update(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'nullable|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'room' => 'required|string|max:255',
            'recurring_type' => 'nullable|in:weekly,biweekly,monthly',
            'recurring_day' => 'nullable|string|max:20',
            'team' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:booked,ongoing,queue,completed,cancelled',
        ]);

        $meeting->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Meeting berhasil diupdate',
            'data' => $meeting->load('creator'),
        ]);
    }

    public function destroy(Meeting $meeting)
    {
        $meeting->delete();

        return response()->json([
            'success' => true,
            'message' => 'Meeting berhasil dihapus',
        ]);
    }

    public function updateStatus(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'status' => 'required|in:booked,ongoing,queue,completed,cancelled',
            'actual_end_time' => 'nullable|date',
        ]);

        $meeting->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Status meeting berhasil diupdate',
            'data' => $meeting->load('creator'),
        ]);
    }

    public function meetingRequests(Request $request)
    {
        $query = MeetingRequest::with(['employee', 'approver']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        $requests = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $requests,
        ]);
    }

    public function storeMeetingRequest(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'room' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'why' => 'nullable|string',
            'what' => 'nullable|string',
            'how' => 'nullable|string',
            'employee_id' => 'required|exists:employees,id',
        ]);

        $meetingRequest = MeetingRequest::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan meeting berhasil diajukan',
            'data' => $meetingRequest->load('employee'),
        ], 201);
    }

    public function approveMeetingRequest(Request $request, MeetingRequest $meetingRequest)
    {
        if ($meetingRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan sudah diproses sebelumnya',
            ], 409);
        }

        $meetingRequest->update([
            'status' => 'disetujui',
            'approved_by' => $request->user()->id,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan meeting disetujui',
            'data' => $meetingRequest->load(['employee', 'approver']),
        ]);
    }

    public function rejectMeetingRequest(Request $request, MeetingRequest $meetingRequest)
    {
        if ($meetingRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan sudah diproses sebelumnya',
            ], 409);
        }

        $validated = $request->validate([
            'notes' => 'required|string',
        ]);

        $meetingRequest->update([
            'status' => 'ditolak',
            'approved_by' => $request->user()->id,
            'notes' => $validated['notes'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan meeting ditolak',
            'data' => $meetingRequest->load(['employee', 'approver']),
        ]);
    }
}
