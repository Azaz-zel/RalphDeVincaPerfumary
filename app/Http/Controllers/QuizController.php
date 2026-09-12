<?php

namespace App\Http\Controllers;

use App\Models\Perfume;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * "Find Your Scent" — a five-question quiz that recommends fragrances
 * from the catalogue.
 *
 * Answers are scored rather than filtered: a filter easily returns nothing
 * when someone picks an uncommon combination, while scoring always produces
 * a ranked list and lets the result page explain why each fragrance matched.
 */
class QuizController extends Controller
{
    /**
     * Each question maps an answer key to the catalogue values it favours,
     * along with the weight it carries. Weights total 100, so a perfume's
     * score reads directly as a match percentage.
     */
    private const QUESTIONS = [
        'character' => [
            'weight' => 40,
            'field' => 'family',
            'question' => 'Which of these draws you in?',
            'hint' => 'There is no wrong answer — go with your gut.',
            'options' => [
                'fresh' => [
                    'label' => 'Fresh & Clean',
                    'hint' => 'Citrus, sea air, crisp linen',
                    'match' => ['Fresh', 'Aquatic', 'Citrus'],
                ],
                'sweet' => [
                    'label' => 'Warm & Sweet',
                    'hint' => 'Vanilla, amber, something edible',
                    'match' => ['Gourmand', 'Vanilla', 'Amber'],
                ],
                'woody' => [
                    'label' => 'Woody & Earthy',
                    'hint' => 'Sandalwood, cedar, dry smoke',
                    'match' => ['Woody', 'Musky'],
                ],
                'floral' => [
                    'label' => 'Soft & Floral',
                    'hint' => 'Rose, jasmine, powdery petals',
                    'match' => ['Floral'],
                ],
            ],
        ],
        'season' => [
            'weight' => 20,
            'field' => 'seasons',
            'question' => 'When will you wear it most?',
            'hint' => 'Heat lifts a fragrance; cold holds it close.',
            'options' => [
                'warm' => [
                    'label' => 'Hot Days',
                    'hint' => 'Summer, humid weather',
                    'match' => ['Summer', 'All Season'],
                ],
                'cold' => [
                    'label' => 'Cold Days',
                    'hint' => 'Winter, sweater weather',
                    'match' => ['Winter', 'All Season'],
                ],
                'mild' => [
                    'label' => 'In Between',
                    'hint' => 'Spring and autumn',
                    'match' => ['Spring', 'Autumn', 'All Season'],
                ],
                'any' => [
                    'label' => 'All Year',
                    'hint' => 'One bottle for every season',
                    'match' => ['All Season', 'Spring', 'Summer', 'Autumn', 'Winter'],
                ],
            ],
        ],
        'occasion' => [
            'weight' => 20,
            'field' => 'occasions',
            'question' => 'Where are you headed?',
            'hint' => 'Think of the place you will wear it most often.',
            'options' => [
                'work' => [
                    'label' => 'Work & Formal',
                    'hint' => 'Office, meetings, occasions',
                    'match' => ['Office', 'Formal'],
                ],
                'night' => [
                    'label' => 'Evenings Out',
                    'hint' => 'Dates, dinners, parties',
                    'match' => ['Date Night', 'Party'],
                ],
                'daily' => [
                    'label' => 'Everyday',
                    'hint' => 'Errands, campus, casual days',
                    'match' => ['Everyday', 'Casual'],
                ],
                'travel' => [
                    'label' => 'On the Move',
                    'hint' => 'Travel and long days out',
                    'match' => ['Travel', 'Casual'],
                ],
            ],
        ],
        'intensity' => [
            'weight' => 10,
            'field' => 'concentration',
            'question' => 'How much presence do you want?',
            'hint' => 'This is about how far the scent travels.',
            'options' => [
                'subtle' => [
                    'label' => 'Close to Skin',
                    'hint' => 'Only noticed up close',
                    'match' => ['Cologne', 'Eau de Toilette'],
                ],
                'balanced' => [
                    'label' => 'Balanced',
                    'hint' => 'Present, never overwhelming',
                    'match' => ['Eau de Parfum'],
                ],
                'bold' => [
                    'label' => 'Bold',
                    'hint' => 'Fills the room, lasts all day',
                    'match' => ['Parfum', 'Extrait de Parfum'],
                ],
            ],
        ],
        'gender' => [
            'weight' => 10,
            'field' => 'gender',
            'question' => 'Which shelf do you browse?',
            'hint' => 'Unisex fragrances are suggested either way.',
            'options' => [
                'men' => [
                    'label' => 'Masculine',
                    'hint' => 'Marketed to men',
                    'match' => ['men', 'unisex'],
                ],
                'women' => [
                    'label' => 'Feminine',
                    'hint' => 'Marketed to women',
                    'match' => ['women', 'unisex'],
                ],
                'unisex' => [
                    'label' => 'Unisex',
                    'hint' => 'Made for anyone',
                    'match' => ['unisex'],
                ],
            ],
        ],
    ];

