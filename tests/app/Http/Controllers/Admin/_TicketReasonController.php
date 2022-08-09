<?php

namespace App\Http\Controllers\Admin;

use App\Models\TicketReason;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class TicketReasonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $ticket_reasons = TicketReason::where('deleted_at','')->orderBy('id', 'DESC')->get();
        return view('admin.ticket_reasons.index', compact('ticket_reasons'));
    }

    public function create()
    {
        return view('admin.ticket_reasons.create');
    }

    public function store(Request $request)
    {
        TicketReason::create($request->validate([
            'reason_description' => 'required',
            'status' => ''
        ]));

        return redirect()->route('admin.ticket-reasons.index')->with('success', 'Ticket Reason added successfully.');
    }

    public function edit($id)
    {
        $ticket_reason = TicketReason::findOrFail($id);        
        return view('admin.ticket_reasons.edit', compact('ticket_reason'));
    }

    public function update(Request $request, $id)
    {
        $ticket_reason = TicketReason::findOrFail($id);

        $input = $request->validate([
            'reason_description' => 'required',
            'status' => ''
        ]);

        $input['status'] = $input['status']??0;

        $ticket_reason->update($input);        

        return redirect()->route('admin.ticket-reasons.index')->with('success', 'Ticket Reason updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $ticket_reason = TicketReason::findOrFail($id);
        $ticket_reason->update(['deleted_at' => Carbon::now()]);
        $request->session()->flash('success', 'Ticket Reason deleted successfully.');
    }
}
