<div class="space-y-4">
    @foreach([
        ['name' => 'Sarah Miller', 'role' => 'Owner', 'rep' => 12, 'color' => 'indigo'],
        ['name' => 'Alex Johnson', 'role' => 'Member', 'rep' => 8, 'color' => 'emerald'],
        ['name' => 'Marc Dupont', 'role' => 'Member', 'rep' => -1, 'color' => 'rose'],
    ] as $member)
    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100">
        <div class="flex items-center gap-3">
            <img src="https://ui-avatars.com/api/?name={{$member['name']}}&background=random" class="w-10 h-10 rounded-lg">
            <div>
                <p class="text-sm font-semibold text-slate-900">{{ $member['name'] }}</p>
                <x-badge type="{{ $member['role'] == 'Owner' ? 'primary' : 'neutral' }}">{{ $member['role'] }}</x-badge>
            </div>
        </div>
        <div class="text-right">
            <span class="text-xs font-bold {{ $member['rep'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                {{ $member['rep'] > 0 ? '+' : '' }}{{ $member['rep'] }}
            </span>
            <p class="text-[10px] text-slate-400 uppercase tracking-tighter">Reputation</p>
        </div>
    </div>
    @endforeach
</div>