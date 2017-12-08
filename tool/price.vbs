dim new_price,ac,update_time,action_price
'action_price=8.72    'complet price
'action_now_price=8.7   'sell price
use_sell_count=171
hight_price=0
do

Url="http://hq.sinajs.cn/list=sz000012" 

call machining_price
Set objShell = CreateObject("Wscript.Shell")
WScript.Sleep 2000
'msgbox new_price
if new_price >= hight_price then
hight_price=new_price
action_now_price=new_price-(hight_price*0.7/100)
action_now_price=Round(action_now_price, 2)
'msgbox action_now_price
end if

if new_price<>0 and new_price < action_now_price then  '设置价格，设置价格必须低于当前价格，这样低于当前价格就会触发卖出指令
action_now_price=action_now_price-0.02
call action
WScript.Sleep 2500
msgbox "sell now!"
exit do
else
'msgbox "success"
objShell.SendKeys "{F5}"
WScript.Sleep 200
objShell.SendKeys "{F4}"

end if

Loop Until ac=1

function machining_price
Html = getHTTPPage(Url)  
MyArray = Split(Html, ",", -1, 1) 
'---------------------------------------------------------
zuo_price=MyArray(2)
new_price=MyArray(3)+0
update_time=MyArray(31)
'update_time=time()
End Function

function getHTTPPage(Url)  
  dim Http  
  set Http=createobject("MSXML2.XMLHTTP")  
  Http.open "GET",Url,false  
  Http.send()  
  if Http.readystate  <> 4 then   
      exit function  
  end if  
  getHTTPPage=bytesToBSTR(Http.responseBody,"gb2312")  

  set http=nothing
  
  if err.number  <> 0 then err.Clear   
end function  

Function BytesToBstr(body,Cset)  
      dim objstream  
      set objstream = CreateObject("adodb.stream")  
      objstream.Type = 1  
      objstream.Mode =3  
      objstream.Open  
      objstream.Write body 
      objstream.Position = 0  
      objstream.Type = 2  
      objstream.Charset = Cset  
      BytesToBstr = objstream.ReadText   
      objstream.Close  
      set objstream = nothing  
End Function  


Function action
WScript.Sleep 200
objShell.SendKeys "{F2}"
WScript.Sleep 200
objShell.SendKeys "000012"
WScript.Sleep 200
objShell.SendKeys "{down 1}"
objShell.SendKeys action_now_price
WScript.Sleep 300
objShell.SendKeys "{down 1}"
WScript.Sleep 100
objShell.SendKeys use_sell_count*100
WScript.Sleep 300
objShell.SendKeys "{Enter}"
WScript.Sleep 500
'msgbox "lai le"
objShell.SendKeys "{Enter}"
WScript.Sleep 300
objShell.SendKeys "{F4}" '回到查询页面
End Function
