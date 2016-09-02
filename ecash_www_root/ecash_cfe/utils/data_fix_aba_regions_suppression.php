<?php
/**
php data_fix_aba_regions_suppression.php
*/
putenv("ECASH_EXEC_MODE=Live");
putenv("ECASH_CUSTOMER=AALM");
putenv("ECASH_CUSTOMER_DIR=/virtualhosts/aalm/ecash3.0/ecash_aalm/");

require_once dirname(realpath(__FILE__)) . '/../www/config.php';
require_once "../www/config.php";
require_once(LIB_DIR."common_functions.php");
require_once(SQL_LIB_DIR.'util.func.php');
require_once(SQL_LIB_DIR . "scheduling.func.php");
require_once(CUSTOMER_LIB."failure_dfa.php");
require_once(SERVER_CODE_DIR . 'comment.class.php');

$db = ECash::getMasterDb();
$company_id = 1;
$agent_id = 1;
$server = ECash::getServer();
$server->company_id = $company_id;

echo "Started...\n";

$list_name = "Regions Bank ABA Exclude";

$query =
"
SELECT list_id
FROM suppression_lists
WHERE company_id=1
AND active=1
AND name = '{$list_name}'
";
$result = $db->Query($query);
$row = $result->fetch(PDO::FETCH_OBJ);
$list_id = intval($row->list_id);
var_dump($list_id);

$query_insert =
"
INSERT INTO
suppression_list_revisions
SET
list_id = {$list_id},
date_created = NOW(),
date_modified = NOW(),
status = 'INACTIVE' -- Change to ACTIVE in the end
";
$db->Query($query_insert);

$query =
"
SELECT revision_id
FROM suppression_list_revisions
WHERE status='INACTIVE'
AND list_id = {$list_id}
ORDER BY revision_id DESC
LIMIT 1
";
$result = $db->Query($query);
$row = $result->fetch(PDO::FETCH_OBJ);
$new_revision_id = intval($row->revision_id);
var_dump($new_revision_id);

$query =
"
SELECT revision_id
FROM suppression_list_revisions
WHERE status='ACTIVE'
AND list_id = {$list_id}
ORDER BY revision_id DESC
LIMIT 1
";
$result = $db->Query($query);
$row = $result->fetch(PDO::FETCH_OBJ);
$current_active_revision_id = intval($row->revision_id);
var_dump($current_active_revision_id);

