<x-app-layout title="Status Pengajuan">
    @push('topbar-left')
        <div>
            <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">Status Pengajuan Pembayaran</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400">Riwayat pengajuan pembayaran kamu</p>
        </div>
    @endpush

    <div class="space-y-6">
        <div class="card overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h2 class="text-sm font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                    Status Pengajuan
                </h2>

                <div class="flex items-center gap-2 flex-wrap">
                    <div class="relative" x-data="{ search: '' }">
                        <input type="text" x-model="search" @input.debounce="loadPengajuan(search)" placeholder="Cari..." class="input-field w-40 text-xs pl-8 py-2">
                        <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    </div>

                    <select x-data="{ status: 'semua' }" @change="loadPengajuan(search, status)" x-model="status" class="input-field text-xs py-2 w-32">
                        <option value="semua">Semua Status</option>
                        <option value="menunggu">Menunggu</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                        <option value="lunas">Lunas</option>
                    </select>

                    <a href="{{ route('payment-submissions.export.pengajuan') }}" class="btn-ghost p-2" title="Download Excel">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    </a>

                    <button @click="$dispatch('open-modal', { name: 'pengajuan-modal' })" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Ajukan Pembayaran
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="table-header">
                            <th class="px-4 py-3 text-center w-12">No</th>
                            <th class="px-4 py-3">Jenis</th>
                            <th class="px-4 py-3">Detail</th>
                            <th class="px-4 py-3 text-right">Nominal</th>
                            <th class="px-4 py-3 text-center">Tgl Bayar</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Bukti</th>
                            <th class="px-4 py-3 text-center">Approval</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($submissions as $s)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-900 transition-colors">
                            <td class="table-cell text-center text-gray-500 dark:text-gray-400">{{ $loop->iteration }}</td>
                            <td class="table-cell font-medium text-gray-900 dark:text-gray-100">{{ $s->jenis }}</td>
                            <td class="table-cell text-gray-600 dark:text-gray-400">{{ $s->detail }}</td>
                            <td class="table-cell text-right font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format($s->nominal, 0, ',', '.') }}</td>
                            <td class="table-cell text-center text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ $s->tgl_bayar?->format('d/m/Y') ?? '-' }}</td>
                            <td class="table-cell text-center">
                                @php
                                    $badge = match($s->status) {
                                        'lunas' => 'badge-success',
                                        'disetujui' => 'badge-success',
                                        'menunggu' => 'badge-warning',
                                        'ditolak' => 'badge-danger',
                                        'jatuh_tempo' => 'badge-warning',
                                        default => 'badge-info',
                                    };
                                @endphp
                                <span class="{{ $badge }}">{{ ucfirst($s->status) }}</span>
                            </td>
                            <td class="table-cell text-center">
                                @if($s->bukti)
                                <a href="{{ asset('storage/' . $s->bukti) }}" target="_blank" class="text-blue-600 hover:underline text-xs">Lihat</a>
                                @elseif($s->status === 'disetujui' || $s->status === 'jatuh_tempo')
                                <form method="POST" action="{{ route('payment-submissions.upload-bukti', $s) }}" enctype="multipart/form-data" class="inline">
                                    @csrf
                                    <label class="text-blue-600 hover:underline text-xs cursor-pointer">
                                        Upload
                                        <input type="file" name="bukti" accept="image/*" class="hidden" onchange="this.form.submit()">
                                    </label>
                                </form>
                                @else
                                <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="table-cell text-center text-gray-600 dark:text-gray-400">{{ $s->approver?->name ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-50 dark:bg-gray-900 mb-3">
                                        <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                                    </div>
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Belum Ada Pengajuan</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kamu belum mengajukan pembayaran apapun.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($submissions->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                {{ $submissions->links() }}
            </div>
            @endif
        </div>
    </div>

    <x-modal name="pengajuan-modal" maxWidth="lg">
        <form method="POST" action="{{ route('payment-submissions.store') }}" class="p-6">
            @csrf
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Ajukan Pembayaran</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ajukan pembayaran untuk disetujui</p>
                </div>
                <button type="button" @click="$dispatch('close-modal', { name: 'pengajuan-modal' })" class="rounded-xl p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-4">
                <div>
                    <x-input-label for="jenis">Jenis Pembayaran</x-input-label>
                    <select id="jenis" name="jenis" class="input-field w-full mt-1" required>
                        <option value="Aset Digital">Aset Digital</option>
                        <option value="IPL Ruko">IPL Ruko</option>
                        <option value="Listrik">Listrik</option>
                        <option value="Internet">Internet</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div>
                    <x-input-label for="detail">Detail</x-input-label>
                    <x-text-input id="detail" name="detail" class="w-full mt-1" required placeholder="Keterangan tagihan" />
                </div>

                <div>
                    <x-input-label for="nominal">Nominal (Rp)</x-input-label>
                    <x-text-input id="nominal" name="nominal" type="number" step="0.01" min="0" class="w-full mt-1" required placeholder="0" />
                </div>

                <div>
                    <x-input-label for="keterangan">Keterangan Tambahan</x-input-label>
                    <textarea id="keterangan" name="keterangan" rows="2" class="input-field w-full mt-1" placeholder="Opsional"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                <button type="button" @click="$dispatch('close-modal', { name: 'pengajuan-modal' })" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary">Ajukan</button>
            </div>
        </form>
    </x-modal>

    @push('scripts')
    <script>
        function pengajuanApp() {
            return {
                init() {},
                async loadPengajuan(search = '', status = 'semua') {
                    try {
                        const params = new URLSearchParams();
                        if (search) params.set('search', search);
                        if (status && status !== 'semua') params.set('status', status);
                        const res = await fetch(`{{ route('payment-submissions.data.pengajuan') }}?${params}`, {
                            headers: { 'Accept': 'application/json' }
                        });
                    } catch (e) {
                        console.error('Failed to load pengajuan:', e);
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
