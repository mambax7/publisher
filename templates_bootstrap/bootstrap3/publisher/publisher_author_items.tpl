<h2><{$smarty.const._MD_PUBLISHER_ITEMS_SAME_AUTHOR}> <{$author_name_with_link}></h2>
<br><img src='<{$user_avatarurl}>' border='0' alt='' class="img-circle"><br><br>
<div class="table-responsive">
<table class="table table-hover">

    <{if isset($total_items) && $total_items === 0}>
        <tr>
            <td><{$smarty.const._MD_PUBLISHER_NO_AUTHOR_ITEMS}></td>
        </tr>
    <{/if}>

    <{foreach item=category from=$categories|default:null}>
        <tr>
            <{if $permRating|default:false && $displayrating|default:false}>
                <th colspan='4'>
            <{else}>
                <th colspan='3'>
            <{/if}> <{$category.link}>
            </th>
        </tr>
        <tr>
            <td class="bold"><{$smarty.const._CO_PUBLISHER_DATESUB}></td>
            <td class="bold">&nbsp;<{$smarty.const._CO_PUBLISHER_TITLE}></td>
            <{if $displayhits|default:false}>
            <td class="bold" align='right'><{$smarty.const._MD_PUBLISHER_HITS}></td>
            <{/if}>
            <{if $permRating|default:false && $displayrating|default:false}>
                <td class="bold" align='right'>&nbsp;&nbsp;&nbsp;<{$smarty.const._MD_PUBLISHER_VOTE_RATING}></td>
            <{/if}>          
        </tr>
        <{foreach item=item from=$category.items|default:null}>
            <tr>
                <td><{$item.published}></td>
                <td>
                <{if $displaymainimage|default:false}>
                <a href="<{$item.itemurl}>"><img src="<{$item.image}>" alt="<{$item.cleantitle}>" title="<{$item.cleantitle}>" align="left"></a>
                <{/if}>
                &nbsp;&nbsp;<{$item.link}>
                <{if $displaysummary|default:false}>
                <br>&nbsp;&nbsp;<{$item.summary}>
                <{/if}>
                <{if $displaycomment|default:false && $item.cancomment|default:false && $item.comment|default:0 != -1}>
                <br>
                <span style="font-size: 11px; padding: 0 0 0 16px; margin: 0; line-height: 12px; opacity:0.8;-moz-opacity:0.8;">
                &nbsp;&nbsp;<span class="glyphicon glyphicon-comment"></span>&nbsp;<{$item.comment}>
                </span>
                <{/if}>
                </td>
                <{if $displayhits|default:false}>
                <td align='right'><{$item.hits}>  </td>
                <{/if}>
                <{if $permRating|default:false && $displayrating|default:false}>
                    <td align='right'>&nbsp;&nbsp;<{$item.rating}></td>
                <{/if}>
            </tr>
        <{/foreach}>
        <tr>
            <td colspan='2' align='left'><br><{$smarty.const._MD_PUBLISHER_TOTAL_ITEMS}><{$category.count_items}></td>
            <{if $displayhits|default:false}>
            <td align='right'><br><{$smarty.const._MD_PUBLISHER_TOTAL_HITS}><{$category.count_hits}></td>
            <{/if}>
            <{if $permRating|default:false && $displayrating|default:false}>
                <td>&nbsp;</td>
            <{/if}>
        </tr>
        <tr>
            <{if $permRating|default:false && $displayrating|default:false}>
                <td colspan='4'>
            <{else}>
                <td colspan='3'>
            <{/if}> &nbsp;
            </td>
        </tr>
    <{/foreach}>
</table>
</div>
