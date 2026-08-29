<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Replaces placeholder/guessed perfume data with real facts gathered via
 * web research (Fragrantica, brand official sites, retailers) — real note
 * pyramids, concentrations, release years, perfumers, descriptions, prices
 * and bottle sizes, matched against each fragrance's actual published
 * composition rather than guessed.
 */
return new class extends Migration
{
    // Approximate USD -> IDR conversion used for all researched retail_price figures.
    private const USD_TO_IDR = 15800;

    /**
     * Lowercase researched ingredient name => canonical name already in the
     * `notes` table. Keeps near-duplicate descriptors (e.g. "Madagascar
     * Vanilla", "Bourbon Vanilla") from spawning redundant Note rows.
     */
    private function noteAliases(): array
    {
        return [
            'oud' => 'Agarwood',
            'agarwood (oud)' => 'Agarwood',
            'indian oud' => 'Agarwood',
            'laotian oud' => 'Agarwood',
            'thailand oud' => 'Agarwood',
            'mandarin' => 'Mandarin Orange',
            'green mandarin' => 'Mandarin Orange',
            'brazilian green mandarin' => 'Mandarin Orange',
            'virginia cedar' => 'Cedarwood',
            'virginian cedarwood' => 'Cedarwood',
            'french labdanum' => 'Labdanum',
            'sicilian orange' => 'Orange',
            'sicilian lemon' => 'Lemon',
            'calabrian lemon' => 'Lemon',
            'mediterranean fruit' => 'Fruity Notes',
            'madagascar vanilla' => 'Vanilla',
            'bourbon vanilla' => 'Vanilla',
            'vanilla absolute' => 'Vanilla',
            'vanille' => 'Vanilla',
            'vanilla madagascar' => 'Vanilla',
            'vanilla (absolute)' => 'Vanilla',
            'italian iris' => 'Iris',
            'florentine iris' => 'Iris',
            'egyptian jasmine' => 'Jasmine',
            'jasmine grandiflorum' => 'Jasmine',
            'jasmine sambac' => 'Jasmine',
            'egyptian jasmine absolute' => 'Jasmine',
            'mysore sandalwood' => 'Sandalwood',
            'australian sandalwood' => 'Sandalwood',
            'laotian benzoin' => 'Benzoin',
            'iranian saffron' => 'Saffron',
            'lavender (flowers)' => 'Lavender',
            'lavender flowers' => 'Lavender',
            'olibanum' => 'Incense',
            'frankincense' => 'Incense',
            'bigarade' => 'Bitter Orange',
            'dry woods' => 'Woody Notes',
            'citruses' => 'Citrus Notes',
            'lemon verbena' => 'Verbena',
            'rum (absolute)' => 'Rum',
            'rum absolute' => 'Rum',
            'coffee (absolute)' => 'Coffee',
            'coffee absolute' => 'Coffee',
            'dark coffee absolute' => 'Coffee',
            'mocha coffee' => 'Coffee',
            'musk mallow' => 'Ambrette',
            'ambrette seeds' => 'Ambrette',
            'ambrette seed' => 'Ambrette',
            'green leaves' => 'Green Notes',
            'cashmere (wood accord)' => 'Cashmere Wood',
            'cashmere wood accord' => 'Cashmere Wood',
            'calabrian bergamot' => 'Calabrian Bergamot',
            'bergamot (calabria)' => 'Calabrian Bergamot',
            'italian/calabrian bergamot' => 'Calabrian Bergamot',
            'blackcurrant' => 'Black Currant',
            'black currant absolute' => 'Black Currant',
            'cassis' => 'Black Currant',
            'blackcurrant syrup' => 'Black Currant',
            'rose de mai' => 'Rose',
            'may rose' => 'Rose',
            'rose absolute' => 'Rose',
            'turkish rose' => 'Turkish Rose',
            'davana' => 'Davana',
            'ambroxan' => 'Ambroxan',
            'amberwood' => 'Amberwood',
            'guaiac wood' => 'Guaiac Wood',
            'suede' => 'Leather',
            'sugar cane' => 'Sugar',
            'candied almond' => 'Almond',
            'oak absolute' => 'Oak',
            'ambrette (musk mallow)' => 'Ambrette',
            'ambrettolide' => 'Ambrette',
            'amyl salicylate' => 'Musk',
            'helvetolide' => 'Musk',
            'cistus labdanum' => 'Labdanum',
            'siam benzoin' => 'Benzoin',
            'lavandin' => 'Lavender',
            'peru balsam' => 'Benzoin',
            'java vetiver oil' => 'Vetiver',
            'gurjan balsam' => 'Elemi',
            'animal notes' => 'Musk',
            'resins' => 'Labdanum',
            'woodsy notes' => 'Woody Notes',
            'green notes' => 'Green Notes',
            'blue ginger' => 'Ginger',
            'oud (agarwood)' => 'Agarwood',
            'pittosporum' => 'Orange Blossom',
            'ambrette seed' => 'Ambrette',
            'rosewood/rosemary' => 'Rosewood',
            'woody notes' => 'Woody Notes',
            'sea water/marine notes' => 'Sea Notes',
            'driftwood/musk facets' => 'Musk',
            'fir resin' => 'Fir Resin',
            'cetalox' => 'Cetalox',
            'hedione' => 'Hedione',
            'iso e super' => 'Iso E Super',
            'sapodilla' => 'Pear',
            'opoponax' => 'Amber',
            'mysore' => 'Sandalwood',
            'sandalwood (mysore)' => 'Sandalwood',
            'orris' => 'Orris Root',
            'tahitian vanilla' => 'Vanilla',
            'ambrox' => 'Ambroxan',
            'cinnamon leaf' => 'Cinnamon',
            'cloves' => 'Clove',
            'tobacco' => 'Tobacco Leaf',
            'tonka' => 'Tonka Bean',
            'red berries' => 'Raspberry',
            'haitian vetiver' => 'Vetiver',
            'litchi (lychee)' => 'Litchi',
            'tunisian neroli' => 'Neroli',
            'italian bergamot' => 'Bergamot',
            'african orange blossom' => 'Orange Blossom',
            'green tangerine' => 'Mandarin Orange',
            'persimmon' => 'Persimmon',
            'suede/leather' => 'Leather',
            'sea/marine notes' => 'Sea Notes',
            'marine notes' => 'Sea Notes',
            'sea notes' => 'Sea Notes',
            'mineral amber' => 'Amber',
            'crystal musks' => 'Musk',
            'white musk' => 'White Musk',
            'orange blossom/flower' => 'Orange Blossom',
            'orange flower' => 'Orange Blossom',
            'neroli (tunisian)' => 'Neroli',
            'tunisian neroli' => 'Neroli',
            'iris butter' => 'Iris',
            'orris butter' => 'Orris Root',
            'iris aldehyde' => 'Iris',
            'guatemalan patchouli' => 'Patchouli',
            'indonesian patchouli' => 'Patchouli',
            'patchouli (indonesian)' => 'Patchouli',
            'labdanum (french)' => 'Labdanum',
            'lentisk absolute' => 'Elemi',
            'aquozone' => 'Sea Notes',
            'tonka bean absolute' => 'Tonka Bean',
        ];
    }

    /**
     * Genuinely new ingredients (not aliasable to an existing note) found
     * during research. char = [floral, woody, warm, sweet, bright, fresh] (0-100).
     */
    private function newNotes(): array
    {
        return [
            'Driftwood' => ['fam' => 'Woody', 'role' => 'Base Note', 'type' => 'Natural', 'char' => [5, 75, 50, 10, 20, 40], 'desc' => 'A dry, salt-bleached wood facet used to evoke weathered timber washed up on a shoreline.'],
            'Palisander Rosewood' => ['fam' => 'Woody', 'role' => 'Base Note', 'type' => 'Natural', 'char' => [15, 70, 45, 20, 15, 15], 'desc' => 'A dense, rosy-sweet exotic rosewood prized in fine perfumery for its warm, slightly floral woodiness.'],
            'Tobacco Blossom' => ['fam' => 'Gourmand', 'role' => 'Middle Note', 'type' => 'Natural', 'char' => [40, 15, 50, 45, 10, 10], 'desc' => 'The honeyed, floral flower of the tobacco plant, sweeter and softer than cured tobacco leaf.'],
            'Guava' => ['fam' => 'Fresh', 'role' => 'Top Note', 'type' => 'Natural', 'char' => [10, 0, 15, 70, 60, 55], 'desc' => 'A juicy, musky-sweet tropical fruit note used to add exotic brightness to fruity compositions.'],
            'Apple Blossom' => ['fam' => 'Floral', 'role' => 'Top Note', 'type' => 'Natural', 'char' => [65, 0, 5, 35, 55, 60], 'desc' => 'A delicate, dewy floral note from apple tree flowers, lighter and greener than ripe apple fruit.'],
            'Kumquat' => ['fam' => 'Citrus', 'role' => 'Top Note', 'type' => 'Natural', 'char' => [5, 0, 5, 30, 80, 65], 'desc' => 'A small, tart-sweet citrus fruit eaten peel and all, giving a bright, slightly bitter citrus sparkle.'],
            'Lilac' => ['fam' => 'Floral', 'role' => 'Middle Note', 'type' => 'Natural', 'char' => [80, 0, 10, 25, 35, 45], 'desc' => 'A soft, powdery-green spring floral with a slightly cool, dewy character.'],
            'Malt' => ['fam' => 'Gourmand', 'role' => 'Base Note', 'type' => 'Natural', 'char' => [0, 20, 45, 55, 10, 5], 'desc' => 'A toasted, bready-sweet accord recalling malted grain, used to add gourmand warmth.'],
            'Cypriol' => ['fam' => 'Woody', 'role' => 'Base Note', 'type' => 'Natural', 'char' => [0, 70, 55, 10, 5, 10], 'desc' => 'Also known as nagarmotha, a smoky, earthy, deeply woody root oil with a dry, incense-like character.'],
            'Floral Notes' => ['fam' => 'Floral', 'role' => 'Middle Note', 'type' => 'Natural', 'char' => [75, 5, 20, 30, 35, 25], 'desc' => 'A general floral accord blending several blossoms rather than one dominant flower.'],
            'Rhubarb' => ['fam' => 'Fresh', 'role' => 'Top Note', 'type' => 'Natural', 'char' => [5, 0, 5, 25, 55, 60], 'desc' => 'A tart, slightly bitter-green stalk note used for a crisp, sour-fresh accent.'],
            'Persimmon' => ['fam' => 'Fresh', 'role' => 'Top Note', 'type' => 'Natural', 'char' => [10, 0, 15, 55, 45, 40], 'desc' => 'A honeyed, mellow orange-toned fruit note, softer and less acidic than citrus.'],
            'Chestnut' => ['fam' => 'Gourmand', 'role' => 'Base Note', 'type' => 'Natural', 'char' => [0, 30, 55, 50, 5, 5], 'desc' => 'A roasted, nutty-sweet accord recalling candied or glazed chestnuts.'],
            'Cherry' => ['fam' => 'Gourmand', 'role' => 'Top Note', 'type' => 'Natural', 'char' => [10, 0, 15, 65, 45, 30], 'desc' => 'A juicy, slightly almond-tinged red fruit note.'],
            'Chamomile' => ['fam' => 'Fresh', 'role' => 'Middle Note', 'type' => 'Natural', 'char' => [30, 0, 25, 35, 30, 45], 'desc' => 'A soft, apple-like herbal floral with a calming, slightly sweet character.'],
            'Birch' => ['fam' => 'Woody', 'role' => 'Base Note', 'type' => 'Natural', 'char' => [0, 65, 40, 10, 15, 25], 'desc' => 'A smoky, tar-like wood note (birch tar) used to add a leathery, slightly burnt facet to woody compositions.'],
            'Myrrh' => ['fam' => 'Amber', 'role' => 'Base Note', 'type' => 'Natural', 'char' => [0, 35, 60, 25, 5, 5], 'desc' => 'A warm, balsamic resin with a slightly bitter, smoky sweetness, often paired with frankincense in oriental compositions.'],
            'Galbanum' => ['fam' => 'Fresh', 'role' => 'Top Note', 'type' => 'Natural', 'char' => [10, 20, 10, 5, 45, 55], 'desc' => "A sharp, bitter-green resinous note giving a crisp, vegetal freshness to a composition's opening."],
            'Myrtle' => ['fam' => 'Fresh', 'role' => 'Top Note', 'type' => 'Natural', 'char' => [10, 10, 10, 5, 40, 65], 'desc' => 'A cool, camphoraceous-green herbal note with a slightly medicinal, eucalyptus-adjacent freshness.'],
            'Cannabis Accord' => ['fam' => 'Woody', 'role' => 'Middle Note', 'type' => 'Synthetic', 'char' => [0, 45, 45, 15, 5, 20], 'desc' => 'A green, resinous, slightly herbal accord built to evoke hashish/cannabis without a literal extract.'],
            'Persimmon' => ['fam' => 'Fresh', 'role' => 'Top Note', 'type' => 'Natural', 'char' => [10, 0, 15, 55, 45, 40], 'desc' => 'A honeyed, mellow orange-toned fruit note, softer and less acidic than citrus.'],
        ];
    }

    private array $perfumes = [];

    public function up(): void
    {
        $this->perfumes = array_merge(
            $this->xerjoffPerfumes(),
            $this->kilianPerfumes(),
            $this->armaniPerfumes(),
            $this->creedPerfumes(),
            $this->flagshipPerfumes(),
            $this->cliveChristianAndGaultierPerfumes(),
        );

        // 1. Build name => id map of existing notes.
        $noteIdByName = [];
        foreach (DB::table('notes')->get() as $note) {
            $noteIdByName[strtolower($note->name)] = $note->id;
        }

        // 2. Create any genuinely new notes.
        foreach ($this->newNotes() as $name => $data) {
            $key = strtolower($name);
            if (isset($noteIdByName[$key])) {
                continue;
            }

            $id = DB::table('notes')->insertGetId([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $data['desc'],
                'aroma' => null,
                'fragrance_family' => $data['fam'],
                'origin' => null,
                'extraction' => null,
                'common_role' => $data['role'],
                'ingredient_type' => $data['type'],
                'longevity' => 'Moderate',
                'intensity' => 'Moderate',
                'best_paired_with' => null,
                'image' => null,
                'about_image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('note_characteristics')->insert([
                'note_id' => $id,
                'floral' => $data['char'][0],
                'woody' => $data['char'][1],
                'warm' => $data['char'][2],
                'sweet' => $data['char'][3],
                'bright' => $data['char'][4],
                'fresh' => $data['char'][5],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $noteIdByName[$key] = $id;
        }

        $aliases = $this->noteAliases();
        $unresolved = [];

        $resolve = function (string $rawName) use (&$noteIdByName, $aliases, &$unresolved) {
            $key = strtolower(trim($rawName));

            if (isset($aliases[$key])) {
                $key = strtolower($aliases[$key]);
            }

            if (! isset($noteIdByName[$key])) {
                $unresolved[$rawName] = true;

                return null;
            }

            return $noteIdByName[$key];
        };

        // 3. Update each perfume + rebuild its notes pivot.
        foreach ($this->perfumes as $slug => $data) {
            $perfumeId = DB::table('perfumes')->where('slug', $slug)->value('id');

            if (! $perfumeId) {
                continue;
            }

            $update = [
                'concentration' => $data['concentration'],
                'gender' => $data['gender'],
                'description' => $data['description'],
                'bottle_sizes' => $data['bottle_sizes'],
            ];

            if (! empty($data['name'])) {
                $update['name'] = $data['name'];
            }

            if (! empty($data['release_year'])) {
                $update['release_year'] = $data['release_year'];
            }

            if (array_key_exists('perfumer', $data)) {
                $update['perfumer'] = $data['perfumer'];
            }

            if (! empty($data['retail_price_usd'])) {
                $update['retail_price'] = round(($data['retail_price_usd'] * self::USD_TO_IDR) / 10000) * 10000;
            }

            DB::table('perfumes')->where('id', $perfumeId)->update($update);

            DB::table('perfume_notes')->where('perfume_id', $perfumeId)->delete();

            $rows = [];
            foreach (['top', 'middle', 'base'] as $type) {
                foreach ($data['notes'][$type] ?? [] as $noteName) {
                    $noteId = $resolve($noteName);

                    if (! $noteId) {
                        continue;
                    }

                    $rows[] = ['perfume_id' => $perfumeId, 'note_id' => $noteId, 'note_type' => $type];
                }
            }

            if ($rows) {
                DB::table('perfume_notes')->insertOrIgnore($rows);
            }
        }

        if ($unresolved) {
            throw new \RuntimeException('Unresolved note names: '.implode(', ', array_keys($unresolved)));
        }
    }

    public function down(): void
    {
        // Data-correction migration; not meaningfully reversible.
    }

    private function flagshipPerfumes(): array
    {
        return [
            'gypsy-water' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2008, 'perfumer' => 'Jerome Epinette', 'gender' => 'unisex',
                'description' => 'A crisp, outdoorsy woody-aromatic scent that opens with bright citrus and juniper, moves through a resinous pine-and-incense heart, and settles into a warm amber-vanilla-sandalwood base.',
                'retail_price_usd' => 330, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Bergamot', 'Lemon', 'Pepper', 'Juniper Berries'], 'middle' => ['Pine Needles', 'Incense', 'Orris Root'], 'base' => ['Amber', 'Sandalwood', 'Vanilla']],
            ],
            'mojave-ghost' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2014, 'perfumer' => null, 'gender' => 'unisex',
                'description' => 'A soft, musky floral built around ambrette and sandalwood, with a delicate fruity opening and violet/magnolia petals in the heart, finishing on warm woods and ambergris.',
                'retail_price_usd' => 330, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Pear', 'Ambrette'], 'middle' => ['Violet', 'Magnolia'], 'base' => ['Sandalwood', 'Ambergris', 'Cedar']],
            ],
            'bleu-de-chanel-eau-de-parfum' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2014, 'perfumer' => 'Jacques Polge', 'gender' => 'men',
                'description' => 'A woody-aromatic fragrance that opens with sharp citrus and mint before a spicy floral heart, drying down into a deep, incense-tinged woody amber base.',
                'retail_price_usd' => 155, 'bottle_sizes' => '50ml, 100ml, 150ml',
                'notes' => ['top' => ['Grapefruit', 'Lemon', 'Mint', 'Bergamot', 'Pink Pepper', 'Aldehydes', 'Coriander'], 'middle' => ['Ginger', 'Nutmeg', 'Jasmine', 'Melon'], 'base' => ['Incense', 'Amber', 'Cedar', 'Sandalwood', 'Amberwood', 'Patchouli', 'Labdanum', 'White Musk']],
            ],
            'coco-mademoiselle-eau-de-parfum' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2001, 'perfumer' => 'Jacques Polge', 'gender' => 'women',
                'description' => "An oriental-floral chypre with a bright citrus opening, a rose-jasmine heart, and a sensual patchouli-vanilla-musk base. Conceived as a modern reinterpretation of Chanel's classic Coco.",
                'retail_price_usd' => 165, 'bottle_sizes' => '35ml, 50ml, 100ml, 200ml',
                'notes' => ['top' => ['Orange', 'Mandarin Orange', 'Bergamot', 'Orange Blossom'], 'middle' => ['Turkish Rose', 'Jasmine', 'Mimosa', 'Ylang-Ylang'], 'base' => ['Patchouli', 'White Musk', 'Vanilla', 'Vetiver', 'Tonka Bean', 'Amber']],
            ],
            'dior-homme-intense' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2011, 'perfumer' => 'François Demachy', 'gender' => 'men',
                'description' => 'A powdery, iris-centric woody fragrance noted for a distinctive cocoa-like softness paired with fresh lavender and a dry cedar-vetiver base. Regarded as a benchmark "iris for men" scent.',
                'retail_price_usd' => 130, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Lavender'], 'middle' => ['Iris', 'Ambrette', 'Pear'], 'base' => ['Cedarwood', 'Vetiver']],
            ],
            'sauvage-eau-de-toilette' => [
                'concentration' => 'Eau de Toilette', 'release_year' => 2015, 'perfumer' => 'François Demachy', 'gender' => 'men',
                'description' => 'A fresh, spicy-citrus fragrance driven by a signature ambroxan base that gives it a clean, radiant, woody-amber trail. Currently the best-selling men\'s fragrance globally.',
                'retail_price_usd' => 100, 'bottle_sizes' => '60ml, 100ml, 200ml',
                'notes' => ['top' => ['Calabrian Bergamot', 'Sichuan Pepper'], 'middle' => ['Lavender', 'Pink Pepper', 'Vetiver', 'Patchouli', 'Geranium', 'Elemi'], 'base' => ['Ambroxan', 'Cedar', 'Labdanum']],
            ],
            'philosykos-eau-de-toilette' => [
                'concentration' => 'Eau de Toilette', 'release_year' => 1996, 'perfumer' => 'Olivia Giacobetti', 'gender' => 'unisex',
                'description' => 'Widely considered the definitive fig fragrance — green, milky fig fruit, sappy leaves, and creamy woody trunk notes for a fresh, green, understated scent.',
                'retail_price_usd' => 145, 'bottle_sizes' => '50ml, 75ml, 200ml',
                'notes' => ['top' => ['Fig Leaf'], 'middle' => ['Fig'], 'base' => ['Fig Tree', 'Cedar', 'Coconut']],
            ],
            'tam-dao-eau-de-parfum' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2013, 'perfumer' => null, 'gender' => 'unisex',
                'description' => 'A creamy, milky sandalwood fragrance cooled by cypress and myrtle, inspired by sandalwood incense burned in Vietnamese temples. A richer, more amplified take on the original EDT.',
                'retail_price_usd' => 260, 'bottle_sizes' => '30ml, 75ml, 200ml',
                'notes' => ['top' => ['Cypress', 'Myrtle'], 'middle' => ['Sandalwood', 'Cedar'], 'base' => ['Brazilian Rosewood', 'Amber', 'White Musk']],
            ],
            'light-blue-eau-intense-pour-homme' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2017, 'perfumer' => 'Alberto Morillas', 'gender' => 'men',
                'description' => 'A fresh, aquatic-woody fragrance that intensifies the original Light Blue Pour Homme with brighter citrus and a more pronounced marine/juniper heart over a clean musky-woody base.',
                'retail_price_usd' => 100, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Grapefruit', 'Mandarin Orange'], 'middle' => ['Sea Notes', 'Juniper'], 'base' => ['Musk', 'Amberwood']],
            ],
            'molecule-01' => [
                'concentration' => 'Eau de Toilette', 'release_year' => 2006, 'perfumer' => 'Geza Schoen', 'gender' => 'unisex',
                'description' => 'A minimalist, avant-garde fragrance composed of essentially one aroma-chemical, Iso E Super, reading as a soft, velvety, skin-like woody-cedar-musk aura that reacts uniquely with each wearer.',
                'retail_price_usd' => 170, 'bottle_sizes' => '30ml, 50ml, 100ml',
                'notes' => ['top' => [], 'middle' => [], 'base' => ['Iso E Super']],
            ],
            'portrait-of-a-lady' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2010, 'perfumer' => 'Dominique Ropion', 'gender' => 'women',
                'description' => 'An intense, dark oriental built on an outsized dose of Turkish rose layered over heavy patchouli, incense, and spices. Regarded as a modern niche classic for its density and exceptional longevity.',
                'retail_price_usd' => 300, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Rose', 'Raspberry', 'Clove', 'Cinnamon', 'Black Currant'], 'middle' => ['Turkish Rose', 'Patchouli', 'Incense', 'Sandalwood', 'Ylang-Ylang'], 'base' => ['Incense', 'Sandalwood', 'Musk', 'Benzoin', 'Amber', 'Ambergris', 'Cedar', 'Vanilla']],
            ],
            'l-homme-ideal-eau-de-parfum' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2016, 'perfumer' => 'Thierry Wasser, Delphine Jelk', 'gender' => 'men',
                'description' => 'A sweet, gourmand-woody fragrance built around an almond-cherry accord over a warm tonka-leather base, a richer, more intense counterpart to the original EDT.',
                'retail_price_usd' => 105, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Almond', 'Lavender', 'Bergamot', 'Rosemary', 'Thyme'], 'middle' => ['Cherry', 'Vanilla', 'Incense', 'Bulgarian Rose'], 'base' => ['Tonka Bean', 'Leather', 'Sandalwood']],
            ],
            'mon-guerlain' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2017, 'perfumer' => 'Thierry Wasser, Delphine Jelk', 'gender' => 'women',
                'description' => 'A modern floral-oriental centered on a lavender-vanilla accord, with iris and jasmine sambac softness over a sweet vanilla-sandalwood base.',
                'retail_price_usd' => 140, 'bottle_sizes' => '30ml, 50ml, 100ml',
                'notes' => ['top' => ['Lavender', 'Bergamot'], 'middle' => ['Iris', 'Jasmine', 'Rose'], 'base' => ['Vanilla', 'Coumarin', 'Sandalwood', 'Licorice', 'Benzoin', 'Patchouli']],
            ],
            'terre-d-hermes-eau-de-toilette' => [
                'concentration' => 'Eau de Toilette', 'release_year' => 2006, 'perfumer' => 'Jean-Claude Ellena', 'gender' => 'men',
                'description' => "A mineral, earthy-woody fragrance exploring man's relationship to soil; citrus brightness gives way to a peppery-green heart and a grounded, flint-like vetiver-patchouli-cedar base.",
                'retail_price_usd' => 120, 'bottle_sizes' => '50ml, 100ml, 200ml',
                'notes' => ['top' => ['Orange', 'Grapefruit'], 'middle' => ['Pepper', 'Geranium'], 'base' => ['Vetiver', 'Patchouli', 'Cedar', 'Benzoin']],
            ],
            'wood-sage-sea-salt' => [
                'concentration' => 'Cologne', 'release_year' => 2014, 'perfumer' => 'Christine Nagel', 'gender' => 'unisex',
                'description' => 'A mineral, briny-fresh scent evoking coastal driftwood and sea spray, combining salty ambrette with peppery sage and a "wet sand" quality. One of Jo Malone\'s most popular unisex releases.',
                'retail_price_usd' => 180, 'bottle_sizes' => '30ml, 100ml',
                'notes' => ['top' => ['Grapefruit', 'Sea Salt'], 'middle' => ['Sage', 'Ambrette'], 'base' => ['Seaweed', 'Driftwood']],
            ],
            'another-13' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2010, 'perfumer' => 'Nathalie Lorson', 'gender' => 'unisex',
                'description' => 'A clean, musky skin-scent blending a fruity opening with a heart of synthetic musks and moss over an ambery-woody-musk base. Known for a radiant, "addictive" quality.',
                'retail_price_usd' => 307, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Pear', 'Apple', 'Citrus Notes'], 'middle' => ['Ambrette', 'Moss', 'Jasmine'], 'base' => ['Iso E Super', 'Cetalox', 'Musk']],
            ],
            'santal-33' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2011, 'perfumer' => 'Frank Voelkl', 'gender' => 'unisex',
                'description' => 'A smoky, spicy sandalwood-and-leather fragrance combining cardamom and violet-iris facets with a "smoking wood" alloy of sandalwood and cedar. One of the most recognizable niche fragrances of the 2010s.',
                'retail_price_usd' => 310, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Cardamom', 'Violet', 'Iris'], 'middle' => ['Cedarwood', 'Papyrus'], 'base' => ['Sandalwood', 'Cedarwood', 'Leather', 'Musk', 'Ambroxan']],
            ],
            'baccarat-rouge-540' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2015, 'perfumer' => 'Francis Kurkdjian', 'gender' => 'unisex',
                'description' => 'A luxurious, sweet-ambery-woody fragrance built on a jasmine-saffron "breeze," a cedar "heat," and an ambergris "mineral" accord, giving it a distinctive sweet, radiant, almost saline-metallic signature.',
                'retail_price_usd' => 325, 'bottle_sizes' => '70ml, 200ml',
                'notes' => ['top' => ['Saffron', 'Jasmine'], 'middle' => ['Amberwood', 'Ambergris', 'Hedione'], 'base' => ['Fir Resin', 'Cedar', 'Ambroxan', 'Oakmoss']],
            ],
            'grand-soir' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2016, 'perfumer' => 'Francis Kurkdjian', 'gender' => 'unisex',
                'description' => 'A deep, resinous amber fragrance built around a labdanum-vanilla-benzoin accord, giving a warm, cozy "amber of ambers" character intended to evoke a Parisian evening.',
                'retail_price_usd' => 250, 'bottle_sizes' => '35ml, 70ml, 200ml',
                'notes' => ['top' => ['Labdanum', 'Orange'], 'middle' => ['Lavender', 'Cinnamon', 'Benzoin'], 'base' => ['Amber', 'Vanilla', 'Tonka Bean', 'Musk', 'Cedar']],
            ],
            'by-the-fireplace' => [
                'concentration' => 'Eau de Toilette', 'release_year' => 2015, 'perfumer' => 'Marie Salamagne', 'gender' => 'unisex',
                'description' => 'A cozy, smoky-sweet gourmand fragrance evoking a crackling fireplace: toasted chestnut and guaiac-wood smokiness paired with a warm vanilla-cashmeran base and a spicy clove-pepper top.',
                'retail_price_usd' => 160, 'bottle_sizes' => '30ml, 100ml',
                'notes' => ['top' => ['Clove', 'Pink Pepper', 'Orange Blossom'], 'middle' => ['Chestnut', 'Guaiac Wood', 'Juniper'], 'base' => ['Vanilla', 'Cashmeran']],
            ],
            'jazz-club' => [
                'concentration' => 'Eau de Toilette', 'release_year' => 2013, 'perfumer' => 'Alienor Massenet', 'gender' => 'men',
                'description' => 'A warm, boozy leather-and-tobacco scent designed to evoke a smoky jazz club, combining rum and tobacco leaf with vanilla for a smooth, sweet masculine composition.',
                'retail_price_usd' => 160, 'bottle_sizes' => '30ml, 100ml',
                'notes' => ['top' => ['Pink Pepper', 'Neroli', 'Lemon'], 'middle' => ['Rum', 'Clary Sage', 'Vetiver'], 'base' => ['Vanilla', 'Tobacco Leaf', 'Styrax']],
            ],
            'black-afgano' => [
                'concentration' => 'Extrait de Parfum', 'release_year' => 2009, 'perfumer' => 'Alessandro Gualtieri', 'gender' => 'unisex',
                'description' => 'A dark, dense, resinous fragrance designed to evoke the smell of hashish through incense, tobacco, coffee, and oud rather than a literal cannabis note. A cult favorite for its unusual intensity.',
                'retail_price_usd' => 195, 'bottle_sizes' => '30ml',
                'notes' => ['top' => ['Cannabis Accord', 'Green Notes', 'Davana', 'Saffron', 'Thyme'], 'middle' => ['Labdanum', 'Woody Notes', 'Tobacco Leaf', 'Coffee', 'Cinnamon', 'Raspberry', 'Violet'], 'base' => ['Agarwood', 'Incense', 'Amber', 'Musk', 'Guaiac Wood', 'Cedar', 'Tonka Bean', 'Vanilla']],
            ],
            'ani' => [
                'concentration' => 'Extrait de Parfum', 'release_year' => 2019, 'perfumer' => 'Cécile Zarokian', 'gender' => 'unisex',
                'description' => 'A rich oriental fragrance inspired by the ancient Armenian city of Ani, opening with bright bergamot and spicy ginger, moving through a fruity rose-blackcurrant heart, and settling into a warm vanilla-wood-amber base.',
                'retail_price_usd' => 375, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Bergamot', 'Green Notes', 'Ginger', 'Pink Pepper'], 'middle' => ['Black Currant', 'Turkish Rose', 'Cardamom'], 'base' => ['Patchouli', 'Cedarwood', 'Vanilla', 'Benzoin', 'Ambergris', 'Musk', 'Sandalwood']],
            ],
            'hacivat' => [
                'concentration' => 'Extrait de Parfum', 'release_year' => 2017, 'perfumer' => 'Jorge Lee', 'gender' => 'unisex',
                'description' => 'A woody-chypre fragrance blending tart, natural-smelling pineapple and citrus with a frankincense-jasmine heart and an oud-patchouli-vetiver base.',
                'retail_price_usd' => 395, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Pineapple', 'Citrus Notes', 'Black Pepper', 'Pink Pepper'], 'middle' => ['Incense', 'Jasmine'], 'base' => ['Agarwood', 'Patchouli', 'Vetiver', 'Vanilla']],
            ],
            'delina' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2017, 'perfumer' => 'Quentin Bisch', 'gender' => 'women',
                'description' => 'A fruity-floral fragrance centered on Turkish rose and juicy lychee, with a sweet, radiant rhubarb-nutmeg opening and a creamy musk-vetiver-caramel base.',
                'retail_price_usd' => 260, 'bottle_sizes' => '30ml, 75ml',
                'notes' => ['top' => ['Litchi', 'Rhubarb', 'Bergamot', 'Nutmeg', 'Black Currant'], 'middle' => ['Turkish Rose', 'Peony', 'Lily of the Valley'], 'base' => ['Musk', 'Vanilla', 'Cashmeran', 'Incense', 'Cedar', 'Vetiver', 'Caramel']],
            ],
            'neroli-portofino' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2007, 'perfumer' => 'Rodrigo Flores-Roux', 'gender' => 'unisex',
                'description' => 'A bright, sunny citrus-neroli fragrance evoking the Italian Riviera, combining fresh Mediterranean citrus and herbs with soft musk-amber warmth.',
                'retail_price_usd' => 300, 'bottle_sizes' => '50ml, 100ml, 250ml',
                'notes' => ['top' => ['Neroli', 'Bergamot', 'Lemon', 'Mandarin Orange', 'Lavender', 'Myrtle', 'Rosemary', 'Bitter Orange'], 'middle' => ['Orange Blossom', 'Jasmine', 'Neroli'], 'base' => ['Amber', 'Ambrette', 'Angelica']],
            ],
            'oud-wood' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2007, 'perfumer' => 'Richard Herpin', 'gender' => 'unisex',
                'description' => 'A smoky, exotic woody fragrance built around agarwood blended with sandalwood and spice, notable for helping popularize oud notes in mainstream Western niche perfumery.',
                'retail_price_usd' => 260, 'bottle_sizes' => '50ml, 100ml, 250ml',
                'notes' => ['top' => ['Cardamom', 'Pink Pepper', 'Rosewood'], 'middle' => ['Sandalwood', 'Agarwood', 'Patchouli', 'Vetiver'], 'base' => ['Vanilla', 'Tonka Bean', 'Amber']],
            ],
            'black-opium' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2014, 'perfumer' => 'Nathalie Lorson, Marie Salamagne, Olivier Cresp, Honorine Blanc', 'gender' => 'women',
                'description' => 'An oriental-gourmand fragrance built around a signature coffee note layered with sweet vanilla and floral jasmine over an orange-blossom-pear opening. Marketed for nighttime wear.',
                'retail_price_usd' => 148, 'bottle_sizes' => '30ml, 50ml, 90ml',
                'notes' => ['top' => ['Pink Pepper', 'Orange Blossom', 'Pear'], 'middle' => ['Coffee', 'Jasmine'], 'base' => ['Vanilla', 'Patchouli', 'Cedarwood']],
            ],
            'y-eau-de-parfum' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2018, 'perfumer' => 'Dominique Ropion', 'gender' => 'men',
                'description' => 'A fresh, aromatic-woody fragrance positioned as a deeper, more intense version of the original Y Eau de Toilette, combining fruity-ginger freshness with a woody-ambroxan base.',
                'retail_price_usd' => 125, 'bottle_sizes' => '60ml, 100ml, 200ml',
                'notes' => ['top' => ['Bergamot', 'Ginger', 'Apple'], 'middle' => [], 'base' => ['Woody Notes', 'Ambroxan']],
            ],
        ];
    }

    private function cliveChristianAndGaultierPerfumes(): array
    {
        return [
            '1872-feminine' => [
                'concentration' => 'Parfum', 'release_year' => 2001, 'perfumer' => 'Patricia Choux', 'gender' => 'women',
                'description' => 'A floral-fruity composition centered on Rose de Mai, jasmine and orchid, opened by bright bergamot and citrus, and grounded in patchouli, sandalwood and musk.',
                'retail_price_usd' => 390, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Bergamot', 'Lemon', 'Pineapple'], 'middle' => ['Rose', 'Osmanthus', 'Jasmine', 'Violet', 'Freesia', 'Orchid', 'Lily of the Valley'], 'base' => ['Patchouli', 'Oakmoss', 'Sandalwood', 'Cedarwood', 'Musk']],
            ],
            '1872-masculine' => [
                'concentration' => 'Parfum', 'release_year' => 2001, 'perfumer' => null, 'gender' => 'men',
                'description' => "A woody-citrus fragrance built on herbaceous clary sage and black pepper over a petitgrain-citrus opening, finished with cedarwood, sandalwood and frankincense. A tribute to the brand's 1872 royal warrant heritage.",
                'retail_price_usd' => 610, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Petitgrain', 'Peach', 'Lavender', 'Rosemary', 'Black Pepper', 'Nutmeg'], 'middle' => ['Clary Sage', 'Jasmine', 'Neroli', 'Musk'], 'base' => ['Cedarwood', 'Sandalwood', 'Incense', 'Musk', 'Amber', 'Patchouli']],
            ],
            'blonde-amber' => [
                'concentration' => 'Extrait de Parfum', 'release_year' => 2022, 'perfumer' => 'Vincent Ricord', 'gender' => 'unisex',
                'description' => 'A warm oriental-amber fragrance centered on smoky, sweet blonde tobacco and bitter orange, cushioned by tuberose and jasmine, finishing on a praline-like tonka-vanilla base.',
                'retail_price_usd' => 610, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Rum', 'Incense', 'Bitter Orange', 'Cardamom', 'Pink Pepper', 'Ginger', 'Bergamot', 'Grapefruit'], 'middle' => ['Dried Fruits', 'Tobacco Leaf', 'Sandalwood', 'Saffron', 'Tuberose', 'Osmanthus', 'Jasmine', 'Orris Root'], 'base' => ['Tonka Bean', 'Vanilla', 'Myrrh', 'Labdanum', 'Patchouli', 'Musk', 'Cedar', 'Vetiver']],
            ],
            'crab-apple-blossom' => [
                'concentration' => 'Parfum', 'release_year' => 2020, 'perfumer' => 'Kamila Lelakova', 'gender' => 'unisex',
                'description' => 'A fresh citrus-aquatic springtime scent combining apple blossom and marine bergamot with a crisp, bittersweet rhubarb-mint heart, settling into a soft driftwood-sandalwood base.',
                'retail_price_usd' => 500, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Bergamot', 'Apple Blossom', 'Sea Notes'], 'middle' => ['Rhubarb', 'Mint', 'Sugar'], 'base' => ['Driftwood', 'Sandalwood']],
            ],
            'cypress-clive-christian' => [
                'concentration' => 'Parfum', 'release_year' => 2018, 'perfumer' => null, 'gender' => 'men',
                'description' => 'A spicy, coniferous cypress fragrance opening with bright bergamot and petitgrain, warmed by a spice accord, grounded in resinous amber with cedar and oakwood.',
                'retail_price_usd' => 580, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Petitgrain', 'Bergamot', 'Basil'], 'middle' => ['Cypress', 'Clove', 'Nutmeg', 'Ginger'], 'base' => ['Amber', 'Cedar', 'Oak']],
            ],
            'jump-up-and-kiss-me-ecstatic' => [
                'concentration' => 'Parfum', 'release_year' => 2021, 'perfumer' => 'Julie Pluchet', 'gender' => 'unisex',
                'description' => "A white-floral fragrance dominated by heady tuberose, opening with bitter orange and closing on a creamy sandalwood-amber-tonka base. Uses Clive Christian's proprietary headspace-extraction technology.",
                'retail_price_usd' => 610, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Bitter Orange', 'Pink Pepper', 'Mandarin Orange'], 'middle' => ['Tuberose', 'Jasmine', 'Orange Blossom', 'Ylang-Ylang', 'Rose'], 'base' => ['Amber', 'Vanilla', 'Musk', 'Tonka Bean', 'Leather', 'Sandalwood', 'Vetiver', 'Woody Notes']],
            ],
            'jump-up-and-kiss-me-hedonistic' => [
                'concentration' => 'Parfum', 'release_year' => 2021, 'perfumer' => 'Julie Pluchet', 'gender' => 'unisex',
                'description' => 'An amber-oriental fragrance built on smoky tobacco and leather over a citrus-and-clary-sage opening, finished with a warm labdanum-tonka-vanilla base.',
                'retail_price_usd' => 610, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Cherry', 'Violet Leaf', 'Mate', 'Bergamot', 'Neroli', 'Clary Sage', 'Mandarin Orange', 'Grapefruit', 'Lemon'], 'middle' => ['Tobacco Leaf', 'Orris Root', 'Papyrus', 'Jasmine'], 'base' => ['Amber', 'Leather', 'Tonka Bean', 'Vanilla', 'Musk', 'Sandalwood', 'Cashmere Wood', 'Labdanum', 'Vetiver', 'Patchouli', 'Moss']],
            ],
            'matsukita' => [
                'concentration' => 'Parfum', 'release_year' => 2021, 'perfumer' => null, 'gender' => 'unisex',
                'description' => 'A smoky, woody-chypre "jasmine tea" fragrance blending green bergamot and pink pepper with smoky mate tea and imperial jasmine, resting on ambery musk and fir-balsam woods.',
                'retail_price_usd' => 500, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Bergamot', 'Pink Pepper', 'Nutmeg'], 'middle' => ['Mate', 'Jasmine', 'Guaiac Wood'], 'base' => ['Fir Resin', 'Woody Notes', 'Amber', 'Musk']],
            ],
            'no-1-feminine' => [
                'concentration' => 'Parfum', 'release_year' => 2001, 'perfumer' => null, 'gender' => 'women',
                'description' => 'An opulent floral fragrance built from many ingredients — juicy plum and white peach up top, a heart of ylang-ylang, vintage iris, rose and jasmine, and a rich vanilla-sandalwood-amber base.',
                'retail_price_usd' => 790, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Plum', 'Peach', 'Mandarin Orange', 'Lemon', 'Cardamom'], 'middle' => ['Ylang-Ylang', 'Osmanthus', 'Carnation', 'Iris', 'Rose', 'Jasmine'], 'base' => ['Sandalwood', 'Vetiver', 'Vanilla', 'Cedarwood', 'Tonka Bean', 'Musk', 'Amber']],
            ],
            'no-1-masculine' => [
                'concentration' => 'Parfum', 'release_year' => 2001, 'perfumer' => null, 'gender' => 'men',
                'description' => 'A woody-amber fragrance opening with citrus and warm nutmeg and thyme, moving through a spiced floral heart, and finishing on a signature aged-sandalwood, vetiver and vanilla base.',
                'retail_price_usd' => 790, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Bergamot', 'Lime', 'Mandarin Orange', 'Grapefruit', 'Nutmeg', 'Thyme'], 'middle' => ['Iris', 'Ylang-Ylang', 'Neroli', 'Heliotrope', 'Rose', 'Jasmine', 'Lily of the Valley', 'Cardamom'], 'base' => ['Sandalwood', 'Vetiver', 'Vanilla', 'Cedarwood', 'Musk', 'Tonka Bean', 'Amber']],
            ],
            'rock-rose' => [
                'concentration' => 'Parfum', 'release_year' => 2016, 'perfumer' => null, 'gender' => 'men',
                'description' => 'An oriental-fougère fragrance pairing sweet Tarocco orange with a resinous rock-rose (labdanum) heart and a spicy saffron base.',
                'retail_price_usd' => 610, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Blood Orange', 'Bergamot', 'Neroli', 'Black Pepper'], 'middle' => ['Labdanum', 'Lavender', 'Clary Sage', 'Violet'], 'base' => ['Saffron', 'Vetiver', 'Amber', 'Patchouli']],
            ],
            'town-and-country' => [
                'concentration' => 'Parfum', 'release_year' => 2023, 'perfumer' => 'Vincent Ricord', 'gender' => 'unisex',
                'description' => 'A crisp herbaceous fragrance built on refreshing clary sage over a woody sandalwood heart and a sophisticated grey-amber base. Revives a 1925 Crown Perfumery formula.',
                'retail_price_usd' => 500, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Clary Sage'], 'middle' => ['Sandalwood'], 'base' => ['Amber']],
            ],
            'x-feminine' => [
                'concentration' => 'Parfum', 'release_year' => 2001, 'perfumer' => 'Patricia Choux', 'gender' => 'women',
                'description' => 'A floral chypre inspired by the romance of Mark Antony and Cleopatra, opening with peach and rhubarb, developing into Egyptian jasmine and tuberose, and closing on a rum-and-musk base.',
                'retail_price_usd' => 390, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Peach', 'Rhubarb', 'Mandarin Orange', 'Bergamot'], 'middle' => ['Jasmine', 'Rose', 'Orris Root', 'Narcissus', 'Lily of the Valley', 'Tuberose'], 'base' => ['Musk', 'Vanilla', 'Cashmeran', 'Patchouli', 'Rum', 'Sandalwood']],
            ],
            'x-masculine' => [
                'concentration' => 'Parfum', 'release_year' => 2001, 'perfumer' => 'Geza Schoen', 'gender' => 'men',
                'description' => 'A woody-spicy fragrance built around cardamom and ginger over an iris-and-jasmine heart, finished with an earthy oakmoss-vetiver-vanilla base.',
                'retail_price_usd' => 390, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Cardamom', 'Pink Pepper', 'Ginger', 'Bergamot'], 'middle' => ['Iris', 'Violet', 'Cinnamon', 'Jasmine'], 'base' => ['Oakmoss', 'Vetiver', 'Moss', 'Vanilla']],
            ],
            'classique-eau-de-parfum' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 1993, 'perfumer' => 'Jacques Cavallier', 'gender' => 'women',
                'description' => 'A warm oriental-floral built on a rum-and-rose opening, a soft narcissus/vanilla-orchid heart, and a signature sweet, powdery vanilla-amber-tonka base — the fuller-strength sibling to the iconic corset-bottle Classique.',
                'retail_price_usd' => 90, 'bottle_sizes' => '30ml, 50ml, 100ml',
                'notes' => ['top' => ['Rose', 'Rum'], 'middle' => ['Narcissus', 'Orchid'], 'base' => ['Vanilla', 'Amber', 'Tonka Bean', 'Sandalwood']],
            ],
            'classique-eau-de-toilette' => [
                'concentration' => 'Eau de Toilette', 'release_year' => 1993, 'perfumer' => 'Jacques Cavallier', 'gender' => 'women',
                'description' => 'The iconic corset-bottle fragrance — an oriental-floral opening with citrus and star anise, a spicy ylang-ylang/tuberose heart, and a warm vanilla-amber-musk base with a cinnamon accent.',
                'retail_price_usd' => 79, 'bottle_sizes' => '30ml, 50ml, 100ml',
                'notes' => ['top' => ['Orange Blossom', 'Star Anise', 'Rose', 'Mandarin Orange', 'Pear', 'Bergamot'], 'middle' => ['Ylang-Ylang', 'Ginger', 'Orchid', 'Tuberose', 'Iris', 'Plum'], 'base' => ['Vanilla', 'Amber', 'Musk', 'Cinnamon', 'Sandalwood']],
            ],
            'classique-intense' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2014, 'perfumer' => 'Francis Kurkdjian', 'gender' => 'women',
                'description' => 'A more concentrated, deeper oriental-floral take on Classique, pairing tiare flower and pomegranate with a jasmine-rose heart and a vanilla-patchouli base.',
                'retail_price_usd' => 95, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Tiare Flower', 'Pomegranate'], 'middle' => ['Orange Blossom', 'Jasmine', 'Rose'], 'base' => ['Vanilla', 'Patchouli']],
            ],
            'la-belle-eau-de-parfum' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2019, 'perfumer' => 'Quentin Bisch, Sonia Constant', 'gender' => 'women',
                'description' => 'A luminous "amber green" gourmand built on juicy green pear enveloped in warm vanilla — an addictive, seductive fragrance and the female counterpart to Le Beau.',
                'retail_price_usd' => 150, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Bergamot', 'Pear'], 'middle' => ['Vanilla'], 'base' => ['Amber']],
            ],
            'la-belle-le-parfum' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2021, 'perfumer' => 'Quentin Bisch, Sonia Constant', 'gender' => 'women',
                'description' => 'A more intense, voluptuous reinterpretation of La Belle, adding sensual jasmine and tonka bean to the green pear and vanilla signature.',
                'retail_price_usd' => 148, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Pear'], 'middle' => ['Jasmine'], 'base' => ['Vanilla', 'Tonka Bean']],
            ],
            'le-beau-eau-de-toilette' => [
                'concentration' => 'Eau de Toilette', 'release_year' => 2019, 'perfumer' => null, 'gender' => 'men',
                'description' => 'Billed by JPG as "the original men\'s fragrance," opening with bright bergamot and developing an addictive sweetness of tonka bean and exotic coconut wood.',
                'retail_price_usd' => 138, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Bergamot'], 'middle' => ['Cedarwood', 'Coconut'], 'base' => ['Tonka Bean']],
            ],
            'le-beau-le-parfum' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2022, 'perfumer' => null, 'gender' => 'men',
                'description' => 'A more intense woody-amber version of Le Beau — fresh ginger opening, an ambergris development, closing on sandalwood and tonka bean.',
                'retail_price_usd' => 134, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Ginger'], 'middle' => ['Ambergris'], 'base' => ['Cedarwood', 'Coconut', 'Tonka Bean', 'Sandalwood']],
            ],
            'le-male-eau-de-toilette' => [
                'concentration' => 'Eau de Toilette', 'release_year' => 1995, 'perfumer' => 'Francis Kurkdjian', 'gender' => 'men',
                'description' => 'The iconic sailor-torso-bottle fragrance — an aromatic-fougère built on a shaving-soap-like mint-and-lavender opening over a warm vanilla base.',
                'retail_price_usd' => 76, 'bottle_sizes' => '40ml, 75ml, 125ml, 200ml',
                'notes' => ['top' => ['Mint', 'Lavender', 'Bergamot'], 'middle' => ['Lavender', 'Orange Blossom', 'Cinnamon'], 'base' => ['Vanilla', 'Sandalwood', 'Cedar', 'Tonka Bean', 'Amber']],
            ],
            'le-male-elixir' => [
                'concentration' => 'Parfum', 'release_year' => 2023, 'perfumer' => null, 'gender' => 'men',
                'description' => 'The highest-concentration Le Male variant — a sharp, cool mint opening, a lavender heart, settling into a honeyed, tobacco-tinged tonka bean and benzoin base.',
                'retail_price_usd' => 178, 'bottle_sizes' => '75ml, 125ml, 200ml',
                'notes' => ['top' => ['Mint'], 'middle' => ['Lavender'], 'base' => ['Tonka Bean', 'Benzoin']],
            ],
            'le-male-le-parfum' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2020, 'perfumer' => 'Quentin Bisch', 'gender' => 'men',
                'description' => 'A reinterpretation of Le Male with a cardamom opening, a lavender-and-iris heart, and a prominent, addictive vanilla base.',
                'retail_price_usd' => 160, 'bottle_sizes' => '75ml, 125ml, 200ml',
                'notes' => ['top' => ['Cardamom'], 'middle' => ['Lavender', 'Iris'], 'base' => ['Vanilla']],
            ],
            'scandal-le-parfum' => [
                'concentration' => 'Parfum', 'release_year' => 2021, 'perfumer' => null, 'gender' => 'women',
                'description' => 'A more intense edition of Scandal built on a dazzling jasmine opening, a salted-caramel heart, and a sensual black-vanilla base with woody-leathery undertones.',
                'retail_price_usd' => 172, 'bottle_sizes' => '30ml, 50ml, 80ml',
                'notes' => ['top' => ['Jasmine'], 'middle' => ['Caramel'], 'base' => ['Vanilla', 'Leather']],
            ],
            'scandal-pour-homme' => [
                'concentration' => 'Eau de Toilette', 'release_year' => 2021, 'perfumer' => null, 'gender' => 'men',
                'description' => 'A sensual woody-amber fragrance built around wildly sensual vetiver, a touch of fresh clary sage, and a near-overdosed tonka bean.',
                'retail_price_usd' => 129, 'bottle_sizes' => '50ml, 100ml, 150ml',
                'notes' => ['top' => ['Clary Sage'], 'middle' => ['Tonka Bean'], 'base' => ['Vetiver']],
            ],
            'so-scandal' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2020, 'perfumer' => null, 'gender' => 'women',
                'description' => 'Part of the Scandal women\'s fragrance line, continuing its warm, glamorous gourmand-oriental character.',
                'retail_price_usd' => 110, 'bottle_sizes' => '50ml, 80ml',
                'notes' => ['top' => ['Orange Blossom'], 'middle' => ['Jasmine'], 'base' => ['Vanilla', 'Patchouli']],
            ],
            'ultra-male' => [
                'concentration' => 'Eau de Toilette', 'release_year' => 2015, 'perfumer' => 'Francis Kurkdjian', 'gender' => 'men',
                'description' => 'A darker, spicier take on Le Male, combining dark lavender, pear juice, mint, and a woody vanilla-amber base.',
                'retail_price_usd' => 125, 'bottle_sizes' => '75ml, 125ml, 200ml',
                'notes' => ['top' => ['Lavender', 'Pear', 'Mint'], 'middle' => ['Vanilla', 'Woody Notes'], 'base' => ['Amber']],
            ],
        ];
    }

    private function xerjoffPerfumes(): array
    {
        return [
            '40-knots' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2012, 'perfumer' => null, 'gender' => 'unisex',
                'description' => 'A bright, salty-marine fragrance evoking a sailboat on the Mediterranean, built around sea salt, driftwood and musky woods.',
                'retail_price_usd' => 284, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Sea Salt', 'Bergamot', 'Mandarin Orange'], 'middle' => ['Driftwood', 'Seaweed', 'Violet Leaf'], 'base' => ['Musk', 'Leather', 'Styrax']],
            ],
            'alexandria-ii' => [
                'concentration' => 'Parfum', 'release_year' => 2012, 'perfumer' => null, 'gender' => 'unisex',
                'description' => 'An oriental-amber fragrance opening with apple, lavender and rosewood, moving into a classic Bulgarian rose heart, and settling into a rich Laotian oud, sandalwood, amber and vanilla base.',
                'retail_price_usd' => 590, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Palisander Rosewood', 'Lavender', 'Apple', 'Cinnamon'], 'middle' => ['Bulgarian Rose', 'Cedarwood', 'Lily of the Valley'], 'base' => ['Agarwood', 'Sandalwood', 'Amber', 'Vanilla', 'Musk']],
            ],
            'alexandria-iii-anniversary' => [
                'name' => 'Alexandria III',
                'concentration' => 'Parfum', 'release_year' => 2019, 'perfumer' => 'Chris Maurice', 'gender' => 'unisex',
                'description' => 'An oud-forward oriental fragrance from the Alexandria series, opening with lavender, rosewood and cinnamon, developing a Bulgarian rose and lily-of-the-valley heart, and settling into a rich oud base with sandalwood, amber and vanilla.',
                'retail_price_usd' => 318, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Lavender', 'Palisander Rosewood', 'Cinnamon'], 'middle' => ['Bulgarian Rose', 'Lily of the Valley', 'Cedarwood'], 'base' => ['Agarwood', 'Sandalwood', 'Amber', 'Vanilla', 'Musk']],
            ],
            'bouquet-ideale' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2010, 'perfumer' => 'Jacques Flori', 'gender' => 'women',
                'description' => 'Part of the Casamorati 1888 collection, a warm, spiced-woody gourmand built on cinnamon and nutmeg over guaiac wood and cedar, finished with vanilla, tobacco blossom and labdanum.',
                'retail_price_usd' => 264, 'bottle_sizes' => '30ml, 100ml',
                'notes' => ['top' => ['Cinnamon', 'Nutmeg'], 'middle' => ['Guaiac Wood', 'Sandalwood', 'Cedarwood'], 'base' => ['Vanilla', 'Coumarin', 'Tobacco Blossom', 'Labdanum', 'Musk']],
            ],
            'cruz-del-sur-ii' => [
                'concentration' => 'Parfum', 'release_year' => 2017, 'perfumer' => null, 'gender' => 'unisex',
                'description' => 'A tropical fruity-floral fragrance inspired by an Amazon rainforest expedition, opening with mango, guava and pineapple, moving through a green floral heart, and settling into a creamy musk, vetiver and cedarwood base.',
                'retail_price_usd' => 230, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Mango', 'Guava', 'Pineapple', 'Apple Blossom'], 'middle' => ['Black Currant', 'Green Notes', 'Violet Leaf'], 'base' => ['Milk', 'Dried Fruits', 'Musk', 'Vetiver', 'Cedarwood']],
            ],
            'dama-bianca' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2012, 'perfumer' => null, 'gender' => 'women',
                'description' => 'A creamy white-floral fragrance from the Casamorati 1888 collection, opening with kumquat and lime, developing into iris, jasmine and lily-of-the-valley, and closing on a soft base of vanilla, malt, musk and sandalwood.',
                'retail_price_usd' => 330, 'bottle_sizes' => '30ml, 100ml',
                'notes' => ['top' => ['Kumquat', 'Lime'], 'middle' => ['Violet', 'Iris', 'Lilac', 'Jasmine', 'Lily of the Valley'], 'base' => ['Vanilla', 'Malt', 'White Musk', 'Ambrette', 'Sandalwood', 'Cedarwood']],
            ],
            'erba-pura' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2019, 'perfumer' => 'Christian Carbonnel, Laura Santander', 'gender' => 'unisex',
                'description' => 'A bright citrus-fruity fragrance evoking Mediterranean old-town streets at golden hour, opening with Sicilian orange, bergamot and lemon over a fruity heart, settling into white musk, vanilla and amber.',
                'retail_price_usd' => 240, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Orange', 'Calabrian Bergamot', 'Lemon'], 'middle' => ['Fruity Notes'], 'base' => ['White Musk', 'Vanilla', 'Amber']],
            ],
            'golden-green' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2020, 'perfumer' => null, 'gender' => 'unisex',
                'description' => "Part of Xerjoff's Coffee Break collection, an aromatic-spicy fragrance opening with cardamom, juniper and pink pepper, moving through a smoky, leathery cedar-incense-vetiver heart, and grounding into coffee, cypriol, musk and ambergris.",
                'retail_price_usd' => 165, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Cardamom', 'Juniper Berries', 'Nutmeg', 'Pink Pepper'], 'middle' => ['Cedar', 'Incense', 'Vetiver', 'Leather', 'Labdanum'], 'base' => ['Coffee', 'Cypriol', 'Musk', 'Ambergris']],
            ],
            'italica' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2016, 'perfumer' => null, 'gender' => 'unisex',
                'description' => 'A gourmand fragrance centered on almond paste, evoking Southern Italian desserts. Opens with almond, milk and saffron, moves through toffee and vanilla, and settles into sandalwood, cedarwood and white musk.',
                'retail_price_usd' => 293, 'bottle_sizes' => '30ml, 100ml',
                'notes' => ['top' => ['Almond', 'Milk', 'Saffron'], 'middle' => ['Toffee', 'Vanilla'], 'base' => ['Sandalwood', 'White Musk', 'Cedarwood']],
            ],
            'kobe' => [
                'concentration' => 'Parfum', 'release_year' => 2009, 'perfumer' => 'Jacques Flori', 'gender' => 'men',
                'description' => 'A citrus-aromatic fragrance from the meteorite-themed Shooting Stars collection, opening bright with orange, bergamot and neroli, then warming into rosewood, oud, benzoin and tonka bean.',
                'retail_price_usd' => 250, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Orange', 'Bergamot', 'Labdanum'], 'middle' => ['Neroli', 'Petitgrain', 'Orange Blossom'], 'base' => ['Brazilian Rosewood', 'Benzoin', 'Tonka Bean', 'Ambergris', 'Agarwood', 'Musk', 'Styrax']],
            ],
            'la-capitale' => [
                'concentration' => 'Extrait de Parfum', 'release_year' => 2018, 'perfumer' => 'Chris Maurice', 'gender' => 'unisex',
                'description' => 'A gourmand-oriental fragrance inspired by Moscow, opening with caramel-dipped strawberry and peach, developing into a leathery floral heart of rose, saffron and ginger, and finishing in vanilla and benzoin.',
                'retail_price_usd' => 260, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Strawberry', 'Caramel', 'Peach', 'Labdanum'], 'middle' => ['Leather', 'Amber', 'Saffron', 'Rose', 'Ginger'], 'base' => ['Vanilla', 'Benzoin']],
            ],
            'lira' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2011, 'perfumer' => 'Chris Maurice', 'gender' => 'women',
                'description' => 'A gourmand-floral fragrance from the Casamorati 1888 collection, opening with blood orange and bergamot, developing into cinnamon, jasmine and rose, and finishing with caramel, vanilla and musk.',
                'retail_price_usd' => 264, 'bottle_sizes' => '30ml, 100ml',
                'notes' => ['top' => ['Blood Orange', 'Bergamot', 'Lavender'], 'middle' => ['Cinnamon', 'Licorice', 'Jasmine', 'Rose'], 'base' => ['Caramel', 'Vanilla', 'Musk']],
            ],
            'mamluk' => [
                'concentration' => 'Parfum', 'release_year' => 2012, 'perfumer' => 'Chris Maurice', 'gender' => 'unisex',
                'description' => 'An oriental-woody Oud Stars fragrance blending smoky benzoin resin, honeyed caramel, jasmine and osmanthus florals with an oud, vanilla and amber base.',
                'retail_price_usd' => 325, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Calabrian Bergamot', 'Honey', 'Caramel'], 'middle' => ['Jasmine', 'Osmanthus', 'Benzoin'], 'base' => ['Vanilla', 'Agarwood', 'Musk', 'Amber']],
            ],
            'mefisto' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2009, 'perfumer' => 'Jacques Flori', 'gender' => 'unisex',
                'description' => 'A fresh, woody Casamorati 1888 fragrance with citrus-lavender top notes, an iris-rose heart, and a warm amber-sandalwood-musk base.',
                'retail_price_usd' => 264, 'bottle_sizes' => '30ml, 100ml',
                'notes' => ['top' => ['Lemon', 'Grapefruit', 'Bergamot', 'Lavender'], 'middle' => ['Iris', 'Rose'], 'base' => ['Amber', 'Cedarwood', 'Musk', 'Sandalwood']],
            ],
            'mefisto-gentiluomo' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2018, 'perfumer' => null, 'gender' => 'men',
                'description' => 'The masculine companion to Mefisto in the Casamorati 1888 line, a fresh, clean citrus-lavender opening over a violet-iris-rose heart and woody amber-musk base.',
                'retail_price_usd' => 264, 'bottle_sizes' => '100ml',
                'notes' => ['top' => ['Lavender', 'Grapefruit', 'Bergamot', 'Lemon'], 'middle' => ['Violet', 'Iris', 'Rose'], 'base' => ['Musk', 'Cedar', 'Amber']],
            ],
            'more-than-words' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2012, 'perfumer' => 'Chris Maurice', 'gender' => 'unisex',
                'description' => 'Part of the Join The Club collection, a woody-floral-oriental fragrance built around oud, ambergris and labdanum.',
                'retail_price_usd' => 284, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Fruity Notes', 'Floral Notes'], 'middle' => ['Agarwood', 'Incense'], 'base' => ['Ambergris', 'Woody Notes', 'Labdanum']],
            ],
            'naxos' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2015, 'perfumer' => null, 'gender' => 'unisex',
                'description' => 'Part of the XJ 1861 collection, an aromatic-spicy tribute to Sicily. Opens with fresh bergamot, lemon and lavender, warms through cinnamon, honey and jasmine, and settles into tobacco leaf, tonka bean and vanilla.',
                'retail_price_usd' => 255, 'bottle_sizes' => '100ml',
                'notes' => ['top' => ['Bergamot', 'Lemon', 'Lavender', 'Incense'], 'middle' => ['Cinnamon', 'Honey', 'Cashmere Wood', 'Jasmine'], 'base' => ['Tobacco Leaf', 'Tonka Bean', 'Vanilla']],
            ],
            'nio' => [
                'concentration' => 'Parfum', 'release_year' => 2009, 'perfumer' => 'Jacques Flori', 'gender' => 'men',
                'description' => 'A fresh woody-aromatic fragrance from the meteorite-themed Shooting Stars collection, combining citrus brightness with green leaves, spice, and a vetiver-cedar-guaiac wood base.',
                'retail_price_usd' => 240, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Bergamot', 'Green Notes', 'Neroli'], 'middle' => ['Cardamom', 'Pink Pepper', 'Jasmine', 'Nutmeg'], 'base' => ['Vetiver', 'Cedarwood', 'Guaiac Wood', 'Amber', 'Patchouli']],
            ],
            'renaissance' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2011, 'perfumer' => null, 'gender' => 'unisex',
                'description' => 'Part of the XJ 1861 trio celebrating Italian heritage, a fresh citrus-floral fragrance opening with mandarin, lemon and bergamot, moving through mint and lily-of-the-valley with Bulgarian rose, and settling into musk, cedarwood, amber and patchouli.',
                'retail_price_usd' => 255, 'bottle_sizes' => '100ml',
                'notes' => ['top' => ['Mandarin Orange', 'Lemon', 'Calabrian Bergamot', 'Bitter Orange', 'Petitgrain'], 'middle' => ['Mint', 'Lily of the Valley', 'Bulgarian Rose'], 'base' => ['Musk', 'Cedarwood', 'Amber', 'Patchouli']],
            ],
            'torino-21' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2021, 'perfumer' => null, 'gender' => 'unisex',
                'description' => 'Inspired by professional tennis in Turin, capturing the pre-match atmosphere. An aromatic-green fragrance built on mint, citrus and herbs over a musky lemon verbena base.',
                'retail_price_usd' => 288, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Mint', 'Lemon', 'Basil', 'Thyme'], 'middle' => ['Black Currant', 'Lavender', 'Rosemary', 'Jasmine'], 'base' => ['Verbena', 'Musk']],
            ],
            'torino-22' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2022, 'perfumer' => null, 'gender' => 'unisex',
                'description' => 'The follow-up to Torino21, also themed around professional tennis, celebrating powerful serves and competitive intensity. A woody-aromatic fragrance opening with bergamot, saffron and eucalyptus over a mate-guaiac wood heart and dry musky wood base.',
                'retail_price_usd' => 191, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Bergamot', 'Saffron', 'Eucalyptus'], 'middle' => ['Clary Sage', 'Mate', 'Guaiac Wood'], 'base' => ['Musk', 'Woody Notes']],
            ],
            'uden' => [
                'concentration' => 'Parfum', 'release_year' => 2009, 'perfumer' => null, 'gender' => 'men',
                'description' => 'Named for a meteorite that fell in Uden, Netherlands, part of the meteorite-themed Shooting Stars collection. Evokes a breezy North Sea coastline with citrus top notes, a rum-guaiac wood-rose heart, and a coffee-vanilla-musk base.',
                'retail_price_usd' => 230, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Citrus Notes', 'Lemon', 'Grapefruit'], 'middle' => ['Rum', 'Guaiac Wood', 'Sandalwood', 'Rose'], 'base' => ['Vanilla', 'Coffee', 'Musk', 'Ambergris']],
            ],
        ];
    }

    private function kilianPerfumes(): array
    {
        return [
            'angels-share' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2020, 'perfumer' => 'Benoist Lapouza', 'gender' => 'unisex',
                'description' => "A boozy gourmand built around a genuine cognac accord, opening on warm oak and cinnamon before settling into praline, vanilla and sandalwood. One of Kilian's best-selling scents.",
                'retail_price_usd' => 285, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Cognac'], 'middle' => ['Oak', 'Cinnamon', 'Tonka Bean', 'Hedione'], 'base' => ['Praline', 'Vanilla', 'Sandalwood', 'Almond']],
            ],
            'apple-brandy-on-the-rocks' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2021, 'perfumer' => 'Sidonie Lancesseur', 'gender' => 'unisex',
                'description' => 'A boozy, fruity-woody scent pairing a tart apple-brandy/rum liqueur accord with cardamom and pineapple, resting on smoked cedar, moss, vanilla and ambroxan.',
                'retail_price_usd' => 270, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Bergamot', 'Cardamom', 'Pineapple'], 'middle' => ['Apple', 'Rum', 'Oak', 'Cognac', 'Labdanum', 'Moss'], 'base' => ['Cedar', 'Vanilla', 'Ambroxan']],
            ],
            'back-to-black-aphrodisiac' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2009, 'perfumer' => 'Calice Becker', 'gender' => 'unisex',
                'description' => "A dark oriental blending honeyed spices with tart raspberry and chamomile over incense, cedar, oak, tobacco, patchouli and vanilla. Part of Kilian's founding L'Oeuvre Noire collection.",
                'retail_price_usd' => 290, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Bergamot', 'Saffron', 'Nutmeg', 'Cardamom', 'Coriander', 'Raspberry'], 'middle' => ['Chamomile', 'Incense', 'Honey'], 'base' => ['Cedar', 'Oak', 'Tobacco Leaf', 'Patchouli', 'Almond', 'Vanilla', 'Labdanum']],
            ],
            'black-phantom-memento-mori' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2017, 'perfumer' => 'Sidonie Lancesseur', 'gender' => 'unisex',
                'description' => 'A dense gourmand opening on rum and a bitter almond accord, moving through dark coffee absolute and woods into a sugar-cane and caramel base.',
                'retail_price_usd' => 250, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Rum', 'Almond'], 'middle' => ['Coffee', 'Patchouli', 'Vetiver'], 'base' => ['Caramel', 'Heliotrope', 'Sandalwood', 'Almond']],
            ],
            'good-girl-gone-bad' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2012, 'perfumer' => 'Alberto Morillas', 'gender' => 'women',
                'description' => 'A white-floral fragrance built on orange blossom and rose opening into osmanthus and narcotic tuberose over a narcissus base.',
                'retail_price_usd' => 295, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Orange Blossom', 'Rose'], 'middle' => ['Osmanthus', 'Tuberose', 'Jasmine'], 'base' => ['Narcissus']],
            ],
            'good-girl-gone-bad-extreme' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2017, 'perfumer' => 'Alberto Morillas', 'gender' => 'women',
                'description' => 'An intensified flanker of Good Girl Gone Bad, adding peach, cherry and a milk accord to the same tuberose-jasmine-osmanthus skeleton, resting on amber, caramel, musk and white cedar.',
                'retail_price_usd' => 300, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Osmanthus', 'Jasmine', 'Rose', 'Peach', 'Cherry'], 'middle' => ['Tuberose', 'Milk', 'Narcissus', 'Orange Blossom'], 'base' => ['Amber', 'Cedarwood', 'Caramel', 'Musk']],
            ],
            'intoxicated' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2014, 'perfumer' => 'Calice Becker', 'gender' => 'unisex',
                'description' => 'An oriental-spicy gourmand centered on a Turkish/mocha coffee accord entwined with green cardamom, warmed by cinnamon and nutmeg over a vanilla-vetiver base.',
                'retail_price_usd' => 280, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Coffee', 'Cardamom', 'Bergamot', 'Aldehydes'], 'middle' => ['Nutmeg', 'Cinnamon'], 'base' => ['Vanilla', 'Vetiver']],
            ],
            'love-dont-be-shy' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2007, 'perfumer' => 'Calice Becker', 'gender' => 'women',
                'description' => "A sweet floral gourmand built around a signature marshmallow accord alongside orange blossom, neroli, honeysuckle, jasmine and rose. One of Kilian's most iconic releases.",
                'retail_price_usd' => 295, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Calabrian Bergamot', 'Neroli', 'Pink Pepper', 'Coriander'], 'middle' => ['Honeysuckle', 'Orange Blossom', 'Jasmine', 'Rose', 'Iris'], 'base' => ['Sugar', 'Vanilla', 'Labdanum', 'White Musk']],
            ],
            'love-dont-be-shy-extreme' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2021, 'perfumer' => 'Calice Becker', 'gender' => 'women',
                'description' => "A more intense, rose-forward flanker of Love, Don't Be Shy, pairing neroli and bergamot with Bulgarian rose and orange blossom over the same marshmallow-vanilla-musk base, plus pomegranate.",
                'retail_price_usd' => 340, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Neroli', 'Bergamot'], 'middle' => ['Orange Blossom', 'Bulgarian Rose'], 'base' => ['Vanilla', 'Musk', 'Pomegranate']],
            ],
            'moonlight-in-heaven' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2016, 'perfumer' => 'Calice Becker', 'gender' => 'unisex',
                'description' => 'A fruity-gourmand-floral scent opening on citrus, pink pepper and peach, moving through mango, coconut and a milky rice accord with tuberose, and settling into vetiver, tonka and musk.',
                'retail_price_usd' => 285, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Lemon', 'Grapefruit', 'Pink Pepper', 'Green Notes', 'Peach', 'Clove'], 'middle' => ['Mango', 'Coconut', 'Rice', 'Tuberose', 'Orris Root'], 'base' => ['Vetiver', 'Tonka Bean', 'Musk', 'Vanilla']],
            ],
            'musk-oud' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2013, 'perfumer' => 'Alberto Morillas', 'gender' => 'unisex',
                'description' => "Part of Kilian's Arabian Nights collection, opening with lemon, mandarin, cardamom and coriander, moving into a syrupy Bulgarian rose heart, and closing on agarwood, incense and patchouli.",
                'retail_price_usd' => 430, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Lemon', 'Mandarin Orange', 'Cardamom', 'Coriander'], 'middle' => ['Bulgarian Rose', 'Cypress', 'Geranium', 'Rum'], 'base' => ['Agarwood', 'Musk', 'Incense', 'Patchouli']],
            ],
            'rolling-in-love' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2019, 'perfumer' => 'Pascal Gaurin', 'gender' => 'unisex',
                'description' => 'Described by Kilian as a "musc de peau" (skin musk), opening with almond milk and ambrette seed, moving through iris and freesia into a musk, tuberose, tonka and vanilla base.',
                'retail_price_usd' => 280, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Almond', 'Milk', 'Ambrette'], 'middle' => ['Iris', 'Freesia'], 'base' => ['Musk', 'Tuberose', 'Tonka Bean', 'Vanilla']],
            ],
            'roses-on-ice' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2020, 'perfumer' => 'Frank Voelkl', 'gender' => 'unisex',
                'description' => 'A fresh, ozonic take on rose, opening with cucumber, juniper berries, lime and pink pepper, moving into a cool rose heart, and resting on musk, sandalwood, ambroxan and cedar.',
                'retail_price_usd' => 285, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Cucumber', 'Juniper Berries', 'Lime', 'Violet Leaf', 'Pink Pepper'], 'middle' => ['Rose'], 'base' => ['Musk', 'Sandalwood', 'Ambroxan', 'Cedar']],
            ],
            'smoking-hot' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2023, 'perfumer' => 'Mathieu Nardin', 'gender' => 'unisex',
                'description' => 'A smoky-sweet gourmand blending shisha-style apple tobacco with fire-cured tobacco and cinnamon, oakmoss and clary sage in the heart, and vanilla, licorice and Iso E Super in the base.',
                'retail_price_usd' => 270, 'bottle_sizes' => '30ml, 50ml, 100ml',
                'notes' => ['top' => ['Apple', 'Cinnamon'], 'middle' => ['Tobacco Leaf', 'Oakmoss', 'Clary Sage'], 'base' => ['Vanilla', 'Licorice', 'Iso E Super']],
            ],
            'straight-to-heaven-white-cristal' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2007, 'perfumer' => 'Sidonie Lancesseur', 'gender' => 'men',
                'description' => "One of Kilian's original 2007 L'Oeuvre Noire fragrances, built on a dark rum accord softened by nutmeg, patchouli, vanilla, dried fruit and cedarwood.",
                'retail_price_usd' => 290, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Rum', 'Nutmeg'], 'middle' => ['Patchouli'], 'base' => ['Vanilla', 'Cedarwood', 'Dried Fruits']],
            ],
            'vodka-on-the-rocks' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2014, 'perfumer' => 'Sidonie Lancesseur', 'gender' => 'unisex',
                'description' => 'A cool, aldehydic floral built to evoke chilled vodka, opening with aldehydes, cardamom and coriander, moving through a bitter-rhubarb-rose heart, and closing on oakmoss, sandalwood and leather.',
                'retail_price_usd' => 285, 'bottle_sizes' => '50ml',
                'notes' => ['top' => ['Aldehydes', 'Coriander', 'Cardamom'], 'middle' => ['Rhubarb', 'Rose', 'Lily of the Valley'], 'base' => ['Oakmoss', 'Sandalwood', 'Leather']],
            ],
        ];
    }

    private function creedPerfumes(): array
    {
        return [
            'aventus' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2010, 'perfumer' => 'Jean-Christophe Hérault', 'gender' => 'men',
                'description' => "A fruity-chypre fragrance built around a smoky birch and pineapple accord, opening with tart bergamot and blackcurrant before settling into a woody, ambergris-and-oakmoss base. Creed's flagship, best-selling scent.",
                'retail_price_usd' => 500, 'bottle_sizes' => '30ml, 50ml, 100ml, 240ml',
                'notes' => ['top' => ['Calabrian Bergamot', 'Black Currant', 'Apple', 'Pineapple', 'Lemon', 'Pink Pepper'], 'middle' => ['Rose', 'Jasmine', 'Birch', 'Patchouli'], 'base' => ['Oakmoss', 'Ambergris', 'Musk', 'Vanilla', 'Cedarwood', 'Birch']],
            ],
            'aventus-cologne' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2018, 'perfumer' => null, 'gender' => 'unisex',
                'description' => 'A fresher, more citrus-forward reworking of Aventus that keeps the birch/musk/ambroxan woody heart but replaces the heavier pineapple-forward opening with brighter citrus and ginger.',
                'retail_price_usd' => 500, 'bottle_sizes' => '50ml, 100ml, 240ml',
                'notes' => ['top' => ['Calabrian Bergamot', 'Mandarin Orange', 'Sea Notes'], 'middle' => ['Ginger', 'Pineapple', 'Pink Pepper'], 'base' => ['Musk', 'Patchouli', 'Birch']],
            ],
            'bois-du-portugal' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 1987, 'perfumer' => 'Olivier Creed', 'gender' => 'men',
                'description' => 'A classic woody-spicy men\'s fragrance built on a bergamot opening and lavender heart, resolving into a warm sandalwood, cedar and vetiver base.',
                'retail_price_usd' => 490, 'bottle_sizes' => '50ml, 100ml, 240ml',
                'notes' => ['top' => ['Bergamot', 'Lemon', 'Lime', 'Mandarin Orange', 'Basil'], 'middle' => ['Lavender'], 'base' => ['Sandalwood', 'Cedar', 'Vetiver', 'Ambergris', 'Patchouli']],
            ],
            'carmina' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2023, 'perfumer' => null, 'gender' => 'women',
                'description' => 'A modern amber-floral women\'s fragrance centered on rose de mai and cashmere wood, opening with black cherry and saffron and settling into a resinous myrrh-frankincense-musk base.',
                'retail_price_usd' => 460, 'bottle_sizes' => '30ml, 75ml, 240ml',
                'notes' => ['top' => ['Pink Pepper', 'Cherry', 'Saffron'], 'middle' => ['Rose', 'Violet', 'Peony', 'Cashmere Wood'], 'base' => ['Myrrh', 'Incense', 'Ambroxan', 'Musk']],
            ],
            'erolfa' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 1992, 'perfumer' => 'Olivier Creed', 'gender' => 'unisex',
                'description' => 'A fresh, aromatic fougère/marine composition evoking Mediterranean sailing, with a citrus-herbal opening, an aromatic-floral heart, and a woody musk base.',
                'retail_price_usd' => 490, 'bottle_sizes' => '50ml, 100ml, 240ml',
                'notes' => ['top' => ['Bergamot', 'Lemon', 'Lime', 'Melon', 'Violet Leaf', 'Orange', 'Mandarin Orange', 'Cumin', 'Basil', 'Rosemary'], 'middle' => ['Ginger', 'Coriander', 'Angelica', 'Juniper Berries', 'Jasmine', 'Ylang-Ylang', 'Rose'], 'base' => ['Ambergris', 'Cedarwood', 'Musk', 'Oakmoss', 'Orris Root', 'Sandalwood']],
            ],
            'green-irish-tweed' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 1985, 'perfumer' => 'Olivier Creed', 'gender' => 'men',
                'description' => "A precision fougère considered one of Creed's most iconic scents, opening with fresh citrus-mint-galbanum, moving through a green floral heart, and finishing on a clean sandalwood/ambergris base.",
                'retail_price_usd' => 500, 'bottle_sizes' => '50ml, 100ml, 240ml',
                'notes' => ['top' => ['Iris', 'Verbena', 'Lemon', 'Bergamot', 'Galbanum', 'Peppermint'], 'middle' => ['Violet Leaf', 'Geranium', 'Lavender', 'Violet'], 'base' => ['Ambergris', 'Sandalwood', 'Cedarwood', 'Ambroxan', 'Oakmoss']],
            ],
            'himalaya' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2002, 'perfumer' => 'Olivier Creed, Erwin Creed', 'gender' => 'men',
                'description' => 'A woody-fresh, spicy-aromatic fragrance created to commemorate a Himalayan mountain climb, opening with citrus and developing through a spicy sandalwood/vetiver heart into a musky ambergris base.',
                'retail_price_usd' => 500, 'bottle_sizes' => '50ml, 100ml, 250ml',
                'notes' => ['top' => ['Calabrian Bergamot', 'Grapefruit', 'Lemon', 'Mandarin Orange'], 'middle' => ['Sandalwood', 'Pink Pepper', 'Nutmeg', 'Cedarwood', 'Vetiver', 'Pepper'], 'base' => ['Cedar', 'Musk', 'Ambergris', 'Tonka Bean']],
            ],
            'love-in-black' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2008, 'perfumer' => 'Olivier Creed, Erwin Creed', 'gender' => 'women',
                'description' => 'An opulent, powdery floral fragrance built around Parma-violet and iris notes with a black currant and Bulgarian rose base.',
                'retail_price_usd' => 460, 'bottle_sizes' => '75ml, 250ml',
                'notes' => ['top' => ['Violet', 'Cedarwood'], 'middle' => ['Iris', 'Musk', 'Clove'], 'base' => ['Black Currant', 'Bulgarian Rose']],
            ],
            'love-in-white' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2005, 'perfumer' => 'Olivier Creed, Erwin Creed', 'gender' => 'women',
                'description' => 'A romantic oriental-floral fragrance blending Bulgarian rose and Florentine iris with Italian orange and warm vanilla-sandalwood.',
                'retail_price_usd' => 460, 'bottle_sizes' => '30ml, 75ml, 240ml',
                'notes' => ['top' => ['Orange', 'Apple', 'Apricot'], 'middle' => ['Magnolia', 'Narcissus', 'Rice', 'Jasmine', 'Iris', 'Bulgarian Rose'], 'base' => ['Ambergris', 'Sandalwood', 'Vanilla', 'Musk', 'Cedarwood']],
            ],
            'millesime-imperial' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 1995, 'perfumer' => 'Olivier Creed', 'gender' => 'unisex',
                'description' => 'A citrus-marine fragrance evoking sun-drenched Mediterranean coastline, opening with bright citrus and blackcurrant, developing an ambergris-forward marine accord, and finishing clean and musky.',
                'retail_price_usd' => 490, 'bottle_sizes' => '50ml, 100ml, 240ml',
                'notes' => ['top' => ['Bergamot', 'Black Currant', 'Violet Leaf'], 'middle' => ['Orris Root', 'Sea Notes'], 'base' => ['Cedarwood', 'Musk', 'Sandalwood']],
            ],
            'original-santal' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2005, 'perfumer' => 'Olivier Creed, Erwin Creed', 'gender' => 'unisex',
                'description' => 'A spicy-woody-amber fragrance opening with warm ginger and coriander, moving through lavender and orange blossom, and settling into a sandalwood-tonka-vanilla base.',
                'retail_price_usd' => 490, 'bottle_sizes' => '50ml, 100ml, 240ml',
                'notes' => ['top' => ['Ginger', 'Juniper Berries', 'Bergamot', 'Rosemary', 'Coriander', 'Mandarin Orange', 'Cinnamon'], 'middle' => ['Lavender', 'Geranium', 'Orange Blossom', 'Sandalwood'], 'base' => ['Oakmoss', 'Musk', 'Tonka Bean', 'Cedarwood', 'Vanilla', 'Benzoin', 'Ambergris']],
            ],
            'original-vetiver' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2004, 'perfumer' => 'Olivier Creed, Erwin Creed', 'gender' => 'unisex',
                'description' => 'A bright, green, modern take on vetiver with a cologne-like freshness, built on Haitian vetiver and sandalwood over a clean musk base.',
                'retail_price_usd' => 490, 'bottle_sizes' => '50ml, 100ml, 240ml',
                'notes' => ['top' => ['Bergamot', 'Ginger', 'Mandarin Orange', 'Lemon', 'Green Notes'], 'middle' => ['Vetiver', 'Sandalwood', 'Iris', 'Cypress'], 'base' => ['Musk', 'Ambergris', 'White Musk']],
            ],
            'royal-oud' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2011, 'perfumer' => 'Olivier Creed, Erwin Creed', 'gender' => 'unisex',
                'description' => 'A Western-style reinterpretation of oud that trades heavy, medicinal Middle-Eastern oud characteristics for a lighter, more luminous and wearable woody-incense composition.',
                'retail_price_usd' => 590, 'bottle_sizes' => '50ml, 100ml, 240ml',
                'notes' => ['top' => ['Calabrian Bergamot', 'Lemon', 'Pink Pepper'], 'middle' => ['Angelica', 'Cedar', 'Galbanum'], 'base' => ['Sandalwood', 'Agarwood', 'Musk', 'Incense', 'Benzoin']],
            ],
            'royal-water' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 1997, 'perfumer' => 'Olivier Creed', 'gender' => 'unisex',
                'description' => 'A citrus-aromatic fragrance with a strong opening blast of citrus and mint over an unusually dark, herbaceous base for the genre.',
                'retail_price_usd' => 490, 'bottle_sizes' => '50ml, 100ml, 250ml',
                'notes' => ['top' => ['Mint', 'Bergamot', 'Lime', 'Lemon', 'Mandarin Orange', 'Verbena', 'Peppermint'], 'middle' => ['Basil', 'Juniper Berries', 'Cumin', 'Pepper', 'Pink Pepper'], 'base' => ['Musk', 'Ambergris']],
            ],
            'silver-mountain-water' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 1995, 'perfumer' => 'Olivier Creed', 'gender' => 'unisex',
                'description' => 'A clean, contemporary aromatic-musk fragrance inspired by skiing trips in the Swiss Alps, opening with citrus and blackcurrant, moving through a green-tea heart, and finishing in soft musky sandalwood.',
                'retail_price_usd' => 480, 'bottle_sizes' => '30ml, 50ml, 100ml, 240ml',
                'notes' => ['top' => ['Bergamot', 'Mandarin Orange', 'Orange'], 'middle' => ['Green Tea', 'Black Currant'], 'base' => ['Musk', 'Sandalwood', 'Petitgrain']],
            ],
            'tabarome' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2000, 'perfumer' => 'Olivier Creed', 'gender' => 'men',
                'description' => 'A warm, woody tobacco fragrance with ginger brightening a base of tobacco, leather, and sandalwood.',
                'retail_price_usd' => 490, 'bottle_sizes' => '50ml, 100ml, 240ml',
                'notes' => ['top' => ['Bergamot'], 'middle' => ['Ginger'], 'base' => ['Tobacco Leaf', 'Sandalwood', 'Ambergris', 'Leather', 'Patchouli']],
            ],
            'viking' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2017, 'perfumer' => 'Olivier Creed, Erwin Creed', 'gender' => 'men',
                'description' => 'A spicy aromatic fougère with a bright citrus-mint opening and a clove-and-rose-forward heart, resolving into a woody tonka-vetiver base.',
                'retail_price_usd' => 510, 'bottle_sizes' => '50ml, 100ml, 240ml',
                'notes' => ['top' => ['Bergamot', 'Lemon', 'Orange', 'Peppermint', 'Pink Pepper'], 'middle' => ['Clove', 'Jasmine', 'Lavender', 'Orris Root', 'Bulgarian Rose'], 'base' => ['Cedarwood', 'Tonka Bean', 'Vetiver', 'White Musk']],
            ],
            'viking-cologne' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2021, 'perfumer' => null, 'gender' => 'men',
                'description' => 'A fresh, woody-spicy flanker to Viking inspired by springtime adventures in Norwegian fjords, combining dry sandalwood and bergamot with nutmeg and frankincense.',
                'retail_price_usd' => 320, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Bergamot', 'Mandarin Orange'], 'middle' => ['Lavender', 'Vetiver', 'Nutmeg'], 'base' => ['Sandalwood', 'Incense']],
            ],
            'virgin-island-water' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2007, 'perfumer' => 'Olivier Creed, Erwin Creed', 'gender' => 'unisex',
                'description' => 'A tropical, Caribbean-inspired fragrance opening with zesty citrus and coconut, developing a jasmine-ylang-ylang floral heart, and finishing with a warm, slightly boozy rum, tonka and musk base.',
                'retail_price_usd' => 490, 'bottle_sizes' => '50ml, 100ml, 240ml',
                'notes' => ['top' => ['Coconut', 'Lime', 'Calabrian Bergamot', 'Mandarin Orange'], 'middle' => ['Ginger', 'Ylang-Ylang', 'Jasmine', 'Hibiscus', 'Coconut'], 'base' => ['Rum', 'Sugar', 'Musk', 'Tonka Bean']],
            ],
            'wind-flowers' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2021, 'perfumer' => null, 'gender' => 'women',
                'description' => 'A floral-amber-gourmand fragrance built on layered white florals over a soft iris-sandalwood-praline base.',
                'retail_price_usd' => 460, 'bottle_sizes' => '30ml, 75ml, 250ml',
                'notes' => ['top' => ['Jasmine', 'Orange Blossom', 'Peach'], 'middle' => ['Jasmine', 'Tuberose', 'Rose'], 'base' => ['Iris', 'Orange Blossom', 'Musk', 'Sandalwood', 'Praline']],
            ],
        ];
    }

    private function armaniPerfumes(): array
    {
        return [
            'acqua-di-gio-absolu' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2018, 'perfumer' => 'Alberto Morillas', 'gender' => 'men',
                'description' => 'A deeper, more intense reinterpretation of the Acqua di Giò aquatic line, opening with bright citrus/fruit and marine facets over an aromatic lavender-rosemary heart, settling into a warm, ambery-woody patchouli base.',
                'retail_price_usd' => 130, 'bottle_sizes' => '40ml, 75ml, 125ml, 200ml',
                'notes' => ['top' => ['Sea Notes', 'Bergamot', 'Pear', 'Apple', 'Grapefruit', 'Lemon'], 'middle' => ['Lavender', 'Rosemary', 'Geranium'], 'base' => ['Woody Notes', 'Tonka Bean', 'Amberwood', 'Patchouli', 'Labdanum']],
            ],
            'acqua-di-gio-eau-de-toilette' => [
                'concentration' => 'Eau de Toilette', 'release_year' => 1996, 'perfumer' => 'Alberto Morillas, Annick Ménardo', 'gender' => 'men',
                'description' => "The defining modern aquatic/marine men's fragrance, inspired by the Mediterranean. Fresh, citrusy-marine opening over an aromatic rosemary heart and a warm woody-musky base; widely credited with popularizing the aquatic fragrance genre.",
                'retail_price_usd' => 130, 'bottle_sizes' => '30ml, 50ml, 100ml, 200ml',
                'notes' => ['top' => ['Sea Notes', 'Calabrian Bergamot', 'Neroli', 'Mandarin Orange'], 'middle' => ['Rosemary', 'Persimmon', 'Patchouli'], 'base' => ['Cedarwood']],
            ],
            'acqua-di-gio-profondo' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2020, 'perfumer' => 'Alberto Morillas', 'gender' => 'men',
                'description' => 'A darker, more mineral and oceanic take on the Acqua di Giò aquatic family, combining a fresh marine-citrus opening with an aromatic rosemary-lavender heart and a mineral-amber, patchouli-musk base.',
                'retail_price_usd' => 130, 'bottle_sizes' => '40ml, 75ml, 125ml, 200ml',
                'notes' => ['top' => ['Sea Notes', 'Bergamot', 'Mandarin Orange'], 'middle' => ['Rosemary', 'Lavender', 'Cypress', 'Elemi'], 'base' => ['Amber', 'Patchouli', 'Musk']],
            ],
            'acqua-di-gio-profumo' => [
                'concentration' => 'Parfum', 'release_year' => 2015, 'perfumer' => 'Alberto Morillas', 'gender' => 'men',
                'description' => 'An aromatic-aquatic-woody-spicy fragrance, an elegant, airy yet deep evolution of the original Acqua di Giò. Built around an incense-and-patchouli base under a sea-note/bergamot opening and a sage-rosemary heart.',
                'retail_price_usd' => 170, 'bottle_sizes' => '40ml, 75ml, 125ml, 180ml',
                'notes' => ['top' => ['Sea Notes', 'Bergamot'], 'middle' => ['Rosemary', 'Sage', 'Geranium'], 'base' => ['Incense', 'Patchouli']],
            ],
            'armani-code-eau-de-parfum' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2021, 'perfumer' => 'Antoine Maisondieu', 'gender' => 'men',
                'description' => 'A warm, seductive oriental-fougère built around a tonka bean heart, opening with lavender and citrus before settling into a soft, musky vanilla-suede base.',
                'retail_price_usd' => 145, 'bottle_sizes' => '30ml, 50ml, 75ml, 125ml',
                'notes' => ['top' => ['Lavender', 'Lemon', 'Bergamot'], 'middle' => ['Tonka Bean'], 'base' => ['Vanilla', 'Leather']],
            ],
            'armani-code-parfum' => [
                'concentration' => 'Parfum', 'release_year' => 2022, 'perfumer' => 'Antoine Maisondieu', 'gender' => 'men',
                'description' => 'The most intense concentration in the Code line, pairing an iris/orris-driven heart with clary sage and a bergamot opening over a tonka bean and cedar base.',
                'retail_price_usd' => 205, 'bottle_sizes' => '30ml, 50ml, 75ml, 125ml',
                'notes' => ['top' => ['Bergamot'], 'middle' => ['Orris Root', 'Iris', 'Clary Sage'], 'base' => ['Tonka Bean', 'Cedar']],
            ],
            'emporio-armani-he' => [
                'concentration' => 'Eau de Toilette', 'release_year' => 2020, 'perfumer' => 'Alain Astori, Carlos Benaïm', 'gender' => 'men',
                'description' => 'A fresh, spicy-powdery woody fragrance for men, opening with citrusy yuzu and warm spice, moving through a powdery nutmeg-rose-orris heart, and finishing on a soft musky sandalwood-vetiver base.',
                'retail_price_usd' => 70, 'bottle_sizes' => '30ml, 50ml, 100ml',
                'notes' => ['top' => ['Yuzu', 'Sage', 'Cardamom'], 'middle' => ['Nutmeg', 'Rose', 'Orris Root'], 'base' => ['White Musk', 'Sandalwood', 'Vetiver', 'Tonka Bean']],
            ],
            'my-way-eau-de-parfum' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2020, 'perfumer' => 'Carlos Benaïm, Bruno Jovanovic', 'gender' => 'women',
                'description' => "A clean, radiant floral built around a tuberose-jasmine heart, opening with orange blossom and bergamot and closing on a warm musk-vanilla-cedar base. Won the Fragrance Foundation's Fragrance of the Year in 2021.",
                'retail_price_usd' => 119, 'bottle_sizes' => '30ml, 50ml, 90ml',
                'notes' => ['top' => ['Orange Blossom', 'Bergamot'], 'middle' => ['Tuberose', 'Jasmine'], 'base' => ['White Musk', 'Vanilla', 'Cedarwood']],
            ],
            'my-way-intense' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2021, 'perfumer' => 'Carlos Benaïm', 'gender' => 'women',
                'description' => 'An amplified, richer version of My Way, intensifying the tuberose heart and pairing it with sandalwood and vanilla for a deeper, more oriental-floral character.',
                'retail_price_usd' => 130, 'bottle_sizes' => '50ml, 90ml',
                'notes' => ['top' => ['Orange Blossom', 'Bitter Orange'], 'middle' => ['Tuberose'], 'base' => ['Vanilla', 'Sandalwood']],
            ],
            'si-eau-de-parfum' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2013, 'perfumer' => 'Christine Nagel', 'gender' => 'women',
                'description' => 'A modern, sophisticated fruity-chypre built on a signature blackcurrant-nectar opening, a soft rose-freesia heart, and a woody-ambroxan-patchouli base with vanilla warmth. Fronted by Cate Blanchett.',
                'retail_price_usd' => 148, 'bottle_sizes' => '15ml, 30ml, 50ml, 100ml',
                'notes' => ['top' => ['Black Currant'], 'middle' => ['Rose', 'Freesia'], 'base' => ['Vanilla', 'Patchouli', 'Woody Notes', 'Ambroxan']],
            ],
            'si-intense' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2021, 'perfumer' => 'Julie Massé', 'gender' => 'women',
                'description' => 'A more concentrated, deeper take on Sì, amplifying the blackcurrant and rose facets against a warmer patchouli base than the standard EDP — marketed as suited to colder weather and evening wear.',
                'retail_price_usd' => 130, 'bottle_sizes' => '30ml, 50ml, 100ml',
                'notes' => ['top' => ['Black Currant'], 'middle' => ['Turkish Rose', 'Davana'], 'base' => ['Benzoin', 'Patchouli']],
            ],
            'si-passione' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2017, 'perfumer' => 'Christine Nagel, Julie Massé', 'gender' => 'women',
                'description' => 'A bolder, fruitier reinterpretation of the Sì line, combining juicy pear/black-currant/pink-pepper top notes with a jasmine-rose-pineapple heart and a warm vanilla-patchouli-amberwood base.',
                'retail_price_usd' => 140, 'bottle_sizes' => '30ml, 50ml, 100ml, 150ml',
                'notes' => ['top' => ['Pear', 'Black Currant', 'Pink Pepper', 'Grapefruit'], 'middle' => ['Pineapple', 'Rose', 'Jasmine', 'Heliotrope'], 'base' => ['Vanilla', 'Cedar', 'Patchouli', 'Amberwood']],
            ],
            'stronger-with-you' => [
                'concentration' => 'Eau de Toilette', 'release_year' => 2017, 'perfumer' => 'Cécile Matton', 'gender' => 'men',
                'description' => 'A warm, spicy-gourmand fougère built around a candied chestnut accord, opening with cardamom and pink pepper, moving through a lavender-sage heart, and finishing with a smoky vanilla/woody base.',
                'retail_price_usd' => 120, 'bottle_sizes' => '30ml, 50ml, 100ml, 150ml',
                'notes' => ['top' => ['Cardamom', 'Pink Pepper', 'Violet Leaf'], 'middle' => ['Sage', 'Lavender'], 'base' => ['Chestnut', 'Vanilla', 'Woody Notes']],
            ],
            'stronger-with-you-absolutely' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2021, 'perfumer' => 'Cécile Matton', 'gender' => 'men',
                'description' => 'The richest, most liquorous entry in the Stronger With You line — a boozy rum-and-chestnut opening over a fruity lavender heart, closing with smoky cedar and vanilla for a warm, gourmand, evening-oriented character.',
                'retail_price_usd' => 135, 'bottle_sizes' => '50ml, 100ml',
                'notes' => ['top' => ['Rum', 'Elemi', 'Bergamot'], 'middle' => ['Lavender', 'Davana'], 'base' => ['Vanilla', 'Chestnut', 'Cedar', 'Patchouli']],
            ],
            'stronger-with-you-intensely' => [
                'concentration' => 'Eau de Parfum', 'release_year' => 2019, 'perfumer' => 'Olivier Cresp', 'gender' => 'men',
                'description' => 'An intensified, sweeter-gourmand version of Stronger With You, opening with spicy pink pepper and chestnut, a lavender-sage heart, and a rich amber wood and vanilla base.',
                'retail_price_usd' => 130, 'bottle_sizes' => '10ml, 50ml, 100ml',
                'notes' => ['top' => ['Pink Pepper', 'Chestnut'], 'middle' => ['Lavender', 'Sage'], 'base' => ['Amberwood', 'Vanilla']],
            ],
        ];
    }
};