$new_values_array = array(
"11807043",
"21000018",
"21000021",
"21000089",
"21001033",
"21001088",
"21001208",
"21001486",
"21004823",
"21030004",
"21051180",
"21051274",
"21051287",
"21051371",
"21052040",
"21052367",
"21052927",
"21052943",
"21080025",
"21080038",
"21080054",
"21080070",
"21080083",
"21080135",
"21080148",
"21080151",
"21080164",
"21080177",
"21080180",
"21080193",
"21080232",
"21080245",
"21080258",
"21080261",
"21080274",
"21080287",
"21080300",
"21080313",
"21080326",
"21080339",
"21080342",
"21080355",
"21080368",
"21080371",
"21080384",
"21080407",
"21080410",
"21080436",
"21080533",
"21080546",
"21080562",
"21080591",
"21080601",
"21080630",
"21080669",
"21080672",
"21080740",
"21080850",
"21080863",
"21080892",
"21080902",
"21080915",
"21080960",
"21080973",
"21081079",
"21081082",
"21081189",
"21081215",
"21081228",
"21081231",
"21081244",
"21081260",
"21081273",
"21081286",
"21081299",
"21081309",
"21081341",
"21081354",
"21081367",
"21081406",
"21081419",
"21081422",
"21081435",
"21081448",
"21081451",
"21081464",
"21081477",
"21081493",
"21081503",
"21081516",
"21081529",
"21081532",
"21081545",
"21081558",
"21081561",
"21081574",
"21081587",
"21081590",
"21081613",
"21081626",
"21081639",
"21081642",
"21081655",
"21081668",
"21081671",
"21081684",
"21081697",
"21081723",
"21081736",
"21081752",
"21081778",
"21081794",
"21081804",
"21081817",
"21081833",
"21081859",
"21081888",
"21081891",
"21081901",
"21081914",
"21081927",
"21081930",
"21081985",
"21081998",
"21082007",
"21082065",
"21082146",
"21082159",
"21082162",
"21082191",
"21082227",
"21082243",
"21082256",
"21082272",
"21082285",
"21082298",
"21082308",
"21082311",
"21082324",
"21082353",
"21082366",
"21082379",
"21082382",
"21082395",
"21082405",
"21082418",
"21082489",
"21082492",
"21082502",
"21082528",
"21082531",
"21082544",
"21082557",
"21082586",
"21082599",
"21082609",
"21082612",
"21082625",
"21082667",
"21082793",
"21082803",
"21082816",
"21082832",
"21082887",
"21082926",
"21082939",
"21082942",
"21082968",
"21082971",
"21082997",
"21083006",
"21083019",
"21083035",
"21083048",
"21083051",
"21083064",
"21083077",
"21083093",
"21083116",
"21083129",
"21083132",
"21083158",
"21083161",
"21083174",
"21083187",
"21083190",
"21083213",
"21083239",
"21083255",
"21083268",
"21083271",
"21083297",
"21083307",
"21083310",
"21083352",
"21083365",
"21083378",
"21083394",
"21083417",
"21083433",
"21083459",
"21083462",
"21083491",
"21083501",
"21083514",
"21083527",
"21083530",
"21083572",
"21083585",
"21083598",
"21083611",
"21083624",
"21083640",
"21083653",
"21083666",
"21083679",
"21083682",
"21083695",
"21083718",
"21083750",
"21083776",
"21083792",
"21083802",
"21083831",
"21083873",
"21083886",
"21083899",
"21083909",
"21083912",
"21083925",
"21083941",
"21083970",
"21083983",
"21084018",
"21084034",
"21084050",
"21084063",
"21084076",
"21084089",
"21084092",
"21084115",
"21084131",
"21084144",
"21084157",
"21084173",
"21084199",
"21084212",
"21084238",
"21084377",
"21084393",
"21084416",
"21084429",
"21084432",
"21084458",
"21084474",
"21084513",
"21084539",
"21084542",
"21084555",
"21084568",
"21084571",
"21084597",
"21084610",
"21084636",
"21084681",
"21084694",
"21084717",
"21084720",
"21084746",
"21084762",
"21084814",
"21084827",
"21084830",
"21084843",
"21084856",
"21084872",
"21084898",
"21084911",
"21084924",
"21084937",
"21084953",
"21084966",
"21084979",
"21084995",
"21085004",
"21085017",
"21085020",
"21085033",
"21085059",
"21085062",
"21085075",
"21085088",
"21085114",
"21085130",
"21085143",
"21085156",
"21085172",
"21085185",
"21085211",
"21085237",
"21085253",
"21085295",
"21085305",
"21085318",
"21085334",
"21085350",
"21085376",
"21085389",
"21085392",
"21085415",
"21085428",
"21085444",
"21085457",
"21085473",
"21085499",
"21085538",
"21085554",
"21085596",
"21085619",
"21085622",
"21085648",
"21085677",
"21085693",
"21085716",
"21085729",
"21085732",
"21085758",
"21085790",
"21085813",
"21085855",
"21085871",
"21085897",
"21085907",
"21085910",
"21085936",
"21085949",
"21085965",
"21085978",
"21085994",
"21086003",
"21086016",
"21086029",
"21086032",
"21086045",
"21086058",
"21086061",
"21086074",
"21086139",
"21086142",
"21086155",
"21086184",
"21086197",
"21086207",
"21086210",
"21086236",
"21086333",
"21086359",
"21086414",
"21086456",
"21086469",
"21086498",
"21086553",
"21086566",
"21086579",
"21086621",
"21086634",
"21086689",
"21086715",
"21086731",
"21086773",
"21086812",
"21086838",
"21086841",
"21086948",
"21086993",
"21087125",
"21087219",
"21087251",
"21087303",
"21087329",
"21087332",
"21087468",
"21087484",
"21087565",
"21087581",
"21087604",
"21087701",
"21087714",
"21087727",
"21087743",
"21087840",
"21087879",
"21087895",
"21087905",
"21087918",
"21087963",
"21087976",
"21087992",
"21088056",
"21088072",
"21088085",
"21088098",
"21088108",
"21088182",
"21088195",
"21088205",
"21088221",
"21088234",
"21088247",
"21088276",
"21088289",
"21088315",
"21088328",
"21088454",
"21088467",
"21088483",
"21088496",
"21088506",
"21088519",
"21088564",
"21088593",
"21088603",
"21088616",
"21088713",
"21088726",
"21088742",
"21088823",
"21088852",
"21088865",
"21088904",
"21088917",
"21088946",
"21088962",
"21089275",
"21089327",
"21089356",
"21089372",
"21089385",
"21089398",
"21089408",
"21089411",
"21089424",
"21089437",
"21089453",
"21089466",
"21089479",
"21089482",
"21089495",
"21089505",
"21089576",
"21089592",
"21089602",
"21089615",
"21089628",
"21089631",
"21089644",
"21089657",
"21089673",
"21089686",
"21089699",
"21089709",
"21089712",
"21089725",
"21089738",
"21089741",
"21089754",
"21089767",
"21089783",
"21089796",
"21089806",
"21089819",
"21089835",
"21089848",
"21089851",
"21089864",
"21110209",
"21272723",
"21272778",
"21300077",
"21300912",
"21301115",
"21302554",
"21302648",
"21302884",
"21303472",
"21303511",
"21303618",
"21304675",
"21305386",
"21305577",
"21305991",
"21306547",
"21307054",
"21307096",
"21307164",
"21307559",
"21307708",
"21307711",
"21308396",
"21308642",
"21308833",
"21309201",
"21309285",
"21309735",
"21309997",
"21310407",
"21310465",
"21310591",
"21310711",
"21311383",
"21311529",
"21313103",
"21313734",
"21313925",
"21373059",
"21405464",
"21406667",
"21407912",
"21411335",
"21412114",
"21413155",
"21413388",
"21413935",
"21414280",
"21414426",
"21414455",
"21473030",
"21484524",
"21902475",
"21906808",
"21907975",
"21909300",
"21909342",
"21911369",
"21911398",
"21912410",
"21912915",
"21912928",
"21913639",
"21913862",
"21914078",
"21914544",
"22000046",
"22000868",
"22072692",
"22302786",
"22303659",
"22304030",
"22304616",
"22305770",
"22306818",
"22306847",
"22307600",
"22307820",
"22309611",
"22310121",
"22311117",
"22313021",
"22314020",
"22314334",
"26000110",
"26000194",
"26000217",
"26001287",
"26001423",
"26001465",
"26001847",
"26002066",
"26002095",
"26002341",
"26002532",
"26002545",
"26002558",
"26002561",
"26002574",
"26002613",
"26002655",
"26002749",
"26002752",
"26002794",
"26002846",
"26002901",
"26002927",
"26002956",
"26003007",
"26003010",
"26003023",
"26003036",
"26003188",
"26003191",
"26003243",
"26003269",
"26003272",
"26003324",
"26003379",
"26003447",
"26003557",
"26003780",
"26004048",
"26004093",
"26004226",
"26004297",
"26004307",
"26004624",
"26004721",
"26004860",
"26004970",
"26005050",
"26005092",
"26005319",
"26005322",
"26005416",
"26005458",
"26005487",
"26005610",
"26005652",
"26005908",
"26005911",
"26006004",
"26006224",
"26006237",
"26007113",
"26007362",
"26007443",
"26007472",
"26007582",
"26007692",
"26007728",
"26007773",
"26007809",
"26007906",
"26007922",
"26007993",
"26008044",
"26008073",
"26008248",
"26008455",
"26008536",
"26008552",
"26008620",
"26008659",
"26008691",
"26008714",
"26008756",
"26008808",
"26008853",
"26008866",
"26009027",
"26009085",
"26009124",
"26009140",
"26009166",
"26009179",
"26009247",
"26009593",
"26009674",
"26009768",
"26009917",
"26009920",
"26009988",
"26010090",
"26010605",
"26010773",
"26010786",
"26010841",
"26010883",
"26010948",
"26011031",
"26011125",
"26011167",
"26011206",
"26011293",
"26011471",
"26011484",
"26011497",
"26011617",
"26011701",
"26011743",
"26011785",
"26011921",
"26011947",
"26011950",
"26011963",
"26012030",
"26012043",
"26012098",
"26012179",
"26012425",
"26012470",
"26012548",
"26012577",
"26012603",
"26012629",
"26012713",
"26013071",
"26013136",
"26013165",
"26013246",
"26013275",
"26013291",
"26013343",
"26013356",
"26013408",
"26013453",
"26013479",
"26013518",
"26013576",
"26013615",
"26013673",
"26013767",
"26013783",
"26013796",
"26013851",
"26013916",
"26013958",
"26013961",
"26013990",
"26014135",
"26014193",
"26014216",
"26014245",
"26014384",
"26014407",
"26014504",
"26014559",
"26014562",
"26014591",
"26014601",
"26014627",
"26014643",
"26014685",
"26014805",
"26014889",
"26014915",
"26014944",
"26072973",
"26073008",
"26073066",
"26073134",
"26084628",
"26510037",
"26510053",
"26510079",
"26510082",
"26510095",
"26510105",
"26510189",
"26510215",
"26510312",
"26510325",
"26510464",
"31040026",
"52073564",
"63192450",
"211170088",
"211170130",
"211170185",
"211174369",
"211672531",
"221172610",
"221370030",
"221370108",
"221370195",
"221370205",
"221370289",
"221370399",
"221370467",
"221370496",
"221370616",
"221370878",
"221370894",
"221370904",
"221371071",
"221371123",
"221371194",
"221371259",
"221371356",
"221371372",
"221371534",
"221371550",
"221371563",
"221371592",
"221371709",
"221371770",
"221373273",
"221373383",
"221373451",
"221375174",
"221375378",
"221375802",
"221376539",
"221378993",
"221379769",
"221379785",
"221379824",
"221379895",
"221379905",
"221380127",
"221380790",
"221380936",
"221381304",
"221381540",
"221381715",
"221381867",
"221472792",
"221472815",
"221473652",
"221475605",
"221475773",
"221475786",
"221475896",
"221476442",
"221476688",
"221476701",
"221478877",
"221480807",
"221481181",
"221970443",
"221970825",
"221970980",
"221971015",
"221971086",
"221971138",
"221971264",
"221971316",
"221972098",
"221972234",
"221975956",
"221976243",
"221979363",
"221981063",
"221981254",
"221981267",
"221981335",
"222080222",
"222370440",
"222370518",
"222371054",
"222371656",
"222371698",
"222371740",
"222371805",
"222371863",
"222379195",
"222380359",
"222380388",
"222380443",
"222380692",
"222381293",
"222381824",
"222381882",
"222381918",
"222382221",
"222382292",
"222382315",
"222382438",
"222383479",
"226070128",
"226070131",
"226070306",
"226070403",
"226070474",
"226070584",
"226071004",
"226071033",
"226071457",
"226072317",
"226072472",
"226072498",
"226072511",
"226072841",
"226072870",
"226073895",
"226075291",
"226075482",
"226076083",
"226076135",
"226078036",
"226078104",
"226078379",
"226078476",
"226078609",
"226082022",
"226082129",
"226082598",
"231385400"
);

