<div x-data="{ collapsed: {{ $level > 2 ? 'true' : 'false' }} }" class="flex flex-col items-center">
    {{-- Card --}}
    <div class="relative" @click="focusedId = {{ $node['id'] }}">
        <div class="cursor-pointer w-64 p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm text-center hover:border-primary-400 dark:hover:border-primary-500 hover:shadow-md hover:scale-105 active:scale-95 transition-all duration-200">
            <div class="flex items-center justify-center mb-1.5">
                @if($node['employee'] && $node['employee']->foto)
                    <img src="{{ asset('storage/employees/' . $node['employee']->foto) }}" alt="{{ $node['employee']->nama }}"
                         class="w-8 h-8 rounded object-cover bg-gray-50 dark:bg-gray-700 shrink-0 shadow-sm">
                @else
                    <div class="flex h-8 w-8 items-center justify-center rounded text-white font-bold text-[13px] shrink-0 shadow-sm
                        {{ $level == 1 ? 'bg-emerald-500' : ($level == 2 ? 'bg-blue-500' : 'bg-purple-500') }}">
                        {{ strtoupper(substr($node['nama'], 0, 1)) }}
                    </div>
                @endif
            </div>
            <p class="text-xs font-semibold text-gray-900 dark:text-gray-100 leading-snug">{{ $node['nama'] }}</p>
            @if($node['employee'])
                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 truncate">{{ $node['employee']->nama }}</p>
            @else
                <p class="text-[10px] text-gray-300 dark:text-gray-600 mt-1 italic">Kosong</p>
            @endif
        </div>

        {{-- Note button --}}
        @php
            $noteInfo = $notesByPosition->get($node['id']);
            $hasCurrentNote = $noteInfo && $noteInfo->has_current > 0;
        @endphp
        <button wire:click.stop="openNoteModal({{ $node['id'] }})"
                class="absolute -top-1.5 -right-1.5 w-7 h-7 flex items-center justify-center rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 shadow-sm hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:border-primary-300 transition-all @if($hasCurrentNote) ring-2 ring-green-400 @endif"
                title="Lihat catatan & rekomendasi">
            <svg class="w-3.5 h-3.5 @if($hasCurrentNote) text-green-500 @else text-gray-400 @endif" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
        </button>
    </div>

    @if(count($node['children']) > 0)
        <template x-if="!collapsed">
            <div>
                <div class="w-0.5 h-6 bg-gray-300 dark:bg-gray-600 mx-auto"></div>

                <div class="relative flex items-start justify-center gap-6 sm:gap-10">
                    @if(count($node['children']) > 1)
                        <div class="absolute top-0 left-[8%] right-[8%] h-0.5 bg-gray-300 dark:bg-gray-600"></div>
                    @endif

                    @foreach($node['children'] as $child)
                        <div class="flex flex-col items-center shrink-0">
                            @if(count($node['children']) > 1)
                                <div class="w-0.5 h-6 bg-gray-300 dark:bg-gray-600"></div>
                            @else
                                <div class="h-2"></div>
                            @endif

                            @include('livewire.struktur-organisasi-tree', ['node' => $child, 'level' => $level + 1, 'notesByPosition' => $notesByPosition, 'myPositionId' => $myPositionId])
                        </div>
                    @endforeach
                </div>
            </div>
        </template>
    @endif
</div>
