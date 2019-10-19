<?php
$lang['addtemplate'] = 'Lägg till mall';
$lang['areyousure'] = 'Är du säker på att du vill radera denna?';
$lang['cachable'] = 'Cachebar';
$lang['dbtemplates'] = 'Databaslagrade mallar';
$lang['default'] = 'Grundinställning';
$lang['delete'] = 'Radera';
$lang['deletetemplate'] = 'Ta bort mall';
$lang['description'] = 'Hantera menymallar för att visa menyer på alla tänkbara sätt';
$lang['edit'] = 'Redigera';
$lang['edittemplate'] = 'Redigera mall';
$lang['error_templatename'] = 'Du kan inte specificera en mall med filändelsen .tpl';
$lang['filename'] = 'Filnamn';
$lang['filetemplates'] = 'Mallar från fil';
$lang['help'] = '<h3>Vad gör den här modulen?</h3>
	<p>Menyhanteraren (Menu Manager) är en modul för att abstrahera menyer till ett system som är enkelt att använda och anpassa. Genom Smarty-mallar kan användaren bestämma hur menyn ska visas. Dvs Menyhanteraren är bara en motor som så att säga matar mallen med uppgifter. Genom att anpassa mallarna, eller göra egna, kan du skapa i princip vilken meny som helst.</p>
	<h3>Hur använder jag den?</h3>
	<p>Använd taggen i en mall/på en sida enligt följande: <code>{menu}</code>.  De parametrar som modulen tar listas längre ner.</p>
	<h3>Varför ska jag bry mig om mallar?</h3>
	<p>Menyhanteraren använder mallar som bestämmer hur menyn ska visas. Modulen kommer med tre standardmallar som heter cssmenu.tpl, minimal_menu.tpl och simple_navigation.tpl. I princip skapar de en enkel lista av sidorna, och använder olika klasser och ID\'s som kan anpassas genom CSS.</p>
	<p>Observera att du ställer in stilen - menyns utseende - med CSS. Stilmallar inkluderas inte med Menyhanteraren, utan måste kopplas till sidans mall separat. För att mallarna cssmenu.tpl och cssmenu-accessible.tpl ska fungera i IE måste du också lägga till en länk till ett JavaScript i head-delen av sidmallen. Det krävs för att hover-effekten ska fungera i IE.</p>
	<p>Om du vill använda en specialversion av en mall kan du enkelt importera mallen till databasen och sedan redigera mallen direkt i CMSMS-administrationen.  Gör då så här:
		<ol>
			<li>Klicka på Layout/Menyhanterare (Menu Manager).</li>
			<li>Klicka på tabben Mallar från fil, och klicka på knappen Importera mall till databas bredvid simple_navigation.tpl.</li>
			<li>Ge kopian av mallen ett namn.  I exemplet kallar vi den "Testmall".</li>
			<li>Du ser nu "Testmall" i listan över Databaslagrade mallar</li>
		</ol>
	</p>
	<p>Nu kan du enkelt modifiera mallen efter dina behov. Lägg till klasser, id\'s och andra taggar så att formateringen är precis som du vill. Nu kan du använda taggen på din sida så här: {menu template=\'Testmall\'}. Observera att filändelsen .tpl måste användas om en filbaserad mall används.</p>
	<p>Parametrarna för $node-objektet som används i mallen är följande:
		<ul>
			<li>$node->id -- Innehålls-ID (för sidan)</li>
			<li>$node->url -- URL för Innehållet</li>
			<li>$node->accesskey -- Access Key, om den är definierad</li>
			<li>$node->tabindex -- Tabb-index, om det är definierat</li>
			<li>$node->titleattribute -- Title-attributet, om det är definierat</li>
			<li>$node->hierarchy -- Position i hierarkin, (t.ex. 1.3.3)</li>
			<li>$node->depth -- Djupet (nivån) för den här noden i den aktuella menyn</li>
			<li>$node->prevdepth -- Djupet (nivån) för noden som var just före den här</li>
			<li>$node->haschildren -- Returnerar true (sant) om noden har undernoder som ska visas</li>
			<li>$node->menutext -- Menytexten</li>
                        <li>$node->alias -- Sidalias</li>
			<li>$node->target -- Målet (target) för länken.  Är tomt om inte satt till något.</li>
			<li>$node->index -- Ordningsnumret för den här noden i hela menyn</li>
			<li>$node->parent -- True (sant) om noden är förälder till den aktuella sidan</li>
		</ul>
	</p>';
