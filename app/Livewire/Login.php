<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $loginType = 'siswa'; // 'siswa', 'operator', 'admin'
    public $identifier;
    public $password;
    public $error;

    public function setLoginType($type)
    {
        $this->loginType = $type;
        $this->reset(['identifier', 'password', 'error']);
    }

    public function login()
    {
        $this->error = null;

        $credentials = [
            $this->getIdentifierField() => $this->identifier,
            'password' => $this->password,
        ];

        if (Auth::guard($this->getGuard())->attempt($credentials)) {
            return redirect()->intended($this->getRedirectPath());
        }

        $this->error = 'Invalid credentials.';
    }

    private function getIdentifierField()
    {
        switch ($this->loginType) {
            case 'admin':
                return 'email';
            case 'operator':
                return 'username';
            case 'siswa':
            default:
                return 'nisn';
        }
    }

    private function getGuard()
    {
        switch ($this->loginType) {
            case 'admin':
                return 'web';
            case 'operator':
                return 'operator';
            case 'siswa':
            default:
                return 'student';
        }
    }

    private function getRedirectPath()
    {
        switch ($this->loginType) {
            case 'admin':
                return route('admin.dashboard');
            case 'operator':
                return route('operator.dashboard');
            case 'siswa':
            default:
                return route('student.dashboard');
        }
    }

    public function render()
    {
        return view('livewire.login');
    }
}
