<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Corrects brand "about" content that was written from general background
 * knowledge (not web-verified) once it was fact-checked via web research.
 * Fixes missing co-founders, wrong founding years/locations, a mislabeled
 * founder's role, and reframes Creed's disputed 1760 founding claim as
 * brand mythology rather than presenting it as settled fact.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach ($this->corrections() as $slug => $fields) {
            $brandId = DB::table('brands')->where('slug', $slug)->value('id');

            if (! $brandId) {
                continue;
            }

            $update = collect($fields)->except('milestones')->all();

            if (isset($update['about'])) {
                $update['about'] = implode("\n\n", $update['about']);
            }

            if ($update) {
                DB::table('brands')->where('id', $brandId)->update($update);
            }

            foreach ($fields['milestones'] ?? [] as $milestone) {
                $this->applyMilestone($brandId, $milestone);
            }
        }
    }

    public function down(): void
    {
        // Fact corrections; not meaningfully reversible.
    }

    /**
     * $milestone is either:
     * ['match' => 'Old Title', 'year' => ..., 'title' => ..., 'description' => ...]  (update existing row matched by title)
     * ['insert' => true, 'year' => ..., 'title' => ..., 'description' => ..., 'sort_order' => N]  (new row)
     */
    private function applyMilestone(int $brandId, array $milestone): void
    {
        if (! empty($milestone['insert'])) {
            DB::table('brand_milestones')->insert([
                'brand_id' => $brandId,
                'year' => $milestone['year'],
                'title' => $milestone['title'],
                'description' => $milestone['description'],
                'sort_order' => $milestone['sort_order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return;
        }

        DB::table('brand_milestones')
            ->where('brand_id', $brandId)
            ->where('title', $milestone['match'])
            ->update([
                'year' => $milestone['year'],
                'title' => $milestone['title'],
                'description' => $milestone['description'],
            ]);
    }

    private function corrections(): array
    {
        return [

            'maison-francis-kurkdjian' => [
                'tagline' => 'Founded 2009 by Francis Kurkdjian & Marc Chaya',
                'founder' => 'Francis Kurkdjian & Marc Chaya',
                'about' => [
                    'Maison Francis Kurkdjian was founded in 2009 by perfumer Francis Kurkdjian together with his business partner Marc Chaya, after Kurkdjian had already composed acclaimed fragrances for other houses.',
                    'The house became widely known after the 2015 release of Baccarat Rouge 540, created in collaboration with the Baccarat crystal house, which grew into one of the most talked-about fragrances of the modern niche era.',
                    'MFK fragrances are known for their clarity and technical precision, favouring luminous, well-defined compositions over heavy layering.',
                ],
                'milestones' => [
                    ['match' => 'The Maison Opens', 'year' => '2009', 'title' => 'The Maison Opens', 'description' => 'Francis Kurkdjian and Marc Chaya found their own house in Paris after years of Kurkdjian composing for others.'],
                ],
            ],

            'byredo' => [
                'milestones' => [
                    ['match' => 'Mojave Ghost', 'year' => '2014', 'title' => 'Mojave Ghost', 'description' => "Inspired by the Mojave Desert, the fragrance becomes one of Byredo's most recognised scents."],
                ],
            ],

            'giorgio-armani' => [
                'founder' => 'Giorgio Armani & Sergio Galeotti',
                'about' => [
                    'Giorgio Armani co-founded his fashion house in Milan in 1975 with business partner Sergio Galeotti, quickly becoming known for softly tailored, understated luxury that stood apart from the more structured fashion of the era.',
                    "In 1996, Acqua di Giò was launched and became one of the best-selling men's fragrances of all time, defining a generation's idea of a fresh, aquatic scent.",
                    "The Armani fragrance range has since grown to include the Code and Sì collections, each carrying the same quiet confidence that defines the house's fashion.",
                ],
                'milestones' => [
                    ['match' => 'The House Is Founded', 'year' => '1975', 'title' => 'The House Is Founded', 'description' => 'Giorgio Armani and Sergio Galeotti open the fashion house in Milan.'],
                ],
            ],

            'nishane' => [
                'founder' => 'Murat Katran & Mert Güzel',
                'about' => [
                    'Nishane was founded in Istanbul in 2012 by Murat Katran and Mert Güzel, bringing a distinctly Turkish perspective to the niche fragrance world at a time when the category was dominated by Western European houses.',
                    'The house is known for extrait-strength concentrations and hand-finished bottles, positioning its fragrances at the highest tier of intensity and craftsmanship.',
                    'Scents such as Ani and Hacivat draw on Anatolian culture and history, giving the house a distinct identity within contemporary niche perfumery.',
                ],
                'milestones' => [
                    ['match' => 'Istanbul Origins', 'year' => '2012', 'title' => 'Istanbul Origins', 'description' => 'Murat Katran and Mert Güzel found Nishane, bringing Turkish perfumery into the global niche conversation.'],
                ],
            ],

            'maison-margiela' => [
                'founder' => 'Martin Margiela & Jenny Meirens',
                'about' => [
                    'Maison Margiela was founded in Paris in 1988 by Belgian designer Martin Margiela together with his business partner Jenny Meirens, known for deconstructed fashion and a deliberately anonymous design identity.',
                    "The house's Replica fragrance line launched in 2012, translating specific memories into scent, each bottle styled like a vintage postcard; By the Fireplace and Jazz Club joined the collection in the years that followed.",
                    'Fragrances such as By the Fireplace and Jazz Club are less about glamour and more about atmosphere, built to transport the wearer to a precise moment.',
                ],
                'milestones' => [
                    ['match' => 'The House Is Founded', 'year' => '1988', 'title' => 'The House Is Founded', 'description' => 'Martin Margiela and Jenny Meirens establish the Paris fashion house.'],
                    ['match' => 'Replica Launches', 'year' => '2012', 'title' => 'Replica Launches', 'description' => 'The fragrance line reimagines scent as captured memory, debuting with scents like Beach Walk and Lazy Sunday Morning.'],
                    ['match' => 'Jazz Club & Beyond', 'year' => '2013–2015', 'title' => 'Jazz Club & By the Fireplace', 'description' => 'Jazz Club (2013) and By the Fireplace (2015) join the Replica collection, becoming two of its most recognised scents.'],
                ],
            ],

            'diptyque' => [
                'founder' => 'Christiane Gautrot, Yves Coueslant & Desmond Knox-Leet',
                'about' => [
                    'Diptyque began in 1961 as a boutique on Boulevard Saint-Germain in Paris, founded by three friends — a painter, a stage designer, and a textile designer — selling furnishing fabrics, candles, and curiosities.',
                    "Fragrance followed naturally from the house's scented candles, with fig-leaf scented Philosykos and woody Tam Dao becoming two of its most enduring signatures.",
                    'Diptyque fragrances favour a single, clearly recognisable material — fig, incense, cedar — rather than complex blends, giving each scent a distinct identity.',
                ],
                'milestones' => [
                    ['match' => 'The First Candle', 'year' => '1963', 'title' => 'The First Candle', 'description' => "Diptyque's scented candles launch, laying the groundwork for its fragrance line."],
                    ['insert' => true, 'year' => '1968', 'title' => "First Perfume: L'Eau", 'description' => "Diptyque releases its first eau de toilette, L'Eau, extending the house beyond candles.", 'sort_order' => 1],
                ],
            ],

            'nasomatto' => [
                'tagline' => 'Founded 2007 by Alessandro Gualtieri — Avant-Garde Perfumery from Amsterdam',
                'founded_year' => '2007',
                'founded_location' => 'Amsterdam, Netherlands',
                'about' => [
                    'Nasomatto was founded in Amsterdam in 2007 by Italian perfumer Alessandro Gualtieri, who previously worked on fragrances for larger fashion houses before starting an intentionally raw, unconventional label of his own.',
                    'The name translates loosely to "crazy nose," reflecting the house\'s willingness to build fragrances around unusual, sometimes challenging accords rather than conventional wearability.',
                    'Black Afgano, one of its most talked-about scents, is built around dense hashish, incense, and dried fruit notes — a deliberately provocative composition.',
                ],
                'milestones' => [
                    ['match' => 'An Unconventional Debut', 'year' => '2007', 'title' => 'An Unconventional Debut', 'description' => 'Alessandro Gualtieri founds Nasomatto in Amsterdam after years composing for other houses.'],
                ],
            ],

            'escentric-molecules' => [
                'tagline' => 'Founded 2006 by Geza Schoen & Paul White — Perfumery Built on a Single Molecule',
                'founded_location' => 'London, United Kingdom',
                'founder' => 'Geza Schoen & Paul White',
                'about' => [
                    'Escentric Molecules was founded in London in 2006 by perfumer Geza Schoen and creative director Paul White, built on a radical premise: a fragrance composed around a single aroma molecule.',
                    "Molecule 01 is built almost entirely around Iso E Super, a synthetic material known for its soft, skin-like woody quality, worn as a nearly invisible, personal scent that reacts uniquely with each wearer's skin chemistry.",
                    'The house challenged conventional ideas of what a fragrance needed to be, favouring concept and minimalism over traditional pyramids of top, middle, and base notes.',
                ],
                'milestones' => [
                    ['match' => 'A Radical Concept', 'year' => '2006', 'title' => 'A Radical Concept', 'description' => 'Geza Schoen and Paul White found Escentric Molecules around the idea of single-molecule perfumery.'],
                ],
            ],

            'xerjoff' => [
                'tagline' => 'Founded 2007 by Sergio Momo & Dominique Salvo — Italian Maximalist Luxury Perfumery',
                'founded_year' => '2007',
                'founder' => 'Sergio Momo & Dominique Salvo',
                'about' => [
                    'Xerjoff was founded in Turin, Italy, in 2007 by Sergio Momo and Dominique Salvo, quickly developing a reputation for opulent, richly layered fragrances housed in ornate, jewel-like bottles.',
                    "The house draws on Italian craftsmanship and Mediterranean history for its imagery, with collections referencing places from Alexandria to the house's home city of Turin.",
                    'With a large and varied catalogue — from Erba Pura to the Alexandria series — Xerjoff has become known for maximalist compositions and equally maximalist presentation.',
                ],
                'milestones' => [
                    ['match' => 'Turin Origins', 'year' => '2007', 'title' => 'Turin Origins', 'description' => 'Sergio Momo and Dominique Salvo found Xerjoff in Turin, Italy.'],
                ],
            ],

            'creed' => [
                'tagline' => "Est. 1760 (Disputed) — A Modern Niche House Built on a Contested Origin Story",
                'about' => [
                    'Creed markets itself as tracing back to 1760 in London, when James Henry Creed is said to have founded a tailoring and perfumery house serving European royal courts — though independent historians have found no archival evidence confirming the company existed before the 20th century, making this a brand-told origin story rather than a documented fact.',
                    'The verifiably documented modern Creed brand was built from the 1970s onward by Olivier Creed and later his son Erwin Creed, who position the house at the intersection of historic prestige and contemporary niche fragrance culture.',
                    'Aventus, released in 2010, became one of the most influential fragrances of the modern niche era — a fruity-smoky composition that inspired countless imitators across the industry.',
                ],
                'philosophy_intro' => 'Creed leans on a contested claim of centuries-old royal association to frame its bold, batch-crafted fragrances.',
                'milestones' => [
                    ['match' => 'Historic Origins', 'year' => '1760', 'title' => 'A Contested Origin', 'description' => "Creed's own history dates the house to 1760 under James Henry Creed in London, though no independent historical record has been found to confirm this — most fragrance historians treat it as unverified brand lore."],
                    ['match' => 'Generations of Creeds', 'year' => '1970s–Today', 'title' => 'The Verifiable Modern House', 'description' => 'Olivier Creed and his son Erwin Creed build the modern, documented Creed brand from the 1970s onward.'],
                ],
            ],

            'jean-paul-gaultier' => [
                'tagline' => "Debut Collection 1976, House Founded 1982 — Fashion's Enfant Terrible",
                'founded_year' => '1982',
                'about' => [
                    'Jean Paul Gaultier presented his first ready-to-wear collection in Paris in 1976 and formally founded his eponymous fashion house in 1982, earning a reputation as fashion\'s "enfant terrible" for his playful, boundary-pushing designs — including the iconic corset gowns.',
                    'That same irreverence shaped his fragrances: Classique (1993), bottled in the shape of a corseted torso, and Le Male (1995), bottled as a sailor\'s torso, became two of the most recognisable fragrance bottles ever made.',
                    "The house has continued that bold visual language through Scandal and later Le Male collections, treating the bottle itself as part of the fragrance's identity.",
                ],
                'milestones' => [
                    ['match' => 'A Bold New Designer', 'year' => '1976', 'title' => 'A Bold Debut', 'description' => 'Jean Paul Gaultier presents his first ready-to-wear collection in Paris.'],
                    ['insert' => true, 'year' => '1982', 'title' => 'The House Is Founded', 'description' => 'Gaultier formally establishes his eponymous fashion house.', 'sort_order' => 1],
                ],
            ],

        ];
    }
};
