<?php


namespace core\services\plans;


use core\services\Client;
use yii\helpers\Json;
use yii\helpers\VarDumper;

class MomCatalog
{

    private $url = 'https://mam-com.by';

    private $mansarda = 'filter/mansarda-is-yes/apply/';

    private $categories = [
        'kamennyy' => [
            'material_id' => 12,
            'link' => 'https://mam-com.by/catalog/kamennyy/',
            'links' => [
                [
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-fabian/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-barbaris/",
                    "https://mam-com.by/catalog/kamennyy/avgustin/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-anis-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-alyie-parusa/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-anastasij-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-chex-m1/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-frej/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-radonezh/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-oregon/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-overn-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-mitrofanushka/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-mezonin-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-bateshkov/",

                ], [
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-bogomol-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-bratskij/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-saxarnyij/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-malta-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-aelita/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-alfa-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-albus/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-lukoshko/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-dachiya-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-antonovich-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-garri-dizajn/",
                ], [
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-antonovich-2-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-alfa-8-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-efima-2-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-czitrin/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-flueri-1/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-titaniya/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-tefiya-3/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-renet/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-razumnik/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-provans-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-svirel/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-pamir/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-oazis-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-nusya/",
                ], [
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-neptun-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-nensi/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-miz-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-krepost/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-ilij-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-angliya/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-beta-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-metida-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-kutyan-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-rajmond/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-lavrin-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-kompakt-dizajn/",
                ], [
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-bruk-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-efima/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-nika-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-efima-grejd/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-anxel-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-toledo/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-rubis/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-rivera/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-narcziss-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-zolotoj-klyuchik-2/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-evropejskij-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-graf/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-taro-2/",
                ], [
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-vakalov-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-myunster-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-rigel/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-gzhelskoe/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-alfa-15-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/tipovoj-proekt-kompakt-2-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-specz-alfa-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-sandal/",
                    "https://mam-com.by/catalog/kamennyy/proekt-oma-ubel-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-kub-vip-dizajn/",
                    "https://mam-com.by/catalog/kamennyy/proekt-doma-ksenya-dizajn/",
                ]

            ],
            'mansarda' => [],
            'types' => [
                [0, 300, [1, 3]],
                [300, 9999, [1, 6]]
            ],
            'purposes' => [
                [0, 9999, [4, 6]],
            ]
        ],
        'derevyannyy' => [
            'material_id' => 2,
            'link' => 'https://mam-com.by/catalog/derevyannyy/',
            'links' => [
                [
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-toliman/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-sirenevyij/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-klyuchik-2/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-safari/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-lugovaya-dizajn/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-klyuchik/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-sokol/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-polkovnik/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-tetra/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-duet/",
                    "https://mam-com.by/catalog/derevyannyy/rolekt-doma-pozitiv/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-malyish/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-knyaz-igor/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-simpl/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-opalevo-m6/",
                ], [
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-serdyuchka/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-taman/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-oven/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-prichuda/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-til/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-mashenka/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-prima/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-elis/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-chibis/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-katyusha/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-feba/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-ryabinushka/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-gordeevskij/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-fidzho/",
                ], [
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-pechki-lavochkik/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-erik/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-santa-klaus/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-sultan/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-slavyanka/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-krokus/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-sapozhok-1/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-veles-2/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-rozhdestvenno/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-veles/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-opalevo/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-dish-2/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-flamandecz/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-rubik/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-klementina-2/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-sverchok/",
                ], [
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-stolichnaya/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-gaston-dizajn/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-repka/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-legenda-dizajn/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-lotos/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-borovichyok/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-tajmen-1/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-pion/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-robi/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-eridana-dizajn/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-passadskij/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-korus/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-izumrud/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-edem/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-klementina-dizajn/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-teoder/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-ugolok/",
                ], [
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-muromecz/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-irinushka/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-radost/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-oxotnik/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-olxovskaya-seryozhka/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-bogorodskij/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-olivin/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-solnechnyij-put/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-sudarushka/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-zhuravushka/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-sapozhok-2/",
                    "https://mam-com.by/catalog/derevyannyy/ofeliya/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-lastochka-k/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-portik-dizajn/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-tatyana/",
                    "https://mam-com.by/catalog/derevyannyy/osennij-vals-2/",
                ], [
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-toronto/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-emil-2/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-shale-2/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-raduga/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-vega/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-grizli/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-ugolok-m3/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-ugolok-m2/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-kornilovskij/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-pechora-dizajn/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-ladushka/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-chelsi/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-lukomore2/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-voznesenskoe/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-solnechnyij-2/",
                ], [
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-venga/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-venga/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-valentin-i-valentina/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-derganovo/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-pechora-dizajn/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-krasnaya-shapochka/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-taremskoe/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-shreder-ll-dizajn/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-alekseev/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-pesochnyij-dizajn/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-karacharovo/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-tajfun/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-forest/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-georgin/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-gloriya/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-tyoshinskaya/",
                ], [
                    "https://mam-com.by/catalog/derevyannyy/origami-2/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-mitino/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-iris/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-oma-al-tayar/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-romanovo-1/",
                    "https://mam-com.by/catalog/derevyannyy/origami-5/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-malyij-vindzor/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-marfik/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-ugolok-m7/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-klever/",
                    "https://mam-com.by/catalog/derevyannyy/orxideya/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-patrik-dizajn/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-eovin/",
                ], [
                    "https://mam-com.by/catalog/derevyannyy/origami-4/",
                    "https://mam-com.by/catalog/derevyannyy/origami-3/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-narsil/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-emelya/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-tiggo/",
                    "https://mam-com.by/catalog/derevyannyy/origami-6/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-azaliya/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-fenshuj/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-al-tayars-pristrojkoj/",
                    "https://mam-com.by/catalog/derevyannyy/proekt-doma-al-tayars-pristrojkoj/",
                ]

            ],
            'mansarda' => [],
            'types' => [
                [0, 300, [1, 4]],
                [100, 300, [1, 3]],
                [300, 9999, [1, 6]],
            ],
            'purposes' => [
                [0, 60, [1, 2, 4, 6, 7]],
                [60, 9999, [2, 4, 6]],
            ]
        ],
        'iz-brusa' => [
            'material_id' => 1,
            'link' => 'https://mam-com.by/catalog/iz-brusa/',
            'links' => [
                [
                    "https://mam-com.by/catalog/iz-brusa/proekt-doma-kuper/",
                    "https://mam-com.by/catalog/iz-brusa/proekt-doma-laguna/",
                    "https://mam-com.by/catalog/iz-brusa/proekt-doma-tuchka/",
                    "https://mam-com.by/catalog/iz-brusa/proekt-doma-sandra-1/",
                    "https://mam-com.by/catalog/iz-brusa/proekt-doma-slavko/",
                    "https://mam-com.by/catalog/iz-brusa/proekt-doma-chex-s-topochnoj-2/",
                    "https://mam-com.by/catalog/iz-brusa/proekt-doma-norvezhecz-5/",
                    "https://mam-com.by/catalog/iz-brusa/proekt-doma-karavan-dizajn/",
                    "https://mam-com.by/catalog/iz-brusa/proekt-doma-solovushka/",
                ], [
                    "https://mam-com.by/catalog/iz-brusa/proekt-doma-opalevo-m4/",
                    "https://mam-com.by/catalog/iz-brusa/proekt-doma-stels/",
                    "https://mam-com.by/catalog/iz-brusa/proekt-doma-norvezhecz-2/",
                    "https://mam-com.by/catalog/iz-brusa/proekt-doma-elisej/",
                    "https://mam-com.by/catalog/iz-brusa/proekt-doma-gurij/",
                    "https://mam-com.by/catalog/iz-brusa/proekt-doma-duet-2/",
                    "https://mam-com.by/catalog/iz-brusa/origami-7/",
                    "https://mam-com.by/catalog/iz-brusa/proekt-doma-glyasse-dizajn/",
                    "https://mam-com.by/catalog/iz-brusa/proekt-doma-ezhevika-dizajn/",
                    "https://mam-com.by/catalog/iz-brusa/proekt-doma-laguna/",
                ]
            ],
            'mansarda' => [],
            'types' => [
                [0, 300, [1, 4]],
                [100, 300, [1, 3]],
                [300, 9999, [1, 6]],
            ],
            'purposes' => [
                [0, 60, [1, 2, 4, 6, 7]],
                [60, 9999, [2, 4, 6]],
            ]
        ],
        'karkasnyy' => [
            'material_id' => 24,
            'link' => 'https://mam-com.by/catalog/karkasnyy/',
            'links' => [
                [
                    "https://mam-com.by/catalog/karkasnyy/proekt-doma-al-tayar-2/",
                    "https://mam-com.by/catalog/karkasnyy/proekt-doma-sprinter/",
                    "https://mam-com.by/catalog/karkasnyy/proekt-doma-al-tayar-2/",
                    "https://mam-com.by/catalog/karkasnyy/proekt-doma-sprinter/",
                ]
            ],
            'mansarda' => [],
            'types' => [
                [0, 300, [1, 3]],
                [300, 9999, [1, 6]]
            ],
            'purposes' => [
                [0, 9999, [4, 6]],
            ]
        ]
    ];

