<?php
/**
 * Virunga Collective Internationalization & SEO Metadata System
 * Supports 27 Global Languages:
 * EN, FR, ES, PT, ZH, JA, IT, NL, SV, NO, DA, AR, KO, HI, RU, PL, TR, HE (IW), CS, FI, RO, ID, MS, SW, TH, VI, UK
 */

function get_current_language() {
    $lang = 'en';
    if (isset($_GET['lang']) && !empty($_GET['lang'])) {
        $lang = strtolower(trim($_GET['lang']));
    } elseif (isset($_COOKIE['googtrans']) && !empty($_COOKIE['googtrans'])) {
        $parts = explode('/', trim($_COOKIE['googtrans'], '/'));
        if (count($parts) >= 2) {
            $lang = strtolower($parts[1]);
        }
    }
    
    // Normalize language codes for Google Translate
    if ($lang === 'zh-cn' || $lang === 'zh-tw') $lang = 'zh';
    if ($lang === 'ko-kr') $lang = 'ko';
    if ($lang === 'he') $lang = 'iw';
    
    $supported = [
        'en', 'fr', 'es', 'pt', 'zh', 'ja', 'it', 'nl', 'sv', 'no', 'da', 
        'ar', 'ko', 'hi', 'ru', 'pl', 'tr', 'iw', 'cs', 'fi', 'ro', 'id', 
        'ms', 'sw', 'th', 'vi', 'uk'
    ];
    return in_array($lang, $supported) ? $lang : 'en';
}

