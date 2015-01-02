<!--target: typeList-->
<table border="0" cellspacing="0" cellpadding="0" class="my-invest-table operation-table">
    <tr>
        <th>时间</th>
        <th>交易类型</th>
        <th>交易流水号</th>
        <th>金额</th>
        <th>可用余额</th>
    </tr>
    <!-- for: ${list} as ${item} -->
    <tr>
        <td>${item.time}</td>
        <!--if: ${item.transType} == 1-->
        <td>充值</td>
        <!-- else -->
        <td>提现</td>
        <!-- /if -->

        <td>${item.serialNo}</td>
        <td>${item.tranAmt}元</td>
        <td>${item.avalBg}元</td>
    </tr>
    <!--/for-->
</table>