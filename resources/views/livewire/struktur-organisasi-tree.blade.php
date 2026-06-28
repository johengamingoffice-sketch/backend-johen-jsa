<div x-data="{ collapsed: {{ $level > 2 ? 'true' : 'false' }} }" class="flex flex-col items-center">
    {{-- Card --}}
    <div @click="focusedId = {{ $node['id'] }}"
         class="cursor-pointer w-64 p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm text-center hover:border-primary-400 dark:hover:border-primary-500 hover:shadow-md hover:scale-105 active:scale-95 transition-all duration-200">
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

    @if(count($node['children']) > 0)
        <template x-if="!collapsed">
            <div>
                <div class="w-0.5 h-6 bg-gray-300 dark:bg-gray-600"></div>

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

                            @include('livewire.struktur-organisasi-tree', ['node' => $child, 'level' => $level + 1])
                        </div>
                    @endforeach
                </div>
            </div>
        </template>
    @endif
</div>
