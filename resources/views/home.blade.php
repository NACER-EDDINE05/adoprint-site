@extends('layouts.storefront')

@section('title', 'ADO PRINT — Imprimerie Casablanca | Imprimerie en ligne')
@section('meta_description', 'Impression en ligne : cartes de visite, flyers, brochures, affiches, objets publicitaires. Prix dégressifs, impression express et livraison rapide partout au Maroc.')

@section('content')

<div id="top-banner" class="carousel slide d-md-block hp24-banner" data-interval="8000" data-ride="carousel" role="region" aria-label="Carousel">
    <ul class="carousel-indicators" role="tablist">
        @foreach ($bannerSlides as $slide)
            <li data-target="#top-banner" data-slide-to="{{ $loop->index }}" role="tab" tabindex="0" aria-label="glisser {{ $loop->iteration }}"></li>
        @endforeach
    </ul>
    <div class="carousel-inner">
        @foreach ($bannerSlides as $slide)
            @php($hue = ['#ffe22c', '#0b0b0b', '#1263e0'][$loop->index % 3])
            <div class="carousel-item {{ $loop->first ? 'active' : '' }} text-center">
                <div class="hp24-slide" style="--slide-bg: {{ $hue }}; --slide-ink: {{ $hue === '#0b0b0b' ? '#fff' : '#0b0b0b' }};">
                    <div class="hp24-slide__text">
                        <span class="hp24-slide__eyebrow">{{ $slide['eyebrow'] }}</span>
                        <h2 class="hp24-slide__title">{{ $slide['title'] }}</h2>
                        <p class="hp24-slide__text">{{ $slide['text'] }}</p>
                        <a href="{{ $slide['href'] }}" class="btn btn-info font-weight-bold">{{ $slide['cta'] }}</a>
                    </div>
                    <div class="hp24-slide__img">
                        <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] }}" loading="lazy">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <a href="#top-banner" class="carousel-control-prev" data-slide="prev" aria-label="Previous"><span class="carousel-control-prev-icon"></span></a>
    <a href="#top-banner" class="carousel-control-next" data-slide="next" aria-label="Next"><span class="carousel-control-next-icon"></span></a>
</div>

<main>
<div class="container body-container py-3 py-md-5">


