<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['parent_id', 'name', 'slug', 'description', 'active', 'position'];

    /**
     * Libellés de navigation sur deux lignes (comme sur la boutique de
     * référence) : la barre reste compacte tout en gardant des noms lisibles.
     * La seconde ligne peut être nulle (une seule ligne).
     */
    public const NAV_LABELS = [
        'impression-papier-bureautique'      => ['Papier &', 'Bureautique'],
        'impression-grand-format'            => ['Grand', 'Format'],
        'impression-textile-personnalisation' => ['Textile &', 'Perso.'],
        'packaging-emballage'                => ['Packaging', 'Emballage'],
        'signaletique-enseignes'             => ['Signalétique', 'Enseignes'],
        'objets-publicitaires-cadeaux'       => ['Objets &', 'Cadeaux'],
        'impression-evenementielle'          => ['Événementiel', null],
        'conception-graphique-design'        => ['Design', 'Graphique'],
        'gravure-decoupe-cnc-laser'          => ['Gravure &', 'Laser'],
    ];

    /**
     * Les deux lignes affichées dans la barre de navigation.
     *
     * @return array{0: string, 1: string|null}
     */
    public function navLines(): array
    {
        if (isset(self::NAV_LABELS[$this->slug])) {
            return self::NAV_LABELS[$this->slug];
        }

        // Repli : « Impression Papier & Bureautique » → « Papier & » / « Bureautique »
        $name = preg_replace('/^(Impression|Conception)\s+/u', '', $this->name);

        if (str_contains($name, ' & ')) {
            [$first, $second] = explode(' & ', $name, 2);

            return [trim($first) . ' &', trim($second)];
        }

        return [$name, null];
    }

    /**
     * Nom complet sur une seule ligne (pied de page, titres).
     */
    public function navLabel(): string
    {
        return implode(' ', array_filter($this->navLines()));
    }

    /**
     * Famille parente (null pour les 9 familles de premier niveau).
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Sous-catégories d'une famille, dans l'ordre défini au catalogue.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('position')->orderBy('name');
    }

    /**
     * Produits actifs de cette catégorie (pour la navigation déroulante).
     */
    public function products()
    {
        return $this->hasMany(Product::class)->where('active', true)->orderBy('name');
    }

    /**
     * Identifiants de la catégorie et de ses sous-catégories : sert à afficher
     * les produits d'une famille entière sur sa page.
     */
    public function selfAndChildrenIds(): array
    {
        return array_merge([$this->id], $this->children()->pluck('id')->all());
    }
}
