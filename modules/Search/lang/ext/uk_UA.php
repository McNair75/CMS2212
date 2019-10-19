<?php
$lang['clear'] = 'Очистити';
$lang['confirm_clearstats'] = 'Ви впевнені, що хочете остаточно видалити всю статистику?';
$lang['confirm_reindex'] = 'Ця операція може зайняти тривалий час та/або вимагати великої кількості пам\'яті PHP. Ви впевнені, що хочете переіндексувати весь вміст?';
$lang['count'] = 'Кількість';
$lang['default_stopwords'] = 'i, me, my, myself, we, our, ours, ourselves, you, your, yours, yourself, yourselves, he, him, his, himself, she, her, hers, herself, it, its, itself, they, them, their, theirs, themselves, what, which, who, whom, this, that, these, those, am, is, are, was, were, be, been, being, have, has, had, having, do, does, did, doing, a, an, the, and, but, if, or, because, as, until, while, of, at, by, for, with, about, against, between, into, through, during, before, after, above, below, to, from, up, down, in, out, on, off, over, under, again, further, then, once, here, there, when, where, why, how, all, any, both, each, few, more, most, other, some, such, no, nor, not, only, own, same, so, than, too, very';
$lang['description'] = 'Модуль для пошуку по сторінках сайту та вмісту модулів.';
$lang['eventdesc-SearchAllItemsDeleted'] = 'Надіслано, коли всі елементи видалено з індексу.';
$lang['eventhelp-SearchAllItemsDeleted'] = '<h4>Параметри</h4>
<ul>
<li>Немає</li>
</ul>';
$lang['eventdesc-SearchCompleted'] = 'Надіслано, коли пошук завершено';
$lang['eventhelp-SearchCompleted'] = '<h4>Параметри</h4>
<ol>
<li>Пошуковий запит.</li> 
<li>Масив знайдених результатів.</li>
</ol>';
$lang['eventdesc-SearchInitiated'] = 'Надіслано, коли пошук розпочато';
$lang['eventhelp-SearchInitiated'] = '<h4>Параметри</h4>
<ol>
<li>Пошуковий запит.</li> 
</ol>';
$lang['eventdesc-SearchItemAdded'] = 'Надіслано, коли новий елемент проіндексовано';
$lang['eventhelp-SearchItemAdded'] = '<h4>Параметри</h4>
<ol>
<li>Назва модулю.</li>
<li>ID елементу.</li>
<li>Додатковий Атрибут.</li>
<li>Вміст для індексування та додавання.</li>
</ol>';
$lang['eventdesc-SearchItemDeleted'] = 'Надіслано, коли елемент видалено з індексу';
$lang['eventhelp-SearchItemDeleted'] = '<h4>Параметри</h4>
<ol>
<li>Назва модулю.</li>
<li>ID елементу.</li>
<li>Додатковий Атрибут.</li>
</ol>';
$lang['export_to_csv'] = 'Експортувати до CSV';
$lang['help'] = '<h3>What does this do?</h3>
<p>Search is a module for searching "core" content along with certain registered modules.  You put in a word or two and it gives you back matching, relevant results.</p>
<h3>How do I use it?</h3>
<p>The easiest way to use it is with the {search} wrapper tag (wraps the module in a tag, to simplify the syntax). This will insert the module into your template or page anywhere you wish, and display the search form.  The code would look something like: <code>{search}</code></p>
<h4>How do i prevent certain content from being indexed</h4>
<p>The search module will not search any "inactive" pages. However on occasion, when you are using the CustomContent module, or other smarty logic to show different content to different groups of users, it may be advisable to prevent the entire page from being indexed even when it is live.  To do this include the following tag anywhere on the page <em><!-- pageAttribute: NotSearchable --></em> When the search module sees this tag in the page it will not index any content for that page.</p>
<p>The <em><!-- pageAttribute: NotSearchable --></em> tag can be placed in the template as well.  if this is done, none of the pages attached to that template will be indexed.  Those pages will be re-indexed if the tag is removed</p>';
$lang['input_resetstopwords'] = 'Вивантажити';
$lang['noresultsfound'] = 'Нічого не знайдено!';
$lang['nostatistics'] = 'Статистики не знайдено!';
$lang['options'] = 'Параметри';
$lang['param_action'] = 'Вкажіть режим роботи модуля. Прийнятними значеннями є \'default\', і \'keywords\'. Дія \'keywords\' може бути використана для створення списку слів через кому, придатних для використання в метатеґу \'keywords\'.';
$lang['param_count'] = 'Використаний з дією \'keywords\', цей параметр дозволить обмежити вивід зазначеної кількості слів';
$lang['param_detailpage'] = 'Використовується лише для знайдених результатів з модулів, цей параметр дозволяє вказати іншу сторінку для виводу результатів. Це корисно, якщо, наприклад, ви завжди відображуєте деталізований вміст (detail view) модуля на сторінці з іншим шаблоном. <em>(<strong>Примітка:</strong> Модулі можуть перевизначати цей параметр.)</em>';
$lang['param_formtemplate'] = 'Використовується лише для дії за замовчуванням, цей параметр дозволяє вказати шаблон.';
$lang['param_inline'] = 'Якщо вірно (true), результати пошуку будуть вставлені замість тегу "search". Використовуйте цей параметр, якщо ваш шаблон має декілька блоків вмісту, і ви не хочете, щоб результати пошуку замінили блок вмісту за замовчуванням';
$lang['param_modules'] = 'Обмежити пошук по модулях, зазначених через кому';
$lang['param_pageid'] = 'Застосовується лише з дією \'keywords\', цей параметр може використовуватися для вказування ID іншої сторінки на яку будуть виведені результати';
$lang['param_passthru'] = 'Передайте названі параметри до вказаних модулів. Формат кожного з цих параметрів: "passtru_MODULENAME_PARAMNAME=\'value\'" i.e.: passthru_News_detailpage=\'newsdetails\'"';
$lang['param_resultpage'] = 'Сторінка, що відображає результати пошуку. Це може бути або псевдонім сторінки, або ID. Дозволяє вивести результати пошуку на сторінці, відмінній (з іншим шаблоном) від тієї, на якій виведена пошуковаа форма';
$lang['param_resulttemplate'] = 'Цей параметр дозволяє вказати шаблон для відображення результатів пошуку.';
$lang['param_searchtext'] = 'Текст у полі пошуку';
$lang['param_submit'] = 'Текст для кнопки відправки';
$lang['param_useor'] = 'Змінити відношення за замовчуванням з OR на AND';
$lang['prompt_alpharesults'] = 'Сортувати результати в алфавітному порядку замість значущості';
$lang['prompt_resetstopwords'] = 'Завантажте з мови стоп-слова за замовчуванням';
$lang['prompt_resultpage'] = 'Сторінка для виводу результатів певних модулів <em>(Враховуйте, що модулі можуть замінити це значення)</em>';
$lang['prompt_savephrases'] = 'Пошукові словосполучення, а не окремі слова';
$lang['prompt_searchtext'] = 'Пошуковий текст за замовчуванням';
$lang['reindexallcontent'] = 'Переіндексувати весь вміст';
$lang['reindexcomplete'] = 'Переіндексування завершено!';
$lang['restoretodefaultsmsg'] = 'Ця операція відновить вміст шаблону до системних значень за замовчуванням. Ви впевнені, що хочете продовжити?';
$lang['resulttemplate'] = 'Шаблон для виводу результатів';
$lang['resulttemplateupdated'] = 'Шаблон для виводу результатів оновлено';
$lang['search'] = 'Пошук';
$lang['searchresultsfor'] = 'Результати пошуку для';
$lang['searchsubmit'] = 'Відправити';
$lang['searchtemplate'] = 'Шаблон для пошуку';
$lang['searchtemplateupdated'] = 'Шаблон для пошуку оновлено';
$lang['search_method'] = 'Сумісність Pretty URLs за допомогою методу POST, значення за замовчуванням завжди GET, щоб зробити цю роботу просто введіть {search search_method="post"}';
$lang['statistics'] = 'Статистика';
$lang['stopwords'] = 'Стоп-слова';
$lang['submit'] = 'Відправити';
$lang['sysdefaults'] = 'Відновити до значення за замовчуванням';
$lang['timetaken'] = 'Витрачено часу';
$lang['type_Search'] = 'Пошук';
$lang['type_searchform'] = 'Пошукова форма';
$lang['type_searchresults'] = 'Результати пошуку';
$lang['usestemming'] = 'Використовувати Word Stemming (лише англійською мовою)';
$lang['use_or'] = 'Знайти результати, які відповідають БУДЬ-ЯКОМУ з слів';
$lang['word'] = 'Слово';
?>