    private $structure = [
        'name' => '',
        'description' => '',
        'width' => 0, //ширина
        'length' => 0, //длина
        'area' => 0, //площадь
        'garage' => 0, //гараж на кол-во машин(0-гаража нет)
        'beds' => null, //кол-во спален
        'images' => [],
        'plans' => [],
        'materials' => [], //дерево
        'extentions' => [], //гараж
        'options' => [],
        'style' => [],
        'type' => [], // дерево
        'purpose' => [],
        'floors' => [],
        'roof' => [],
    ];

    private $basePath;

    private $data = [];

    private $names = [];

    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->basePath = \Yii::getAlias('@common/data/mam-catalog');
    }


    public function parse()
    {

        foreach ($this->categories as $cat_id => &$category) {
            $i = 0;
            $this->loadMansarda($category);

            foreach ($category['links'] as $chunk) {
                $i++;
                print_r("Load {$i} in " . count($category['links']) . "\n");

                $requests = [];
                foreach ($chunk as $link) {
                    $id = explode('/', preg_replace('/\/$/', '', $link));
                    $id = $id[count($id) - 1];
                    $requests[$id] = $this->client->get($link);
                }

                $responses = $this->client->batch($requests);
                var_dump('Loaded');

                foreach ($responses as $id => $response) {
                    $item = $this->structure;

                    $pq = \phpQuery::newDocumentHTML($response->content);
                    $propHtml = preg_replace("/(\s\s+)/", '', $pq->find('.props_table')->html());
                    $item['materials'][] = $category['material_id'];
                    $item['name'] = $this->getName($pq);
                    $item['price'] = $this->getPrice($link);
                    $item['area'] = $this->getArea($propHtml);
                    $item['garage'] = $this->getGarage($propHtml);
                    $item['type'] = $this->getType($category, $item);
                    $item['purpose'] = $this->getPurpose($category, $item);

                    $item['floors'] = $this->getFloors($category, $item, $propHtml);
                    $item['roof'] = $this->getRoof($propHtml);
                    $item['images'] = $this->getImages($id, $pq);


                    file_put_contents($this->basePath . '/json/' . $id . '.json', Json::encode($item));

                    $this->names[] = $item['name'] . "\r";
                }

            }
            file_put_contents($this->basePath . '/project.txt', $this->names);
        }
    }


    /**
     * @param $link
     * @return float
     */
    private function getPrice($link)
    {
        $request = $this->client->get($link)->setHeaders([
            'bx-action-type' => 'get_dynamic',
            'bx-cache-blocks' => '{"info":"d41d8cd98f00","order":"d41d8cd98f00","sender-subscribe":"baa5572c7144"}',
            'bx-cache-mode' => 'HTMLCACHE',
            'bx-ref' => '',
        ])->send();

        $html = str_replace('\'', '"', $request->content);
        $html = str_replace("\t", '', $html);

        $pq = \phpQuery::newDocumentHTML(Json::decode($html)['dynamicBlocks'][0]['CONTENT']);

        $price = $pq->find('.price_new .price_val:eq(1)')->text();
        $price = str_replace('руб.', '', $price);
        $price = str_replace(' ', '', $price);

        return (float)$price;
    }


    /**
     * @param $id
     * @param  \phpQueryObject $pq
     * @return array
     */
    private function getImages($id, $pq)
    {
        $return = [];
        $imageDir = '/img/images/' . $id . '/';
        if (!file_exists($this->basePath . $imageDir)) {
            //mkdir($this->basePath . $imageDir);
        }

        $images = $pq->find('.portfolio .item a');

        foreach ($images as $image) {
            $url = pq($image)->attr('href');

            $name = $imageDir . count($return) . '.jpg';
            //file_put_contents($this->basePath . $name, file_get_contents($this->url . $url));
            $return[] = '..' . $name;

        }

        return $return;
    }


    private function getRoof($html)
    {
        preg_match("/Крыша<\/span><\/td><td class=\"char_value\"><span>([\w\s-]+)/u", $html, $math);
        if (isset($math[1])) {
            if (trim($math[1]) == '1-скатная') {
                return [1];
            }
            if ($math[1] == '2-скатная') {
                return [2];
            }
            if ($math[1] == '4-скатная') {
                return [3];
            }
        }

        return [];
    }

    private function getPurpose($category, $item)
    {
        foreach ($category['purposes'] as $type) {
            if ($type[0] <= $item['area'] && $item['area'] < $type[1]) {
                return $type[2];
            }
        }
        return [];
    }

    private function getFloors($category, $link, $html)
    {
        preg_match("/Этажей<\/span><\/td><td class=\"char_value\"><span>(\d+)<\/span/u", $html, $math);

        $floor = isset($math[1]) ? (int)$math[1] : 0;
        if ($floor > 3) {
            $floor = 3;
        }

        if ($floor == 2) {
            foreach ($category['mansarda'] as $item) {
                if ($item == $link) {
                    $floor = 4;
                }
            }
        }
        return [$floor];
    }

    private function getType($category, $item)
    {
        foreach ($category['types'] as $type) {
            if ($type[0] <= $item['area'] && $item['area'] < $type[1]) {
                return $type[2];
            }
        }
        return [];
    }

    /**
     * @param \phpQueryObject $pq
     * @return mixed
     */
    private function getName($pq)
    {
        $name = $pq->find('h1')->text();
        return trim(preg_replace('/\+ дизайн$/', '', $name));
    }

    private function getArea($html)
    {
        preg_match("/Площадь<\/span><\/td><td class=\"char_value\"><span>(.+)<\/span/u", $html, $math);

        return isset($math[1]) ? (float)$math[1] : 0;
    }

    private function getGarage($html)
    {
        preg_match("/Гараж<\/span><\/td><td class=\"char_value\"><span>([\w\s]+)/u", $html, $math);

        if (isset($math[1])) {
            if ($math[1] == 'На два авто') {
                return 2;
            }
            if ($math[1] == 'На один авто') {
                return 2;
            }
            if ($math[1] == 'Навес') {
                return 0;
            }
        }

        return 0;
    }


    private function loadMansarda(&$category)
    {
        $page = 1;
        do {
            $url = $category['link'] . $this->mansarda . '?PAGEN_1=' . $page;

            VarDumper::dump($url . "\n");
            $request = $this->client->get($url)->send();
            $pq = \phpQuery::newDocumentHTML($request->content);

            $links = $pq->find('.item a.blink');
            foreach ($links as $link) {
                $category['mansarda'][] = pq($link)->attr('href');
            }


            $is_next = $pq->find('.next')->count();
            $page++;
        } while ($is_next);
    }


}