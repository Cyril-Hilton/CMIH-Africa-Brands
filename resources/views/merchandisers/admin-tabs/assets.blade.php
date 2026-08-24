                <div x-show="activeTab === 'assets'" x-cloak x-transition>
                    <div class="glass-panel rounded-2xl border border-brand-white/10 overflow-hidden">
                        <div class="px-5 py-4 border-b border-brand-white/10 flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-brand-ash">All Field Gear & POSM Check-Outs</p>
                                <p class="text-xs text-brand-ash mt-0.5">Entered by merchandisers. {{ $allAssetsTotal }} total entries.</p>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-brand-white/10">
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Date</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Merchandiser</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Item</th>
                                        <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-brand-ash">Qty Out</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Location</th>
                                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-brand-ash">Notes</th>
                                        <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-brand-ash">Photo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($allAssets as $asset)
                                    <tr class="border-b border-brand-white/5">
                                        <td class="px-5 py-3 text-brand-ash text-xs whitespace-nowrap">{{ $asset->created_at->format('d M Y') }}</td>
                                        <td class="px-5 py-3">
                                            <p class="font-medium text-brand-white text-xs">{{ $asset->createdBy->name ?? '—' }}</p>
                                        </td>
                                        <td class="px-5 py-3 text-brand-white text-sm font-medium">{{ $asset->item_name }}</td>
                                        <td class="px-5 py-3 text-center">
                                            <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-blue-500/10 text-blue-400">{{ $asset->quantity_out }}</span>
                                        </td>
                                        <td class="px-5 py-3 text-brand-ash text-xs">{{ $asset->location ?? '—' }}</td>
                                        <td class="px-5 py-3 text-brand-ash text-xs max-w-[200px]">
                                            <div class="line-clamp-2">{{ strip_tags($asset->notes ?? '—') }}</div>
                                        </td>
                                        <td class="px-5 py-3 text-center">
                                            @if($asset->image_path)
                                                <a href="{{ Storage::disk('public')->url($asset->image_path) }}" target="_blank" class="inline-block">
                                                    <img src="{{ Storage::disk('public')->url($asset->image_path) }}" alt="Proof" class="w-10 h-10 rounded-lg object-cover border border-brand-white/20 hover:border-brand-red/50 transition cursor-pointer">
                                                </a>
                                            @else
                                                <span class="text-brand-ash/40 text-xs">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="7" class="px-5 py-10 text-center text-brand-ash text-sm">No field gear check-outs recorded yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($allAssets->hasPages())
                            <div class="border-t border-brand-white/10 px-5 py-4">
                                {{ $allAssets->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- ═══════════════════════════════════════════════════════════
                     TAB: NOTIFICATIONS & APPROVALS
                ════════════════════════════════════════════════════════════ -->
