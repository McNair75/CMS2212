<?php
$lang['addarticle'] = 'Tilføj artikel';
$lang['addcategory'] = 'Tilføj kategori';
$lang['addfielddef'] = 'Tilføj feltdefinition';
$lang['addnewsitem'] = 'Tilføj nyhed';
$lang['allcategories'] = 'Alle katagorier';
$lang['allentries'] = 'Alle indlæg';
$lang['allowed_upload_types'] = 'Tillad kun upload af filer med disse type-navne';
$lang['allow_summary_wysiwyg'] = 'Tillad brug af WYSIWYG editoren for resumé-feltet';
$lang['anonymous'] = 'Anonym';
$lang['apply'] = 'Anvend';
$lang['approve'] = 'Sæt status til \'Udgivet\'';
$lang['areyousure'] = 'Er du sikker på dette skal slettes?';
$lang['areyousure_deletemultiple'] = 'Er du sikker på disse artikler skal slettes?\\nDenne handling kan ikke fortrydes';
$lang['areyousure_multiple'] = 'Er du sikker på, du vil udføre denne handling på flere artikler?';
$lang['article'] = 'Artikel';
$lang['articleadded'] = 'Artiklen blev tilføjet.';
$lang['articledeleted'] = 'Artiklen blev slettet';
$lang['articles'] = 'Artikler';
$lang['articlesubmitted'] = 'Det lykkedes at sende artiklen';
$lang['articleupdated'] = 'Det lykkedes at opdatere artiklen';
$lang['author'] = 'Forfatter';
$lang['author_label'] = 'Skrevet af:';
$lang['auto_create_thumbnails'] = 'Opret automatisk billedeksempler for filer af denne type';
$lang['bulk_delete'] = 'Slet';
$lang['bulk_setcategory'] = 'Vælg kategori';
$lang['bulk_setdraft'] = 'Gør til kladde';
$lang['bulk_setpublished'] = 'Offentliggør';
$lang['browsecattemplate'] = 'Gennemse kategori-skabelonerne';
$lang['cancel'] = 'Fortryd';
$lang['categories'] = 'Kategorier';
$lang['category'] = 'Kategori';
$lang['categoryadded'] = 'Kategorien blev tilføjet.';
$lang['categorydeleted'] = 'Kategorien blev slettet.';
$lang['categoryupdated'] = 'Kategorien blev opdateret.';
$lang['category_label'] = 'Kategori:';
$lang['checkbox'] = 'Afkrydsningsboks';
$lang['close'] = 'Luk';
$lang['content'] = 'Indhold';
$lang['customfields'] = 'Feltdefinitioner';
$lang['dateformat'] = '%s er ikke gyldigt ifølge yyyy-mm-dd tt:mm:ss formatet';
$lang['default_category'] = 'Standard kategori';
$lang['default_templates'] = 'Standardskabeloner';
$lang['delete'] = 'Slet';
$lang['delete_article'] = 'Slet artikel';
$lang['delete_selected'] = 'Slet valgte artikler';
$lang['deprecated'] = 'ikke understøttet';
$lang['description'] = 'Tilføj, redigér og slet nyheder';
$lang['desc_adminsearch'] = 'Søg i alle nyhedsartikler (uanset status eller udløbsdato)';
$lang['desc_news_settings'] = 'Indstillinger for nyhedsmodulet';
$lang['detailtemplate'] = 'Skabeloner for indhold';
$lang['detailtemplateupdated'] = 'Den opdaterede skabelon for indhold blev gemt i databasen.';
$lang['detail_page'] = 'Indholdsside';
$lang['detail_template'] = 'Indholdsskabelon';
$lang['displaytemplate'] = 'Vis skabelon';
$lang['down'] = 'Ned';
$lang['draft'] = 'Kladde';
$lang['dropdown'] = 'Rullemenu';
$lang['edit'] = 'Redigér';
$lang['editarticle'] = 'Redigér artikel';
$lang['editcategory'] = 'Redigér kategori';
$lang['editfielddef'] = 'Redigér feltdefinition';
$lang['email_subject'] = 'Emnet for udgående email';
$lang['email_template'] = 'Email-beskedens format';
$lang['enddate'] = 'Slut dato';
$lang['endrequiresstart'] = 'Angivelse af en slutdato kræver en startdato';
$lang['entries'] = '%s Indlæg';
$lang['error_categorynotfoun'] = 'Den valgte kategori blev ikke fundet';
$lang['error_categoryparent'] = 'Den overliggende kategori er ugyldig';
$lang['error_duplicatename'] = 'Der findes allerede et emne med dette navn';
$lang['error_filesize'] = 'En upload\'et fil var større end den maksimalt tilladte';
$lang['error_insufficientparams'] = 'Utilstrækkelige (eller tomme) parametre';
$lang['error_invaliddates'] = 'En eller flere af datoerne er ugyldige';
$lang['error_invalidfiletype'] = 'Kan ikke upload\'e filer af denne type';
$lang['error_invalidurl'] = 'Ugyldig webadresse <em>(måske anvendes den allerede eller den indeholder ugyldige tegn)</em>';
$lang['error_mkdir'] = 'Kunne ikke oprette mappen: %s';
$lang['error_movefile'] = 'Kunne ikke oprette filen: %s';
$lang['error_noarticlesselected'] = 'Ingen artikler valgt';
$lang['error_nooptions'] = 'Der er ikke angivet nogen feltdefinitioner';
$lang['error_templatenamexists'] = 'En skabelon med dette navn findes allerede';
$lang['error_upload'] = 'Fejl ved upload af fil';
$lang['eventdesc-NewsArticleAdded'] = 'Sendes når en artikel tilføjes.';
$lang['eventhelp-NewsArticleAdded'] = '<p>Sendes når en artikel tilføjes.</p>
<h4>Parametre</h4>
<ul>
<li>\\"news_id\\" - Nyhedsartiklens id</li>
<li>\\"category_id\\" - Artiklens kategori-id</li>
<li>\\"title\\" - Artiklens titel</li>
<li>\\"content\\" - Artiklens indhold</li>
<li>\\"summary\\" - Artiklens resumé</li>
<li>\\"status\\" - Artiklens status (\\"Kladde\\" eller \\"Udgivet\\")</li>
<li>\\"start_time\\" - Den dato hvorfra artiklen skal vises</li>
<li>\\"end_time\\" -  Den dato hvorfra artiklen ikke længere skal vises</li>
<li>\\"useexp\\" - Om udløbsdatoen skal ignoreres eller ej</li>
</ul>';
$lang['eventdesc-NewsArticleDeleted'] = 'Sendes når en artikel slettes.';
$lang['eventhelp-NewsArticleDeleted'] = '<p>Sendes når en artikel slettes.</p>
<h4>Parametre</h4>
<ul>
<li>\\"news_id\\" - Nyhedsartiklens id</li>
</ul>';
$lang['eventdesc-NewsArticleEdited'] = 'Sendes når en artikel redigeres.';
$lang['eventhelp-NewsArticleEdited'] = '<p>Sendes når en artikel redigeres.</p>
<h4>Parametre</h4>
<ul>
<li>\\"news_id\\" - Nyhedsartiklens id</li>
<li>\\"category_id\\" - Artiklens kategori-id</li>
<li>\\"title\\" - Artiklens titel</li>
<li>\\"content\\" - Artiklens indhold</li>
<li>\\"summary\\" - Artiklens resumé</li>
<li>\\"status\\" - Artiklens status (\\"Kladde\\" eller \\"Udgivet\\")</li>
<li>\\"start_time\\" - Den dato hvorfra artiklen skal vises</li>
<li>\\"end_time\\" -  Den dato hvorfra artiklen ikke længere skal vises</li>
<li>\\"useexp\\" - Om udløbsdatoen skal ignoreres eller ej</li>
</ul>
<p><strong>Bemærk:</strong> Det er ikke sikkert, at alle disse parametre er tilgængelige, når denne hændelse sendes.</p>';
$lang['eventdesc-NewsCategoryAdded'] = 'Sendes når en kategori tilføjes.';
$lang['eventhelp-NewsCategoryAdded'] = '<p>Sendes når en kategori tilføjes.</p>
<h4>Parametre</h4>
<ul>
<li>\\"category_id\\" - Kategoriens id</li>
<li>\\"name\\" - Kategoriens navn</li>
</ul>';
$lang['eventdesc-NewsCategoryDeleted'] = 'Sendes når en kategori slettes.';
$lang['eventhelp-NewsCategoryDeleted'] = '<p>Sendes når en kategori slettes.</p>
<h4>Parametre</h4>
<ul>
<li>\\"category_id\\" - Det id som den slettede kategori havde </li>
<li>\\"name\\" - Det navn som den slettede kategori havde</li>
</ul>';
$lang['eventdesc-NewsCategoryEdited'] = 'Sendes når en kategori redigeres.';
$lang['eventhelp-NewsCategoryEdited'] = '<p>Sendes når en kategori redigeres.</p>
<h4>Parametre</h4>
<ul>
<li>\\"category_id\\" - Kategoriens id</li>
<li>\\"name\\" - Kategoriens navn</li>
<li>\\"origname\\" - Nyhedskategoriens oprindelige navn</li>
</ul>';
$lang['expired'] = 'Udløbet';
$lang['expired_searchable'] = 'Udløbne artikler kan forekomme i søgeresultater';
$lang['expired_viewable'] = 'Udløbne artikler kan vises i indholdsvisningen';
$lang['expiry'] = 'Udløb';
$lang['expiry_date_asc'] = 'Udløbsdato stigende';
$lang['expiry_date_desc'] = 'Udløbsdato faldende';
$lang['expiry_interval'] = 'Antallet af dage (standardværdi) før en artikel udløber (hvis udløbsdato vælges)';
$lang['extra'] = 'Ekstra';
$lang['extra_label'] = 'Ekstra:';
$lang['fesubmit_redirect'] = 'PageID eller alias som der skal viderstilles til efter nyhedsartklen er sendt gennem fesubmit handlingen';
$lang['fesubmit_status'] = 'Status for nyhedsartikler sendt via frontend';
$lang['fielddef'] = 'Feltdefinition';
$lang['fielddefadded'] = 'Feltdefinitionen blev tilføjet';
$lang['fielddefdeleted'] = 'Feltdefinition slettet';
$lang['fielddefupdated'] = 'Feltdefinition opdateret';
$lang['file'] = 'Fil';
$lang['filter'] = 'Filter';
$lang['firstpage'] = '<<';
$lang['formsubmit_emailaddress'] = 'Email-adresse som adviseringer skal sendes til';
$lang['formtemplate'] = 'Skabeloner for formularer';
$lang['help'] = '<h3>Vigtigt at bemærke</h3>
<p>Version 2.9 og derover af News har fjernet parametret formatpostdate og dateformat fra skabelonerne.  Du bør i stedet bruge cms_date_format (som oplyst i standardskabelonerne) til formatering af datoer samt entry->postdate i stedet for entry->formatpostdate i dine skabeloner.</p>
<h3>Hvad er det her?</h3>
<p>News er et modul til visning af nyheder på siden i lighed med en blog, men med flere funktioner!.  Når modulet er installeret, føjes menupunktet "Nyheder" til administrationsmenuen. I nyhedsadministrationen kan man selektere og oprette nyhedskategorier. Når der er oprettet eller valgt en nyhedskategori, vises en liste over nyheder i kategorien. Herfra kan du tilføje, redigere eller slette nyheder i kategorien.</p>
<h4>Mange visningsmetoder</h4>
<p>Nyhedsmodulets parametre samt understøttelse af mange skabeloner betyder, at du kan præsentere nyhedsartiklerne på et utal af måder.</p>
<h4>Brugerdefinerede felter</h4>
<p>Nyhedsmodulet giver mulighed for selv at definere en række felter (herunder filer og billeder), som gør det muligt at knytte pdf filer eller et utal af billeder til artiklerne.</p>
        <h4>Kategorier</h4>
	<p>Modulet indeholder et system af hierarkiske kategorier, så artiklerne kan organiseres efter ønske.  En nyhedsartikel kan kun ligge ét sted i hierarkiet.</p>
	<h4>Udløb og status</h4>
	<p>Hver nyhedsartikel kan gives en udløbsdato, hvis man ønsker det. Efter den angivne dato vises artiklen ikke længere på hjemmesiden.  Artikler kan også markeres som <em>kladde</em>, hvorved de bliver fjernet permanent fra hjemmesiden.</p>
	<h3>Sikkerhed</h3>
	<p>Brugeren <strong>skal</strong> tilhøre en gruppe, som har tilladelsen \'Modify News\' for at kunne tilføje eller redigere nyhedsartikler.</p>
        <p>For at kunne slette nyhedsartikler <strong>skal</strong> brugeren ligeledes tilhøre en gruppe, som har tilladelsen  \'Delete News Articles\'.</p>
	<p>For at kunne redigere de skabeloner, som styrer layoutet, <strong>skal</strong> brugeren tilhøre en gruppe, som har tilladelsen \'Modify Templates\' .</p>
	<p>For at kunne redigere nyhedernes globale præferencer <strong>skal</strong> brugeren tilhøre en gruppe, som har tilladelsen \'Modify Site Preferences\'.</p>
	<p>For at godkende nyheder fra hjemmesidens "forside" <strong>skal</strong> brugeren tilhøre en gruppe, som har tilladelsen \'Approve News\' .</p>
	<h3>Hvordan tager jeg det i brug?</h3>
	<p>Den letteste måde er at bruge {news} tag\'et (putter modulet ind i et tag, for at forenkle syntaksen).  Modulets tag indsættes i en skabelon eller en side hvor som helst, du ønsker nyhedsartiklerne vist.  Koden kunne se således ud: <code>{news number=\'5\'}</code></p>
