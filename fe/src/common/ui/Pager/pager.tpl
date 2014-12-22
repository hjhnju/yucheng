<!-- target: ui-pager -->
<table class="ui-pager" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="ui-pager-box">
                <!-- if: ${data.hasprev} -->
                <a href="###" class="ui-pager-item ui-pager-left" data-value="${data.prev}">${data.prevText}</a>
                <!-- /if -->
                <!-- for: ${data.pages} as ${item}, ${idx} -->
                    <!-- if: ${idx} == ${data.currentPage} -->
                    <a href="###" class="ui-pager-item ui-pager-select current" data-value="${item.index}">${item.value}</a>
                    <!-- else -->
                    <a href="###" class="ui-pager-item ui-pager-select" data-value="${item.index}">${item.value}</a>
                    <!-- /if -->
                <!-- /for -->
                <!-- if: ${data.hasnext} -->
                <a href="###" class="ui-pager-item ui-pager-right" data-value="${data.next}">${data.nextText}</a>
                <!-- /if -->
            </div>
        </td>
    </tr>
</table>
