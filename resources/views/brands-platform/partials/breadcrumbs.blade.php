@php
    $items = $breadcrumbs ?? [];
    
    if (empty($items)) {
        $items[] = ['label' => 'Home', 'url' => url('/')];
        $items[] = ['label' => 'Brands', 'url' => route('brands-platform.index')];
        
        $currentRoute = request()->route() ? request()->route()->getName() : '';
        $brandParam = request()->route('brand');
        $brandObj = null;
        if ($brandParam) {
            if ($brandParam instanceof \App\Models\Brand) {
                $brandObj = $brandParam;
            } else {
                $brandObj = \App\Models\Brand::where('slug', $brandParam)->orWhere('id', $brandParam)->first();
            }
        }
        
        if ($brandObj) {
            $bKey = $brandObj->slug ?: $brandObj->id;
            $items[] = ['label' => $brandObj->display_name ?: $brandObj->name, 'url' => route('brands-platform.show', $bKey)];
        }
        
        if ($currentRoute === 'brands-platform.publications') {
            $items[] = ['label' => 'Publications'];
        } elseif ($currentRoute === 'brands-platform.activation') {
            $items[] = ['label' => 'Activation'];
        } elseif ($currentRoute === 'brands-platform.consumer') {
            $items[] = ['label' => 'Consumer Experience'];
        } elseif ($currentRoute === 'brands-platform.support-login') {
            $items[] = ['label' => 'Staff Login'];
        } elseif ($currentRoute === 'brands-platform.support') {
            $items[] = ['label' => 'Retail Support Staff'];
        } elseif ($currentRoute === 'brands-platform.retail') {
            $items[] = ['label' => 'Retail Attendant & Scanner'];
        } elseif ($currentRoute === 'brands-platform.agency-login') {
            $items[] = ['label' => 'Agency Login'];
        } elseif ($currentRoute === 'brands-platform.agency') {
            $items[] = ['label' => 'Agency Portal'];
        } elseif ($currentRoute === 'brands-platform.admin') {
            $items[] = ['label' => 'Admin Console'];
        } elseif ($currentRoute === 'brands-platform.gallery') {
            $items[] = ['label' => 'Field Gallery'];
        } elseif ($currentRoute === 'brands-platform.notifications') {
            $items[] = ['label' => 'Notifications'];
        } elseif ($currentRoute === 'brands-platform.client-report') {
            $items[] = ['label' => 'Shared Client Report'];
        }
    }
@endphp

<nav class="cmih-breadcrumbs" aria-label="Breadcrumbs">
    <div class="breadcrumbs-container">
        @foreach($items as $index => $crumb)
            @if(!$loop->first)
                <span class="crumb-separator" aria-hidden="true">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.16 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                    </svg>
                </span>
            @endif

            @if(isset($crumb['url']) && !$loop->last)
                <a href="{{ $crumb['url'] }}" class="crumb-link">
                    @if($loop->first)
                        <svg class="crumb-home-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                        </svg>
                    @endif
                    <span>{{ $crumb['label'] }}</span>
                </a>
            @else
                <span class="crumb-current" aria-current="page">{{ $crumb['label'] }}</span>
            @endif
        @endforeach
    </div>
</nav>

<style>
.cmih-breadcrumbs {
    padding: 12px 5vw;
    background: rgba(10, 8, 9, 0.85);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    position: relative;
    z-index: 70;
}
.breadcrumbs-container {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
    font-size: 11px;
    font-weight: 700;
    max-width: 1500px;
    margin: 0 auto;
}
.crumb-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: rgba(255, 255, 255, 0.65);
    text-decoration: none;
    padding: 3px 8px;
    border-radius: 6px;
    transition: background 0.18s ease, color 0.18s ease;
}
.crumb-link:hover {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.08);
}
.crumb-home-icon {
    width: 14px;
    height: 14px;
}
.crumb-separator {
    display: inline-flex;
    align-items: center;
    color: rgba(255, 255, 255, 0.3);
}
.crumb-separator svg {
    width: 14px;
    height: 14px;
}
.crumb-current {
    color: #ff1020;
    padding: 3px 8px;
    background: rgba(255, 16, 32, 0.1);
    border-radius: 6px;
    font-weight: 800;
    letter-spacing: 0.02em;
}
</style>
