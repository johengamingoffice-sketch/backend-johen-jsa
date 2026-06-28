<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination;
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public ?int $editId = null;

    public string $name = '';
    public string $username = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = 'karyawan';
    public ?int $linkEmployeeId = null;
    public string $filterRole = '';
    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterRole(): void
    {
        $this->resetPage();
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'regex:/^\S*$/', 'unique:users,username' . ($this->editId ? ',' . $this->editId : '')],
            'password' => [$this->editId ? 'nullable' : 'required', 'string', 'min:4', 'confirmed'],
            'role' => ['required', 'in:admin,direksi,karyawan'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.regex' => 'Username tidak boleh mengandung spasi.',
            'username.unique' => 'Username sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 4 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role wajib dipilih.',
        ];
    }

    public function openCreateModal(): void
    {
        Gate::authorize('create-data');
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal(int $id): void
    {
        Gate::authorize('update-data');
        $user = User::findOrFail($id);
        $this->editId = $user->id;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->role = $user->role;
        $this->linkEmployeeId = $user->employee?->id;
        $this->password = '';
        $this->password_confirmation = '';
        $this->showEditModal = true;
    }

    public function closeModal(): void
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->editId = null;
        $this->resetErrorBag();
    }

    public function save(): void
    {
        Gate::authorize('create-data');
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'username' => $this->username,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        if ($this->linkEmployeeId) {
            Employee::where('id', $this->linkEmployeeId)->update(['user_id' => $user->id]);
        }

        $this->closeModal();
        $this->dispatch('notify', type: 'success', message: 'Akun berhasil ditambahkan.');
    }

    public function update(): void
    {
        Gate::authorize('update-data');
        $user = User::findOrFail($this->editId);

        $rules = $this->rules();
        if (!$this->password) {
            $rules['password'] = ['nullable', 'string', 'min:4', 'confirmed'];
        }
        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'username' => $this->username,
            'role' => $this->role,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);

        Employee::where('user_id', $user->id)->update(['user_id' => null]);
        if ($this->linkEmployeeId) {
            Employee::where('id', $this->linkEmployeeId)->update(['user_id' => $user->id]);
        }

        $this->closeModal();
        $this->dispatch('notify', type: 'success', message: 'Akun berhasil diperbarui.');
    }

    public function delete(int $id): void
    {
        Gate::authorize('delete-data');
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            $this->dispatch('notify', type: 'error', message: 'Tidak dapat menghapus akun sendiri.');
            return;
        }

        Employee::where('user_id', $user->id)->update(['user_id' => null]);
        $user->delete();
        $this->dispatch('notify', type: 'success', message: 'Akun berhasil dihapus.');
    }

    public function render()
    {
        $users = User::with('employee')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('username', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filterRole, fn($q) => $q->where('role', $this->filterRole))
            ->orderBy('name')->paginate(20);
        $unlinkedEmployees = Employee::whereNull('user_id')->where('status', 'aktif')->orderBy('nama')->get();
        $allEmployees = Employee::where('status', 'aktif')->orderBy('nama')->get();
        return view('livewire.user-table', compact('users', 'unlinkedEmployees', 'allEmployees'));
    }

    private function resetForm(): void
    {
        $this->editId = null;
        $this->name = '';
        $this->username = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = 'karyawan';
        $this->linkEmployeeId = null;
        $this->resetErrorBag();
    }
}
