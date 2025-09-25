<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Collection;
use App\Models\Chat;
use App\Models\ChatGroup;
use App\Services\AiService;
use Illuminate\Support\Facades\DB;

class ChatWindow extends Component
{
    use WithFileUploads;

    public $chatgroupId = null;
    public $messages;
    public $input = '';
    public $attachment;

    protected $listeners = [
        'chatSelected' => 'setChatGroup'
    ];

    public function mount($chatgroupId = null)
    {
        $this->chatgroupId = $chatgroupId;
        $this->messages = collect(); 
        if ($this->chatgroupId) {
            $this->loadMessages();
        }
    }

    public function setChatGroup($chatgroupId)
    {
        $this->chatgroupId = $chatgroupId;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        if (!$this->chatgroupId) {
            $this->messages = collect();
            return;
        }

        $this->messages = Chat::where('chatgroup_id', $this->chatgroupId)
            ->orderBy('created_at')
            ->get();
        $this->emit('messagesUpdated');

    }

    public function sendMessage()
    {
        $text = trim($this->input);
        if ($text === '' && !$this->attachment) return;

        // Crear grupo si no existe
        if (!$this->chatgroupId) {
            $group = ChatGroup::create([
                'name' => 'Nueva conversación',
                'user_id' => auth()->id(),
            ]);
            $this->chatgroupId = $group->id;
        }

        // 1️⃣ Guardar mensaje del usuario
        $userChat = Chat::create([
            'chatgroup_id' => $this->chatgroupId,
            'emisor_id' => auth()->id(),
            'receiver' => 1,
            'role' => 'user',
            'message' => $text,
        ]);

        // Guardar en pivote
        DB::table('user_chat')->insert([
            'user_id' => auth()->id(),
            'chatgroup_id' => $this->chatgroupId,
            'chat_id' => $userChat->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->input = '';
        $this->attachment = null;
        $this->messages->push($userChat);

        // 2️⃣ Guardar mensaje de la IA
        $messagesForAi = AiService::buildMessagesFromChats($this->messages);
        $aiText = '';
        try {
            $aiText = AiService::ask($messagesForAi);
        } catch (\Exception $e) {
            $aiText = "Error al contactar la IA: " . $e->getMessage();
        }

        $aiChat = Chat::create([
            'chatgroup_id' => $this->chatgroupId,
            'emisor_id' => 1,       // IA
            'receiver' => auth()->id(), // usuario
            'role' => 'assistant',
            'message' => $aiText,
        ]);

        DB::table('user_chat')->insert([
            'user_id' => auth()->id(),
            'chatgroup_id' => $this->chatgroupId,
            'chat_id' => $aiChat->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->messages->push($aiChat);
        $this->emit('messagesUpdated');
        
   
    }

    public function render()
    {
        return view('livewire.chat-window');
    }
}
