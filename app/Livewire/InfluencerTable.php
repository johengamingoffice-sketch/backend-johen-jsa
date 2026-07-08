<?php

namespace App\Livewire;

use App\Models\Influencer;
use Livewire\Component;
use Livewire\WithPagination;

class InfluencerTable extends Component
{
    use WithPagination;

    public bool $showModal = false;
    public ?int $editId = null;

    public string $no_kontrak = '';
    public string $nama = '';
    public string $mulai_kontrak = '';
    public string $habis_kontrak = '';
    public string $link_sosmed = '';

    protected function rules(): array
    {
        return [
            'no_kontrak' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'mulai_kontrak' => 'required|date',
            'habis_kontrak' => 'required|date|after_or_equal:mulai_kontrak',
            'link_sosmed' => 'nullable|string|max:500',
        ];
    }

    protected function messages(): array
    {
        return [
            'no_kontrak.required' => 'No. Kontrak wajib diisi.',
            'nama.required' => 'Nama influencer wajib diisi.',
            'mulai_kontrak.required' => 'Mulai kontrak wajib diisi.',
            'habis_kontrak.required' => 'Habis kontrak wajib diisi.',
            'habis_kontrak.after_or_equal' => 'Habis kontrak harus setelah atau sama dengan mulai kontrak.',
        ];
    }

    public function openNew(): void
    {
        $this->resetInput();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $item = Influencer::findOrFail($id);
        $this->editId = $item->id;
        $this->no_kontrak = $item->no_kontrak;
        $this->nama = $item->nama;
        $this->mulai_kontrak = $item->mulai_kontrak->format('Y-m-d');
        $this->habis_kontrak = $item->habis_kontrak->format('Y-m-d');
        $this->link_sosmed = $item->link_sosmed ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editId) {
            $item = Influencer::findOrFail($this->editId);
            $item->update([
                'no_kontrak' => $this->no_kontrak,
                'nama' => $this->nama,
                'mulai_kontrak' => $this->mulai_kontrak,
                'habis_kontrak' => $this->habis_kontrak,
                'link_sosmed' => $this->link_sosmed ?: null,
            ]);
            session()->flash('message', 'Data influencer berhasil diperbarui.');
        } else {
            Influencer::create([
                'no_kontrak' => $this->no_kontrak,
                'nama' => $this->nama,
                'mulai_kontrak' => $this->mulai_kontrak,
                'habis_kontrak' => $this->habis_kontrak,
                'link_sosmed' => $this->link_sosmed ?: null,
            ]);
            session()->flash('message', 'Data influencer berhasil ditambahkan.');
        }

        $this->close();
    }

    public function delete(int $id): void
    {
        Influencer::findOrFail($id)->delete();
        session()->flash('message', 'Data influencer berhasil dihapus.');
    }

    public function close(): void
    {
        $this->showModal = false;
        $this->resetInput();
    }

    private function resetInput(): void
    {
        $this->editId = null;
        $this->no_kontrak = '';
        $this->nama = '';
        $this->mulai_kontrak = '';
        $this->habis_kontrak = '';
        $this->link_sosmed = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        $items = Influencer::latest()->paginate(10);

        $now = now()->startOfDay();
        $aktifCount = Influencer::where('habis_kontrak', '>', $now)->count();
        $segeraHabisCount = Influencer::where('habis_kontrak', '>', $now)
            ->where('habis_kontrak', '<=', $now->copy()->addDays(7))
            ->count();
        $tidakAktifCount = Influencer::where('habis_kontrak', '<=', $now)->count();

        return view('livewire.influencer-table', compact('items', 'aktifCount', 'segeraHabisCount', 'tidakAktifCount'));
    }
}
