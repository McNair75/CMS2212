<?php
$lang['description'] = 'Цей модуль забезпечує легкий і простий спосіб генерування HTML-код, необхідний для меню веб-сайту безпосередньо та динамічно з структури сторінки CMSMS. Він забезпечує гнучку фільтрацію та можливості шаблонів для створення потужних, швидких та привабливих веб-сторінок без взаємодії з редактором вмісту.';
$lang['friendlyname'] = 'Генератор меню CMSMS';
$lang['help'] = '<h3>What does this do?</h3>
  <p>The "Navigator" module is an engine for generating navigations from the CMSMS content tree and a smarty template.  This module provides flexible filtering capabilities to allow building numerous navigations based on different criteria, and a simple to use hierarchical data format for generating navigations with complete flexibility.</p>
  <p>This module has no admin interface of its own, instead it uses the DesignManager to manage Navigator templates.</p>
<h3>How do I use it?</h3>
<p>The simplest way to use this module is to insert the <code>{Navigator}</code> tag into a template.  The module accepts numerous parameters to alter its behavior and filter the data.</p>
<h3>Why do I care about templates?</h3>
<p>This is the power of CMSMS.  Navigations can be built automatically using the data from your content hierarchy, and a smarty template.  There is no need to edit a navigation object each time a content page is added or removed from the system.  Additionally, navigation templates can easily include JavaScript or advanced functionality and can be shared between websites.</p>
<p>This module is distributed with a few sample templates, they are only samples.  You are free and encouraged to copy them and modify the templates to your liking.  Styling of the navigation is accomplished by editing a CMSMS stylesheet.  Stylesheets are not included with the Navigator module.</p>
<h3>The node object:</h3>
  <p>Each nav template is provided with an array of node objects that match the criteria specified on the tag.  Below is a description of the members of the node object:</p>
<ul>
  <li>$node->id -- The content object integer ID.</li>
  <li>$node->type -- The type of the node.  i.e: content, link, pagelink, etc.</li>
  <li>$node->url -- URL to the content object.  This should be used when building links.</li>
  <li>$node->accesskey -- Access Key, if defined.</li>
  <li>$node->tabindex -- Tab index, if defined.</li>
  <li>$node->titleattribute -- Description, or Title attribute (title), if defined.</li>
  <li>$node->hierarchy -- Hierarchy position.  (i.e. 1.3.3)</li>
  <li>$node->default -- TRUE if this node refers to the default content object.</li>
  <li>$node->menutext -- Menu Text</li>
  <li>$node->raw_menutext -- Menu Text without having html entities converted</li>
  <li>$node->alias -- Page alias</li>
  <li>$node->extra1 -- This field contains the value of the extra1 page property, unless the loadprops-parameter is set to NOT load the properties.</li>
  <li>$node->extra2 -- This field contains the value of the extra2 page property, unless the loadprops-parameter is set to NOT load the properties.</li>
  <li>$node->extra3 -- This field contains the value of the extra3 page property, unless the loadprops-parameter is set to NOT load the properties.</li>
  <li>$node->image -- This field contains the value of the image page property (if non empty), unless the loadprops-parameter is set to NOT load the properties.</li>
  <li>$node->thumbnail -- This field contains the value of the thumbnail page property (if non empty), unless the loadprops-parameter is set to NOT load the properties.</li>
  <li>$node->target -- This field contains Target for the link (if non empty), unless the loadprops-parameter is set to NOT load the properties.</li>
  <li>$node->created -- Item creation date</li>
  <li>$node->modified -- Item modified date</li>
  <li>$node->parent -- TRUE if this node is a parent of the currently selected page</li>
  <li>$node->current -- TRUE if this node is the currently selected page</li>
  <li>$node->children_existn -- TRUE if this node has any children at all.</li>
  <li>$node->children -- An array of node objects representing the displayable children of this node. Not set if node does not have children to display.</li>
  <li>$node->has_children -- TRUE if this node has any children that could be displayed but are not being displayed due to other filtering parameters (number of levels, etc).</li>
