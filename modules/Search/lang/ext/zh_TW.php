<?php
$lang['clear'] = '清除';
$lang['confirm_clearstats'] = '你確定要永久清除所有統計嗎?';
$lang['count'] = '計算';
$lang['default_stopwords'] = '我，我自己，我們，我們自己，你，你的，你自己，他，他自己，她，她的，她自己，它，它們，它們，它們本身，什麼，那一個，誰，這，那些，這些，是，曾經，擁有，做，和，但是，如果，或者，因為，直到，while，of，at，by，for，with，about，against，between，into，
通過，期間，之前，之後，上方，下方，通向，向上，向下，
in，out，on，off，over，under，again，more，then，once，here，
那裡，何時，何地，為什麼，如何，所有，任何，兩者，每一個，幾個，更多，
大多數，其他，某些，這樣，不，也不是，只有，擁有，相同，所以，
而且，非常';
$lang['description'] = '搜索站點和其他模塊內容的模塊。';
$lang['eventdesc-SearchAllItemsDeleted'] = '當所有索引被刪除時發送。';
$lang['eventhelp-SearchAllItemsDeleted'] = '<p>當所有索引被刪除時發送。</p>
<h4>參數</h4>
<ul>
<li>無</li>
</ul>';
$lang['eventdesc-SearchCompleted'] = '搜尋完成時發送。';
$lang['eventhelp-SearchCompleted'] = '<p>搜尋完成時發送。</p>
<h4>參數</h4>
<ol>
<li>被搜尋的文字。</li>
<li>完成結果的陣列。</li>
</ol>';
$lang['eventdesc-SearchInitiated'] = '搜尋開始時發送。';
$lang['eventhelp-SearchInitiated'] = '<p>搜尋開始時發送。</p>
<h4>參數</h4>
<ol>
<li>被搜尋的文字。</li>
</ol>';
$lang['eventdesc-SearchItemAdded'] = '當一個新的項目被索引時發送。';
$lang['eventhelp-SearchItemAdded'] = '<p>當一個新的項目被索引時發送。</p>
<h4>參數</h4>
<ol>
<li>模組名稱。</li>
<li>這個項目的Id。</li>
<li>額外的屬性。</li>
<li>內容索引和添加。</li>
</ol>';
$lang['eventdesc-SearchItemDeleted'] = '當一個索引被刪除時發送。';
$lang['eventhelp-SearchItemDeleted'] = '<p>當一個索引被刪除時發送。</p>
<h4>參數</h4>
<ol>
<li>模組名稱。</li>
<li>這個項目的Id。</li>
<li>額外的屬性。</li>
</ol>';
$lang['export_to_csv'] = '匯出至 CSV';
$lang['help'] = '<h3>What does this do?</h3>
<p>Search is a module for searching &quot;core&quot; content along with certain registered modules.  You put in a word or two and it gives you back matching, relevant results.</p>
<h3>How do I use it?</h3>
<p>The easiest way to use it is with the {search} wrapper tag (wraps the module in a tag, to simplify the syntax). This will insert the module into your template or page anywhere you wish, and display the search form.  The code would look something like: <code>{search}</code></p>
<h4>How do i prevent certain content from being indexed</h4>
<p>The search module will not search any &quot;inactive&quot; pages. However on occasion, when you are using the CustomContent module, or other smarty logic to show different content to different groups of users, it may be advisiable to prevent the entire page from being indexed even when it is live.  To do this include the following tag anywhere on the page <em><!-- pageAttribute: NotSearchable --></em> When the search module sees this tag in the page it will not index any content for that page.</p>
<p>The <em><!-- pageAttribute: NotSearchable --></em> tag can be placed in the template as well.  if this is done, none of the pages attached to that template will be indexed.  Those pages will be re-indexed if the tag is removed</p>';
$lang['input_resetstopwords'] = '載入';
$lang['noresultsfound'] = '沒有資料！';
$lang['nostatistics'] = '沒有統計數據！';
$lang['options'] = '選項';
$lang['param_searchtext'] = '搜尋欄位中顯示的文字';
$lang['param_submit'] = '提交按鈕中顯示的文字';
$lang['prompt_resetstopwords'] = '從以下語言載入預設的停止詞';
$lang['prompt_resultpage'] = '單個模塊結果的頁面<em>（注意模塊可以選擇覆蓋它）</ em>';
$lang['prompt_savephrases'] = '跟踪搜尋短語，而不是單個單詞';
$lang['prompt_searchtext'] = '預設搜尋文字';
$lang['reindexallcontent'] = '重建所有內容';
$lang['reindexcomplete'] = '重新建立完成！';
$lang['restoretodefaultsmsg'] = '此操作將模板內容恢復為其係統默認值。 你確定要繼續嗎？';
$lang['resulttemplate'] = '結果模板';
$lang['resulttemplateupdated'] = '結果模板更新';
$lang['search'] = '搜尋';
$lang['searchresultsfor'] = '搜尋結果';
$lang['searchsubmit'] = '提交';
$lang['searchtemplate'] = '搜尋模板';
$lang['searchtemplateupdated'] = '搜索模板更新';
$lang['statistics'] = '統計';
$lang['stopwords'] = '停止詞';
$lang['submit'] = '提交';
$lang['sysdefaults'] = '還原為預設值';
$lang['timetaken'] = '拍攝搜尋到資料';
$lang['type_Search'] = '搜尋';
$lang['type_searchform'] = '搜尋表單';
$lang['type_searchresults'] = '搜尋結果';
$lang['usestemming'] = '使用詞幹 (只有英文)';
$lang['use_or'] = '查找與任何單詞匹配的結果';
$lang['word'] = '搜尋文字';
?>