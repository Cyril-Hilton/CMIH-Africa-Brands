                <div x-show="activeTab === 'forms'" x-cloak x-transition class="space-y-6">
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                        <!-- Assign Google Form Card -->
                        <div class="merch-card p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm rounded-2xl">
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Assign Google Form</p>
                            <form method="POST" action="{{ route('merchandisers.admin.google-forms.store') }}" class="mt-4 grid gap-3 sm:grid-cols-2">
                                @csrf
                                <input name="title" required placeholder="Form name" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold placeholder-slate-400 focus:border-brand-red focus:ring-0 sm:col-span-2">
                                <input name="google_form_url" type="url" placeholder="Google Form URL" value="{{ old('google_form_url', 'https://docs.google.com/forms/d/e/1FAIpQLSfAKE-pKp82legHbJ5qza-R0lTVZ6fagvzG669Lc3PPDaHS6Q/viewform') }}" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold placeholder-slate-400 focus:border-brand-red focus:ring-0 sm:col-span-2">
                                <select name="assigned_user_id" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    <option value="">All merchandisers</option>
                                    @foreach($allMerchandisers as $m)<option value="{{ $m->id }}">{{ $m->name }}</option>@endforeach
                                </select>
                                <select name="kd_id" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    <option value="">Any KD</option>
                                    @foreach($kds as $kd)<option value="{{ $kd->id }}">{{ $kd->name }}</option>@endforeach
                                </select>
                                <select name="outlet_id" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    <option value="">Any outlet</option>
                                    @foreach($kds as $kd)
                                        @foreach($kd->outlets as $outlet)<option value="{{ $outlet->id }}">{{ $outlet->name }}</option>@endforeach
                                    @endforeach
                                </select>
                                <select name="channel_type" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    <option value="">Any channel</option>
                                    <option value="GT">GT</option>
                                    <option value="SSM">SSM</option>
                                    <option value="LMT">LMT</option>
                                    <option value="PHARMACY">Pharmacy</option>
                                    <option value="COSMETICS">Cosmetics</option>
                                </select>
                                <select name="brand_id" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    <option value="">Any brand</option>
                                    @foreach($brandOptions as $brand)<option value="{{ $brand->id }}">{{ $brand->name }}</option>@endforeach
                                </select>
                                <select name="campaign_id" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    <option value="">Any campaign</option>
                                    @foreach($campaignOptions as $campaign)<option value="{{ $campaign->id }}">{{ $campaign->name }}</option>@endforeach
                                </select>
                                <input name="category" placeholder="Category e.g. Perfect Store / Price Check" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold placeholder-slate-400 focus:border-brand-red focus:ring-0 sm:col-span-2">
                                <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 p-3">
                                    <input type="hidden" name="google_enabled" value="0">
                                    <label class="flex items-center gap-2 text-xs font-bold text-slate-900 dark:text-white">
                                        <input type="checkbox" name="google_enabled" value="1" checked class="rounded border-slate-300 text-brand-red focus:ring-brand-red">
                                        Allow Google Form
                                    </label>
                                </div>
                                <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 p-3">
                                    <input type="hidden" name="native_enabled" value="0">
                                    <label class="flex items-center gap-2 text-xs font-bold text-slate-900 dark:text-white">
                                        <input type="checkbox" name="native_enabled" value="1" checked class="rounded border-slate-300 text-brand-red focus:ring-brand-red">
                                        Allow inbuilt form
                                    </label>
                                </div>
                                <select name="native_template_key" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0 sm:col-span-2">
                                    <option value="perfect_store_v1">Perfect Store 2.0 Native Mirror</option>
                                </select>
                                <input type="date" name="starts_on" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                <input type="date" name="ends_on" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                <input type="hidden" name="status" value="active">
                                <textarea name="description" rows="3" placeholder="Notes / instructions" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold placeholder-slate-400 focus:border-brand-red focus:ring-0 sm:col-span-2"></textarea>
                                <button type="submit" class="rounded-xl bg-brand-red px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-white hover:bg-red-700 transition sm:col-span-2">Save Form Assignment</button>
                            </form>
                        </div>

                        <!-- Planogram Reference Card -->
                        <div class="merch-card p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm rounded-2xl">
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Planogram Reference</p>
                            <form method="POST" action="{{ route('merchandisers.admin.planograms.store') }}" enctype="multipart/form-data" class="mt-4 grid gap-3 sm:grid-cols-2">
                                @csrf
                                <input name="title" required placeholder="Planogram title" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold placeholder-slate-400 focus:border-brand-red focus:ring-0 sm:col-span-2">
                                <input name="category" placeholder="Category e.g. Pharmacy / Cosmetics" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold placeholder-slate-400 focus:border-brand-red focus:ring-0">
                                <select name="channel_type" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold focus:border-brand-red focus:ring-0">
                                    <option value="">Any channel</option>
                                    <option value="GT">GT</option>
                                    <option value="SSM">SSM</option>
                                    <option value="LMT">LMT</option>
                                    <option value="PHARMACY">Pharmacy</option>
                                    <option value="COSMETICS">Cosmetics</option>
                                </select>
                                <input type="file" name="reference_file" accept=".jpg,.jpeg,.png,.webp,.pdf,.ppt,.pptx" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold sm:col-span-2">
                                <textarea name="checklist_items" rows="4" placeholder="Checklist items, one per line" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold placeholder-slate-400 focus:border-brand-red focus:ring-0 sm:col-span-2"></textarea>
                                <textarea name="playbook_notes" rows="3" placeholder="Playbook notes" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-xs text-slate-900 dark:text-white font-semibold placeholder-slate-400 focus:border-brand-red focus:ring-0 sm:col-span-2"></textarea>
                                <input type="hidden" name="status" value="active">
                                <button type="submit" class="rounded-xl bg-brand-red px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-white hover:bg-red-700 transition sm:col-span-2">Save Planogram</button>
                            </form>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                        <!-- Assigned Google Forms Table -->
                        <div class="xl:col-span-2 merch-card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm rounded-2xl overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Assigned Google Forms</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm min-w-[760px]">
                                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                                        <tr class="border-b border-slate-200 dark:border-slate-800">
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Form</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Scope</th>
                                            <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Completed</th>
                                            <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                        @forelse($googleForms as $form)
                                            <tr>
                                                <td class="px-5 py-3">
                                                    @if($form->google_form_url)
                                                        <a href="{{ $form->google_form_url }}" target="_blank" rel="noopener" class="text-xs font-bold text-slate-900 dark:text-white hover:text-brand-red">{{ $form->title }}</a>
                                                    @else
                                                        <p class="text-xs font-bold text-slate-900 dark:text-white">{{ $form->title }}</p>
                                                    @endif
                                                    <p class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold">{{ $form->starts_on?->format('d M') ?? 'Anytime' }} - {{ $form->ends_on?->format('d M Y') ?? 'Open' }}</p>
                                                    <div class="mt-2 flex flex-wrap gap-1.5">
                                                        @if($form->google_enabled)
                                                            <span class="rounded-full border border-sky-400/40 bg-sky-100 dark:bg-sky-500/20 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-sky-800 dark:text-sky-200">Google</span>
                                                        @endif
                                                        @if($form->native_enabled)
                                                            <span class="rounded-full border border-emerald-400/40 bg-emerald-100 dark:bg-emerald-500/20 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-200">Inbuilt</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-5 py-3 text-xs text-slate-700 dark:text-slate-300 font-medium">
                                                    {{ $form->assignedUser?->name ?? 'All merchandisers' }} /
                                                    {{ $form->keyDistributor?->name ?? 'Any KD' }} /
                                                    {{ $form->outlet?->name ?? 'Any outlet' }} /
                                                    {{ $form->channel_type ?? 'Any channel' }} /
                                                    {{ $form->brand?->name ?? 'Any brand' }} /
                                                    {{ $form->campaign?->name ?? 'Any campaign' }} /
                                                    {{ $form->category ?? 'Any category' }}
                                                </td>
                                                <td class="px-5 py-3 text-center text-xs font-bold text-emerald-700 dark:text-emerald-300">
                                                    <span class="block">{{ $form->submissions_count }} Google</span>
                                                    <span class="mt-1 block">{{ $form->native_submissions_count }} Inbuilt</span>
                                                </td>
                                                <td class="px-5 py-3 text-right">
                                                    <form method="POST" action="{{ route('merchandisers.admin.google-forms.destroy', $form) }}" onsubmit="return confirm('Deactivate this form assignment?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="rounded-lg bg-red-100 dark:bg-red-500/20 px-2.5 py-1 text-[10px] font-bold text-red-700 dark:text-red-300 hover:bg-red-200 transition">Deactivate</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="px-5 py-8 text-center text-sm text-slate-600 dark:text-slate-400 font-semibold">No Google Forms assigned yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Perfect Store Reference Guides -->
                        <div class="merch-card p-5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm rounded-2xl space-y-4">
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Perfect Store References</p>
                            @foreach($perfectStoreGuides as $channel => $items)
                                <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 p-3.5">
                                    <p class="text-xs font-bold text-slate-900 dark:text-white">{{ $channel }}</p>
                                    <p class="mt-1 text-[11px] leading-relaxed text-slate-700 dark:text-slate-300 font-medium">{{ implode(', ', $items) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Saved Planograms Grid -->
                    <div class="merch-card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm rounded-2xl overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                            <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">Saved Planograms</p>
                        </div>
                        <div class="grid gap-3 p-5 md:grid-cols-2 xl:grid-cols-3">
                            @forelse($planograms as $planogram)
                                <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $planogram->title }}</p>
                                            <p class="text-[10px] text-slate-600 dark:text-slate-400 font-semibold">{{ $planogram->category ?? 'General' }} / {{ $planogram->channel_type ?? 'Any channel' }}</p>
                                        </div>
                                        <form method="POST" action="{{ route('merchandisers.admin.planograms.destroy', $planogram) }}" onsubmit="return confirm('Remove this planogram?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 text-xs font-bold hover:underline">Remove</button>
                                        </form>
                                    </div>
                                    @if($planogram->reference_file_path)
                                        <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($planogram->reference_file_path) }}" target="_blank" rel="noopener" class="mt-3 inline-flex rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-900 dark:text-white hover:bg-slate-100 dark:hover:bg-slate-700 transition">Open Reference</a>
                                    @endif
                                    @if($planogram->checklist)
                                        <ul class="mt-3 space-y-1 text-[11px] text-slate-700 dark:text-slate-300 font-medium">
                                            @foreach(array_slice($planogram->checklist, 0, 5) as $item)
                                                <li>• {{ $item }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @empty
                                <p class="text-sm text-slate-600 dark:text-slate-400 font-semibold md:col-span-2 xl:col-span-3">No planogram references saved yet.</p>
                            @endforelse
                        </div>
                        @if($planograms->hasPages())
                            <div class="border-t border-slate-200 dark:border-slate-800 px-5 py-4">
                                {{ $planograms->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- ═══════════════════════════════════════════════════════════
                     TAB: MANAGE MERCHANDISERS
                ════════════════════════════════════════════════════════════ -->