</ul>
<h3>Examples:</h3>
<ul>
   <li>A simple navigation that is only 2 levels deep, using the default template:<br/>
     <pre><code>{Navigator number_of_levels=2}</code></pre>
   </li>
     <li>Display a simple navigation two levels deep starting with the children of the current page.  Use the default template:</li>
     <pre><code>{Navigator number_of_levels=2 start_page=$page_alias}</code></pre>
   </li>
   <li>Display a simple navigation two levels deep starting with the children of the current page.  Use the default template:</li>
     <pre><code>{Navigator number_of_levels=2 childrenof=$page_alias}</code></pre>
   </li>
   <li>Display a navigation two levels deep starting with the current page, its peers, and everything below them.  Use the default template:</li>
     <pre><code>{Navigator number_of_levels=2 start_page=$page_alias show_root_siblings=1}</code></pre>
   </li>
   <li>Display a navigation of the specified menu items and their children.  Use the template named mymenu</li>
     <pre><code>{Navigator items=\'alias1,alias2,alias3\' number_of_levels=3 template=mymenu}</code></pre>
   </li>
</ul>';
$lang['help_action'] = 'Вкажіть дію модуля. Цей модуль підтримує дві дії:
<ul>
   <li><em>за замовчуванням</em> - використовується для побудови основного меню. (якщо жодної дії не вказано).</li>
   <li> хлібні крихти - використовується для створення міні-меню, що складається з шляху від кореня сайту до поточної сторінки.</li>
</ul>';
$lang['help_collapse'] = 'Якщо цей параметр ввімкнено, буде виведено лише елементи, що безпосередньо пов\'язані з поточною активною сторінкою';
$lang['help_childrenof'] = 'Цей параметр дозволяє вивести лише нащадків сторінки, яку ви зазначите за допомогою ID або псевдоніма. Тобто: <code>{Navigator childrenof=$page_alias}</code> відображатиме лише дочірні сторінки поточної сторінки.';
$lang['help_excludeprefix'] = 'Виключити всі елементи (та їхні дочірні елементи), псевдоніми сторінок яких відповідають одному з вказаних через кому префіксів. Цей параметр не можна використовувати разом з параметром includeprefix.';
$lang['help_includeprefix'] = 'Включити лише ті елементи, псевдоніми сторінок яких відповідають одному з вказаних через кому префіксів. Цей параметр не можна поєднувати з параметром excluderefix.';
$lang['help_items'] = 'Вкажіть через кому список псевдонімів сторінок, які слід відобразити в меню.';
$lang['help_loadprops'] = 'Використовуйте цей параметр, якщо в шаблоні меню НЕ використовуються розширені властивості вмісту. Це вимкне завантаження всіх властивостей вмісту для кожної сторінки (наприклад extra1, зображення, ескіз та ін.). Це значно зменшить кількість запитів, необхідних для побудови меню, і звільнить пам\'ять, але позбавить можливості використовувати просунуті властивості вмісту';
$lang['help_nlevels'] = 'Псевдонім для number_of_levels';
$lang['help_number_of_levels'] = 'Цей параметр обмежить глибину створеного меню до визначеної кількості рівнів. За замовчуванням значення для цього параметра має бути необмеженим, крім випадків, коли використовується параметр items, і при цьому параметр number_of_levels повинен бути 1';
$lang['help_root2'] = 'Використовується лише в дії "breadcrumbs", цей параметр вказує на те, що хлібні крихти повинні бути побудовані від сторінки, псевдонім якої зазначено, а не від домашньої сторінки. Вказавши значення -1, відображатимуться лише хлібні крихти від верхнього рівня, виключаючи домашню сторінку.';
$lang['help_show_all'] = 'Ця параметр призведе до того, що в меню відображатимуться всі сторінки, навіть якщо їх налаштовано не відображати в меню. Однак це не відображатиме неактивні сторінки.';
$lang['help_show_root_siblings'] = 'Цей параметр стане в нагоді лише тоді, коли буде використано start_element або start_page. Основна дія - це відобразити всі інші суміжні сторінки на цьому рівні.';
$lang['help_start_element'] = 'Відображає меню починаючи з заданого start_element, включаючи всі його дочірні елементи. Приймає позицію в ієрархії сторінок (наприклад, 5.1.2).';
$lang['help_start_level'] = 'Цей параметр дозволяє відобразити пункти меню, починаючи з заданого рівня відносно поточної сторінки. Простий приклад, якщо у вас було одне меню на сторінці з number_of_levels=1. У другому меню, у вас є start_level=2. Тепер ваше друге меню відображатиме елементи на основі того, що вибрано в першому меню. Мінімальне значення для цього параметра становить 2';
$lang['help_start_page'] = 'Відображає меню, починаючи з start_page, включаючи всі його дочірні елементи. Приймає псевдонім сторінки.';
$lang['help_template'] = 'Шаблон для відображення меню. Вказаний шаблон повинен існувати в Менеджері Дизайну, а якщо відсутній, то відобразиться помилка. Якщо цей параметр не вказано, буде використано шаблон за замовчуванням типу Navigator::Navigation';
$lang['help_start_text'] = 'Корисний лише в дії breadcrumbs, цей параметр дозволяє вказати додатковий текст для відображення на початку хлібних крихт. Наприклад "Ви знаходитесь тут"';
$lang['type_breadcrumbs'] = 'Хлібні крихти';
$lang['type_Navigator'] = 'Навігатор';
$lang['type_navigation'] = 'Меню';
$lang['youarehere'] = 'Ви знаходитесь тут';
?>