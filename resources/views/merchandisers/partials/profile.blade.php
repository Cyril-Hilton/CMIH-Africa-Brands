<section x-show="activeTab === 'profile'"
         x-data="{ showPhotoModal: false, photoPreview: null }"
         class="space-y-6"
         x-cloak>
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Staff Profile Header Card -->
        <div class="rounded-2xl p-5 sm:p-6 border border-sky-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm flex flex-col sm:flex-row items-center gap-5 sm:gap-6 relative">
            <div class="relative group shrink-0 mx-auto sm:mx-0">
                <div class="h-28 w-28 rounded-full overflow-hidden ring-4 ring-blue-500/30 shadow-xl relative aspect-square shrink-0 mx-auto bg-[#E21C1E] flex items-center justify-center">
                    <img :src="photoPreview || @js(auth()->user()->profilePhotoUrl())"
                         alt="{{ auth()->user()->name }}"
                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?: 'User') }}&color=38BDF8&background=0F172A&bold=true';"
                         class="w-full h-full object-cover rounded-full block scale-[1.45] transform-gpu origin-center"
                         style="width: 100% !important; height: 100% !important; object-fit: cover !important;">
                </div>
                <button type="button" @click="showPhotoModal = true"
                        class="absolute bottom-0 right-0 h-8 w-8 rounded-full bg-[#155EEF] text-white flex items-center justify-center text-xs shadow-lg hover:scale-110 transition cursor-pointer z-10 ring-2 ring-white"
                        title="Upload / Change Avatar"
                        style="background-color: #155EEF !important; color: #ffffff !important;">
                    📷
                </button>
            </div>

            <div class="min-w-0 flex-1 text-center sm:text-left space-y-2 w-full">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white leading-tight">{{ auth()->user()->name }}</h2>
                        <p class="text-xs text-[#0284C7] dark:text-sky-400 font-extrabold uppercase tracking-wider mt-0.5">{{ auth()->user()->job_title ?: 'Merchandiser Field Agent' }}</p>
                    </div>
                    <span class="inline-flex items-center justify-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 self-center sm:self-auto">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Active Staff Profile
                    </span>
                </div>
                <!-- Clean Contact Info -->
                <div class="flex flex-col sm:flex-row items-center justify-center sm:justify-start gap-1 sm:gap-3 text-xs text-slate-700 dark:text-slate-300 font-bold pt-1">
                    <div><span class="text-slate-500 font-medium">Phone:</span> {{ auth()->user()->phone ?: 'No phone registered' }}</div>
                    <div class="hidden sm:block text-slate-400">&bull;</div>
                    <div><span class="text-slate-500 font-medium">Email:</span> {{ auth()->user()->email }}</div>
                </div>
                <!-- High-Contrast Buttons -->
                <div class="pt-3 flex flex-col sm:flex-row items-stretch sm:items-center gap-2 justify-center sm:justify-start">
                    <button type="button" @click="showPhotoModal = true"
                            class="w-full sm:w-auto px-5 py-2.5 rounded-xl text-xs font-black shadow-md transition-all flex items-center justify-center gap-2"
                            style="background-color: #155EEF !important; color: #ffffff !important;">
                        Change Profile Photo
                    </button>
                    <button type="button" onclick="window.location.reload()"
                            class="w-full sm:w-auto px-5 py-2.5 rounded-xl text-xs font-extrabold shadow-sm transition-all flex items-center justify-center gap-2"
                            style="background-color: #E0F2FE !important; color: #0C4A6E !important; border: 1.5px solid #7DD3FC !important;">
                        Refresh / Sync Data
                    </button>
                </div>
            </div>
        </div>

        <!-- Sub-Tab Header Navigation Bar (Responsive Stack/Grid for Mobile & Desktop) -->
        <div class="grid grid-cols-1 sm:flex sm:items-center gap-2 border-b border-sky-200 dark:border-slate-800 pb-3">
            <button type="button" @click="profileSubTab = 'personal'"
                    :style="profileSubTab === 'personal' ? 'background-color: #155EEF !important; color: #ffffff !important;' : 'background-color: #E0F2FE; color: #0C4A6E;'"
                    :class="profileSubTab === 'personal' ? 'shadow-md font-black' : 'hover:bg-sky-200 font-bold'"
                    class="w-full sm:w-auto px-4 py-3 sm:py-2.5 rounded-xl text-xs uppercase tracking-wider transition flex items-center justify-center sm:justify-start gap-2 text-center sm:text-left">
                <span>Staff Profile &amp; Personal Details</span>
            </button>

            <button type="button" @click="profileSubTab = 'banking'"
                    :style="profileSubTab === 'banking' ? 'background-color: #155EEF !important; color: #ffffff !important;' : 'background-color: #E0F2FE; color: #0C4A6E;'"
                    :class="profileSubTab === 'banking' ? 'shadow-md font-black' : 'hover:bg-sky-200 font-bold'"
                    class="w-full sm:w-auto px-4 py-3 sm:py-2.5 rounded-xl text-xs uppercase tracking-wider transition flex items-center justify-center sm:justify-start gap-2 text-center sm:text-left">
                <span>Mobile Money &amp; Banking Credentials</span>
            </button>

            <button type="button" @click="profileSubTab = 'organization'"
                    :style="profileSubTab === 'organization' ? 'background-color: #155EEF !important; color: #ffffff !important;' : 'background-color: #E0F2FE; color: #0C4A6E;'"
                    :class="profileSubTab === 'organization' ? 'shadow-md font-black' : 'hover:bg-sky-200 font-bold'"
                    class="w-full sm:w-auto px-4 py-3 sm:py-2.5 rounded-xl text-xs uppercase tracking-wider transition flex items-center justify-center sm:justify-start gap-2 text-center sm:text-left">
                <span>Organization &amp; Assignment</span>
            </button>
        </div>

        <!-- SUB-TAB 1: STAFF PROFILE & PERSONAL DETAILS FORM -->
        <div x-show="profileSubTab === 'personal'" class="space-y-6" x-cloak>
            <div class="rounded-2xl p-6 border border-sky-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-sky-100 dark:border-slate-800 pb-4">
                    <div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white">Edit Staff Profile &amp; Personal Details</h3>
                        <p class="text-xs text-slate-500 font-medium">Update your official contact information, address, and login credentials</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('merchandisers.profile.update') }}" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="edit_name" value="Full Staff Name *" class="text-xs font-bold text-slate-700 dark:text-slate-300" />
                            <x-text-input id="edit_name" name="name" type="text" class="mt-1 block w-full text-xs" :value="old('name', auth()->user()->name)" required />
                        </div>

                        <div>
                            <x-input-label for="edit_email" value="Email Address *" class="text-xs font-bold text-slate-700 dark:text-slate-300" />
                            <x-text-input id="edit_email" name="email" type="email" class="mt-1 block w-full text-xs" :value="old('email', auth()->user()->email)" required />
                        </div>

                        <div>
                            <x-input-label for="edit_phone" value="Phone Number *" class="text-xs font-bold text-slate-700 dark:text-slate-300" />
                            <x-text-input id="edit_phone" name="phone" type="text" class="mt-1 block w-full text-xs" :value="old('phone', auth()->user()->phone)" required />
                        </div>

                        <div>
                            <x-input-label for="edit_residential_address" value="Residential Address" class="text-xs font-bold text-slate-700 dark:text-slate-300" />
                            <x-text-input id="edit_residential_address" name="residential_address" type="text" class="mt-1 block w-full text-xs" :value="old('residential_address', auth()->user()->residential_address)" placeholder="e.g. House No. 12, Osu, Accra" />
                        </div>
                    </div>

                    <div class="border-t border-sky-100 dark:border-slate-800 pt-4 space-y-4">
                        <h4 class="text-xs font-extrabold uppercase tracking-wider text-[#0284C7]">Password Reset &amp; Security</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="edit_password" value="New Password (optional)" class="text-xs font-bold text-slate-700 dark:text-slate-300" />
                                <x-text-input id="edit_password" name="password" type="password" class="mt-1 block w-full text-xs" placeholder="Leave blank to keep current" />
                            </div>
                            <div>
                                <x-input-label for="edit_password_confirmation" value="Confirm New Password" class="text-xs font-bold text-slate-700 dark:text-slate-300" />
                                <x-text-input id="edit_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full text-xs" placeholder="Repeat new password" />
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end border-t border-sky-100 dark:border-slate-800">
                        <button type="submit" class="px-6 py-3 rounded-xl bg-[#155EEF] hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider shadow-md transition">
                            Save Profile Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- SUB-TAB 2: MOBILE MONEY & BANKING CREDENTIALS FORM -->
        <div x-show="profileSubTab === 'banking'" class="space-y-6" x-cloak>
            <div class="rounded-2xl p-6 border border-sky-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-sky-100 dark:border-slate-800 pb-4">
                    <div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white">💳 Banking &amp; Mobile Money Payout Details</h3>
                        <p class="text-xs text-slate-500 font-medium">Configure your preferred MoMo network or bank account for field allowances and salary payouts</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('merchandisers.profile.update') }}" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-4">
                        <h4 class="text-xs font-extrabold uppercase tracking-wider text-[#0284C7]">Mobile Money Account</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="edit_momo_number" value="Mobile Money Phone Number *" class="text-xs font-bold text-slate-700 dark:text-slate-300" />
                                <x-text-input id="edit_momo_number" name="momo_number" type="text" class="mt-1 block w-full text-xs" :value="old('momo_number', auth()->user()->momo_number)" placeholder="e.g. 0541234567" required />
                            </div>
                            <div>
                                <x-input-label for="edit_momo_name" value="Registered Account Holder Name *" class="text-xs font-bold text-slate-700 dark:text-slate-300" />
                                <x-text-input id="edit_momo_name" name="momo_name" type="text" class="mt-1 block w-full text-xs" :value="old('momo_name', auth()->user()->momo_name)" placeholder="e.g. Ama Field Agent" required />
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end border-t border-sky-100 dark:border-slate-800">
                        <button type="submit" class="px-6 py-3 rounded-xl bg-[#155EEF] hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider shadow-md transition">
                            Save Banking Details
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- SUB-TAB 3: ORGANIZATION & ASSIGNMENT INFO -->
        <div x-show="profileSubTab === 'organization'" class="space-y-6" x-cloak>
            <div class="rounded-2xl p-6 border border-sky-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm space-y-5">
                <div class="border-b border-sky-100 dark:border-slate-800 pb-4">
                    <h3 class="text-lg font-black text-slate-900 dark:text-white">💼 Organizational Assignment</h3>
                    <p class="text-xs text-slate-500 font-medium">Your current field hierarchy, key distributor mapping, and supervisor</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl border border-sky-100 bg-[#F0F9FF] dark:bg-slate-800">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Assigned KD</p>
                        <p class="text-sm font-black text-slate-900 dark:text-white mt-1">{{ auth()->user()->merchandiserKd->name ?? 'Unilever Ghana Central KD' }}</p>
                    </div>

                    <div class="p-4 rounded-xl border border-sky-100 bg-[#F0F9FF] dark:bg-slate-800">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Field Region</p>
                        <p class="text-sm font-black text-slate-900 dark:text-white mt-1">{{ auth()->user()->merchandiserRegion->name ?? 'Greater Accra Territory' }}</p>
                    </div>

                    <div class="p-4 rounded-xl border border-sky-100 bg-[#F0F9FF] dark:bg-slate-800">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Direct Supervisor</p>
                        <p class="text-sm font-black text-slate-900 dark:text-white mt-1">Kweku Supervisor</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal: Profile Photo Upload -->
    <div x-show="showPhotoModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
        <div class="rounded-2xl bg-white dark:bg-slate-900 border border-sky-200 dark:border-slate-800 w-full max-w-md p-6 space-y-4 shadow-2xl relative" @click.away="showPhotoModal = false">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Upload Staff Profile Picture</h3>
                <button type="button" @click="showPhotoModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
            </div>

            <form method="POST" action="{{ route('merchandisers.profile.photo.update') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="flex flex-col items-center gap-3">
                    <div class="h-28 w-28 rounded-full border-2 border-dashed border-sky-300 flex items-center justify-center overflow-hidden bg-[#F0F9FF]">
                        <template x-if="photoPreview">
                            <img :src="photoPreview" class="h-28 w-28 rounded-full object-cover">
                        </template>
                        <template x-if="!photoPreview">
                            <span class="text-3xl">📷</span>
                        </template>
                    </div>
                    <label class="cursor-pointer px-4 py-2 bg-[#E0F2FE] hover:bg-sky-200 rounded-xl text-xs font-bold text-[#0C4A6E] border border-sky-200 transition">
                        Select Photo File
                        <input type="file" name="profile_photo" accept="image/*" required class="hidden"
                               @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => photoPreview = e.target.result; reader.readAsDataURL(file); }">
                    </label>
                </div>

                <div class="pt-2 flex justify-end gap-2 border-t border-slate-200 dark:border-slate-800">
                    <button type="button" @click="showPhotoModal = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#155EEF] text-white text-xs font-bold uppercase tracking-wider hover:bg-blue-700 transition shadow-md">Save Avatar</button>
                </div>
            </form>
        </div>
    </div>
</section>
