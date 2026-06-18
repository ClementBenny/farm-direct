@if(session('success'))
    <div x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 3000)"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="bg-[var(--champagne)] border border-[var(--border)] text-[var(--umber)] px-4 py-3 rounded-xl mb-6 flex items-center justify-between shadow-sm">
        <span class="text-sm font-semibold tracking-wide flex items-center gap-2">
            <svg class="w-4 h-4 text-[var(--accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </span>
        <button @click="show = false" class="text-[var(--accent)] hover:text-[var(--dark)] ml-4 text-xl leading-none transition-colors">&times;</button>
    </div>
@endif

@if(session('error'))
    <div x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 4000)"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="bg-[var(--mauve)]/30 border border-[var(--mauve)] text-[var(--dark)] px-4 py-3 rounded-xl mb-6 flex items-center justify-between shadow-sm">
        <span class="text-sm font-semibold tracking-wide flex items-center gap-2">
            <svg class="w-4 h-4 text-[var(--umber)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            {{ session('error') }}
        </span>
        <button @click="show = false" class="text-[var(--umber)] hover:text-[var(--dark)] ml-4 text-xl leading-none transition-colors">&times;</button>
    </div>
@endif