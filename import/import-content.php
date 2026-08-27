<?php
/**
 * Imam Ali Theme — Dummy Content Importer
 * Access once at: http://imam-ali.local/wp-content/themes/imam-ali-theme/import/import-content.php
 * Delete this file after importing.
 */

// Bootstrap WordPress
$wp_load = dirname(__FILE__, 5) . '/wp-load.php';
if (!file_exists($wp_load)) {
    die('Could not find wp-load.php. Make sure this file is inside your theme folder.');
}
require_once $wp_load;

// Must be logged in as admin
if (!current_user_can('manage_options')) {
    wp_die('You must be logged in as an administrator to run this importer.');
}

// Prevent running twice
if (get_option('imam_ali_content_imported')) {
    wp_die('Content has already been imported. Delete this file for security.');
}

require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$results = [];

/* ── Helper: sideload image ──────────────────────────────────── */
function ia_sideload_image(string $url, int $post_id): void {
    $tmp = download_url($url);
    if (is_wp_error($tmp)) return;
    $file = [
        'name'     => basename(parse_url($url, PHP_URL_PATH)) ?: 'image.jpg',
        'type'     => mime_content_type($tmp),
        'tmp_name' => $tmp,
        'error'    => 0,
        'size'     => filesize($tmp),
    ];
    $attach_id = media_handle_sideload($file, $post_id);
    if (!is_wp_error($attach_id)) {
        set_post_thumbnail($post_id, $attach_id);
    }
}

/* ── Categories ──────────────────────────────────────────────── */
$category_data = [
    ['Life & Legacy',        'life-and-legacy',        'The biography, early life, character, and lasting legacy of Imam Ali (A.S.) — the Lion of God.'],
    ['Ahlul Bayt',           'ahlul-bayt',             'The blessed household of the Prophet — their lives, sacrifices, and enduring spiritual authority.'],
    ['Imamate & Ghadir',     'imamate-and-ghadir',     'The divine appointment of Imam Ali at Ghadir Khumm and the doctrine of Imamate in Islam.'],
    ['Battles & Leadership', 'battles-and-leadership', 'The military campaigns, governance, and leadership of Imam Ali (A.S.) across Islamic history.'],
    ['Wisdom',               'wisdom',                 'The timeless wisdom, sermons, letters, and sayings of Imam Ali preserved in Nahj al-Balagha.'],
    ['Shrine & Ziyarat',     'shrine-and-ziyarat',     'The sacred shrine of Imam Ali in Najaf — a spiritual centre visited by millions each year.'],
];

$cat_ids = [];
foreach ($category_data as [$name, $slug, $desc]) {
    $existing = get_category_by_slug($slug);
    if ($existing) {
        $cat_ids[$slug] = $existing->term_id;
        wp_update_term($existing->term_id, 'category', ['description' => $desc]);
    } else {
        $r = wp_insert_term($name, 'category', ['slug' => $slug, 'description' => $desc]);
        $cat_ids[$slug] = is_wp_error($r) ? 0 : $r['term_id'];
    }
    $results[] = "Category: $name";
}

/* ── Picsum image seeds per article ─────────────────────────── */
$image_seeds = [
    'https://picsum.photos/seed/imam1/800/500',
    'https://picsum.photos/seed/imam2/800/500',
    'https://picsum.photos/seed/imam3/800/500',
    'https://picsum.photos/seed/imam4/800/500',
    'https://picsum.photos/seed/imam5/800/500',
    'https://picsum.photos/seed/imam6/800/500',
    'https://picsum.photos/seed/imam7/800/500',
    'https://picsum.photos/seed/imam8/800/500',
    'https://picsum.photos/seed/imam9/800/500',
    'https://picsum.photos/seed/imam10/800/500',
    'https://picsum.photos/seed/imam11/800/500',
    'https://picsum.photos/seed/imam12/800/500',
    'https://picsum.photos/seed/imam13/800/500',
    'https://picsum.photos/seed/imam14/800/500',
    'https://picsum.photos/seed/imam15/800/500',
    'https://picsum.photos/seed/imam16/800/500',
    'https://picsum.photos/seed/imam17/800/500',
];

