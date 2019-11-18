{extends file="parent:frontend/index/index.tpl"}

{block name='frontend_index_content_wrapper'}
    {$smarty.block.parent}
    {include file='frontend/index/cookie_consent.tpl'}
{/block}

{block name="frontend_index_header_javascript"}
    {if {config name="show_cookie_note"}}
        {include file="frontend/_includes/cookie_permission_note.tpl"}
    {/if}

	{$smarty.block.parent}
{/block}

{block name="frontend_index_header_javascript_inline"}
    {$smarty.block.parent}

    var cookieRemoval = cookieRemoval || {config name="cookie_note_mode"};
{/block}