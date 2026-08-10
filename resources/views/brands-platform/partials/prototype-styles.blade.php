@once
    @push('head')
        <style>
            body:has(.brand-page) header.sticky,
            body:has(.brand-page) footer {
                display: none !important;
            }

            .internal-header {
                align-items: center;
                backdrop-filter: blur(16px);
                background: rgba(7, 7, 7, .94);
                border-bottom: 1px solid rgba(255, 255, 255, .08);
                color: #fff;
                display: flex;
                gap: 13px;
                height: 72px;
                padding: 0 24px;
                position: sticky;
                top: 0;
                z-index: 80;
            }

            .internal-header img {
                height: 43px;
                object-fit: contain;
                width: 43px;
            }

            .internal-header strong {
                font-size: 11px;
            }

            .internal-header small {
                color: #a98f95;
                display: block;
                font-size: 8px;
                margin-top: 3px;
                text-transform: uppercase;
            }

            .internal-header .spacer {
                flex: 1;
            }

            .internal-header a {
                background: rgba(255, 255, 255, .07);
                border-radius: 11px;
                color: #fff;
                font-size: 9px;
                font-weight: 900;
                padding: 9px 12px;
                text-transform: uppercase;
            }

            .brand-page {
                background: var(--bbg, #003e46);
                color: white;
                min-height: 100vh;
            }

            .brand-main {
                min-height: calc(100vh - 72px);
                overflow: hidden;
                padding: 70px 6vw 80px;
                position: relative;
            }

            .brand-main:after {
                background: var(--ba, #ff2ba6);
                border-radius: 50%;
                content: "";
                height: 570px;
                opacity: .17;
                position: absolute;
                right: -160px;
                top: -180px;
                width: 570px;
            }

            .brand-logo-main {
                max-height: 78px;
                max-width: 220px;
                object-fit: contain;
                position: relative;
                z-index: 2;
            }

            .brand-copy {
                margin-top: 70px;
                max-width: 820px;
                position: relative;
                z-index: 2;
            }

            .brand-copy .eyebrow {
                color: var(--bs, #18e7ef);
                font-size: 9px;
                font-weight: 950;
                letter-spacing: .16em;
                text-transform: uppercase;
            }

            .brand-copy h1 {
                font-family: Impact, 'Arial Narrow Bold', Arial, sans-serif;
                font-size: clamp(56px, 7vw, 94px);
                letter-spacing: -.04em;
                line-height: .88;
                margin: 13px 0 15px;
                text-transform: uppercase;
            }

            .brand-copy p {
                color: rgba(255, 255, 255, .74);
                font-size: 15px;
                line-height: 1.6;
                max-width: 660px;
            }

            .brand-entry-buttons {
                display: grid;
                gap: 14px;
                grid-template-columns: 1fr 1fr;
                margin-top: 28px;
                max-width: 760px;
            }

            .brand-entry {
                background: rgba(255, 255, 255, .06);
                border: 1px solid rgba(255, 255, 255, .13);
                border-radius: 24px;
                color: #fff;
                min-height: 180px;
                overflow: hidden;
                padding: 22px;
                position: relative;
                text-align: left;
                transition: .22s ease;
            }

            .brand-entry:hover {
                background: rgba(255, 255, 255, .11);
                transform: translateY(-3px);
            }

            .brand-entry .ico {
                background: var(--bs, #18e7ef);
                border-radius: 14px;
                color: var(--bink, #082126);
                display: grid;
                font-weight: 950;
                height: 44px;
                margin-bottom: 26px;
                place-items: center;
                width: 44px;
            }

            .brand-entry strong {
                display: block;
                font-family: Impact, 'Arial Narrow Bold', Arial, sans-serif;
                font-size: 25px;
                letter-spacing: .01em;
                text-transform: uppercase;
            }

            .brand-entry small {
                color: rgba(255, 255, 255, .65);
                display: block;
                font-size: 10px;
                line-height: 1.45;
                margin-top: 7px;
            }

            .activation-roles {
                display: grid;
                gap: 14px;
                grid-template-columns: repeat(3, 1fr);
                padding: 28px 6vw 70px;
                position: relative;
                z-index: 2;
            }

            .role-card {
                background: #fff;
                border: 1px solid #e8dfe2;
                border-radius: 23px;
                box-shadow: 0 12px 28px rgba(60, 30, 40, .04);
                color: #20191b;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                min-height: 235px;
                padding: 20px;
            }

            .role-card .icon {
                background: var(--bsoft, #e9fbfb);
                border-radius: 13px;
                color: var(--bp, #00656c);
                display: grid;
                font-weight: 950;
                height: 42px;
                place-items: center;
                width: 42px;
            }

            .role-card h3 {
                font-family: Impact, 'Arial Narrow Bold', Arial, sans-serif;
                font-size: 25px;
                margin: 28px 0 8px;
                text-transform: uppercase;
            }

            .role-card p {
                color: #78676d;
                font-size: 10px;
                line-height: 1.5;
            }

            .role-card a,
            .role-card button {
                background: linear-gradient(135deg, var(--bs, #18e7ef), var(--bp, #00656c));
                border-radius: 14px;
                color: var(--bink, #082126);
                display: block;
                font-size: 10px;
                font-weight: 950;
                margin-top: 18px;
                padding: 13px 15px;
                text-align: center;
                text-transform: uppercase;
            }

            .brand-consumer-card {
                background: rgba(255, 255, 255, .07);
                border: 1px solid rgba(255, 255, 255, .14);
                border-radius: 26px;
                margin: 0 6vw 80px;
                padding: 25px;
                position: relative;
                z-index: 2;
            }

            .brand-consumer-card input,
            .brand-consumer-card select,
            .brand-consumer-card textarea,
            .brands-role-dashboard input,
            .brands-role-dashboard select,
            .brands-role-dashboard textarea {
                background: rgba(0, 0, 0, .46) !important;
                border: 1px solid rgba(255, 255, 255, .12) !important;
                border-radius: 13px !important;
                color: #fff !important;
            }

            .brands-role-dashboard {
                background:
                    radial-gradient(circle at 84% 12%, color-mix(in srgb, var(--ba, #ff2ba6) 24%, transparent), transparent 26%),
                    radial-gradient(circle at 7% 92%, color-mix(in srgb, var(--bp, #00656c) 34%, transparent), transparent 30%),
                    linear-gradient(145deg, #050505, var(--bbg, #170004) 62%, #050505);
                color: #fff;
                min-height: 100vh;
            }

            .brands-role-dashboard .rounded-lg,
            .brands-role-dashboard .rounded-md {
                border-color: rgba(255, 255, 255, .12) !important;
            }

            .brands-role-dashboard .bg-brand-white\/\[0\.045\],
            .brands-role-dashboard .bg-brand-white\/\[0\.04\],
            .brands-role-dashboard .bg-brand-white\/\[0\.035\],
            .brands-role-dashboard .bg-brand-black\/35,
            .brands-role-dashboard .bg-brand-black\/50 {
                background: rgba(255, 255, 255, .065) !important;
                backdrop-filter: blur(16px);
            }

            .brands-role-dashboard .bg-brand-red {
                background: linear-gradient(135deg, var(--bs, #18e7ef), var(--bp, #00656c)) !important;
                color: var(--bink, #082126) !important;
            }

            .brands-role-dashboard h1,
            .brands-role-dashboard h2,
            .brands-role-dashboard h3,
            .brands-role-dashboard p,
            .brands-role-dashboard td,
            .brands-role-dashboard th,
            .brands-role-dashboard span,
            .brands-role-dashboard label {
                letter-spacing: 0;
            }

            @media(max-width:900px) {
                .brand-entry-buttons,
                .activation-roles {
                    grid-template-columns: 1fr;
                }

                .brand-main {
                    padding-top: 45px;
                }
            }
        </style>
    @endpush
@endonce