foreach ($new_values_array as $value)
{
//echo $value, "\n";

$query =
"
SELECT value_id
FROM suppression_list_values
WHERE value = '{$value}'
";
$result = $db->Query($query);
$row = $result->fetch(PDO::FETCH_OBJ);
$value_id = intval($row->value_id);

if (empty($value_id))
{
//insert value and suppression_list_revision_values
echo $value, " does NOT exist", "\n";

$query_insert_value =
"
INSERT INTO
suppression_list_values
SET
value = '{$value}',
date_created = NOW()
";
$db->Query($query_insert_value);

$query_find_new_value_id =
"
SELECT value_id
FROM suppression_list_values
WHERE value = '{$value}'
";
$result = $db->Query($query_find_new_value_id);
$row = $result->fetch(PDO::FETCH_OBJ);
$value_id = intval($row->value_id);

$query_insert_srv =
"
INSERT IGNORE INTO
suppression_list_revision_values
SET
list_id = {$list_id},
revision_id = {$new_revision_id},
value_id = {$value_id}
";
$db->Query($query_insert_srv);
}
else
{
//insert only suppression_list_revision_values
echo $value, " exists, value_id: ", $value_id, "\n";
$query_insert_srv =
"
INSERT IGNORE INTO
suppression_list_revision_values
SET
list_id = {$list_id},
revision_id = {$new_revision_id},
value_id = {$value_id}
";
$db->Query($query_insert_srv);
}

}

// UPDATE status
$query =
"
UPDATE suppression_list_revisions
SET status='INACTIVE'
WHERE revision_id = {$current_active_revision_id}
";
$db->Query($query);

$query =
"
UPDATE suppression_list_revisions
SET status='ACTIVE'
WHERE revision_id = {$new_revision_id}
";
$db->Query($query);

?>
