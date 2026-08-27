                <div x-show="activeTab === 'assets'" x-cloak x-transition>
                    <div class="merch-card rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-slate-900 dark:text-white font-extrabold">All Field Gear & POSM Check-Outs</p>
                                <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-0.5">Entered by merchandisers. {{ $allAssetsTotal }} total entries.</p>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-50 dark:bg-slate-800/50">
                                    <tr class="border-b border-slate-200 dark:border-slate-800">
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Date</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Merchandiser</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Item</th>
                                        <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Qty Out</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Location</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Notes</th>
                                        <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-slate-900 dark:text-slate-100 font-extrabold">Photo</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                    @forelse($allAssets as $asset)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                        <td class="px-5 py-3 text-slate-600 dark:text-slate-400 text-xs font-semibold whitespace-nowrap">{{ $asset->created_at->format('d M Y') }}</td>
                                        <td class="px-5 py-3">
                                            <p class="font-bold text-slate-900 dark:text-white text-xs">{{ $asset->createdBy->name ?? '—' }}</p>
                                        </td>
                                        <td class="px-5 py-3 text-slate-900 dark:text-white text-sm font-bold">{{ $asset->item_name }}</td>
                                        <td class="px-5 py-3 text-center">
                                            <span class="text-xs font-extrabold px-2.5 py-0.5 rounded-full bg-sky-100 dark:bg-sky-500/20 text-sky-800 dark:text-sky-300 border border-sky-400/30">{{ $asset->quantity_out }}</span>
                                        </td>
                                        <td class="px-5 py-3 text-slate-600 dark:text-slate-400 text-xs font-semibold">{{ $asset->location ?? '—' }}</td>
                                        <td class="px-5 py-3 text-slate-600 dark:text-slate-400 text-xs font-semibold max-w-[200px]">
                                            <div class="line-clamp-2">{{ strip_tags($asset->notes ?? '—') }}</div>
                                        </td>
                                        <td class="px-5 py-3 text-center">
                                            @if($asset->image_path)
                                                <a href="{{ Storage::disk('public')->url($asset->image_path) }}" target="_blank" class="inline-block">
                                                    <img src="{{ Storage::disk('public')->url($asset->image_path) }}" alt="Proof" class="w-10 h-10 rounded-lg object-cover border border-slate-300 dark:border-slate-700 hover:border-brand-red transition cursor-pointer shadow-sm">
                                                </a>
                                            @else
                                                <span class="text-slate-400 dark:text-slate-500 text-xs">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="7" class="px-5 py-10 text-center text-slate-600 dark:text-slate-400 text-sm font-semibold">No field gear check-outs recorded yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($allAssets->hasPages())
                            <div class="border-t border-slate-200 dark:border-slate-800 px-5 py-4">
                                {{ $allAssets->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- ═══════════════════════════════════════════════════════════
                     TAB: NOTIFICATIONS & APPROVALS
                ════════════════════════════════════════════════════════════ -->
