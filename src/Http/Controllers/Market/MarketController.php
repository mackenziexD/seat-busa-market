<?php

namespace Helious\SeatBusaHr\Http\Controllers\Character;

use Seat\Web\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Seat\Web\Http\DataTables\Scopes\CharacterScope;

use Helious\SeatBusaHr\Models\HrNote;
use Seat\Eveapi\Models\Character\CharacterInfo;

use Warlof\Seat\Connector\Models\User;

class HrController extends Controller
{
    /**
     * Show the eligibility checker.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(CharacterInfo $character, Request $request)
    {
        // get the main character id
        $main_character_id = $character->refresh_token->user->main_character_id;
        $main_character_name = $character->refresh_token->user->main_character->name;

        // get the user id for the main character
        $main_character_user_id = $character->refresh_token->user->id;
        
        $identities = User::where('user_id', $main_character_user_id)->get();
        $has_linked_teamspeak = false;
        $has_linked_discord = false;

        foreach($identities as $identity)
        {
            if($identity->connector_type === 'teamspeak')
                $has_linked_teamspeak = true;
            if($identity->connector_type === 'discord')
                $has_linked_discord = true;
        }

        // get all notes for the main character
        $notes = HrNote::where('note_for', $main_character_id)->orderBy('id', 'desc')->get();
        return view('seat-busa-hr::notes.index', compact('character', 'main_character_id', 'main_character_name', 'notes', 'has_linked_teamspeak', 'has_linked_discord'));
    }

    public function create(Request $request, CharacterInfo $character)
    {
        if($request->isMethod('post'))
        {
            $data = $request->only([
                'director_only',
                'note',
            ]);

            $rules = [
                'note' => ['required'],
            ];
            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                return back()->withErrors($validator->errors());
            }

            // add the main character id to the data array
            $data['note_for'] = $character->refresh_token->user->main_character_id;
            $data['created_by'] = \Auth::user()->id;

            HrNote::create($data);

            return redirect()->route('seat-busa-hr::notes.index', compact('character'))
                ->with('success', 'User note created successfully.');
        }

        return view('seat-busa-hr::notes.create', compact('character'));
    }

    

    public function edit(CharacterInfo $character, HrNote $note, Request $request)
    {
        if($request->isMethod('post'))
        {
            $data = $request->only([
                'director_only',
                'note',
            ]);

            $rules = [
                'note' => ['required'],
            ];
            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                return back()->withErrors($validator->errors());
            }

            // add the main character id to the data array
            $data['note_for'] = $character->refresh_token->user->main_character_id;
            $data['created_by'] = \Auth::user()->id;
            $data['director_only'] = $request->has('director_only') ? true : false;

            $note->update($data);
            
            $main_character_id = $character->refresh_token->user->main_character_id;
            $main_character_name = $character->refresh_token->user->main_character->name;

            
            $notes = HrNote::where('note_for', $main_character_id)->orderBy('id', 'desc')->get();
            return redirect()->route('seat-busa-hr::notes.index', ['character' => $character])->with('success', 'User note has been updated successfully.');

        }
        
        return view('seat-busa-hr::notes.edit', ['character' => $character, 'note' => $note])->with('error', 'Something went wrong.');
    }

    
    public function delete(CharacterInfo $character, HrNote $note)
    {
        // is the user allowed to delete this note?
        if(!\Auth::user()->can('seat-busa-hr.director_notes'))
            return redirect()->back()->with('error', 'You are not allowed to delete this note.');

        $note->delete();

        return redirect()->route('seat-busa-hr::notes.index', ['character' => $character])->with('success', 'User note has been removed successfully.');
    }


}
