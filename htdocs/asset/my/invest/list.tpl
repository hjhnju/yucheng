<!--
 * @ignore
 * @file list
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 14-12-22
-->

<!-- target: returnMoneyList -->
<ul>
<li class="my-invest-header">
    <span class="my-invest-title invest-name">投资项目</span>
    <span class="my-invest-title invest-balance">年化利率</span>
    <span class="my-invest-title invest-money">投标金额</span>
    <span class="my-invest-title invest-time">投标时间</span>
    <span class="my-invest-title invest-finish">已回款</span>
    <span class="my-invest-title invest-wait">待回款</span>
    <span class="my-invest-title invest-op"></span>
</li>
<!-- for: ${list} as ${item} -->
<li class="my-invest-item">
    <div class="my-invest-content">
        <span class="my-invest-project invest-name">${item.investPro}</span>
        <span class="my-invest-project invest-balance"><span class="invest-em">${item.annlnterestRate}</span>%</span>
        <span class="my-invest-project invest-money">￥${item.tenderAmt}</span>
        <span class="my-invest-project invest-time">${item.timeInfo}</span>
        <span class="my-invest-project invest-finish">￥${item.haveBack}</span>
        <span class="my-invest-project invest-wait">￥${item.toBeBack}</span>
        <span class="my-invest-project view-plan"><span class="view-plan-btn" data-id="${item.proId}">查看还款计划</span></span>
    </div>
    <div class="my-invest-detail">
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
<h2 class="my-invest-plan-title">还款计划（投资人：${data.invester}）</h2>
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
        <td><span class="invest-pass">${item.recePrincipal}</span></td>
        <td><span class="invest-pass">${item.receProfit}</span></td>
        <td>按时还款</td>
        <td><span class="invest-pass">${item.punitive}</span></td>
        <!-- else -->
        <td><span class="invest-em">${item.recePrincipal}</span></td>
        <td><span class="invest-em">${item.receProfit}</span></td>
        <td>已逾期</td>
        <td><span class="invest-em">${item.punitive}</span></td>
        <!-- /if -->
    </tr>
    <!-- /for -->
    <tr class="my-invest-plan-all">
        <td>总计</td>
        <td>${data.all.repossPrincipal}</td>
        <td>${data.all.repossProfit}</td>
        <td>${data.all.recePrincipal}</td>
        <td>${data.all.receProfit}</td>
        <td></td>
        <td>${data.all.punitive}</td>
    </tr>
</table>
<!-- /target -->

<!-- target: tenderingList -->
<table border="0" cellspacing="0" cellpadding="0" class="my-invest-table">
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
        <td>${item.investPro}</td>
        <td><span class="invest-em">${item.annlnterestRate}</span>%</td>
        <td>￥${item.tenderAmt}</td>
        <td>${item.deadline}个月</td>
        <td>${item.timeInfo}</td>
        <td>
            <span class="span-box"><span class="within" style="width: ${item.tenderProgress}%;"></span></span>
            <span class="invest-em">${item.tenderProgress}</span>%
        </td>
    </tr>
    <!-- /for -->
</table>
<!-- /target -->

<!-- target: endedList -->
<table border="0" cellspacing="0" cellpadding="0" class="my-invest-table">
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
        <td>${item.investPro}</td>
        <td><span class="invest-em">${item.annlnterestRate}</span>%</td>
        <td>￥${item.tenderAmt}</td>
        <td>${item.deadline}个月</td>
        <td>${item.timeInfo}</td>
        <td>${item.endTimeInfo}</td>
        <td>￥${item.totalRetAmt}</td>
        <td><span class="invest-em">￥${item.totalProfit}</span></td>
    </tr>
    <!-- /for -->
</table>
<!-- /target -->

<!-- target: tenderFailList -->
<table border="0" cellspacing="0" cellpadding="0" class="my-invest-table">
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
        <td>${item.investPro}</td>
        <td><span class="invest-em">${item.annlnterestRate}</span>%</td>
        <td>￥${item.tenderAmt}</td>
        <td>${item.deadline}个月</td>
        <td>${item.timeInfo}</td>
        <td>${item.failReason}</td>
    </tr>
    <!-- /for -->
</table>
<!-- /target -->