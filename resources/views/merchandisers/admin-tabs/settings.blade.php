                <div x-show="activeTab === 'settings'" x-cloak x-transition>
                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
                        <div class="xl:col-span-2 glass-panel rounded-2xl p-5 border border-brand-white/10">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between mb-5">
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-brand-ash">Merchandiser outlet visits</p>
                                    <h3 class="text-xl font-display text-brand-white mt-1">Open Visit Window</h3>
                                    <p class="text-xs text-brand-ash mt-1">Set the daily outlet clock-in window. Merchandisers can clock in and clock out at every assigned outlet during this period.</p>
                                </div>
                                <span class="inline-flex w-fit rounded-full border border-emerald-500/20 bg-emerald-500/10 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-emerald-400">
                                    No code deploy needed
                                </span>
                            </div>

                            <form method="POST" action="{{ route('merchandisers.admin.clock-settings.update') }}" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="rounded-2xl border border-brand-white/10 bg-brand-black/40 p-4">
                                        <p class="text-[10px] uppercase tracking-wider text-brand-ash">Window Start</p>
                                        <p class="mt-1 text-sm font-bold text-brand-white">{{ $clockSettings['label'] }}</p>
                                        <input type="time" name="visit_start" value="{{ old('visit_start', $clockSettings['start']) }}" required class="mt-4 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                    </div>
                                    <div class="rounded-2xl border border-brand-white/10 bg-brand-black/40 p-4">
                                        <p class="text-[10px] uppercase tracking-wider text-brand-ash">Window End</p>
                                        <p class="mt-1 text-sm font-bold text-brand-white">{{ $clockSettings['range'] }}</p>
                                        <input type="time" name="visit_end" value="{{ old('visit_end', $clockSettings['end']) }}" required class="mt-4 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                    </div>
                                    <div class="rounded-2xl border border-brand-white/10 bg-brand-black/40 p-4">
                                        <p class="text-[10px] uppercase tracking-wider text-brand-ash">Late Start Flag</p>
                                        <p class="mt-1 text-sm font-bold text-brand-white">After this time, first outlet starts are flagged late.</p>
                                        <input type="time" name="late_start" value="{{ old('late_start', $clockSettings['late_start']) }}" required class="mt-4 w-full rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-sm text-brand-white focus:border-brand-red focus:ring-0">
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="rounded-xl bg-brand-red px-6 py-2.5 text-xs font-bold uppercase tracking-widest text-white hover:bg-red-700 transition">
                                        Save Visit Window
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="glass-panel rounded-2xl p-5 border border-brand-white/10 bg-brand-white/5">
                            <p class="text-xs uppercase tracking-widest text-brand-ash">How this works</p>
                            <div class="mt-4 space-y-3 text-sm text-brand-white/70 leading-relaxed">
                                <p>The PJP assigns the outlets for the day. Each assigned outlet gets its own Clock In and Clock Out controls on the merchandiser dashboard.</p>
                                <p>Clock-in verifies physical presence and unlocks the Perfect Store entry for that outlet. Clock-out records the visit duration after execution is scored.</p>
                                <p>Coverage is calculated as scored outlets divided by scheduled outlets, while missed outlets remain Not Covered after the visit window closes.</p>
                            </div>
                        </div>
                    </div>
                </div>
