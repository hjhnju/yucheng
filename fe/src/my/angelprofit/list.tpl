<!--
 * @ignore
 * @file list  爱心收益
 * @author fanyy
 * @time 15-5-28
--> 

<!-- target: returnAngelProfitList -->
<table border="0" cellspacing="0" cellpadding="0" class="my-invest-table">
    <tr> 
        <th>投资项目</th>
        <th>年利率</th>
        <th>投标人</th>
        <th>投标时间</th>
        <th>期限</th>
        <th>收益分配(您自己)</th>
    </tr>
    <!-- for: ${list} as ${item} --> 
            <td><a href="${item.url}" class="invest-name">${item.investPro}</a></td>
            <td><span class="invest-em">${item.annlnterestRate}</span>%</td>
            <td>￥${item.tenderAmt}</td>
            <td>${item.deadline}</td>
            <td>${item.timeInfo}</td>
            <td>${item.angelmoney}</td>
        </tr>
    <!-- if: ${item.angel}-->
        <tr>
            <td class="my-heart-content" colspan="6"> 
                   <span class="">收益分配：
                    <span class="color-green">${item.selfrate}%</span>（您自己）${item.seltmoney} 元
                </span>
                <img class="angel-img" alt="" src="" />
                ${item.angelrate}%（爱心天使）${item.angelmoney} 元
                <span class=""><span class="color-pink"></span></span>
                </div>
            </td>
        </tr>
        <!-- /if -->  
    <!-- /for -->
</table>
<!-- /target -->

<!-- target: returnAngelProfitDetail -->
<span class="trangle-border"></span>
<span class="trangle-content"></span> 
<table border="0" cellspacing="0" cellpadding="0">
    <tr>
        <th>时间</th> 
        <th>待收收益</th> 
        <th>已收收益</th>
        <th>还款状态</th> 
    </tr>
    <!-- for: ${data.list} as ${item} -->
    <tr>
        <td>${item.timeInfo}</td>
        <td>${item.repossPrincipal}</td>
        <td>${item.repossProfit}</td>
        <!-- if: ${item.paymentStatus} == 0 --> 
        <td>${item.receProfit}</td>
        <td>未到期</td>
        <td>${item.punitive}</td>
        <!-- elif: ${item.paymentStatus} == 1 --> 
        <td><span class="invest-pass">${item.receProfit}</span></td>
        <td>按时还款</td>
        <td><span class="invest-pass">${item.punitive}</span></td>
        <!-- else --> 
        <td><span class="invest-em">${item.receProfit}</span></td>
        <td>已逾期</td> 
        <!-- /if -->
    </tr>
    <!-- /for -->
    <tr class="my-invest-plan-all">
        <td>总计</td> 
        <td>${data.total.repossProfit}</td> 
        <td>${data.total.receProfit}</td>
        <td></td> 
    </tr>
</table>
<!-- /target -->
