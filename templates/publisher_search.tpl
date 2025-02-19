<{if $search_info|default:false}>
    <div class="resultMsg"> <{$search_info}></div>
    <{if $results|default:false}>
        <{foreach item=result from=$results|default:null}>
            <div class="item">
                <strong><a href="<{$result.link}>"><{$result.title}></a></strong><br>
                <{$result.author}> <{$result.datesub}>
                <{if $result.text|default:false}>
                    <br>
                    <{$result.text}>
                <{/if}>
            </div>
            <div class="clear"></div>
        <{/foreach}>
    <{/if}>
<{/if}>

<form name="search" action="search.php" method="post">
    <table class="outer" border="0" cellpadding="1" cellspacing="0" align="center" width="95%">
        <tr>
            <td>
                <table border="0" cellpadding="1" cellspacing="1" width="100%" class="head">
                    <tr>
                        <td class="head" width="10%" align="right">
                            <strong><{$smarty.const._SR_KEYWORDS}></strong></td>
                        <td class="even">
                            <input type="text" name="term" title="term" value="<{$search_term}>" size="50">
                        </td>
                    </tr>
                    <tr>
                        <td class="head" align="right">
                            <strong><{$smarty.const._SR_TYPE}></strong></td>
                        <td class="even"><{$type_select}></td>
                    </tr>
                    <tr>
                        <td class="head" align="right">
                            <strong><{$smarty.const._CO_PUBLISHER_CATEGORY}></strong>
                        </td>
                        <td class="even"><{$category_select}></td>
                    </tr>
                    <tr>
                        <td class="head" align="right">
                            <strong><{$smarty.const._SR_SEARCHIN}></strong></td>
                        <td class="even"><{$searchin_select}></td>
                    </tr>
                    <tr>
                        <td class="head" align="right">
                            <strong><{$smarty.const._CO_PUBLISHER_UID}></strong>&nbsp;
                        </td>
                        <td class="even">
                            <input type="text" name="uname" title="uname" value="<{$search_user}>">
                        </td>
                    </tr>
                    <tr>
                        <td class="head" align="right">
                            <strong><{$smarty.const._CO_PUBLISHER_SORTBY}></strong>&nbsp;
                        </td>
                        <td class="even"><{$sortby_select}></td>
                    </tr>
                    <{if !empty($search_rule)}>
                        <tr>
                            <td class="head" align="right">
                                <strong><{$smarty.const._SR_SEARCHRULE}></strong>&nbsp;
                            </td>
                            <td class="even"><{$search_rule}></td>
                        </tr>
                    <{/if}>
                    <tr>
                        <td class="head" align="right">&nbsp;</td>
                        <td class="even">
                            <input type="submit" name="submit" value="<{$smarty.const._SUBMIT}>">&nbsp;
                            <input type="reset" name="cancel" value="<{$smarty.const._CANCEL}>">
                        </td>
                </table>
            </td>
        </tr>
    </table>
</form><!-- end module contents -->
