define(function () {return '<!--* @ignore* @file list* @author fanyy* @time 15-4-28--><!-- target: returnInvestList --><!-- for: ${list} as ${item} --><li onclick="window.location.href=\'/m/invest/detail?id=${item.id}\'">\t<div class="title">\t\t<span class="name-shi fl">\t\t\t <!-- if: ${item.type_id} == 1 -->实\t\t\t <!-- elif: ${item.type_id} == 2 -->信\t\t\t <!-- else -->机<!-- /if -->\t\t</span>\t    <span class="invest-title fl">${item.title}</span><span class="grade-star level-${item.level_name}" ></span>\t</div>\t<div class="content align-center flex-x f-hl" style="">\t\t<span class="lilv" >\t\t\t年利率<br/>\t\t\t  <span class="lilv-number">${item.interest}</span>%\t\t</span>\t\t<span class="price " style="">\t\t\t<span class="price-title flex-equal">\t\t\t\t<span class="total">金额(元)</span>\t\t\t    <span class="lilv-day">期限</span>\t\t\t    <span class="jindu">进度</span>\t\t\t</span>\t\t\t<span class="price-content flex-equal">\t\t\t\t<span class="total">${item.amount}</span>\t\t\t\t<span class="lilv-day">${item.duration_day}${item.duration_type}</span>\t\t\t\t<span class="jindu flex-center align-center">\t\t\t\t\t <span class="jindu-box"><span class="jindu-box-inline" style="width: ${item.percent}%;"></span></span><span class="jindu-number"><span class="lilv-number">${item.percent}</span><span class="lilv-bai">%</span></span>\t\t\t\t</span>\t\t\t</span>\t\t\t\t\t</span>\t\t<span class="status"><!-- if: ${item.status} == 2-->\t\t       <a class="status-biding" href="${webroot}/m/invest/detail?id=${item.id}">${item.status_name}</a>\t\t    <!-- elif: ${item.status} == 1 --><a class="status-biding start" href="${webroot}/m/invest/detail?id=${item.id}">${item.status_name}</a>\t\t    <!-- else-->\t\t       <a class="status-biding current" href="${webroot}/m/invest/detail?id=${item.id}">${item.status_name}</a>\t\t    <!--/if--></span>\t</div></li><!-- /for --><!-- /target -->';});