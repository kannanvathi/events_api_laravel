<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventUpdateController extends Controller
{
    public function updateRecord(Request $request, $id)
    {
        $request->validate([
            'event_name' => 'required',
            'location' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'banner'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input = $request->all();
        if ($image = $request->file('banner')) {
            $destinationPath = 'image/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['banner'] = "$profileImage";
        }

        $event = Event::find($id);
        $event->update([
            'event_name' => $request->input('event_name'),
            'location' => $request->input('location'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date')
        ]);

        return response([
            'success' => 'Event updated successfully'
        ]);
    }

}
