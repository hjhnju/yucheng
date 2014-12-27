<!-- target: list -->
<!--for: ${list} as ${item} -->
<div class="investlist-box-title">
    <div class="investlist-box-title-name name">
        <!--if: ${item.type_id} == 1-->
        <span class="name-shi">实</span>
        <!--elif: ${item.type_id} == 2-->
        <span class="name-shi">信</span>
        <!--else-->
        <span class="name-shi">机</span>
        <!--/if-->
        <span class="school">${item.title}</span>
    </div>
    <div class="investlist-box-title-name grade">
        <span class="grade-star all"></span>
        <span class="grade-star hale"></span>
        <span class="grade-star none"></span>
    </div>
    <div class="investlist-box-title-name lilv">
        <span class="lilv-number">${item.interest}</span>
        <span class="lilv-bai">%</span>
    </div>
    <div class="investlist-box-title-name price">
        <span class="lilv-number color-blank">${item.amount}</span>
        <span class="lilv-bai">元</span>
    </div>
    <div class="investlist-box-title-name time">
        <span class="lilv-number color-blank">${item.duration}</span>
        <span class="lilv-bai">个月</span>
    </div>
    <div class="investlist-box-title-name jindu">
        <div class="jindu-box">
            <div class="jindu-box-inline" style="width: ${item.percent}%;"></div>
        </div>
        <div class="jindu-number">
            <span class="lilv-number">${item.percent}</span>
            <span class="lilv-bai">%</span>
        </div>
    </div>
    <div class="investlist-box-title-name status">
        <!-- if: ${item.status} == 1-->
        <a class="status-biding" href="###">投标中</a>
        <!-- elif: ${item.status} == 2-->
        <span class="status-biding current">回款中</span>
        <!-- elif: ${item.status} == 3-->
        <span class="status-biding current">投资失败</span>
        <!-- else-->
        <span class="status-biding current">投资结束</span>
        <!--/if-->
    </div>
</div>
<!--/for-->