$lang['help_action'] = 'Specifiera modulens beteende . Det finns två möjligheter för denna parameter:
<ul>
  <li>default <em>(default)</em> - Used for building a navigation menu.</li>
  <li>breadcrumbs - Used to build a breadcrumb trail to the currently displayed page.  <strong>Note: {cms_breadcrumbs}</strong> is a short way of calling this action.</li>
</ul>';
$lang['help_childrenof'] = 'Med detta alternativ så kommer menyn endast att visa objekt som tillhör vald page id eller alias. Exempel <code>{menu childrenof=$page_alias}</code> kommer endast att visa undersidor till den aktuella sidan.';
$lang['help_collapse'] = 'Sätt till 1 för att menyn ska gömma poster/sidor som inte är relaterade till den aktuella sidan.';
$lang['help_excludeprefix'] = 'Exkudera alla poster (och dess undernoder) vars sidalias innehåller det angivna prefixet. Den här parametern kan inte användas tillsammans med parametern includeprefix';
$lang['help_includeprefix'] = 'Inkludera bara de poster vars sidalias innehåller det angivna prefixet. Den här parametern kan inte kombineras med parametern excludeprefix';
$lang['help_items'] = 'Använd denna för att välja en lista med sidor som den här menyn ska visa. Värdet ska anges som en lista med sidalias, separerade med kommatecken.';
$lang['help_loadprops'] = 'Använd denna parameter när du använder avancerade egenskaper i din meny managermall. Denna parameter tvingar laddningen av all innehåll  för varje node (som EXTRA1, bild, miniatyrbilder, etc). och kommer att dramatiskt öka antalet sökfrågor som behövs för att bygga upp en meny och minne, men kommer att möjliggöra mer avancerade menyer.';
$lang['help_number_of_levels'] = 'Välj det antal nivåer som menyn ska visa.';
$lang['help_root'] = 'Gäller endast för "breadcrumbs", tillåter att man anger en start nivå som inte är standard sidan.';
$lang['help_show_all'] = 'Detta alternativ gör att menyn visar alla noder, även om de är satta att inte visas i menyn. Den visar dock inte ainaktiva sidor.';
$lang['help_show_root_siblings'] = 'Den här parametern kan bara användas om start_element eller start_page används. Den visar sidor som är på samma nivå som den valda start_page/start_element.';
$lang['help_start_element'] = 'Börjar visa menyn från angett start_element och visar enbart den sidan/det elementet och dess undersidor. Anges som positionsnummer i hierarkin (t.ex. 5.1.2).';
$lang['help_start_level'] = 'Med den här parametern visar menyn endast sidor fr.om. den nivån som anges och neråt. Ett enkelt exempel: du har en meny på sidan med number_of_levels=\'1\'. Som andra meny kan du ha start_level=\'2\'. Din andra meny visar sidor som baseras på vad som är valt i den första menyn.';
$lang['help_start_page'] = 'Börjar visa menyn från sidan som anges med start_page och visar enbart den sidan och dess undersidor. Anges som sidalias.';
$lang['help_template'] = 'Mallen som används för att visa menyn. Mallar kommer från databaslagrade mallar om inte mallnamnet anges med filändelsen .tpl. I det senare fallet används mallar från filer i templates-katalogen i MenuManager-mappen.';
$lang['import'] = 'Importera';
$lang['importtemplate'] = 'Importera mall till databas';
$lang['menumanager'] = 'Menyhanterare (Menu Manager)';
$lang['newtemplate'] = 'Nytt mallnamn';
$lang['nocontent'] = 'Inget innehåll angivet';
$lang['notemplatefiles'] = 'Inga filbaserade mallar i %s';
$lang['notemplatename'] = 'Inget mallnamn angivet';
$lang['readonly'] = 'Skrivskyddad';
$lang['set_as_default'] = 'Sätt som grund menymall';
$lang['set_cachable'] = 'Ställ in den här mallen som cachable';
$lang['templatecontent'] = 'Mallinnehåll';
$lang['templatenameexists'] = 'En mall med detta namnet finns redan';
$lang['templates'] = 'Mallar';
$lang['this_is_default'] = 'Grund menymall';
$lang['type_MenuManager'] = 'Menyhanteraren';
$lang['type_navigation'] = 'Navigering';
$lang['usage'] = 'Användning';
$lang['youarehere'] = 'Du är här';
?>