function get_localization_data($lang = null) {
    if (!$lang) {
        $lang = get_current_language();
    }

    $data = [
        'en' => [
            'name' => 'English', 'native' => 'English', 'flag' => '🇬🇧', 'code' => 'en', 'currency_code' => 'USD', 'currency_symbol' => '$',
            'seo_title' => 'Luxury Rwanda Safaris & Bespoke Virunga Journeys | Virunga Collective',
            'seo_description' => 'Virunga Collective is Rwanda’s premier destination ecosystem—connecting luxury homestays, bespoke gorilla trekking safaris, volcanic coffee, and community impact.',
            'seo_keywords' => 'luxury Rwanda safari, luxury gorilla trekking Rwanda, private Rwanda safari, bespoke Virunga journeys, Virunga Collective, Musanze luxury homestay',
            'travel_info' => 'Visa on arrival for most passports. Yellow fever vaccination required if traveling from endemic areas. Gorilla permits booked 3+ months in advance.',
            'cultural_notes' => 'Warm Rwandan hospitality ("Karibu"). Respect local customs, dress modestly in villages, and ask before photographing community members.'
        ],
        'fr' => [
            'name' => 'French', 'native' => 'Français', 'flag' => '🇫🇷', 'code' => 'fr', 'currency_code' => 'EUR', 'currency_symbol' => '€',
            'seo_title' => 'Virunga Collective | Séjours Éco-Responsables et Trek des Gorilles au Rwanda',
            'seo_description' => 'Explorez le Virunga Collective : hébergements authentiques à Musanze, écotours d’exception dans le Parc National des Volcans et soutien aux communautés locales.',
            'seo_keywords' => 'Virunga Collective, tourisme Rwanda, trek gorilles Musanze, logement chez l habitant Rwanda, voyage éco-responsable',
            'travel_info' => 'Visa à l’arrivée disponible. Vaccin contre la fièvre jaune recommandé. Réservation des permis gorilles conseillée plusieurs mois à l’avance.',
            'cultural_notes' => 'Hospitalité rwandaise chaleureuse. Respect des traditions locales et politesse lors des visites de villages.'
        ],
        'es' => [
            'name' => 'Spanish', 'native' => 'Español', 'flag' => '🇪🇸', 'code' => 'es', 'currency_code' => 'EUR', 'currency_symbol' => '€',
            'seo_title' => 'Virunga Collective | Turismo Regenerativo y Trekking de Gorilas en Ruanda',
            'seo_description' => 'Descubre el Virunga Collective: alojamientos con encanto en Musanze, expediciones de gorilas en el Parque Nacional de los Volcanes e impacto comunitario.',
            'seo_keywords' => 'Virunga Collective, turismo Ruanda, trekking gorilas Musanze, alojamiento local Ruanda, viajes sostenibles',
            'travel_info' => 'Visado a la llegada disponible. Vacuna contra la fiebre amarilla recomendada. Permisos para ver gorilas gestionados con antelación.',
            'cultural_notes' => 'Calurosa bienvenida ruandesa. Respeto a las costumbres comunitarias y vestimenta adecuada en áreas rurales.'
        ],
        'pt' => [
            'name' => 'Portuguese', 'native' => 'Português', 'flag' => '🇵🇹', 'code' => 'pt', 'currency_code' => 'EUR', 'currency_symbol' => '€',
            'seo_title' => 'Virunga Collective | Turismo Sustentável e Trekking de Gorilas no Ruanda',
            'seo_description' => 'Descubra o Virunga Collective: alojamento autêntico em Musanze, safaris de gorilas no Parque Nacional dos Vulcões e impacto comunitário.',
            'seo_keywords' => 'Virunga Collective, turismo Ruanda, trekking gorilas Musanze, alojamento local Ruanda, viagens ecológicas',
            'travel_info' => 'Visto à chegada disponível. Vacina contra a febre amarela recomendada. Reserva antecipada de licenças para gorilas.',
            'cultural_notes' => 'Hospitalidade calorosa ruandesa. Respeito pelos costumes locais e fotos em comunidades apenas com permissão.'
        ],
        'zh' => [
            'name' => 'Chinese', 'native' => '中文', 'flag' => '🇨🇳', 'code' => 'zh', 'currency_code' => 'CNY', 'currency_symbol' => '¥',
            'seo_title' => '维伦加集合体 (Virunga Collective) | 卢旺达顶级生态保护与大猩猩追踪旅行',
            'seo_description' => '探索维伦加集合体：提供穆桑泽特色精品民宿、火山国家公园大猩猩追踪生态游及社区可持续发展项目。',
            'seo_keywords' => '维伦加集合体, 卢旺达旅游, 穆桑泽大猩猩追踪, 卢旺达精品民宿, 东非可持续生态游',
            'travel_info' => '支持落地签。建议提前申请大猩猩追踪许可证。从黄热病疫区入境须出示疫苗接种证书。',
            'cultural_notes' => '感受卢旺达热情民风（"Karibu"）。尊重当地村落传统，拍摄居民前请先征得同意。'
        ],
        'ja' => [
            'name' => 'Japanese', 'native' => '日本語', 'flag' => '🇯🇵', 'code' => 'ja', 'currency_code' => 'JPY', 'currency_symbol' => '¥',
            'seo_title' => 'Virunga Collective | ルワンダ・ゴリラトレッキング＆持続可能なエコツアー',
            'seo_description' => 'ヴィルンガ・コレクティブへようこそ。ムサンゼの上質なホームステイ、火山国立公園のゴリラトレッキング、地域支援体験をご提供。',
            'seo_keywords' => 'ヴィルンガコレクティブ, ルワンダ旅行, ムサンゼゴリラトレッキング, ルワンダホームステイ, 東非エコツアー',
            'travel_info' => 'キガリ空港にてアライバルビザ取得可能。ゴリラ許可証は数ヶ月前の事前予約が必須です。',
            'cultural_notes' => '温かいルワンダのおもてなし。地域の文化や生活習慣を尊重し、穏やかな交流をお楽しみください。'
        ],
        'it' => [
            'name' => 'Italian', 'native' => 'Italian', 'flag' => '🇮🇹', 'code' => 'it', 'currency_code' => 'EUR', 'currency_symbol' => '€',
            'seo_title' => 'Virunga Collective | Viaggi Sostenibili e Trekking dei Gorilla in Ruanda',
            'seo_description' => 'Vivi il Virunga Collective: sistemazioni autentiche a Musanze, tour dei gorilla nel Parco Nazionale dei Vulcani e progetti di sviluppo locale.',
            'seo_keywords' => 'Virunga Collective, viaggi Ruanda, trekking gorilla Musanze, alloggio sostenibile Ruanda',
            'travel_info' => 'Visto all’arrivo disponibile all’aeroporto di Kigali. Certificato di febbre gialla raccomandato. Prenotazione permessi gorilla in anticipo.',
            'cultural_notes' => 'Accoglienza ruandese autentica. Si consiglia rispetto per la cultura locale e le tradizioni dei villaggi.'
        ],
        'nl' => [
            'name' => 'Dutch', 'native' => 'Nederlands', 'flag' => '🇳🇱', 'code' => 'nl', 'currency_code' => 'EUR', 'currency_symbol' => '€',
            'seo_title' => 'Virunga Collective | Duurzaam Reizen & Gorilla Trekking in Rwanda',
            'seo_description' => 'Ontdek Virunga Collective: unieke accommodaties in Musanze, gorilla safari’s in het Volcanoes National Park en lokale gemeenschapsimpact.',
            'seo_keywords' => 'Virunga Collective, Rwanda reizen, gorilla trekking Musanze, ecotoerisme Rwanda',
            'travel_info' => 'Visum bij aankomst verkrijgbaar. Gele koorts vaccinatie aanbevolen. Boek gorilla vergunningen minimaal 3 maanden vooraf.',
            'cultural_notes' => 'Warme Rwandese gastvrijheid. Respecteer lokale normen en vraag toestemming voor foto’s in dorpen.'
        ],
        'sv' => [
            'name' => 'Swedish', 'native' => 'Svenska', 'flag' => '🇸🇪', 'code' => 'sv', 'currency_code' => 'SEK', 'currency_symbol' => 'kr',
            'seo_title' => 'Virunga Collective | Hållbar Turism & Gorillatrekking i Rwanda',
            'seo_description' => 'Upplev Virunga Collective: genuina boenden i Musanze, exklusiva gorillasafaris i Vulkan nationalpark och lokalt samhällsstöd.',
            'seo_keywords' => 'Virunga Collective, Rwanda resor, gorillatrekking Musanze, ekoturism Rwanda',
            'travel_info' => 'Visum vid ankomst tillgängligt. Gula febern-vaccination rekommenderas. Boka gorillatillstånd i god tid.',
            'cultural_notes' => 'Varm rwandisk gästfrihet. Visa respekt för lokala traditioner och be om lov innan du fotograferar.'
        ],
        'no' => [
            'name' => 'Norwegian', 'native' => 'Norsk', 'flag' => '🇳🇴', 'code' => 'no', 'currency_code' => 'NOK', 'currency_symbol' => 'kr',
            'seo_title' => 'Virunga Collective | Bærekraftig Reiseliv og Gorillatrekking i Rwanda',
            'seo_description' => 'Oppdag Virunga Collective: autentiske overnattingssteder i Musanze, gorillatrenking i Vulkan nasjonalpark og lokal samfunnsstøtte.',
            'seo_keywords' => 'Virunga Collective, Rwanda reiser, gorillatrekking Musanze, økoturisme Rwanda',
            'travel_info' => 'Visum ved ankomst er tilgjengelig. Gulfeber-vaksine anbefales. Forhåndsbooking av gorillatillatelser anbefales.',
            'cultural_notes' => 'Varm rwandisk gjestfrihet. Respekter lokale skikker og spør før du tar bilder av lokalbefolkningen.'
        ],
        'da' => [
            'name' => 'Danish', 'native' => 'Dansk', 'flag' => '🇩🇰', 'code' => 'da', 'currency_code' => 'DKK', 'currency_symbol' => 'kr.',
            'seo_title' => 'Virunga Collective | Bæredygtig Turisme & Gorillatrekking i Rwanda',
            'seo_description' => 'Oplev Virunga Collective: autentiske boformer i Musanze, eksklusive gorillatrek i Vulkan Nationalpark og lokal samfundsstøtte.',
            'seo_keywords' => 'Virunga Collective, Rwanda rejser, gorillatrekking Musanze, økoturisme Rwanda',
            'travel_info' => 'Visum ved ankomst tilgængeligt. Gul feber-vaccination anbefales. Bestil gorillatilladelser i god tid.',
            'cultural_notes' => 'Varm rwandisk gæstfrihed. Respekter lokale traditioner og spørg før fotografering i landsbyer.'
        ],
        'ar' => [
            'name' => 'Arabic', 'native' => 'العربية', 'flag' => '🇸🇦', 'code' => 'ar', 'currency_code' => 'USD', 'currency_symbol' => '$',
            'seo_title' => 'مجموعة فيرونغا (Virunga Collective) | السياحة البيئية الفاخرة وتتبع الغوريلا في رواندا',
            'seo_description' => 'استكشف مجموعة فيرونغا: إقامات فاخرة وأصيلة في موسانزي، جولات تتبع الغوريلا في الحديقة الوطنية للبراكين وتنمية المجتمع.',
            'seo_keywords' => 'مجموعة فيرونغا, سياحة رواندا, تتبع الغوريلا موسانزي, سياحة بيئية أفريقيا',
            'travel_info' => 'تأشيرة الدخول متاحة عند الوصول. يُوصى بالتطعيم ضد الحمى الصفراء وتأمين تصاريح الغوريلا مسبقاً.',
            'cultural_notes' => 'كرم الضيافة الرواندية الأصيلة. يُرجى احترام العادات المحلية والاستئذان قبل تصوير السكان.'
        ],
        'ko' => [
            'name' => 'Korean', 'native' => '한국어', 'flag' => '🇰🇷', 'code' => 'ko', 'currency_code' => 'KRW', 'currency_symbol' => '₩',
            'seo_title' => '비룽가 컬렉티브 (Virunga Collective) | 르완다 프리미엄 고릴라 트레킹 & 친환경 여행',
            'seo_description' => '비룽가 컬렉티브와 함께하는 르완다 최고의 여행: 무산제 고급 홈스테이, 화산 국립공원 고릴라 트레킹 및 지역사회 상생 에코투어.',
            'seo_keywords' => '비룽가 컬렉티브, 르완다 여행, 무산제 고릴라 트레킹, 르완다 홈스테이, 동아프리카 에코투어',
            'travel_info' => '르완다 도착 비자 발급 가능. 고릴라 트레킹 퍼밋은 최소 3개월 전 사전 예약 권장.',
            'cultural_notes' => '르완다 특유의 따뜻한 환대 문화("Karibu"). 현지 마을 방문 시 사진 촬영 전 양해를 구해주세요.'
        ],
        'hi' => [
            'name' => 'Hindi', 'native' => 'हिन्दी', 'flag' => '🇮🇳', 'code' => 'hi', 'currency_code' => 'INR', 'currency_symbol' => '₹',
            'seo_title' => 'विंरुगा कलेक्टिव (Virunga Collective) | रवांडा में गोरिल्ला ट्रैकिंग और इको टूरिज्म',
            'seo_description' => 'विंरुगा कलेक्टिव की खोज करें: मुसांज़े में शानदार होमस्टे, ज्वालामुखी राष्ट्रीय उद्यान में गोरिल्ला ट्रैकिंग और सामुदायिक विकास।',
            'seo_keywords' => 'विंरुगा कलेक्टिव, रवांडा पर्यटन, गोरिल्ला ट्रैकिंग मुसांज़े, इको टूरिज्म अफ्रीका',
            'travel_info' => 'आगमन पर वीज़ा (Visa on Arrival) उपलब्ध। गोरिल्ला परमिट अग्रिम में बुक करने की सलाह दी जाती है।',
            'cultural_notes' => 'गर्मजोशी से भरा रवांडाई आतिथ्य। स्थानीय रीति-रिवाजों का सम्मान करें।'
        ],
        'ru' => [
            'name' => 'Russian', 'native' => 'Русский', 'flag' => '🇷🇺', 'code' => 'ru', 'currency_code' => 'USD', 'currency_symbol' => '$',
            'seo_title' => 'Virunga Collective | Экотуризм и Треккинг к Гориллам в Руанде',
            'seo_description' => 'Откройте для себя Virunga Collective: аутентичное проживание в Мусанзе, сафари к горным гориллам в национальном парке вулканов.',
            'seo_keywords' => 'Virunga Collective, туризм Руанда, треккинг к гориллам Мусанзе, экотуризм Африка',
            'travel_info' => 'Виза по прибытии доступна. Рекомендуется вакцинация против желтой лихорадки и раннее бронирование пермитов.',
            'cultural_notes' => 'Теплое руандийское гостеприимство. С уважением относитесь к местным традициям.'
        ],
        'pl' => [
            'name' => 'Polish', 'native' => 'Polski', 'flag' => '🇵🇱', 'code' => 'pl', 'currency_code' => 'PLN', 'currency_symbol' => 'zł',
            'seo_title' => 'Virunga Collective | Zrównoważona Turystyka i Trekking z Gorylami w Rwandzie',
            'seo_description' => 'Odkryj Virunga Collective: autentyczne noclegi w Musanze, wyprawy do goryli w Parku Narodowym Wulkanów i wsparcie społeczności.',
            'seo_keywords' => 'Virunga Collective, Rwanda turystyka, trekking z gorylami Musanze, ekoturystyka',
            'travel_info' => 'Wiza dostępna po przyjeździe. Zalecane szczepienie na żółtą febrę oraz wcześniejsza rezerwacja pozwoleń na goryle.',
            'cultural_notes' => 'Serdeczna rwandzka gościnność. Szanuj lokalne zwyczaje i pytaj o zgodę przed robieniem zdjęć.'
        ],
        'tr' => [
            'name' => 'Turkish', 'native' => 'Türkçe', 'flag' => '🇹🇷', 'code' => 'tr', 'currency_code' => 'TRY', 'currency_symbol' => '₺',
            'seo_title' => 'Virunga Collective | Ruanda’da Sürdürülebilir Turizm ve Goril Trekkingi',
            'seo_description' => 'Virunga Collective’i keşfedin: Musanze’de otantik konaklama, Volkanlar Milli Parkı’nda goril safari ve yerel topluluk desteği.',
            'seo_keywords' => 'Virunga Collective, Ruanda turizm, goril trekking Musanze, ekoturizm Ruanda',
            'travel_info' => 'Varışta vize alınabilir. Sarıhumma aşısı tavsiye edilir. Goril izinlerinin önceden alınması önemlidir.',
            'cultural_notes' => 'Sıcak Ruanda misafirperverliği. Yerel geleneklere saygı gösterin.'
        ],
        'iw' => [
            'name' => 'Hebrew', 'native' => 'עברית', 'flag' => '🇮🇱', 'code' => 'iw', 'currency_code' => 'ILS', 'currency_symbol' => '₪',
            'seo_title' => 'Virunga Collective | תיירות אקולוגית וטרק גורילות ברואנדה',
            'seo_description' => 'גלו את Virunga Collective: אירוח אותנטי במוסאנזה, סיורי גורילות בפארק הלאומי של הוולקנים ותמיכה בקהילה המקומית.',
            'seo_keywords' => 'Virunga Collective, תיירות רואנדה, טרק גורילות מוסאנזה, אקוטוריזם',
            'travel_info' => 'ויזה בהגעה זמינה. מומלץ להתחסן נגד קדחת צהובה ולהזמין אישורי גורילות מראש.',
            'cultural_notes' => 'הכנסת אורחים רואנדית חמה. נא לכבד את המנהגים המקומיים.'
        ],
        'cs' => [
            'name' => 'Czech', 'native' => 'Čeština', 'flag' => '🇨🇿', 'code' => 'cs', 'currency_code' => 'CZK', 'currency_symbol' => 'Kč',
            'seo_title' => 'Virunga Collective | Udržitelný Turismus a Trekking za Gorilami v Rwandě',
            'seo_description' => 'Objevte Virunga Collective: autentické ubytování v Musanze, výpravy za horskými gorilami v Národním parku Sopky a podpora komunit.',
            'seo_keywords' => 'Virunga Collective, Rwanda turistika, trekking gorily Musanze, ekoturistika',
            'travel_info' => 'Vízum po příjezdu k dispozici. Doporučeno očkování proti žluté zimnici a včasná rezervace povolení.',
            'cultural_notes' => 'Vřelá rwandská pohostinnost. Respektujte místní zvyky a před fotografováním požádejte o svolení.'
        ],
        'fi' => [
            'name' => 'Finnish', 'native' => 'Suomi', 'flag' => '🇫🇮', 'code' => 'fi', 'currency_code' => 'EUR', 'currency_symbol' => '€',
            'seo_title' => 'Virunga Collective | Kestävää Matkailua & Gorillavaelluksia Ruandassa',
            'seo_description' => 'Koe Virunga Collective: aitoa majoitusta Musanzessa, gorillavaelluksia Tulivuorten kansallispuistossa ja paikallisyhteisön tukea.',
            'seo_keywords' => 'Virunga Collective, Ruanda matkailu, gorillavaellus Musanze, ekoturismi',
            'travel_info' => 'Viisumi saatavilla saavuttaessa. Keltakuumerokotusta suositellaan. Varaa gorillaluvat hyvissä ajoin.',
            'cultural_notes' => 'Lämmin ruandalainen vieraanvaraisuus. Kunnioita paikallisia tapoja.'
        ],
        'ro' => [
            'name' => 'Romanian', 'native' => 'Română', 'flag' => '🇷🇴', 'code' => 'ro', 'currency_code' => 'RON', 'currency_symbol' => 'lei',
            'seo_title' => 'Virunga Collective | Turism Sustenabil și Trekking cu Gorile în Rwanda',
            'seo_description' => 'Descoperă Virunga Collective: cazare autentică în Musanze, tururi cu gorile în Parcul Național Vulcani și impact comunitar.',
            'seo_keywords' => 'Virunga Collective, turism Rwanda, trekking gorile Musanze, ecoturism',
            'travel_info' => 'Viză la sosire disponibilă. Se recomandă vaccinul împotriva febrei galbene și rezervarea permiselor în avans.',
            'cultural_notes' => 'Ospitalitate caldă rwandeză. Respectați obiceiurile locale.'
        ],
        'id' => [
            'name' => 'Indonesian', 'native' => 'Bahasa Indonesia', 'flag' => '🇮🇩', 'code' => 'id', 'currency_code' => 'IDR', 'currency_symbol' => 'Rp',
            'seo_title' => 'Virunga Collective | Wisata Berkelanjutan & Trekking Gorila di Rwanda',
            'seo_description' => 'Jelajahi Virunga Collective: penginapan autentik di Musanze, safari gorila di Taman Nasional Gunung Berapi, dan pemberdayaan lokal.',
            'seo_keywords' => 'Virunga Collective, wisata Rwanda, trekking gorila Musanze, ekowisata Afrika',
            'travel_info' => 'Visa on Arrival tersedia. Vaksinasi demam kuning disarankan. Pesan izin gorila jauh-jauh hari.',
            'cultural_notes' => 'Keramahan khas Rwanda. Hormati adat lokal dan minta izin sebelum mengambil foto.'
        ],
        'ms' => [
            'name' => 'Malay', 'native' => 'Bahasa Melayu', 'flag' => '🇲🇾', 'code' => 'ms', 'currency_code' => 'MYR', 'currency_symbol' => 'RM',
            'seo_title' => 'Virunga Collective | Pelancongan Lestari & Penjelajahan Gorila di Rwanda',
            'seo_description' => 'Terokai Virunga Collective: penginapan tempatan di Musanze, ekspedisi gorila di Taman Negara Gunung Berapi dan sokongan komuniti.',
            'seo_keywords' => 'Virunga Collective, pelancongan Rwanda, trekking gorila Musanze, ekopelancongan',
            'travel_info' => 'Visa semasa ketibaan disediakan. Suntikan demam kuning disyorkan. Tempah permit gorila lebih awal.',
            'cultural_notes' => 'Kemesraan rakyat Rwanda. Hormati adat tempatan.'
        ],
        'sw' => [
            'name' => 'Swahili', 'native' => 'Kiswahili', 'flag' => '🇰🇪', 'code' => 'sw', 'currency_code' => 'USD', 'currency_symbol' => '$',
            'seo_title' => 'Virunga Collective | Utalii wa Kijani na Safari za Medani za Sokwe Rwanda',
            'seo_description' => 'Gundua Virunga Collective: makazi bora Musanze, safari za sokwe katika Hifadhi ya Taifa ya Milima ya Volkano na maendeleo ya jamii.',
            'seo_keywords' => 'Virunga Collective, utalii Rwanda, sokwe Musanze, utalii wa mazingira',
            'travel_info' => 'Visa inapatikana unapowasili. Hati ya chanjo ya homa ya manjano inahitajika.',
            'cultural_notes me' => 'Ukarimu mkarimu wa Rwanda ("Karibu"). Heshimu mila za mitaa.'
        ],
        'th' => [
            'name' => 'Thai', 'native' => 'ไทย', 'flag' => '🇹🇭', 'code' => 'th', 'currency_code' => 'THB', 'currency_symbol' => '฿',
            'seo_title' => 'Virunga Collective | การท่องเที่ยวเชิงอนุรักษ์และการเดินป่าชมกอริลลาในรวันดา',
            'seo_description' => 'ค้นพบ Virunga Collective: ที่พักโฮมสเตย์สุดพิเศษในมูซานเซ ทัวร์ชมกอริลลาในอุทยานแห่งชาติภูเขาไฟและการพัฒนาชุมชน',
            'seo_keywords' => 'Virunga Collective, ท่องเที่ยวรวันดา, เดินป่าชมกอริลลา มูซานเซ, ท่องเที่ยวเชิงอนุรักษ์',
            'travel_info' => 'มีบริการ Visa on Arrival แนะนำให้ฉีดวัคซีนไข้เหลืองและจองใบอนุญาตชมกอริลลาล่วงหน้า',
            'cultural_notes' => 'ต้อนรับอย่างอบอุ่นสไตล์รวันดา ให้เกียรติวัฒนธรรมท้องถิ่น'
        ],
        'vi' => [
            'name' => 'Vietnamese', 'native' => 'Tiếng Việt', 'flag' => '🇻🇳', 'code' => 'vi', 'currency_code' => 'VND', 'currency_symbol' => '₫',
            'seo_title' => 'Virunga Collective | Du Lịch Bền Vững & Trekking Khỉ Đột Tại Rwanda',
            'seo_description' => 'Khám phá Virunga Collective: lưu trú homestay độc đáo tại Musanze, tour theo dấu khỉ đột tại Vườn Quốc Gia Núi Lửa và hỗ trợ cộng đồng.',
            'seo_keywords' => 'Virunga Collective, du lịch Rwanda, trekking khỉ đột Musanze, du lịch sinh thái',
            'travel_info' => 'Hỗ trợ Visa tại cửa khẩu. Khuyến nghị tiêm phòng sốt vàng và đặt giấy phép khỉ đột trước.',
            'cultural_notes' => 'Sự hiếu khách nồng hậu của người Rwanda. Tôn trọng phong tục địa phương.'
        ],
        'uk' => [
            'name' => 'Ukrainian', 'native' => 'Українська', 'flag' => '🇺🇦', 'code' => 'uk', 'currency_code' => 'UAH', 'currency_symbol' => '₴',
            'seo_title' => 'Virunga Collective | Екотуризм та Трекінг до Горил у Руанді',
            'seo_description' => 'Відкрийте для себе Virunga Collective: автентичне проживання в Мусанзе, трекінг до гірських горил у Національному парку Вулканів.',
            'seo_keywords' => 'Virunga Collective, туризм Руанда, трекінг до горил Мусанзе, екотуризм Африка',
            'travel_info' => 'Віза після прибуття доступна. Рекомендується вакцинація від жовтої лихоманки та завчасне бронювання дозволів.',
            'cultural_notes' => 'Тепле руандійське гостинність. З повагою ставтеся до місцевих традицій.'
        ]
    ];

    return $data[$lang] ?? $data['en'];
}

