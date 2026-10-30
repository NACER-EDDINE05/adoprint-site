{{-- ==========================================================================
    Pied de page réplique : newsletter + colonnes infos + copyright
    ========================================================================== --}}
@php
    $phone = $siteSettings->get('company.phone', '');
    $phoneIntl = $siteSettings->get('company.phone_intl', '');
    $email = $siteSettings->get('company.email', '');
    $address = $siteSettings->get('company.address', 'Casablanca, Maroc');
    $mapsUrl = $siteSettings->get('company.maps_url', '#');
    $hours = $siteSettings->get('company.hours', 'Lun–Ven : 9h–18h · Sam : 9h–13h');
    $phoneRaw = preg_replace('/[^0-9]/', '', $phoneIntl ?: $phone);
@endphp

<div id="footer" class="footer-content">
    {{-- Newsletter --}}
    <div class="subscribe_section py-4 py-md-5 text-center bg-primary">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-12 col-md-8 mb-md-0 mb-4">
                    <span style="color:#FFFFFF;">
                        <div class="card rounded-0 bg-transparent border-0" id="footersubscribe_sidebar">
                            <div class="card-body">
                                <form method="post" action="{{ route('contact.send') }}" onsubmit="return footerSubscribe(this);">
                                    <div class="form-row justify-content-center">
                                        <div class="col-12 col-md-6">
                                            <h3 class="mt-0 mb-md-0">Inscrivez-vous ici</h3>
                                        </div>
                                        <div class="col-12 col-md-6 text-right">
                                            <div class="form-group email">
                                                <div class="input-group">
                                                    <input type="email" name="subemail" value=""
                                                           class="form-control" placeholder="Entrez votre E-mail"
                                                           aria-label="Entrez votre E-mail" aria-required="true"
                                                           required />
                                                    <div class="input-group-append">
                                                        <button type="submit" class="btn btn btn-primary">S'abonner</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="help-block">Inscrivez-vous pour recevoir nos offres promotionnelles.</p>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </span>
                </div>
                <div class="col-12 col-md-4 social_icon">
                    <h3>Nous suivre</h3>
                    <ul class="text-center list-inline">
                        <li class="list-inline-item"><a href="https://www.facebook.com/" target="_blank" title="facebook" rel="noopener noreferrer"><i class="fab fa-facebook"></i></a><br></li>
                        <li class="list-inline-item"><a href="https://www.instagram.com/" target="_blank" title="instagram" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a><br></li>
                        <li class="list-inline-item"><a href="https://www.youtube.com/" target="_blank" title="youtube" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a><br></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Colonnes bas de page --}}
    <div class="footer_bottom bg-primary text-center text-sm-left py-3 py-md-5">
        <div class="container-fluid">
            <div class="row justify-content-center">

                <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3 mb-lg-0">
                    <h3>Info. &amp; commandes</h3>
                    <ul class="list-unstyled mb-3 mb-md-5">
                        <li class="display-5"><a href="tel:{{ $phoneRaw }}">{{ $phone }}</a></li>
                        <li class="display-5"><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings->get('company.whatsapp', $phoneIntl)) }}" target="_blank" rel="noopener">WhatsApp {{ $phone }}</a></li>
                    </ul>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('aide') }}">Comment commander en ligne ?</a></li>
                        <li><a href="{{ route('cart') }}">Mon panier</a></li>
                        <li><a href="{{ route('checkout.show') }}">Finaliser ma commande</a></li>
                    </ul>
                </div>

                <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3 mb-lg-0">
                    <h3>Coordonnées</h3>
                    <h4 class="text-white text-bold mb-0">Adresse imprimerie</h4>
                    <p>{{ $address }}<br>Casablanca – Maroc</p>
                    <h4 class="text-white text-bold mt-4 mb-0">Email</h4>
                    <p><a href="mailto:{{ $email }}">{{ $email }}</a></p>
                    <h3 class="mt-3 mt-md-5">Nos Horaires</h3>
                    <p class="mb-0">{{ $hours }}</p>
                </div>

                <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3 mb-lg-0">
                    <h3>Informations</h3>
                    <ul class="list-inline mb-0 bottom-links">
                        <li class="list-inline-item"><a href="{{ route('products.index') }}"><span> Tous nos produits </span></a></li>
                        <li class="list-inline-item"><a href="{{ route('devis') }}"><span> Devis sur mesure </span></a></li>
                        <li class="list-inline-item"><a href="{{ route('guides.index') }}"><span> Guides d'impression </span></a></li>
                        <li class="list-inline-item"><a href="{{ route('aide') }}"><span> Page d'aide / FAQ </span></a></li>
                        <li class="list-inline-item"><a href="{{ route('a-propos') }}"><span> À propos de nous </span></a></li>
                        <li class="list-inline-item"><a href="{{ route('contact') }}"><span> Contactez-nous </span></a></li>
                    </ul>
                </div>

                <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3 mb-lg-0">
                    <h3>Showroom</h3>
                    <h4 class="text-white text-bold mb-0">Casablanca</h4>
                    <p>{{ $address }}<br><a href="{{ $mapsUrl }}" target="_blank" rel="noopener" class="text-white">Voir sur Google Maps</a></p>
                    <h3 class="mt-3 mt-md-5" style="margin-bottom: 1.5rem; font-size: 1.11111rem; color: rgb(255, 226, 44); text-transform: uppercase; background-color: rgb(11, 11, 11);">AIDE</h3>
                    <ul class="list-unstyled mb-3 mb-md-5" style="margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; color: rgb(255, 255, 255); background-color: rgb(11, 11, 11);">
                        <li style="margin: 0px; display: block;"><a href="{{ route('aide') }}" style="color: rgb(251, 251, 251); display: inline-block; padding: 0.2rem 0px;">Comment commander en ligne ?</a></li>
                        <li style="margin: 0px; display: block;"><a href="{{ route('guides.index') }}" style="color: rgb(251, 251, 251); display: inline-block; padding: 0.2rem 0px;">Guides d'impression</a></li>
                        <li style="margin: 0px; display: block;">
                            <a href="{{ route('contact') }}" style="color: rgb(251, 251, 251); display: inline-block; padding: 0.2rem 0px;">Contactez-nous</a>
                            <br>
                            <a href="{{ route('aide') }}" style="color: rgb(251, 251, 251); display: inline-block; padding: 0.2rem 0px;">Foire aux questions</a>
                            <br>
                            <a href="{{ route('a-propos') }}" style="color: rgb(251, 251, 251); display: inline-block; padding: 0.2rem 0px;">À propos de nous</a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>

<footer class="py-2 text-center bg-primary">
    <div class="container">
        <small class="text-white">
            Copyright © {{ date('Y') }} ADO PRINT — adoprint.test. Tous droits réservés.
        </small>
    </div>
</footer>
