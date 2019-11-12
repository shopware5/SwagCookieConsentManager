{extends file='parent:frontend/_includes/cookie_permission_note.tpl'}
{namespace name="frontend/cookiepermission/index"}

{block name="cookie_permission_content"}
    <div class="cookie-permission--content{if {config name="cookie_note_mode"} == 1 && {config name="cookie_show_button"}} cookie-permission--extra-button{/if}">
        {block name="cookie_permission_content_text"}
            {if {config name="cookie_note_mode"} == 1}
                {s name="cookiePermission/textMode1"}{/s}
            {else}
                {s name="cookiePermission/text"}{/s}
            {/if}
        {/block}

        {block name="cookie_permission_content_link"}
            {$privacyLink = {config name="data_privacy_statement_link"}}
            {if $privacyLink}
                <a title="{s name="cookiePermission/linkText"}{/s}"
                   class="cookie-permission--privacy-link"
                   href="{$privacyLink}">
                    {s name="cookiePermission/linkText"}{/s}
                </a>
            {/if}
        {/block}
    </div>
{/block}

{block name="cookie_permission_accept_button"}
    <div class="cookie-permission--button{if {config name="cookie_note_mode"} == 1 && {config name="cookie_show_button"}} cookie-permission--extra-button{/if}">
        {block name="cookie_permission_decline_button_fixed"}
            {if {config name="cookie_note_mode"} == 1}
                {block name="cookie_permission_decline_button"}
                    <a href="#" class="cookie-permission--decline-button btn is--large is--center">
                        {s name="cookiePermission/declineText"}{/s}
                    </a>
                {/block}
            {/if}
        {/block}

        {block name="cookie_permission_accept_button_fixed"}
            {if {config name="cookie_note_mode"} == 1}
                {if {config name="cookie_show_button"}}
                    <a href="#" class="cookie-permission--accept-button btn is--large is--center">
                        {s name="cookiePermission/acceptAll"}{/s}
                    </a>
                {/if}

                <a href="#" class="cookie-permission--configure-button btn is--primary is--large is--center" data-openConsentManager="true">
                    {s name="cookiePermission/configure"}{/s}
                </a>
            {else}
                <a href="#" class="cookie-permission--accept-button btn is--primary is--large is--center">
                    {s name="cookiePermission/buttonText"}{/s}
                </a>
            {/if}
        {/block}
    </div>
{/block}