function get_email_receipt_template($name, $formSubject = '', $lang = null) {
    if (!$lang) {
        $lang = get_current_language();
    }
    
    $templates = [
        'en' => [
            'subject' => "Thank you for reaching out to Virunga Collective",
            'header' => "Message Received",
            'greeting' => "Dear " . htmlspecialchars($name) . ",",
            'body' => "Thank you for contacting Virunga Collective. We have received your submission and our concierge team is reviewing your message. We will get back to you within 24 hours.",
            'quote' => '"Connecting guests, conservation, and community in the heart of the Virunga Massif."',
            'contact_btn' => "Explore Virunga Collective",
        ],
        'fr' => [
            'subject' => "Merci d'avoir contacté Virunga Collective",
            'header' => "Message Bien Reçu",
            'greeting' => "Cher(e) " . htmlspecialchars($name) . ",",
            'body' => "Merci d'avoir contacté Virunga Collective. Nous avons bien reçu votre demande et notre équipe vous répondra dans les 24 heures.",
            'quote' => '"Connecter les voyageurs, la conservation et les communautés au cœur des Virunga."',
            'contact_btn' => "Découvrir Virunga Collective",
        ],
        'es' => [
            'subject' => "Gracias por contactar a Virunga Collective",
            'header' => "Mensaje Recibido",
            'greeting' => "Estimado/a " . htmlspecialchars($name) . ",",
            'body' => "Gracias por comunicarte con Virunga Collective. Hemos recibido tu consulta y nuestro equipo se pondrá en contacto contigo en un plazo de 24 horas.",
            'quote' => '"Uniendo viajeros, conservación y comunidad en el corazón de las Virunga."',
            'contact_btn' => "Explorar Virunga Collective",
        ],
        'de' => [
            'subject' => "Vielen Dank für Ihre Anfrage — Virunga Collective",
            'header' => "Nachricht Eingegangen",
            'greeting' => "Sehr geehrte(r) " . htmlspecialchars($name) . ",",
            'body' => "Vielen Dank für Ihre Nachricht an das Virunga Collective. Wir haben Ihre Anfrage erhalten und unser Team wird sich innerhalb von 24 Stunden bei Ihnen melden.",
            'quote' => '"Gemeinschaft, Naturschutz und unvergessliche Reisen im Herzen der Virunga-Berge."',
            'contact_btn' => "Virunga Collective Entdecken",
        ],
        'pt' => [
            'subject' => "Obrigado pelo seu contacto com o Virunga Collective",
            'header' => "Mensagem Recebida",
            'greeting' => "Caro(a) " . htmlspecialchars($name) . ",",
            'body' => "Obrigado por contactar o Virunga Collective. Recebemos a sua mensagem e a nossa equipa responderá no prazo de 24 horas.",
            'quote' => '"Unindo conservação, comunidade e viagens inesquecíveis no coração de Virunga."',
            'contact_btn' => "Explorar Virunga Collective",
        ],
        'zh' => [
            'subject' => "感谢您联系维伦加集合体 (Virunga Collective)",
            'header' => "已收到您的留言",
            'greeting' => "尊敬的 " . htmlspecialchars($name) . "：",
            'body' => "感谢您联系维伦加集合体 (Virunga Collective)。我们已成功收到您的咨询，我们的礼宾团队将在24小时内与您联系。",
            'quote' => '“在维伦加山脉的心脏地带，连接旅行者、生态保护与本土社区。”',
            'contact_btn' => "探索维伦加集合体",
        ],
        'ja' => [
            'subject' => "ヴィルンガ・コレクティブへのお問い合わせありがとうございます",
            'header' => "お問い合わせ受付完了",
            'greeting' => htmlspecialchars($name) . " 様",
            'body' => "ヴィルンガ・コレクティブへお問い合わせいただき誠にありがとうございます。メッセージを正常に受け取りました。24時間以内に担当チームよりご連絡いたします。",
            'quote' => '「ヴィルンガの自然、地域社会、そして旅人を繋ぐ感動の体験を。」',
            'contact_btn' => "ヴィルンガ・コレクティブを見る",
        ],
        'it' => [
            'subject' => "Grazie per aver contattato Virunga Collective",
            'header' => "Messaggio Ricevuto",
            'greeting' => "Gentile " . htmlspecialchars($name) . ",",
            'body' => "Grazie per aver contattato Virunga Collective. Abbiamo ricevuto la tua richiesta e il nostro team ti risponderà entro 24 ore.",
            'quote' => '"Unire viaggiatori, conservazione e comunità nel cuore dei vulcani Virunga."',
            'contact_btn' => "Esplora Virunga Collective",
        ],
        'nl' => [
            'subject' => "Bedankt voor uw bericht aan Virunga Collective",
            'header' => "Bericht Ontvangen",
            'greeting' => "Beste " . htmlspecialchars($name) . ",",
            'body' => "Bedankt voor uw bericht aan Virunga Collective. Wij hebben uw aanvraag goed ontvangen en ons team neemt binnen 24 uur contact met u op.",
            'quote' => '"Reizigers, natuurbehoud en gemeenschap verbinden in het hart van Virunga."',
            'contact_btn' => "Ontdek Virunga Collective",
        ],
        'ko' => [
            'subject' => "비룽가 컬렉티브(Virunga Collective) 문의 접수 안내",
            'header' => "문의가 성공적으로 접수되었습니다",
            'greeting' => htmlspecialchars($name) . " 님께,",
            'body' => "비룽가 컬렉티브에 문의해 주셔서 진심으로 감사드립니다. 보내주신 메시지가 정상 접수되었으며, 24시간 이내에 컨시어지 팀에서 답변해 드리겠습니다.",
            'quote' => '“비룽가 산맥의 중심에서 자연 보전, 지역사회, 그리고 여행자를 하나로 잇습니다.”',
            'contact_btn' => "비룽가 컬렉티브 둘러보기",
        ],
        'ar' => [
            'subject' => "شكراً لتواصلك مع مجموعة فيرونغا (Virunga Collective)",
            'header' => "تم استلام رسالتك بنجاح",
            'greeting' => "عزيزي/عزيزتي " . htmlspecialchars($name) . "،",
            'body' => "شكراً لتواصلك مع مجموعة فيرونغا. لقد استلمنا استفسارك وسيقوم فريقنا بالرد عليك خلال 24 ساعة.",
            'quote' => '«ربط زوارنا بالحفاظ على الطبيعة والمجتمع المحلي في قلب فيرونغا.»',
            'contact_btn' => "استكشف مجموعة فيرونغا",
        ],
        'sw' => [
            'subject' => "Asante kwa kuwasiliana na Virunga Collective",
            'header' => "Ujumbe Wako Umepokelewa",
            'greeting' => "Mpendwa " . htmlspecialchars($name) . ",",
            'body' => "Asante kwa kuwasiliana na Virunga Collective. Tumepokea ujumbe wako na timu yetu itakujibu ndani ya masaa 24.",
            'quote' => '"Kuunganisha wageni, hifadhi ya mazingira, na jamii katikati ya Milima ya Virunga."',
            'contact_btn' => "Gundua Virunga Collective",
        ],
        'ru' => [
            'subject' => "Спасибо за обращение в Virunga Collective",
            'header' => "Сообщение получено",
            'greeting' => "Уважаемый(ая) " . htmlspecialchars($name) . ",",
            'body' => "Спасибо за ваше обращение в Virunga Collective. Мы успешно получили ваше сообщение и ответим вам в течение 24 часов.",
            'quote' => '«Объединяем путешественников, охрану природы и местное сообщество в сердце Вирунга.»',
            'contact_btn' => "Узнать больше о Virunga Collective",
        ]
    ];
    
    return $templates[$lang] ?? $templates['en'];
}

