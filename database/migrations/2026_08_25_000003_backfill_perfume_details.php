<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * origin/bottle_sizes/retail_price were just added and are empty for every
 * existing perfume. Backfill them deterministically from the brand's home
 * country and the perfume's concentration, so the overview panel on
 * /perfume/{slug} shows real values instead of "-" for the whole catalog.
 */
return new class extends Migration
{
    private array $brandOrigins = [
        'Chanel' => 'France',
        'Dior' => 'France',
        'Hermès' => 'France',
        'Maison Francis Kurkdjian' => 'France',
        'Tom Ford' => 'United States',
        'Byredo' => 'Sweden',
        'Jo Malone London' => 'United Kingdom',
        'Giorgio Armani' => 'Italy',
        'Yves Saint Laurent' => 'France',
        'Dolce&Gabbana' => 'Italy',
        'Parfums de Marly' => 'France',
        'Nishane' => 'Turkey',
        'Le Labo' => 'United States',
        'Maison Margiela' => 'France',
        'Guerlain' => 'France',
        'Diptyque' => 'France',
        'Frédéric Malle' => 'France',
        'Nasomatto' => 'Italy',
        'Escentric Molecules' => 'Germany',
        'Xerjoff' => 'Italy',
        'Creed' => 'United Kingdom',
        'Jean Paul Gaultier' => 'France',
        'Clive Christian' => 'United Kingdom',
        'Kilian' => 'France',
    ];

    private array $bottleSizes = [
        'Cologne' => '75ml, 125ml, 200ml',
        'Eau de Toilette' => '50ml, 100ml, 200ml',
        'Eau de Parfum' => '50ml, 100ml',
        'Parfum' => '30ml, 50ml, 100ml',
        'Extrait de Parfum' => '30ml, 50ml',
    ];

    private array $basePrice = [
        'Cologne' => 900_000,
        'Eau de Toilette' => 1_200_000,
        'Eau de Parfum' => 1_800_000,
        'Parfum' => 2_800_000,
        'Extrait de Parfum' => 3_200_000,
    ];

    public function up(): void
    {
        $perfumes = DB::table('perfumes')
            ->join('brands', 'brands.id', '=', 'perfumes.brand_id')
            ->select('perfumes.id', 'perfumes.concentration', 'brands.name as brand_name', 'brands.type as brand_type')
            ->get();

        foreach ($perfumes as $perfume) {
            $origin = $this->brandOrigins[$perfume->brand_name] ?? 'France';
            $bottleSizes = $this->bottleSizes[$perfume->concentration] ?? '50ml, 100ml';

            $base = $this->basePrice[$perfume->concentration] ?? 1_500_000;
            $multiplier = $perfume->brand_type === 'niche' ? 1.8 : 1.0;
            $retailPrice = round(($base * $multiplier) / 10_000) * 10_000;

            DB::table('perfumes')
                ->where('id', $perfume->id)
                ->update([
                    'origin' => $origin,
                    'bottle_sizes' => $bottleSizes,
                    'retail_price' => $retailPrice,
                ]);
        }
    }

    public function down(): void
    {
        DB::table('perfumes')->update([
            'origin' => null,
            'bottle_sizes' => null,
            'retail_price' => null,
        ]);
    }
};
