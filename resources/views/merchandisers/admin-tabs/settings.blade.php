                <div x-show="activeTab === 'settings'" x-cloak x-transition>
                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
                        <div class="xl:col-span-2 merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between mb-5">
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Merchandiser outlet visits</p>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mt-1">Open Visit Window</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-1">Set the daily outlet clock-in window. Merchandisers can clock in and clock out at every assigned outlet during this period.</p>
                                </div>
                                <span class="inline-flex w-fit rounded-full border border-emerald-400/40 bg-emerald-100 dark:bg-emerald-500/20 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-300">
                                    No code deploy needed
                                </span>
                            </div>

                            <form method="POST" action="{{ route('merchandisers.admin.clock-settings.update') }}" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 p-4">
                                        <p class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold">Window Start</p>
                                        <p class="mt-1 text-sm font-bold text-slate-900 dark:text-white">{{ $clockSettings['label'] }}</p>
                                        <input type="time" name="visit_start" value="{{ old('visit_start', $clockSettings['start']) }}" required class="mt-4 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    </div>
                                    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 p-4">
                                        <p class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold">Window End</p>
                                        <p class="mt-1 text-sm font-bold text-slate-900 dark:text-white">{{ $clockSettings['range'] }}</p>
                                        <input type="time" name="visit_end" value="{{ old('visit_end', $clockSettings['end']) }}" required class="mt-4 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    </div>
                                    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 p-4">
                                        <p class="text-[10px] uppercase tracking-wider text-slate-900 dark:text-white font-extrabold">Late Start Flag</p>
                                        <p class="mt-1 text-sm font-bold text-slate-900 dark:text-white">After this time, first outlet starts are flagged late.</p>
                                        <input type="time" name="late_start" value="{{ old('late_start', $clockSettings['late_start']) }}" required class="mt-4 w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="rounded-xl bg-brand-red px-6 py-2.5 text-xs font-bold uppercase tracking-widest text-white hover:bg-red-700 transition shadow-sm">
                                        Save Visit Window
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="merch-card rounded-2xl p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">How this works</p>
                            <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-400 font-semibold leading-relaxed">
                                <p>The PJP assigns the outlets for the day. Each assigned outlet gets its own Clock In and Clock Out controls on the merchandiser dashboard.</p>
                                <p>Clock-in verifies physical presence and unlocks the Perfect Store entry for that outlet. Clock-out records the visit duration after execution is scored.</p>
                                <p>Coverage is calculated as scored outlets divided by scheduled outlets, while missed outlets remain Not Covered after the visit window closes.</p>
                            </div>
                        </div>
                    </div>
                </div>
