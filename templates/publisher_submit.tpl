<{include file="db:publisher_header.tpl" item=$item|default:false}>

<{if isset($op) && $op === 'preview'}>
    <br>
    <{include file="db:publisher_singleitem.tpl" item=$item}>
<{/if}>

<div class="publisher_infotitle"><{if !empty($langIntroTitle)}><{$langIntroTitle}><{/if}></div>
<div class="publisher_infotext"><{if !empty($langIntroText)}><{$langIntroText}><{/if}></div>
<br>
<{$form.javascript}>

<div id="tabs">
    <ul>
        <{foreach item=tab from=$form.tabs|default:null key=key }>
            <li><a href="#tab_<{$key}>"><span><{$tab}></span></a></li>
        <{/foreach}>
    </ul>

    <form name="<{$form.name}>" action="<{$form.action}>" method="<{$form.method}>"<{$form.extra}>>
        <!-- start of form elements loop -->
        <{foreach item=tab from=$form.tabs|default:null key=key }>
            <div id="tab_<{$key}>">
                <table class="outer" cellspacing="1">
                    <{foreach item=element from=$form.elements|default:null}>
                    <{if $element.tab|default:'' == $key || $element.tab|default:0 == -1}>
                        <{if empty($element.hidden)}>
                            <tr>
                                <td class="head" width="30%">
                                    <{if isset($element.caption) && $element.caption != ''}>
                                        <div class='xoops-form-element-caption<{if $element.required}>-required<{/if}>'>
                                            <span class='caption-text'><{$element.caption}></span>
                                            <{if $element.required}>
                                                <span class='caption-marker'>*</span>
                                            <{/if}>
                                        </div>
                                    <{/if}> 
                                    <{if isset($element.description) && $element.description == true}>
                                        <div style="font-weight: normal; font-size:small;"><{$element.description}></div>
                                    <{/if}>
                                </td>
                                <td class="<{cycle values=" even,odd"}>"><{$element.body}></td>
                            </tr>
                        <{/if}>
                    <{/if}>
                    <{/foreach}><!-- end of form elements loop -->
                </table>
            </div>
        <{/foreach}>
        <{foreach item=element from=$form.elements|default:null}>
            <{if isset($element.hidden) && $element.hidden == true}>
                <{$element.body}>
            <{/if}>
        <{/foreach}>
    </form>
</div>

<{if isset($isAdmin) && $isAdmin == 1}>
    <div class="publisher_adminlinks"><{$publisher_adminpage}></div>
<{/if}>
