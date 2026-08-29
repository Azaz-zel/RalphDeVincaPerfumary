<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Backfills editorial content (about/history/philosophy) for every existing
 * brand so /brand/{slug} can render real, brand-specific pages instead of
 * the single hardcoded "Dior" page it showed before.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach ($this->brands() as $slug => $data) {
            $brandId = DB::table('brands')->where('slug', $slug)->value('id');

            if (! $brandId) {
                continue;
            }

            DB::table('brands')->where('id', $brandId)->update([
                'tagline' => $data['tagline'],
                'founded_year' => $data['founded_year'],
                'founded_location' => $data['founded_location'],
                'founder' => $data['founder'],
                'about' => implode("\n\n", $data['about']),
                'hero_image' => $data['hero_image'] ?? null,
                'about_image' => $data['about_image'] ?? null,
                'philosophy_quote' => $data['philosophy_quote'],
                'philosophy_intro' => $data['philosophy_intro'],
                'philosophy_pillars' => json_encode($data['pillars']),
            ]);

            $milestones = [];

            foreach ($data['milestones'] as $order => $milestone) {
                $milestones[] = [
                    'brand_id' => $brandId,
                    'year' => $milestone[0],
                    'title' => $milestone[1],
                    'description' => $milestone[2],
                    'sort_order' => $order,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('brand_milestones')->where('brand_id', $brandId)->delete();
            DB::table('brand_milestones')->insert($milestones);
        }
    }

    public function down(): void
    {
        DB::table('brand_milestones')->truncate();

        DB::table('brands')->update([
            'tagline' => null,
            'founded_year' => null,
            'founded_location' => null,
            'founder' => null,
            'about' => null,
            'hero_image' => null,
            'about_image' => null,
            'philosophy_quote' => null,
            'philosophy_intro' => null,
            'philosophy_pillars' => null,
        ]);
    }

    private function brands(): array
    {
        return [

            'chanel' => [
                'tagline' => 'Founded 1910 by Coco Chanel — Parisian Fashion & Fragrance Maison',
                'founded_year' => '1910',
                'founded_location' => 'Paris, France',
                'founder' => 'Gabrielle "Coco" Chanel',
                'about' => [
                    'Chanel began as a Parisian millinery house opened by Gabrielle "Coco" Chanel, before growing into one of the most influential names in fashion and perfumery.',
                    'In 1921 the house released Chanel No. 5, the first fragrance built around abstract aldehydes rather than a single flower, forever changing how perfume was made.',
                    "Today Chanel continues to craft fragrances defined by understated elegance, pairing modern compositions such as Bleu de Chanel and Coco Mademoiselle with the house's century of savoir-faire.",
                ],
                'philosophy_quote' => 'Luxury must be comfortable, otherwise it is not luxury.',
                'philosophy_intro' => "Chanel's fragrances are built on the same principles that shaped its couture: simplicity, confidence, and understated refinement.",
                'pillars' => [
                    ['title' => 'Simplicity', 'description' => 'A Chanel fragrance is rarely loud — it favours clean, well-balanced compositions over excess.'],
                    ['title' => 'Modernity', 'description' => "Each generation of Chanel perfumery reinterprets the house's codes for the present moment."],
                    ['title' => 'Craftsmanship', 'description' => 'From aldehydes to absolutes, Chanel controls its own ingredient sourcing to protect quality.'],
                    ['title' => 'Timelessness', 'description' => 'Formulas are built to be worn for decades, not seasons.'],
                ],
                'milestones' => [
                    ['1910', 'A Parisian Beginning', 'Coco Chanel opens her first boutique on Rue Cambon, laying the foundation for the house.'],
                    ['1921', 'Chanel No. 5', 'Ernest Beaux composes Chanel No. 5, the fragrance that introduces aldehydic perfumery to the world.'],
                    ['1955', 'Pour Monsieur', "Chanel enters men's fragrance with its first masculine composition."],
                    ['2010', 'Bleu de Chanel', "A modern woody-aromatic signature launches and becomes one of the house's most recognised men's fragrances."],
                    ['Today', 'Coco Mademoiselle & Beyond', 'Chanel continues to balance heritage formulas with contemporary bestsellers like Coco Mademoiselle.'],
                ],
            ],

            'dior' => [
                'tagline' => 'Founded 1946 by Christian Dior — The House of Sauvage & Miss Dior',
                'founded_year' => '1946',
                'founded_location' => 'Paris, France',
                'founder' => 'Christian Dior',
                'about' => [
                    "Founded by Christian Dior in 1946, Dior has become one of the world's most celebrated luxury maisons. Renowned for its haute couture, craftsmanship, and refined aesthetic, the brand has continuously shaped the world of fashion and beauty.",
                    "Beyond fashion, Dior's fragrance creations have established a lasting legacy. From timeless classics to contemporary bestsellers, each perfume reflects the house's dedication to elegance, creativity, and exceptional quality.",
                    'Today, Dior continues to inspire fragrance enthusiasts worldwide by blending heritage with innovation, creating scents that are both sophisticated and unforgettable.',
                ],
                'hero_image' => 'images/brands/dior.jpg',
                'about_image' => 'images/brands/dior-boutique.jpg',
                'philosophy_quote' => 'Perfume is more than a fragrance. It is a reflection of identity.',
                'philosophy_intro' => 'Every Dior fragrance is created with the belief that scent should tell a story, evoke emotions, and become an extension of the person who wears it.',
                'pillars' => [
                    ['title' => 'Elegance', 'description' => "Timeless sophistication lies at the heart of every Dior creation, balancing classic heritage with contemporary refinement."],
                    ['title' => 'Craftsmanship', 'description' => 'From ingredient selection to bottle design, every detail reflects exceptional quality and meticulous artistry.'],
                    ['title' => 'Innovation', 'description' => 'While respecting its heritage, Dior constantly explores new creative directions that redefine modern perfumery.'],
                    ['title' => 'Emotion', 'description' => 'Every fragrance is designed to create memories, inspire confidence, and express individuality.'],
                ],
                'milestones' => [
                    ['1946', 'The Beginning', 'Christian Dior founded the House of Dior in Paris, introducing a new vision of elegance after World War II.'],
                    ['1947', 'Miss Dior', 'The first Dior fragrance, Miss Dior, was launched alongside the New Look collection.'],
                    ['1966', 'Eau Sauvage', "Dior introduced Eau Sauvage, one of the most influential men's fragrances ever created."],
                    ['1985', 'Poison', "Poison became an international icon and strengthened Dior's reputation in perfumery."],
                    ['2015', 'Sauvage', "Sauvage was launched, quickly becoming one of the best-selling men's fragrances worldwide."],
                    ['Today', 'Global Luxury House', 'Dior continues to lead the luxury industry through fashion, beauty, and exceptional fragrances.'],
                ],
            ],

            'hermes' => [
                'tagline' => 'Founded 1837 — Parisian House of Leather & Fragrance',
                'founded_year' => '1837',
                'founded_location' => 'Paris, France',
                'founder' => 'Thierry Hermès',
                'about' => [
                    "Hermès began in 1837 as a harness and saddlery workshop in Paris, built on a philosophy of exceptional craftsmanship that would later extend across leather goods, silk, and fragrance.",
                    "The house's perfume atelier is known for pairing natural materials with a spirit of freedom and simplicity, epitomised by Terre d'Hermès, a woody-mineral fragrance built around the contrast of earth and metal.",
                    'Hermès fragrances are composed in-house, giving the maison the same control over its perfumery as it holds over its leather craftsmanship.',
                ],
                'philosophy_quote' => 'Nothing is more beautiful than the true, revealed.',
                'philosophy_intro' => "Hermès perfumery favours raw, honest materials over ornamentation, echoing the house's craftsman origins.",
                'pillars' => [
                    ['title' => 'Craft Heritage', 'description' => "Every fragrance carries the same workshop discipline as the house's leather ateliers."],
                    ['title' => 'Natural Materials', 'description' => 'Hermès favours raw, recognisable ingredients over abstraction.'],
                    ['title' => 'Freedom', 'description' => 'Compositions are built to feel unforced, closer to nature than to trend.'],
                    ['title' => 'Continuity', 'description' => 'In-house perfumers work within the maison for decades, preserving a consistent language.'],
                ],
                'milestones' => [
                    ['1837', 'A Parisian Workshop', 'Thierry Hermès opens a harness workshop near the Grands Boulevards in Paris.'],
                    ['1951', "Eau d'Hermès", "The house's first fragrance is introduced, setting the tone for decades of in-house perfumery."],
                    ['2006', "Terre d'Hermès", "A defining woody-mineral fragrance is launched, becoming one of the house's most celebrated scents."],
                    ['Today', 'A Continued Craft', 'Hermès continues composing fragrances entirely in-house, alongside its leather and silk ateliers.'],
                ],
            ],

            'maison-francis-kurkdjian' => [
                'tagline' => 'Founded 2009 by Perfumer Francis Kurkdjian',
                'founded_year' => '2009',
                'founded_location' => 'Paris, France',
                'founder' => 'Francis Kurkdjian',
                'about' => [
                    'Maison Francis Kurkdjian was founded in 2009 by perfumer Francis Kurkdjian, who had already composed acclaimed fragrances for other houses before starting his own maison in Paris.',
                    'The house became widely known after the 2015 release of Baccarat Rouge 540, created in collaboration with the Baccarat crystal house, which grew into one of the most talked-about fragrances of the modern niche era.',
                    'MFK fragrances are known for their clarity and technical precision, favouring luminous, well-defined compositions over heavy layering.',
                ],
                'philosophy_quote' => 'A fragrance should be legible — never a fog.',
                'philosophy_intro' => 'MFK perfumery is built on precision: every note is chosen so it can still be recognised inside the finished composition.',
                'pillars' => [
                    ['title' => 'Precision', 'description' => 'Formulas favour clarity over density, so each material remains legible.'],
                    ['title' => 'Technique', 'description' => "Kurkdjian's classical training shows in balanced, exacting compositions."],
                    ['title' => 'Radiance', 'description' => 'Fragrances are built to project a clean signature rather than an obvious "cloud."'],
                    ['title' => 'Collaboration', 'description' => "The house partners with ateliers like Baccarat to extend its perfumery into objects."],
                ],
                'milestones' => [
                    ['2009', 'The Maison Opens', 'Francis Kurkdjian founds his own house in Paris after years composing for others.'],
                    ['2015', 'Baccarat Rouge 540', 'Created with Baccarat, the fragrance becomes a defining scent of the niche perfumery boom.'],
                    ['Today', 'A Modern Niche Icon', "Grand Soir and the wider MFK collection continue to expand the house's amber and floral repertoire."],
                ],
            ],

            'tom-ford' => [
                'tagline' => 'Founded 2005 by Tom Ford — Modern American Luxury',
                'founded_year' => '2005',
                'founded_location' => 'New York, United States',
                'founder' => 'Tom Ford',
                'about' => [
                    'Tom Ford launched his eponymous label in 2005 after leading creative direction at Gucci and Yves Saint Laurent, bringing a polished, unapologetically glamorous sensibility to fashion and fragrance.',
                    'The Private Blend collection, home to fragrances like Oud Wood and Neroli Portofino, positioned Tom Ford as a bridge between designer glamour and niche-level ingredient quality.',
                    'Tom Ford fragrances are known for rich, confident compositions — refined, but never quiet.',
                ],
                'philosophy_quote' => 'Dress impeccably and they will remember the woman.',
                'philosophy_intro' => 'Tom Ford brings the same sense of confident, editorial glamour to fragrance as he does to fashion.',
                'pillars' => [
                    ['title' => 'Confidence', 'description' => 'Fragrances are built to be noticed, not hidden.'],
                    ['title' => 'Richness', 'description' => 'Private Blend compositions favour dense, luxurious materials like oud and amber.'],
                    ['title' => 'Polish', 'description' => "Every release is presented with the same graphic, editorial precision as the brand's fashion line."],
                    ['title' => 'Modern Glamour', 'description' => 'The house updates classic opulence for a contemporary wardrobe.'],
                ],
                'milestones' => [
                    ['2005', 'The House Is Founded', 'Tom Ford launches his eponymous label in New York after leaving Gucci and YSL.'],
                    ['2007', 'Private Blend', 'An intimate, ingredient-driven fragrance collection launches, including Oud Wood.'],
                    ['Today', 'Neroli Portofino & Beyond', 'The Private Blend line continues to expand across woods, orientals, and citrus compositions.'],
                ],
            ],

            'byredo' => [
                'tagline' => 'Founded 2006 by Ben Gorham — Scandinavian Niche Perfumery',
                'founded_year' => '2006',
                'founded_location' => 'Stockholm, Sweden',
                'founder' => 'Ben Gorham',
                'about' => [
                    'Byredo was founded in Stockholm in 2006 by Ben Gorham, an artist with no formal training in perfumery, who set out to translate personal memories into scent.',
                    'Fragrances such as Gypsy Water and Mojave Ghost are built around specific memories or places rather than traditional perfumery categories, giving the house a distinctly narrative, art-driven identity.',
                    'Byredo has since expanded into leather goods and cosmetics, but fragrance remains its foundation, marked by a minimalist Scandinavian aesthetic.',
                ],
                'philosophy_quote' => 'Perfume as memory, not as trend.',
                'philosophy_intro' => 'Byredo treats scent as a personal, almost diaristic medium rather than a seasonal product.',
                'pillars' => [
                    ['title' => 'Memory', 'description' => 'Every fragrance is designed to anchor to a specific place or feeling.'],
                    ['title' => 'Minimalism', 'description' => 'Bottles and packaging favour restraint over ornamentation.'],
                    ['title' => 'Individuality', 'description' => 'Byredo positions its scents as personal signatures, not shared trends.'],
                    ['title' => 'Art Direction', 'description' => 'The house treats fragrance as part of a wider creative practice.'],
                ],
                'milestones' => [
                    ['2006', 'Stockholm Beginnings', 'Ben Gorham founds Byredo, drawing on personal memory rather than perfumery convention.'],
                    ['2008', 'Gypsy Water', "An early signature scent establishes the house's narrative approach to fragrance."],
                    ['2013', 'Mojave Ghost', "Inspired by the Mojave Desert, the fragrance becomes one of Byredo's most recognised scents."],
                    ['Today', 'Beyond Fragrance', 'Byredo expands into leather goods, cosmetics, and home while fragrance remains its core.'],
                ],
            ],

            'jo-malone-london' => [
                'tagline' => 'Founded 1994 by Jo Malone — English Fragrance Combining',
                'founded_year' => '1994',
                'founded_location' => 'London, United Kingdom',
                'founder' => 'Jo Malone',
                'about' => [
                    'Jo Malone founded her namesake house in London in 1994, building a following through a distinctly English approach to scent — clean, wearable, and designed to be layered.',
                    'The house popularised fragrance "combining," encouraging customers to layer colognes such as Wood Sage & Sea Salt with other scents in the range to create a personal blend.',
                    "In 1999, Jo Malone London was acquired by Estée Lauder, allowing the brand to expand globally while keeping its understated, quintessentially British identity.",
                ],
                'philosophy_quote' => 'Fragrance should be worn like a wardrobe — mixed, matched, and personal.',
                'philosophy_intro' => 'Jo Malone London treats scent as something to be combined and personalised, not worn as a single fixed signature.',
                'pillars' => [
                    ['title' => 'Simplicity', 'description' => 'Fragrances favour clean, single-note-led compositions.'],
                    ['title' => 'Layering', 'description' => 'Colognes are designed to be combined into a personal blend.'],
                    ['title' => 'Englishness', 'description' => 'The house draws heavily on British gardens, coastlines, and countryside.'],
                    ['title' => 'Versatility', 'description' => 'Light concentrations make the range easy to wear daily.'],
                ],
                'milestones' => [
                    ['1994', 'A London Boutique', 'Jo Malone opens her first shop, building a reputation through word of mouth.'],
                    ['1999', 'Acquired by Estée Lauder', 'The house joins the Estée Lauder Companies, enabling international growth.'],
                    ['Today', 'Wood Sage & Sea Salt and Beyond', "The house's coastal, garden-inspired colognes remain some of its most loved scents."],
                ],
            ],

            'giorgio-armani' => [
                'tagline' => 'Founded 1975 by Giorgio Armani — Milanese Fashion & Fragrance',
                'founded_year' => '1975',
                'founded_location' => 'Milan, Italy',
                'founder' => 'Giorgio Armani',
                'about' => [
                    'Giorgio Armani founded his fashion house in Milan in 1975, quickly becoming known for softly tailored, understated luxury that stood apart from the more structured fashion of the era.',
                    "In 1996, Acqua di Giò was launched and became one of the best-selling men's fragrances of all time, defining a generation's idea of a fresh, aquatic scent.",
                    "The Armani fragrance range has since grown to include the Code and Sì collections, each carrying the same quiet confidence that defines the house's fashion.",
                ],
                'philosophy_quote' => "Elegance is not about being noticed, it's about being remembered.",
                'philosophy_intro' => 'Armani fragrances favour understated, wearable elegance over dramatic statement scents.',
                'pillars' => [
                    ['title' => 'Understatement', 'description' => 'Fragrances are designed to be refined rather than loud.'],
                    ['title' => 'Wearability', 'description' => 'Compositions favour everyday versatility over occasion-only scents.'],
                    ['title' => 'Consistency', 'description' => 'The house maintains a recognisable "Armani" character across its collections.'],
                    ['title' => 'Modern Freshness', 'description' => "Aquatic and citrus accords remain central to the brand's identity."],
                ],
                'milestones' => [
                    ['1975', 'The House Is Founded', 'Giorgio Armani opens his fashion house in Milan.'],
                    ['1996', 'Acqua di Giò', 'The fragrance launches and becomes a defining aquatic scent of the era.'],
                    ['2004', 'Armani Code', "A darker, more seductive fragrance broadens the house's range."],
                    ['Today', 'Sì & Stronger With You', "Contemporary collections continue to expand Armani's fragrance wardrobe for men and women."],
                ],
            ],

            'yves-saint-laurent' => [
                'tagline' => 'Founded 1961 by Yves Saint Laurent — Parisian Rebellion & Elegance',
                'founded_year' => '1961',
                'founded_location' => 'Paris, France',
                'founder' => 'Yves Saint Laurent & Pierre Bergé',
                'about' => [
                    'Yves Saint Laurent founded his couture house in Paris in 1961 alongside Pierre Bergé, quickly becoming known for challenging convention — from Le Smoking to ready-to-wear.',
                    'That same spirit carried into fragrance: Opium (1977) was one of the boldest, most controversial orientals of its time, while Black Opium later reimagined that daring in a modern coffee-and-vanilla gourmand.',
                    'YSL fragrances remain defined by confidence and contrast — sweet against dark, classic against rebellious.',
                ],
                'philosophy_quote' => 'Fashions fade, style is eternal.',
                'philosophy_intro' => 'That same line shapes YSL fragrance as much as couture: bold, contrasting, and confidently styled.',
                'pillars' => [
                    ['title' => 'Boldness', 'description' => 'YSL fragrances rarely play it safe, favouring strong, memorable accords.'],
                    ['title' => 'Contrast', 'description' => 'Compositions often pair opposites — sweetness with darkness, freshness with intensity.'],
                    ['title' => 'Confidence', 'description' => 'Scents are designed for wearers who want to be noticed.'],
                    ['title' => 'Reinvention', 'description' => 'Classic YSL codes are continually reworked for new generations.'],
                ],
                'milestones' => [
                    ['1961', 'The House Is Founded', 'Yves Saint Laurent and Pierre Bergé open their Paris couture house.'],
                    ['1977', 'Opium', 'A daring oriental fragrance sparks controversy and becomes a modern classic.'],
                    ['2014', 'Black Opium', "A coffee-vanilla gourmand reimagines the house's rebellious spirit for a new generation."],
                    ['Today', 'Y & Black Opium', 'The house balances fresh, modern masculines with its iconic feminine orientals.'],
                ],
            ],

            'dolce-gabbana' => [
                'tagline' => 'Founded 1985 by Domenico Dolce & Stefano Gabbana — Sicilian-Italian Glamour',
                'founded_year' => '1985',
                'founded_location' => 'Milan, Italy',
                'founder' => 'Domenico Dolce & Stefano Gabbana',
                'about' => [
                    'Domenico Dolce and Stefano Gabbana founded their fashion house in Milan in 1985, drawing heavily on Sicilian heritage, Italian cinema, and Mediterranean sensuality.',
                    "The house's fragrances, including the Light Blue collection, translate that Mediterranean identity into fresh, sun-drenched compositions built around citrus and warmth.",
                    'D&G fragrances balance glamour with wearability, aiming for scents that feel both luxurious and easy to live in.',
                ],
                'philosophy_quote' => 'Fragrance should feel like an Italian summer, all year round.',
                'philosophy_intro' => "Dolce&Gabbana's perfumery leans on Sicilian sun, citrus, and warmth as much as its runway does.",
                'pillars' => [
                    ['title' => 'Heritage', 'description' => "Sicilian and Italian culture inform much of the house's imagery and scent direction."],
                    ['title' => 'Sensuality', 'description' => 'Compositions favour warm, skin-close materials.'],
                    ['title' => 'Freshness', 'description' => 'Citrus and marine accords remain central to the fragrance wardrobe.'],
                    ['title' => 'Glamour', 'description' => "Every release carries the house's cinematic, larger-than-life presentation."],
                ],
                'milestones' => [
                    ['1985', 'The House Is Founded', 'Domenico Dolce and Stefano Gabbana launch their label in Milan.'],
                    ['2001', 'Light Blue', "The fragrance launches and becomes emblematic of the house's Mediterranean freshness."],
                    ['Today', 'Light Blue Eau Intense', 'The collection is reworked into deeper, more intense compositions for modern wear.'],
                ],
            ],

            'parfums-de-marly' => [
                'tagline' => 'Founded 2009 by Julien Sprecher — French Heritage-Inspired Niche Perfumery',
                'founded_year' => '2009',
                'founded_location' => 'Paris, France',
                'founder' => 'Julien Sprecher',
                'about' => [
                    'Parfums de Marly was founded in 2009 by Julien Sprecher, named after the Château de Marly, the private retreat built for King Louis XIV.',
                    'The house positions itself as a bridge between 18th-century French court perfumery and contemporary tastes, favouring rich, opulent compositions.',
                    'Fragrances such as Delina are known for generous sillage and a distinctly maximalist, celebratory character.',
                ],
                'philosophy_quote' => 'Perfume as a return to the splendour of the French court.',
                'philosophy_intro' => 'Parfums de Marly filters 18th-century French opulence through a modern, maximalist lens.',
                'pillars' => [
                    ['title' => 'Opulence', 'description' => 'Compositions favour rich, generous materials over restraint.'],
                    ['title' => 'Heritage Imagery', 'description' => "The house draws on 18th-century French royal history for its identity."],
                    ['title' => 'Projection', 'description' => 'Fragrances are built to be felt across a room, not just up close.'],
                    ['title' => 'Modern Luxury', 'description' => 'Classical inspiration is filtered through a contemporary, niche presentation.'],
                ],
                'milestones' => [
                    ['2009', 'The House Is Founded', 'Julien Sprecher launches Parfums de Marly in Paris.'],
                    ['Early 2010s', 'A Niche Following', 'The house builds a following for its rich, maximalist florals and orientals.'],
                    ['Today', 'Delina & Beyond', "The house's feminine florals remain among its most celebrated releases."],
                ],
            ],

            'nishane' => [
                'tagline' => 'Founded 2012 in Istanbul — Contemporary Turkish Niche Perfumery',
                'founded_year' => '2012',
                'founded_location' => 'Istanbul, Turkey',
                'founder' => 'Mustafa Nishan Erkan',
                'about' => [
                    'Nishane was founded in Istanbul in 2012, bringing a distinctly Turkish perspective to the niche fragrance world at a time when the category was dominated by Western European houses.',
                    'The house is known for extrait-strength concentrations and hand-finished bottles, positioning its fragrances at the highest tier of intensity and craftsmanship.',
                    'Scents such as Ani and Hacivat draw on Anatolian culture and history, giving the house a distinct identity within contemporary niche perfumery.',
                ],
                'philosophy_quote' => 'Perfumery rooted in Anatolian craft and story.',
                'philosophy_intro' => "Nishane builds its identity on Turkish craft heritage and extrait-strength intensity.",
                'pillars' => [
                    ['title' => 'Intensity', 'description' => 'Nishane favours extrait-level concentrations for maximum longevity.'],
                    ['title' => 'Craft', 'description' => 'Bottles are hand-finished, reflecting artisanal production values.'],
                    ['title' => 'Storytelling', 'description' => 'Fragrance names and concepts draw on Turkish history and culture.'],
                    ['title' => 'Independence', 'description' => 'As a relatively young house, Nishane has built its identity outside traditional perfumery capitals.'],
                ],
                'milestones' => [
                    ['2012', 'Istanbul Origins', 'Nishane is founded, bringing Turkish perfumery into the global niche conversation.'],
                    ['Early Releases', 'Ani & Hacivat', "Early fragrances establish the house's rich, extrait-strength signature."],
                    ['Today', 'Global Niche Recognition', "Nishane's fragrances gain a following among niche perfumery enthusiasts worldwide."],
                ],
            ],

            'le-labo' => [
                'tagline' => 'Founded 2006 in New York — Handmade, City-Exclusive Perfumery',
                'founded_year' => '2006',
                'founded_location' => 'New York, United States',
                'founder' => 'Eddie Roschi & Fabrice Penot',
                'about' => [
                    'Le Labo was founded in New York in 2006 by Eddie Roschi and Fabrice Penot, both perfume industry veterans who wanted to strip fragrance back to its ingredients.',
                    "Each bottle is hand-blended and labelled at the point of sale, with the batch date and mixer's initials written directly on the label — a deliberate contrast to mass-produced perfumery.",
                    "Santal 33, one of the house's signatures, became one of the most widely recognised niche fragrances of the 2010s.",
                ],
                'philosophy_quote' => 'Perfume, unpackaged and unpretentious.',
                'philosophy_intro' => 'Le Labo strips away gloss and marketing in favour of raw materials and a hand-made ritual.',
                'pillars' => [
                    ['title' => 'Rawness', 'description' => 'Formulas favour recognisable, honest materials over polish.'],
                    ['title' => 'Craft Ritual', 'description' => 'Every bottle is hand-mixed and labelled to order in-store.'],
                    ['title' => 'Anti-Marketing', 'description' => 'Le Labo deliberately avoids glossy campaigns in favour of word of mouth.'],
                    ['title' => 'City Identity', 'description' => 'Certain fragrances were originally exclusive to specific city boutiques.'],
                ],
                'milestones' => [
                    ['2006', 'The Lab Opens', 'Eddie Roschi and Fabrice Penot found Le Labo in New York.'],
                    ['2011', 'Santal 33', 'The fragrance launches and becomes one of the defining niche scents of the decade.'],
                    ['Today', 'A Cult Following', "Le Labo's hand-labelled bottles remain instantly recognisable across its global boutiques."],
                ],
            ],

            'maison-margiela' => [
                'tagline' => 'Founded 1988 by Martin Margiela — Fragrance as Memory',
                'founded_year' => '1988',
                'founded_location' => 'Paris, France',
                'founder' => 'Martin Margiela',
                'about' => [
                    'Maison Margiela was founded in Paris in 1988 by Belgian designer Martin Margiela, known for deconstructed fashion and a deliberately anonymous design identity.',
                    "The house's Replica fragrance line, launched in 2012, translates specific memories — a walk by the fireplace, a jazz club at night — into scent, each bottle styled like a vintage postcard.",
                    'Fragrances such as By the Fireplace and Jazz Club are less about glamour and more about atmosphere, built to transport the wearer to a precise moment.',
                ],
                'philosophy_quote' => 'Perfume as a replica of memory.',
                'philosophy_intro' => 'Each Replica fragrance is built to recreate a specific remembered moment rather than project a single signature.',
                'pillars' => [
                    ['title' => 'Memory', 'description' => 'Each Replica scent recreates a specific moment or place.'],
                    ['title' => 'Anonymity', 'description' => 'The house favours concept over celebrity or overt branding.'],
                    ['title' => 'Atmosphere', 'description' => 'Fragrances aim to build a mood rather than a conventional "signature."'],
                    ['title' => 'Nostalgia', 'description' => 'Vintage-style packaging reinforces the sense of a remembered moment.'],
                ],
                'milestones' => [
                    ['1988', 'The House Is Founded', 'Martin Margiela establishes his Paris fashion house.'],
                    ['2012', 'Replica Launches', 'The fragrance line reimagines scent as captured memory, starting with By the Fireplace.'],
                    ['Today', 'Jazz Club & Beyond', "The Replica collection continues to expand its catalogue of remembered moments."],
                ],
            ],

            'guerlain' => [
                'tagline' => 'Founded 1828 by Pierre-François Guerlain — One of Perfumery\'s Oldest Houses',
                'founded_year' => '1828',
                'founded_location' => 'Paris, France',
                'founder' => 'Pierre-François Pascal Guerlain',
                'about' => [
                    'Guerlain was founded in Paris in 1828, making it one of the oldest continuously operating perfume houses in the world, with a legacy spanning generations of in-house perfumers.',
                    'The house introduced the "Guerlinade," a recognisable base accord of vanilla, iris, and tonka bean that has appeared in various forms across many of its historic fragrances, including Shalimar (1925).',
                    'Modern releases such as Mon Guerlain continue that legacy, translating the house\'s signature gourmand-floral warmth for contemporary wear.',
                ],
                'philosophy_quote' => 'Perfumery as a family inheritance, passed down for generations.',
                'philosophy_intro' => "Guerlain's perfumery is built on lineage — a recognisable signature carried across two centuries.",
                'pillars' => [
                    ['title' => 'Lineage', 'description' => "Guerlain's perfumers have historically come from within the Guerlain family itself."],
                    ['title' => 'The Guerlinade', 'description' => "A recognisable signature base accord links many of the house's fragrances across centuries."],
                    ['title' => 'Craftsmanship', 'description' => "Formulas are built with the same rigour as the house's earliest 19th-century creations."],
                    ['title' => 'Continuity', 'description' => 'Guerlain balances reviving historic formulas with new releases like Mon Guerlain.'],
                ],
                'milestones' => [
                    ['1828', 'A Parisian House Is Born', 'Pierre-François Guerlain opens his perfumery in Paris.'],
                    ['1925', 'Shalimar', "One of perfumery's most influential orientals is created, defining the house's identity for a century."],
                    ['2017', 'Mon Guerlain', "A modern fragrance updates the house's warm, gourmand character for a new generation."],
                    ['Today', 'A Living Legacy', 'Guerlain continues operating with generations of in-house perfumery expertise.'],
                ],
            ],

            'diptyque' => [
                'tagline' => 'Founded 1961 in Paris — From Candles to Niche Perfumery',
                'founded_year' => '1961',
                'founded_location' => 'Paris, France',
                'founder' => 'Christiane Gautrot, Yves Coueslant & Desmond Knox-Leet',
                'about' => [
                    'Diptyque began in 1961 as a boutique on Boulevard Saint-Germain in Paris, founded by three friends — a painter, a set designer, and a stage director — selling furnishing fabrics, candles, and curiosities.',
                    "Fragrance followed naturally from the house's scented candles, with fig-leaf scented Philosykos and woody Tam Dao becoming two of its most enduring signatures.",
                    'Diptyque fragrances favour a single, clearly recognisable material — fig, incense, cedar — rather than complex blends, giving each scent a distinct identity.',
                ],
                'philosophy_quote' => 'One material, fully explored.',
                'philosophy_intro' => "Diptyque's perfumery favours focus over complexity — one true material, explored in depth.",
                'pillars' => [
                    ['title' => 'Focus', 'description' => 'Fragrances are typically built around one clear, dominant material.'],
                    ['title' => 'Artistic Origins', 'description' => "The house's founders came from art and design, not perfumery."],
                    ['title' => 'Botanical Honesty', 'description' => 'Scents like Philosykos aim to feel true to their namesake plant.'],
                    ['title' => 'Quiet Confidence', 'description' => 'Diptyque favours understated packaging over loud branding.'],
                ],
                'milestones' => [
                    ['1961', 'A Left Bank Boutique', 'Three friends open Diptyque on Boulevard Saint-Germain, Paris.'],
                    ['1968', 'The First Candle', "Diptyque's scented candles launch, laying the groundwork for its fragrance line."],
                    ['1996', 'Philosykos', "The fig-leaf fragrance becomes one of the house's most recognised scents."],
                    ['Today', 'Tam Dao & Beyond', "Diptyque's single-material philosophy continues across its fragrance range."],
                ],
            ],

            'frederic-malle' => [
                'tagline' => "Founded 2000 by Frédéric Malle — The Perfumer's Publisher",
                'founded_year' => '2000',
                'founded_location' => 'Paris, France',
                'founder' => 'Frédéric Malle',
                'about' => [
                    'Frédéric Malle, grandson of the founder of Parfums Christian Dior, launched Editions de Parfums Frédéric Malle in 2000 with a radical idea: crediting each perfumer by name, like an author.',
                    'Rather than working in-house, Malle commissions top independent "noses" and gives them full creative freedom, publishing the results as he would a book.',
                    'Portrait of a Lady, composed by Dominique Ropion, exemplifies the house\'s approach — an uncompromising, maximalist rose fragrance built without commercial constraints.',
                ],
                'philosophy_quote' => 'A publisher of perfume, not a brand that owns it.',
                'philosophy_intro' => 'Every Frédéric Malle fragrance credits its perfumer by name, treating scent as authored work.',
                'pillars' => [
                    ['title' => 'Authorship', 'description' => 'Every fragrance credits its perfumer by name, like a book credits its author.'],
                    ['title' => 'Creative Freedom', 'description' => 'Perfumers are given full autonomy over ingredients and cost.'],
                    ['title' => 'Editorial Curation', 'description' => 'Malle "publishes" a small, carefully selected catalogue rather than a mass line.'],
                    ['title' => 'Uncompromising Formulas', 'description' => 'Concentration and quality are prioritised over cost efficiency.'],
                ],
                'milestones' => [
                    ['2000', 'The House Is Founded', 'Frédéric Malle launches Editions de Parfums, crediting perfumers by name for the first time.'],
                    ['2010', 'Portrait of a Lady', 'Dominique Ropion composes a maximalist rose fragrance that becomes a modern niche classic.'],
                    ['Today', 'A Curated Catalogue', "The house continues publishing a small, uncompromising collection from the industry's most respected perfumers."],
                ],
            ],

            'nasomatto' => [
                'tagline' => 'Founded by Alessandro Gualtieri — Avant-Garde Italian Perfumery',
                'founded_year' => 'Late 2000s',
                'founded_location' => 'Italy',
                'founder' => 'Alessandro Gualtieri',
                'about' => [
                    'Nasomatto was founded by Italian perfumer Alessandro Gualtieri, who previously worked on fragrances for larger fashion houses before starting an intentionally raw, unconventional label of his own.',
                    'The name translates loosely to "crazy nose," reflecting the house\'s willingness to build fragrances around unusual, sometimes challenging accords rather than conventional wearability.',
                    'Black Afgano, one of its most talked-about scents, is built around dense hashish, incense, and dried fruit notes — a deliberately provocative composition.',
                ],
                'philosophy_quote' => 'Perfume without compromise, without a safety net.',
                'philosophy_intro' => 'Nasomatto builds fragrance around provocation and intensity rather than easy wearability.',
                'pillars' => [
                    ['title' => 'Provocation', 'description' => 'Nasomatto favours unconventional accords over easy wearability.'],
                    ['title' => 'Rawness', 'description' => 'Packaging and presentation are stripped back and industrial.'],
                    ['title' => 'Independence', 'description' => 'The house operates outside typical fragrance-industry conventions.'],
                    ['title' => 'Intensity', 'description' => 'Fragrances are built for strong, memorable impact.'],
                ],
                'milestones' => [
                    ['Late 2000s', 'An Unconventional Debut', 'Alessandro Gualtieri founds Nasomatto after years composing for other houses.'],
                    ['Black Afgano', 'A Cult Signature', 'The fragrance becomes a cult favourite for its dense, provocative character.'],
                    ['Today', 'A Niche Cult Following', 'Nasomatto remains known among enthusiasts for its uncompromising, avant-garde style.'],
                ],
            ],

            'escentric-molecules' => [
                'tagline' => 'Founded 2006 by Geza Schoen — Perfumery Built on a Single Molecule',
                'founded_year' => '2006',
                'founded_location' => 'Berlin, Germany',
                'founder' => 'Geza Schoen',
                'about' => [
                    'Escentric Molecules was founded in Berlin in 2006 by perfumer Geza Schoen, built on a radical premise: a fragrance composed around a single aroma molecule.',
                    "Molecule 01 is built almost entirely around Iso E Super, a synthetic material known for its soft, skin-like woody quality, worn as a nearly invisible, personal scent that reacts uniquely with each wearer's skin chemistry.",
                    'The house challenged conventional ideas of what a fragrance needed to be, favouring concept and minimalism over traditional pyramids of top, middle, and base notes.',
                ],
                'philosophy_quote' => 'One molecule. Infinite reactions.',
                'philosophy_intro' => 'Escentric Molecules rejects the conventional fragrance pyramid in favour of radical minimalism.',
                'pillars' => [
                    ['title' => 'Minimalism', 'description' => 'Fragrances are stripped to as few materials as possible.'],
                    ['title' => 'Skin Chemistry', 'description' => 'Scents are designed to interact differently with each individual wearer.'],
                    ['title' => 'Concept Over Convention', 'description' => 'The house rejects traditional top/middle/base note structures.'],
                    ['title' => 'Modern Synthesis', 'description' => 'Escentric Molecules embraces synthetic materials as legitimate perfumery ingredients, not lesser substitutes.'],
                ],
                'milestones' => [
                    ['2006', 'A Radical Concept', 'Geza Schoen founds Escentric Molecules around the idea of single-molecule perfumery.'],
                    ['2006', 'Molecule 01', 'The debut fragrance, built on Iso E Super, redefines minimalist perfumery.'],
                    ['Today', 'A Cult Minimalist Icon', 'Molecule 01 remains one of the most distinctive fragrances in modern niche perfumery.'],
                ],
            ],

            'xerjoff' => [
                'tagline' => 'Founded 2003 by Sergio Momo — Italian Maximalist Luxury Perfumery',
                'founded_year' => '2003',
                'founded_location' => 'Turin, Italy',
                'founder' => 'Sergio Momo',
                'about' => [
                    'Xerjoff was founded in Turin, Italy, in 2003 by Sergio Momo, quickly developing a reputation for opulent, richly layered fragrances housed in ornate, jewel-like bottles.',
                    "The house draws on Italian craftsmanship and Mediterranean history for its imagery, with collections referencing places from Alexandria to the house's home city of Turin.",
                    'With a large and varied catalogue — from Erba Pura to the Alexandria series — Xerjoff has become known for maximalist compositions and equally maximalist presentation.',
                ],
                'philosophy_quote' => 'Luxury perfumery without restraint.',
                'philosophy_intro' => 'Xerjoff favours richness and scale over restraint, across both scent and presentation.',
                'pillars' => [
                    ['title' => 'Opulence', 'description' => 'Xerjoff favours rich, layered compositions over minimalism.'],
                    ['title' => 'Italian Craft', 'description' => "Bottles and packaging reflect a distinctly Italian sense of luxury."],
                    ['title' => 'Storied Naming', 'description' => 'Collections are often built around historical places and eras.'],
                    ['title' => 'Scale', 'description' => 'A large, varied catalogue lets the house explore many olfactory directions at once.'],
                ],
                'milestones' => [
                    ['2003', 'Turin Origins', 'Sergio Momo founds Xerjoff in Turin, Italy.'],
                    ['Alexandria', 'A Signature Series', "A series inspired by the ancient city becomes one of the house's most celebrated lines."],
                    ['Today', 'An Expansive Catalogue', "Xerjoff continues to grow one of niche perfumery's largest and most maximalist ranges."],
                ],
            ],

            'creed' => [
                'tagline' => 'Est. 1760 — A Historic House Reimagined for Modern Niche Perfumery',
                'founded_year' => '1760',
                'founded_location' => 'London, England',
                'founder' => 'James Henry Creed',
                'about' => [
                    'Creed traces its roots to 1760 in London, where James Henry Creed is said to have founded a tailoring and perfumery house serving European royal courts.',
                    'The modern Creed brand, guided for decades by Olivier Creed and later his son Erwin Creed, positions itself at the intersection of historic prestige and contemporary niche fragrance culture.',
                    'Aventus, released in 2010, became one of the most influential fragrances of the modern niche era — a fruity-smoky composition that inspired countless imitators across the industry.',
                ],
                'philosophy_quote' => 'Perfumery as a royal inheritance.',
                'philosophy_intro' => 'Creed leans on centuries of claimed royal association to frame its bold, batch-crafted fragrances.',
                'pillars' => [
                    ['title' => 'Prestige Imagery', 'description' => 'Creed leans heavily on its claimed royal and historic associations.'],
                    ['title' => 'Craft Fragrance', 'description' => 'Batch-based production is used to emphasise freshness and exclusivity.'],
                    ['title' => 'Bold Signatures', 'description' => 'Fragrances like Aventus are designed to be immediately recognisable.'],
                    ['title' => 'Modern Reach', 'description' => 'Despite its historic framing, Creed has become one of the most widely worn niche houses today.'],
                ],
                'milestones' => [
                    ['1760', 'Historic Origins', 'Creed traces its founding to James Henry Creed in London.'],
                    ['Family Stewardship', 'Generations of Creeds', 'The house passes through generations of the Creed family, including Olivier and Erwin Creed.'],
                    ['2010', 'Aventus', 'The fragrance launches and becomes one of the most influential and widely imitated scents of modern niche perfumery.'],
                    ['Today', 'A Global Niche Presence', "Creed's catalogue, from Green Irish Tweed to Aventus, remains among the most recognised in niche perfumery."],
                ],
            ],

            'jean-paul-gaultier' => [
                'tagline' => "Founded by Jean Paul Gaultier — Fashion's Enfant Terrible",
                'founded_year' => '1970s',
                'founded_location' => 'Paris, France',
                'founder' => 'Jean Paul Gaultier',
                'about' => [
                    'Jean Paul Gaultier built his fashion house in Paris through the late 1970s and 1980s, earning a reputation as fashion\'s "enfant terrible" for his playful, boundary-pushing designs — including the iconic corset gowns.',
                    'That same irreverence shaped his fragrances: Classique (1993), bottled in the shape of a corseted torso, and Le Male (1995), bottled as a sailor\'s torso, became two of the most recognisable fragrance bottles ever made.',
                    "The house has continued that bold visual language through Scandal and later Le Male collections, treating the bottle itself as part of the fragrance's identity.",
                ],
                'philosophy_quote' => 'Fashion is not something that exists in dresses only.',
                'philosophy_intro' => 'Gaultier treats the fragrance bottle itself as a design statement, as playful and bold as his runway.',
                'pillars' => [
                    ['title' => 'Provocation', 'description' => 'Gaultier fragrances embrace bold, sometimes cheeky concepts.'],
                    ['title' => 'Iconic Bottles', 'description' => 'Packaging is treated as inseparable from the fragrance itself.'],
                    ['title' => 'Gender Play', 'description' => 'The house has long blurred conventional masculine/feminine fragrance codes.'],
                    ['title' => 'Wit', 'description' => "Gaultier's fragrances rarely take themselves entirely seriously."],
                ],
                'milestones' => [
                    ['1970s', 'A Bold New Designer', "Jean Paul Gaultier establishes his reputation as fashion's provocateur."],
                    ['1993', 'Classique', 'The corset-bottle fragrance becomes an instant design icon.'],
                    ['1995', 'Le Male', "The sailor-torso bottle and its fresh, oriental scent become one of the best-selling men's fragrances ever."],
                    ['Today', 'Scandal & Beyond', 'The house continues its bold visual and olfactory language across new collections.'],
                ],
            ],

            'clive-christian' => [
                'tagline' => 'Founded 1999 by Clive Christian — British Ultra-Luxury Perfumery',
                'founded_year' => '1999',
                'founded_location' => 'England, United Kingdom',
                'founder' => 'Clive Christian',
                'about' => [
                    'Clive Christian founded his eponymous house in 1999 after acquiring the Crown Perfumery Company, a historic English perfumer originally established in 1872 and once holder of a Royal Warrant.',
                    'The house positioned itself firmly at the top of the luxury market, with No. 1 once marketed as one of the most expensive perfumes in the world, presented in Baccarat crystal bottles topped with a crown stopper.',
                    "Collections such as 1872, named for the Crown Perfumery's founding year, and the X and Original ranges continue that maximalist, distinctly British approach to luxury fragrance.",
                ],
                'philosophy_quote' => 'Perfumery as the pinnacle of British luxury.',
                'philosophy_intro' => 'Clive Christian positions fragrance at the absolute top tier of luxury, in materials and in presentation alike.',
                'pillars' => [
                    ['title' => 'Ultra-Luxury', 'description' => 'Clive Christian positions itself among the most expensive perfumery on the market.'],
                    ['title' => 'British Heritage', 'description' => 'The house draws on its historic Crown Perfumery lineage and royal associations.'],
                    ['title' => 'Rare Materials', 'description' => 'Formulas favour costly, high-concentration ingredients.'],
                    ['title' => 'Presentation', 'description' => "Crystal bottles and crown stoppers reinforce the house's luxury positioning."],
                ],
                'milestones' => [
                    ['1872', 'Crown Perfumery Founded', 'The historic English house that Clive Christian would later acquire is established.'],
                    ['1999', 'The House Relaunches', 'Clive Christian acquires Crown Perfumery and relaunches it as an ultra-luxury brand.'],
                    ['No. 1', 'A Landmark Fragrance', 'The fragrance is marketed as one of the most expensive perfumes in the world.'],
                    ['Today', '1872 & the X Collection', 'The house continues expanding its maximalist, heritage-driven ranges.'],
                ],
            ],

            'kilian' => [
                'tagline' => 'Founded 2007 by Kilian Hennessy — Refillable Luxury Perfumery',
                'founded_year' => '2007',
                'founded_location' => 'Paris, France',
                'founder' => 'Kilian Hennessy',
                'about' => [
                    'Kilian Hennessy, a member of the Hennessy cognac family, founded his eponymous fragrance house — By Kilian — in Paris in 2007, bringing a spirits-industry sense of craftsmanship to perfumery.',
                    'The house built its identity around refillable, jewel-like flacons with lacquered boxes and leather straps, positioning fragrance closer to fine accessories than to disposable bottles.',
                    "Collections like Good Girl Gone Bad and Angels' Share (named after the portion of cognac lost to evaporation during ageing) reflect the house's blend of sensuality and heritage craftsmanship.",
                ],
                'philosophy_quote' => 'Perfume as a fine object, made to be refilled and kept.',
                'philosophy_intro' => "Kilian's spirits-industry background shapes a house built around refillable, keepsake bottles.",
                'pillars' => [
                    ['title' => 'Sustainability by Design', 'description' => "Refillable bottles were part of the house's identity from the start."],
                    ['title' => 'Heritage Craft', 'description' => "Kilian Hennessy's background in cognac-making informs the house's material sensibility."],
                    ['title' => 'Sensuality', 'description' => 'Fragrances lean into rich, indulgent, often gourmand-oriental compositions.'],
                    ['title' => 'Object Design', 'description' => 'Bottles, boxes, and straps are designed to feel like fine accessories.'],
                ],
                'milestones' => [
                    ['2007', 'The House Is Founded', 'Kilian Hennessy launches By Kilian in Paris.'],
                    ['Good Girl Gone Bad', 'A Signature Oriental', "The fragrance becomes one of the house's most recognised feminine orientals."],
                    ["Angels' Share", 'A Cognac-Inspired Scent', "Named after the cognac-ageing term, the fragrance reflects the house's spirits heritage."],
                    ['Today', 'A Refillable Legacy', "Kilian's flacon-refill system remains central to its luxury positioning."],
                ],
            ],

        ];
    }
};
