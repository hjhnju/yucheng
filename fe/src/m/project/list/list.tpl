<!--
 * @ignore
 * @file list
 * @author fanyy 
 * @time 15-4-28
-->


<!-- target: returnInvestList -->

<!-- for: ${list} as ${item} -->
<li onclick="window.location.href='/m/invest/detail?id=${item.id}'">
	<div class="title">
		<span class="name-shi fl">
			 <!-- if: ${item.type_id} == 1 -->
              实
			 <!-- elif: ${item.type_id} == 2 -->
              信
			 <!-- else -->
              机
            <!-- /if -->  
		</span>
	    <span class="invest-title fl">${item.title}</span> 
       <span class="grade-star level-${item.level_name}" ></span>  
	</div>
	<div class="content align-center flex-x f-hl" style="">
		<span class="lilv" >
			年利率<br/> 
			  <span class="lilv-number">${item.interest}</span>%
		</span>
		<span class="price " style="">
			<span class="price-title flex-equal">
				<span class="total">金额(元)</span>
			    <span class="lilv-day">期限</span>
			    <span class="jindu">进度</span>  
			</span>
			<span class="price-content flex-equal">
				<span class="total">${item.amount}</span>
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


 