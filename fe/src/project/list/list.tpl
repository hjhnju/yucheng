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
        <div class="school">
            <a href="/invest/detail?id=${item.id}" class="school-link">${item.title}</a>
        </div>
        <!--if: ${item.fresh} == 1-->
        <span class="school-new">新手专享</span>
        <!--/if-->
    </div>
    <div class="investlist-box-title-name grade">

        <span class="grade-star level-${item.level_name}"></span>
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
        <span class="lilv-number color-blank">${item.duration_day}</span>
        <span class="lilv-bai">${item.duration_type}</span>
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
        <!-- if: ${item.status} == 2-->
        <a class="status-biding" href="/invest/detail?id=${item.id}">${item.status_name}</a>
        <!-- else-->
        <span class="status-biding current">${item.status_name}</span>
        <!--/if-->
    </div>
</div>
<!--/for-->