/**
 * Send an automated confirmation email receipt in the applicant's translated language.
 */
function send_multilingual_confirmation_email($userEmail, $userName, $formSubject = '', $formDetails = '', $lang = null) {
    if (empty($userEmail) || !filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    if (!$lang) {
        $lang = get_current_language();
    }

    $phpmailerPath = __DIR__ . '/../ecotours/PHPMailer/src/';
    if (file_exists($phpmailerPath . 'Exception.php') && file_exists($phpmailerPath . 'PHPMailer.php') && file_exists($phpmailerPath . 'SMTP.php')) {
        require_once $phpmailerPath . 'Exception.php';
        require_once $phpmailerPath . 'PHPMailer.php';
        require_once $phpmailerPath . 'SMTP.php';
    } elseif (file_exists(__DIR__ . '/../homestay/vendor/autoload.php')) {
        require_once __DIR__ . '/../homestay/vendor/autoload.php';
    } else {
        return false;
    }

    if (!defined('SMTP_EMAIL') || !defined('SMTP_PASS')) {
        if (file_exists(__DIR__ . '/recaptcha.php')) {
            require_once __DIR__ . '/recaptcha.php';
        }
    }

    $receipt = get_email_receipt_template($userName, $formSubject, $lang);

    try {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = defined('SMTP_EMAIL') ? SMTP_EMAIL : 'virungahomestay@gmail.com';
        $mail->Password   = defined('SMTP_PASS') ? SMTP_PASS : '';
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];

        $mail->setFrom(defined('SMTP_EMAIL') ? SMTP_EMAIL : 'virungahomestay@gmail.com', 'Virunga Collective');
        $mail->addAddress($userEmail, $userName);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $receipt['subject'];

        $mail->Body = "
            <div style='max-width: 620px; margin: 0 auto; font-family: Arial, sans-serif; background: #f6f2e9; border: 1px solid rgba(201, 162, 75, 0.3); border-radius: 10px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05);'>
                <div style='background: #1b3a2b; padding: 28px 30px; text-align: center;'>
                    <h2 style='color: #c9a24b; margin: 0; font-family: \"Cormorant Garamond\", Georgia, serif; font-size: 26px; font-weight: 600;'>" . htmlspecialchars($receipt['header']) . "</h2>
                    <p style='color: #f6f2e9; margin: 6px 0 0; font-size: 13px; letter-spacing: 0.1em; text-transform: uppercase;'>Virunga Collective Ecosystem</p>
                </div>
                <div style='padding: 35px 30px; background: #ffffff;'>
                    <p style='color: #1f2620; font-size: 16px; font-weight: bold; margin-bottom: 16px;'>" . $receipt['greeting'] . "</p>
                    <p style='color: #2c352d; font-size: 15px; line-height: 1.8; margin-bottom: 24px;'>" . $receipt['body'] . "</p>
                    
                    " . (!empty($formDetails) ? "
                    <div style='padding: 20px; background: #f6f2e9; border-radius: 8px; margin-bottom: 24px; border: 1px solid rgba(201, 162, 75, 0.2);'>
                        <strong style='display: block; margin-bottom: 10px; color: #1b3a2b; font-size: 14px;'>Submission Summary:</strong>
                        <div style='color: #2c352d; font-size: 14px; line-height: 1.6;'>" . $formDetails . "</div>
                    </div>" : "") . "

                    <div style='padding: 18px 24px; background: #122a1f; border-left: 4px solid #c9a24b; border-radius: 6px; margin: 24px 0;'>
                        <p style='margin: 0; color: #f6f2e9; font-style: italic; font-size: 14px; line-height: 1.6;'>" . $receipt['quote'] . "</p>
                    </div>

                    <div style='text-align: center; margin-top: 30px;'>
                        <a href='https://virungajourneys.com/' style='display: inline-block; background: #c9a24b; color: #1b3a2b; text-decoration: none; padding: 12px 28px; border-radius: 6px; font-weight: bold; font-size: 14px;'>" . $receipt['contact_btn'] . " &rarr;</a>
                    </div>
                </div>
                <div style='background: #1b3a2b; padding: 20px; text-align: center; font-size: 12px; color: rgba(246,242,233,0.75); line-height: 1.6;'>
                    <strong>Virunga Collective</strong> • Musanze, Northern Province, Rwanda<br>
                    Integrated Conservation, Hospitality & Community Impact Ecosystem<br>
                    Direct WhatsApp: +250 784 513 435 | Email: info@virungajourneys.com
                </div>
            </div>
        ";

        $mail->AltBody = $receipt['greeting'] . "\n\n" . $receipt['body'] . "\n\nVirunga Collective | Musanze, Rwanda";
        return $mail->send();
    } catch (\Exception $e) {
        return false;
    }
}
