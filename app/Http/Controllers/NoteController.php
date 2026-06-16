<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::orderBy('created_at', 'asc')->get();
        return view('notes.index', compact('notes'));
    }

    public function showOverlay($id)
    {
        $note = Note::findOrFail($id);
        return view('notes.overlay', compact('note'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('SA-error', 'Validation failed.');
        }

        try {
            $note = new Note();
            $note->title = $request->title;
            $note->description = $request->description;
            $note->save();

            $details = [
                'action' => 'create',
                'data' => [
                    'Title' => $note->title,
                    'Description' => $note->description
                ]
            ];

            return redirect()->back()->with('SA-success', 'Note created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('SA-error', 'Failed to create note.');
        }
    }

    public function update(Request $request, string $id)
    {
        $note = Note::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('SA-error', 'Validation failed.');
        }

        try {
            $changes = [];
            
            if ($note->title !== $request->title) {
                $changes['Title'] = [
                    'old' => $note->title,
                    'new' => $request->title
                ];
            }

            if ($note->description !== $request->description) {
                $changes['Description'] = [
                    'old' => $note->description,
                    'new' => $request->description
                ];
            }

            $note->title = $request->title;
            $note->description = $request->description;
            $note->save();

            if (!empty($changes)) {
                $details = [
                    'action' => 'update',
                    'changes' => $changes
                ];
            }

            return redirect()->back()->with('SA-success', 'Note updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('SA-error', 'Failed to update note.');
        }
    }

    public function destroy(string $id)
    {
        $note = Note::findOrFail($id);
        try {
            $noteTitle = $note->title; 
            
            $details = [
                'action' => 'delete',
                'data' => [
                    'Title' => $note->title,
                    'Description' => $note->description
                ]
            ];

            $note->delete();

            return redirect()->back()->with('SA-success', 'Note deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('SA-error', 'Failed to delete note.');
        }
    }
}