<h3>Skabeloner</h3>
<p>Siden version 2.3 har modulet understøttet brug af flere forskellige skabeloner fra databasen, mens ekstra skabeloner fra filer ikke længere understøttes.  Brugere som anvendte det gamle fil-skabelon-system, bør følge disse trin (for hver fil-skabelon):</p>
<ul>
<li>Kopier fil-skabelonen til klippebordet</li>
<li>Opret en ny database-skabelon <em>(til enten resumé eller indhold efter behov)</em>.  Giv den nye skabelon det samme navn (inklusiv endelsen .tpl) som den gamle fil-skabelon og indsæt indholdet fra klippebordet.</li>
<li>Klik på Send</li>
</ul>
<p>Disse trin skulle løse problemet med, at dine nyhedsskabeloner ikke kan findes samt andre lignende smarty fejl, når du opgraderer til en version af CMS, som anvender News 2.3 eller derover.</p>';
$lang['helpaction'] = '\'Tilsidesæt standardhandlingen. Kan antage følgende værdier:
<ul>
<li>&quot;detail&quot; - se en bestemt artikels indhold.</li>
<li>&quot;default&quot; - se artiklens resumé</li>
<li>&quot;fesubmit&quot; - <strong>Anvendelse frarådes</strong> vis frontend formularen, så brugerne kan oprette nyhedsartikler fra den almindelige hjemmeside. Indsæt:<code>{cms_init_editor}</code> i metadata-sektionen for at initialisere den valgte wysiwyg editor. (Side Administration >> Global konfiguration)</li>
<li>&quot;browsecat&quot; - vis en liste over kategorier.</li>
</ul>';
$lang['helpbrowsecat'] = 'Vis en liste over kategorier.';
$lang['helpbrowsecattemplate'] = 'Anvend en særskilt skabelon fra databasen til gennemsyn af kategorier. Skabelonen <strong>skal</strong> være oprettet og kunne ses under fanen "Gennemse kategoriskabeloner" i Nyhedsadministrationen. Skabelonen behøver dog ikke at være valgt som standard. Hvis denne parameter ikke er sat, så vil den skabelon, som for øjeblikket er sat som standard, blive anvendt.';
$lang['helpcategory'] = 'Vis kun nyheder for denne kategori og dens underkategorier. Hvis feltet er blankt vises alle kategorier.';
$lang['helpdetailpage'] = 'Side som nyhedernes indhold skal vises på. Det kan være et side-alias eller et side-id. Bruges til at vise indholdet i en anden skabelon end den der bruges til resumeet.';
$lang['helpdetailtemplate'] = 'Anvend en særskilt skabelon fra databasen til visning af artiklens indhold. Skabelonen <strong>skal</strong> være oprettet og kunne ses under fanen "Indholdsskabeloner" i Nyhedsadministrationen. Skabelonen behøver dog ikke at være valgt som standard. Hvis denne parameter ikke er sat, så vil den skabelon, som for øjeblikket er sat som standard, blive anvendt.';
$lang['helpformtemplate'] = 'Brug en særskilt skabelon fra databasen til visning af formularen til oprettelse af artikler. Skabelonen <strong>skal</strong> være oprettet og kunne ses under fanen "Formularskabeloner" i Nyhedsadministrationen. Skabelonen behøver dog ikke at være valgt som standard. Hvis denne parameter ikke er sat, så vil den skabelon, som for øjeblikket er sat som standard, blive anvendt.';
$lang['helpmoretext'] = 'Tekst der skal vises efter en nyhed hvis længden af denne overskrider resumeets længde. Standard er "Mere..."';
$lang['helpnumber'] = 'Det maksimale antal nyheder der skal vises -- alle nyheder vises hvis feltet er blankt.';
$lang['helpshowall'] = 'Vis alle artikler, uden at tage hensyn til udløbsdato';
$lang['helpshowarchive'] = 'Vis kun udløbne nyhedsartikler';
$lang['helpsortasc'] = 'Sortér nyheder i stigende orden efter dato i stedet for faldende.';
$lang['helpsortby'] = 'Hvilket felt der skal sorteres efter. Muligheder er: "news_date", "summary", "news_data", "news_category", "news_title", "news_extra", "end_time", "start_time", "random". Er intet angivet benyttes "news_date". Hvis sat til "random", bliver sortasc-parametren ignoreret.';
$lang['helpstart'] = 'Start ved det n\'te element - hvis intet er angivet startes ved det første element.';
$lang['helpsummarytemplate'] = 'Benyt en særskilt skabelon fra databasen til at vise en nyhedsartikels resumé. Skabelonen <strong>skal</strong> være oprettet og kunne ses under fanen "Resuméskabeloner" i Nyhedsadministrationen, omend den ikke behøver at være sat som standard. Hvis denne parameter ikke er sat, så vil den skabelon, som i øjeblikket er markeret som standard blive anvendt.';
$lang['help_articleid'] = 'Denne parameter kan kun anvendes ved indholdsvisning.  Den gør det muligt at specificere hvilke nyhedsartikler, som skal vises i indholdsvisning.  Anvendes specialværdien -1, vil systemet vise den nyeste artikel med statussen "Udgivet", som ikke har overskredet udløbsdatoen.';
$lang['help_article_title'] = 'Indtast artiklens titel. Den bør være kort og uden html-koder.';
$lang['help_article_category'] = 'Af hensyn til organiseringen af artiklerne, har du mulighed for at vælge en kategori';
$lang['help_article_content'] = 'Indtast artiklens hovedindhold her';
$lang['help_article_enddate'] = 'Hvis der benyttes udløbstidspunkt, bliver artiklen ikke længere vist efter det angivne tidspunkt';
$lang['help_article_extra'] = 'Her kan angives ekstra oplysninger tilknyttet nyhedsartiklen. Feltet kan anvendes til bestemmelse af rækkefølge eller i forbindelse med funktioner fastsat af designeren. Du bør spørge udvikleren af hjemmesiden om, hvordan feltet skal bruges (om overhovedet)';
$lang['help_article_searchable'] = 'Dette felt angiver, om artiklen skal indekseres af søgemodulet eller ej';
$lang['help_article_postdate'] = 'Oprettelsesdatoen <em>(normalt dags dato for nyhedsartikler)</em> er den dato, hvor artiklen bliver offentliggjort. Den anvendes også i forbindelse med sortering af rækkefølge';
$lang['help_article_summary'] = 'Indtast en kort tekst, som beskriver artiklen. Resumeet kan bruges i forbindelse med oversigter over flere artikler';
$lang['help_article_status'] = 'Hvis du ønsker, at artiklen øjeblikkeligt skal kunne ses af andre, skal du vælge statussen offentliggjort. Hvis du gerne vil arbejde videre med denne artikel lidt endnu, så vælg kladde';
$lang['help_articles_filtercategory'] = 'Listen over viste artikler kan filtreres, så kun dem der tilhører den valgte kategori bliver vist';
$lang['help_pagelimit'] = 'Maksimalt antal emner der skal vises (pr. side). Hvis denne parameter ikke er sat, vil alle emner blive vist. Hvis den er sat, og der er flere emner end angivet, vil der blive vist tekst og links, så der kan rulles gennem resultaterne';
$lang['hide_summary_field'] = 'Skjul resumé-feltet ved oprettelse eller redigering af artikler';
$lang['info_allow_fesubmit'] = 'Denne indstilling bestemmer, hvorvidt der kan indsendes artikler af brugerene via selve hjemmesiden med modulet Front End Users. Vær forsigtig med denne indstilling.';
$lang['info_categories'] = 'Nyhedsartikler kan organiseres ved hjælp af hierarkiske kategorier';
$lang['info_detail_returnid'] = 'Præferencen anvendes til at bestemme hvilken side (og dermed hvilken skabelon) der skal bruges til visning af nyhedernes indhold.  Individualiserede webadresser til nyhedsindhold kommer ikke til at virke, hvis ikke denne parameter sættes til en gyldig side. Bemærk endvidere, at hvis denne præference er sat, men der ikke er angivet nogen værdi for parametren "detailpage" sammen med news tag\'et, så anvendes denne værdi til links til nyhedernes indhold.';
$lang['info_expired_searchable'] = 'Ved tilvalg kan udløbne artikler fortsat indekseres af søgemodulet og vises i søgeresultaterne';
$lang['info_expired_viewable'] = 'Hvis aktiveret kan udløbne artikler vises i indholdsmodus (genskabelse af ældre funktionalitet). Parametren showall kan anvendes på webadressen (når der ikke bruges pretty urls) for at vise, at også udløbne artikler kan blive vist.';
$lang['info_fesubmit_notification'] = 'Hvis ønsket kan du sende en email til en enkelt email adresse, når der indsendes en nyhedsartikel via formular på selve hjemmesiden.';
$lang['info_maxlength'] = 'Den maksimale længe har kune relevans for tekst input felter';
$lang['info_reorder_categories'] = 'Træk og slip hvert af emnerne til den korrekte placering for at ændre kategoriernes indbyrdes orden';
$lang['info_searchable'] = 'Feltet angiver, om artiklen skal medtages af søgemodulet eller ej';
$lang['info_sysdefault'] = '<em>(indhold der benyttes som standard når der oprettes en ny skabelon)</em>';
$lang['info_sysdefault2'] = '<strong>Bemærk:</strong> Denne fane indeholder tekstfelter, hvori du kan redigere et sæt af skabeloner, som bruges, når du opretter en ny skabelon til resumeer, indhold og formularer.  Ændring af indhold under denne fane og klik på \'Send\' har <strong>ingen effekt på nogen af de nuværende visningsmåder</strong>.';
$lang['lastpage'] = '>>';
$lang['lbl_adminsearch'] = 'Søg i nyhedsartikler';
$lang['linkedfile'] = 'Tilknyttet fil';
$lang['maxlength'] = 'Maksimal længde';
$lang['msg_cancelled'] = 'Handling annulleret';
$lang['msg_categoriesreordered'] = 'Rækkefølge af kategorier opdateret';
$lang['msg_contenttype_removed'] = 'Nyheds-indholdstypen er blevet fjernet. Erstat venligst {news} tags med de fungerende parametre i din skabelon eller indhold for at genskabe denne funktionalitet.';
$lang['msg_success'] = 'Handling udført';
$lang['more'] = 'Mere';
$lang['moretext'] = 'Mere tekst';
$lang['name'] = 'Navn';
$lang['nameexists'] = 'Et felt med dette navn eksisterer allerede';
$lang['needpermission'] = 'Du skal have tilladelsen \\\'%s\\\' for at kunne udføre den funktion.';
$lang['newcategory'] = 'Ny kategori';
$lang['news'] = 'Nyheder';
$lang['news_return'] = 'Tilbage';
$lang['nextpage'] = '>';
$lang['noarticles'] = 'Indtil videre er der ikke blevet oprettet nogen nyhedsartikler';
$lang['noarticlesinfilter'] = 'Ved brug af dette filter er der ikke nogen nyhedsartikler at vise';
$lang['nocategorygiven'] = 'Ingen kategori angivet';
$lang['nocontentgiven'] = 'Intet indhold angivet';
$lang['noitemsfound'] = '<strong>Ingen</strong> nyheder fundet for kategorien: %s';
$lang['nonamegiven'] = 'Intet navn angivet';
$lang['none'] = 'Ingen';
$lang['nopostdategiven'] = 'Ingen oprettelsesdato angivet';
$lang['notanumber'] = 'Maksimal længde er ikke et tal';
$lang['note'] = '<em>Bemærk:</em> Datoer skal angives i formatet: \'yy-mm-dd tt:mm:ss\'.';
$lang['notify_n_draft_items'] = 'Du har %s som ikke er offentliggjort';
$lang['notify_n_draft_items_sub'] = '%d Nyhedsartikle(r)';
$lang['notitlegiven'] = 'Ingen titel angivet';
$lang['numbertodisplay'] = 'Antal der skal vises (blank viser alle nyheder)';
$lang['options'] = 'Indstillinger';
$lang['optionsupdated'] = 'Indstillingerne blev gemt.';
$lang['parent'] = 'Overliggende';
$lang['postdate'] = 'Oprettelsesdato';
$lang['postinstall'] = 'Kontrollér at tilladelsen "Modify News" er slået til for brugere der skal kunne administrere Nyheder.';
$lang['post_date_asc'] = 'Oprettelsesdato stigende';
$lang['post_date_desc'] = 'Oprettelsesdato faldende';
$lang['preview'] = 'Visning';
$lang['prevpage'] = '<';
$lang['print'] = 'Udskriv';
$lang['prompt_alert_drafts'] = 'Giv besked om artikler, som ikke er blevet godkendt';
$lang['prompt_allow_fesubmit'] = 'Tillad at nyhedsartikler kan oprettes via hjemmesiden';
$lang['prompt_default'] = 'Standard';
$lang['prompt_go'] = 'Sæt igang';
$lang['prompt_name'] = 'Navn';
$lang['prompt_newtemplate'] = 'Opret en ny  skabelon';
$lang['prompt_of'] = 'af';
$lang['prompt_page'] = 'Side';
$lang['prompt_pagelimit'] = 'Side grænse';
$lang['prompt_redirecttocontent'] = 'Retur til side';
$lang['prompt_sorting'] = 'Sortér på';
$lang['prompt_template'] = 'Skabelon kode';
$lang['prompt_templatename'] = 'Skabelonens navn';
$lang['public'] = 'Udgivet';
$lang['published'] = 'Udgivet';
$lang['reassign_category'] = 'Skift kategori til';
$lang['removed'] = 'Fjernet';
$lang['reorder'] = 'Ændring af rækkefølge';
$lang['reset'] = 'Nulstil';
$lang['resettodefault'] = 'Gendan fabriksindstillinger';
$lang['restoretodefaultsmsg'] = 'Med denne handling gendannes systemets oprindelige skabeloner. Er du sikker på, du vil fortsætte?';
$lang['revert'] = 'Sæt status til \'Kladde\'';
$lang['searchable'] = 'Søgbar';
$lang['select'] = 'Vælg';
$lang['select_option'] = 'Foretag valg';
$lang['selectall'] = 'Vælg alle';
$lang['selectcategory'] = 'Vælg kategori';
$lang['showchildcategories'] = 'Vis underkategorier';
$lang['sortascending'] = 'Sortér stigende';
$lang['startdate'] = 'Start dato';
$lang['startdatetoolate'] = 'Startdatoen er for sen (efter slutdato?)';
$lang['startoffset'] = 'Begynd visning ved det n\'te element';
$lang['startrequiresend'] = 'Angivelse af en startdato kræver en slutdato';
$lang['status'] = 'Status';
$lang['status_asc'] = 'Status i stigende orden';
$lang['status_desc'] = 'Status i faldende orden';
$lang['subject_newnews'] = 'Der er blevet oprettet en ny nyhedsartikel';
$lang['submit'] = 'Send';
$lang['summary'] = 'Resumé';
$lang['summarytemplate'] = 'Resuméskabelon';
$lang['summarytemplateupdated'] = 'Skabelonen til resumé af nyheder blev opdateret.';
$lang['sysdefaults'] = 'Nulstil til standardværdier';
$lang['template'] = 'Skabelon';
$lang['textarea'] = 'Tekstfelt';
$lang['textbox'] = 'Tekst input';
$lang['title'] = 'Titel';
$lang['title_asc'] = 'Titel stigende';
$lang['title_available_templates'] = 'Tilgængelige skabeloner';
$lang['title_browsecat_sysdefault'] = 'Standardskabelon for gennemsyn af kategorier';
$lang['title_browsecat_template'] = 'Skabelon for gennemsyn af kategorier';
$lang['title_desc'] = 'Titel faldende';
$lang['title_detail_returnid'] = 'Standardside til indholdsvisning';
$lang['title_detail_settings'] = 'Indstillinger for indholdsvisning';
$lang['title_detail_sysdefault'] = 'Standardskabelon for indhold';
$lang['title_detail_template'] = 'Redigering af indholdsskabeloner';
$lang['title_draft_entries'] = 'Nyhedsartikler som ikke er blevet godkendt';
$lang['title_fesubmit_form'] = 'Send nyhedsartikel';
$lang['title_fesubmit_settings'] = 'Indstillinger for oprettelse af nyheder via frontend';
$lang['title_filter'] = 'Filtre';
$lang['title_form_sysdefault'] = 'Standardskabelon for formular';
$lang['title_form_template'] = 'Redigering af formular-skabeloner';
$lang['title_news_settings'] = 'Indstillinger - Nyhedsmodul';
$lang['title_notification_settings'] = 'Indstillinger for advisering';
$lang['title_submission_settings'] = 'Indstillinger for oprettelse af nyheder';
$lang['title_summary_sysdefault'] = 'Standardskabelon for resumé';
$lang['title_summary_template'] = 'Redigering af resuméskabeloner';
$lang['toggle_bulk'] = 'Medtag denne artikel ved samlet behandling af flere artikler';
$lang['type'] = 'Skriv';
$lang['type_browsecat'] = 'Gennemse kategori';
$lang['type_form'] = 'Brugerformular';
$lang['type_detail'] = 'Detalje';
$lang['type_News'] = 'Nyheder';
$lang['type_summary'] = 'Resumé';
$lang['unknown'] = 'Ukendt';
$lang['unlimited'] = 'Ubegrænset';
$lang['up'] = 'Op';
$lang['uploadscategory'] = 'Uploads kategori';
$lang['url'] = 'Webadresse';
$lang['useexpiration'] = 'Benyt udløbsdato';
$lang['viewfilter'] = 'Vis filter';
$lang['warning_preview'] = 'Advarsel: Dette visningsvindue fungerer i store træk ligesom en browser, hvilket indebærer, at du kan navigere væk fra den oprindeligt viste side. Men gør du det, kan du komme ud for uventede hændelser.  Navigering væk fra den oprindelige side og tilbage igen fungerer ikke som forventet.<br/><strong>Bemærk:</strong> Visningen uploader ikke de filer, du måtte have valgt til upload.';
$lang['with_selected'] = 'Med valgte';
?>