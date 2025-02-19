<{include file="db:publisher_header.tpl" item=$item}>

<div class="publisher_infotitle"><{$langIntroTitle}></div>
<div class="publisher_infotext"><{$langIntroText}></div>
<br><{$form.javascript}>
<form name="<{$form.name}>" action="<{$form.action}>" method="<{$form.method}>" <{$form.extra}>>
    <table class="outer" cellspacing="1">
        <tr>
            <th colspan="2"><{$form.title}></th>
        </tr>
        <!-- start of form elements loop -->
        <{foreach item=element from=$form.elements|default:null}>
            <{if isset($element.hidden) && $element.hidden !== true}>
                <tr>
                    <td class="head"><{$element.caption}> <{if $element.required}> *<{/if}>
                        <{if $element.description|default:''}>
                            <div style="font-weight: normal;"><{$element.description}></div>
                        <{/if}>
                    </td>
                    <td class="<{cycle values=" even,odd"}>"><{$element.body}></td>
                </tr>
            <{else}>
                <{$element.body}>
            <{/if}>
        <{/foreach}><!-- end of form elements loop -->
    </table>
</form>

<{if $isAdmin|default:0 == 1}>
    <div class="publisher_adminlinks"><{$publisher_adminpage}></div>
<{/if}>
