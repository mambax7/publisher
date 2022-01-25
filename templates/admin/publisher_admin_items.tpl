<{if $itemsRows > 0}>
    <div class="outer">
        <form name="select" action="item.php?op=" method="POST"
              onsubmit="if(window.document.select.op.value =='') {return false;} else if (window.document.select.op.value =='delete') {return deleteSubmitValid('itemsId[]');} else if (isOneChecked('itemsId[]')) {return true;} else {alert('<{$smarty.const.AM_PUBLISHER_SELECTED_ERROR}>'); return false;}">
            <input type="hidden" name="confirm" value="1">
            <div class="floatleft">
                <select name="op">
                    <option value=""><{$smarty.const.AM_PUBLISHER_SELECT}></option>
                    <option value="delete"><{$smarty.const.AM_PUBLISHER_SELECTED_DELETE}></option>
                </select>
                <input id="submitUp" class="formButton" type="submit" name="submitselect" value="<{$smarty.const._SUBMIT}>" title="<{$smarty.const._SUBMIT}>">
            </div>
            <div class="floatcenter0">
                <div id="pagenav"><{$pagenav|default:''}></div>
            </div>


            <{*<table class="$listing" cellpadding="0" cellspacing="0" width="100%">*}>


            <!-- pager -->
            <div id="pager" class="pager">
                <form>
                    <img src="<{$mod_url}>/assets/js/tablesorter/css/images/first.png" class="first"/>
                    <img src="<{$mod_url}>/assets/js/tablesorter/css/images/prev.png" class="prev"/>
                    <!-- the "pagedisplay" can be any element, including an input -->
                    <span class="pagedisplay" data-pager-output-filtered="{startRow:input} &ndash;{endRow} / {filteredRows} of {totalRows} total rows"></span>
                    <img src="<{$mod_url}>/assets/js/tablesorter/css/images/next.png" class="next"/>
                    <img src="<{$mod_url}>/assets/js/tablesorter/css/images/last.png" class="last"/>
                    <select class="pagesize">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="all">All Rows</option>
                    </select>
                </form>
            </div>

            <table id="sortTable" class="tablesorter-blue" cellspacing="1" cellpadding="0" width="100%">
                <thead>
                <tr>
                    <th align="center" width="5%"><input name="allbox" title="allbox" id="allbox" onclick="xoopsCheckAll('select', 'allbox');" type="checkbox" title="Check All" value="Check All"></th>
                    <th class="left"><{$selectoritemid}></th>
                    <th class="left"><{$selectorcategoryid}></th>
                    <th class="left"><{$selectortitle}></th>
<{*                    <th class="left"><{$selectorsubtitle}></th>*}>
<{*                    <th class="left"><{$selectorsummary}></th>*}>
<{*                    <th class="left"><{$selectorbody}></th>*}>
                    <th class="left"><{$selectoruid}></th>
<{*                    <th class="left"><{$selectorauthor_alias}></th>*}>
                    <th class="left"><{$selectordatesub}></th>
                    <th class="left"><{$selectorstatus}></th>
<{*                    <th class="left"><{$selectorimage}></th>*}>
<{*                    <th class="left"><{$selectorimages}></th>*}>
                    <th class="left"><{$selectorcounter}></th>
