<{foreach item=item from=$block.items|default:null}>
    <{if isset($item.display_item_image) && $item.display_item_image === '1'}>
        <a href="<{$item.url}>"><img class="img-fluid" src="<{$item.image_path}>" alt="<{$item.alt}>" title="<{$item.alt}>" ></a>
    <{/if}>
    <{$item.titlelink}><br>
    <{if isset($item.display_summary) && $item.display_summary === '1'}>
        <{$item.content}><br>
    <{/if}>
    <{if isset($item.display_categorylink) && $item.display_categorylink === '1'}>
        <span style="font-size: 11px; padding: 0; margin: 0; line-height: 12px; opacity:0.8;-moz-opacity:0.8;">
                    <span class="fa fa-tag"></span>&nbsp;<{$item.categorylink}>
                </span>
    <{/if}>
    <{if isset($item.display_poster) && $item.display_poster === '1'}>
        <span style="font-size: 11px; padding: 0 0 0 16px; margin: 0; line-height: 12px; opacity:0.8;-moz-opacity:0.8;">
                    <span class="fa fa-user"></span>&nbsp;<{$item.poster}>
                </span>
    <{/if}>
    <{if isset($item.display_date) && $item.display_date === '1'}>
        <span style="font-size: 11px; padding: 0 0 0 16px; margin: 0; line-height: 12px; opacity:0.8;-moz-opacity:0.8;">
                    <span class="fa fa-calendar"></span>&nbsp;<{$item.date}>
                </span>
    <{/if}>
    <{if $item.display_comment|default:'' == '1' && $item.cancomment|default:false && $item.comment|default:0 != -1}>
        <span style="font-size: 11px; padding: 0 0 0 16px; margin: 0; line-height: 12px; opacity:0.8;-moz-opacity:0.8;">
                    <span class="fa fa-comment"></span>&nbsp;<{$item.comment}>
                </span>
    <{/if}>
    <{if isset($item.display_hits) && $item.display_hits === '1'}>
        <span style="font-size: 11px; padding: 0 0 0 16px; margin: 0; line-height: 12px; opacity:0.8;-moz-opacity:0.8;">
                    <span class="fa fa-check-circle-o"></span>&nbsp;<{$item.hits}>
                </span>
    <{/if}>


    <{if $item.display_lang_fullitem|default:'' == '1'}>
        <div align="right" style="padding: 15px 0 0 0;">
            <a class="btn btn-primary btn-xs" href='<{$item.url}>'><{$item.lang_fullitem}></a>
        </div>
    <{/if}>
<{/foreach}>
