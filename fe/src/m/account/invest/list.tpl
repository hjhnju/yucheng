<!--
 * @ignore
 * @file list
 * @author fanyy 
 * @time 15-5-8
-->


<!-- target: returnMoneyList -->

<!-- for: ${list} as ${item} -->
<li>
	<div class="title"> 
	    <span class="invest-title fl">${item.title}</span>  
	</div>
	<div class="content align-center flex-x f-hl" style="">
		<span class="lilv" >
			年利率<br/> 
			  <span class="lilv-number">${item.interest}</span>%
		</span>
		<span class="price " style="">
			<span class="price-title flex-equal">
				<span class="total">投标金额<label class="count">￥${item.tenderAmt}</label></span>
			    <span class="lilv-day">已回款</span>
			    <span class="jindu">待回款</span>  
			</span>
			<span class="price-content flex-equal">
				<span class="total">投标时间
					<label class="count">￥${item.timeInfo}</label>
				</span>
				<span class="lilv-day">${item.haveBack}</span>
				<span class="jindu">
					 ${item.toBeBack}
				</span> 
			</span>
			
		</span>
		<span class="status">   
		       <a class="status-biding" href="${webroot}/m/account/payplan?id=${item.invest_id}">还款计划</a> 
         </span>

	</div>
</li>
<!-- /for --> 
<!-- /target -->

<!-- target: tenderingList -->

<!-- for: ${list} as ${item} -->
<li>
    <div class="title"> 
        <span class="invest-title fl">${item.title}</span>  
    </div>
    <div class="content align-center flex-x f-hl" style="">
        <span class="lilv" >
            年利率<br/> 
              <span class="lilv-number">${item.interest}</span>%
        </span>
        <span class="price " style="">
            <span class="price-title flex-equal">
               <span class="total">投标金额<label class="count">￥${item.tenderAmt}</label></span>
                <span class="lilv-day">期限</span>
                <span class="jindu">进度</span>  
            </span>
            <span class="price-content flex-equal">
               <span class="total">投标时间
                    <label class="count">￥${item.timeInfo}</label>
                </span>
                <span class="lilv-day">${item.duration_day}${item.duration_type}</span>
                <span class="jindu flex-center align-center">
                     <span class="jindu-box">
                        <span class="jindu-box-inline" style="width: ${item.percent}%;"></span>
                     </span>
                     <span class="jindu-number">
                            <span class="lilv-number">${item.percent}</span>
                            <span class="lilv-bai">%</span>
                     </span> 
                </span> 
            </span>
            
        </span>
        <span class="status">  
            <!-- if: ${item.status} == 2-->
               <a class="status-biding" href="${webroot}/m/invest/detail?id=${item.id}">${item.status_name}</a>
            <!-- elif: ${item.status} == 1 -->
               <a class="status-biding start" href="${webroot}/m/invest/detail?id=${item.id}">${item.status_name}</a>
            <!-- else--> 
               <a class="status-biding current" href="${webroot}/m/invest/detail?id=${item.id}">${item.status_name}</a>
            <!--/if-->
         </span>

    </div>
</li>
<!-- /for --> 
<!-- /target -->


<!-- target: returnMoneyDetail -->
  <table border="0" cellspacing="0" cellpadding="0" class="my-payplan-list"> 
    <!-- for: ${data.list} as ${item} -->
    <tr>
    	<td rowspan="2">${item.timeInfo}</td>
        <td>待收本金 <label class="num color-6">￥${item.repossPrincipal}</label></td> 
        <!-- if: ${item.paymentStatus} == 0 --> 
        <td>已收本金 <label class="num color-6">￥${item.recePrincipal}</label></td>
        <td>还款状态 <label class="color-6">未到期</label></td>
        <!-- elif: ${item.paymentStatus} == 1 --> 
        <td>已收本金 <label class="num color-green">￥${item.recePrincipal}</label></td>
        <td>还款状态 <label class="color-green">按时还款</label></td>
        <!-- else --> 
        <td>已收本金 <label class="num color-6">￥${item.recePrincipal}</label></td>
        <td>还款状态 <label class="color-red">已逾期</label></td>
        <!-- /if -->

       
        <td>待收收益 <label class="num color-6">￥${item.repossProfit}</label></td>
        <!-- if: ${item.paymentStatus} == 0 --> 
         <td>已收收益 <label class="num color-6">￥${item.receProfit}</label></td>
        <td>罚息 <label class="num color-6">￥${item.punitive}</label></td>
        <!-- elif: ${item.paymentStatus} == 1 -->  
         <td>已收收益 <label class="num color-green">￥${item.receProfit}</label></td>
         <td>罚息 <label class="num color-green">￥${item.punitive}</label></td>
        <!-- else --> 
         <td>已收收益 <label class="num color-red">￥${item.receProfit}</label></td>
         <td>罚息 <label class="num color-red">￥${item.punitive}</label></td>
        <!-- /if -->  
    </tr>
    <!-- /for -->
    <tr class="my-invest-plan-all">
        <td rowspan="2">总计</td> 
        <td>待收本金 <label class="num color-6">￥${item.repossPrincipal}</label></td> 
        <td>已收本金 <label class="num color-green">￥${item.recePrincipal}</label></td> 
        <td  rowspan="2">罚息 <label class="num color-6">￥${item.punitive}</label></td> 
        <td>待收收益 <label class="num color-6">￥${item.repossProfit}</label></td> 
        <td>已收收益 <label class="num color-green">￥${item.receProfit}</label></td> 
    </tr>
</table>
<!-- /target -->
 