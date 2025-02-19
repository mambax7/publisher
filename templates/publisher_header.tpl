<{if isset($collapsable_heading) && $collapsable_heading == 1}>
    <script type="text/javascript"><!--
        function goto_URL(object) {
            window.location.href = object.options[object.selectedIndex].value;
        }

        function toggle(id) {
            if (document.getElementById) {
                obj = document.getElementById(id);
            }
            if (document.all) {
                obj = document.all[id];
            }
            if (document.layers) {
                obj = document.layers[id];
            }
            if (obj) {
                if (obj.style.display == "none") {
                    obj.style.display = "";
                } else {
                    obj.style.display = "none";
                }
            }
            return false;
        }

        var iconClose = new Image();
        iconClose.src = 'assets/images/links/close12.gif';
        var iconOpen = new Image();
        iconOpen.src = 'assets/images/links/open12.gif';

        function toggleIcon(iconName) {
            if (document.images[iconName].src == window.iconOpen.src) {
                document.images[iconName].src = window.iconClose.src;
            } else if (document.images[iconName].src == window.iconClose.src) {
                document.images[iconName].src = window.iconOpen.src;
            }
        }

        //-->
    </script>
<{/if}>

<{if !empty($publisher_display_breadcrumb)}>
    <!-- Do not display breadcrumb if you are on indexpage or you do not want to display the module name -->
    <{if !empty($module_home) || !empty($categoryPath)}>
        <ul class="publisher_breadcrumb">
            <{if !empty($module_home)}>
                <li><{$module_home}></li>
            <{/if}>
            <{$categoryPath|default:''}>
        </ul><br>
    <{/if}>
<{/if}>

<{if !empty($title_and_welcome) && (isset($lang_mainintro) && $lang_mainintro  != '')}>
                <span><{$lang_mainintro}></span>
<{/if}>
