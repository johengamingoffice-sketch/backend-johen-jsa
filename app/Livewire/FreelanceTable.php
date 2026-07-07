<?php

namespace App\Livewire;

use App\Models\Freelance;
use App\Models\Position;
use Livewire\Component;
use Livewire\WithPagination;

class FreelanceTable extends Component
{
    use WithPagination;

    public bool $showModal = false;
    public ?int $editId = null;

    public string $nama = '';
    public string $host_position = '';
    public string $no_whatsapp = '';

    protected function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'host_position' => 'required|string',
            'no_whatsapp' => 'nullable|string|max:20',
        ];
    }

    protected function messages(): array
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'host_position.required' => 'Host wajib dipilih.',
        ];
    }

    public function openNew(): void
    {
        $this->resetInput();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $item = Freelance::findOrFail($id);
        $this->editId = $item->id;
        $this->nama = $item->nama;
        $this->host_position = $item->host_position;
        $this->no_whatsapp = $item->no_whatsapp ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editId) {
            $item = Freelance::findOrFail($this->editId);
            $item->update([
                'nama' => $this->nama,
                'host_position' => $this->host_position,
                'no_whatsapp' => $this->no_whatsapp ?: null,
            ]);
            session()->flash('message', 'Data freelance berhasil diperbarui.');
        } else {
            Freelance::create([
                'nama' => $this->nama,
                'host_position' => $this->host_position,
                'no_whatsapp' => $this->no_whatsapp ?: null,
            ]);
            session()->flash('message', 'Data freelance berhasil ditambahkan.');
        }

        $this->close();
    }

    public function delete(int $id): void
    {
        Freelance::findOrFail($id)->delete();
        session()->flash('message', 'Data freelance berhasil dihapus.');
    }

    public function close(): void
    {
        $this->showModal = false;
        $this->resetInput();
    }

    private function resetInput(): void
    {
        $this->editId = null;
        $this->nama = '';
        $this->host_position = '';
        $this->no_whatsapp = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        $items = Freelance::latest()->paginate(10);

        $hostPositions = Position::where('nama', 'like', 'Host%')
            ->orderBy('nama')
            ->get()
            ->pluck('nama');

        return view('livewire.freelance-table', compact('items', 'hostPositions'));
    }
}
