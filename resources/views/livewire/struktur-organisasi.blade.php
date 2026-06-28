<div x-data="strukturOrganisasi()">
    {{-- Header --}}
    <div class="flex items-center justify-end px-4 sm:px-8 pt-6 pb-2">
        <button x-show="focusedId" @click="focusedId = null"
                class="flex items-center gap-1.5 text-xs font-semibold text-primary-600 hover:text-primary-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Kembali ke seluruh bagan
        </button>
    </div>

    <div class="py-6 px-4 sm:px-8 overflow-x-auto">
        {{-- Full tree view --}}
        <div x-show="!focusedId"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">
            @if(count($roots) > 0)
                <div class="flex flex-col items-center min-w-max">
                    @foreach($roots as $root)
                        @include('livewire.struktur-organisasi-tree', ['node' => $root, 'level' => 1])
                    @endforeach
                </div>
            @else
                <div class="py-16 text-center">
                    <div class="flex items-center justify-center w-14 h-14 mx-auto mb-4 rounded-xl bg-gray-100 dark:bg-gray-800">
                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-1">Belum ada data organisasi</h3>
                    <p class="text-xs text-gray-400">Tambahkan jabatan terlebih dahulu</p>
                </div>
            @endif
        </div>

        {{-- Focus view: parent → focused → children --}}
        <div x-show="focusedId" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="flex flex-col items-center min-w-max py-4">
            {{-- Parent --}}
            <template x-if="parent">
                <div>
                    <div @click="focusedId = parent.id"
                         :class="children.length > 4 ? 'w-48 p-3.5' : 'w-64 p-4'"
                         class="cursor-pointer rounded-xl bg-white dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-600 shadow-sm text-center hover:border-amber-400 dark:hover:border-amber-500 hover:shadow-md transition-all">
                        <div class="flex items-center justify-center gap-1.5 mb-1">
                            <template x-if="parent.employee_foto">
                                <img :src="'/storage/employees/' + parent.employee_foto" :alt="parent.employee_nama"
                                     :class="children.length > 4 ? 'w-6 h-6' : 'w-8 h-8'"
                                     class="rounded object-cover bg-gray-50 dark:bg-gray-700 shrink-0 shadow-sm">
                            </template>
                            <template x-if="!parent.employee_foto">
                                <div :class="children.length > 4 ? 'w-6 h-6 text-[11px]' : 'w-8 h-8 text-[13px]'"
                                     class="flex items-center justify-center rounded text-white font-bold shrink-0 bg-amber-500">
                                    <span x-text="parent.nama.charAt(0).toUpperCase()"></span>
                                </div>
                            </template>
                            <span class="text-[9px] font-medium text-amber-600 dark:text-amber-400 uppercase tracking-wider">Atasan</span>
                        </div>
                        <p :class="children.length > 4 ? 'text-xs' : 'text-sm'" class="font-semibold text-gray-900 dark:text-gray-100 leading-tight" x-text="parent.nama"></p>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 truncate" x-text="parent.employee_nama || ''"></p>
                    </div>
                    <div class="w-0.5 h-6 bg-gray-300 dark:bg-gray-600 mx-auto"></div>
                </div>
            </template>

            {{-- Focused position --}}
            <div :class="children.length > 4 ? 'w-48 p-3.5' : 'w-64 p-4'"
                 class="rounded-xl bg-gradient-to-br from-primary-50 to-blue-50 dark:from-primary-950 dark:to-blue-950 border-2 border-primary-500 shadow-lg shadow-primary-100 dark:shadow-primary-900/30 text-center ring-2 ring-primary-200 dark:ring-primary-800">
                <div class="flex items-center justify-center gap-1.5 mb-1">
                    <template x-if="focused.employee_foto">
                        <img :src="'/storage/employees/' + focused.employee_foto" :alt="focused.employee_nama"
                             :class="children.length > 4 ? 'w-6 h-6' : 'w-8 h-8'"
                             class="rounded object-cover bg-gray-50 dark:bg-gray-700 shrink-0 shadow-sm">
                    </template>
                    <template x-if="!focused.employee_foto">
                        <div :class="children.length > 4 ? 'w-6 h-6 text-[11px]' : 'w-8 h-8 text-[13px]'"
                             class="flex items-center justify-center rounded text-white font-bold shrink-0 bg-primary-600">
                            <span x-text="focused.nama.charAt(0).toUpperCase()"></span>
                        </div>
                    </template>
                    <span class="text-[9px] font-medium text-primary-600 dark:text-primary-400 uppercase tracking-wider">Terfokus</span>
                </div>
                <p :class="children.length > 4 ? 'text-xs' : 'text-sm'" class="font-bold text-primary-800 dark:text-primary-200 leading-tight" x-text="focused.nama"></p>
                <p class="text-[10px] text-primary-600 dark:text-primary-400 mt-0.5 truncate font-medium" x-text="focused.employee_nama || ''"></p>
            </div>

            {{-- Children --}}
            <template x-if="children.length > 0">
                <div>
                    <div class="w-0.5 h-6 bg-gray-300 dark:bg-gray-600 mx-auto"></div>
                    <div class="relative flex items-start justify-center" :class="children.length > 4 ? 'gap-8' : 'gap-12'">
                        <template x-if="children.length > 1">
                            <div class="absolute top-0 left-[5%] right-[5%] h-0.5 bg-gray-300 dark:bg-gray-600"></div>
                        </template>
                        <template x-for="child in children" :key="child.id">
                            <div class="flex flex-col items-center shrink-0">
                                <div class="w-0.5 h-6 bg-gray-300 dark:bg-gray-600"></div>
                                <div @click="focusedId = child.id"
                                     :class="children.length > 4 ? 'w-48 p-3.5' : 'w-64 p-4'"
                                     class="cursor-pointer rounded-xl bg-white dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-600 shadow-sm text-center hover:border-purple-400 dark:hover:border-purple-500 hover:shadow-md transition-all">
                                    <div class="flex items-center justify-center gap-1.5 mb-1">
                                        <template x-if="child.employee_foto">
                                            <img :src="'/storage/employees/' + child.employee_foto" :alt="child.employee_nama"
                                                 :class="children.length > 4 ? 'w-6 h-6' : 'w-8 h-8'"
                                                 class="rounded object-cover bg-gray-50 dark:bg-gray-700 shrink-0 shadow-sm">
                                        </template>
                                        <template x-if="!child.employee_foto">
                                            <div :class="children.length > 4 ? 'w-6 h-6 text-[11px]' : 'w-8 h-8 text-[13px]'"
                                                 class="flex items-center justify-center rounded text-white font-bold shrink-0 bg-purple-500">
                                                <span x-text="child.nama.charAt(0).toUpperCase()"></span>
                                            </div>
                                        </template>
                                        <span class="text-[9px] font-medium text-purple-600 dark:text-purple-400 uppercase tracking-wider">Bawahan</span>
                                    </div>
                                    <p :class="children.length > 4 ? 'text-xs' : 'text-sm'" class="font-semibold text-gray-900 dark:text-gray-100 leading-tight" x-text="child.nama"></p>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 truncate" x-text="child.employee_nama || ''"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <template x-if="children.length === 0">
                <div class="mt-4 text-center">
                    <span class="text-xs text-gray-400 dark:text-gray-500 italic">Tidak ada bawahan</span>
                </div>
            </template>
        </div>
    </div>



    <script>
        function strukturOrganisasi() {
            return {
                focusedId: null,
                positions: @json($flatPositions),
                get focused() {
                    return this.focusedId ? this.positions[this.focusedId] : null;
                },
                get parent() {
                    if (!this.focused) return null;
                    return this.focused.parent_id ? this.positions[this.focused.parent_id] : null;
                },
                get children() {
                    if (!this.focusedId) return [];
                    return Object.values(this.positions).filter(p => p.parent_id === this.focusedId);
                }
            }
        }
    </script>
</div>
