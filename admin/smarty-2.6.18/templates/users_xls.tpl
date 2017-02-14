{include file="header.tpl" top_panes=true}
{pane title="Скачать данные перевозчиков в Excel" tab=3}
    <p>Пожалуйста, сохраните файл...</p>
    <p>Если диалог сохранения файла не открылся, воспользуйтесь <a href="{$hidden_arc}">этой ссылкой</a></p>
    <div style="position: absolute; z-index: 1; left: -10; top: -10; display: none">
        <iframe src="{$hidden_arc|escape:'html'}" width="1" height="1"></iframe>
    </div>
{/pane}
{include file="footer.tpl" bottom_panes=true}
