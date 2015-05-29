<!--
 * @ignore
 * @file list
 * @author fanyy
 * @time 15-5-28
-->

<!-- target: returnAngelList -->
<ul> 
<!-- for: ${list} as ${item} --> 
	<li class="my-angel-item">
     <a href="${item.url}" target="_blank">
		   <img src="${item.url}" alt="" class="angel-img">
      </a>
     <span class="angel-name">天使：${item.angelname} </span>
     <span class="angel-code">专属码：${item.angelcode}</span>
	</li>
<!-- /for -->
    <li class="my-angel-item add-angel-li">
       <span class="add-angel">+</span>
       <span class="dis-block tl"> 
         输入天使码：<input type="text" name="angelcode" class="add-angel-input" />
         <a class="common-a-btn add-angel-btn">确定</a>
       </span>
      <span class="add-error color-red f12 tl dis-block"></span>
   </li>
</ul>
<!-- /target -->
