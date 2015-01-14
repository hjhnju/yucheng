<!--
 * @ignore
 * @file dialog
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 15-1-2
-->

<!-- target: dialogWarp -->
<div id="mk-dialog">
    <div class="bg" style="width:${width}px;">
        <!-- if: ${defaultTitle}-->
        <div class="title"><span>${title}</span></div>
        <!-- /if -->
        <a id="popup-close" class="close" title="关闭">╳</a>
        ${content|raw}
    </div>
</div>
<!-- if: ${mask} -->
<div id="mk-dialog-mask" class="mask"></div>
<!-- else -->
<div id="mk-dialog-mask"></div>
<!-- /if -->
