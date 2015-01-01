<!--target: list-->
<table border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr class="color-hui">
        <td>成交时间</td>
        <td>投资人</td>
        <td>投资金额</td>
    </tr>
    <!--for: ${list} as ${item}-->
    <tr>
        <td>${item.timeInfo}</td>
        <td>${item.name}</td>
        <td>￥${item.amount}</td>
    </tr>
    <!--/for-->
</table>

<!-- target: startTime -->
<!-- /target -->

<!-- target: leftTime -->
${d}天${h}小时${M}分
<!-- /target -->

<!-- target: over -->
<!-- /target -->