<!--
 * @ignore
 * @file list
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 14-12-27
-->

<!-- target: msgList -->
<ul>
    <li class="my-invest-header">
        <span class="my-invest-title msg-type">消息类型</span>
        <span class="my-invest-title msg-content">内容</span>
        <span class="my-invest-title msg-time">发送时间</span>
    </li>
    <!-- for: ${list} as ${item} -->
    <!-- if: ${item.status} == 1 -->
    <li class="my-invest-item">
    <!-- else -->
    <li class="my-invest-item unread">
    <!-- /if -->
        <div class="my-invest-content">
            <span class="my-invest-project msg-type">${item.type}</span>
            <span class="my-invest-project msg-content"><span class="msg-content-text" data-id="${item.mid}">${item.content}</span></span>
            <span class="my-invest-project msg-time">${item.timeInfo} <span class="del-msg">╳</span></span>
        </div>
        <div class="my-invest-detail my-msg-detail">
            <span class="close-detail">收起</span>
            <p>${item.content}</p>
            <div>查看 <a href="${item.link}">${item.linkname}</a></div>
        </div>
    </li>
    <!-- /for -->
</ul>
<!-- /target -->
