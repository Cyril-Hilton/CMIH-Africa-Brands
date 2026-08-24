                <div x-show="activeTab === 'forms'" x-cloak x-transition class="space-y-6">
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                        <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-black/40 p-5">
                            <p class="text-xs uppercase tracking-widest text-brand-ash font-bold">Assign Google Form</p>
                            <form method="POST" action="{{ route('merchandisers.admin.google-forms.store') }}" class="mt-4 grid gap-3 sm:grid-cols-2">
                                @csrf
                                <input name="title" required placeholder="Form name" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white placeholder-brand-ash focus:border-brand-red focus:ring-0 sm:col-span-2">
                                <input name="google_form_url" type="url" placeholder="Google Form URL" value="{{ old('google_form_url', 'https://docs.google.com/forms/d/e/1FAIpQLSfAKE-pKp82legHbJ5qza-R0lTVZ6fagvzG669Lc3PPDaHS6Q/viewform') }}" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white placeholder-brand-ash focus:border-brand-red focus:ring-0 sm:col-span-2">
                                <select name="assigned_user_id" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                    <option value="">All merchandisers</option>
                                    @foreach($allMerchandisers as $m)<option value="{{ $m->id }}">{{ $m->name }}</option>@endforeach
                                </select>
                                <select name="kd_id" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                    <option value="">Any KD</option>
                                    @foreach($kds as $kd)<option value="{{ $kd->id }}">{{ $kd->name }}</option>@endforeach
                                </select>
                                <select name="outlet_id" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                    <option value="">Any outlet</option>
                                    @foreach($kds as $kd)
                                        @foreach($kd->outlets as $outlet)<option value="{{ $outlet->id }}">{{ $outlet->name }}</option>@endforeach
                                    @endforeach
                                </select>
                                <select name="channel_type" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                    <option value="">Any channel</option>
                                    <option value="GT">GT</option>
                                    <option value="SSM">SSM</option>
                                    <option value="LMT">LMT</option>
                                    <option value="PHARMACY">Pharmacy</option>
                                    <option value="COSMETICS">Cosmetics</option>
                                </select>
                                <select name="brand_id" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                    <option value="">Any brand</option>
                                    @foreach($brandOptions as $brand)<option value="{{ $brand->id }}">{{ $brand->name }}</option>@endforeach
                                </select>
                                <select name="campaign_id" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                    <option value="">Any campaign</option>
                                    @foreach($campaignOptions as $campaign)<option value="{{ $campaign->id }}">{{ $campaign->name }}</option>@endforeach
                                </select>
                                <input name="category" placeholder="Category e.g. Perfect Store / Price Check" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white placeholder-brand-ash focus:border-brand-red focus:ring-0 sm:col-span-2">
                                <div class="rounded-xl border border-brand-white/10 bg-brand-white/[0.03] p-3">
                                    <input type="hidden" name="google_enabled" value="0">
                                    <label class="flex items-center gap-2 text-xs font-semibold text-brand-white">
                                        <input type="checkbox" name="google_enabled" value="1" checked class="rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-brand-red">
                                        Allow Google Form
                                    </label>
                                </div>
                                <div class="rounded-xl border border-brand-white/10 bg-brand-white/[0.03] p-3">
                                    <input type="hidden" name="native_enabled" value="0">
                                    <label class="flex items-center gap-2 text-xs font-semibold text-brand-white">
                                        <input type="checkbox" name="native_enabled" value="1" checked class="rounded border-brand-white/20 bg-brand-black text-brand-red focus:ring-brand-red">
                                        Allow inbuilt form
                                    </label>
                                </div>
                                <select name="native_template_key" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0 sm:col-span-2">
                                    <option value="perfect_store_v1">Perfect Store 2.0 Native Mirror</option>
                                </select>
                                <input type="date" name="starts_on" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                <input type="date" name="ends_on" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                <input type="hidden" name="status" value="active">
                                <textarea name="description" rows="3" placeholder="Notes / instructions" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white placeholder-brand-ash focus:border-brand-red focus:ring-0 sm:col-span-2"></textarea>
                                <button type="submit" class="rounded-xl bg-brand-red px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-white hover:bg-red-700 transition sm:col-span-2">Save Form Assignment</button>
                            </form>
                        </div>

                        <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-black/40 p-5">
                            <p class="text-xs uppercase tracking-widest text-brand-ash font-bold">Planogram Reference</p>
                            <form method="POST" action="{{ route('merchandisers.admin.planograms.store') }}" enctype="multipart/form-data" class="mt-4 grid gap-3 sm:grid-cols-2">
                                @csrf
                                <input name="title" required placeholder="Planogram title" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white placeholder-brand-ash focus:border-brand-red focus:ring-0 sm:col-span-2">
                                <input name="category" placeholder="Category e.g. Pharmacy / Cosmetics" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white placeholder-brand-ash focus:border-brand-red focus:ring-0">
                                <select name="channel_type" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white focus:border-brand-red focus:ring-0">
                                    <option value="">Any channel</option>
                                    <option value="GT">GT</option>
                                    <option value="SSM">SSM</option>
                                    <option value="LMT">LMT</option>
                                    <option value="PHARMACY">Pharmacy</option>
                                    <option value="COSMETICS">Cosmetics</option>
                                </select>
                                <input type="file" name="reference_file" accept=".jpg,.jpeg,.png,.webp,.pdf,.ppt,.pptx" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white sm:col-span-2">
                                <textarea name="checklist_items" rows="4" placeholder="Checklist items, one per line" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white placeholder-brand-ash focus:border-brand-red focus:ring-0 sm:col-span-2"></textarea>
                                <textarea name="playbook_notes" rows="3" placeholder="Playbook notes" class="rounded-xl border border-brand-white/10 bg-brand-black px-3 py-2 text-xs text-brand-white placeholder-brand-ash focus:border-brand-red focus:ring-0 sm:col-span-2"></textarea>
                                <input type="hidden" name="status" value="active">
                                <button type="submit" class="rounded-xl bg-brand-red px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-white hover:bg-red-700 transition sm:col-span-2">Save Planogram</button>
                            </form>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                        <div class="xl:col-span-2 glass-panel rounded-2xl border border-brand-white/10 overflow-hidden">
                            <div class="px-5 py-4 border-b border-brand-white/10">
                                <p class="text-xs uppercase tracking-widest text-brand-ash">Assigned Google Forms</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm min-w-[760px]">
                                    <thead class="bg-brand-white/3">
                                        <tr class="border-b border-brand-white/10">
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Form</th>
                                            <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Scope</th>
                                            <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-brand-ash">Completed</th>
                                            <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-brand-ash">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($googleForms as $form)
                                            <tr class="border-b border-brand-white/5">
                                                <td class="px-5 py-3">
                                                    @if($form->google_form_url)
                                                        <a href="{{ $form->google_form_url }}" target="_blank" rel="noopener" class="text-xs font-semibold text-brand-white hover:text-brand-red">{{ $form->title }}</a>
                                                    @else
                                                        <p class="text-xs font-semibold text-brand-white">{{ $form->title }}</p>
                                                    @endif
                                                    <p class="text-[10px] text-brand-ash">{{ $form->starts_on?->format('d M') ?? 'Anytime' }} - {{ $form->ends_on?->format('d M Y') ?? 'Open' }}</p>
                                                    <div class="mt-2 flex flex-wrap gap-1.5">
                                                        @if($form->google_enabled)
                                                            <span class="rounded-full border border-sky-400/20 bg-sky-500/10 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-sky-200">Google</span>
                                                        @endif
                                                        @if($form->native_enabled)
                                                            <span class="rounded-full border border-emerald-400/20 bg-emerald-500/10 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-emerald-200">Inbuilt</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-5 py-3 text-xs text-brand-ash">
                                                    {{ $form->assignedUser?->name ?? 'All merchandisers' }} /
                                                    {{ $form->keyDistributor?->name ?? 'Any KD' }} /
                                                    {{ $form->outlet?->name ?? 'Any outlet' }} /
                                                    {{ $form->channel_type ?? 'Any channel' }} /
                                                    {{ $form->brand?->name ?? 'Any brand' }} /
                                                    {{ $form->campaign?->name ?? 'Any campaign' }} /
                                                    {{ $form->category ?? 'Any category' }}
                                                </td>
                                                <td class="px-5 py-3 text-center text-xs font-bold text-green-300">
                                                    <span class="block">{{ $form->submissions_count }} Google</span>
                                                    <span class="mt-1 block text-emerald-300">{{ $form->native_submissions_count }} Inbuilt</span>
                                                </td>
                                                <td class="px-5 py-3 text-right">
                                                    <form method="POST" action="{{ route('merchandisers.admin.google-forms.destroy', $form) }}" onsubmit="return confirm('Deactivate this form assignment?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="rounded-lg bg-brand-red/20 px-2.5 py-1 text-[10px] font-bold text-brand-red hover:bg-brand-red/30">Deactivate</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="px-5 py-8 text-center text-sm text-brand-ash">No Google Forms assigned yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="glass-panel rounded-2xl border border-brand-white/10 bg-brand-black/40 p-5 space-y-4">
                            <p class="text-xs uppercase tracking-widest text-brand-ash">Perfect Store References</p>
                            @foreach($perfectStoreGuides as $channel => $items)
                                <div class="rounded-xl border border-brand-white/10 bg-brand-white/[0.03] p-3">
                                    <p class="text-xs font-bold text-brand-white">{{ $channel }}</p>
                                    <p class="mt-1 text-[11px] leading-relaxed text-brand-white/50">{{ implode(', ', $items) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="glass-panel rounded-2xl border border-brand-white/10 overflow-hidden">
                        <div class="px-5 py-4 border-b border-brand-white/10">
                            <p class="text-xs uppercase tracking-widest text-brand-ash">Saved Planograms</p>
                        </div>
                        <div class="grid gap-3 p-5 md:grid-cols-2 xl:grid-cols-3">
                            @forelse($planograms as $planogram)
                                <div class="rounded-xl border border-brand-white/10 bg-brand-white/[0.04] p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold text-brand-white">{{ $planogram->title }}</p>
                                            <p class="text-[10px] text-brand-ash">{{ $planogram->category ?? 'General' }} / {{ $planogram->channel_type ?? 'Any channel' }}</p>
                                        </div>
                                        <form method="POST" action="{{ route('merchandisers.admin.planograms.destroy', $planogram) }}" onsubmit="return confirm('Remove this planogram?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-brand-red text-xs font-bold">Remove</button>
                                        </form>
                                    </div>
                                    @if($planogram->reference_file_path)
                                        <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($planogram->reference_file_path) }}" target="_blank" rel="noopener" class="mt-3 inline-flex rounded-lg border border-brand-white/10 bg-brand-white/5 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-brand-white hover:bg-brand-white/10">Open Reference</a>
                                    @endif
                                    @if($planogram->checklist)
                                        <ul class="mt-3 space-y-1 text-[11px] text-brand-white/55">
                                            @foreach(array_slice($planogram->checklist, 0, 5) as $item)
                                                <li>{{ $item }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @empty
                                <p class="text-sm text-brand-ash md:col-span-2 xl:col-span-3">No planogram references saved yet.</p>
                            @endforelse
                        </div>
                        @if($planograms->hasPages())
                            <div class="border-t border-brand-white/10 px-5 py-4">
                                {{ $planograms->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- ═══════════════════════════════════════════════════════════
                     TAB: MANAGE MERCHANDISERS
                ════════════════════════════════════════════════════════════ -->
