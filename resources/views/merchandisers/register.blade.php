<x-guest-layout>
    <div class="space-y-6">
        <div class="space-y-2">
            <p class="text-xs uppercase tracking-[0.3em] text-brand-ash">Merchandiser Portal Access</p>
            <h2 class="text-3xl font-display text-brand-white">Register Account</h2>
            <p class="text-sm text-brand-white/70">Create your merchandiser portal account. Select your role and brand workspace. All accounts require admin approval before activation.</p>
        </div>

        <form method="POST" action="{{ route('merchandisers.register') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Unified Role Selector (Field Agent, Supervisor, Client / TM) -->
            <fieldset class="space-y-2">
                <legend class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-white/75">Select Portal Role</legend>
                <p class="text-xs leading-5 text-brand-white/55">Choose the account role you are applying for in the portal.</p>
                <div class="grid gap-2.5 sm:grid-cols-3">
                    @foreach($portalRoles as $roleKey => $roleDef)
                        <label class="group cursor-pointer rounded-xl border border-brand-white/10 bg-brand-white/5 p-3 transition hover:border-brand-red/45 hover:bg-brand-red/5">
                            <input type="radio" name="portal_role" value="{{ $roleKey }}" class="sr-only peer" @checked(old('portal_role', $activeRole) === $roleKey) required>
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-base">{{ $roleDef['icon'] }}</span>
                                    <span class="text-xs font-bold text-brand-white group-hover:text-white peer-checked:text-brand-red">{{ $roleDef['label'] }}</span>
                                </div>
                                <p class="text-[10px] text-brand-ash leading-snug">{{ $roleDef['short_label'] }} registration</p>
                            </div>
                        </label>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('portal_role')" class="mt-1" />
            </fieldset>

            <div>
                <x-input-label for="name" :value="__('Full Name')" />
                <x-text-input id="name" type="text" name="name" :value="old('name')" required placeholder="Enter your full name" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="profile_photo" :value="__('Profile Photo / Headshot Image (Required)')" />
                <div class="mt-1.5 flex items-center gap-3 rounded-xl border border-brand-white/15 bg-brand-white/5 p-3 transition hover:border-brand-red/40">
                    <input id="profile_photo" type="file" name="profile_photo" accept="image/*" required class="w-full text-xs text-brand-white/80 file:mr-3 file:rounded-lg file:border-0 file:bg-brand-red file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-white hover:file:bg-brand-red/80 cursor-pointer">
                </div>
                <p class="text-[10px] text-brand-white/50 mt-1">Upload a clear front-facing headshot photo for your ID card and portal avatar (JPG, PNG, WEBP, max 5MB).</p>
                <x-input-error :messages="$errors->get('profile_photo')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Login Email Address')" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required placeholder="Enter login email" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="contact_email" :value="__('Personal/Contact Email')" />
                <x-text-input id="contact_email" type="email" name="contact_email" :value="old('contact_email')" required placeholder="Enter personal email" />
                <x-input-error :messages="$errors->get('contact_email')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="phone" :value="__('Phone Number')" />
                <x-text-input id="phone" type="text" name="phone" :value="old('phone')" required placeholder="e.g. +23354XXXXXXX" />
                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                <x-text-input id="date_of_birth" type="date" name="date_of_birth" :value="old('date_of_birth')" required />
                <p class="text-[10px] text-brand-white/40 mt-0.5">Must be between 18 and 65 years of age.</p>
                <x-input-error :messages="$errors->get('date_of_birth')" class="mt-1" />
            </div>

            <fieldset class="space-y-2">
                <legend class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-white/75">Brand affiliation</legend>
                <p class="text-xs leading-5 text-brand-white/55">Choose the business whose outlets and field instructions you work on.</p>
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach($merchandiserTenants as $tenant)
                        <label class="group cursor-pointer rounded-xl border border-brand-white/10 bg-brand-white/5 p-3.5 transition hover:border-brand-red/45 hover:bg-brand-red/5">
                            <input type="radio" name="merchandiser_tenant" value="{{ $tenant['code'] }}" class="sr-only peer" @checked(old('merchandiser_tenant', 'unilever') === $tenant['code']) required>
                            <span class="flex items-center gap-3">
                                <div class="h-11 w-11 shrink-0 rounded-xl bg-white p-1.5 flex items-center justify-center shadow-md">
                                    <img src="{{ asset($tenant['code'] === 'unilever' ? 'storage/brands/unilever-light.png' : 'storage/brands/guinness-light.png') }}"
                                         alt="{{ $tenant['name'] }}"
                                         class="h-full w-full object-contain">
                                </div>
                                <span>
                                    <span class="block text-sm font-bold text-white group-hover:text-white">{{ $tenant['name'] }}</span>
                                    <span class="mt-0.5 block text-[10px] uppercase tracking-[0.12em] text-brand-ash">Field merchandising team</span>
                                </span>
                            </span>
                        </label>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('merchandiser_tenant')" class="mt-1" />
            </fieldset>

            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="At least 9 chars, e.g. Cmih2026!" />
                <p class="text-[10px] text-brand-ash mt-1">Use more than 8 characters with at least one letter, one number, and one symbol.</p>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Repeat password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>

            <x-primary-button class="w-full justify-center mt-2">
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
