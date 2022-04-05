<?php

namespace App\Http\Controllers;

use App\Exports\EventsExport;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*$events = Event::all();*/

        $events = Event::select('id','event_name','location','start_date','end_date', 'description', 'banner')
            ->orderBy('id', 'desc')
            ->get();
        return response()->json([
            'events' => $events
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $request->validate([
           'event_name' => 'required|unique:events',
           'location' => 'required',
           'start_date' => 'required',
           'end_date' => 'required|same:start_date',
           'description' => 'required',
           'banner' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        /*if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
               'errors' =>  $error
            ]);
        }*/

        $input = $request->all();

        /*$fileName = time().'.'.$request->file->getClientOriginalExtension();
        $request->file->move(public_path('upload'), $fileName);*/

        if ($image = $request->file('banner')) {
            $destinationPath = 'image/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['banner'] = "$profileImage";
        }

        Event::create($input);

        return response()->json([
            'success' => 'Event created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = Event::find($id);

        return response()->json([
            'event' => $event
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
           'event_name' => 'required',
           'location' => 'required',
           'start_date' => 'required',
           'end_date' => 'required',
            'description' => 'required',
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
            'end_date' => $request->input('end_date'),
            'description' => $request->input('description'),
        ]);

        return response([
           'success' => 'Event updated successfully'
        ]);
    }

    public function updateRecord(Request $request, $id)
    {
        $request->validate([
            'event_name' => 'required',
            'location' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'description' => 'required',
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
            'end_date' => $request->input('end_date'),
            'description' => $request->input('description'),
        ]);

        return response([
            'success' => 'Event updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::find($id);
        $event->delete();

        return response()->json([
           'success' => 'Event deleted successfully'
        ]);
    }

    public function bulkDelete(Request $request){
        $ids = $request->ids;
        foreach ($ids  as $id){
                Event::find($id)->delete();
        }
        return response()->json([
            'success' => 'Events are deleted'
        ]);
    }

    public function export($events)
    {
        $eventsArray = explode(',', $events);
        return (new EventsExport($eventsArray))->download('events.xlsx');
    }
}
