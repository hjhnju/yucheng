<!--
 * @ignore
 * @file list
 * @author fanyy
 * @time 15-4-12
-->

<!-- target: returnTicketList --> 

<!-- for: ${list} as ${item} -->
<div class="reward-ticket fl status${item.status}" ticketid="${item.ticketid}">
       <span class="ticket-name fl"> 
          <!-- if: ${item.ticket_type} == 1 --> 
                现金券
          <!-- /if -->
      </span>
       <span class="ticket-amount fl">
           <label>${item.amount}</label>元
           <span class="ticket-src">
                来源：${item.src}<br/>
                 有效期至：${item.valid_time}
           </span>
       </span>
       <span class="ticket-status ticket-status${item.status}"></span>
        
      
         <!-- if: ${item.status} != 2 --> 
           <div class="ticket-status-tips fl">
            <table >
             <tr>
                <td>
                  <!-- if: ${item.status} == 1 --> 
                      ${item.desc} 
                  <!-- /if -->
                   <!-- if: ${item.status} == 3 --> 
                        兑换时间：${item.pay_time} 
                   <!-- /if -->
                   <!-- if: ${item.status} == 4 --> 
                        过期时间：${item.valid_time} 
                   <!-- /if -->
               </td>
            </tr>
         </table>
         </div> 
          <!-- /if -->
</div>
<!-- /for -->
<!-- /target -->
 