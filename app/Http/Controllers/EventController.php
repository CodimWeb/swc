<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->sendResponse(
            EventResource::collection(Event::with('users')->get())
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Event::create([
            'title' => $request->post('title'),
            'description' => $request->post('description'),
            'date' => $request->post('date'),
            'user_id' => auth()->user()->id,
        ]);
        return $this->sendResponse(
            EventResource::collection(Event::with('users')->get())
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function subscribe(string $id)
    {
        $event = Event::where('id', $id)->first();
        $event->users()->attach(auth()->user()->id);
        return $this->sendResponse(
            EventResource::collection(Event::with('users')->get())
        );
    }

    public function unsubscribe(string $id)
    {
        $event = Event::where('id', $id)->first();
        $event->users()->detach(auth()->user()->id);
        return $this->sendResponse(
            EventResource::collection(Event::with('users')->get())
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::find($id);
        if($event) {
            $event->delete();
            return $this->sendResponse(
                EventResource::collection(Event::with('users')->get())
            );
        }
        else {
            return $this->sendError('not found', code: 404);
        }
    }
}