/* ── Articles ────────────────────────────────────────────────── */
$articles = [

  // Life & Legacy
  [
    'title'    => 'The Birth of Imam Ali in the Holy Kaaba',
    'slug'     => 'birth-imam-ali-holy-kaaba',
    'category' => 'life-and-legacy',
    'sticky'   => true,
    'excerpt'  => 'Born inside the sacred walls of the Kaaba in Mecca, Imam Ali\'s arrival into the world was unlike any other — a divine sign that set the course of Islamic history.',
    'content'  => '<p>Among the most remarkable events in Islamic history is the birth of Imam Ali ibn Abi Talib (A.S.) inside the Holy Kaaba in Mecca. No other human being, before or after, has been born inside this sacred sanctuary — an honor that marks the exceptional spiritual station of the Commander of the Faithful.</p>

<p>His mother, Fatimah bint Asad, felt the onset of labor while circumambulating the Kaaba. In a miraculous moment, the wall of the Kaaba opened before her and she stepped inside. Three days later, she emerged carrying the newborn Ali in her arms. This event was witnessed by many and has been recorded across historical sources from both Sunni and Shia traditions.</p>

<h2>A Birth Foretold</h2>
<p>Many scholars have noted that the birth of Imam Ali inside the Kaaba was not a coincidence but a divine arrangement. The Prophet Muhammad (S.A.W.) would later say of Ali: "You are to me as Aaron was to Moses, except that there shall be no prophet after me." This profound connection between the Prophet and his cousin and son-in-law began at the very moment of Ali\'s birth.</p>

<p>Imam Ali was born on the 13th of Rajab, approximately 23 years before the Hijra. His father, Abu Talib, was the uncle and guardian of Prophet Muhammad. From his earliest years, Ali grew up in the household of the Prophet, learning directly from him and absorbing the light of divine guidance.</p>

<h2>The First to Accept Islam</h2>
<p>When the Prophet Muhammad received his first revelation and began privately calling people to Islam, it was the young Ali — still a boy — who became the first male to accept the new faith. This act of courage and loyalty would define the rest of his life. He stood beside the Prophet through every hardship, never wavering in his commitment even when the community of believers was small and persecuted.</p>

<p>The birth of Imam Ali inside the Kaaba remains a celebrated event among Muslims worldwide. It symbolizes that the one who would become the Commander of the Faithful, the gateway to the city of knowledge, and the voice of justice was marked by the Divine from the very first moment of his life.</p>',
  ],
  [
    'title'    => 'The Courage and Wisdom of Imam Ali Before Prophethood',
    'slug'     => 'courage-wisdom-imam-ali-before-prophethood',
    'category' => 'life-and-legacy',
    'sticky'   => false,
    'excerpt'  => 'Long before the Prophet\'s mission began, Imam Ali demonstrated extraordinary wisdom, loyalty, and courage that marked him as destined for greatness.',
    'content'  => '<p>The years before the declaration of Prophethood were formative ones for Imam Ali. Growing up in the household of Prophet Muhammad (S.A.W.), he was immersed in an environment of spiritual depth, moral excellence, and profound wisdom. The Prophet recognized early that Ali was no ordinary child.</p>

<p>From a young age, Ali displayed qualities that set him apart: an extraordinary memory, a sharp and penetrating intellect, and an unwavering sense of justice. He would spend long hours in contemplation and prayer, reflecting on the nature of existence and the oneness of God even before formal revelation had arrived.</p>

<h2>Loyalty in Difficult Times</h2>
<p>When the Quraysh tribe of Mecca began threatening the Prophet after his public declaration of Islam, it was Ali who served as the Prophet\'s shield and protector. On numerous occasions, Ali placed himself between the Prophet and those who sought to harm him. This physical and moral courage became one of the defining characteristics of his life.</p>

<p>One of the most celebrated demonstrations of this loyalty came on the Night of Migration (Laylat al-Mabit), when Imam Ali slept in the Prophet\'s bed, fully aware that assassins planned to kill the Prophet that night. By taking this risk upon himself, he allowed the Prophet to safely begin his migration to Medina.</p>

<h2>The Night of Migration</h2>
<p>The divine verse revealed about this night — "And of the people is he who sells himself, seeking the means to the approval of Allah" (Quran 2:207) — is widely understood by commentators to refer to Imam Ali\'s selfless act of sleeping in the Prophet\'s place. It was a moment that encapsulated everything about who Ali was: a man who gave everything in service of truth and the divine mission.</p>

<p>These early years paint a portrait of Imam Ali not merely as a great leader who would emerge later, but as someone who was being shaped, tested, and prepared for a role of immense importance in the story of Islam.</p>',
  ],
  [
    'title'    => 'The Martyrdom of Imam Ali: A Night that Shook Islam',
    'slug'     => 'martyrdom-imam-ali-night-that-shook-islam',
    'category' => 'life-and-legacy',
    'sticky'   => false,
    'excerpt'  => 'On the 19th of Ramadan, while prostrating in prayer, Imam Ali was struck by the poisoned sword of Abd al-Rahman ibn Muljam — and two days later, the world lost its greatest just ruler.',
    'content'  => '<p>The night of the 19th of Ramadan, in the year 40 AH, began like any other for the Commander of the Faithful. Imam Ali ibn Abi Talib (A.S.) rose before dawn, as was his custom, to perform the night prayers in the mosque of Kufa. He had been fasting and was in a state of deep spiritual devotion when Abd al-Rahman ibn Muljam — a Kharijite who had secretly planned the assassination — struck him with a poisoned sword on his head while he was prostrating in prayer.</p>

<p>The blow was devastating, but Imam Ali\'s first words upon being struck were remarkably composed: "By the Lord of the Kaaba, I have succeeded." He understood that this was the moment of his martyrdom, something he had spoken of and in many ways welcomed as a reunion with his Lord.</p>

<h2>Two Days of Farewell</h2>
<p>Imam Ali survived for two days after the attack. During this time, he continued to give counsel, settle debts, and offer advice to his sons Hassan and Hussain and to the community of believers. He instructed that his attacker be treated justly — neither tortured nor killed in retaliation beyond what was appropriate — demonstrating that even in his dying moments, his commitment to justice overrode any personal sentiment.</p>

<p>He passed away on the 21st of Ramadan, 40 AH. The news of his martyrdom sent shockwaves across the Muslim world. Companions who had witnessed the entire span of Islamic history wept openly. The man who had been the first male Muslim, who had slept in the Prophet\'s bed, who had carried the banner of Islam through its most critical battles, who had governed with unmatched justice — was gone.</p>

<h2>A Legacy That Cannot Die</h2>
<p>Imam Ali was buried in Najaf, Iraq, where his shrine stands to this day as one of the holiest sites in Islam. Millions of pilgrims visit each year, and his words — collected in Nahj al-Balagha — continue to guide, inspire, and challenge readers across the world. His martyrdom was an ending only in the physical sense; his legacy has never stopped growing.</p>',
  ],

  // Ahlul Bayt
  [
    'title'    => 'The Sacred Marriage of Imam Ali and Lady Fatimah',
    'slug'     => 'sacred-marriage-imam-ali-lady-fatimah',
    'category' => 'ahlul-bayt',
    'sticky'   => false,
    'excerpt'  => 'The union of Imam Ali and Lady Fatimah al-Zahra was not merely a marriage between two individuals — it was a divine bond between the two greatest souls of their age.',
    'content'  => '<p>Among the most spiritually significant events in Islamic history is the marriage of Imam Ali ibn Abi Talib (A.S.) and Lady Fatimah al-Zahra (A.S.), the beloved daughter of Prophet Muhammad (S.A.W.). This union, which took place in the second year of Hijra, was arranged by divine command and stands as a model of spiritual partnership, mutual respect, and shared devotion to God.</p>

<p>Several companions of the Prophet had requested Lady Fatimah\'s hand in marriage, but the Prophet declined each offer, saying: "Her matter is with God." When Imam Ali finally approached the Prophet with his request, the Prophet smiled and said he had been waiting for this — for the angel Jibreel had informed him that God had already arranged this marriage in the heavens before it was to be enacted on earth.</p>

<h2>A Simple and Blessed Wedding</h2>
<p>The wedding ceremony was modest by any worldly measure. Imam Ali sold his armor to gather the marriage dowry — approximately 480 dirhams. The Prophet himself arranged the simple wedding feast, which consisted of dates, figs, and sheep\'s milk. Yet this simplicity was not poverty of spirit — it was a declaration of the values that both Imam Ali and Lady Fatimah held: that the richness of the soul far exceeds the adornments of the world.</p>

<p>The Prophet gave Lady Fatimah a practical gift: a leather cushion filled with palm fiber, a water skin, and a hand-mill. These were the tools of a dignified household, and both Imam Ali and Fatimah worked together — she managing the home, he providing for them through his labor and his role in the Muslim community.</p>

<h2>A Partnership of Equals</h2>
<p>Their marriage was one of deep mutual respect and love. Historical accounts describe Imam Ali as never causing Lady Fatimah grief in all the years of their marriage. When asked about his wife, he spoke of her with tenderness and admiration. Their home became a center of spiritual light, from which emerged Imam Hassan and Imam Hussain — two of the most revered figures in all of Islam.</p>

<p>Lady Fatimah passed away a short time after her father, the Prophet, in the year 11 AH. Imam Ali was devastated by her loss. At her grave, he spoke words of farewell so tender and profound that they are still quoted today: "O Fatimah, peace be upon you from me and from the Messenger of God who is your father."</p>',
  ],
  [
    'title'    => 'Imam Hassan: The Legacy of the Prophet\'s Beloved Grandson',
    'slug'     => 'imam-hassan-legacy-prophets-beloved-grandson',
    'category' => 'ahlul-bayt',
    'sticky'   => false,
    'excerpt'  => 'Imam Hassan ibn Ali carried the light of his grandfather, the Prophet, and his father, Imam Ali, guiding the Muslim community with patience, wisdom, and extraordinary peace.',
    'content'  => '<p>Imam Hassan ibn Ali (A.S.), the elder son of Imam Ali and Lady Fatimah, was born in the third year of Hijra in Medina. From the moment of his birth, he was cherished by his grandfather Prophet Muhammad (S.A.W.), who named him, whispered the call to prayer in his ear, and carried him with visible joy and pride. The Prophet would often say of Hassan and his brother Hussain: "These two are my two flowers in this world."</p>

<p>Imam Hassan grew up immersed in the teachings and example of both his grandfather and his father. He absorbed their wisdom, their eloquence, their justice, and their deep connection to God. By the time he reached adulthood, he was recognized as one of the most knowledgeable and morally upright individuals in the Muslim community.</p>

<h2>Leadership After Imam Ali</h2>
<p>Following the martyrdom of Imam Ali in 40 AH, Imam Hassan assumed leadership of the Muslim community. He faced an extraordinarily difficult situation — a community fractured by internal conflict, an army whose loyalty had been tested and found wanting, and an opposition under Muawiyah ibn Abi Sufyan that was militarily powerful and politically ruthless.</p>

<p>After careful consideration of the lives and welfare of his followers, Imam Hassan chose to negotiate a peace treaty with Muawiyah rather than engage in a war that he foresaw would bring tremendous bloodshed without meaningful benefit to the truth. This decision, which some at the time criticized, demonstrated his profound understanding of the long-term interests of Islam and the protection of human life.</p>

<h2>A Life of Generosity and Piety</h2>
<p>Imam Hassan was renowned for his extraordinary generosity. It is recorded that he performed the Hajj pilgrimage on foot more than twenty times. He gave away his entire wealth twice in his lifetime to those in need. Those who came to him in hardship never left empty-handed.</p>

<p>He was martyred in 50 AH through poisoning — a death arranged by those who feared his continued moral authority over the Muslim world. He was buried in Medina, near his grandmother Khadijah and his mother Fatimah. His legacy endures as a model of patient leadership, generosity, and unwavering devotion to God.</p>',
  ],
  [
    'title'    => 'The Eternal Bond Between Imam Ali and Imam Hussain',
    'slug'     => 'eternal-bond-imam-ali-imam-hussain',
    'category' => 'ahlul-bayt',
    'sticky'   => false,
    'excerpt'  => 'The relationship between Imam Ali and his son Hussain was one of the most profound father-son bonds in history — shaped by faith, love, courage, and a shared commitment to truth.',
    'content'  => '<p>Among the relationships that define the spiritual legacy of the Ahlul Bayt, the bond between Imam Ali (A.S.) and his son Imam Hussain (A.S.) stands as one of the most moving and historically significant. It was a relationship forged in the light of prophecy, nurtured through years of shared devotion, and ultimately sealed through sacrifice.</p>

<p>The Prophet Muhammad (S.A.W.) had said of Imam Hussain: "Hussain is from me and I am from Hussain." This phrase, seemingly simple, carries immense depth. Imam Hussain carried the mission of the Prophet forward — and through his father Imam Ali, he inherited the spiritual and intellectual treasures that would sustain the Islamic tradition for centuries to come.</p>

<h2>A Father\'s Teaching</h2>
<p>Imam Ali was deeply involved in the upbringing and education of both Hassan and Hussain. He taught them the Quran, the principles of justice, the ethics of leadership, and the necessity of standing for truth regardless of the personal cost. The letters of Imam Ali, preserved in Nahj al-Balagha, include some of his most moving words directed to his sons — instructions that read not merely as parental advice but as a spiritual inheritance.</p>

<p>Imam Ali once said to Hussain: "Be in the world as if you are a stranger or a wayfarer." This teaching of detachment from worldly comforts and focus on eternal values was one that Hussain absorbed deeply — and one that would guide his actions decades later at Karbala, where he gave his life rather than bow to injustice.</p>

<h2>The Legacy They Share</h2>
<p>The events of Karbala in 61 AH, in which Imam Hussain was martyred, are incomprehensible without understanding who his father was and what he taught. Hussain\'s famous declaration — "I do not see death except as martyrdom, and life among the unjust except as suffering" — echoes his father\'s entire life philosophy.</p>

<p>Today, the shrines of Imam Ali in Najaf and Imam Hussain in Karbala stand just miles apart — as they stood in life, inseparable in spirit, united in purpose, and eternal in the hearts of those who love them.</p>',
  ],

  // Imamate & Ghadir
  [
    'title'    => 'Ghadir Khumm: The Day Imam Ali Was Appointed by Divine Command',
    'slug'     => 'ghadir-khumm-day-imam-ali-appointed-divine-command',
    'category' => 'imamate-and-ghadir',
    'sticky'   => false,
    'excerpt'  => 'On the 18th of Dhul-Hijjah, after his Farewell Pilgrimage, the Prophet Muhammad stopped at Ghadir Khumm and declared to over 100,000 pilgrims: "Whoever I am his master, Ali is his master."',
    'content'  => '<p>The event of Ghadir Khumm stands as one of the most documented and significant moments in early Islamic history. On the 18th of Dhul-Hijjah, in the year 10 AH — just months before the Prophet Muhammad (S.A.W.) would pass away — the Prophet stopped a massive caravan of returning pilgrims at a place called Ghadir Khumm, located between Mecca and Medina.</p>

<p>The reason for this unusual stop came by divine command. The Quranic verse revealed at that moment stated: "O Messenger, deliver what has been revealed to you from your Lord, and if you do not, then you have not delivered His message. And Allah will protect you from the people." (Quran 5:67) This verse — with its urgency and assurance of divine protection — indicated that what was about to be announced was of the highest importance.</p>

<h2>The Declaration</h2>
<p>The Prophet gathered the pilgrims, estimated by historians to number between 70,000 and 120,000. He delivered a lengthy sermon and then raised the hand of Imam Ali, declaring: "Whoever I am his master (mawla), Ali is his master. O God, befriend those who befriend him, and be an enemy to those who are his enemy. Help those who help him, and abandon those who abandon him."</p>

<p>Immediately after the Prophet concluded, the Quranic verse was revealed: "This day I have perfected your religion for you, completed My favor upon you, and have chosen for you Islam as your religion." (Quran 5:3). The significance of this verse — speaking of the completion and perfection of religion — being revealed at this moment has been a subject of extensive discussion among Islamic scholars.</p>

<h2>A Pivotal Moment</h2>
<p>The companions present, including leading figures of the early Muslim community, came forward to congratulate Imam Ali on his appointment. Among the first to do so was Umar ibn al-Khattab, who said: "Congratulations, O son of Abu Talib! You have become the master of every believing man and woman."</p>

<p>Ghadir Khumm is commemorated annually on the 18th of Dhul-Hijjah as Eid al-Ghadir — a day of celebration for millions of Muslims around the world who see in it the formal divine appointment of Imam Ali as the rightful leader of the Muslim community after the Prophet.</p>',
  ],
  [
    'title'    => 'The Doctrine of Imamate: Divine Leadership in Islam',
    'slug'     => 'doctrine-imamate-divine-leadership-islam',
    'category' => 'imamate-and-ghadir',
    'sticky'   => false,
    'excerpt'  => 'The concept of Imamate — divinely appointed leadership — is one of the central theological pillars of Islamic thought, connecting the guidance of the Prophet to the living presence of an Imam.',
    'content'  => '<p>At the heart of much Islamic theological discussion is the concept of Imamate — the doctrine that divine guidance does not end with the death of the Prophet, but continues through a line of appointed leaders (Imams) who carry the spiritual, moral, and intellectual authority of the Prophet\'s mission.</p>

<p>The Quran speaks repeatedly of divine leadership and appointment: "We made them leaders guiding by Our command" (21:73) and "Indeed, I will make you a leader (imam) for the people" (2:124). These verses, and many prophetic traditions, form the scriptural basis for the belief that God does not leave humanity without authoritative guidance.</p>

<h2>The Role of the Imam</h2>
<p>According to Islamic theological tradition, the Imam is not merely a political leader or an administrator of religious law. The Imam is the living repository of divine knowledge, the interpreter of the Quran\'s inner meanings, and the spiritual guide who can illuminate the path to God for those who seek it. Imam Ali himself described this role when he said: "Ask me before you lose me. By God, if you asked me about every verse in the Quran, I would tell you whether it was revealed by night or by day, whether on a plain or in the mountains."</p>

<p>This claim to comprehensive knowledge of the Quran and its context was not arrogance — it was a statement of divine appointment and the responsibility that came with it. The Imam, in this understanding, is the indispensable link between humanity and the divine message.</p>

<h2>Imamate and Justice</h2>
<p>Central to the concept of Imamate is justice. An Imam is not appointed by popular vote or tribal consensus — he is appointed by God and must embody the highest standards of justice, knowledge, and moral excellence. Imam Ali\'s brief caliphate (35–40 AH) is studied by historians across religious traditions as a remarkable attempt to implement justice-based governance — returning lands wrongfully seized, ensuring equal treatment before the law, and personally investigating complaints against officials.</p>

<p>The doctrine of Imamate, in its various formulations, has profoundly shaped Islamic civilization, philosophy, law, and spirituality. At its center stands the figure of Imam Ali — the first Imam, the Commander of the Faithful, the man the Prophet himself called the gateway to the city of knowledge.</p>',
  ],
  [
    'title'    => 'Imam Ali in the Holy Quran: The Divine References',
    'slug'     => 'imam-ali-holy-quran-divine-references',
    'category' => 'imamate-and-ghadir',
    'sticky'   => false,
    'excerpt'  => 'Across the chapters of the Holy Quran, scholars have identified numerous verses understood — by both classical and contemporary commentators — to refer to Imam Ali\'s unique station.',
    'content'  => '<p>The relationship between Imam Ali (A.S.) and the Holy Quran is profound and multifaceted. On one level, Imam Ali was the first person outside the Prophet\'s immediate family to commit the entire Quran to memory, and he played a central role in its preservation and compilation. On another level, numerous Quranic verses are understood by scholars to refer directly to Imam Ali and to describe his spiritual station in relation to the Prophet and the Muslim community.</p>

<h2>The Verse of Wilayah</h2>
<p>Among the most discussed is verse 5:55: "Your guardian (wali) is only Allah and His Messenger and those who believe, those who establish prayer and give zakah while they are bowing." Classical commentators, including many from the Sunni tradition, record that this verse was revealed when Imam Ali gave his ring in charity while bowing in prayer. The verse establishes a hierarchy of guardianship — God, the Prophet, and then the believer who gives zakah while bowing — with Imam Ali as the specific occasion of revelation.</p>

<h2>The Verse of Mubahalah</h2>
<p>Verse 3:61, known as the Verse of Mubahalah, instructs the Prophet to bring "ourselves and yourselves, our sons and your sons, our women and your women" to a spiritual contest with the Christians of Najran. Historical sources record that the Prophet brought only Ali, Fatimah, Hassan, and Hussain — referring to Ali as his "self" (nafs). This identification of Imam Ali as the soul (nafs) of the Prophet has been extensively commented upon.</p>

<h2>The Verse of Completion</h2>
<p>As discussed in the account of Ghadir Khumm, verse 5:3 — "This day I have perfected your religion for you" — was revealed after the Prophet\'s announcement of Imam Ali\'s leadership. The placement of this declaration of religious completion at that specific moment has led scholars to connect the perfection of Islam with the formal appointment of Imam Ali.</p>

<p>These and numerous other verses have been the subject of extensive commentary across Islamic theological traditions, making the study of Imam Ali\'s relationship with the Quran one of the richest areas of Islamic scholarship.</p>',
  ],

  // Battles & Leadership
  [
    'title'    => 'The Battle of Badr: Imam Ali\'s First Great Test of Courage',
    'slug'     => 'battle-of-badr-imam-ali-first-great-test-courage',
    'category' => 'battles-and-leadership',
    'sticky'   => false,
    'excerpt'  => 'At the Battle of Badr in 2 AH, Imam Ali emerged as the decisive force that secured a victory against overwhelming odds — a battle that would define the survival of early Islam.',
    'content'  => '<p>The Battle of Badr, fought on the 17th of Ramadan in the year 2 AH, was the first major military engagement between the young Muslim community of Medina and the powerful Quraysh tribe of Mecca. The Muslims were outnumbered nearly three to one — facing approximately 1,000 well-armed Qurayshi soldiers with only around 313 poorly equipped fighters. In this context, the role of Imam Ali proved decisive.</p>

<p>The battle began with single combat, as was the custom of Arabian warfare. Three Qurayshi champions stepped forward: Utba ibn Rabi\'a, his son al-Walid, and his brother Shaybah. Three Muslims were called to face them. The young Imam Ali, alongside Hamzah and Ubayda, stepped onto the field. While Hamzah defeated Utba and Ubayda engaged Shaybah — being mortally wounded in the process — Imam Ali swiftly dispatched al-Walid with a single blow.</p>

<h2>The Turning of the Battle</h2>
<p>When the full battle commenced, Imam Ali\'s presence on the battlefield was transformative. Historical accounts indicate that he personally killed between 21 and 36 of the Qurayshi fighters — a remarkable number given the size of the engagement. At multiple critical moments, he turned the tide when it appeared the Muslim lines might break.</p>

<p>The angel Jibreel is said to have called out during the battle: "There is no sword but Dhul Fiqar, and there is no brave man but Ali." This proclamation echoed through Islamic history, associating Imam Ali permanently with the famous double-bladed sword that became his symbol.</p>

<h2>The Significance of Badr</h2>
<p>Badr was not merely a military victory. It was the moment that established the credibility and survival of the young Muslim community in the face of what seemed impossible odds. Imam Ali\'s role in securing that victory was foundational. The Prophet recognized this, and the battle deepened the bond between the two men who were already connected by faith, family, and love.</p>',
  ],
  [
    'title'    => 'The Battle of Khaybar: The Opening of the Impregnable Fortress',
    'slug'     => 'battle-of-khaybar-opening-impregnable-fortress',
    'category' => 'battles-and-leadership',
    'sticky'   => false,
    'excerpt'  => 'At Khaybar, when all others had failed, the Prophet entrusted the banner of Islam to Imam Ali — who then opened the fortress that had seemed unconquerable.',
    'content'  => '<p>The Battle of Khaybar, fought in the seventh year of Hijra (7 AH), stands as one of the most celebrated military engagements of early Islam — and at its center is a moment that has been repeated in poetry, song, and oral tradition across centuries: the giving of the banner to Imam Ali.</p>

<p>The fortresses of Khaybar had resisted the Muslim army for days. Two companions of the Prophet had each attempted to lead the assault and had returned defeated and dispirited. The Prophet announced: "Tomorrow I will give the banner to a man who loves God and His Messenger, and God and His Messenger love him — a man who is not a deserter, who will not flee." The entire army waited in anticipation through the night, each hoping to be chosen.</p>

<h2>The Appointment of Ali</h2>
<p>The following morning, the Prophet called for Imam Ali, who was suffering from an eye ailment so severe that he had been unable to see clearly for days. The Prophet applied his saliva to Ali\'s eyes and prayed for him — and the pain departed instantly, his vision restored. Then he gave him the banner and issued his famous declaration of love.</p>

<p>Imam Ali marched toward the fortress and engaged the legendary warrior Marhab, who was said to be so powerful that no one had ever bested him in single combat. The exchange was brief. Imam Ali struck Marhab with a single blow of Dhul Fiqar that split both his helmet and his head. With Marhab fallen, the fortress\'s resistance collapsed, and Khaybar was opened.</p>

<h2>A Moment of Pure Faith</h2>
<p>What makes the story of Khaybar so enduring is not simply the military victory — it is the intimacy of the Prophet\'s declaration. "God and His Messenger love him." In a tradition where divine love is the highest aspiration, to be named as the beloved of God and the Prophet in front of an entire army is the most profound of honors. Khaybar was not just a battle — it was a proclamation of Imam Ali\'s spiritual station.</p>',
  ],
  [
    'title'    => 'Imam Ali as Caliph: Justice Beyond Any Other Ruler',
    'slug'     => 'imam-ali-caliph-justice-beyond-any-other-ruler',
    'category' => 'battles-and-leadership',
    'sticky'   => false,
    'excerpt'  => 'When Imam Ali finally assumed the caliphate in 35 AH, he governed with a standard of justice so exacting that it earned both admiration and fierce opposition from those who profited from corruption.',
    'content'  => '<p>The caliphate of Imam Ali ibn Abi Talib (A.S.), which lasted from 35 AH to his martyrdom in 40 AH, represents one of the most remarkable and contested periods of early Islamic governance. It was remarkable for the absolute commitment to justice that characterized every decision; it was contested because that same commitment to justice upended the privileges that powerful figures had accumulated over the preceding years.</p>

<p>On the very first day of his caliphate, Imam Ali addressed the crowd gathered to pledge allegiance: "Be aware that the problems you faced are the same ones that faced the people when the Prophet was first sent. By God, you will be turned upside down and thoroughly shaken, and the one who is last will be at the front and the one who is first will be at the back." This was not merely rhetoric — it was a statement of policy.</p>

<h2>Returning the Public Treasury</h2>
<p>One of Imam Ali\'s first acts was to reclaim lands and resources that had been distributed to political allies by the previous administration without proper justification. He declared: "By God, even if I had found that money used for the marriage of women or to buy slave-girls, I would have returned it to the treasury, because justice has wide scope, and whoever finds it hard, finds injustice even harder."</p>

<p>This commitment to returning improperly distributed wealth immediately created powerful enemies — those who had benefited from the previous arrangement were not willing to surrender their gains without resistance. It was this principled stand that led directly to the internal conflicts that plagued his caliphate: the Battle of the Camel, the Battle of Siffin, and the Kharijite uprising.</p>

<h2>Governance by Example</h2>
<p>While governing, Imam Ali lived with extraordinary simplicity. He was known to personally inspect the markets of Kufa, speaking directly with merchants and citizens, ensuring fair weights and honest dealing. He wrote detailed instructions to his governors — most famously his letter to Malik al-Ashtar upon appointing him governor of Egypt — that laid out a comprehensive philosophy of just and compassionate governance that is studied by political theorists to this day.</p>',
  ],

  // Wisdom
  [
    'title'    => 'Nahj al-Balagha: The Peak of Human Eloquence',
    'slug'     => 'nahj-al-balagha-peak-human-eloquence',
    'category' => 'wisdom',
    'sticky'   => false,
    'excerpt'  => 'Compiled by Sharif al-Radi in the 10th century, Nahj al-Balagha — the Peak of Eloquence — collects the sermons, letters, and sayings of Imam Ali into one of the greatest works of Arabic literature ever produced.',
    'content'  => '<p>Nahj al-Balagha, which translates as "The Peak of Eloquence" or "The Pinnacle of Rhetoric," is a collection of the sermons, letters, and short sayings of Imam Ali ibn Abi Talib (A.S.). Compiled by the Shia scholar Sharif al-Radi in the 10th century CE, it stands as one of the most celebrated and studied works in the Arabic literary tradition — praised by scholars across religious and linguistic boundaries for the extraordinary quality of its prose.</p>

<p>The collection contains 239 sermons, 79 letters, and 480 short aphorisms. Together, they cover an extraordinary range of subjects: theology and metaphysics, politics and governance, ethics and spirituality, the nature of the human soul, the transience of the world, the importance of knowledge, and the duties owed to God, to society, and to oneself.</p>

<h2>Literary Excellence</h2>
<p>Muslim scholars have long regarded Nahj al-Balagha as second only to the Quran in the richness and power of its Arabic. Non-Muslim scholars who have studied the text in the original language have similarly been struck by its literary quality. The Irish orientalist Charles James Lyall described passages from Imam Ali\'s speeches as among the finest examples of Arabic rhetoric ever recorded.</p>

<p>The Sermon of Shaqshaqiyya, the Sermon of Piety (Sermon 193, also known as the Sermon of Those Who Fear God), and the First Sermon — in which Imam Ali describes the creation of the universe, the nature of God, and the purpose of divine guidance — are among the most studied and translated passages in Islamic literature.</p>

<h2>Governance and Social Justice</h2>
<p>Nahj al-Balagha contains what many consider the most sophisticated articulation of justice-based governance in pre-modern Islamic thought. His letter to Malik al-Ashtar (Letter 53), written upon appointing him governor of Egypt, is a masterwork of political philosophy that addresses the duties of rulers, the rights of citizens, the treatment of minorities, the management of the treasury, and the spiritual obligations of those in power. It has been cited in discussions of human rights, political theory, and Islamic jurisprudence.</p>

<p>For those who wish to encounter the mind and spirit of Imam Ali directly, Nahj al-Balagha remains the indispensable starting point — a text that rewards reading and re-reading across a lifetime.</p>',
  ],
  [
    'title'    => 'The Sayings of Imam Ali: Wisdom That Crosses Centuries',
    'slug'     => 'sayings-imam-ali-wisdom-crosses-centuries',
    'category' => 'wisdom',
    'sticky'   => false,
    'excerpt'  => 'The short aphorisms of Imam Ali — preserved across centuries of Islamic scholarship — offer insight into the nature of the soul, the meaning of life, and the path to God.',
    'content'  => '<p>Among the many treasures of Imam Ali\'s legacy, his short sayings — aphorisms that distill complex truths into a few words — hold a special place. These sayings have been memorized, quoted, calligraphed, and pondered across the Islamic world for over fourteen centuries. Their power lies not only in their wisdom but in their compression: each saying opens up into a world of contemplation.</p>

<h2>On Knowledge</h2>
<p>"Knowledge is power, and it can command obedience." Imam Ali\'s elevation of knowledge as a divine gift and a moral responsibility runs through almost everything he taught. He said: "Learn knowledge and it will be a lantern for you; learn it and it will benefit you; let it not go to sleep when you are awake." And most strikingly: "The value of every person is what he knows."</p>

<p>This profound respect for knowledge was not abstract. Imam Ali identified himself to the community as the gateway to the city of knowledge — and his entire life was a demonstration of what knowledge, properly understood and faithfully applied, could look like.</p>

<h2>On Justice and the Poor</h2>
<p>"How can you feel satisfied with your possessions when you know that your neighbor goes to bed hungry?" Imam Ali\'s sayings on social justice are some of his most piercing. He said: "The rights of the poor are a debt upon the rich." And: "Do not be ashamed of giving little, for refusal is smaller still."</p>

<h2>On the Self</h2>
<p>"Your remedy is within you, but you do not sense it. Your sickness is from yourself, but you do not see it. You are the open book by whose letters the hidden is made manifest. You are the substance in whom everything is contained. Therefore you do not need to look outside yourself. What you seek is within you."</p>

<p>This extraordinary passage — at once a spiritual teaching, a call to self-examination, and a description of the divine immanence in the human person — demonstrates why Imam Ali\'s sayings have found admirers not only among Muslims but among students of mysticism and philosophy across the world.</p>',
  ],
  [
    'title'    => 'The Letters of Imam Ali: Wisdom Across the Centuries',
    'slug'     => 'letters-imam-ali-wisdom-across-centuries',
    'category' => 'wisdom',
    'sticky'   => false,
    'excerpt'  => 'The letters of Imam Ali — written to governors, companions, enemies, and his own children — constitute a unique body of correspondence that illuminates his philosophy of governance, justice, and faith.',
    'content'  => '<p>Among the most remarkable documents in Islamic history are the letters of Imam Ali ibn Abi Talib (A.S.), 79 of which are preserved in Nahj al-Balagha. These letters were written during his caliphate (35–40 AH) to a wide range of recipients: governors he appointed across the Muslim world, companions who needed guidance, opponents he challenged to reconsider, and his own sons to whom he wished to bequeath a spiritual inheritance.</p>

<h2>Letter to Malik al-Ashtar</h2>
<p>Perhaps the most celebrated of all Imam Ali\'s letters is the one he wrote upon appointing Malik ibn al-Ashtar as governor of Egypt (Letter 53). This letter is a comprehensive treatise on governance — covering the appointment and oversight of officials, the obligations of rulers to their subjects, the management of different social classes, the treatment of non-Muslims under Islamic governance, the dangers of flattery and yes-men, and the spiritual responsibilities of those who hold power.</p>

<p>It contains the famous passage: "Develop in your heart the feeling of love for your people and let it be the source of kindliness and blessing to them. Do not behave with them like a barbarian, and do not appropriate to yourself that which belongs to them. Remember that the citizens of the state are of two categories. They are either your brethren in religion or your brethren in kind."</p>

<h2>Letter to His Son Hassan</h2>
<p>Among the most personally moving of his letters is the one Imam Ali wrote to his son Imam Hassan upon returning from the Battle of Siffin (Letter 31). It is a meditation on mortality, wisdom, and the purpose of a well-lived life. He begins: "From a father who recognizes the feebleness of time, who has turned his back on the world, who is humble before the vicissitudes of time... to a son who desires that which is perishable and lives in the path of those who are afflicted with pain."</p>

<p>The letter continues with guidance on piety, the cultivation of the soul, the importance of reflection, and the ultimate accountability before God. It is one of the most personal windows into Imam Ali\'s inner life — a father speaking to his son with the urgency of one who knows his time is limited and his love is boundless.</p>',
  ],

  // Shrine & Ziyarat
  [
    'title'    => 'The Sacred Shrine of Imam Ali in Najaf',
    'slug'     => 'sacred-shrine-imam-ali-najaf',
    'category' => 'shrine-and-ziyarat',
    'sticky'   => false,
    'excerpt'  => 'The shrine of Imam Ali in Najaf, Iraq, is among the most magnificent and spiritually significant sites in the Islamic world — visited by millions of pilgrims each year from every country on earth.',
    'content'  => '<p>In the city of Najaf, in south-central Iraq, stands one of the most magnificent structures in the Islamic world: the shrine of Imam Ali ibn Abi Talib (A.S.), the Commander of the Faithful and the first Imam. With its golden dome visible for miles across the flat Iraqi landscape, and its outer walls covered in tiles of turquoise and cobalt blue, the shrine is both a masterwork of Islamic architecture and the spiritual center of the Shia Muslim world.</p>

<p>The location of Imam Ali\'s burial was initially kept secret on his instruction — he feared that his enemies might desecrate his grave. The site was revealed later by his son Imam Sadiq (A.S.) during the Abbasid era, after which a succession of rulers and benefactors funded the construction and expansion of the shrine that stands today.</p>

<h2>The Architecture</h2>
<p>The current structure of the shrine represents centuries of renovation and expansion. The golden dome — one of the most recognized images in Islamic architecture — was restored and expanded during the Safavid period and has been periodically refurbished since. The minarets that flank the dome are covered in hand-painted tiles and can be seen from great distances. Inside, the burial chamber (zarih) is surrounded by intricately worked silver and gold, the result of donations from rulers, pilgrims, and devout craftsmen across generations.</p>

<p>The inner courtyard of the shrine is a vast, marble-covered space where pilgrims gather at all hours of the day and night. The sound of prayers, the Quran being recited, and the quiet weeping of those who have come from distant lands fill the air with a unique spiritual atmosphere that visitors consistently describe as unlike anything else they have experienced.</p>

<h2>Millions of Pilgrims</h2>
<p>The shrine of Imam Ali receives millions of visitors annually — a number that swells dramatically during the holy months of Muharram, Ramadan, and on the occasion of Eid al-Ghadir. Pilgrims come from Iran, Iraq, Pakistan, India, Lebanon, Bahrain, Afghanistan, and increasingly from Western countries where Shia Muslim communities have established themselves.</p>

<p>For many pilgrims, the journey to Najaf is the fulfillment of a lifelong aspiration. To stand before the zarih of Imam Ali, to touch the golden gates, to recite the prescribed prayers of Ziyarat — this is considered one of the most spiritually transformative experiences a believer can undertake. Many describe leaving Najaf feeling profoundly changed.</p>',
  ],
  [
    'title'    => 'The Spiritual Journey of Ziyarat to Imam Ali\'s Shrine',
    'slug'     => 'spiritual-journey-ziyarat-imam-ali-shrine',
    'category' => 'shrine-and-ziyarat',
    'sticky'   => false,
    'excerpt'  => 'Ziyarat — the ritual visitation of the shrine of Imam Ali — is a deeply personal and communal spiritual practice that connects the believer to the presence and intercession of the Imam.',
    'content'  => '<p>Ziyarat, from the Arabic root meaning "to visit," refers in Islamic practice to the ritual visitation of the shrines of the Prophet, the Imams, and other holy figures. Among all acts of Ziyarat, visiting the shrine of Imam Ali in Najaf is considered one of the most meritorious — a practice encouraged by hadith attributed to the Prophet Muhammad and each of the Imams who came after Imam Ali.</p>

<p>The ritual of Ziyarat to Imam Ali\'s shrine follows a specific format. The pilgrim typically begins their preparation before arriving in Najaf — performing ritual purification, wearing clean garments, and entering a state of spiritual focus. As they approach the shrine, specific prayers and salutations are recited. At the entrance, the pilgrim seeks permission to enter in a prayerful address to the Imam — a practice that reflects the belief that the Imam is spiritually present and aware of those who visit him.</p>

<h2>The Prescribed Prayers</h2>
<p>Inside the shrine, the pilgrim recites specific texts known as Ziyarat texts — compilations of salutations, acknowledgments of the Imam\'s station, expressions of love and loyalty, and requests for intercession. The most comprehensive of these is the Ziyarat al-Jami\'a al-Kabira, a text transmitted from Imam Ali al-Hadi (A.S.) that describes the station of all the Imams and the obligations of the believer toward them.</p>

<p>After the formal Ziyarat prayers, pilgrims typically perform two rak\'at of prayer as a gift to the Imam, recite Quran, make personal supplications, and spend time in silent contemplation. Many pilgrims report that this period of quiet, in the presence of the shrine, is among the most powerful moments of their spiritual lives.</p>

<h2>The Communal Dimension</h2>
<p>Ziyarat is not only a personal spiritual practice — it is a communal act that connects the individual to the global community of those who love Imam Ali. To stand in the courtyard of the Najaf shrine alongside pilgrims from Pakistan, Iran, Iraq, Nigeria, and the United Kingdom is to experience, viscerally, the universality of the devotion to the Commander of the Faithful. In this sense, Ziyarat is both an act of prayer and an act of belonging — a reaffirmation of one\'s place within a community united by love, faith, and the aspiration to follow the path of the Imam.</p>',
  ],
];

