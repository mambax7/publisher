<table class"table" cellpadding="0" cellspacing="0" border="0">

<{if !empty($block.newitems)}>
    <{foreach item=newitems from=$block.newitems|default:null}>
        <tr class="<{cycle values=" even,odd"}>">
            <{if $newitems.image|default:false}>
                <td style="padding: 5px 0;" width="120px">
                    <a href="<{$item.itemurl}>"><img style="padding: 1px; margin: 2px; border: 1px solid #c3c3c3;" width="110" src="<{$newitems.image}>" title="<{$newitems.alt}>"
                                                     alt="<{$newitems.alt}>"></a>
                </td>
            <{/if}>
            <td>
                <strong><{$newitems.link}></strong>
                <{if $block.show_order|default:'' == '1'}>
                    (<{$newitems.new}>)
                <{/if}>
                <br>
                <{if $block.show_summary|default:'' == '1'}><{$newitems.summary}><br><{/if}>
                <{if $block.show_category|default:'' == '1'}>
                    <span style="padding: 3px 16px 0 0; font-size: 11px;"><span class="fa fa-tag"></span>&nbsp;<{$newitems.categorylink}></span>
                <{/if}>
                <{if $block.show_poster|default:'' == '1'}>
                    <span style="padding: 3px 16px 0 0; font-size: 11px;"><span class="fa fa-user"></span>&nbsp;<{$newitems.poster}></span>
                <{/if}>
                <{if $block.show_date|default:'' == '1'}>
                    <span style="padding: 3px 16px 0 0; font-size: 11px;"><span class="fa fa-calendar"></span>&nbsp;<{$newitems.date}></span>
                <{/if}>
                <{if $block.show_hits|default:'' == '1'}>
                    <span style="padding: 3px 16px 0 0; font-size: 11px;"><span class="fa fa-check-circle-o"></span>&nbsp;<{$newitems.hits}> <{$newitems.lang_hits}> </span>
                <{/if}>
                <{if (isset($block.show_comment) && $block.show_comment === '1') && !empty($newitems.cancomment) && (isset($newitems.comment) && $newitems.comment != -1)}>
                    <span style="padding: 3px 16px 0 0; font-size: 11px;"><span class="fa fa-comment"></span>&nbsp;<{$newitems.comment}></span>
                <{/if}>
                <{if $block.show_rating|default:'' == '1'}>
                    <span style="padding: 3px 16px 0 0; font-size: 11px;"><span class="fa fa-star"></span>&nbsp;<{$newitems.rating}></span>
                <{/if}>
            </td>
        </tr>
    <{/foreach}>
<{/if}>
</table>
