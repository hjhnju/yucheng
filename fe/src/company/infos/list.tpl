<!--
 * @ignore
 * @file list
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 15-1-3
-->
<!-- target: infosList -->
<ul class="infos-list">
    <!-- for: ${list} as ${item} -->
    <li class="infos-item">
        <span class="infos-dot">•</span>
        <div class="infos-box">
            <div class="infos-title">
                <a href="/infos/post/detail?id=${item.id}">${item.title}</a>
            </div>
            <div class="infos-ctx">
                ${item.content}
            </div>
        </div>
    </li>
    <!-- /for -->
</ul>
<!-- /target -->