<?php

/**
 * resets specified apps to 2nd tier pending (for [#53649] / [#53232])
 * 
 * @author Justin Foell <justin.foell@sellingsource.com>
 */

require_once dirname(realpath(__FILE__)) . '/../www/config.php';
require_once LIB_DIR . 'common_functions.php';
require_once SQL_LIB_DIR . 'util.func.php';

$factory = ECash::getFactory();
$db = $factory->getDB();
$app_ids = array(
900465849,900472777,900499584,900505112,900524075,900524875,900531177,900534407,900554461,900558245,900559448,900560016,900560230,900561342,900561364,900564054,900567071,900568716,900569046,900569277,900577382,900587839,900593508,900601120,900605502,900621437,900623469,900637930,900650208,900677423,900786477,900815493,900818794,900818829,900820085,900820691,900824790,900826116,900827266,900828784,900831681,900832592,900836655,900837091,900843334,900844616,900844768,900845263,900859056,900889536,900890685,900894450,900898091,900900466,900914387,900921302,900936459,900956308,900957189,900962507,900965659,900966010,900966891,900968111,900969593,900972033,900973179,900978736,900984068,900989422,900996583,901008805,901014538,901015168,901021036,901022351,901023290,901023929,901024469,901029000,901040037,901041197,901041446,901042224,901043244,901044774,901047793,901048625,901049930,901052389,901058245,901060069,901061505,901062841,901063079,901065090,901065799,901066998,901070209,901071928,901072183,901073676,901074794,901076317,901076841,901079576,901080632,901080786,901081383,901081900,901082243,901082897,901082969,901082983,901083182,901083526,901084729,901086190,901086358,901086463,901087017,901087485,901087495,901087926,901091052,901091126,901091590,901092713,901094606,901094986,901095021,901098586,901099146,901099644,901100466,901101492,901102083,901103562,901104152,901106040,901106181,901107429,901108244,901110025,901110916,901111605,901112497,901113711,901117702,901117949,901118348,901121364,901121539,901122589,901124438,901124510,901126720,901129764,901129870,901130768,901132225,901132268,901132290,901132611,901132774,901133915,901134142,901134262,901136717,901139856,901140452,901140926,901146182,901146654,901149547,901149998,901150681,901157284,901157684,901162866,901164085,901165774,901167016,901172908,901173128,901190292,901191375,901194694,901198078,901209570,901210245,901212936,901213206,901220751,901222674,901224901,901237948,901246975,901247922,901249099,901252017,901253130,901253278,901253841,901254422,901255109,901256322,901258443,901258464,901260347,901260515,901264460,901264705,901268548,901276950,901277669,901278493,901281513,901283043,901285198,901285956,901287605,901292756,901292991,901293838,901294229,901296077,901296616,901297754,901298118,901298667,901302842,901303920,901304983,901305547,901306264,901307065,901311718,901315254,901315672,901315674,901315811,901319341,901319462,901319791,901320683,901322967,901323074,901323385,901323895,901323954,901324180,901324839,901325381,901325613,901327306,901328549,901329143,901329591,901330695,901330770,901330966,901331079,901331248,901331662,901332084,901332304,901332392,901333319,901333907,901337077,901341444,901341991,901342849,901343070,901343078,901343369,901343556,901344295,901345249,901347816,901350685,901354134,901354476,901354736,901355203,901356286,901356330,901356413,901357266,901357581,901357819,901359310,901360480,901360708,901361430,901362757,901363123,901363599,901363818,901367533,901369215,901369722,901370518,901371758,901372148,901372150,901376065,901376639,901376856,901378814,901378883,901379097,901379513,901380190,901381373,901381380,901381816,901381991,901383885,901384752,901385198,901385433,901385938,901386138,901386393,901386786,901387173,901387401,901387512,901388381,901389320,901389461,901389963,901390725,901391178,901391625,901391869,901393103,901393958,901394499,901394735,901394749,901395021,901395939,901396445,901396956,901397220,901397716,901397864,901398024,901398499,901399262,901402249,901402498,901402511,901402745,901402832,901403321,901403600,901403838,901403855,901403977,901404158,901404200,901404744,901405730,901406914,901407986,901408129,901408289,901408448,901408734,901409416,901411032,901411691,901412006,901412383,901412448,901413035,901413896,901413923,901413954,901414185,901414229,901414455,901414784,901414838,901415467,901415554,901416098,901416133,901416354,901416548,901416626,901416893,901417957,901417987,901418404,901418505,901420135,901421189,901421313,901422059,901422380,901422724,901422892,901423044,901424635,901424816,901424852,901425079,901425256,901425713,901425847,901425932,901426654,901427455,901429272,901429732,901430004,901430009,901430159,901430714,901430951,901430997,901431302,901431366,901431652,901432466,901433044,901433234,901433486,901434390,901434431,901434432,901434472,901434733,901434817,901434986,901435522,901436330,901437954,901438166,901439379,901439814,901440073,901440221,901440253,901440339,901440813,901441174,901441417,901441669,901442918,901443113,901443239,901443440,901444146,901444454,901444752,901445123,901445934,901446199,901446203,901446418,901447730,901448479,901449209,901450002,901450567,901451992,901452020,901452193,901452263,901452431,901452573,901453445,901453799,901454067,901454285,901454373,901455904,901457639,901457756,901458137,901458342,901458824,901458837,901459136,901459609,901461517,901461548,901461782,901462265,901462350,901462431,901462632,901462648,901462827,901463118,901463308,901463468,901463582,901464358,901464838,901465222,901465636,901465939,901467210,901467292,901467653,901468178,901469245,901469357,901470052,901471517,901471924,901472343,901473466,901474158,901474293,901475169,901475332,901475381,901475693,901475910,901476248,901476294,901476316,901476376,901476605,901476956,901477247,901477325,901477708,901477826,901477877,901478885,901478922,901479107,901479580,901479611,901481148,901481482,901481964,901481977,901482865,901483465,901484734,901484818,901485897,901486009,901486297,901486396,901486455,901486676,901487066,901488158,901488187,901488585,901488856,901491175,901491384,901491677,901493059,901493089,901493248,901494225,901494815,901494936,901495141,901496185,901496289,901496370,901496802,901497241,901497257,901497527,901497672,901499417,901499735,901499972,901501199,901501697,901501752,901501877,901502493,901502530,901502631,901503111,901503165,901503340,901503730,901503821,901504027,901504863,901504867,901505194,901505805,901506601,901506607,901506821,901507052,901507598,901507909,901508078,901508164,901508167,901508370,901508460,901508461,901508497,901508699,901509802,901510221,901511022,901511512,901512175,901513022,901513230,901513783,901514275,901514456,901514460,901514672,901515490,901515702,901515854,901516002,901517017,901517087,901517675,901517793,901518479,901518539,901518637,901518749,901519456,901519511,901519597,901520380,901520680,901521310,901521355,901521367,901521456,901521608,901521826,901522027,901522245,901522457,901523108,901523192,901523209,901523518,901524260,901524506,901524873,901525892,901525906,901526254,901526317,901526970,901527165,901527171,901527222,901527489,901527492,901527549,901527844,901528154,901528318,901528322,901528520,901528701,901528898,901528954,901529011,901529171,901529342,901529386,901529702,901529728,901530060,901530107,901530322,901530601,901531177,901531233,901531291,901531503,901531605,901531646,901532163,901532560,901532615,901532616,901532949,901533155,901533310,901533337,901533672,901533762,901533810,901534211,901534271,901534657,901534674,901534940,901535039,901535826,901535891,901535892,901536318,901536384,901536607,901536747,901536884,901537240,901537464,901537553,901537647,901537800,901537855,901537902,901538231,901538277,901538498,901538792,901539094,901539324,901539347,901540082,901540209,901540237,901540556,901540875,901541025,901541281,901541504,901541535,901541658,901541865,901541989,901542018,901542217,901542429,901542577,901542637,901542989,901543094,901543170,901543835,901544133,901544397,901544455,901544583,901544630,901544631,901544673,901544681,901544854,901544972,901545295,901545371,901545621,901545930,901546588,901546838,901547114,901547187,901547674,901547720,901547756,901547854,901548087,901548137,901548635,901548962,901548989,901549090,901550042,901550141,901550239,901550282,901550451,901550996,901551096,901551176,901551358,901551584,901551698,901551760,901551835,901551836,901552283,901552417,901552524,901552851,901553013,901553317,901553382,901553584,901553667,901553941,901553948,901554145,901554184,901554210,901554219,901554262,901554265,901554321,901554425,901554558,901554596,901554991,901555017,901555636,901555733,901555803,901555806,901555811,901556065,901556255,901556530,901556662,901556696,901556719,901556949,901557085,901557445,901557561,901557717,901558102,901558177,901558402,901558456,901558491,901558513,901558579,901558664,901558712,901558914,901558969,901559028,901559137,901559204,901559259,901559269,901559342,901559518,901559521,901559599,901560001,901560007,901560158,901560177,901560218,901560236,901560387,901560812,901560829,901560959,901561043,901561085,901561159,901561290,901561335,901561449,901561474,901561491,901561600,901561828,901562015,901562316,901562412,901562472,901562777,901563551,901563615,901563782,901563874,901563939,901564022,901564442,901564454,901564662,901564760,901564823,901565017,901565311,901565355,901565768,901565776,901565958,901566001,901566142,901566186,901566243,901566375,901566496,901566698,901566780,901567112,901567151,901567249,901567287,901567370,901567497,901567506,901567679,901567757,901568088,901568100,901568125,901568136,901568278,901568343,901568387,901568435,901568460,901568471,901568501,901568617,901568994,901569159,901569261,901569309,901569844,901570026,901570060,901570247,901570595,901570733,901571729,901571805,901571865,901572071,901572349,901572429,901572493,901572569,901572981,901574603,901574690,901574954,901575176,901575475,901575955,901575975,901576036,901576149,901576161,901576328,901576726,901577176,901577253,901577436,901577835,901577918,901578048,901578243,901578490,901578569,901578676,901579006,901579085,901579192,901579346,901579609,901579638,901579640,901580158,901580727,901580839,901580982,901581266,901581375,901581464,901581535,901581549,901581663,901581673,901581674,901581848,901581880,901581968,901582602,901582705,901582766,901582837,901582909,901582998,901583042,901583730,901583931,901584255,901584396,901584523,901584825,901585072,901585175,901585199,901585666,901585717,901586073,901586590,901586872,901586907,901587014,901587112,901587268,901587277,901587316,901587427,901587744,901587943,901588532,901588563,901588577,901588690,901588974,901589038,901589187,901589256,901589822,901590046,901590056,901590489,901591242,901591417,901591516,901591600,901591632,901591718,901591745,901591760,901591814,901591910,901591957,901592050,901592316,901592568,901593193,901593802,901594500,901594570,901594780,901594965,901594998,901595090,901595150,901595287,901595696,901596258,901596264,901596376,901596922,901597010,901597258,901597265,901597598,901597763,901598307,901598749,901599129,901599437,901599626,901599682,901599683,901599736,901600077,901600300,901600308,901600452,901600887,901600936,901600965,901601207,901601292,901601510,901601536,901601686,901601788,901601816,901602291,901602483,901602850,901602892,901602919,901603245,901603522,901603806,901603940,901604136,901604263,901604298,901604322,901604461,901604579,901604602,901604764,901604983,901605116,901605308,901605362,901605877,901606329,901606414,901606546,901607419,901609786,901609793,901610315,901610374,901610411,901610412,901610513,901610517,901610800,901610848,901611163,901611343,901611488,901611665,901611902,901612228,901612744,901612821,901613331,901613404,901613608,901613866,901613926,901613939,901614647,901614806,901615406,901615592,901615879,901615977,901616006,901616015,901616206,901616437,901616544,901616784,901616925,901616961,901617289,901617394,901617752,901618043,901618151,901618175,901618665,901618724,901619085,901619435,901620843,901620853,901620918,901621282,901621686,901622323,901622409,901622490,901622629,901623044,901623111,901623181,901623237,901623430,901623546,901623602,901623910,901623917,901624013,901624152,901624888,901624906,901624934,901625214,901625523,901625698,901625714,901625784,901625791,901625849,901625936,901626360,901626903,901627027,901627408,901627482,901627487,901627719,901627725,901627861,901627970,901628275,901628565,901628587,901628623,901628728,901628839,901628889,901628973,901629046,901629205,901629318,901629398,901629545,901629755,901630433,901630479,901630488,901630616,901630875,901630895,901631061,901631188,901631502,901631592,901631753,901632104,901632233,901632296,901632658,901632706,901632857,901632934,901633164,901633257,901633318,901633379,901633457,901633529,901633663,901633712,901633936,901633976,901634087,901634135,901634162,901634212,901634224,901634235,901634441,901634604,901634775,901634835,901635026,901635128,901635352,901635365,901635595,901635897,901636025,901636152,901636373,901636552,901636827,901637035,901637098,901637292,901637600,901637837,901637861,901637938,901638223,901638290,901638430,901638575,901638633,901638697,901638864,901638970,901639024,901639057,901639109,901639192,901639328,901639462,901639566,901639630,901639692,901639794,901639928,901640005,901640007,901640034,901640050,901640099,901640298,901640513,901640840,901641091,901641639,901641736,901641748,901641786,901642221,901642290,901642434,901642483,901642548,901642586,901642647,901642651,901643190,901643230,901643379,901643398,901643469,901643580,901643581,901643611,901643622,901644060,901644084,901644139,901644273,901644320,901644983,901644996,901645098,901645127,901645216,901645415,901645676,901645946,901645998,901646234,901646372,901646486,901646516,901646630,901646734,901647237,901647728,901647998,901648160,901648200,901648276,901648281,901648688,901649216,901649517,901650177,901650187,901650758,901651061,901651119,901651374,901651514,901651834,901651939,901651955,901651978,901652049,901652247,901652251,901652340,901652368,901652780,901652968,901653058,901653094,901653155,901653198,901653315,901653702,901653863,901654707,901654933,901655107,901655254,901655392,901655554,901655598,901655711,901655941,901656080,901656270,901656696,901656955,901656990,901657225,901657465,901658012,901658228,901659143,901659267,901659904,901660043,901660173,901660228,901660487,901660692,901660827,901661583,901661863,901662025,901662149,901662269,901662289,901662514,901662565,901663338,901664661,901664827,901664923,901664986,901664990,901665333,901665525,901665590,901666535,901666846,901666857,901666871,901667184,901667234,901667242,901667321,901667633,901668061,901668302,901668735,901668784,901669343,901669387,901669568,901669771,901669906,901669912,901669957,901670201,901670504,901671022,901671221,901671835,901671859,901672709,901673171,901673442,901674237,901674517,901674677,901674796,901674829,901675358,901675464,901675603,901675675,901675986,901676124,901676300,901676573,901676810,901676985,901677010,901677121,901680240,901680837,901683318,901683334,901685209,901685652,901685826,901687064,901687361,901687717,901687740,901688142,901688282,901688801,901689447,901689863,901689905,901689985,901690144,901690187,901690342,901690488,901690689,901690813,901691386,901691752,901691794,901692230,901692270,901692378,901693486,901694534,901694902,901695067,901695267,901695425,901695934,901696435,901696535,901696543
);

foreach($app_ids as $app_id)
{
	echo "Updating $app_id to second tier (pending)\n";
	try
	{
		Update_Status(NULL, $app_id, array('pending', 'external_collections', '*root'), null, null, true);
	}
	catch(ECash_Application_NotFoundException $e)
	{
		echo $e->getMessage() . PHP_EOL;
	}
}

echo "Resetting batch item count\n";
$query = "update ext_collections_batch
set item_count = item_count - ?
where ext_collections_batch_id =
(
   select ext_collections_batch_id
   from ext_collections
   where application_id = ?
)
";
$db->execPrepared($query, array(count($app_ids), $app_ids[0]));

echo "Removing apps from second tier batch\n";
$query = "delete from ext_collections
where application_id in (".join(',', $app_ids).")";
$db->exec($query);



?>