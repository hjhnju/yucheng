<!--
 * @ignore
 * @file list
 * @author fanyy
 * @time 15-4-12
-->

<!-- target: returnInviteList --> 

<!-- for: ${list} as ${item} --> 
 <tr>
    <td class="text-align">{%$item.username%}({%$item.phone%})</td>
    <td>
        <!-- if: ${item.regist_progress} == 1 --> 
              已开通资金托管 
        <!-- else -->
              未开通资金托管
        <!-- /if --> 
    </td>
    <td  class="text-align"> 
        <!-- if: ${item.invested} == 1 --> 
             <span class="invest-icon-yes"></span>
        <!-- else -->
              <span class="invest-icon-no"></span>
        <!-- /if -->  
        
    </td>
    <td>   
           <!-- if: ${item.amount} > 0% --> 
             <span class="reward-count">￥&nbsp;{%$item.amount%}</span>
        <!-- else -->
                <span class="no-reward"> 暂无奖励</span>
        <!-- /if -->                             
    </td>
</tr>
<!-- /for --> 
<!-- /target -->
 