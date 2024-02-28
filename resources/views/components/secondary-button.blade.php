<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-white text-slate-800 justify-center border border-transparent hover:scale-10 rounded-sm font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-700 focus:ring-offset-2 duration-150 hover:scale-110']) }}>
    {{ $slot }}
</button>