<style>
.hp24-root, .hp24-root *, .hp24-root *::before, .hp24-root *::after {
  box-sizing: border-box; margin: 0; padding: 0;
}
.hp24-root a { text-decoration: none; color: inherit; }
.hp24-root img { display: block; max-width: 100%; }
.hp24-root {
  --blue:#1263e0; --blue-dk:#0b4bb0; --blue-lt:#eef4fd;
  --gold:#f5a623; --dark:#111118; --mid:#555566; --soft:#888899;
  --bg:#f6f6fa; --card-bg:#ffffff; --border:#ebebf0; --border-hv:rgba(18,99,224,0.4);
  font-family:'Outfit',sans-serif; color:var(--dark); line-height:1.6; background:transparent;
}
.hp24-wrap { max-width:1340px; margin:0 auto; padding:0 28px 100px; }
.hp24-sh { display:flex; align-items:flex-end; justify-content:space-between; gap:16px; margin-bottom:36px; flex-wrap:wrap; }
.hp24-sh-left { display:flex; flex-direction:column; gap:6px; }
.hp24-sh-label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.18em; color:var(--blue); }
.hp24-sh h2 { font-size:clamp(24px,3vw,36px); font-weight:800; color:var(--dark); line-height:1.05; letter-spacing:-.025em; }
.hp24-sh h2 em { font-style:normal; color:var(--blue); }
.hp24-badge { display:inline-flex; align-items:center; gap:8px; background:var(--white); border:1.5px solid var(--border); padding:8px 18px; border-radius:999px; font-size:13px; font-weight:600; color:var(--mid); white-space:nowrap; box-shadow:0 2px 8px rgba(0,0,0,.04); background:#fff; }
.hp24-stars { color:var(--gold); letter-spacing:1px; }
.hp24-promos, .hp24-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:22px; margin-bottom:72px; }
.hp24-card { min-width:0; background:var(--card-bg); border-radius:20px; overflow:hidden; border:1.5px solid var(--border); display:flex; flex-direction:column; box-shadow:0 2px 12px rgba(0,0,0,.05); transition:transform .28s cubic-bezier(.22,1,.36,1),box-shadow .28s ease,border-color .28s ease; position:relative; }
.hp24-card:hover { transform:translateY(-8px); box-shadow:0 20px 50px rgba(18,99,224,.12),0 4px 16px rgba(0,0,0,.06); border-color:var(--border-hv); }
.hp24-img { contain:layout paint; position:relative; width:100%; aspect-ratio:1/1; overflow:hidden; background:var(--bg); flex-shrink:0; }
.hp24-img img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; transition:transform .5s cubic-bezier(.22,1,.36,1); }
.hp24-card:hover .hp24-img img { transform:scale(1.07); }
.hp24-img::after { content:''; position:absolute; inset:0; background:linear-gradient(to top,rgba(0,0,0,.18) 0%,transparent 45%); pointer-events:none; z-index:1; }
.hp24-img-ph { position:absolute; inset:0; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,#f4f4f8 0%,#e9e9f2 100%); }
.hp24-img-ph i { font-size:64px; color:#d1d1de; }
.hp24-tag { position:absolute; top:14px; left:14px; z-index:10; background:var(--blue); color:#fff; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.1em; padding:4px 12px; border-radius:999px; box-shadow:0 4px 14px rgba(18,99,224,.4); pointer-events:none; }
.hp24-tag.gold { background:var(--gold); color:#3a2000; box-shadow:0 4px 14px rgba(245,166,35,.35); }
.hp24-body { padding:20px 20px 22px; display:flex; flex-direction:column; flex:1; gap:6px; }
.hp24-body h3 { font-size:15px; font-weight:700; color:var(--dark); line-height:1.3; letter-spacing:-.01em; }
.hp24-body p { font-size:13px; color:var(--soft); line-height:1.55; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.hp24-price-row { margin-top:14px; padding-top:14px; border-top:1.5px solid var(--border); display:flex; align-items:center; justify-content:space-between; gap:8px; }
.hp24-price { font-size:13px; font-weight:700; color:var(--blue); }
.hp24-arrow { width:30px; height:30px; border-radius:50%; background:var(--blue-lt); border:1.5px solid rgba(18,99,224,.2); display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:background .2s,transform .2s; }
.hp24-card:hover .hp24-arrow { background:var(--blue); transform:translateX(2px); }
.hp24-arrow svg { stroke:var(--blue); transition:stroke .2s; }
.hp24-card:hover .hp24-arrow svg { stroke:#fff; }
.hp24-div { height:1.5px; background:var(--border); margin:0 0 72px; }
.hp24-about { display:grid; grid-template-columns:1fr 1fr; border-radius:24px; overflow:hidden; border:1.5px solid var(--border); margin-bottom:72px; min-height:440px; box-shadow:0 8px 40px rgba(18,99,224,.08); }
.hp24-about-txt { background:#fff; padding:60px 52px; display:flex; flex-direction:column; justify-content:center; gap:18px; }
.hp24-eyebrow { font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:.18em; color:var(--blue); }
.hp24-about-txt h2 { font-size:clamp(26px,3vw,40px); font-weight:800; color:var(--dark); line-height:1.1; letter-spacing:-.025em; }
.hp24-about-txt p { font-size:15px; color:var(--mid); line-height:1.75; }
.hp24-btn { display:inline-flex; align-items:center; gap:10px; background:var(--blue); color:#fff; padding:14px 28px; border-radius:999px; font-size:14px; font-weight:700; letter-spacing:.04em; text-transform:uppercase; align-self:flex-start; transition:background .2s,transform .18s,box-shadow .18s; box-shadow:0 8px 24px rgba(18,99,224,.3); }
.hp24-btn:hover { background:var(--blue-dk); transform:translateY(-2px); box-shadow:0 14px 32px rgba(18,99,224,.38); }
.hp24-about-img { position:relative; overflow:hidden; min-height:440px; background:var(--bg); }
.hp24-about-img img { width:100%; height:100%; object-fit:cover; transition:transform .6s ease; }
.hp24-about:hover .hp24-about-img img { transform:scale(1.04); }
.hp24-services { display:grid; grid-template-columns:repeat(3,1fr); gap:22px; }
.hp24-svc { background:#fff; border:1.5px solid var(--border); border-radius:20px; padding:36px 30px; display:flex; flex-direction:column; gap:16px; transition:transform .25s ease,border-color .25s,box-shadow .25s; }
.hp24-svc:hover { transform:translateY(-5px); border-color:var(--border-hv); box-shadow:0 16px 40px rgba(18,99,224,.1); }
.hp24-svc-icon { width:54px; height:54px; border-radius:14px; background:var(--blue-lt); border:1.5px solid rgba(18,99,224,.18); display:flex; align-items:center; justify-content:center; }
.hp24-svc-icon svg { width:26px; height:26px; stroke:var(--blue); stroke-width:1.8; fill:none; stroke-linecap:round; stroke-linejoin:round; }
.hp24-svc h3 { font-size:17px; font-weight:700; color:var(--dark); letter-spacing:-.01em; }
.hp24-svc p { font-size:14px; color:var(--mid); line-height:1.65; }

/* --- Carrousel d'ouverture --- */
.hp24-banner { background: #eee; }
.hp24-slide { --slide-bg:#ffe22c; --slide-ink:#0b0b0b; display:flex; align-items:center; justify-content:center; gap:40px; min-height:340px; padding:34px 6vw; background:var(--slide-bg); color:var(--slide-ink); }
.hp24-slide__text { max-width:560px; text-align:left; }
.hp24-slide__eyebrow { font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:.18em; opacity:.75; }
.hp24-slide__title { font-family:'Outfit',sans-serif; font-size:clamp(28px,4vw,54px); font-weight:900; line-height:1.05; letter-spacing:-.02em; margin:10px 0 14px; color:var(--slide-ink); }
.hp24-slide__text { font-size:15px; line-height:1.6; margin-bottom:22px; color:var(--slide-ink); opacity:.85; }
.hp24-slide .btn-info { background:transparent; border:2px solid var(--slide-ink); color:var(--slide-ink); font-weight:800; text-transform:uppercase; letter-spacing:.06em; font-size:14px; padding:12px 26px; transition:background .2s,color .2s; }
.hp24-slide .btn-info:hover, .hp24-slide .btn-info:focus { background:var(--slide-ink); color:var(--slide-bg); border-color:var(--slide-ink); }
.hp24-slide__img img { max-height:300px; max-width:min(46vw,460px); width:100%; height:auto; object-fit:contain; border-radius:16px; box-shadow:0 18px 44px rgba(0,0,0,.22); }
@media (max-width:820px) {
  .hp24-slide { flex-direction:column-reverse; text-align:center; padding:20px 16px 28px; min-height:0; gap:20px; }
  .hp24-slide__text { max-width:none; width:100%; }
  .hp24-slide__img { width:100%; display:flex; align-items:center; justify-content:center; }
  .hp24-slide__img img { max-width:90vw; width:100%; height:auto; max-height:240px; object-fit:contain; }
}
@media (max-width:1100px) { .hp24-promos, .hp24-grid { grid-template-columns:repeat(3,1fr); } }
@media (max-width:820px) {
  .hp24-promos, .hp24-grid { grid-template-columns:repeat(2,1fr); }
  .hp24-about { grid-template-columns:1fr; }
  .hp24-about-img { min-height:280px; order:-1; }
  .hp24-about-txt { padding:36px 28px; }
  .hp24-services { grid-template-columns:1fr 1fr; }
}
@media (max-width:540px) {
  .hp24-promos, .hp24-grid { grid-template-columns:repeat(2,1fr); gap:14px; }
  .hp24-services { grid-template-columns:1fr; }
  .hp24-sh { flex-direction:column; align-items:flex-start; }
  .hp24-wrap { padding:40px 16px 80px; }
  .hp24-body { padding:16px 14px 18px; }
}
</style>

<div class="hp24-root"><div class="hp24-wrap">

    {{-- Promotions --}}
    <div class="hp24-sh">
        <div class="hp24-sh-left">
            <span class="hp24-sh-label">Offres exclusives</span>
            <h2>Nos supers <em>promos</em></h2>
        </div>
        <div class="hp24-badge"><span class="hp24-stars">★★★★★</span><strong>&nbsp;Clients satisfaits</strong></div>
    </div>
    <div class="hp24-promos">
        @forelse ($promos as $promo)
            @php($p = $promo['product'])
            <a href="{{ route('product.show', $p->slug) }}" class="hp24-card">
                <div class="hp24-img">
                    <span class="hp24-tag gold">Top vente</span>
                    @if ($p->photoUrl())
                        <img src="{{ $p->photoUrl() }}" alt="{{ $p->name }}" loading="lazy">
                    @endif
                </div>
                <div class="hp24-body">
                    <h3>{{ $p->name }}</h3>
                    <p>{{ \Illuminate\Support\Str::limit(strip_tags((string) $p->description), 70) }}</p>
                    <div class="hp24-price-row">
                        <span class="hp24-price">Dès {{ number_format($promo['fromPrice'], 2, ',', ' ') }} dh · {{ $promo['minQty'] }} ex.</span>
                        <span class="hp24-arrow"><svg viewBox="0 0 16 16" width="14" height="14"><path d="M3 8h10M9 4l4 4-4 4"></path></svg></span>
                    </div>
                </div>
            </a>
        @empty
            <p class="col-12">Aucune promotion active pour le moment.</p>
        @endforelse
    </div>

    <div class="hp24-div"><br></div>

    {{-- Familles --}}
    <?php $familyIcons = [
        'impression-papier-bureautique'       => 'fas fa-book',
        'impression-grand-format'             => 'fas fa-image',
        'impression-textile-personnalisation' => 'fas fa-magic',
        'packaging-emballage'                 => 'fas fa-box-open',
        'signaletique-enseignes'              => 'fas fa-flag',
        'objets-publicitaires-cadeaux'        => 'fas fa-gift',
        'impression-evenementielle'           => 'fas fa-ticket-alt',
        'conception-graphique-design'         => 'fas fa-palette',
        'gravure-decoupe-cnc-laser'           => 'fas fa-layer-group',
    ]; ?>
    <div class="hp24-sh">
        <div class="hp24-sh-left">
            <span class="hp24-sh-label">Notre catalogue</span>
            <h2>Tous nos <em>produits</em></h2>
        </div>
        <div class="hp24-badge"><span class="hp24-stars">★★★★★</span> Qualité certifiée</div>
    </div>
        <div class="hp24-grid">
        @foreach ($families as $family)
            @php($f = $family['model'])
            <a href="{{ route('category.show', $f->slug) }}" class="hp24-card">
                <div class="hp24-img">
                    @if ($family['cheapest'] && $family['cheapest']->photoUrl())
                        <img src="{{ $family['cheapest']->photoUrl() }}" alt="{{ $f->name }}" loading="lazy">
                    @else
                        <div class="hp24-img-ph"><i class="{{ $familyIcons[$f->slug] ?? 'fas fa-print' }}"></i></div>
                    @endif
                </div>
                <div class="hp24-body">
                    <h3>{{ $f->name }}</h3>
                    <p>{{ \Illuminate\Support\Str::limit(strip_tags((string) $f->description), 70) ?: 'Découvrez notre gamme — livraison rapide partout au Maroc.' }}</p>
                    <div class="hp24-price-row">
                        <span class="hp24-price">{{ $family['fromPrice'] ? 'Dès '.($family['fromPrice'] < 10 ? number_format($family['fromPrice'], 2, ',', ' ') : number_format($family['fromPrice'], 0, ',', ' ')).' dh' : 'Découvrir la gamme' }}</span>
                        <span class="hp24-arrow"><svg viewBox="0 0 16 16" width="14" height="14"><path d="M3 8h10M9 4l4 4-4 4"></path></svg></span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    {{-- Histoire --}}
    <div class="hp24-about">
        <div class="hp24-about-txt">
            <p class="hp24-eyebrow">Notre histoire</p>
            <h2>Une imprimerie née à Casablanca</h2>
            <p>Chez ADO PRINT, nous offrons des impressions de qualité supérieure directement depuis Casablanca. Avec un engagement envers l'excellence, nous livrons vos projets partout au Maroc, rapidement et efficacement.</p>
            <a href="{{ route('a-propos') }}" class="hp24-btn">En savoir plus <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8h10M9 4l4 4-4 4"></path></svg></a>
        </div>
        <div class="hp24-about-img">
            @if ($families->first() && $families->first()['cheapest'] && $families->first()['cheapest']->photoUrl())
                <img src="{{ $families->first()['cheapest']->photoUrl() }}" alt="ADO PRINT Casablanca" loading="lazy">
            @endif
        </div>
    </div>

    {{-- Services --}}
    <div class="hp24-services">
        <div class="hp24-svc">
            <div class="hp24-svc-icon"><svg viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"></rect><path d="M8 21h8M12 17v4"></path></svg></div>
            <h3>Commande 100% en ligne</h3>
            <p>Calculez automatiquement le prix et passez commande directement en ligne, 24h/24, 7j/7.</p>
        </div>
        <div class="hp24-svc">
            <div class="hp24-svc-icon"><svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"></path></svg></div>
            <h3>Support à l'écoute</h3>
            <p>Disponible du lundi au samedi par WhatsApp, Email et Téléphone.</p>
        </div>
        <div class="hp24-svc">
            <div class="hp24-svc-icon"><svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"></rect><path d="M7 11V7a5 5 0 0110 0v4"></path></svg></div>
            <h3>100% Sécurisé</h3>
            <p>Confidentialité garantie de vos données et fichiers à chaque étape de la commande.</p>
        </div>
    </div>

</div></div>

<section class="hp24-how" aria-labelledby="hp24-how-title">
  <style>
.hp24-how, .hp24-how *, .hp24-how *::before, .hp24-how *::after { box-sizing: border-box; }
.hp24-how {
  --how-blue: #1263e0; --how-yellow: #ffe22c; --how-dark: #15151d;
  --how-text: #62626d; --how-border: #dedee4; --how-bg: #f7f7f8;
  width: 100%; padding: 72px 24px; background: var(--how-bg); color: var(--how-dark);
  font-family: 'Outfit',sans-serif;
}
.hp24-how__inner { width: 100%; max-width: 1300px; margin: 0 auto; display: grid; grid-template-columns: minmax(0,1fr) minmax(360px,.9fr); gap: 64px; align-items: center; }
.hp24-how__title { margin: 0 0 38px; font-size: clamp(32px,4vw,54px); line-height: 1.08; font-weight: 800; color: var(--how-dark); }
.hp24-how__steps { list-style: none; margin: 0; padding: 0; }
.hp24-how__step { position: relative; display: grid; grid-template-columns: 64px minmax(0,1fr); gap: 22px; padding-bottom: 34px; }
.hp24-how__step:not(:last-child)::after { content: ""; position: absolute; top: 64px; bottom: 8px; left: 31px; width: 1px; background: var(--how-border); }
.hp24-how__number { position: relative; z-index: 1; width: 64px; height: 64px; display: grid; place-items: center; border: 1px solid var(--how-border); border-radius: 50%; background: #fff; color: var(--how-dark); font-size: 20px; font-weight: 800; }
.hp24-how__step:first-child .hp24-how__number { border-color: var(--how-yellow); background: var(--how-yellow); }
.hp24-how__content { padding-top: 3px; }
.hp24-how__content h3 { margin: 0 0 10px; color: var(--how-dark); font-size: clamp(20px,2vw,27px); line-height: 1.3; font-weight: 750; }
.hp24-how__content p { max-width: 600px; margin: 0; color: var(--how-text); font-size: 16px; line-height: 1.75; }
.hp24-how__actions { display: flex; align-items: center; flex-wrap: wrap; gap: 16px 26px; margin: 10px 0 0 86px; }
.hp24-how__primary, .hp24-how__video { min-height: 48px; display: inline-flex; align-items: center; justify-content: center; gap: 10px; text-decoration: none; font-weight: 700; transition: background-color 180ms ease,color 180ms ease,transform 180ms ease; }
.hp24-how__primary { padding: 12px 22px; border: 2px solid var(--how-dark); border-radius: 8px; background: var(--how-dark); color: #fff; }
.hp24-how__primary:hover, .hp24-how__primary:focus-visible { background: var(--how-blue); border-color: var(--how-blue); color: #fff; transform: translateY(-2px); }
.hp24-how__video { color: var(--how-dark); }
.hp24-how__video-icon { width: 42px; height: 42px; display: grid; place-items: center; border: 2px solid var(--how-dark); border-radius: 50%; }
.hp24-how__video:hover, .hp24-how__video:focus-visible { color: var(--how-blue); }
.hp24-how__visual { position: relative; min-width: 0; }
.hp24-how__visual img { display: block; width: 100%; height: auto; aspect-ratio: 4/5; object-fit: contain; }
@media (max-width: 900px) {
  .hp24-how { padding: 52px 18px; }
  .hp24-how__inner { grid-template-columns: 1fr; gap: 42px; }
  .hp24-how__visual { order: -1; max-width: 480px; margin: 0 auto; }
  .hp24-how__visual img { max-height: 420px; }
  .hp24-how__title { margin-bottom: 30px; }
}
@media (max-width: 540px) {
  .hp24-how { padding: 42px 16px; }
  .hp24-how__step { grid-template-columns: 48px minmax(0,1fr); gap: 16px; padding-bottom: 28px; }
  .hp24-how__number { width: 48px; height: 48px; font-size: 17px; }
  .hp24-how__step:not(:last-child)::after { top: 48px; left: 23px; }
  .hp24-how__actions { margin-left: 0; align-items: stretch; flex-direction: column; }
  .hp24-how__primary { width: 100%; }
}
</style>
  <div class="hp24-how__inner">
    <div class="hp24-how__main">
      <h2 class="hp24-how__title" id="hp24-how-title">Comment ça marche&nbsp;?</h2>
      <ol class="hp24-how__steps">
        <li class="hp24-how__step">
          <span class="hp24-how__number" aria-hidden="true">1</span>
          <div class="hp24-how__content">
            <h3>Choisissez vos produits et options en ligne</h3>
            <p>Sélectionnez votre produit, configurez ses dimensions, finitions et quantités, puis obtenez immédiatement votre tarif.</p>
          </div>
        </li>
        <li class="hp24-how__step">
          <span class="hp24-how__number" aria-hidden="true">2</span>
          <div class="hp24-how__content">
            <h3>Transférez ou créez votre design</h3>
            <p>Importez votre fichier prêt à imprimer, ou confiez la création à notre équipe graphique.</p>
          </div>
        </li>
        <li class="hp24-how__step">
          <span class="hp24-how__number" aria-hidden="true">3</span>
          <div class="hp24-how__content">
            <h3>Commandez et recevez vos impressions</h3>
            <p>Validez votre commande en toute sécurité. Nous produisons vos supports et les livrons partout au Maroc.</p>
          </div>
        </li>
      </ol>
      <div class="hp24-how__actions">
        <a class="hp24-how__primary" href="{{ route('products.index') }}">Découvrir les produits</a>
        <a class="hp24-how__video" href="{{ route('aide') }}">
          &nbsp;&nbsp;Comment commander&nbsp;&nbsp;
          <span class="hp24-how__video-icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"></path></svg></span>
        </a>
      </div>
    </div>
    <div class="hp24-how__visual">
      @if ($bannerSlides->first())
        <img src="{{ $bannerSlides->first()['image'] }}" alt="Impression ADO PRINT" loading="lazy">
      @endif
    </div>
  </div>
</section>

<div class="info-section py-5 text-center">
    <div class="container py-lg-3">
        <div class="row">
            <div class="col-6 col-md-4 py-4 py-md-5"><h2 class="display-3">{{ $productCount }}</h2><h3>Produits en ligne</h3></div>
            <div class="col-6 col-md-4 py-4 py-md-5"><h2 class="display-3">-{{ $bestDiscount }}%</h2><h3>Meilleure remise</h3></div>
            <div class="col-6 col-md-4 py-4 py-md-5"><h2 class="display-3">24h</h2><h3>Impression express</h3></div>
            <div class="col-6 col-md-4 py-4 py-md-5"><h2 class="display-3">+98%</h2><h3>Clients satisfaits</h3></div>
            <div class="col-6 col-md-4 py-4 py-md-5"><h2 class="display-3">100%</h2><h3>Prix dégressifs</h3></div>
            <div class="col-6 col-md-4 py-4 py-md-5"><h2 class="display-3">Maroc</h2><h3>Livraison partout</h3></div>
        </div>
    </div>
</div>

</div><!--/container-->
</main>

@endsection