    /** Short phrases used on the result page to explain each match. */
    private const REASONS = [
        'character' => [
            'fresh' => 'fresh and clean',
            'sweet' => 'warm and sweet',
            'woody' => 'woody and earthy',
            'floral' => 'soft and floral',
        ],
        'season' => [
            'warm' => 'warm weather',
            'cold' => 'cold weather',
            'mild' => 'mild weather',
            'any' => 'year-round wear',
        ],
        'occasion' => [
            'work' => 'work and formal settings',
            'night' => 'evenings out',
            'daily' => 'everyday wear',
            'travel' => 'travel',
        ],
        'intensity' => [
            'subtle' => 'a close-to-skin presence',
            'balanced' => 'a balanced presence',
            'bold' => 'a bold presence',
        ],
        'gender' => [
            'men' => 'masculine fragrances',
            'women' => 'feminine fragrances',
            'unisex' => 'unisex fragrances',
        ],
    ];

    public function index()
    {
        return view('pages.quiz', ['questions' => self::QUESTIONS]);
    }

    public function result(Request $request)
    {
        $answers = $this->validAnswers($request);

        // Nothing usable was answered — send them back to the questions.
        if ($answers === []) {
            return redirect()->route('quiz');
        }

        $matches = $this->rank($answers);

        return view('pages.quiz-result', [
            'summary' => $this->summarise($answers),
            'topMatch' => $matches->first(),
            'matches' => $matches->slice(1)->values(),
        ]);
    }

    /**
     * Keeps only answers that exist in the question definitions, so a
     * hand-edited URL degrades into "no preference" instead of an error.
     */
    private function validAnswers(Request $request): array
    {
        $answers = [];

        foreach (self::QUESTIONS as $key => $question) {
            $value = $request->input($key);

            if (is_string($value) && isset($question['options'][$value])) {
                $answers[$key] = $value;
            }
        }

        return $answers;
    }

    /**
     * Scores every perfume against the answers and returns the best six.
     */
    private function rank(array $answers): Collection
    {
        // Every perfume has to be scored, so only the columns the scoring and
        // the result cards actually use are hydrated.
        return Perfume::with([
            'brand:id,name,slug',
            'fragranceFamily:id,name',
            'seasons:id,name',
            'occasions:id,name',
        ])
            ->select([
                'id', 'name', 'slug', 'image', 'gender',
                'concentration', 'rating', 'brand_id', 'fragrance_family_id',
            ])
            ->get()
            ->map(function (Perfume $perfume) use ($answers) {
                [$score, $reasons] = $this->score($perfume, $answers);

                $perfume->match_score = $score;
                $perfume->match_reasons = $reasons;

                return $perfume;
            })
            // Rating only breaks ties, so it never outweighs a real match;
            // id settles the rest so the order is stable between visits.
            ->sortByDesc(fn (Perfume $perfume) => [
                $perfume->match_score,
                (float) $perfume->rating,
                -$perfume->id,
            ])
            ->take(6)
            ->values();
    }

    /**
     * @return array{0: int, 1: array<int, string>} score out of 100, plus
     *                                              the reasons it earned
     */
    private function score(Perfume $perfume, array $answers): array
    {
        $score = 0;
        $reasons = [];

        foreach ($answers as $key => $answer) {
            $question = self::QUESTIONS[$key];

            if ($this->perfumeOffers($perfume, $question['field'], $question['options'][$answer]['match'])) {
                $score += $question['weight'];
                $reasons[] = self::REASONS[$key][$answer];
            }
        }

        return [$score, $reasons];
    }

    /**
     * Whether a perfume carries any of the values a question asked for.
     */
    private function perfumeOffers(Perfume $perfume, string $field, array $wanted): bool
    {
        $has = match ($field) {
            'family' => [$perfume->fragranceFamily?->name],
            'seasons' => $perfume->seasons->pluck('name')->all(),
            'occasions' => $perfume->occasions->pluck('name')->all(),
            'concentration' => [$perfume->concentration],
            'gender' => [$perfume->gender],
        };

        return array_intersect(array_filter($has), $wanted) !== [];
    }

    /**
     * A plain-language recap of what the visitor asked for.
     */
    private function summarise(array $answers): array
    {
        $summary = [];

        foreach ($answers as $key => $answer) {
            $summary[] = self::REASONS[$key][$answer];
        }

        return $summary;
    }
}
