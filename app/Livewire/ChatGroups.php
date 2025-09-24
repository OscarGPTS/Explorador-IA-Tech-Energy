<?php

namespace App\Livewire;
use Livewire\Component;
use App\Models\ChatGroup;
use Illuminate\Support\Facades\Auth;

class ChatGroups extends Component
{
    public $name;

    public function render()
    {
        $groups = Auth::user()->chatgroups()->latest()->get();
        return view('livewire.chat-groups', compact('groups'));
    }

    public function create()
    {
        $g = ChatGroup::create([
            'user_id' => auth()->id(),
            'name' => $this->name ?? 'Nueva conversación'
        ]);

        $this->emit('chatSelected', $g->id);
    }

    public function select($chatId)
    {
        $this->emit('chatSelected', $chatId);
    }
}