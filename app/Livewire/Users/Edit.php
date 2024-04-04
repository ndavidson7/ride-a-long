<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

#[Title('Edit profile')]
class Edit extends Component
{
    public User $user;

    #[Validate('nullable|string|max:255')]
    public ?string $bio;

    public function mount()
    {
        $this->user = Auth::user();

        $this->bio = $this->user->bio;
    }

    public function save()
    {
        $this->validate();

        $this->user->update($this->only('bio'));

        return redirect()->route('users.show')->with(['status' => 'success', 'message' => 'Profile updated!']);
    }

    public function render()
    {
        return view('livewire.users.edit');
    }
}
