<x-guest-layout>
    <div class="space-y-5 sm:space-y-6">
        <!-- Header -->
        <div class="space-y-1.5 sm:space-y-2">
            <p class="text-[10px] sm:text-xs uppercase tracking-[0.25em] sm:tracking-[0.3em] text-brand-ash font-bold">Merchandiser Portal Access</p>
            <h2 class="text-2xl sm:text-3xl font-display text-brand-white">Register Account</h2>
            <p class="text-xs sm:text-sm text-brand-white/70 leading-relaxed">Create your merchandiser portal account. Select your role and brand workspace. All accounts require admin approval before activation.</p>
        </div>

        <form method="POST" action="{{ route('merchandisers.register') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Unified Role Selector (Field Agent, Supervisor, Client / TM) -->
            <fieldset class="space-y-2">
                <legend class="text-[11px] sm:text-xs font-bold uppercase tracking-[0.16em] text-brand-white/80">Select Portal Role</legend>
                <p class="text-[11px] sm:text-xs leading-5 text-brand-white/55">Choose the account role you are applying for in the portal.</p>
                <div class="grid grid-cols-1 gap-2 sm:grid-cols-3 sm:gap-2.5">
                    @foreach($portalRoles as $roleKey => $roleDef)
                        <label class="group cursor-pointer rounded-xl border border-brand-white/10 bg-brand-white/5 p-3 transition hover:border-brand-red/45 hover:bg-brand-red/5 active:scale-[0.99]">
                            <input type="radio" name="portal_role" value="{{ $roleKey }}" class="sr-only peer" @checked(old('portal_role', $activeRole) === $roleKey) required>
                            <div class="flex items-center sm:flex-col sm:items-start gap-2.5 sm:gap-1">
                                <span class="text-lg sm:text-base shrink-0">{{ $roleDef['icon'] }}</span>
                                <div class="min-w-0">
                                    <span class="block text-xs font-bold text-brand-white group-hover:text-white peer-checked:text-brand-red truncate">{{ $roleDef['label'] }}</span>
                                    <p class="text-[10px] text-brand-ash leading-snug truncate">{{ $roleDef['short_label'] }} registration</p>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('portal_role')" class="mt-1" />
            </fieldset>

            <!-- Full Name -->
            <div>
                <x-input-label for="name" :value="__('Full Name')" class="text-xs font-semibold" />
                <x-text-input id="name" type="text" name="name" :value="old('name')" required placeholder="Enter your full name" class="w-full text-xs sm:text-sm" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <!-- Profile Photo Upload -->
            <div>
                <x-input-label for="profile_photo" :value="__('Profile Photo / Headshot Image (Required)')" class="text-xs font-semibold" />
                <div class="mt-1.5 flex items-center gap-3 rounded-xl border border-brand-white/15 bg-brand-white/5 p-2.5 sm:p-3 transition hover:border-brand-red/40">
                    <input id="profile_photo" type="file" name="profile_photo" accept="image/*" required class="w-full text-xs text-brand-white/80 file:mr-2.5 file:rounded-lg file:border-0 file:bg-brand-red file:px-2.5 file:py-1 file:text-xs file:font-bold file:text-white hover:file:bg-brand-red/80 cursor-pointer">
                </div>
                <p class="text-[10px] text-brand-white/50 mt-1 leading-tight">Upload a clear front-facing headshot photo for your ID card and avatar (JPG, PNG, WEBP, max 5MB).</p>
                <x-input-error :messages="$errors->get('profile_photo')" class="mt-1" />
            </div>

            <!-- Emails Row (2 Columns on SM+) -->
            <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-2">
                <div>
                    <x-input-label for="email" :value="__('Login Email Address')" class="text-xs font-semibold" />
                    <x-text-input id="email" type="email" name="email" :value="old('email')" required placeholder="name@company.com" class="w-full text-xs sm:text-sm" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="contact_email" :value="__('Personal/Contact Email')" class="text-xs font-semibold" />
                    <x-text-input id="contact_email" type="email" name="contact_email" :value="old('contact_email')" required placeholder="personal@email.com" class="w-full text-xs sm:text-sm" />
                    <x-input-error :messages="$errors->get('contact_email')" class="mt-1" />
                </div>
            </div>

            <!-- Phone & Date of Birth Row (2 Columns on SM+) -->
            <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-2">
                <div>
                    <x-input-label for="phone" :value="__('Phone Number')" class="text-xs font-semibold" />
                    <x-text-input id="phone" type="text" name="phone" :value="old('phone')" required placeholder="e.g. +23354XXXXXXX" class="w-full text-xs sm:text-sm" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="date_of_birth" :value="__('Date of Birth')" class="text-xs font-semibold" />
                    <x-text-input id="date_of_birth" type="date" name="date_of_birth" :value="old('date_of_birth')" required class="w-full text-xs sm:text-sm" />
                    <x-input-error :messages="$errors->get('date_of_birth')" class="mt-1" />
                </div>
            </div>

            <!-- Brand Affiliation -->
            <fieldset class="space-y-2">
                <legend class="text-[11px] sm:text-xs font-bold uppercase tracking-[0.16em] text-brand-white/80">Brand Affiliation</legend>
                <p class="text-[11px] sm:text-xs leading-5 text-brand-white/55">Choose the business whose outlets and field instructions you work on.</p>
                <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2">
                    @foreach($merchandiserTenants as $tenant)
                        <label class="group cursor-pointer rounded-xl border border-brand-white/10 bg-brand-white/5 p-3 sm:p-3.5 transition hover:border-brand-red/45 hover:bg-brand-red/5 active:scale-[0.99]">
                            <input type="radio" name="merchandiser_tenant" value="{{ $tenant['code'] }}" class="sr-only peer" @checked(old('merchandiser_tenant', 'unilever') === $tenant['code']) required>
                            <span class="flex items-center gap-3">
                                <div class="h-10 w-10 sm:h-11 sm:w-11 shrink-0 rounded-xl bg-white p-1.5 flex items-center justify-center shadow-md">
                                    <img src="{{ asset($tenant['code'] === 'unilever' ? 'storage/brands/unilever-light.png' : 'storage/brands/guinness-light.png') }}"
                                         alt="{{ $tenant['name'] }}"
                                         class="h-full w-full object-contain">
                                </div>
                                <span class="min-w-0">
                                    <span class="block text-xs sm:text-sm font-bold text-white group-hover:text-white truncate">{{ $tenant['name'] }}</span>
                                </span>
                            </span>
                        </label>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('merchandiser_tenant')" class="mt-1" />
            </fieldset>

            <!-- Password & Confirmation Row (2 Columns on SM+) -->
            <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-2">
                <div>
                    <x-input-label for="password" :value="__('Password')" class="text-xs font-semibold" />
                    <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="At least 9 characters" class="w-full text-xs sm:text-sm" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-xs font-semibold" />
                    <x-text-input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Repeat password" class="w-full text-xs sm:text-sm" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>
            </div>

            <x-primary-button class="w-full justify-center py-3 text-xs sm:text-sm font-bold mt-2">
                Submit Registration
            </x-primary-button>
        </form>

        <p class="text-xs text-brand-white/60">
            Already registered? <a href="{{ route('merchandisers.login') }}" class="text-amber-500 hover:text-amber-400 font-semibold underline">Login here</a>.
        </p>

        <div class="pt-4 border-t border-brand-white/10 mt-4">
            <a href="{{ route('merchandisers.login') }}" class="text-xs text-brand-white/60 hover:text-brand-white underline">
                ← Back to Sign In
            </a>
        </div>
    </div>
</x-guest-layout>
