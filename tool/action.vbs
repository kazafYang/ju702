dim Url,id,code,trade_type,number,trade_buy_price,trade_sell_price
Url="http://ju71-n2.193b.starter-ca-central-1.openshiftapps.com/getdate.php" 
do
call machining_price
  if id >0 then 
    call action
  end if  
Loop Until ac=1

function machining_price
Html = getHTTPPage(Url)  
MyArray = Split(Html, ",", -1, 1)
      
  id=MyArray(0) '+Second(now)
  if id<>0 then
  code=MyArray(1)
  trade_type=MyArray(2)
  number=MyArray(3)
  trade_buy_price=MyArray(4)
  trade_sell_price=MyArray(5)
  end if
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


Function action
Const sTitle = "网上"   ' 查找窗口名称
Const nTimeOut = 5      ' 指定超时时间

Dim dtStart, bFind
dtStart = Now()
Do While DateDiff("s", dtStart, Now()) < nTimeOut
  WScript.Sleep 200
  If CreateObject("WScript.Shell").Appactivate(sTitle) Then
    bFind = True  ' 做标记-已找到
    Exit Do
  End If
Loop


If bFind Then
  'Msgbox "找到一个含有“百度”文字的窗口！耗时 " & DateDiff("s", dtStart, Now()) _
    '& " 秒。", vbSystemModal+vbInformation, WScript.ScriptName
if trade_type>=5 then
objShell.SendKeys "{F1}"
WScript.Sleep 100
objShell.SendKeys "{down 4}"
WScript.Sleep 200
objShell.SendKeys "{Enter}"
WScript.Sleep 100
objShell.SendKeys code
WScript.Sleep 300
objShell.SendKeys "{down 2}"
WScript.Sleep 100
objShell.SendKeys number*100
WScript.Sleep 200
objShell.SendKeys "{B}"
WScript.Sleep 200
objShell.SendKeys "{Y}"
WScript.Sleep 300
objShell.SendKeys "{Enter}"
	FUrl="http://ju71-n2.193b.starter-ca-central-1.openshiftapps.com/page/update.php?type=4&sql=update~trade_history~set~status=1~where~id="&id
	FHtml = getHTTPPageF(FUrl)
	msgbox FHtml
elseif trade_type<5 then
bjShell.SendKeys "{F2}"
WScript.Sleep 100
objShell.SendKeys "{down 4}"
WScript.Sleep 200
objShell.SendKeys "{Enter}"
WScript.Sleep 200
objShell.SendKeys code
WScript.Sleep 300
objShell.SendKeys "{down 2}"
WScript.Sleep 100
objShell.SendKeys number*100
WScript.Sleep 300
objShell.SendKeys "{S}"
WScript.Sleep 200
objShell.SendKeys "{Y}"
WScript.Sleep 300
objShell.SendKeys "{Enter}"
	FUrl="http://ju71-n2.193b.starter-ca-central-1.openshiftapps.com/page/update.php?type=4&sql=update~trade_history~set~status=1~where~id="&id
	FHtml = getHTTPPageF(FUrl)
	msgbox FHtml
else
objShell.SendKeys "{F5}"
end if 
Else
 ' Msgbox "找不到含有“百度”文字的窗口！耗时 " & DateDiff("s", dtStart, Now()) _
  '  & " 秒。", vbSystemModal+vbCritical, WScript.ScriptName
End If
'msgbox "结束"
End Function
            




function getHTTPPageF(FUrl)  
  dim Http  
  set Http=createobject("MSXML2.ServerXMLHTTP")  
  Http.open "GET",FUrl,false  
  Http.send()  
  if Http.readystate  <> 4 then   
      exit function  
  end if  
  getHTTPPageF=bytesToBSTR(Http.responseBody,"gb2312")  

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
