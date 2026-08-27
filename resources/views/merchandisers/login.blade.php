<x-guest-layout>
    @php
        $merchTenant = \App\Support\MerchandiserTenant::theme($activeTenant);
    @endphp
    <div class="space-y-6" x-data="{
        activeRole: @js($activeRole),
        activeTenant: @js($activeTenant),
        roles: @js($portalRoles),
        tenants: @js($merchandiserTenants),
    }">
        <!-- Header -->
        <div class="text-center space-y-2">
            <p class="text-xs font-bold uppercase tracking-[0.3em] text-brand-ash">CMIH Africa Merchandiser Portal</p>
            <h2 class="text-3xl font-display text-brand-white">Unified Portal Access</h2>
            <p class="text-xs text-brand-white/70">Select your portal role tab and brand workspace to sign in or register.</p>
        </div>

        <!-- 4 Role Tabs -->
        <div class="grid grid-cols-2 gap-2 sm:grid-cols-4 rounded-xl border border-brand-white/15 bg-brand-black/60 p-1.5">
            @foreach($portalRoles as $roleKey => $roleDef)
                <button type="button"
                        @click="activeRole = '{{ $roleKey }}'"
                        :class="activeRole === '{{ $roleKey }}' ? 'bg-brand-red text-white shadow-lg' : 'text-brand-white/60 hover:text-brand-white hover:bg-white/5'"
                        class="rounded-lg py-2.5 px-2 text-center text-xs font-bold transition duration-200">
                    <span class="block text-sm mb-0.5">{{ $roleDef['icon'] }}</span>
                    <span>{{ $roleDef['short_label'] }}</span>
                </button>
            @endforeach
        </div>

        <!-- Brand Selector (Unilever vs GGBL) with Real Logos -->
        <div class="space-y-2">
            <label class="block text-xs font-bold uppercase tracking-wider text-brand-ash">Select Brand Workspace</label>
            <div class="grid grid-cols-2 gap-3">
                @foreach($merchandiserTenants as $tenantKey => $tenantDef)
                    <button type="button"
                            @click="activeTenant = '{{ $tenantKey }}'"
                            :class="activeTenant === '{{ $tenantKey }}' ? 'border-amber-400 bg-amber-400/10 ring-2 ring-amber-400/30' : 'border-brand-white/15 bg-brand-black/40 hover:border-brand-white/30'"
                            class="flex items-center gap-3 rounded-xl border p-3 text-left transition duration-200">
                        <img src="{{ asset($tenantDef['logo']) }}"
                             alt="{{ $tenantDef['name'] }}"
                             class="h-10 w-10 object-contain shrink-0 rounded-lg p-1 bg-white/10">
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-brand-white truncate">{{ $tenantDef['name'] }}</p>
                            <p class="text-[10px] text-brand-ash truncate">{{ $tenantDef['code'] === 'unilever' ? 'Blue & White' : 'Black & Gold' }}</p>
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Form -->
        <form method="POST" action="{{ route('merchandisers.login') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="portal_role" :value="activeRole">
            <input type="hidden" name="merchandiser_tenant" :value="activeTenant">

            <div>
                <x-input-label for="email" :value="__('Email Address')" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="name@company.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" />
                <div class="relative w-full">
                    <x-text-input id="password" type="password" name="password" required placeholder="Enter password" class="pr-10" />
                    <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-brand-white/50 hover:text-brand-white transition-colors">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between text-xs text-brand-white/70">
                <label for="remember_me" class="inline-flex items-center gap-2">
                    <input id="remember_me" type="checkbox" class="rounded border-brand-white/20 bg-brand-black/40 text-brand-red focus:ring-brand-red" name="remember">
                    Remember me
                </label>
                <a href="{{ route('merchandisers.password.request') }}" class="font-semibold text-amber-400 underline decoration-amber-400/40 hover:text-amber-300">
                    Forgot password?
                </a>
            </div>

            <x-primary-button class="w-full justify-center py-3 text-sm font-bold">
                <span x-text="activeRole === 'admin' ? 'Log In to Admin Hub' : 'Log In to ' + (roles[activeRole]?.label || 'Portal')"></span>
            </x-primary-button>
        </form>



        <!-- Registration link (Allowed for Field, Supervisor, Client) -->
        <div class="rounded-xl border border-brand-white/10 bg-white/5 p-4 text-center text-xs text-brand-white/70"
             x-show="activeRole !== 'admin'">
            Need a new account?
            <a :href="'{{ route('merchandisers.register') }}?role=' + activeRole + '&tenant=' + activeTenant"
               class="font-bold text-amber-400 hover:text-amber-300 underline ml-1">
                Register as <span x-text="roles[activeRole]?.label"></span>
            </a>
            <p class="mt-1 text-[10px] text-brand-ash">Registrations require approval from the Brands Team Admin.</p>
        </div>

        <div class="rounded-xl border border-brand-white/10 bg-white/5 p-4 text-center text-xs text-brand-white/70"
             x-show="activeRole === 'admin'">
            <p class="font-semibold text-brand-white">Admin Hub Sign-in Only</p>
            <p class="mt-1 text-[10px] text-brand-ash">Brands Team members and Super Admins can log in directly with their existing staff credentials.</p>
        </div>

        <div class="pt-2 text-center">
            <a href="{{ route('merchandisers.portal') }}" class="text-xs text-brand-white/60 hover:text-brand-white underline">
                ← Back to Portal Home
            </a>
        </div>
    </div>
</x-guest-layout>
