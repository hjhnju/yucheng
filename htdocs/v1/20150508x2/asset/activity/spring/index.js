define("activity/spring/index",["require","jquery","etpl","common/extra/jquery.marquee","common/header","common/ui/Dialog/Dialog","activity/spring/index.tpl"],function(require){function e(){i.compile(s),r.init(),o.init(),t()}function t(){n(".activity-list-content-scroll").kxbdMarquee({isEqual:!0,loop:0,direction:"up",scrollAmount:1,scrollDelay:20}),n(".rule-btn").click(function(){var e=n(this).attr("for");o.show({width:700,defaultTitle:!1,content:i.render(e)})})}var n=require("jquery"),i=require("etpl"),r=(require("common/extra/jquery.marquee"),require("common/header")),o=require("common/ui/Dialog/Dialog"),s=require("activity/spring/index.tpl");return{init:e}});