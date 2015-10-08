{if isset($menu)}
    <div id="menu_container">
        <nav id="menu">{$menu}</nav>
    </div>
{/if}
{if isset($header)}
    <div id="header_container">
        <header id="header">{$header}</header>
    </div>
{/if}
{if isset($content)}
    <div id="content_container">
        <aside id="content">{$content}</aside>
    </div>
{/if}
{if isset($footer)}
    <div id="footer_container">
        <footer id="footer">{$footer}</footer>
    </div>
{/if}
<div id="dialog_container"></div>
<div id="loading"></div>