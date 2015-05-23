<!--
 * @ignore
 * @file list 我的贷款
 * @author fanyy
 * @time 15-5-55
-->

<!-- target: returnApplyList -->
<ul> 
<!-- for: ${list} as ${item} --> 
    <li class="my-apply-item apply-item fl">
          <div class="apply-status  fl">
            ${item.apply.status}<br/>
            创建时间${item.apply.create_time}
        </div> 
        <div class="apply-info-left fl">
             申请人：${item.personal.realname}  <br/>   
             贷款金额：￥${item.apply.amount}         

        </div> 
        <div class="apply-info-right fl">
           学校名称： ${item.school.name}  <br/>    贷款期限：${item.apply.duration}  
        </div>
        <div class="apply-info-bottom fl">审核评估</div>
    </li>  
<!-- /for -->
</ul>
<!-- /target -->
 