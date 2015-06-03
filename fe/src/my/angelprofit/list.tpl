<!--
 * @ignore
 * @file list  爱心收益
 * @author fanyy
 * @time 15-5-28
--> 


<!-- target: returnAngelProfitList -->
<ul>
<li class="my-invest-header">
    <span class="my-invest-title invest-name">投资项目</span>
    <span class="my-invest-title invest-rate">年利率</span>
    <span class="my-invest-title invest-money">投标人</span>
    <span class="my-invest-title invest-time">投标时间</span>
    <span class="my-invest-title invest-finish">期限</span>
    <span class="my-invest-title invest-profit">收益分配（您自己）</span>
    <span class="my-invest-title invest-op"></span>
</li>
<!-- for: ${list} as ${item} -->  
<li class="my-invest-item  my-heart-item">
    <div class="my-invest-content"> 
        <a href="/invest/detail/index?id=${item.proId}" class="my-invest-project invest-name">${item.investPro}</a>
        <span class="my-invest-project invest-rate"><span class="invest-em">${item.annlnterestRate}</span>%</span> 
        <span class="my-invest-project invest-money">${item.name}</span>
        <span class="my-invest-project invest-time">${item.timeInfo}</span>
        <span class="my-invest-project invest-finish">${item.deadline}</span>
        <span class="my-invest-project invest-profit">
            <span class="color-pink">${item.interest}%</span>￥${item.haveBack}
        </span>
        <!-- if: ${item.status} ==  5 -->
        <span class="my-invest-project view-plan"><span class="view-plan-btn" data-id="${item.invest_id}">查看收益详情</span></span>
        <!-- elif: ${item.status}== 4-->
          <span class="tc dis-block" data-id="${item.invest_id}">已满标</span>
        <!-- elif: ${item.status}== 2-->
          <span class="tc dis-block"  data-id="${item.invest_id}">投标中</span>
        <!-- elif: ${item.status}== 6-->
          <span class="tc dis-block"  data-id="${item.invest_id}">已完成</span>
        <!-- /if -->  
    </div> 
    <div class="my-invest-detail"></div>
</li>
<!-- /for -->
</ul>
<!-- /target -->


<!-- target: returnAngelProfitList-old -->
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
                <th>回款状态</th> 
            </tr>
            <!-- for: ${data.list} as ${item} -->
            <tr>
                <td>${item.timeInfo}</td> 
                <td>${item.repossProfit}</td>
                <!-- if: ${item.paymentStatus} == 0 --> 
                <td>${item.receProfit}</td>
                <td>未到期</td> 
                <!-- elif: ${item.paymentStatus} == 1 --> 
                <td><span class="invest-pass">${item.receProfit}</span></td>
                <td>按时还款</td> 
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
