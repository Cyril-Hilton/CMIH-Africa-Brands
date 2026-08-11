@once
    @push('head')
        <style>
:root{
  --cmih-red:#ff1020;--cmih-red2:#9d000d;--cmih-deep:#210004;--cmih-black:#070707;
  --paper:#fff8fa;--ink:#171115;--muted:#775e65;--line:#ead6dc;--ok:#0a9d70;--warn:#d89400;--bad:#cc3341;
  --gold:#d4aa45;--silver:#aeb4bc;--shadow:0 24px 70px rgba(55,0,8,.15);
  --bp:#00656c;--bs:#18e7ef;--ba:#ff2ba6;--bbg:#003e46;--bsoft:#e9fbfb;--bink:#082126;
  --logo: none;
}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{margin:0;background:#0b0809;color:var(--ink);font-family:Inter,Arial,Helvetica,sans-serif}
button,input,select,textarea{font:inherit}
button{cursor:pointer}
.hidden{display:none!important}
.view{display:none;min-height:100vh}
.view.active{display:block;animation:fade .24s ease}
@keyframes fade{from{opacity:.2;transform:translateY(5px)}to{opacity:1;transform:none}}
.btn{border:0;border-radius:14px;padding:13px 17px;font-size:11px;font-weight:900;transition:.2s ease}
.btn:hover{transform:translateY(-2px)}
.btn.red{background:linear-gradient(135deg,var(--cmih-red),#96000c);color:#fff;box-shadow:0 12px 28px rgba(255,16,32,.2)}
.btn.dark{background:#111;color:#fff}
.btn.light{background:#fff;color:#20171a;border:1px solid var(--line)}
.btn.brand{background:var(--bs);color:var(--bink)}
.btn.gold{background:linear-gradient(135deg,#eed376,#a67818);color:#1c1405}
.btn.silver{background:linear-gradient(135deg,#f1f4f7,#8e98a4);color:#171a1e}
.eyebrow{font-size:9px;letter-spacing:.16em;text-transform:uppercase;font-weight:950;color:var(--cmih-red)}
.toast{position:fixed;right:22px;top:22px;z-index:300;background:#160b0e;color:white;padding:13px 16px;border-radius:14px;font-size:11px;font-weight:850;box-shadow:0 18px 50px rgba(0,0,0,.28);transform:translateY(-100px);opacity:0;transition:.28s}
.toast.show{transform:none;opacity:1}

/* ===== PUBLIC HOME ===== */
.home{
  min-height:100vh;color:#fff;overflow:hidden;
  background:
    radial-gradient(circle at 85% 10%,rgba(255,16,32,.24),transparent 23%),
    radial-gradient(circle at 10% 85%,rgba(153,0,13,.20),transparent 27%),
    linear-gradient(145deg,#050505,#170004 58%,#050505);
}
.home::before{content:"";position:fixed;inset:0;pointer-events:none;background:linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(0deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:44px 44px;mask-image:linear-gradient(180deg,#000,transparent 80%)}
.home-top{position:relative;z-index:2;display:flex;align-items:center;justify-content:space-between;padding:23px 5vw}
.public-lockup{display:flex;align-items:center;gap:12px}
.public-lockup img{width:48px;height:48px;object-fit:contain}
.public-lockup strong{display:block;font-size:13px;letter-spacing:.05em}
.public-lockup small{display:block;font-size:8px;letter-spacing:.13em;color:#e64a57;margin-top:3px}
.home-admin{border:1px solid rgba(255,255,255,.14);background:rgba(255,255,255,.055);color:#fff;border-radius:999px;padding:10px 15px;font-size:9px;font-weight:900;letter-spacing:.08em;text-transform:uppercase}
.home-hero{position:relative;z-index:2;text-align:center;padding:90px 20px 24px;max-width:1050px;margin:auto}
.home-hero h1{font-family:Impact,'Arial Narrow Bold',Arial,sans-serif;font-weight:500;letter-spacing:.01em;font-size:clamp(58px,8.5vw,120px);line-height:.82;text-transform:uppercase;margin:13px 0}
.home-hero h1 span{background:linear-gradient(90deg,#ff192b,#b80011,#ff4a57);-webkit-background-clip:text;background-clip:text;color:transparent}
.home-hero p{max-width:680px;margin:22px auto 0;color:#ceb8be;font-size:16px;line-height:1.6}
.home-cta{display:flex;justify-content:center;gap:10px;flex-wrap:wrap;margin-top:25px}
.home-cta .btn{padding:14px 20px;border-radius:999px}
.merch-bridge{position:relative;z-index:2;display:flex;justify-content:center;padding:10px 20px 44px}
.merch-btn{min-width:240px;border-radius:999px;border:1px solid rgba(255,255,255,.16);background:rgba(255,255,255,.07);backdrop-filter:blur(16px);color:#fff;padding:13px 18px;font-size:10px;font-weight:900;letter-spacing:.11em;text-transform:uppercase}
.merch-btn:hover{background:#fff;color:#171115}
.brand-zone{position:relative;z-index:2;padding:18px 5vw 80px;max-width:1500px;margin:auto}
.brand-zone-head{display:flex;align-items:end;justify-content:space-between;gap:20px;margin-bottom:18px}
.brand-zone h2{font-family:Impact,'Arial Narrow Bold',Arial,sans-serif;font-weight:500;font-size:clamp(37px,5vw,58px);margin:7px 0 0;letter-spacing:.02em}
.brand-zone-head p{max-width:420px;color:#bba4aa;font-size:11px;line-height:1.5;margin:0}
.liquid-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:15px}
.liquid-tile{
  position:relative;min-height:205px;border-radius:26px;padding:17px;overflow:hidden;
  background:linear-gradient(145deg,rgba(255,255,255,.08),rgba(255,255,255,.025));
  border:1px solid rgba(255,255,255,.10);backdrop-filter:blur(24px);
  box-shadow:inset 0 1px 0 rgba(255,255,255,.08),0 18px 45px rgba(0,0,0,.14);
  transition:.28s ease;color:#fff;
}
.liquid-tile:before{content:"";position:absolute;inset:0;background:var(--tile-bg);opacity:0;transition:.28s;z-index:0}
.liquid-tile:after{content:"";position:absolute;width:160px;height:160px;border-radius:50%;right:-80px;top:-85px;background:rgba(255,255,255,.10);filter:blur(2px);z-index:0}
.liquid-tile>*{position:relative;z-index:1}
.liquid-tile:hover{transform:translateY(-5px);border-color:rgba(255,255,255,.2);box-shadow:0 28px 65px rgba(0,0,0,.22)}
.liquid-tile:hover:before{opacity:1}
.tile-category{font-size:8px;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.55);font-weight:900}
.tile-logo-wrap{height:118px;display:flex;align-items:center;justify-content:center}
.tile-logo{max-width:84%;max-height:96px;object-fit:contain;filter:grayscale(1) saturate(0) brightness(1.15) opacity(.72);transition:.28s}
.liquid-tile:hover .tile-logo{filter:none;transform:scale(1.05)}
.tile-bottom{display:flex;justify-content:space-between;gap:12px;align-items:end}
.tile-bottom strong{font-size:13px}.tile-bottom small{display:block;color:rgba(255,255,255,.58);font-size:8px;margin-top:4px}
.tile-open{width:37px;height:37px;border-radius:50%;display:grid;place-items:center;border:1px solid rgba(255,255,255,.13);background:rgba(255,255,255,.05)}
.rexona{--tile-bg:linear-gradient(145deg,#003a42,#009c9f)}.guinness{--tile-bg:linear-gradient(145deg,#080807,#211d18)}.gino{--tile-bg:linear-gradient(145deg,#ce2b20,#f5b82d)}.omo{--tile-bg:linear-gradient(145deg,#0e4c99,#18aee0)}.lush{--tile-bg:linear-gradient(145deg,#9c145b,#ed3e98)}.dove{--tile-bg:linear-gradient(145deg,#07519b,#e5c263)}.ovaltine{--tile-bg:linear-gradient(145deg,#143a88,#f1a51c)}.mtn{--tile-bg:linear-gradient(145deg,#ffe000,#ffbe00)}
@media(max-width:1050px){.liquid-grid{grid-template-columns:repeat(2,1fr)}}@media(max-width:620px){.liquid-grid{grid-template-columns:1fr}.brand-zone-head{display:block}.brand-zone-head p{margin-top:12px}.home-hero{padding-top:55px}}

/* ===== INTERNAL HEADER ===== */
.internal-header{height:72px;position:sticky;top:0;z-index:80;background:rgba(7,7,7,.94);backdrop-filter:blur(16px);color:white;border-bottom:1px solid rgba(255,255,255,.08);display:flex;align-items:center;padding:0 24px;gap:13px}
.internal-header img{width:43px;height:43px;object-fit:contain}.internal-header strong{font-size:11px}.internal-header small{display:block;color:#a98f95;font-size:8px;margin-top:3px}
.internal-header .spacer{flex:1}.internal-header button{border:0;background:rgba(255,255,255,.07);color:#fff;border-radius:11px;padding:9px 12px;font-size:9px;font-weight:900}

/* ===== BRAND LANDING ===== */
.brand-page{background:var(--bbg);color:white}
.brand-main{padding:70px 6vw 80px;min-height:calc(100vh - 72px);position:relative;overflow:hidden}
.brand-main:after{content:"";position:absolute;width:570px;height:570px;border-radius:50%;right:-160px;top:-180px;background:var(--ba);opacity:.17}
.brand-logo-main{max-width:220px;max-height:78px;object-fit:contain;position:relative;z-index:2}
.brand-copy{position:relative;z-index:2;max-width:820px;margin-top:70px}
.brand-copy .eyebrow{color:var(--bs)}
.brand-copy h1{font-family:var(--display,Arial);font-size:clamp(56px,7vw,94px);line-height:.88;text-transform:uppercase;letter-spacing:-.04em;margin:13px 0 15px}
.brand-copy p{max-width:660px;color:rgba(255,255,255,.74);font-size:15px;line-height:1.6}
.brand-entry-buttons{display:grid;grid-template-columns:1fr 1fr;gap:14px;max-width:760px;margin-top:28px}
.brand-entry{
  border:1px solid rgba(255,255,255,.13);border-radius:24px;padding:22px;text-align:left;min-height:180px;
  background:rgba(255,255,255,.06);color:#fff;backdrop-filter:blur(14px);position:relative;overflow:hidden;
}
.brand-entry:hover{background:rgba(255,255,255,.11);transform:translateY(-3px)}
.brand-entry .ico{width:44px;height:44px;border-radius:14px;background:var(--bs);color:var(--bink);display:grid;place-items:center;font-weight:950;margin-bottom:26px}
.brand-entry strong{font-family:var(--display,Arial);font-size:25px}.brand-entry small{display:block;color:rgba(255,255,255,.65);font-size:10px;line-height:1.45;margin-top:7px}
@media(max-width:720px){.brand-entry-buttons{grid-template-columns:1fr}}

/* ===== PUBLICATION ===== */
.publications{background:#fbf6f2;min-height:100vh;color:#241d19}
.pub-hero{padding:58px 6vw 44px;background:linear-gradient(145deg,var(--bbg),color-mix(in srgb,var(--bbg) 78%,#000));color:#fff}
.pub-hero img{max-width:150px;max-height:60px;object-fit:contain}.pub-hero h1{font-family:var(--display,Arial);font-size:clamp(45px,6vw,72px);margin:35px 0 8px}.pub-hero p{color:rgba(255,255,255,.72);max-width:650px;line-height:1.55}
.pub-grid{padding:34px 6vw 75px;display:grid;grid-template-columns:repeat(3,1fr);gap:15px}
.pub-card{border-radius:22px;background:#fff;border:1px solid #eadfd9;overflow:hidden;box-shadow:0 10px 26px rgba(80,52,30,.05)}
.pub-image{height:180px;background:linear-gradient(135deg,var(--bp),var(--ba));position:relative}
.pub-image:after{content:"";position:absolute;width:130px;height:130px;border-radius:50%;right:-35px;top:-30px;background:rgba(255,255,255,.16)}
.pub-body{padding:18px}.pub-body .date{font-size:8px;letter-spacing:.1em;text-transform:uppercase;color:#9a7f74;font-weight:900}.pub-body h3{font-family:var(--display,Arial);font-size:20px;line-height:1.08;margin:10px 0}.pub-body p{font-size:10px;color:#7f6d66;line-height:1.5}
@media(max-width:900px){.pub-grid{grid-template-columns:1fr}}

/* ===== ACTIVATION PAGE ===== */
.activation-page{background:#f6f2f3;min-height:100vh}
.activation-banner{min-height:400px;padding:55px 6vw 44px;color:#fff;position:relative;overflow:hidden;background:
 radial-gradient(circle at 82% 18%,color-mix(in srgb,var(--ba) 42%,transparent),transparent 23%),
 linear-gradient(145deg,var(--bbg),color-mix(in srgb,var(--bbg) 70%,#000))}
.activation-banner img{max-width:160px;max-height:58px;object-fit:contain}
.activation-badge{position:absolute;right:6vw;top:54px;border-radius:999px;padding:10px 14px;font-size:9px;font-weight:950;letter-spacing:.09em;text-transform:uppercase;box-shadow:0 10px 25px rgba(0,0,0,.2)}
.activation-badge.gold{background:linear-gradient(135deg,#f2d978,#9e741e);color:#211704}.activation-badge.silver{background:linear-gradient(135deg,#f5f7f9,#959da7);color:#202329}
.activation-banner h1{font-family:var(--display,Arial);font-size:clamp(48px,6.5vw,84px);line-height:.9;text-transform:uppercase;margin:55px 0 12px;max-width:900px}.activation-banner p{max-width:690px;color:rgba(255,255,255,.72);line-height:1.55}
.activation-roles{padding:28px 6vw 70px;display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.role-card{background:#fff;border:1px solid #e8dfe2;border-radius:23px;padding:20px;min-height:235px;display:flex;flex-direction:column;justify-content:space-between;box-shadow:0 12px 28px rgba(60,30,40,.04)}
.role-card .icon{width:42px;height:42px;border-radius:13px;background:var(--bsoft);color:var(--bp);display:grid;place-items:center;font-weight:950}.role-card h3{font-family:var(--display,Arial);font-size:25px;margin:28px 0 8px}.role-card p{font-size:10px;color:#78676d;line-height:1.5}.role-card button{width:100%}
@media(max-width:900px){.activation-roles{grid-template-columns:1fr}.activation-badge{position:static;display:inline-block;margin-top:18px}}

/* ===== AUTH CARDS ===== */
.auth-page{min-height:100vh;background:radial-gradient(circle at 84% 10%,rgba(255,16,32,.14),transparent 24%),linear-gradient(145deg,#0a0809,#1a0b0e);display:grid;place-items:center;padding:25px;color:#fff}
.auth-card{width:min(520px,100%);background:#fff;color:#25191d;border-radius:26px;padding:25px;box-shadow:0 30px 90px rgba(0,0,0,.35)}
.auth-card img{max-width:130px;max-height:54px;object-fit:contain}.auth-card h2{margin:28px 0 8px;font-size:30px}.auth-card p{font-size:11px;line-height:1.5;color:#806b71}.field{margin:13px 0}.field label{display:block;font-size:9px;font-weight:900;color:#745e64;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px}.field input,.field select,.field textarea{width:100%;border:1px solid #e5d5da;border-radius:12px;padding:11px;outline:none;background:#fff;color:#21171a}.field textarea{min-height:86px;resize:vertical}.field input:focus,.field select:focus,.field textarea:focus{border-color:#d7929d;box-shadow:0 0 0 3px rgba(255,16,32,.08)}
.demo-logins{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:10px}.demo-logins button{border:1px solid #e7d7dc;background:#fff7f9;border-radius:11px;padding:10px;font-size:9px;font-weight:900}

/* ===== CONSUMER ===== */
.consumer-page{min-height:100vh;background:var(--bbg);color:#fff;padding:24px}
.consumer-wrap{max-width:1180px;margin:auto;display:grid;grid-template-columns:1fr 430px;gap:44px;align-items:center;min-height:calc(100vh - 48px)}
.consumer-intro h1{font-family:var(--display,Arial);font-size:clamp(48px,6vw,86px);line-height:.9;text-transform:uppercase;margin:12px 0}.consumer-intro p{color:rgba(255,255,255,.72);line-height:1.55;max-width:610px}.journey-tags{display:flex;gap:7px;flex-wrap:wrap;margin-top:22px}.journey-tags span{border:1px solid rgba(255,255,255,.15);padding:7px 9px;border-radius:999px;font-size:8px;text-transform:uppercase;letter-spacing:.06em}
.phone{height:min(820px,92vh);min-height:650px;background:#fff;color:var(--bink);border:9px solid #061b1e;border-radius:38px;overflow:hidden;position:relative;box-shadow:0 30px 80px rgba(0,0,0,.32)}
.phone-screen{position:absolute;inset:0;display:none;overflow:auto;padding-bottom:85px;background:#fff}.phone-screen.active{display:block}.phone-hero{min-height:100%;background:radial-gradient(circle at 82% 10%,color-mix(in srgb,var(--ba) 38%,transparent),transparent 28%),linear-gradient(155deg,var(--bbg),color-mix(in srgb,var(--bbg) 72%,#000));color:#fff;padding:42px 24px}.phone-hero img{max-width:120px;max-height:48px;object-fit:contain}.phone-hero h2{font-family:var(--display,Arial);font-size:48px;line-height:.9;text-transform:uppercase;margin:58px 0 14px}.phone-hero p{color:rgba(255,255,255,.78);font-size:14px;line-height:1.5}.phone-page{padding:36px 22px 100px}.phone-top{display:flex;align-items:center;gap:10px;margin-bottom:18px}.phone-top button{width:36px;height:36px;border-radius:10px;border:1px solid #dfe9ea;background:#fff}.phone-top strong{font-size:11px;flex:1}.step{font-size:9px;color:#819095}.progress{height:6px;background:#e9f0f1;border-radius:999px;margin-bottom:22px;overflow:hidden}.progress span{display:block;height:100%;background:linear-gradient(90deg,var(--bs),var(--ba));border-radius:999px}.phone-page h3{font-family:var(--display,Arial);font-size:31px;margin:0 0 8px}.phone-page>p{font-size:11px;color:#78898c;line-height:1.45}.phone-field{margin:13px 0}.phone-field label{display:block;font-size:10px;font-weight:900;margin-bottom:6px;color:#36535a}.phone-field input,.phone-field select{width:100%;height:47px;border:1px solid #dbe7e8;border-radius:12px;padding:0 11px;outline:none}.phone-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px}.phone-bottom{position:absolute;left:0;right:0;bottom:0;padding:13px 22px 16px;background:rgba(255,255,255,.97);border-top:1px solid #e3ebec}.phone-bottom button{width:100%}.consent{display:flex;gap:9px;font-size:10px;line-height:1.4;margin:13px 0;color:#53696d}.consent input{width:18px;height:18px;accent-color:var(--bp)}.otp{display:grid;grid-template-columns:repeat(6,1fr);gap:6px;margin:22px 0}.otp input{width:100%;height:52px;border:1px solid #dbe7e8;border-radius:10px;text-align:center;font-weight:900;font-size:18px}.success-center{text-align:center;padding-top:45px}.success-circle{width:82px;height:82px;border-radius:50%;display:grid;place-items:center;margin:auto;background:var(--bs);color:var(--bink);font-size:36px;font-weight:950}.reward{margin-top:20px;background:var(--bsoft);border-radius:18px;padding:16px;text-align:left;border:1px dashed var(--bp)}.reward small{display:block;color:#678086;font-size:8px;text-transform:uppercase}.reward strong{display:block;margin-top:6px;font-size:20px}
@media(max-width:900px){.consumer-wrap{grid-template-columns:1fr}.consumer-intro{display:none}.phone{margin:auto;width:100%;max-width:430px}}

/* ===== ROLE DASHBOARDS ===== */
.workspace{min-height:100vh;background:#f2eeee;color:#20191b}
.work-shell{display:grid;grid-template-columns:230px 1fr;min-height:100vh}.work-side{background:linear-gradient(180deg,#080808,#210006);color:#fff;padding:20px 15px}.work-brand{display:flex;gap:10px;align-items:center}.work-brand img{width:45px;height:45px;object-fit:contain}.work-brand strong{font-size:11px}.work-brand small{display:block;color:#ad9299;font-size:8px;margin-top:3px}.side-label{font-size:8px;text-transform:uppercase;letter-spacing:.12em;color:#886e75;margin:25px 8px 8px;font-weight:900}.side-btn{width:100%;border:0;background:transparent;color:#bca8ad;text-align:left;padding:11px;border-radius:10px;font-size:10px;font-weight:850;margin-bottom:3px}.side-btn.active,.side-btn:hover{background:#2a090d;color:#fff;box-shadow:inset 3px 0 0 var(--cmih-red)}.work-main{padding:25px;min-width:0}.work-top{display:flex;justify-content:space-between;gap:20px;align-items:end;margin-bottom:18px}.work-top h1{font-size:35px;margin:4px 0 0}.chip{display:inline-flex;border-radius:999px;padding:7px 10px;font-size:8px;font-weight:900;text-transform:uppercase;letter-spacing:.06em}.chip.ok{background:#e6f8f0;color:#0b8d66}.chip.warn{background:#fff2d8;color:#a26b00}.chip.info{background:#e8f0ff;color:#2d63b9}.metrics{display:grid;grid-template-columns:repeat(4,1fr);gap:11px;margin-bottom:14px}.metric{background:#fff;border:1px solid #e4dadd;border-radius:17px;padding:14px}.metric small{display:block;font-size:8px;text-transform:uppercase;letter-spacing:.07em;color:#8a747a}.metric strong{display:block;font-size:27px;margin-top:7px}.metric span{display:block;color:#b10010;font-size:8px;margin-top:5px;font-weight:900}.dash-grid{display:grid;grid-template-columns:1.2fr .8fr;gap:13px}.panel{background:#fff;border:1px solid #e4dadd;border-radius:20px;padding:16px}.panel-head{display:flex;justify-content:space-between;align-items:start;gap:12px;margin-bottom:13px}.panel-head h3{margin:0;font-size:13px}.panel-head small{color:#8b757b;font-size:8px}.leader{width:100%;border-collapse:collapse}.leader th,.leader td{text-align:left;padding:10px 7px;border-bottom:1px solid #efe6e8;font-size:9px}.leader th{font-size:7px;color:#8e777d;text-transform:uppercase;letter-spacing:.07em}.myrow{background:#fff3f6}.rank-badge{width:27px;height:27px;border-radius:8px;display:grid;place-items:center;background:#f4edef;font-size:9px;font-weight:900}.map{height:170px;border-radius:16px;background:repeating-linear-gradient(0deg,#dfe8e9 0 1px,transparent 1px 25px),repeating-linear-gradient(90deg,#dfe8e9 0 1px,transparent 1px 25px),#edf3f3;position:relative}.map-radius{position:absolute;width:100px;height:100px;border-radius:50%;border:2px dashed #0aa777;background:#0aa77714;left:50%;top:50%;transform:translate(-50%,-50%)}.map-pin{position:absolute;width:16px;height:16px;border-radius:50%;background:var(--cmih-red);left:55%;top:42%;border:3px solid white}.action-row{display:flex;gap:8px;flex-wrap:wrap;margin-top:11px}.feed{display:grid;gap:8px;max-height:240px;overflow:auto}.feed-item{border-left:3px solid var(--cmih-red);background:#fff6f8;border-radius:0 9px 9px 0;padding:9px;font-size:9px;color:#655158}
.scan-zone{height:300px;border-radius:18px;background:radial-gradient(circle at 50% 40%,#19e8ef18,transparent 30%),linear-gradient(#062e34,#031a1e);display:grid;place-items:center;position:relative;overflow:hidden}.scan-frame{width:65%;height:52%;border:2px solid #19e8ef;border-radius:15px;position:relative}.scanline{position:absolute;left:10px;right:10px;height:2px;background:linear-gradient(90deg,transparent,#ff2aa6,#19e8ef,transparent);top:20%;animation:scan 2.4s linear infinite}@keyframes scan{50%{top:80%}}
@media(max-width:960px){.work-shell{display:block}.work-side{display:none}.metrics{grid-template-columns:repeat(2,1fr)}.dash-grid{grid-template-columns:1fr}}

/* ===== AGENCY + ADMIN ===== */
.big-dashboard{min-height:100vh;background:#f3edef;color:#20191b}.big-shell{display:grid;grid-template-columns:245px 1fr;min-height:100vh}.big-side{background:linear-gradient(180deg,#070707,#200006);color:#fff;padding:20px 15px;position:sticky;top:0;height:100vh;overflow:auto}.big-side .logo-lock{display:flex;gap:10px;align-items:center}.big-side .logo-lock img{width:47px;height:47px;object-fit:contain}.big-side .logo-lock strong{font-size:11px}.big-nav-label{font-size:8px;text-transform:uppercase;letter-spacing:.12em;color:#856a71;font-weight:900;margin:25px 8px 8px}.big-nav{width:100%;border:0;background:transparent;color:#b9a4aa;text-align:left;padding:11px;border-radius:10px;font-size:10px;font-weight:850;margin-bottom:3px}.big-nav.active,.big-nav:hover{background:#2b090e;color:white;box-shadow:inset 3px 0 0 var(--cmih-red)}.big-main{padding:24px;min-width:0}.big-top{display:flex;align-items:end;justify-content:space-between;gap:18px;margin-bottom:17px}.big-top h1{margin:4px 0 0;font-size:34px}.filters{display:flex;gap:7px;flex-wrap:wrap}.filter{height:40px;border:1px solid #e1d3d7;background:#fff;border-radius:10px;padding:0 10px;font-size:9px}.stats6{display:grid;grid-template-columns:repeat(6,1fr);gap:9px;margin-bottom:12px}.stat{background:#fff;border:1px solid #e4dadd;border-radius:16px;padding:13px}.stat small{display:block;font-size:7px;text-transform:uppercase;letter-spacing:.07em;color:#8c747b}.stat strong{display:block;font-size:25px;margin-top:7px}.chart{height:245px;display:flex;gap:9px;align-items:end;padding:20px 5px 28px}.bar{flex:1;min-width:28px;background:linear-gradient(180deg,var(--cmih-red),#85000b);border-radius:9px 9px 3px 3px;position:relative}.bar span{position:absolute;bottom:-20px;left:50%;transform:translateX(-50%);font-size:7px;color:#806a70}.bar b{position:absolute;top:-18px;left:50%;transform:translateX(-50%);font-size:7px}.table-wrap{overflow:auto}.data-table{width:100%;border-collapse:collapse;min-width:780px}.data-table th,.data-table td{text-align:left;padding:10px 7px;border-bottom:1px solid #efe5e8;font-size:9px}.data-table th{font-size:7px;text-transform:uppercase;letter-spacing:.07em;color:#8b747a}.admin-view{display:none}.admin-view.active{display:block}.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}.form-grid .wide{grid-column:1/-1}.upload-box{height:120px;border:1px dashed #d7bac2;border-radius:14px;display:grid;place-items:center;text-align:center;background:#fff7f9;position:relative;overflow:hidden}.upload-box input{position:absolute;inset:0;opacity:0;cursor:pointer}.upload-box small{font-size:8px;color:#846d74}.switches{display:grid;grid-template-columns:1fr 1fr;gap:8px}.switch-row{display:flex;align-items:center;justify-content:space-between;border:1px solid #eadde0;border-radius:12px;padding:10px;background:#fff}.switch-row strong{font-size:9px}.switch-row input{accent-color:var(--cmih-red)}.archive-list{display:grid;gap:8px}.archive-row{display:flex;align-items:center;justify-content:space-between;gap:10px;border:1px solid #eadde0;background:#fff;border-radius:12px;padding:11px}.archive-row strong{font-size:10px}.archive-row small{display:block;font-size:8px;color:#897278;margin-top:3px}.log-row td:first-child{font-family:monospace}
@media(max-width:1100px){.stats6{grid-template-columns:repeat(3,1fr)}.big-shell{display:block}.big-side{height:auto;position:static}.big-side .big-nav-label,.big-side .big-nav{display:none}.form-grid{grid-template-columns:1fr}.form-grid .wide{grid-column:auto}}@media(max-width:650px){.stats6{grid-template-columns:repeat(2,1fr)}.big-top{display:block}.filters{margin-top:10px}}

/* ===== MODAL ===== */
.modal{position:fixed;inset:0;background:rgba(0,0,0,.62);z-index:250;display:none;place-items:center;padding:20px}.modal.show{display:grid}.modal-card{width:min(650px,100%);max-height:88vh;overflow:auto;background:#fff;border-radius:23px;padding:22px;position:relative}.modal-card h2{margin:0 0 8px}.modal-card p{font-size:10px;color:#7f6970;line-height:1.5}.modal-close{position:absolute;right:14px;top:14px;width:34px;height:34px;border:0;border-radius:10px;background:#f3e9ec}.modal-actions{display:flex;gap:8px;justify-content:flex-end;margin-top:15px}

/* ==========================================================
   HOME BRAND CAROUSEL — SINGLE CURVED ROW
   Total visual bend is kept subtle (about 15 degrees).
   ========================================================== */
.brand-carousel-wrap{position:relative}
.brand-carousel-viewport{
  overflow:hidden;padding:55px 10px 42px;
}
.liquid-grid{
  display:grid !important;
  grid-template-columns:repeat(5,minmax(0,1fr)) !important;
  gap:16px !important;
  align-items:start;
}
.liquid-tile {
  min-width: 0;
  min-height: 190px;
  transform-origin: 50% 115%;
  transition: transform .32s ease, scale .32s ease, box-shadow .28s ease, border-color .28s ease;
}
.liquid-tile.arc-0 { transform: translateY(45px) rotate(-9deg); scale: 0.78; }
.liquid-tile.arc-1 { transform: translateY(16px) rotate(-4.5deg); scale: 0.93; }
.liquid-tile.arc-2 { transform: translateY(0) rotate(0deg); scale: 1.25; z-index: 2; }
.liquid-tile.arc-3 { transform: translateY(16px) rotate(4.5deg); scale: 0.93; }
.liquid-tile.arc-4 { transform: translateY(45px) rotate(9deg); scale: 0.78; }

.liquid-tile.arc-0:hover { scale: 0.88; z-index: 5; }
.liquid-tile.arc-1:hover { scale: 1.05; z-index: 5; }
.liquid-tile.arc-2:hover { scale: 1.38; z-index: 5; }
.liquid-tile.arc-3:hover { scale: 1.05; z-index: 5; }
.liquid-tile.arc-4:hover { scale: 0.88; z-index: 5; }

.liquid-tile .tile-category{display:none}
.liquid-tile .tile-bottom small{display:none}
.liquid-tile .tile-bottom{align-items:center}
.liquid-tile .tile-logo-wrap{height:126px}
.liquid-tile .tile-logo{max-width:88%;max-height:104px}
.carousel-controls{
  display:flex;align-items:center;justify-content:center;gap:12px;margin-top:-8px
}
.carousel-arrow{
  width:44px;height:44px;border-radius:50%;border:1px solid rgba(255,255,255,.14);
  background:rgba(255,255,255,.065);backdrop-filter:blur(16px);color:#fff;
  font-size:18px;font-weight:900;display:grid;place-items:center;
  box-shadow:inset 0 1px 0 rgba(255,255,255,.08);
  transition:.22s ease;
}
.carousel-arrow:hover{background:#fff;color:#171115;transform:translateY(-2px)}
.carousel-count{
  min-width:78px;text-align:center;color:#9f878d;font-size:9px;
  letter-spacing:.12em;text-transform:uppercase;font-weight:900;
}
@media(max-width:1050px){
  .liquid-grid{grid-template-columns:repeat(3,minmax(0,1fr)) !important}
  .liquid-tile.arc-0{transform:translateY(20px) rotate(-7.5deg); scale: 0.92;}
  .liquid-tile.arc-1{transform:translateY(0) rotate(0deg); scale: 1.12; z-index: 2;}
  .liquid-tile.arc-2{transform:translateY(20px) rotate(7.5deg); scale: 0.92;}
  
  .liquid-tile.arc-0:hover{scale: 1.02; z-index: 5;}
  .liquid-tile.arc-1:hover{scale: 1.22; z-index: 5;}
  .liquid-tile.arc-2:hover{scale: 1.02; z-index: 5;}
}
@media(max-width:620px){
  .liquid-grid{grid-template-columns:1fr !important}
  .liquid-tile{transform:none !important; scale: 1 !important;}
  .liquid-tile:hover{scale: 1.05 !important; z-index: 5;}
  .brand-carousel-viewport{padding-left:16px;padding-right:16px}
}

/* Agency sign-in has no public-home presence; only Activation > Agency. */
.agency-login-note{
  border-left:3px solid var(--cmih-red);background:#fff4f6;border-radius:0 11px 11px 0;
  padding:10px 12px;margin:13px 0;color:#765f66;font-size:9px;line-height:1.45
}

/* ==========================================================
   SUPPORT STAFF — TEAM + INDIVIDUAL PERFORMANCE
   ========================================================== */
.promo-section-title{
  display:flex;justify-content:space-between;align-items:end;gap:16px;margin:20px 0 10px
}
.promo-section-title h2{margin:0;font-size:19px}
.promo-section-title p{margin:0;color:#887278;font-size:9px}
.team-metrics{
  display:grid;grid-template-columns:repeat(4,1fr);gap:11px;margin-bottom:14px
}
.team-metric{
  background:linear-gradient(145deg,#180c0f,#2a070c);color:#fff;
  border:1px solid #3d2026;border-radius:18px;padding:15px;
  box-shadow:0 10px 28px rgba(34,0,5,.08)
}
.team-metric small{display:block;font-size:8px;text-transform:uppercase;letter-spacing:.08em;color:#bda7ad}
.team-metric strong{display:block;font-size:29px;margin-top:7px}
.team-metric span{display:block;color:#ff7a85;font-size:8px;margin-top:5px;font-weight:850}
.promo-chart{
  height:240px;display:flex;align-items:end;gap:10px;padding:20px 8px 33px;
  border-left:1px solid #eee3e6;border-bottom:1px solid #eee3e6
}
.promo-bar{
  flex:1;min-width:34px;border-radius:9px 9px 3px 3px;
  background:linear-gradient(180deg,#ff2537,#8e000c);position:relative;
  transition:.22s ease
}
.promo-bar:hover{transform:translateY(-3px);filter:brightness(1.05)}
.promo-bar.you{background:linear-gradient(180deg,#171115,#5b4349)}
.promo-bar b{position:absolute;top:-18px;left:50%;transform:translateX(-50%);font-size:8px}
.promo-bar span{
  position:absolute;bottom:-23px;left:50%;transform:translateX(-50%);
  font-size:7px;color:#806a70;white-space:nowrap
}
.team-summary{
  display:grid;grid-template-columns:1.25fr .75fr;gap:13px;margin-bottom:13px
}
.personal-metrics{
  display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:14px
}
.personal-metric{background:#fff;border:1px solid #e5dadd;border-radius:16px;padding:13px}
.personal-metric small{display:block;font-size:7px;color:#8b747a;text-transform:uppercase;letter-spacing:.07em}
.personal-metric strong{display:block;font-size:24px;margin-top:7px}
.personal-metric span{display:block;font-size:8px;color:#a7000e;margin-top:5px;font-weight:850}
.activation-context{
  display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:10px
}
.activation-context div{background:#f8f2f4;border-radius:12px;padding:10px}
.activation-context small{display:block;font-size:7px;color:#897278;text-transform:uppercase;letter-spacing:.07em}
.activation-context strong{display:block;font-size:10px;margin-top:5px}
@media(max-width:900px){
  .team-metrics,.personal-metrics{grid-template-columns:repeat(2,1fr)}
  .team-summary{grid-template-columns:1fr}
}
@media(max-width:560px){
  .team-metrics,.personal-metrics,.activation-context{grid-template-columns:1fr}
}

/* ===== FUNCTIONAL PROTOTYPE PATCH ===== */
.focus-ring{animation:focusPulse .7s ease 2}
@keyframes focusPulse{50%{box-shadow:0 0 0 5px rgba(255,16,32,.16)}}
.modal-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.modal-stat{border:1px solid var(--line);border-radius:14px;padding:12px;background:#fff8fa}
.modal-stat small{display:block;color:#856f75;font-size:8px;text-transform:uppercase;letter-spacing:.07em}
.modal-stat strong{display:block;margin-top:5px;font-size:20px}
.inventory-row,.article-row{
  display:flex;justify-content:space-between;align-items:center;gap:12px;
  border-bottom:1px solid #eee3e6;padding:11px 0
}
.inventory-row:last-child,.article-row:last-child{border-bottom:0}
.inventory-row strong,.article-row strong{font-size:10px}
.inventory-row small,.article-row small{display:block;color:#887278;font-size:8px;margin-top:3px}
.qty-controls{display:flex;align-items:center;gap:6px}
.qty-controls button{width:29px;height:29px;border-radius:8px;border:1px solid #e4d7db;background:#fff;font-weight:900}
.qty-controls b{min-width:28px;text-align:center;font-size:10px}
.agency-panel{display:none}.agency-panel.active{display:block}
.agency-brand-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
.agency-brand-card{background:#fff;border:1px solid #e4dadd;border-radius:16px;padding:14px}
.agency-brand-card strong{display:block;font-size:12px}.agency-brand-card small{display:block;color:#8b747a;font-size:8px;margin-top:4px}
.agency-brand-card .big{font-size:25px;margin:16px 0 4px;font-weight:900}
.retailer-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
.retailer-card{background:#fff;border:1px solid #e4dadd;border-radius:16px;padding:14px}
.retailer-card h3{font-size:12px;margin:0 0 5px}.retailer-card p{font-size:8px;color:#876f75;margin:0}
.retailer-card strong{display:block;font-size:24px;margin-top:16px}
.file-preview{display:none;max-width:100%;max-height:90px;object-fit:contain;margin:auto}
.upload-box.has-preview>div{display:none}.upload-box.has-preview .file-preview{display:block}
.filter-result-note{font-size:8px;color:#876f75;margin-top:8px}
.side-btn,.big-nav{transition:background .18s ease,color .18s ease}
@media(max-width:900px){.agency-brand-grid{grid-template-columns:repeat(2,1fr)}.retailer-grid{grid-template-columns:1fr}.modal-grid{grid-template-columns:1fr}}

/* ===== VIEW ROUTING FIX ===== */
.view:not(.active){
  display:none !important;
}
.view.active.auth-page{
  display:grid !important;
}
.view.active.workspace,
.view.active.big-dashboard,
.view.active.home,
.view.active.brand-page,
.view.active.publications,
.view.active.activation-page,
.view.active.consumer-page{
  display:block !important;
}

/* ===== AGENCY — CONSUMER INSIGHTS + REPORTS ===== */
.insight-kpis{
  display:grid;grid-template-columns:repeat(4,1fr);gap:11px;margin-bottom:13px
}
.insight-kpi{
  background:#fff;border:1px solid #e4dadd;border-radius:17px;padding:15px;
  box-shadow:0 8px 22px rgba(55,23,33,.035)
}
.insight-kpi small{
  display:block;font-size:7px;color:#8b747a;text-transform:uppercase;
  letter-spacing:.08em;font-weight:900
}
.insight-kpi strong{display:block;font-size:27px;letter-spacing:-.04em;margin-top:8px}
.insight-kpi p{margin:6px 0 0;font-size:8px;color:#877177;line-height:1.45}
.insight-layout{display:grid;grid-template-columns:1fr 1fr;gap:13px;margin-bottom:13px}
.insight-panel{
  background:#fff;border:1px solid #e4dadd;border-radius:20px;padding:17px;
  box-shadow:0 9px 26px rgba(55,23,33,.035)
}
.insight-panel h3{font-size:13px;margin:0}
.insight-panel .sub{font-size:8px;color:#8b747a;margin-top:4px}
.gender-area{
  display:grid;grid-template-columns:210px 1fr;gap:18px;align-items:center;
  min-height:250px
}
.gender-donut{
  width:176px;height:176px;border-radius:50%;margin:auto;display:grid;place-items:center;
  background:conic-gradient(#ff1020 0 56%,#24191c 56% 99%,#d9cbd0 99% 100%);
  position:relative
}
.gender-donut:after{
  content:"";width:102px;height:102px;border-radius:50%;background:#fff;
  position:absolute
}
.gender-donut-label{position:absolute;z-index:2;text-align:center}
.gender-donut-label strong{display:block;font-size:28px}
.gender-donut-label span{display:block;font-size:7px;color:#8a7479;text-transform:uppercase;letter-spacing:.07em}
.gender-list{display:grid;gap:9px}
.gender-row{display:grid;grid-template-columns:70px 1fr 38px;gap:8px;align-items:center}
.gender-row label{font-size:9px;color:#6f5c62;font-weight:850}
.gender-track{height:9px;background:#f0e7e9;border-radius:999px;overflow:hidden}
.gender-fill{height:100%;border-radius:999px}
.gender-row b{text-align:right;font-size:9px}
.age-list{display:grid;gap:13px;margin-top:20px}
.age-row{display:grid;grid-template-columns:75px 1fr 45px;gap:10px;align-items:center}
.age-row label{font-size:9px;color:#6d585e;font-weight:850}
.age-track{height:20px;background:#f2e9ec;border-radius:8px;overflow:hidden}
.age-fill{
  height:100%;border-radius:8px;background:linear-gradient(90deg,#ff1020,#8e000c)
}
.age-row b{text-align:right;font-size:9px}
.insight-secondary{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:13px}
.insight-secondary .panel{min-height:145px}
.insight-secondary .big-value{font-size:25px;font-weight:950;margin-top:18px}
.insight-secondary p{font-size:8px;line-height:1.45;color:#876f75}
.insight-table-logo{width:60px;height:26px;object-fit:contain}
.insight-note{
  border-left:3px solid var(--cmih-red);background:#fff3f6;border-radius:0 11px 11px 0;
  padding:11px 13px;color:#725b62;font-size:9px;line-height:1.5;margin-bottom:13px
}

/* reports */
.report-toolbar{
  display:flex;justify-content:space-between;gap:14px;align-items:center;
  flex-wrap:wrap;margin-bottom:15px
}
.report-toolbar h2{margin:4px 0 0;font-size:24px}
.reports-grid{
  display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:13px
}
.agency-report-card{
  background:#fff;border:1px solid #e4dadd;border-radius:19px;padding:16px;
  display:flex;flex-direction:column;min-height:238px;
  transition:transform .2s ease,box-shadow .2s ease
}
.agency-report-card:hover{transform:translateY(-3px);box-shadow:0 15px 34px rgba(63,20,32,.08)}
.report-icon{
  width:43px;height:43px;border-radius:13px;display:grid;place-items:center;
  background:linear-gradient(135deg,#fff0f3,#f6e2e7);color:#a3000d;
  font-weight:950;font-size:15px;margin-bottom:13px
}
.agency-report-card h3{font-size:13px;margin:0}
.agency-report-card p{font-size:8.5px;color:#806a70;line-height:1.5;flex:1;margin:8px 0 13px}
.report-meta{
  display:flex;justify-content:space-between;gap:10px;color:#907b80;
  font-size:7.5px;margin-bottom:12px;padding-top:10px;border-top:1px solid #f0e6e9
}
.report-actions{display:flex;gap:7px}
.report-actions .btn{flex:1;padding:9px 10px;font-size:9px}
.report-history-status{font-size:8px;font-weight:900}
.report-builder-options{
  display:grid;grid-template-columns:1fr 1fr;gap:10px
}
.report-checks{
  display:grid;grid-template-columns:1fr 1fr;gap:8px;margin:12px 0
}
.report-check{
  border:1px solid #eadde0;border-radius:11px;padding:10px;background:#fff8fa;
  display:flex;gap:8px;align-items:center;font-size:9px;font-weight:800
}
.report-check input{accent-color:#ff1020}
.report-preview{
  background:#f8f2f4;border:1px solid #eadde0;border-radius:14px;padding:13px;
  margin-top:12px;min-height:90px;font-size:9px;color:#6e5960;line-height:1.5
}
.report-format-buttons{display:flex;gap:7px;flex-wrap:wrap}
.report-format-buttons button{
  border:1px solid #e3d4d8;background:#fff;border-radius:999px;padding:8px 10px;
  font-size:8px;font-weight:900
}
.report-format-buttons button.active{background:#171115;color:#fff}
@media(max-width:1100px){
  .insight-kpis{grid-template-columns:repeat(2,1fr)}
  .insight-layout{grid-template-columns:1fr}
  .reports-grid{grid-template-columns:repeat(2,1fr)}
}
@media(max-width:720px){
  .insight-kpis,.insight-secondary,.reports-grid{grid-template-columns:1fr}
  .gender-area{grid-template-columns:1fr}
  .report-builder-options,.report-checks{grid-template-columns:1fr}
}

/* ===== AGENCY BRANDS — ACTIVATION DRILL-DOWN ===== */
.agency-brand-card{cursor:pointer;transition:.2s ease}
.agency-brand-card:hover{transform:translateY(-3px);box-shadow:0 14px 32px rgba(50,18,28,.08)}
.agency-brand-card.selected{outline:2px solid var(--cmih-red);outline-offset:2px}
.drilldown{
  margin-top:14px;background:#fff;border:1px solid #e4dadd;border-radius:22px;
  overflow:hidden;box-shadow:0 14px 36px rgba(52,18,29,.045)
}
.drill-head{
  padding:22px;display:flex;justify-content:space-between;gap:18px;align-items:start;
  background:linear-gradient(145deg,#14090c,#2a060b);color:#fff
}
.drill-lock{display:flex;align-items:center;gap:14px}
.drill-lock img{
  width:105px;height:54px;object-fit:contain;filter:none;background:rgba(255,255,255,.96);
  border-radius:13px;padding:8px
}
.drill-head .eyebrow{color:#ff8c96}
.drill-head h2{margin:5px 0 4px;font-size:26px}
.drill-head p{margin:0;color:#cdb7bd;font-size:9px;line-height:1.45}
.drill-head-actions{display:flex;gap:8px;flex-wrap:wrap}
.drill-head-actions button{
  border:1px solid rgba(255,255,255,.14);background:rgba(255,255,255,.07);
  color:#fff;border-radius:10px;padding:9px 11px;font-size:8px;font-weight:900
}
.drill-body{padding:18px}
.drill-kpis{
  display:grid;grid-template-columns:repeat(6,1fr);gap:9px;margin-bottom:13px
}
.drill-kpi{border:1px solid #e9dfe2;background:#fff9fa;border-radius:15px;padding:13px}
.drill-kpi small{
  display:block;font-size:7px;color:#887278;text-transform:uppercase;letter-spacing:.08em
}
.drill-kpi strong{display:block;font-size:23px;margin-top:6px}
.drill-kpi span{display:block;font-size:7px;color:#a5000d;font-weight:850;margin-top:4px}
.channel-split{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:13px}
.channel-card{border:1px solid #e6dade;border-radius:17px;padding:15px;background:#fff}
.channel-card-head{display:flex;justify-content:space-between;align-items:end;gap:12px}
.channel-card h3{font-size:12px;margin:0}.channel-card .big{font-size:23px;font-weight:950}
.channel-card small{font-size:8px;color:#897278}
.progress-lg{height:9px;border-radius:999px;background:#efe6e8;overflow:hidden;margin-top:11px}
.progress-lg span{display:block;height:100%;background:linear-gradient(90deg,#ff1020,#8a000c);border-radius:999px}
.brand-drill-grid{display:grid;grid-template-columns:.85fr 1.15fr;gap:13px;margin-bottom:13px}
.location-list{display:grid;gap:8px;max-height:510px;overflow:auto}
.location-item{
  border:1px solid #e8dcdf;background:#fff;border-radius:14px;padding:11px;cursor:pointer;
  transition:.18s
}
.location-item:hover,.location-item.active{border-color:#d48e99;background:#fff4f6}
.location-item-top{display:flex;justify-content:space-between;gap:10px;align-items:start}
.location-item strong{font-size:10px}.location-item small{display:block;color:#897278;font-size:7.5px;margin-top:3px}
.location-progress{height:6px;border-radius:999px;background:#f0e8ea;overflow:hidden;margin-top:9px}
.location-progress span{display:block;height:100%;background:#ff1020;border-radius:999px}
.location-item-stats{display:flex;justify-content:space-between;gap:8px;margin-top:7px;font-size:7.5px;color:#755f65}
.daily-panel-head{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:10px}
.daily-panel-head h3{margin:0;font-size:13px}
.daily-panel-head small{display:block;font-size:8px;color:#8a747a;margin-top:3px}
.daily-chart{
  height:190px;border-left:1px solid #eee3e6;border-bottom:1px solid #eee3e6;
  padding:22px 8px 28px;display:flex;align-items:end;gap:10px;margin-bottom:15px
}
.day-bar{
  flex:1;min-width:36px;border-radius:999px 999px 3px 3px;position:relative;
  background:linear-gradient(180deg,#ff1d2f,#8d000c)
}
.day-bar.target{background:linear-gradient(180deg,#c9b8bd,#756167)}
.day-bar b{position:absolute;top:-17px;left:50%;transform:translateX(-50%);font-size:7px;white-space:nowrap}
.day-bar span{position:absolute;bottom:-21px;left:50%;transform:translateX(-50%);font-size:7px;color:#806a70;white-space:nowrap}
.drill-filters{display:flex;gap:7px;flex-wrap:wrap;margin-bottom:11px}
.drill-filter{
  height:36px;border:1px solid #e3d5d9;background:#fff;border-radius:999px;
  padding:0 9px;font-size:8px
}
.location-summary{
  display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-bottom:11px
}
.location-summary div{background:#f8f1f3;border-radius:11px;padding:10px}
.location-summary small{display:block;font-size:7px;color:#8a7379;text-transform:uppercase}
.location-summary strong{display:block;font-size:13px;margin-top:5px}
.month-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:9px}
.month-card{border:1px solid #e8dcdf;border-radius:14px;padding:12px;background:#fff}
.month-card small{display:block;font-size:7px;color:#8b7479;text-transform:uppercase}
.month-card strong{display:block;font-size:18px;margin-top:5px}
.month-card .location-progress{margin-top:8px}
.table-click tbody tr{cursor:pointer}.table-click tbody tr:hover{background:#fff4f6}
@media(max-width:1150px){
  .drill-kpis{grid-template-columns:repeat(3,1fr)}
  .brand-drill-grid{grid-template-columns:1fr}
}
@media(max-width:720px){
  .drill-kpis,.location-summary,.month-grid{grid-template-columns:repeat(2,1fr)}
  .channel-split{grid-template-columns:1fr}
  .drill-head{display:block}.drill-head-actions{margin-top:14px}
}

/* ===== ADMIN — ACTIVATION PLAN, LOCATION / DAY TARGETS & STAFFING ===== */
.activation-plan-panel{
  margin-top:13px;background:#fff;border:1px solid #e4dadd;border-radius:20px;padding:17px
}
.plan-head{
  display:flex;justify-content:space-between;gap:14px;align-items:start;margin-bottom:13px
}
.plan-head h3{margin:0;font-size:14px}.plan-head p{margin:4px 0 0;color:#897278;font-size:8px;line-height:1.45}
.plan-summary{
  display:grid;grid-template-columns:repeat(5,1fr);gap:9px;margin-bottom:14px
}
.plan-stat{background:#fbf5f7;border:1px solid #eadde0;border-radius:14px;padding:12px}
.plan-stat small{display:block;font-size:7px;color:#8a7479;text-transform:uppercase;letter-spacing:.08em}
.plan-stat strong{display:block;font-size:20px;margin-top:5px}
.plan-stat span{display:block;font-size:7px;color:#9a0010;margin-top:4px;font-weight:850}
.plan-config{
  display:grid;grid-template-columns:repeat(4,1fr);gap:9px;margin-bottom:14px
}
.plan-config .field{margin:0}
.location-builder{display:grid;gap:12px}
.admin-location-card{
  border:1px solid #e6dadd;border-radius:18px;background:#fff;overflow:hidden
}
.admin-location-head{
  padding:13px 14px;background:#fbf5f7;display:flex;justify-content:space-between;
  gap:12px;align-items:center;border-bottom:1px solid #eadde0
}
.admin-location-head strong{font-size:11px}.admin-location-head small{display:block;color:#897278;font-size:7px;margin-top:3px}
.admin-location-actions{display:flex;gap:6px}
.admin-location-actions button{
  border:1px solid #dfd0d4;background:#fff;border-radius:8px;padding:7px 9px;font-size:8px;font-weight:900
}
.admin-location-body{padding:13px}
.location-fields{
  display:grid;grid-template-columns:1.4fr .75fr .75fr 1.3fr;gap:9px;margin-bottom:11px
}
.staff-allocator{
  border:1px solid #eadde0;border-radius:13px;padding:10px;background:#fffafb;margin-bottom:11px
}
.staff-allocator-head{display:flex;justify-content:space-between;gap:10px;align-items:center;margin-bottom:8px}
.staff-allocator-head strong{font-size:9px}.staff-allocator-head small{font-size:7px;color:#887278}
.staff-chips{display:flex;gap:7px;flex-wrap:wrap}
.staff-chip{
  display:flex;align-items:center;gap:6px;border:1px solid #e4d6da;background:#fff;border-radius:999px;
  padding:7px 9px;font-size:8px;font-weight:800;cursor:pointer
}
.staff-chip input{accent-color:#ff1020}.staff-chip.assigned{background:#fff0f3;border-color:#d98e99}
.day-builder{border-top:1px solid #eee4e7;padding-top:11px}
.day-builder-head{display:flex;justify-content:space-between;gap:10px;align-items:center;margin-bottom:8px}
.day-builder-head strong{font-size:9px}.day-builder-head button{border:0;background:#171115;color:#fff;border-radius:8px;padding:7px 9px;font-size:8px;font-weight:900}
.day-row{
  display:grid;grid-template-columns:72px 1fr .85fr .8fr .8fr 34px;gap:7px;align-items:end;
  border:1px solid #eee3e6;background:#fff;border-radius:11px;padding:9px;margin-bottom:7px
}
.day-row .field{margin:0}.day-row .field label{font-size:6.5px;margin-bottom:4px}
.day-row .field input,.day-row .field select{height:35px;font-size:8px;padding:0 7px}
.day-label{font-size:9px;font-weight:950;padding:0 0 11px 2px}
.day-remove{width:30px;height:35px;border:1px solid #e6d7db;background:#fff;border-radius:8px;color:#9c0010;font-weight:950}
.plan-validation{
  margin-top:12px;border-left:3px solid #d99600;background:#fff6df;border-radius:0 10px 10px 0;
  padding:10px 12px;font-size:8px;color:#775b18;line-height:1.45
}
.plan-validation.ok{border-left-color:#0a9d70;background:#ebfaf4;color:#17664e}
.assigned-table td{vertical-align:top}
.assignment-location{font-size:7px;color:#8c757b;margin-top:3px}
@media(max-width:1100px){
  .plan-summary{grid-template-columns:repeat(3,1fr)}
  .plan-config{grid-template-columns:repeat(2,1fr)}
  .location-fields{grid-template-columns:1fr 1fr}
}
@media(max-width:720px){
  .plan-summary,.plan-config,.location-fields{grid-template-columns:1fr}
  .day-row{grid-template-columns:1fr 1fr}
  .day-label{padding:0}.day-remove{width:100%}
}

/* ===== BRAND ACTIVATION — TEMPORARY CLIENT LINK ===== */
.client-link-zone{
  margin:0 6vw 70px;border-radius:22px;padding:17px 18px;
  background:linear-gradient(145deg,#16080b,#2b050a);color:#fff;
  border:1px solid rgba(255,255,255,.08);box-shadow:0 18px 42px rgba(51,0,8,.12)
}
.client-link-top{
  display:flex;justify-content:space-between;gap:16px;align-items:center;flex-wrap:wrap
}
.client-link-copy strong{display:block;font-size:12px}
.client-link-copy small{display:block;color:#bda8ad;font-size:8px;line-height:1.45;margin-top:4px;max-width:620px}
.client-link-actions{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
.client-duration{
  height:42px;border-radius:11px;border:1px solid rgba(255,255,255,.15);
  background:rgba(255,255,255,.08);color:#fff;padding:0 11px;font-size:9px;font-weight:850
}
.client-duration option{color:#171115}
.generate-client-btn{
  height:42px;border:0;border-radius:11px;padding:0 17px;
  background:linear-gradient(135deg,#ff1427,#a5000d);color:#fff;
  font-size:9px;font-weight:950;letter-spacing:.05em;text-transform:uppercase;
  box-shadow:0 10px 28px rgba(255,16,32,.2)
}
.generate-client-btn:hover{transform:translateY(-2px)}
.client-link-result{
  display:none;margin-top:13px;border:1px solid rgba(255,255,255,.10);
  background:rgba(255,255,255,.055);border-radius:15px;padding:12px
}
.client-link-result.show{display:block}
.client-result-grid{display:grid;grid-template-columns:1fr auto;gap:12px;align-items:center}
.client-url{
  min-width:0;font-family:monospace;font-size:9px;color:#ffe4e8;
  overflow:hidden;text-overflow:ellipsis;white-space:nowrap
}
.client-meta{display:flex;gap:10px;flex-wrap:wrap;margin-top:7px;color:#bda8ad;font-size:7.5px}
.client-result-actions{display:flex;gap:6px}
.client-result-actions button{
  border:1px solid rgba(255,255,255,.13);background:rgba(255,255,255,.08);color:#fff;
  border-radius:9px;padding:8px 10px;font-size:8px;font-weight:900
}
.client-result-actions button.primary{background:#fff;color:#171115}
.client-auth-brand{
  display:flex;align-items:center;gap:11px;padding:11px;border-radius:13px;
  background:#f8f1f3;margin:12px 0
}
.client-auth-brand img{width:74px;height:38px;object-fit:contain}
.client-auth-brand strong{font-size:10px}.client-auth-brand small{display:block;color:#846d73;font-size:7.5px;margin-top:3px}
.client-access-banner{
  display:none;border-radius:15px;padding:11px 13px;margin-bottom:13px;
  background:linear-gradient(135deg,#fff0f3,#f6e5e9);border:1px solid #e6cdd3;
  align-items:center;justify-content:space-between;gap:13px
}
.client-access-banner.show{display:flex}
.client-access-banner strong{font-size:10px}.client-access-banner small{display:block;color:#7f6870;font-size:7.5px;margin-top:3px}
.client-access-banner button{border:0;border-radius:9px;padding:8px 10px;background:#171115;color:#fff;font-size:8px;font-weight:900}
body.client-readonly #agencyAdminButton{display:none}
body.client-readonly #agencyBrand{pointer-events:none;opacity:.72}
body.client-readonly .big-nav[data-agency-tab="brands"],
body.client-readonly .big-nav[data-agency-tab="promoters"],
body.client-readonly .big-nav[data-agency-tab="retailers"]{display:none}
body.client-readonly .big-side .big-nav[onclick*="agencySignOut"]{display:none}
@media(max-width:720px){
  .client-result-grid{grid-template-columns:1fr}
  .client-result-actions{justify-content:flex-start}
  .client-link-zone{margin-left:4vw;margin-right:4vw}
}

/* ===== CLIENT READ-ONLY — BRAND-SPECIFIC DETAIL DASHBOARD ===== */
.client-brand-overview{display:none;margin-bottom:14px}
body.client-readonly .client-brand-overview{display:block}
.client-hero-card{
  position:relative;overflow:hidden;border-radius:22px;padding:22px;color:#fff;
  background:linear-gradient(145deg,#12080a,#31050b);margin-bottom:12px;
  border:1px solid rgba(255,255,255,.08)
}
.client-hero-card:after{
  content:"";position:absolute;width:270px;height:270px;border-radius:50%;
  right:-80px;top:-110px;background:var(--cmih-red);opacity:.14
}
.client-hero-top{position:relative;z-index:1;display:flex;justify-content:space-between;gap:18px;align-items:start}
.client-hero-lock{display:flex;align-items:center;gap:13px}
.client-hero-lock img{width:108px;height:54px;object-fit:contain;background:#fff;border-radius:13px;padding:8px}
.client-hero-lock h2{margin:0;font-size:23px}.client-hero-lock p{margin:5px 0 0;color:#cdb7bd;font-size:8px}
.client-hero-badge{border:1px solid rgba(255,255,255,.15);border-radius:999px;padding:8px 11px;font-size:8px;font-weight:900;text-transform:uppercase}
.client-kpis{
  position:relative;z-index:1;display:grid;grid-template-columns:repeat(6,1fr);gap:9px;margin-top:18px
}
.client-kpi{background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.09);border-radius:14px;padding:12px}
.client-kpi small{display:block;color:#cdb8bd;font-size:7px;text-transform:uppercase;letter-spacing:.07em}
.client-kpi strong{display:block;font-size:22px;margin-top:6px}.client-kpi span{display:block;color:#ff9aa3;font-size:7px;margin-top:4px}
.location-status-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:12px}
.location-status-card{background:#fff;border:1px solid #e5dadd;border-radius:17px;padding:14px}
.location-status-card small{display:block;font-size:7px;color:#8a7479;text-transform:uppercase;letter-spacing:.08em}
.location-status-card strong{display:block;font-size:25px;margin-top:6px}
.location-status-card.completed{border-top:3px solid #0a9d70}
.location-status-card.live{border-top:3px solid #ff1020}
.location-status-card.upcoming{border-top:3px solid #d79a00}
.client-location-table tbody tr{cursor:pointer}
.client-location-table tbody tr:hover{background:#fff3f6}
.client-location-progress{min-width:130px}
.client-location-progress .track{height:7px;border-radius:999px;background:#efe5e8;overflow:hidden;margin-bottom:4px}
.client-location-progress .track span{display:block;height:100%;background:linear-gradient(90deg,#ff1020,#92000c);border-radius:999px}
.client-location-progress small{font-size:7px;color:#8b747a}
.status-pill{display:inline-flex;align-items:center;gap:5px;border-radius:999px;padding:6px 8px;font-size:7px;font-weight:900;text-transform:uppercase;letter-spacing:.05em}
.status-pill.completed{background:#e8f8f1;color:#087a59}
.status-pill.live{background:#fff0f2;color:#cf0012}
.status-pill.upcoming{background:#fff5df;color:#a86f00}
.client-quick-links{display:flex;gap:7px;flex-wrap:wrap;margin-top:12px}
.client-quick-links button{border:1px solid rgba(255,255,255,.13);background:rgba(255,255,255,.07);color:#fff;border-radius:999px;padding:8px 10px;font-size:8px;font-weight:900}

/* In client mode the Agency sidebar becomes a client-facing navigation. */
body.client-readonly .big-nav[data-agency-tab="retailers"]{display:none}
body.client-readonly .big-nav[data-agency-tab="brands"],
body.client-readonly .big-nav[data-agency-tab="promoters"]{display:block}
body.client-readonly .big-side .big-nav[onclick*="showView('activation')"]{display:none}
body.client-readonly #agencyStatus{display:none}
body.client-readonly #agencyBrand{opacity:.78}
body.client-readonly #agencyAdminButton{display:none}

/* Individual brand location status controls */
.location-status-summary{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:12px}
.location-status-mini{border:1px solid #e8dcdf;border-radius:13px;padding:11px;background:#fff}
.location-status-mini small{display:block;font-size:7px;text-transform:uppercase;color:#8b747a}
.location-status-mini strong{display:block;font-size:18px;margin-top:5px}
@media(max-width:1100px){/deep/ .client-kpis{grid-template-columns:repeat(3,1fr)}}
@media(max-width:720px){
  .client-kpis{grid-template-columns:repeat(2,1fr)}
  .location-status-grid,.location-status-summary{grid-template-columns:1fr}
  .client-hero-top{display:block}.client-hero-badge{display:inline-flex;margin-top:12px}
}

            /* Hide scrollbars globally across the page and frames, keeping them scrollable */
            html::-webkit-scrollbar,
            body::-webkit-scrollbar,
            *::-webkit-scrollbar {
                display: none !important;
            }
            html,
            body,
            * {
                -ms-overflow-style: none !important;
                scrollbar-width: none !important;
            }

            /* Overrides to make continue/action buttons sit below inputs in the form flow and scroll with it */
            .phone-page .phone-bottom,
            .phone-screen .phone-bottom {
                position: relative !important;
                margin-top: 24px !important;
                padding: 16px 0 !important;
                background: transparent !important;
                border-top: none !important;
                left: auto !important;
                right: auto !important;
                bottom: auto !important;
            }

            /* Google Places Autocomplete premium dark theme styling */
            .pac-container {
                background-color: #171115 !important;
                border: 1px solid rgba(255, 255, 255, 0.15) !important;
                border-radius: 12px !important;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
                font-family: Inter, Arial, sans-serif !important;
                margin-top: 4px !important;
                color: #fff !important;
                z-index: 1000000 !important;
            }
            .pac-item {
                border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
                color: #aeb4bc !important;
                padding: 10px 14px !important;
                font-size: 11px !important;
                cursor: pointer !important;
            }
            .pac-item:hover {
                background-color: rgba(255, 255, 255, 0.08) !important;
            }
            .pac-item-query {
                color: #fff !important;
                font-size: 12px !important;
            }
            .pac-matched {
                color: #ff1020 !important;
            }
            .pac-icon {
                filter: invert(1) !important;
            }
        </style>
    @endpush
@endonce