/* ── Create posts ────────────────────────────────────────────── */
$img_index = 0;
foreach ($articles as $art) {
    // Skip if slug already exists
    if (get_page_by_path($art['slug'], OBJECT, 'post')) {
        $results[] = "Skipped (exists): {$art['title']}";
        $img_index++;
        continue;
    }

    $cat_id = $cat_ids[$art['category']] ?? 0;

    $post_id = wp_insert_post([
        'post_title'   => $art['title'],
        'post_name'    => $art['slug'],
        'post_content' => $art['content'],
        'post_excerpt' => $art['excerpt'],
        'post_status'  => 'publish',
        'post_category'=> $cat_id ? [$cat_id] : [],
        'post_author'  => 1,
    ]);

    if (is_wp_error($post_id)) {
        $results[] = "Error: {$art['title']} — " . $post_id->get_error_message();
        continue;
    }

    // Sticky post
    if (!empty($art['sticky'])) {
        stick_post($post_id);
    }

    // Featured image from Picsum
    ia_sideload_image($image_seeds[$img_index % count($image_seeds)], $post_id);

    $results[] = "Created: {$art['title']}";
    $img_index++;
}

// Mark as imported
update_option('imam_ali_content_imported', true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Imam Ali Theme — Content Importer</title>
<style>
  body { font-family: sans-serif; max-width: 700px; margin: 40px auto; padding: 20px; background: #f5f0e8; color: #2a1b0f; }
  h1 { font-size: 28px; margin-bottom: 24px; }
  .item { padding: 8px 12px; margin-bottom: 6px; border-radius: 6px; font-size: 14px; }
  .created  { background: #d1fae5; color: #065f46; }
  .skipped  { background: #fef3c7; color: #92400e; }
  .error    { background: #fee2e2; color: #991b1b; }
  .success  { margin-top: 24px; padding: 16px; background: #068f5f; color: white; border-radius: 8px; font-weight: bold; }
  a { color: #068f5f; }
</style>
</head>
<body>
<h1>Content Import Complete</h1>
<?php foreach ($results as $r) :
  $cls = str_starts_with($r, 'Created') ? 'created' : (str_starts_with($r, 'Skipped') ? 'skipped' : 'error');
?>
  <div class="item <?php echo $cls; ?>"><?php echo esc_html($r); ?></div>
<?php endforeach; ?>
<div class="success">
  ✓ Import complete! <a href="<?php echo esc_url(home_url('/')); ?>" style="color:white;text-decoration:underline">Visit your homepage →</a>
</div>
<p style="margin-top:16px;font-size:13px;color:#6b5a47;">
  <strong>Important:</strong> Delete or rename this file now to prevent unauthorized re-use.
</p>
</body>
</html>
