<!--
 * @ignore
 * @file list
 * @author hejunhua
 * @time 15-03-08
-->

<!-- target: returnMoneyList -->
<ul>
<li class="my-loan-header">
    <span class="my-loan-title loan-name">投资项目</span>
    <span class="my-loan-title loan-balance">年化利率</span>
    <span class="my-loan-title loan-money">投标金额</span>
    <span class="my-loan-title loan-time">投标时间</span>
    <span class="my-loan-title loan-finish">已回款</span>
    <span class="my-loan-title loan-wait">待回款</span>
    <span class="my-loan-title loan-op"></span>
</li>
<!-- for: ${list} as ${item} -->
<li class="my-loan-item">
    <div class="my-loan-content">
        <a href="/loan/detail/index?id=${item.proId}" class="my-loan-project loan-name">${item.loanPro}</a>
        <span class="my-loan-project loan-balance"><span class="loan-em">${item.annlnterestRate}</span>%</span>
        <span class="my-loan-project loan-money">￥${item.tenderAmt}</span>
        <span class="my-loan-project loan-time">${item.timeInfo}</span>
        <span class="my-loan-project loan-finish">￥${item.haveBack}</span>
        <span class="my-loan-project loan-wait">￥${item.toBeBack}</span>
        <span class="my-loan-project view-plan"><span class="view-plan-btn" data-id="${item.loan_id}">查看还款计划</span></span>
    </div>
    <div class="my-loan-detail">
        <span class="trangle-border"></span>
        <span class="trangle-content"></span>
    </div>
</li>
<!-- /for -->
</ul>
<!-- /target -->

<!-- target: returnMoneyDetail -->
<span class="trangle-border"></span>
<span class="trangle-content"></span>
<h2 class="my-loan-plan-title">还款计划（投资人：${data.loaner}）</h2>
<table border="0" cellspacing="0" cellpadding="0">
    <tr>
        <th>时间</th>
        <th>待收本金</th>
        <th>待收收益</th>
        <th>已收本金</th>
        <th>已收收益</th>
        <th>还款状态</th>
        <th>罚息</th>
    </tr>
    <!-- for: ${data.list} as ${item} -->
    <tr>
        <td>${item.timeInfo}</td>
        <td>${item.repossPrincipal}</td>
        <td>${item.repossProfit}</td>
        <!-- if: ${item.paymentStatus} == 0 -->
        <td>${item.recePrincipal}</td>
        <td>${item.receProfit}</td>
        <td>未到期</td>
        <td>${item.punitive}</td>
        <!-- elif: ${item.paymentStatus} == 1 -->
        <td><span class="loan-pass">${item.recePrincipal}</span></td>
        <td><span class="loan-pass">${item.receProfit}</span></td>
        <td>按时还款</td>
        <td><span class="loan-pass">${item.punitive}</span></td>
        <!-- else -->
        <td><span class="loan-em">${item.recePrincipal}</span></td>
        <td><span class="loan-em">${item.receProfit}</span></td>
        <td>已逾期</td>
        <td><span class="loan-em">${item.punitive}</span></td>
        <!-- /if -->
    </tr>
    <!-- /for -->
    <tr class="my-loan-plan-all">
        <td>总计</td>
        <td>${data.total.repossPrincipal}</td>
        <td>${data.total.repossProfit}</td>
        <td>${data.total.recePrincipal}</td>
        <td>${data.total.receProfit}</td>
        <td></td>
        <td>${data.total.punitive}</td>
    </tr>
</table>
<!-- /target -->

<!-- target: tenderingList -->
<table border="0" cellspacing="0" cellpadding="0" class="my-loan-table">
    <tr>
        <th>项目名称</th>
        <th>年利率</th>
        <th>投资金额</th>
        <th>期限</th>
        <th>投标时间</th>
        <th>进度</th>
    </tr>
    <!-- for: ${list} as ${item} -->
    <tr>
        <td><a href="/loan/detail/index?id=${item.proId}" class="loan-name">${item.loanPro}</a></td>
        <td><span class="loan-em">${item.annlnterestRate}</span>%</td>
        <td>￥${item.tenderAmt}</td>
        <td>${item.deadline}</td>
        <td>${item.timeInfo}</td>
        <td>
            <span class="span-box"><span class="within" style="width: ${item.tenderProgress}%;"></span></span>
            <span class="loan-em">${item.tenderProgress}</span>%
        </td>
    </tr>
    <!-- /for -->
</table>
<!-- /target -->

<!-- target: endedList -->
<table border="0" cellspacing="0" cellpadding="0" class="my-loan-table">
    <tr>
        <th>项目名称</th>
        <th>年利率</th>
        <th>投资金额</th>
        <th>期限</th>
        <th>投标时间</th>
        <th>合同结束时间</th>
        <th>总回款</th>
        <th>总收益</th>
    </tr>
    <!-- for: ${list} as ${item} -->
    <tr>
        <td><a href="/loan/detail/index?id=${item.proId}" class="loan-name">${item.loanPro}</a></td>
        <td><span class="loan-em">${item.annlnterestRate}</span>%</td>
        <td>￥${item.tenderAmt}</td>
        <td>${item.deadline}</td>
        <td>${item.timeInfo}</td>
        <td>${item.endTimeInfo}</td>
        <td>￥${item.totalRetAmt}</td>
        <td><span class="loan-em">￥${item.totalProfit}</span></td>
    </tr>
    <!-- /for -->
</table>
<!-- /target -->

<!-- target: tenderFailList -->
<table border="0" cellspacing="0" cellpadding="0" class="my-loan-table">
    <tr>
        <th>项目名称</th>
        <th>年利率</th>
        <th>投资金额</th>
        <th>期限</th>
        <th>投标时间</th>
        <th>失败原因</th>
    </tr>
    <!-- for: ${list} as ${item} -->
    <tr>
        <td><a href="/loan/detail/index?id=${item.proId}" class="loan-name">${item.loanPro}</a></td>
        <td><span class="loan-em">${item.annlnterestRate}</span>%</td>
        <td>￥${item.tenderAmt}</td>
        <td>${item.deadline}</td>
        <td>${item.timeInfo}</td>
        <td>${item.failReason}</td>
    </tr>
    <!-- /for -->
</table>
<!-- /target -->