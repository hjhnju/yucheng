
var count,curcount,outstr 
//初始化 

outstr = ""; 

function setpage(capge,totalpage) 
{ 
  if(totalpage<=10){        //总页数小于十页 
      for (count=1;count<=totalpage;count++) 
          {    if(count!=cpage) 
              { 
              outstr = outstr + "<a href='javascript:void(0)' onclick='gotopage("+count+")'>"+count+"</a>"; 
              }else{ 
                  outstr = outstr + "--" + "<span class='current' >"+count+"</span>"; 
                  } 
          } 
      } 
  if(totalpage>10){        //总页数大于十页 
      if(parseInt((cpage-1)/10) == 0) 
          {             
          for (count=1;count<=10;count++) 
              {    if(count!=cpage) 
                  { 
                  outstr = outstr + "--"+ "<a href='javascript:void(0)' onclick='gotopage("+count+")'>"+count+"</a>";
                  }else{ 
                      outstr = outstr + "--" + "<span class='current'>"+count+"</span>"; 
                      } 
              } 
          outstr = outstr + "--" + "<a href='javascript:void(0)' onclick='gotopage("+count+")'> >>> </a>"; 
          } 
          else if(parseInt((cpage-1)/10) == parseInt(totalpage/10)) {     
          outstr = outstr + "--" +  "<a href='javascript:void(0)' onclick='gotopage("+(parseInt((cpage-1)/10)*10)+")'><<<</a>"; 
          for (count=parseInt(totalpage/10)*10+1;count<=totalpage;count++) 
              {    if(count!=cpage) 
                  { 
                  outstr = outstr + "--" + "<a href='javascript:void(0)' onclick='gotopage("+count+")'>"+count+"</a>"; 
                  }else{ 
                      outstr = outstr + "--" + "<span class='current'>"+count+"</span>"; 
                      } 
              } 
          } 
      else 
          {     
          outstr = outstr + "--" +  "<a href='javascript:void(0)' onclick='gotopage("+(parseInt((cpage-1)/10)*10)+")'><<<</a>"; 
          for (count=parseInt((cpage-1)/10)*10+1;count<=parseInt((cpage-1)/10)*10+10;count++) 
              {         
              if(count!=cpage) 
                  { 
                  outstr = outstr + "--" + "<a href='javascript:void(0)' onclick='gotopage("+count+")'>"+count+"</a>"; 
                  }else{ 
                      outstr = outstr + "--" + "<span class='current'>"+count+"</span>"; 
                      } 
              } 
          outstr = outstr + "--" +  "<a href='javascript:void(0)' onclick='gotopage("+count+")'> >>> </a>"; 
          } 
      }     
  document.getElementById("setpage").innerHTML = "<div id='setpage'><span id='info'>共"+totalpage+"页|第"+cpage+"页<\/span>" + outstr + "<\/div>"; 
  outstr = ""; 
  } 
function gotopage(target,webroot){
	    var url = location.protocol +"//"+ location.hostname + ":" +location.port + window.location.pathname + "?page=" + target;
	    window.location.href = url;
  }