<{*                    <th class="left"><{$selectorrating}></th>*}>
<{*                    <th class="left"><{$selectorvotes}></th>*}>
<{*                    <th class="left"><{$selectorweight}></th>*}>
<{*                    <th class="left"><{$selectordohtml}></th>*}>
<{*                    <th class="left"><{$selectordosmiley}></th>*}>
<{*                    <th class="left"><{$selectordoxcode}></th>*}>
<{*                    <th class="left"><{$selectordoimage}></th>*}>
<{*                    <th class="left"><{$selectordobr}></th>*}>
<{*                    <th class="left"><{$selectorcancomment}></th>*}>
<{*                    <th class="left"><{$selectorcomments}></th>*}>
<{*                    <th class="left"><{$selectornotifypub}></th>*}>
<{*                    <th class="left"><{$selectormeta_keywords}></th>*}>
<{*                    <th class="left"><{$selectormeta_description}></th>*}>
<{*                    <th class="left"><{$selectorshort_url}></th>*}>
<{*                    <th class="left"><{$selectoritem_tag}></th>*}>

                    <th class="center width5"><{$smarty.const.AM_PUBLISHER_FORM_ACTION}></th>
                </tr>

                </thead>

                <tbody>

                <{foreach item=itemsArray from=$itemsArrays}>
                    <tr class="<{cycle values="odd,even"}>">

                        <td align="center" style="vertical-align:middle;"><input type="checkbox" name="items_id[]" title="items_id[]" id="items_id[]" value="<{$itemsArray.items_id|default:''}>"></td>
                        <td class='left'><{$itemsArray.itemid}></td>
                        <td class='left'><{$itemsArray.categoryid}></td>
                        <td class='left'><{$itemsArray.title}></td>
<{*                        <td class='left'><{$itemsArray.subtitle}></td>*}>
<{*                        <td class='left'><{$itemsArray.summary}></td>*}>
<{*                        <td class='left'><{$itemsArray.body}></td>*}>
                        <td class='left'><{$itemsArray.uid}></td>
<{*                        <td class='left'><{$itemsArray.author_alias}></td>*}>
                        <td class='left'><{$itemsArray.datesub}></td>
                        <td class='left'><{$itemsArray.status}></td>
<{*                        <td class='left'><{$itemsArray.image}></td>*}>
<{*                        <td class='left'><{$itemsArray.images}></td>*}>
                        <td class='left'><{$itemsArray.counter}></td>
<{*                        <td class='left'><{$itemsArray.rating}></td>*}>
<{*                        <td class='left'><{$itemsArray.votes}></td>*}>
<{*                        <td class='left'><{$itemsArray.weight}></td>*}>
<{*                        <td class='left'><{$itemsArray.dohtml}></td>*}>
<{*                        <td class='left'><{$itemsArray.dosmiley}></td>*}>
<{*                        <td class='left'><{$itemsArray.doxcode}></td>*}>
<{*                        <td class='left'><{$itemsArray.doimage}></td>*}>
<{*                        <td class='left'><{$itemsArray.dobr}></td>*}>
<{*                        <td class='left'><{$itemsArray.cancomment}></td>*}>
<{*                        <td class='left'><{$itemsArray.comments}></td>*}>
<{*                        <td class='left'><{$itemsArray.notifypub}></td>*}>
<{*                        <td class='left'><{$itemsArray.meta_keywords}></td>*}>
<{*                        <td class='left'><{$itemsArray.meta_description}></td>*}>
<{*                        <td class='left'><{$itemsArray.short_url}></td>*}>
<{*                        <td class='left'><{$itemsArray.item_tag}></td>*}>


                        <td class="center width5"><{$itemsArray.edit_delete}></td>
                    </tr>
                <{/foreach}>

                </tbody>

            </table>

            <!-- pager -->
            <div id="pager" class="pager">
                <form>
                    <img src="<{$mod_url}>/assets/js/tablesorter/css/images/first.png" class="first"/>
                    <img src="<{$mod_url}>/assets/js/tablesorter/css/images/prev.png" class="prev"/>
                    <!-- the "pagedisplay" can be any element, including an input -->
                    <span class="pagedisplay" data-pager-output-filtered="{startRow:input} &ndash;{endRow} / {filteredRows} of {totalRows} total rows"></span>
                    <img src="<{$mod_url}>/assets/js/tablesorter/css/images/next.png" class="next"/>
                    <img src="<{$mod_url}>/assets/js/tablesorter/css/images/last.png" class="last"/>
                    <select class="pagesize">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="all">All Rows</option>
                    </select>
                </form>
            </div>


            <br>
            <br>
    </div>
    <br>
    <br>
<{/if}>
